#!/bin/bash
# U-Cup Tournament Server Setup Script
# This script configures a fresh Ubuntu server for Laravel deployment

# Update system
echo "🔄 Updating system..."
sudo apt-get update -y
sudo apt-get upgrade -y

# Install required packages
echo "📦 Installing required packages..."
sudo apt-get install -y \
    nginx \
    php8.1 \
    php8.1-fpm \
    php8.1-mysql \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-curl \
    php8.1-gd \
    php8.1-zip \
    mysql-client \
    unzip \
    git \
    curl \
    composer \
    certbot \
    python3-certbot-nginx

# Install Docker and Docker Compose
echo "🐳 Installing Docker..."
sudo apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg \
    lsb-release

curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

echo \
  "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt-get update -y
sudo apt-get install -y docker-ce docker-ce-cli containerd.io

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.2.3/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Configure PHP
echo "🔧 Configuring PHP..."
sudo sed -i "s/memory_limit = .*/memory_limit = 256M/" /etc/php/8.1/fpm/php.ini
sudo sed -i "s/upload_max_filesize = .*/upload_max_filesize = 64M/" /etc/php/8.1/fpm/php.ini
sudo sed -i "s/post_max_size = .*/post_max_size = 64M/" /etc/php/8.1/fpm/php.ini

# Configure Nginx
echo "🔧 Configuring Nginx..."
sudo rm /etc/nginx/sites-enabled/default
sudo cp /home/ubuntu/u-cup-tournament/deploy/config/nginx.conf /etc/nginx/sites-available/u-cup-tournament
sudo ln -s /etc/nginx/sites-available/u-cup-tournament /etc/nginx/sites-enabled/

# Configure Laravel environment
echo "🔧 Configuring Laravel..."
cd /home/ubuntu/u-cup-tournament
cp .env.example .env
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Configure Supervisor for queues
echo "🔧 Configuring Supervisor..."
sudo cp /home/ubuntu/u-cup-tournament/deploy/config/supervisor.conf /etc/supervisor/conf.d/u-cup-tournament.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start u-cup-tournament:*

# Restart services
echo "🔄 Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
sudo systemctl restart docker

# Set up SSL with Certbot
echo "🔒 Setting up SSL..."
sudo certbot --nginx -d u-cup-tournament.yourdomain.com --non-interactive --agree-tos -m your-email@example.com

# Configure automatic SSL renewal
sudo crontab -l | { cat; echo "0 12 * * * /usr/bin/certbot renew --quiet"; } | sudo crontab -

echo "✅ Server setup completed successfully!"
echo "🌐 Your U-Cup Tournament site should now be accessible at: https://u-cup-tournament.yourdomain.com"