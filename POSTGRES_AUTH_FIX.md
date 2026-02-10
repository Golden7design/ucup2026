# 🔐 Résoudre l'Erreur "peer authentication failed"

**Le problème:**
PostgreSQL utilise l'authentification "peer" par défaut sur Linux. Cela signifie que le nom d'utilisateur Linux DOIT être identique au nom d'utilisateur PostgreSQL.

Comme ton utilisateur Linux est "nassir" et que tu utilises "postgres1", l'authentification échoue.

---

## Solution 1: Utiliser l'Utilisateur PostgreSQL (Le Plus Simple)

```bash
# Se connecter en tant qu'utilisateur postgres (système)
sudo -i -u postgres

# Importer la base
psql -d ucup_db < /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql

# Quitter
exit
```

---

## Solution 2: Changer l'Authentification en md5

### Étape A: Éditer pg_hba.conf

```bash
# Trouver le fichier pg_hba.conf
sudo find /etc -name "pg_hba.conf"

# En général:
sudo nano /etc/postgresql/15/main/pg_hba.conf
# (remplacer 15 par ta version PostgreSQL)
```

### Étape B: Modifier les lignes

Chercher et modifier ces lignes:

```
#local   all             all                                     peer
local    all             all                                     md5
```

Devient:

```
local   all             all                                     md5
```

Enregistrer (Ctrl+O, Entrée, Ctrl+X)

### Étape C: Redémarrer PostgreSQL

```bash
sudo systemctl restart postgresql
```

### Étape D: Réessayer

```bash
psql -U postgres1 -d ucup_db -W
# Entrer: elmish2003
```

---

## Solution 3: Créer un Utilisateur Linux "postgres1"

```bash
# Créer l'utilisateur Linux postgres1
sudo useradd -m -s /bin/bash postgres1

# Définir un mot de passe Linux
sudo passwd postgres1
# Entrer un mot de passe (peut être différent de PostgreSQL)

# Se connecter en tant que postgres1
su - postgres1

# Importer la base
psql -d ucup_db < /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql

# Quitter
exit
```

---

## Solution 4: Utiliser la Connexion Socket TCP

```bash
# Essayer avec l'hôte localhost (pas peer)
psql -h localhost -U postgres1 -d ucup_db -W
```

---

## Vérifier la Version de PostgreSQL

```bash
psql --version
```

Pour pg_hba.conf, la version est dans le chemin:
`/etc/postgresql/[VERSION]/main/pg_hba.conf`

---

## 📋 Résumé Rapide

| Solution | Commande | Facilité |
|----------|----------|----------|
| 1. sudo -i -u postgres | `sudo -i -u postgres && psql -d ucup_db < sauvegarde.sql` | ⭐ Facile |
| 2. Changer md5 | Éditer pg_hba.conf | ⭐⭐ Moyen |
| 3. Créer user Linux | `sudo useradd postgres1` | ⭐⭐⭐ Difficile |
| 4. TCP connection | `psql -h localhost -U postgres1` | ⭐ Facile |

---

## 🎯 Recommandation

Utilise la **Solution 1** - c'est la plus rapide et la plus sûre:

```bash
sudo -i -u postgres
psql -d ucup_db < /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql
exit
```

---

## 🔍 Vérifier si l'Utilisateur Existe

```bash
# Lister les utilisateurs PostgreSQL
psql -U postgres -c "\du"

# Tu devrais voir:
#                                   Liste des rôles
#     Nom du rôle  |    Attributs     | Membre de | Description 
# ----------------+------------------+-----------+-------------
#  postgres       | Superuser       | {}        | 
#                  | Create role    |           |
#                  | Create database |           |
#  postgres1      |                 | {}        |
