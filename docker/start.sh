#!/usr/bin/env sh
set -e

PORT="${PORT:-8080}"

# Update Nginx listen port
sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/nginx.conf

# Laravel cache warmup (optional, safe if no .env yet)
php artisan config:clear || true
php artisan view:clear || true

# Ensure uploaded files are web-accessible in container/runtime.
# We force recreation to avoid broken symlinks copied from local machines.
mkdir -p /app/storage/app/public /app/bootstrap/cache
rm -rf /app/public/storage
php artisan storage:link --force || ln -s /app/storage/app/public /app/public/storage

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g "daemon off;"
