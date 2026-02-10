# 🚀 Prochaines Étapes - Phases 1 & 2

---

## ⚙️ Phase 1: Générer la Clé et Configurer

```bash
cd /home/nassir/Documents/Workflow/ucup2026

# 1. Générer la clé d'application
php artisan key:generate

# 2. Créer le lien symbolique storage
php artisan storage:link

# 3. Nettoyer les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 4. Vérifier la connexion BDD
php artisan tinker
# Puis taper:
# \App\Models\User::count()
# Doit afficher: 1

# Pour quitter tinker: exit
```

---

## 🗄️ Phase 2: Vérifier la Base de Données

```bash
# Vérifier que les données sont là
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM users;"
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM teams;"
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM players;"
psql -U postgres -d ucup_db -c "SELECT COUNT(*) FROM matches;"
```

---

## 🎯 Lancer le Serveur

```bash
# Lancer le serveur de développement
php artisan serve
```

Puis ouvrir: **http://localhost:8000**

---

## 📋 Commandes Rapides (Copier-Coller)

```bash
cd /home/nassir/Documents/Workflow/ucup2026

php artisan key:generate && \
php artisan storage:link && \
php artisan config:clear && \
php artisan cache:clear && \
php artisan view:clear && \
php artisan serve
```

---

## 🐛 Si Erreur à l'Import

Vérifier les tables:

```bash
psql -U postgres -d ucup_db -c "\dt"
```

Doit montrer une liste de tables:
- users
- universities
- teams
- players
- matches
- match_events
- standings
- etc.

---

## 🎉 Après Lancement

1. Ouvrir http://localhost:8000
2. Vérifier que la page d'accueil s'affiche
3. Naviguer vers /admin
4. Tester la connexion
