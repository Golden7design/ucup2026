# 🚀 Guide de Déploiement Frontend/Backend Séparé
# U-Cup Tournament

Ce guide vous explique comment déployer votre application U-Cup Tournament avec une architecture moderne :
- **Frontend** : Vercel (Next.js)
- **Backend** : Render (Laravel)

## 📋 Prérequis

1. **Compte Vercel** (gratuit)
2. **Compte Render** (gratuit)
3. **Dépôt Git** (GitHub/GitLab/Bitbucket)
4. **Projet Laravel** configuré
5. **Frontend Next.js** (si vous en avez un)

## 🛠️ Configuration du Backend (Render)

### 1. Préparer le backend Laravel

#### a. Installer les dépendances
```bash
composer install --no-dev --optimize-autoloader
```

#### b. Configurer CORS
Créez un middleware CORS (déjà fait : `app/Http/Middleware/Cors.php`)

#### c. Enregistrer le middleware
Ajoutez le middleware dans `app/Http/Kernel.php` :
```php
protected $middlewareAliases = [
    // ... autres middlewares
    'cors' => \App\Http\Middleware\Cors::class,
];
```

#### d. Appliquer le middleware
Dans `app/Http/Kernel.php`, ajoutez le middleware global :
```php
protected $middleware = [
    // ... autres middlewares
    \App\Http\Middleware\Cors::class,
];
```

### 2. Configurer les routes API

Dans `routes/api.php` :
```php
Route::middleware(['cors'])->group(function () {
    // Vos routes API existantes
    Route::get('/matches', [MatchController::class, 'index']);
    Route::get('/teams', [TeamController::class, 'index']);
    // ... autres routes
});
```

### 3. Configurer les variables d'environnement

Créez un fichier `.env.render` :
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://u-cup-backend.onrender.com
FRONTEND_URL=https://u-cup-tournament.vercel.app

DB_CONNECTION=pgsql
# La base de données sera configurée automatiquement par Render

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

### 4. Déployer sur Render

1. **Créez un nouveau service Web** sur Render
2. **Connectez votre dépôt Git**
3. **Utilisez le fichier `render.yaml`** (déjà créé)
4. **Déployez**

## 🖥️ Configuration du Frontend (Vercel)

### 1. Configurer les appels API

Dans votre frontend, utilisez la configuration API (déjà créée : `resources/js/config/api.js`)

### 2. Configurer Vercel

Créez un fichier `vercel.json` (déjà fait) avec :
```json
{
    "version": 2,
    "builds": [{"src": "package.json", "use": "@vercel/next"}],
    "routes": [
        {"src": "/api/(.*)", "dest": "https://u-cup-backend.onrender.com/api/$1"}
    ]
}
```

### 3. Configurer les variables d'environnement

Dans les paramètres de votre projet Vercel :
```
NEXT_PUBLIC_API_URL=https://u-cup-backend.onrender.com/api
```

### 4. Déployer sur Vercel

1. **Installez l'extension Vercel** pour GitHub/GitLab
2. **Importez votre projet**
3. **Déployez**

## 🔄 Configuration des Requêtes CORS

### 1. Backend (Laravel)

Dans `app/Http/Middleware/Cors.php` :
```php
$response->headers->set('Access-Control-Allow-Origin', env('FRONTEND_URL', 'https://u-cup-tournament.vercel.app'));
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
$response->headers->set('Access-Control-Allow-Credentials', 'true');
```

### 2. Frontend (Next.js)

Dans vos appels API :
```javascript
const response = await fetch('https://u-cup-backend.onrender.com/api/matches', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    credentials: 'include' // Pour les cookies
});
```

## 📊 Gestion des Sessions

### 1. Configurer les cookies

Dans `config/session.php` :
```php
'domain' => env('SESSION_DOMAIN', '.vercel.app'),
'secure' => true, // HTTPS seulement
'same_site' => 'lax',
```

### 2. Configurer Sanctum (si utilisé)

Dans `config/sanctum.php` :
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'u-cup-tournament.vercel.app')),
```

## 🚀 Étapes de Déploiement Complètes

### 1. Déployer le Backend

```bash
# Préparer le backend
git add .
git commit -m "Prêt pour le déploiement backend"
git push origin main

# Sur Render :
# - Créez un nouveau service Web
# - Sélectionnez votre dépôt
# - Utilisez le fichier render.yaml
# - Déployez
```

### 2. Déployer le Frontend

```bash
# Préparer le frontend
npm install
npm run build
git add .
git commit -m "Prêt pour le déploiement frontend"
git push origin main

# Sur Vercel :
# - Importez votre projet
# - Configurez les variables d'environnement
# - Déployez
```

### 3. Tester la connexion

```bash
# Tester le backend
curl https://u-cup-backend.onrender.com/api/matches

# Tester le frontend
curl https://u-cup-tournament.vercel.app
```

## ⚠️ Problèmes Courants et Solutions

### 1. Erreur CORS

**Solution** :
- Vérifiez que le middleware CORS est bien enregistré
- Assurez-vous que `FRONTEND_URL` est correct dans `.env`
- Vérifiez que les en-têtes sont bien envoyés

### 2. Problèmes de session

**Solution** :
- Configurez correctement `SESSION_DOMAIN`
- Utilisez `credentials: 'include'` dans les requêtes frontend
- Assurez-vous que le backend et le frontend utilisent HTTPS

### 3. Requêtes bloquées

**Solution** :
- Vérifiez que le backend répond correctement
- Testez les endpoints avec Postman ou curl
- Vérifiez les logs sur Render

## 💡 Optimisations

### 1. Cache backend

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Optimisation frontend

```javascript
// Utilisez React.Query pour le cache
import { useQuery } from 'react-query';

const { data } = useQuery('matches', () => 
    fetch('https://u-cup-backend.onrender.com/api/matches').then(res => res.json())
);
```

### 3. CDN pour les assets

Utilisez Vercel Edge Network pour servir les assets statiques

## 🎉 Résultat Final

- **Frontend** : `https://u-cup-tournament.vercel.app`
- **Backend** : `https://u-cup-backend.onrender.com/api`
- **Base de données** : PostgreSQL gratuit sur Render

## 📚 Ressources

- [Documentation Vercel](https://vercel.com/docs)
- [Documentation Render](https://render.com/docs)
- [Laravel CORS](https://laravel.com/docs/http-client#cors)
- [Next.js API Routes](https://nextjs.org/docs/api-routes)

Si vous avez des questions ou rencontrez des problèmes, je suis là pour vous aider ! 😊