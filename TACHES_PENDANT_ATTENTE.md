# ⏳ À Faire Pendant l'Attente du Dump SQL

Ton ami fini l'école et t'envoie le fichier SQL. Voici ce que tu peux faire en attendant!

---

## ✅ Étape 1: Créer la Base de Données

```bash
# Ouvrir un terminal et exécuter:
createdb -U postgres ucup_db

# Mot de passe: elmish2003

# Vérifier que la base est créée:
psql -U postgres -d ucup_db -c "\dt"
# Devrait montrer: No relations found. (normal, vide pour l'instant)
```

---

## ✅ Étape 2: Installer les Dépendances

```bash
# Dans un terminal, aller dans le projet:
cd /home/nassir/Documents/Workflow/ucup2026

# Installer PHP dependencies (composer)
composer install

# Installer Node dependencies (npm)
npm install

# ⚠️ Esto puede tardar 5-15 minutos dependiendo de tu internet
```

---

## ✅ Étape 3: Préparer le Fichier .env

```bash
# Copier le fichier d'exemple:
cp .env.example .env

# Éditer le fichier .env avec:
# - DB_PASSWORD=elmish2003
# - DB_DATABASE=ucup_db

# Générer la clé d'app:
php artisan key:generate
```

---

## ✅ Étape 4: Explorer le Code ( pendant les installations )

Pendant que `composer install` et `npm install` tournent, tu peux:

### Lire la documentation:
- [`GUIDE_UTILISATION_PROD.md`](GUIDE_UTILISATION_PROD.md) - Comment utiliser l'app
- [`IMPLEMENTATION_CHECKLIST.md`](IMPLEMENTATION_CHECKLIST.md) - Checklist complète

### Explorer les fichiers principaux:
```
app/Http/Controllers/
├── Frontend/     # Pages publiques (accueil, matchs, équipes...)
└── Admin/        # Pages admin (gestion des équipes, live...)

app/Models/
├── MatchModel.php    # Logique des matchs
├── Player.php        # Logique des joueurs
├── Team.php          # Logique des équipes
└── Standing.php      # Logique des classements
```

---

## ✅ Étape 5: Préparer l'Import du Dump

Quand ton ami t'envoie `ucup_backup.sql`:

```bash
# 1. Importer le dump:
psql -U postgres -d ucup_db < ucup_backup.sql

# 2. Vérifier que les données sont là:
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM users;"
# Devrait montrer: count = 1 (ou plus)

# 3. Créer le lien symbolique:
php artisan storage:link

# 4. Lancer le serveur:
php artisan serve
```

---

## 📋 Checklist Rapide - À Faire Maintenant

| Tâche | Status |
|-------|--------|
| Créer base `ucup_db` | ⬜ |
| `composer install` | ⬜ |
| `npm install` | ⬜ |
| Configurer `.env` | ⬜ |
| `php artisan key:generate` | ⬜ |

---

## 💡 Astuce

Pendant que `composer install` tourne, tu peux:
1. Ouvrir un **nouveau** terminal
2. Explorer les fichiers du projet
3. Lire les guides que j'ai créés

Comme ça, tu ne perds pas de temps!

---

## 📞 Quand Ton Ami Envoie le Fichier

1. **Télécharger** le fichier SQL
2. Le **placer** dans `/home/nassir/Documents/Workflow/ucup2026/`
3. Exécuter:
   ```bash
   psql -U postgres -d ucup_db < ucup_backup.sql
   ```
4. Puis:
   ```bash
   php artisan serve
   ```
5. Ouvrir `http://localhost:8000` dans le navigateur

---

## ❓ Questions Fréquentes

**"Psql n'est pas reconnu"**
- Vérifier que PostgreSQL est installé
- Ajouter PostgreSQL au PATH Windows

**"Composer n'est pas reconnu"**
- Installer Composer depuis https://getcomposer.org/

**"npm install échoue"**
- Vérifier que Node.js est installé: `node -v`
- Mettre à jour npm: `npm install -g npm@latest`

---

**Bonne continuation! 🚀**
