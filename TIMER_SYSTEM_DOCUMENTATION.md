# Système de Chronomètre de Match - Documentation

## Architecture

Le chronomètre de match est **100% backend-driven**, comme dans les vraies applications de football (Sofascore, Flashscore, etc.).

```
┌─────────────────────────────────────────────────────────────────┐
│                        BACKEND                                   │
│  ┌─────────────┐    ┌─────────────────┐    ┌────────────────┐  │
│  │   Admin     │───▶│  LiveMatchCtrl  │───▶│  Broadcast     │  │
│  │   Panel     │    │  (start/pause)  │    │  (WebSocket)   │  │
│  └─────────────┘    └─────────────────┘    └───────┬────────┘  │
│                                                      │           │
│                                                      ▼           │
│  ┌─────────────┐    ┌─────────────────┐    ┌────────────────┐  │
│  │ Scheduler   │───▶│  BroadcastCmd    │───▶│  MatchStatus   │  │
│  │ (cron)      │    │  (every 1s)      │    │  OrStatsEvent  │  │
│  └─────────────┘    └─────────────────┘    └────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ WebSocket + Polling
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        FRONTEND                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  - Pas de timer JavaScript local                        │   │
│  │  - Affiche SEULEMENT ce que le backend envoie           │   │
│  │  - Écoute les broadcasts WebSocket                      │   │
│  │  - Fallback polling API toutes les 15-30 secondes       │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

## Fonctionnement

### 1. Administration (Backend)

| Action Admin | Effet sur le Timer |
|-------------|-------------------|
| Status → **LIVE** | `start_time = NOW()`, timer démarre |
| Status → **PAUSE** | `elapsed_time += (NOW - start_time)`, `timer_paused_at = NOW` |
| Status → **HALFTIME** | Timer figé (pause) |
| Status → **RESUME** | `start_time = NOW`, timer reprend |
| Status → **FINISHED** | Timer arrêté, `elapsed_time` finalisé |

### 2. Calcul du Temps Écoulé

```php
// MatchModel::getElapsedTime()
public function getElapsedTime()
{
    $base = (int) ($this->elapsed_time ?? 0);  // Temps accumulé

    if (!$this->start_time || $this->timer_paused_at || $this->status !== 'live') {
        return max(0, $base);  // Timer arrêté
    }

    // Timer en cours → base + temps écoulé depuis start_time
    $now = Carbon::now();
    $running = $now->diffInSeconds(Carbon::parse($this->start_time), false);
    return max(0, $base + $running);
}
```

### 3. Diffusion Temps Réel

#### Via WebSocket (principal)
- Le scheduler Laravel exécute `match:broadcast-timer` **toutes les secondes**
- Broadcast `MatchStatusOrStatsUpdated` avec `elapsed_time` et `formatted_time`
- Type `timer_tick` pour les mises à jour de timer simples

#### Via Polling (fallback)
- Frontend appelle `/api/matches/{id}/timer` toutes les 15-30 secondes
- Utilisé si WebSocket non disponible

## Fichiers Clés

| Fichier | Rôle |
|---------|------|
| `app/Models/MatchModel.php` | Logique de calcul du timer |
| `app/Console/Commands/BroadcastMatchTimer.php` | Commande de broadcast périodique |
| `bootstrap/app.php` | Configuration du scheduler |
| `app/Events/MatchStatusOrStatsUpdated.php` | Event broadcasté |
| `app/Http/Controllers/Api/MatchTimerController.php` | API endpoint timer |
| `resources/views/frontend/matches/show.blade.php` | Frontend (timer display uniquement) |

## Configuration du Scheduler

Dans `bootstrap/app.php`:
```php
->withSchedule(function (Schedule $schedule) {
    $schedule->command('match:broadcast-timer')
        ->everySecond()
        ->withoutOverlapping()
        ->runInBackground();
})
```

## Commandes Artisan

```bash
# Broadcast timer pour tous les matchs en direct
php artisan match:broadcast-timer

# Broadcast timer pour un match spécifique
php artisan match:broadcast-timer --match-id=1

# Tester le timer d'un match via API
curl http://localhost/api/matches/1/timer
```

## Réponse API Timer

```json
{
  "success": true,
  "status": "live",
  "label": null,
  "is_paused": false,
  "elapsed_time": 923,
  "formatted_time": "15:23",
  "start_time": "2026-02-10T13:50:00Z",
  "server_time": "2026-02-10T14:05:23Z"
}
```

## Dépannage

### Le timer ne s'actualise pas
1. Vérifier que le scheduler Laravel tourne: `php artisan schedule:run`
2. Vérifier les logs: `storage/logs/laravel.log`
3. Tester la commande: `php artisan match:broadcast-timer --match-id=X`

### WebSocket non disponible
Le frontend utilise automatiquement le polling comme fallback toutes les 15 secondes.

### Timer affiche une valeur incorrecte
- Vérifier `start_time` et `timer_paused_at` dans la base de données
- Le timer peut avoir besoin d'être réinitialisé si le status est `scheduled`
