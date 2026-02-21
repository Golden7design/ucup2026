FROM php:8.4-fpm

WORKDIR /app

# System deps + PHP extensions for Laravel + Postgres
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    nginx \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    ca-certificates \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip xml intl \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App files
COPY . .

# Ensure Laravel runtime dirs exist before Composer scripts run
RUN mkdir -p \
    /app/bootstrap/cache \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R ug+rwx /app/storage /app/bootstrap/cache

# PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Nginx config + start script
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]
