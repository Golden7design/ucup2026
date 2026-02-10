# 🚀 Étapes Immédiates - Lancer UCup

Tu as le fichier SQL dans le projet. Voici les prochaines étapes:

---

## 1. Importer la Base de Données

```bash
# Aller dans le dossier du projet
cd /home/nassir/Documents/Workflow/ucup2026

# Lister les fichiers SQL disponibles
ls -la *.sql

# Importer le fichier SQL (remplacer NOM_DU_FICHIER.sql par le nom réel)
psql -U postgres -d ucup_db < NOM_DU_FICHIER.sql

# Vérifier que l'import a fonctionné
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM users;"
```

Si tu vois un nombre > 0, l'import a réussi!

---

## 2. Configurer le Fichier .env

```bash
# Copier le fichier d'exemple
cp .env.example .env

# Éditer le fichier .env
nano .env
```

Modifier ces lignes:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ucup_db
DB_USERNAME=postgres
DB_PASSWORD=elmish2003
```

Enregistrer (Ctrl+O, Entrée, Ctrl+X)

```bash
# Générer la clé d'application
php artisan key:generate
```

---

## 3. Créer le Lien Symbolique

```bash
php artisan storage:link
```

---

## 4. Installer les Dépendances du Projet

```bash
# Installer PHP dependencies
composer install

# Installer Node dependencies
npm install
```

---

## 5. Lancer le Serveur

```bash
php artisan serve
```

---

## 6. Accéder à l'Application

Ouvrir un navigateur et aller sur:
```
http://localhost:8000
```

---

## 7. Se Connecter (Admin)

- Email: `emoukouanga@gmail.com`
- Mot de passe: `AdminUCup2026`

Aller sur `/admin` pour accéder au panneau d'administration.

---

## 📋 Checklist Rapide

| Étape | Commande | Status |
|-------|----------|--------|
| 1. Importer BDD | `psql -U postgres -d ucup_db < fichier.sql` | ⬜ |
| 2. Configurer .env | `cp .env.example .env` puis éditer | ⬜ |
| 3. Key generate | `php artisan key:generate` | ⬜ |
| 4. Storage link | `php artisan storage:link` | ⬜ |
| 5. Composer install | `composer install` | ⬜ |
| 6. NPM install | `npm install` | ⬜ |
| 7. Lancer | `php artisan serve` | ⬜ |

---

## 🐛 Si Erreur à l'Import BDD

**"psql: error: connection to server on socket..."**

PostgreSQL n'est pas démarré:
```bash
# Démarrer PostgreSQL
sudo service postgresql start

# Ou
sudo pg_ctlcluster 15 main start
```

**"psql: error: database ucup_db does not exist"**

Créer la base d'abord:
```bash
createdb -U postgres ucup_db
```

**"psql: error: could not connect to database: FATAL:  password authentication failed"**

Mauvais mot de passe. Le mot de passe par défaut est `elmish2003`.

---

## 🎯 Prochaines Étapes Après Lancement

1. Vérifier que la page d'accueil s'affiche
2. Tester la connexion admin
3. Naviguer dans le panneau admin
4. Tester l'ajout d'un match

---

**Bon courage! 🚀**
