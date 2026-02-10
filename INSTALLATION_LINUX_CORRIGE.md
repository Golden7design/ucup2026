# 🛠️ Installation PHP/Laravel - Correction Erreurs Ubuntu

---

## 🚨 Erreur: "Le fichier configuré ne sera pas pris en compte"

Cette erreur signifie que ton système a un problème de configuration des dépôts. Voici la solution:

---

## Étape 1: Corriger les Dépôts

```bash
# 1. Ouvrir le fichier de configuration des sources
sudo nano /etc/apt/sources.list.d/pgdg.list
```

Si le fichier est vide ou contient des erreurs, le supprimer et le recréer:

```bash
# Supprimer l'ancien fichier
sudo rm /etc/apt/sources.list.d/pgdg.list

# Créer un nouveau fichier
sudo nano /etc/apt/sources.list.d/pgdg.list
```

Ajouter cette ligne:
```
deb [arch=amd64] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main
```

Enregistrer (Ctrl+O, Entrée, Ctrl+X)

```bash
# Mettre à jour
sudo apt update
```

---

## Étape 2: Si l'erreur persiste - Utiliser les Dépôts Ubuntu Standard

```bash
# Ignorer le dépôt PostgreSQL externe et utiliser celui d'Ubuntu
sudo rm /etc/apt/sources.list.d/pgdg.list
sudo apt update

# Installer PHP depuis les dépôts Ubuntu
sudo apt install -y php php-cli php-common php-curl php-mbstring php-xml php-zip php-pgsql php-sqlite3 php-tokenizer php-json php-bcmath php-intl php-gd
```

---

## Étape 3: Vérifier PHP

```bash
php -v
```

Si PHP n'est pas trouvé:
```bash
# Installer PHP
sudo apt install -y php8.2
```

---

## Étape 4: Installer Composer (Méthode Alternative)

```bash
# Télécharger directement
curl -sS https://getcomposer.org/installer | php

# Déplacer
sudo mv composer.phar /usr/local/bin/composer

# Rendre exécutable
sudo chmod +x /usr/local/bin/composer

# Vérifier
composer --version
```

---

## Étape 5: Installer Node.js (Méthode Alternative)

```bash
# Utiliser nvm (recommandé)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Recharger le terminal
source ~/.bashrc

# Installer Node.js LTS
nvm install --lts

# Vérifier
node -v
npm -v
```

---

## 🚀 Installation Rapide (Sans Dépôts Externes)

```bash
# 1. Mise à jour
sudo apt update && sudo apt upgrade -y

# 2. Installer PHP et extensions (dépôts Ubuntu)
sudo apt install -y php \
    php-cli \
    php-common \
    php-curl \
    php-mbstring \
    php-xml \
    php-zip \
    php-pgsql \
    php-sqlite3 \
    php-tokenizer \
    php-json \
    php-bcmath \
    php-intl \
    php-gd \
    php-imagick

# 3. Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# 4. Installer Node.js avec nvm
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
source ~/.bashrc
nvm install --lts

# 5. Installer Git
sudo apt install -y git

# 6. Vérifications
php -v
composer -v
node -v
git --version
```

---

## 🐛 Si Tu As un Système 32-bit (i386)

Les dépôts PostgreSQL ne supportent plus i386.解决方案:

**Option A: Utiliser SQLite au lieu de PostgreSQL (plus simple)**

Dans `.env`:
```env
DB_CONNECTION=sqlite
# Supprimer les autres lignes DB_*
```

**Option B: Utiliser une base de données distante**

Utiliser la base de données de ton ami directement (si accessible).

---

## 📋 Commandes de Vérification

```bash
# Vérifier PHP
php -v

# Vérifier Composer
composer -v

# Vérifier Node.js
node -v
npm -v

# Vérifier Git
git --version

# Vérifier PostgreSQL
psql --version
```

---

## ⚠️ Si Nothing Ne Fonctionne

Essayez cette méthode Docker (plus simple):

```bash
# Installer Docker
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER

# Utiliser Laravel Sail (inclus dans le projet)
cd /home/nassir/Documents/Workflow/ucup2026
./vendor/bin/sail up
```

---

## 🎯下一步 - Après Installation Réussie

1. `cd /home/nassir/Documents/Workflow/ucup2026`
2. `composer install`
3. `npm install`
4. `cp .env.example .env`
5. Configurer `.env` avec les identifiants BDD
6. `php artisan key:generate`
7. `php artisan serve`

---

## 📞 Si l'Erreur Persiste

Copie-colle-moi l'erreur exacte que tu vois, et je t'aiderai à la résoudre!
