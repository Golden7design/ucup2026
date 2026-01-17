# 1. UTILISATION D'UNE IMAGE PHP ALPINE STABLE (8.3)
FROM php:8.3-fpm-alpine

# Définir le répertoire de travail
WORKDIR /app

# 2. INSTALLATION DES DÉPENDANCES SYSTÈME ET EXTENSIONS PHP OPTIMISÉES POUR RAILWAY
# Utiliser les extensions PHP pré-compilées pour éviter les timeouts de compilation
# Installation en plusieurs étapes pour éviter les timeouts
RUN apk add --no-cache --update \
    git \
    libpq \
    libzip \
    unzip \
    postgresql-client \
    nginx \
    supervisor \
    ca-certificates \
    icu-data \
    icu-libs

# Installation des extensions PHP en une seule commande pour optimiser le cache
RUN apk add --no-cache \
    php83-bcmath \
    php83-openssl \
    php83-pdo \
    php83-pdo_pgsql \
    php83-zip \
    php83-opcache \
    php83-intl \
    php83-tokenizer \
    php83-json \
    php83-dom \
    php83-simplexml \
    php83-xml \
    php83-xmlwriter \
    php83-mbstring \
    php83-curl \
    php83-fileinfo \
    php83-filter

# 3. INSTALLATION DE COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. COPIE DU CODE SOURCE
COPY . .

# 5. INSTALLATION DES DÉPENDANCES COMPOSER
# Utiliser --no-interaction et --no-scripts pour éviter les timeouts
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# 6. CONFIGURATION DES PERMISSIONS
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# 6b. CRÉATION DES RÉPERTOIRES DE LOGS AVEC BONNES PERMISSIONS
RUN mkdir -p /var/log/supervisor /var/log/nginx /var/log/php-fpm /var/log/laravel /var/run/php
RUN chown -R www-data:www-data /var/log/supervisor /var/log/nginx /var/log/php-fpm /var/log/laravel /var/run/php
RUN chmod -R 775 /var/log/supervisor /var/log/nginx /var/log/php-fpm /var/log/laravel /var/run/php

# 7. CONFIGURATION NGINX
COPY deploy/config/nginx.conf /etc/nginx/nginx.conf

# 8. CONFIGURATION PHP-FPM
COPY deploy/config/php-fpm.conf /etc/php83/php-fpm.conf

# 9. CONFIGURATION SUPERVISOR
COPY deploy/config/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# 10. COPIE DU FICHIER D'ENVIRONNEMENT POUR RAILWAY
COPY deploy/config/.env.railway /app/.env.railway

# 11. CRÉATION DU SCRIPT D'INITIALISATION
COPY deploy/scripts/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# 12. DÉMARRAGE AVEC LE SCRIPT D'INITIALISATION
CMD ["/bin/sh", "-c", "/usr/local/bin/init.sh"]