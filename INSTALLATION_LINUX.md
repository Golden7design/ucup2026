# 🛠️ Installation de PHP, Composer et Laravel sur Ubuntu/Debian

---

## Étape 1: Mettre à Jour le Système

```bash
sudo apt update
sudo apt upgrade -y
```

---

## Étape 2: Installer PHP 8.2+

```bash
# Ajouter le PPA pour les dernières versions de PHP (si disponible)
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Installer PHP 8.2 et les extensions nécessaires
sudo apt install -y php8.2 \
    php8.2-cli \
    php8.2-common \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-zip \
    php8.2-pgsql \
    php8.2-sqlite3 \
    php8.2-tokenizer \
    php8.2-json \
    php8.2-bcmath \
    php8.2-intl \
    php8.2-redis

# Vérifier l'installation
php -v
```

---

## Étape 3: Installer Composer

```bash
# Télécharger Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Déplacer Composer globally
sudo mv composer.phar /usr/local/bin/composer

# Vérifier l'installation
composer --version
```

---

## Étape 4: Installer Node.js et npm

```bash
# Installer Node.js (version LTS)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Vérifier l'installation
node -v
npm -v
```

---

## Étape 5: Installer Git (si pas déjà installé)

```bash
sudo apt install -y git
git --version
```

---

## Étape 6: Vérifier l'Installation

```bash
# Vérifier toutes les installations
php -v          # Devrait montrer PHP 8.2+
composer -v     # Devrait montrer Composer 2.x
node -v         # Devrait montrer Node 20.x
npm -v          # Devrait montrer npm 10.x
git --version   # Devrait montrer git
```

---

## Étape 7: Installer les Extensions PHP Additionnelles

```bash
# Pour Laravel et le projet UCup
sudo apt install -y \
    php8.2-gd \
    php8.2-imagick \
    php8.2-memcached \
    php8.2-xdebug \
    libzip-dev
```

---

## 🚀 Après l'Installation - Retour au Projet UCup

```bash
# Aller dans le dossier du projet
cd /home/nassir/Documents/Workflow/ucup2026

# Installer les dépendances PHP
composer install

# Installer les dépendances Node
npm install

# Créer le fichier .env
cp .env.example .env

# Éditer le .env avec les identifiants PostgreSQL
# DB_PASSWORD=elmish2003
# DB_DATABASE=ucup_db

# Générer la clé d'application
php artisan key:generate

# Créer le lien symbolique storage
php artisan storage:link

# Lancer le serveur
php artisan serve
```

---

## 📋 Résumé des Commandes (Copier-Coller)

```bash
# 1. Mise à jour
sudo apt update && sudo apt upgrade -y

# 2. PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip php8.2-pgsql php8.2-sqlite3 php8.2-tokenizer php8.2-json php8.2-bcmath php8.2-intl php8.2-redis php8.2-gd php8.2-imagick

# 3. Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer

# 4. Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# 5. Git
sudo apt install -y git

# 6. Vérification
php -v
composer -v
node -v
```

---

## 🐛 Problèmes Courants

### "sudo: add-apt-repository: command not found"

```bash
sudo apt install -y software-properties-common
```

### "curl: command not found"

```bash
sudo apt install -y curl
```

### "php: command not found"

```bash
# Vérifier que PHP est installé
which php
# Si absent, reinstaller PHP
sudo apt install -y php8.2-cli
```

### "composer: command not found"

```bash
# Vérifier que Composer est dans le PATH
which composer
# Si absent
sudo mv composer.phar /usr/local/bin/composer
```

### "node: command not found"

```bash
# Vérifier que Node.js est installé
which node
# Si absent
sudo apt install -y nodejs
```

---

## ⏱️ Temps d'Installation Estimé

| Étape | Temps |
|-------|-------|
| Mise à jour système | 5-10 minutes |
| Installation PHP | 5-10 minutes |
| Installation Composer | 2-5 minutes |
| Installation Node.js | 5-10 minutes |
| **Total** | **20-35 minutes** |

---

## ✅ Vérification Finale

Après l'installation, exécuter:

```bash
php -v    # PHP 8.2.x
composer -v  # Composer 2.x
node -v   # Node 20.x
npm -v    # npm 10.x
```

Si tout affiche une version, tu es prêt pour le projet UCup!

---

## 🎯 Prochaines Étapes

Une fois PHP/Composer/Node.js installés:

1. `cd /home/nassir/Documents/Workflow/ucup2026`
2. `composer install`
3. `npm install`
4. Configurer `.env`
5. `php artisan serve`

---

**Bon courage pour l'installation! 🚀**
