# 🚀 Checklist d'Implémentation - UCup

## Objectif: Rendre l'application fonctionnelle et sûre

---

## ⚙️ Phase 1: Installation et Configuration

- [X] **Cloner le projet**
  ```bash
  cd /home/nassir/Documents/Workflow/ucup2026
  ```

- [X] **Installer les dépendances PHP**
  ```bash
  composer install
  ```

- [X] **Installer les dépendances Node**
  ```bash
  npm install
  ```

- [X] **Créer le fichier `.env`**
  - Copier `.env.example` vers `.env`
  - Configurer les variables PostgreSQL:
    ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=ucup_db
    DB_USERNAME=postgres
    DB_PASSWORD=elmish2003
    ```

- [X] **Générer la clé d'application**
  ```bash
  php artisan key:generate
  ```

- [X] **Créer le lien symbolique pour le storage**
  ```bash
  php artisan storage:link
  ```

- [X] **Nettoyer les caches**
  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan view:clear
  ```

---

## 🗄️ Phase 2: Base de Données

### Option A: Importer dump existant (RECOMMANDÉ)

- [X] Demander à ton ami le fichier `ucup_backup.sql`
- [X] Importer le dump:
  ```bash
  psql -U postgres -d ucup_db < ucup_backup.sql
  ```
- [ ] Copier les fichiers médias (`storage/app/public/`)

### Option B: Créer depuis zéro

- [ ] Exécuter les migrations
  ```bash
  php artisan migrate
  ```
- [ ] Peupler avec les seeders
  ```bash
  php artisan db:seed
  ```
- [ ] Créer le compte admin
  ```bash
  php artisan create_admin
  ```

- [ ] **Vérifier la connexion BDD**
  ```bash
  php artisan tinker
  # Puis: \App\Models\User::count()
  ```

---

## 🔐 Phase 3: Authentification et Sécurité

- [X] **Tester la page de connexion** (`/login`)
- [X] **Vérifier les identifiants admin**
  - Email: `emoukouanga@gmail.com`
  - Mot de passe: `AdminUCup2026`
- [X] **Tester la déconnexion**
- [X] **Vérifier que les routes `/admin` nécessitent une connexion**
- [X] **Tester l'accès sans être connecté** (doit être redirigé vers login)
- [x] **Activer HTTPS en production** (si applicable)
- [x] **Configurer les headers de sécurité**

---

## 🧪 Phase 4: Tests des Fonctionnalités

### Pages Publiques

- [X] **Page d'accueil** (`/`)
  - [X] Affichage des matchs en direct
  - [X] Affichage des matchs à venir
  - [X] Affichage du classement
  - [X] Affichage des top buteurs

- [X] **Liste des matchs** (`/matches`)
  - [X] Filtrage par statut
  - [X] Affichage des scores
  - [X] Liens vers les détails

- [X] **Détails d'un match** (`/matches/{id}`)
  - [X] Affichage du score
  - [X] Timeline des événements
  - [X] Composition des équipes
  - [X] Statistiques du match

- [ ] **Liste des équipes** (`/teams`)
  - [ ] Affichage des logos
  - [ ] Lien vers le profil

- [ ] **Profil d'une équipe** (`/teams/{id}`)
  - [ ] Liste des joueurs
  - [ ] Stats de l'équipe

- [ ] **Liste des joueurs** (`/players`)
  - [ ] Recherche
  - [ ] Filtres

- [ ] **Profil d'un joueur** (`/players/{id}`)
  - [ ] Stats personnelles
  - [ ] Historique

- [ ] **Classements** (`/standings`)
  - [ ] Tableau des points
  - [ ] Filtres par groupe

- [ ] **Galerie** (`/galerie`)
  - [ ] Affichage des images

---

### Panneau Admin

- [ ] **Accéder au dashboard admin** (`/admin`)
- [ ] **Universités**
  - [ ] Lister
  - [ ] Créer
  - [ ] Modifier
  - [ ] Supprimer

- [ ] **Équipes**
  - [ ] Lister
  - [ ] Créer
  - [ ] Modifier
  - [ ] Supprimer
  - [ ] Assigner joueurs

- [ ] **Joueurs**
  - [ ] Lister
  - [ ] Créer
  - [ ] Modifier
  - [ ] Supprimer
  - [ ] Import en masse

- [ ] **Matchs**
  - [ ] Lister
  - [ ] Créer
  - [ ] Modifier
  - [ ] Supprimer
  - [ ] Définir compositions

- [ ] **Matchs en direct** (`/admin/live`)
  - [ ] Démarrer un match
  - [ ] Ajouter un but
  - [ ] Ajouter un carton
  - [ ] Ajouter une substitution
  - [ ] Mettre à jour le score
  - [ ] Mettre en pause

- [ ] **Utilisateurs**
  - [ ] Lister
  - [ ] Créer admin
  - [ ] Modifier rôles

- [ ] **Classements**
  - [ ] Recalculer les points

---

## 📡 Phase 5: Temps Réel (Pusher)

- [ ] **Vérifier les credentials Pusher**
  ```
  PUSHER_APP_ID=1937103
  PUSHER_APP_KEY=1e779a950482592f6dae
  PUSHER_APP_SECRET=203792019446d3e75871
  PUSHER_APP_CLUSTER=mt1
  ```

- [ ] **Tester la connexion Pusher**
  - Ouvrir un match en direct
  - Ajouter un événement depuis l'admin
  - Vérifier que ça s'affiche en temps réel sur une autre page

- [ ] **Vérifier Laravel Echo**
  ```bash
  npm run dev
  ```

---

## ⚡ Phase 6: Performance et Optimisation

- [ ] **Build du frontend**
  ```bash
  npm run build
  ```

- [ ] **Optimiser Laravel**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan optimize
  ```

- [ ] **Vérifier les logs**
  ```bash
  tail -f storage/logs/laravel.log
  ```

- [ ] **Tester la vitesse de chargement**
  - Page d'accueil: < 2 secondes
  - Pages de détails: < 1 seconde

---

## 🔒 Phase 7: Sécurité

- [ ] **Vérifier les permissions**
  - `storage/` et `bootstrap/cache/` doivent être writable
  - `.env` ne doit pas être accessible via web

- [ ] **Tester la protection CSRF**
  - Essayer de poster un formulaire sans token (doit échouer)

- [ ] **Vérifier les injections SQL**
  - Tester les URLs avec des IDs invalides
  - Vérifier que les erreurs ne révèlent pas d'info sensible

- [ ] **Configurer HTTPS**
  - Rediriger HTTP vers HTTPS
  - Mettre à jour `APP_URL`

- [ ] **Sauvegardes automatiques**
  - Configurer une sauvegarde BDD quotidienne

---

## 📋 Phase 8: Checklist Finale Avant Production

### Fonctionnalités Validées

- [ ] Inscription/Connexion fonctionne
- [ ] Pages publiques s'affichent correctement
- [ ] Panneau admin est accessible
- [ ] CRUD Universités ok
- [ ] CRUD Équipes ok
- [ ] CRUD Joueurs ok
- [ ] CRUD Matchs ok
- [ ] Matchs en direct fonctionnent
- [ ] Événements s'affichent en temps réel
- [ ] Classements se calculent automatiquement
- [ ] Images/logos s'affichent

### Sécurité Validée

- [ ] HTTPS activé
- [ ] Sauvegardes configurées
- [ ] Logs monitorés
- [ ] Permissions correctes
- [ ] Pas d'erreurs exposées

### Performance Validée

- [ ] Temps de chargement < 3 secondes
- [ ] Build optimisé
- [ ] Cache activé

---

## 🚀 Lancement en Production

- [ ] **Faire une dernière sauvegarde BDD**
- [ ] **Vérifier les variables `.env`**
  - `APP_ENV=production`
  - `APP_DEBUG=false`
- [ ] **Tester une dernière fois en local**
- [ ] **Deployer sur le serveur**
- [ ] **Vérifier que tout fonctionne en production**
- [ ] **Monitorer les logs**

---

## 📞 En Cas de Problème

| Problème | Solution |
|----------|----------|
| Erreur 500 | Vérifier `storage/logs/laravel.log` |
| Images pas affichées | Vérifier `php artisan storage:link` |
| BDD connection failed | Vérifier `.env` et PostgreSQL |
| Pusher pas connecté | Vérifier credentials et firewall |
| Pages lentes | Activer le cache Laravel |

---

**Date de début:** _______________
**Date de fin:** _______________
**Statut final:** ⬜ Non commencé | 🔄 En cours | ✅ Terminé
