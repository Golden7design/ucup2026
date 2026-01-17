#!/bin/sh
set -e

echo "Starting U-CUP Initialization Script for Railway..."

# Le WORKDIR est /app.

# 1. APPLICATION DES VARIABLES DE SÉCURITÉ ET DE CONNEXION POUR RAILWAY
echo "Copying and configuring environment file for Railway..."

# CRITIQUE: Copie le contenu de .env.railway vers .env
cat /app/.env.railway > /app/.env
# CRITIQUE: Ajoute une ligne vide de sécurité
echo "" >> /app/.env 

# Forcer le mode SSL pour PostgreSQL (requis par Railway) et l'APP_URL
echo "DB_SSLMODE=require" >> /app/.env
echo "APP_URL=${RAILWAY_PUBLIC_DOMAIN}" >> /app/.env

# Générer la clé d'application si elle est manquante ou vide.
# IMPORTANT : Utilisation de '#' comme délimiteur pour éviter les conflits avec le contenu de la clé.
APP_KEY_LINE=$(grep "^APP_KEY=" /app/.env)

if [ -z "$APP_KEY_LINE" ] || [ "$(echo $APP_KEY_LINE | cut -d '=' -f 2)" = "" ]; then
    echo "Generating new application key..."
    APP_KEY=$(php artisan key:generate --show)
    
    if [ -z "$APP_KEY_LINE" ]; then
        # La clé n'existe pas, on l'ajoute
        echo "APP_KEY=$APP_KEY" >> /app/.env
    else
        # La clé existe mais est vide, on la remplace
        # CORRECTION SED: Utilisation de '#' comme délimiteur pour éviter les conflits avec les caractères de la clé.
        # sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /app/.env
        sed -i "s#^APP_KEY=.*#APP_KEY=$APP_KEY#" /app/.env
    fi
fi

# 2. CACHE ET MIGRATIONS
echo "Clearing and caching configuration..."
# Assurez-vous que l'extension 'intl' est chargée ici (corrigé dans le Dockerfile v3)
# Utiliser --no-interaction pour éviter les timeouts
php artisan config:clear --no-interaction
php artisan config:cache --no-interaction
php artisan view:cache --no-interaction
php artisan route:cache --no-interaction

# 3. Exécuter la réinitialisation de la base de données et le seeding
echo "Exécution des migrations et seeding..."
# Utiliser --no-interaction pour éviter les timeouts
php artisan migrate:fresh --seed --force --no-interaction

# 4. CONFIGURATION NGINX DYNAMIQUE POUR RENDER
echo "Setting Nginx listener port to $PORT..."
# Utilisation de 'sed' pour remplacer le port 8080 par la variable $PORT. Utilisation du délimiteur '/' standard ici.
sed -i "s/listen 0.0.0.0:8080;/listen 0.0.0.0:$PORT;/" /etc/nginx/nginx.conf
sed -i "s/listen \[::\]:8080;/listen \[::\]:$PORT;/" /etc/nginx/nginx.conf

# 5. CONFIGURATION DES LOGS ET PERMISSIONS FINALES (CORRIGÉ POUR PHP-FPM)
echo "Setting permissions..."
chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Créer les logs des services et définir les permissions
echo "Creating log files and setting permissions..."
touch /var/log/supervisor/supervisord.log
touch /var/log/nginx/access.log
touch /var/log/nginx/error.log
# CRITIQUE: Ajout de la création des fichiers de log PHP-FPM pour Supervisor
touch /var/log/php-fpm.log
touch /var/log/php-fpm-error.log
# Mise à jour de chown pour inclure tous les répertoires de logs et les NOUVEAUX fichiers
chown -R www-data:www-data /var/log/supervisor /var/log/nginx /var/log/php-fpm /var/log/php-fpm.log /var/log/php-fpm-error.log

# 6. DÉMARRAGE DES SERVICES
echo "Starting Supervisor (Nginx and PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf -n