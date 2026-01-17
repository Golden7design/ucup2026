#!/bin/bash

# Render deployment script for Laravel
set -e

echo "Starting deployment..."

# Navigate to the app directory
cd /var/www/html

echo "Installing dependencies..."
composer install --no-interaction --no-dev --optimize-autoloader

echo "Setting up environment..."
cp .env.production .env

# Generate application key if needed
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

echo "Running migrations..."
php artisan migrate --force --no-interaction

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "Setting up storage permissions..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create necessary log directories
echo "Creating log directories..."
mkdir -p storage/logs
chown -R www-data:www-data storage/logs
chmod -R 775 storage/logs

# Create supervisor log directory
mkdir -p /var/log/supervisor
chown -R www-data:www-data /var/log/supervisor
chmod -R 775 /var/log/supervisor

echo "Optimizing application..."
php artisan optimize

echo "Starting Supervisor..."
supervisord -c /etc/supervisor/supervisord.conf

echo "Deployment completed successfully!"