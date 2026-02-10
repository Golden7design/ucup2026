# 🔓 Permission non accordée - Solution

Le problème: l'utilisateur "postgres" n'a pas le droit de lire ton fichier.

---

## Solution 1: Copier le Fichier vers un Dossier Accessible

```bash
# En tant que postgres
postgres@pc-nassir:~$ cp /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql /tmp/

postgres@pc-nassir:~$ psql -d ucup_db < /tmp/sauvegarde_ucup.sql

postgres@pc-nassir:~$ exit
```

---

## Solution 2: Changer les Permissions du Fichier (depuis ton terminal)

```bash
# Ouvrir un nouveau terminal (en tant que nassir)
chmod 755 /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql

# Retourner sur le terminal postgres et réessayer
```

---

## Solution 3: Utiliser le Chemin /tmp

```bash
# Copier le fichier dans /tmp d'abord
cp /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql /tmp/

# Puis importer
psql -d ucup_db < /tmp/sauvegarde_ucup.sql
```

---

## 📋 Étapes Complètes (Copier-Coller)

```bash
# Terminal 1 - En tant que postgres
sudo -i -u postgres

# Copier le fichier et importer
cp /home/nassir/Documents/Workflow/ucup2026/public/sauvegarde_ucup.sql /tmp/sauvegarde_ucup.sql
psql -d ucup_db < /tmp/sauvegarde_ucup.sql

exit
```

---

## Vérifier que l'Import a Fonctionné

```bash
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM users;"
# Doit montrer: count > 0
```

---

## 🎯 Résultat

Le fichier sera importé dans la base ucup_db!
