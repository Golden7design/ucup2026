# Guide de déploiement sur Railway.app

Ce guide explique comment déployer l'application U-Cup Tournament sur Railway.app.

## Prérequis

1. Un compte Railway.app
2. Un dépôt GitHub avec le code source
3. Une base de données PostgreSQL configurée sur Railway

## Configuration du déploiement

### 1. Configuration des variables d'environnement

Dans votre projet Railway, configurez les variables d'environnement suivantes :

```
# Variables de base de données (automatiquement fournies par Railway PostgreSQL)
PGHOST=your-railway-postgresql-host
PGPORT=5432
PGDATABASE=your-database-name
PGUSER=your-username
PGPASSWORD=your-password

# Variables d'application
RAILWAY_PUBLIC_DOMAIN=your-app-url.railway.app
APP_NAME="U-Cup Tournament"
APP_ENV=production
APP_DEBUG=false

# Configuration de session et cache
SESSION_DRIVER=file
CACHE_DRIVER=file

# Configuration de queue
QUEUE_CONNECTION=sync

# Configuration de mail (optionnel)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="U-Cup Tournament"
```

### 2. Configuration du service

1. **Type de service** : Web Service
2. **Port** : 3000 (ou le port que vous préférez)
3. **Commande de démarrage** : Utilisez la commande par défaut du Dockerfile
4. **Buildpack** : Docker (utilisez le Dockerfile.railway)

### 3. Configuration de la base de données

1. Créez un service PostgreSQL sur Railway
2. Liez-le à votre service web
3. Les variables d'environnement PostgreSQL seront automatiquement configurées

### 4. Configuration du domaine

1. Configurez un domaine personnalisé si nécessaire
2. Mettez à jour la variable `RAILWAY_PUBLIC_DOMAIN` avec votre domaine

## Résolution des problèmes courants

### Timeout lors du build

Si vous rencontrez des timeouts lors du build :

1. **Utilisez le Dockerfile optimisé** : `Dockerfile.railway`
2. **Vérifiez votre .dockerignore** : Assurez-vous qu'il exclut les fichiers inutiles
3. **Réduisez la taille de votre dépôt** : Supprimez les fichiers volumineux inutiles
4. **Utilisez le cache** : Railway utilise automatiquement le cache Docker

### Problèmes de connexion à la base de données

1. Vérifiez que les variables d'environnement PostgreSQL sont correctement configurées
2. Assurez-vous que le service PostgreSQL est lié à votre service web
3. Vérifiez que `DB_SSLMODE=require` est configuré dans votre .env

### Problèmes de permissions

1. Assurez-vous que les répertoires `storage` et `bootstrap/cache` ont les bonnes permissions
2. Vérifiez que l'utilisateur `www-data` a les droits appropriés

## Optimisations pour Railway

### 1. Utilisation des extensions PHP pré-compilées

Le Dockerfile utilise les extensions PHP pré-compilées pour éviter les timeouts de compilation :

```dockerfile
php83-bcmath
php83-openssl
php83-pdo
php83-pdo_pgsql
php83-zip
php83-opcache
php83-intl
php83-tokenizer
php83-json
php83-dom
php83-simplexml
php83-xml
php83-xmlwriter
php83-mbstring
php83-curl
php83-fileinfo
php83-filter
```

### 2. Installation de Composer optimisée

```dockerfile
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts
```

### 3. Configuration des logs

Les logs sont configurés pour être accessibles via Supervisor :

- Logs Nginx : `/var/log/nginx.log` et `/var/log/nginx-error.log`
- Logs PHP-FPM : `/var/log/php-fpm.log` et `/var/log/php-fpm-error.log`
- Logs Laravel : `/var/log/laravel.log`

## Déploiement

1. **Poussez votre code** sur GitHub
2. **Créez un nouveau projet** sur Railway
3. **Sélectionnez votre dépôt** GitHub
4. **Configurez les variables d'environnement** comme indiqué ci-dessus
5. **Lancez le déploiement**
6. **Surveillez les logs** pour vérifier que tout se passe bien

## Post-déploiement

Après le déploiement :

1. Vérifiez que l'application est accessible
2. Testez les fonctionnalités principales
3. Configurez les tâches cron si nécessaire
4. Configurez les sauvegardes de la base de données

## Support

Si vous rencontrez des problèmes, consultez :

- La documentation officielle de Railway : https://docs.railway.app/
- La documentation Laravel : https://laravel.com/docs
- Les logs de votre application dans l'interface Railway
