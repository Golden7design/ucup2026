# 🐛 Erreur PostgreSQL - authentification peer échouée

**Erreur:**
```
psql: erreur : la connexion au serveur sur le socket « /var/run/postgresql/.s.PLSQL.5432 » a échoué : FATAL:  authentification peer échouée pour l'utilisateur « postgres »
```

---

## Solution 1: Utiliser l'Utilisateur PostgreSQL (Sans Mot de Passe)

```bash
# Se connecter en tant que utilisateur postgres
sudo -i -u postgres

# Importer la base de données
psql -d ucup_db < /home/nassir/Documents/Workflow/ucup2026/sauvegarde_ucup.sql

# Quitter
exit
```

---

## Solution 2: Changer le Mot de Passe PostgreSQL

```bash
# Se connecter en tant que postgres
sudo -i -u postgres

# Ouvrir psql
psql

# Changer le mot de passe
ALTER USER postgres WITH PASSWORD 'elmish2003';

# Quitter
\q
exit

# Tester la connexion
psql -U postgres -d ucup_db
```

---

## Solution 3: Modifier la Méthode d'Authentification (md5)

```bash
# Éditer le fichier de configuration PostgreSQL
sudo nano /etc/postgresql/15/main/pg_hba.conf
# (remplacer 15 par ta version de PostgreSQL)

# Chercher ces lignes:
# local   all   all   peer
# local   all   all   scram-sha-256

# Les modifier en:
# local   all   all   md5

# Enregistrer (Ctrl+O, Entrée, Ctrl+X)

# Redémarrer PostgreSQL
sudo systemctl restart postgresql

# Maintenant tu peux utiliser le mot de passe
psql -U postgres -d ucup_db
```

---

## Solution 4: Connexion via Socket TCP

```bash
# Essayer avec l'hôte localhost
psql -h localhost -U postgres -d ucup_db -W
# Puis entrer le mot de passe: elmish2003
```

---

## Vérifier la Version de PostgreSQL

```bash
psql --version
```

---

## 📋 Commandes Utiles

| Commande | Description |
|----------|-------------|
| `sudo systemctl status postgresql` | Vérifier si PostgreSQL tourne |
| `sudo systemctl start postgresql` | Démarrer PostgreSQL |
| `sudo systemctl restart postgresql` | Redémarrer PostgreSQL |
| `sudo -i -u postgres` | Se connecter en tant que postgres |
| `psql -l` | Lister les bases de données |

---

## 🎯 Recommandation

Utilise la **Solution 1** (sudo -i -u postgres) - c'est la plus simple!

```bash
sudo -i -u postgres
psql -d ucup_db < /home/nassir/Documents/Workflow/ucup2026/sauvegarde_ucup.sql
exit
```
