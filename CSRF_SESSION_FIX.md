# Solution pour les problèmes CSRF (Erreur 419) et de Sessions

## Problème

L'erreur 419 (CSRF Token Mismatch) et les problèmes de sessions peuvent survenir pour plusieurs raisons :

1. **Problèmes de domaine** : Lorsque `SESSION_DOMAIN` est défini sur `localhost`, cela peut causer des problèmes avec les cookies.
2. **Problèmes CSRF** : Le token CSRF n'est pas correctement transmis ou validé.
3. **Problèmes de configuration** : Les paramètres de session et CSRF ne sont pas optimisés pour le développement local.

## Solutions implémentées

### 1. Middleware personnalisé

Un middleware `HandleCsrfAndSessions` a été créé pour :
- Corriger les problèmes de domaine de session pour localhost
- Ajouter le token CSRF aux en-têtes des réponses AJAX
- Garantir que les cookies de session fonctionnent correctement

### 2. Service Provider

Un service provider `CsrfSessionServiceProvider` a été créé pour :
- Enregistrer le middleware personnalisé
- Charger les configurations personnalisées
- Ajouter des directives Blade pour les meta tags CSRF

### 3. Configurations personnalisées

Deux fichiers de configuration ont été ajoutés :
- `config/csrf-sessions-fix.php` : Configuration pour les corrections CSRF et sessions
- `config/filament-csrf-fix.php` : Configuration spécifique à Filament

## Comment utiliser

### Pour le développement local

1. **Assurez-vous que votre fichier `.env` contient** :
```env
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
CSRF_COOKIE_SECURE=false
```

2. **Videz le cache de configuration** :
```bash
php artisan config:clear
php artisan cache:clear
```

3. **Redémarrez votre serveur** :
```bash
php artisan serve
```

### Pour la production

1. **Configurez votre fichier `.env` pour la production** :
```env
SESSION_DOMAIN=votre-domaine.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
CSRF_COOKIE_SECURE=true
```

2. **Videz le cache** :
```bash
php artisan config:clear
php artisan cache:clear
```

## Synchronisation des données entre local et production

### Commandes disponibles

1. **Exporter les données** :
```bash
php artisan data:export
```
Cela exporte les données des tables principales dans des fichiers JSON dans `storage/app/exports/`.

2. **Importer les données** :
```bash
php artisan data:import
```
Cela importe les données à partir des fichiers JSON dans `storage/app/exports/`.

3. **Synchroniser les données** :
```bash
php artisan data:sync local production
```
Cela exporte les données locales pour qu'elles puissent être importées en production.

### Procédure de synchronisation

**De local vers production** :
1. Exécutez `php artisan data:export` en local
2. Téléchargez les fichiers de `storage/app/exports/` vers le serveur de production
3. Exécutez `php artisan data:import` en production

**De production vers local** :
1. Exécutez `php artisan data:export` en production
2. Téléchargez les fichiers de `storage/app/exports/` vers votre machine locale
3. Exécutez `php artisan data:import` en local

## Fichiers modifiés

- `bootstrap/app.php` : Ajout du middleware `HandleCsrfAndSessions`
- `bootstrap/providers.php` : Ajout du service provider `CsrfSessionServiceProvider`
- `.env.example` : Mise à jour avec la configuration PostgreSQL

## Fichiers ajoutés

- `app/Http/Middleware/HandleCsrfAndSessions.php` : Middleware personnalisé
- `app/Http/Middleware/TrustProxies.php` : Middleware pour les proxies
- `app/Providers/CsrfSessionServiceProvider.php` : Service provider
- `config/csrf-sessions-fix.php` : Configuration CSRF/Sessions
- `config/filament-csrf-fix.php` : Configuration Filament
- `CSRF_SESSION_FIX.md` : Ce fichier de documentation

## Prochaines étapes

1. Testez l'application localement pour vérifier que l'erreur 419 est résolue
2. Utilisez les commandes de synchronisation pour aligner vos données locales et de production
3. Déployez les modifications en production si tout fonctionne correctement