# Guide de déploiement sur Vercel pour U-Cup Tournament

## 🚀 Préparation du déploiement

Ce guide vous explique comment déployer votre application Laravel + React/Inertia.js sur Vercel.

## 📋 Configuration requise

1. **Compte Vercel** : [https://vercel.com](https://vercel.com)
2. **Vercel CLI** : `npm install -g vercel`
3. **PHP 8.2+** installé localement
4. **Composer** installé localement

## 🔧 Configuration effectuée

### Fichiers modifiés/créés :

1. **`vercel.json`** : Configuration principale pour Vercel
   - Configuration pour les fichiers statiques (frontend)
   - Configuration pour les Serverless Functions PHP (backend)
   - Routage des requêtes API vers le backend Laravel
   - En-têtes de sécurité

2. **`.env.vercel`** : Fichier d'environnement spécifique à Vercel
   - Variables d'environnement par défaut pour Vercel
   - Configuration CORS pour votre domaine Vercel

3. **`vite.config.ts`** : Configuration Vite adaptée pour Vercel
   - Configuration de build pour la production
   - Chemin de sortie adapté (`public/build`)

4. **`package.json`** : Scripts de build mis à jour
   - Ajout de `build:vercel` pour la production
   - Ajout de `deploy:vercel` pour le déploiement

5. **`api/index.php`** : Point d'entrée pour les Serverless Functions
   - Gestion des requêtes API via Laravel
   - Intégration avec les fonctions serverless de Vercel

6. **`deploy-to-vercel.sh`** : Script de déploiement automatisé
   - Nettoyage des caches
   - Configuration de l'environnement
   - Build des assets
   - Préparation des fichiers statiques

## 🛠️ Étapes de déploiement

### 1. Préparation locale

```bash
# Installer les dépendances
composer install --optimize-autoloader --no-dev
npm install

# Générer la clé d'application
php artisan key:generate

# Configurer l'environnement
cp .env.vercel .env

# Build des assets
npm run build:vercel
```

### 2. Configuration des variables d'environnement

Dans le tableau de bord Vercel, configurez les variables suivantes :

- `APP_KEY` : Générée par `php artisan key:generate --show`
- `APP_URL` : `https://votre-projet.vercel.app`
- `CORS_ALLOWED_ORIGINS` : `https://votre-projet.vercel.app`

Pour la base de données (si vous utilisez une base de données) :
- `DB_CONNECTION` : `pgsql` (ou `mysql`)
- `DB_HOST` : Votre hôte de base de données
- `DB_PORT` : Port de la base de données
- `DB_DATABASE` : Nom de la base de données
- `DB_USERNAME` : Nom d'utilisateur
- `DB_PASSWORD` : Mot de passe

### 3. Déploiement

```bash
# Se connecter à Vercel
vercel login

# Déployer le projet
vercel --prod
```

## ⚠️ Points d'attention

### 1. Base de données
Vercel ne fournit pas de base de données. Vous devez utiliser un service externe comme :
- **Render** (recommandé, déjà configuré dans votre projet)
- **Aiven**
- **Supabase**
- **PlanetScale**
- **Neon**

### 2. Stockage de fichiers
Pour le stockage de fichiers (images, uploads), utilisez :
- **AWS S3**
- **Cloudinary**
- **Vercel Blob Storage**

### 3. Sessions et cache
Configurez le driver de session et de cache :
- `SESSION_DRIVER=file` (par défaut)
- `CACHE_DRIVER=file` (par défaut)

Pour de meilleures performances, vous pouvez utiliser Redis :
- **Upstash** (Redis serverless compatible avec Vercel)

### 4. Files d'attente
Vercel a des limitations pour les tâches longues. Pour les queues :
- Utilisez **Redis** avec Upstash
- Ou un service externe comme **Render**

## 🔄 Configuration CORS

Le middleware CORS est déjà configuré dans `app/Http/Middleware/Cors.php` et utilise la variable d'environnement `CORS_ALLOWED_ORIGINS`.

Assurez-vous de configurer cette variable avec votre domaine Vercel :
```
CORS_ALLOWED_ORIGINS=https://votre-projet.vercel.app
```

## 🧪 Test local avant déploiement

Pour tester localement avant de déployer :

```bash
# Lancer le serveur de développement
php artisan serve

# Dans un autre terminal, lancer Vite
npm run dev

# Tester les requêtes API
curl http://localhost:8000/api/matches
```

## 📚 Documentation supplémentaire

- [Documentation Vercel pour PHP](https://vercel.com/docs/concepts/functions/serverless-functions/runtimes/php)
- [Laravel sur Vercel](https://vercel.com/guides/deploy-laravel-application-vercel)
- [Configuration des Serverless Functions](https://vercel.com/docs/concepts/functions)

## 🎯 Prochaines étapes

1. **Configurer votre base de données externe**
2. **Mettre à jour les variables d'environnement** dans le tableau de bord Vercel
3. **Tester le déploiement** avec `vercel --prod`
4. **Configurer un domaine personnalisé** si nécessaire
5. **Mettre en place la surveillance** avec les outils de Vercel

Si vous avez des questions ou rencontrez des problèmes, consultez la documentation officielle ou contactez le support Vercel.