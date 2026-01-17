# 🔧 Code Snippets - À Ajouter à Vos Contrôleurs

## 1️⃣ Importer les modèles nécessaires

Ajoutez à tous vos contrôleurs Frontend:

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Models\MatchModel;
use App\Models\Standing;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
```

---

## 2️⃣ HomeController::index() - Code complet

Remplacez la méthode `index()` existante par:

```php
public function index()
{
    // Matchs en direct
    $liveMatches = MatchModel::query()
        ->where('status', 'live')
        ->with(['team_a', 'team_b'])
        ->latest('started_at')
        ->take(5)
        ->get()
        ->map(fn($match) => [
            'id' => $match->id,
            'team_a_id' => $match->team_a_id,
            'team_b_id' => $match->team_b_id,
            'score_a' => $match->score_a ?? 0,
            'score_b' => $match->score_b ?? 0,
            'status' => $match->status,
            'started_at' => $match->started_at?->toIso8601String(),
            'team_a' => [
                'name' => $match->team_a?->name ?? 'Équipe A',
                'logo' => $match->team_a?->logo,
            ],
            'team_b' => [
                'name' => $match->team_b?->name ?? 'Équipe B',
                'logo' => $match->team_b?->logo,
            ],
        ]);

    // Classement
    $standings = Standing::query()
        ->with('team')
        ->orderByDesc('points')
        ->orderByDesc('goal_difference')
        ->take(8)
        ->get()
        ->map(fn($s) => [
            'id' => $s->id,
            'team_id' => $s->team_id,
            'team' => [
                'name' => $s->team->name,
                'logo' => $s->team->logo,
            ],
            'played' => $s->played,
            'won' => $s->won,
            'drawn' => $s->drawn,
            'lost' => $s->lost,
            'goals_for' => $s->goals_for,
            'goals_against' => $s->goals_against,
            'goal_difference' => $s->goal_difference,
            'points' => $s->points,
        ]);

    // Top 8 buteurs
    $topScorers = Player::query()
        ->select('id', 'name', 'goals', 'assists')
        ->orderByDesc('goals')
        ->take(8)
        ->get()
        ->map(fn($player) => [
            'id' => $player->id,
            'player_id' => $player->id,
            'player' => ['name' => $player->name],
            'goals' => $player->goals ?? 0,
            'assists' => $player->assists ?? 0,
            'cards' => ($player->yellow_cards ?? 0) + ($player->red_cards ?? 0),
        ]);

    // Statistiques globales
    $stats = [
        'totalMatches' => MatchModel::count(),
        'liveMatches' => MatchModel::where('status', 'live')->count(),
        'teams' => Team::count(),
        'players' => Player::count(),
    ];

    return Inertia::render('dashboard', [
        'liveMatches' => $liveMatches,
        'standings' => $standings,
        'topScorers' => $topScorers,
        'stats' => $stats,
    ]);
}
```

---

## 2️⃣ MatchController - Ajouter les deux méthodes

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Team;
use Inertia\Inertia;

class MatchController extends Controller
{
    /**
     * Affiche la liste de tous les matchs
     */
    public function index()
    {
        $matches = MatchModel::query()
            ->with(['team_a', 'team_b'])
            ->orderByDesc('started_at')
            ->get()
            ->map(fn($match) => [
                'id' => $match->id,
                'team_a_id' => $match->team_a_id,
                'team_b_id' => $match->team_b_id,
                'score_a' => $match->score_a ?? 0,
                'score_b' => $match->score_b ?? 0,
                'status' => $match->status,
                'started_at' => $match->started_at?->toIso8601String(),
                'location' => $match->location,
                'team_a' => [
                    'name' => $match->team_a?->name,
                    'logo' => $match->team_a?->logo,
                ],
                'team_b' => [
                    'name' => $match->team_b?->name,
                    'logo' => $match->team_b?->logo,
                ],
            ]);

        $teams = Team::select('id', 'name')->get();

        return Inertia::render('matches/Index', [
            'matches' => $matches,
            'teams' => $teams,
            'breadcrumbs' => [
                ['title' => 'Accueil', 'href' => route('home')],
                ['title' => 'Matchs', 'href' => route('matches.index')],
            ],
        ]);
    }

    /**
     * Affiche un match spécifique (existant)
     */
    public function show(MatchModel $match)
    {
        return Inertia::render('matches/Show', [
            'match' => $match->load(['team_a', 'team_b', 'events']),
        ]);
    }

    /**
     * Affiche les matchs en direct
     */
    public function live()
    {
        $matches = MatchModel::query()
            ->where('status', 'live')
            ->with(['team_a', 'team_b'])
            ->orderByDesc('started_at')
            ->get();

        return Inertia::render('matches/Live', [
            'matches' => $matches,
        ]);
    }
}
```

---

## 3️⃣ PlayerController - Code complet

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Inertia\Inertia;

class PlayerController extends Controller
{
    /**
     * Affiche la liste de tous les joueurs
     */
    public function index()
    {
        $players = Player::query()
            ->with(['team', 'university'])
            ->get()
            ->map(fn($player) => [
                'id' => $player->id,
                'name' => $player->name,
                'number' => $player->number,
                'position' => $player->position,
                'photo' => $player->photo,
                'team_id' => $player->team_id,
                'team' => [
                    'name' => $player->team?->name,
                    'logo' => $player->team?->logo,
                ],
                'stats' => [
                    'goals' => $player->goals ?? 0,
                    'assists' => $player->assists ?? 0,
                    'matches_played' => $player->matches_played ?? 0,
                ],
            ]);

        $teams = Team::select('id', 'name')->get();

        return Inertia::render('players/Index', [
            'players' => $players,
            'teams' => $teams,
            'breadcrumbs' => [
                ['title' => 'Accueil', 'href' => route('home')],
                ['title' => 'Joueurs', 'href' => route('players.index')],
            ],
        ]);
    }

    /**
     * Affiche le profil détaillé d'un joueur
     */
    public function show(Player $player)
    {
        $stats = [
            'goals' => $player->goals ?? 0,
            'assists' => $player->assists ?? 0,
            'yellow_cards' => $player->yellow_cards ?? 0,
            'red_cards' => $player->red_cards ?? 0,
            'matches_played' => $player->matches_played ?? 0,
            'minutes_played' => $player->minutes_played ?? 0,
            'passes_completed' => $player->passes_completed ?? 0,
            'pass_accuracy' => $player->pass_accuracy ?? 0,
            'tackles' => $player->tackles ?? 0,
            'interceptions' => $player->interceptions ?? 0,
            'fouls_committed' => $player->fouls_committed ?? 0,
            'fouls_suffered' => $player->fouls_suffered ?? 0,
            'shots_on_target' => $player->shots_on_target ?? 0,
            'dribbles' => $player->dribbles ?? 0,
        ];

        return Inertia::render('players/Show', [
            'player' => $player->load(['team', 'university']),
            'stats' => $stats,
            'performanceHistory' => [], // À connecter à votre data
            'breadcrumbs' => [
                ['title' => 'Joueurs', 'href' => route('players.index')],
                ['title' => $player->name],
            ],
        ]);
    }

    /**
     * Affiche le classement des buteurs
     */
    public function leaderboard()
    {
        $topScorers = Player::query()
            ->select('id', 'name', 'number', 'goals', 'assists', 'team_id')
            ->with('team')
            ->orderByDesc('goals')
            ->get();

        return Inertia::render('players/Leaderboard', [
            'players' => $topScorers,
        ]);
    }
}
```

---

## 4️⃣ StandingController - Code complet

```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Standing;
use Inertia\Inertia;

class StandingController extends Controller
{
    /**
     * Affiche le classement complet
     */
    public function index()
    {
        $standings = Standing::query()
            ->with('team')
            ->orderByDesc('points')
            ->orderByDesc('goal_difference')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'team_id' => $s->team_id,
                'team' => [
                    'name' => $s->team->name,
                    'logo' => $s->team->logo,
                ],
                'played' => $s->played,
                'won' => $s->won,
                'drawn' => $s->drawn,
                'lost' => $s->lost,
                'goals_for' => $s->goals_for,
                'goals_against' => $s->goals_against,
                'goal_difference' => $s->goal_difference,
                'points' => $s->points,
            ]);

        return Inertia::render('standings/Index', [
            'standings' => $standings,
            'breadcrumbs' => [
                ['title' => 'Accueil', 'href' => route('home')],
                ['title' => 'Classement', 'href' => route('standings.index')],
            ],
        ]);
    }

    /**
     * Affiche le classement d'un groupe spécifique
     */
    public function group($group)
    {
        $standings = Standing::query()
            ->with('team')
            ->where('group', $group)
            ->orderByDesc('points')
            ->orderByDesc('goal_difference')
            ->get();

        return Inertia::render('standings/Group', [
            'standings' => $standings,
            'group' => $group,
        ]);
    }
}
```

---

## 5️⃣ Routes à vérifier (routes/web.php)

Assurez-vous que ces routes existent:

```php
// Matchs
Route::prefix('matches')->name('matches.')->group(function () {
    Route::get('/', [MatchController::class, 'index'])->name('index');
    Route::get('/live', [MatchController::class, 'live'])->name('live');
    Route::get('/{match}', [MatchController::class, 'show'])->name('show');
});

// Joueurs
Route::prefix('players')->name('players.')->group(function () {
    Route::get('/', [PlayerController::class, 'index'])->name('index');
    Route::get('/leaderboard', [PlayerController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/{player}', [PlayerController::class, 'show'])->name('show');
});

// Classement
Route::prefix('standings')->name('standings.')->group(function () {
    Route::get('/', [StandingController::class, 'index'])->name('index');
    Route::get('/{group}', [StandingController::class, 'group'])->name('group');
});
```

---

## ✅ Checklist d'Intégration

- [ ] Copier le code des contrôleurs
- [ ] Vérifier les routes
- [ ] `npm install` (pour les nouvelles dépendances)
- [ ] `npm run dev`
- [ ] Tester chaque page dans le navigateur
- [ ] Vérifier que les données s'affichent correctement
- [ ] `npm run build`
- [ ] Déployer

---

## 🐛 Troubleshooting

### "Undefined method" erreur
→ Vérifiez que vous importez bien le modèle en haut du fichier

### Page blanche
→ Vérifiez la console du navigateur (F12 > Console)

### Données manquantes
→ Vérifiez que vos colonnes de base de données existent (`goals`, `assists`, etc.)

### Animations ne fonctionnent pas
→ Assurez-vous que `npm install` a été exécuté

---

Vous êtes prêt! 🚀
