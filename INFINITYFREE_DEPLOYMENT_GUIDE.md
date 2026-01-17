# 🎯 Guide de Déploiement pour InfinityFree
# U-Cup Tournament

Ce guide vous explique comment déployer votre application U-Cup Tournament sur InfinityFree **sans carte bancaire**.

## 📋 Prérequis

1. **Compte InfinityFree** (déjà créé)
2. **Base de données MySQL** (déjà configurée sur InfinityFree)
3. **Client FTP** (FileZilla recommandé)
4. **Éditeur de texte** (VS Code, Notepad++, etc.)

## 🛠️ Étapes de Déploiement

### 1. Préparer votre projet localement

Exécutez le script de préparation :

```bash
# Sur votre machine locale, dans le dossier du projet
chmod +x deploy-to-infinityfree.sh
./deploy-to-infinityfree.sh
```

Ce script va :
- Installer les dépendances en mode production
- Optimiser Laravel
- Configurer les permissions
- Créer un fichier .htaccess optimisé
- Générer des instructions de déploiement

### 2. Configurer le fichier .env

Ouvrez le fichier `.env.infinityfree` et mettez à jour les informations avec celles de votre base de données InfinityFree :

```env
DB_HOST=sqlXXX.epizy.com      # Remplacez XXX par votre numéro
DB_DATABASE=epiz_XXXXXX_u_cup # Votre nom de base de données
DB_USERNAME=epiz_XXXXXX       # Votre utilisateur
DB_PASSWORD=votre_mot_de_passe # Votre mot de passe

APP_URL=https://votre-sous-domaine.epizy.com
```

### 3. Se connecter via FTP

Utilisez FileZilla avec ces informations (disponibles dans votre tableau de bord InfinityFree) :
- **Hôte** : ftp://votre-sous-domaine.epizy.com
- **Identifiant** : epiz_XXXXXX
- **Mot de passe** : Votre mot de passe FTP
- **Port** : 21

### 4. Télécharger les fichiers

1. **Supprimez tous les fichiers existants** sur le serveur
2. **Téléchargez tous les fichiers** de votre projet (sauf ceux exclus par le script)
3. **Renommez `.env.infinityfree` en `.env`** sur le serveur

### 5. Exécuter les migrations

Utilisez le **terminal en ligne** dans le tableau de bord InfinityFree :

```bash
cd htdocs
php artisan migrate --force
php artisan db:seed --force  # Si vous avez des seeders
php artisan storage:link
```

### 6. Configurer les permissions

Toujours via le terminal :

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public
```

## ⚠️ Problèmes Courants et Solutions

### 1. Erreur 500 après déploiement

**Cause** : Fichier .env manquant ou mal configuré

**Solution** :
- Vérifiez que `.env` existe et est bien configuré
- Vérifiez les permissions (chmod 644 .env)

### 2. Erreur de connexion à la base de données

**Cause** : Informations de base de données incorrectes

**Solution** :
- Vérifiez `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Testez la connexion avec :
  ```bash
  php artisan db
  ```

### 3. Problèmes d'upload de fichiers

**Cause** : Permissions ou limite de taille

**Solution** :
- Vérifiez que `storage/app/public` est accessible en écriture
- Dans `.env`, assurez-vous que :
  ```env
  FILE_STORAGE=local
  ```

### 4. Site lent

**Cause** : Ressources limitées sur l'hébergement gratuit

**Solution** :
- Activez le cache :
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- Utilisez `QUEUE_DRIVER=sync` dans `.env`

## 🔧 Optimisations pour InfinityFree

### 1. Configurer le cache

Dans `.env` :
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### 2. Désactiver les fonctionnalités gourmandes

Dans `config/queue.php` :
```php
'default' => env('QUEUE_CONNECTION', 'sync'),
```

### 3. Optimiser les assets

Exécutez :
```bash
npm run production
```

### 4. Configurer OPcache

Créez un fichier `php.ini` dans le dossier racine :
```ini
; OPcache settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=240
opcache.fast_shutdown=1
```

## 📊 Surveillance et Maintenance

### 1. Vérifier les logs

Via le terminal :
```bash
tail -f storage/logs/laravel.log
```

### 2. Nettoyer le cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 3. Sauvegarder la base de données

```bash
mysqldump -u epiz_XXXXXX -p -h sqlXXX.epizy.com epiz_XXXXXX_u_cup > backup.sql
```

## 🎉 Votre site est prêt !

Votre application U-Cup Tournament devrait maintenant être accessible à :
```
https://votre-sous-domaine.epizy.com
```

## 💡 Conseils Supplémentaires

1. **Utilisez Cloudflare** pour :
   - HTTPS gratuit
   - Cache supplémentaire
   - Protection DDoS

2. **Optimisez vos images** avant de les uploader

3. **Minifiez vos assets** JavaScript et CSS

4. **Utilisez des CDN** pour les bibliothèques communes (jQuery, Bootstrap, etc.)

## 📚 Ressources Utiles

- [Documentation InfinityFree](https://infinityfree.net/support/)
- [Forum InfinityFree](https://infinityfree.net/forum/)
- [Documentation Laravel Deployment](https://laravel.com/docs/deployment)

Si vous rencontrez des problèmes spécifiques, n'hésitez pas à demander de l'aide ! Je suis là pour vous guider à travers chaque étape. 😊