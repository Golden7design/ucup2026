#!/usr/bin/env sh
set -e

PORT="${PORT:-8080}"

# Update Nginx listen port
sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/nginx.conf

# Laravel cache warmup (optional, safe if no .env yet)
php artisan config:clear || true
php artisan view:clear || true

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g "daemon off;"
