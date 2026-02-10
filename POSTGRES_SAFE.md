# 🔒 Solution Sûre - Créer un Utilisateur PostgreSQL Séparé

Tu as raison de vouloir protéger tes autres bases! Voici comment créer un utilisateur spécifique pour UCup sans toucher à la configuration existante.

---

## Étape 1: Vérifier Tes Bases Existantes

```bash
# Lister toutes tes bases (sans te connecter)
psql -U postgres -l

# Tu verras une liste comme:
#       Nom        | Propriétaire | Codage | COLLATIE  |   Crédits   
# -----------------+-------------+---------+------------+--------------
#  autre_projet    | postgres    | UTF8   | french    |
#  autre_base      | postgres    | UTF8   | french    |
```

---

## Étape 2: Créer un Nouvel Utilisateur (SAFE)

```bash
# Se connecter en tant que postgres
sudo -i -u postgres

# Créer un utilisateur avec mot de passe
psql
```

Dans psql, taper:
```sql
-- Créer l'utilisateur ucup_user avec mot de passe
CREATE USER ucup_user WITH PASSWORD 'elmish2003';

-- Créer la base ucup_db appartenant à ucup_user
CREATE DATABASE ucup_db OWNER ucup_user;

-- Donner les droits à ucup_user
GRANT ALL PRIVILEGES ON DATABASE ucup_db TO ucup_user;

-- Quitter psql
\q
```

```bash
exit
```

---

## Étape 3: Tester la Connexion

```bash
# Tester avec le nouvel utilisateur
psql -U ucup_user -d ucup_db -W

# Entrer le mot de passe: elmish2003
```

Si ça marche, tu verras `ucup_db=#`

---

## Étape 4: Importer le Dump

```bash
# Importer le dump SQL
psql -U ucup_user -d ucup_db < /home/nassir/Documents/Workflow/ucup2026/sauvegarde_ucup.sql -W
```

---

## Étape 5: Configurer le Fichier .env

```bash
cd /home/nassir/Documents/Workflow/ucup2026

cp .env.example .env

nano .env
```

Modifier ainsi:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ucup_db
DB_USERNAME=ucup_user
DB_PASSWORD=elmish2003
```

---

## ✅ Résultat

| Base de données | Propriétaire | Statut |
|-----------------|--------------|--------|
| autre_projet | postgres | ⬜ Non touché |
| autre_base | postgres | ⬜ Non touché |
| **ucup_db** | ucup_user | ✅ Nouveau |

---

## 📋 Commandes de Sécurité

```bash
# Lister les utilisateurs
psql -U postgres -c "\du"

# Lister les bases
psql -U postgres -c "\l"

# Se connecter à UCup
psql -U ucup_user -d ucup_db -W

# Supprimer UCup (si besoin plus tard)
sudo -i -u postgres
psql
DROP DATABASE ucup_db;
DROP USER ucup_user;
\q
exit
```

---

## 🎯 Tes Autres Bases Sont en Sécurité

- `autre_projet` - Non touché
- `autre_base` - Non touché
- Seule `ucup_db` est nouvelle

---

## 🔐 Vérification

```bash
# Vérifier que ucup_db existe et est vide
psql -U postgres -c "\l"

# Vérifier que tes autres bases sont intactes
psql -U postgres -c "\l"
```

---

**AUCUNE de tes autres bases ne sera modifiée! ✅**
