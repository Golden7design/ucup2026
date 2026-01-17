#!/bin/sh

# Script de démarrage simplifié
set -e

echo "Starting services..."

# Créer un fichier .env minimal si nécessaire
if [ ! -f /var/www/html/.env ]; then
    cat > /var/www/html/.env << 'EOF'
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:WRRD3ByFIDp53mY9y5jNOYbvNHWauw7rf3xBTmCqQbY=
APP_DEBUG=false
APP_URL=https://u-cup-backend-laravel.onrender.com

DB_CONNECTION=pgsql
DB_HOST=dpg-d54ii8umcj7s73es0220-a.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=ucup_database
DB_USERNAME=ucup_database_user
DB_PASSWORD=o2HvDyIDWtgPrijOJ4aehI10mjJaWs9E
EOF
fi

# Démarrer Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisor.conf