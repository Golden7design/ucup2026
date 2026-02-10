# 🚧 Problèmes à Résoudre - Projet UCup

## 1. 🗄️ Base de Données

### Migrations et Seeders
- [ ] Exécuter `php artisan migrate` pour créer les tables
- [ ] Créer des seeders pour les données de test (universités, équipes, joueurs)
- [ ] Vérifier que toutes les 30+ migrations passent sans erreur

### Structure Tables Requise
Les tables suivantes doivent exister et être poblées:
- `users` - utilisateurs et admins
- `universities` - universités participantes
- `teams` - équipes
- `players` - joueurs avec statistiques
- `matches` - matchs avec scores et statuts
- `match_events` - événements (buts, cartons, substitutions)
- `match_lineups` - compositions d'équipes
- `standings` - classements

---

## 2. 👤 Création Administrateur

- [ ] Créer un compte admin pour accéder au panneau d'administration
- [ ] Commande: `php artisan create_admin` ou insertion manuelle en BDD
- [ ] Le champ `is_admin` doit être à `1` pour l'utilisateur admin

---

## 3. 🎨 Configuration Frontend

### Installation des dépendances
```bash
npm install
```

### Build Production
```bash
npm run build
```

### Storage Link
```bash
php artisan storage:link
```
Nécessaire pour les photos de joueurs, logos d'équipes et images de galerie.

---

## 4. 🔐 Système d'Authentification

### État Actuel
Laravel Fortify est installé mais des éléments manquent:

- [ ] Configurer les vues de login/registration
- [ ] Tester la connexion
- [ ] Tester la déconnexion
- [ ] Configurer la vérification email (optionnel)
- [ ] Configurer l'authentification à deux facteurs (optionnel)

### Middleware Admin
- [ ] Vérifier que le middleware `is_admin` fonctionne correctement
- [ ] Tester l'accès aux routes `/admin/*` sans être admin

---

## 5. 📡 Temps Réel (WebSocket)

### Pusher/Laravel Echo
Le projet utilise Pusher pour les mises à jour temps réel:

- [ ] Créer un compte sur [pusher.com](https://pusher.com)
- [ ] Créer une app Pusher Channels
- [ ] Configurer les credentials dans `.env`:
  ```
  PUSHER_APP_ID=votre_app_id
  PUSHER_APP_KEY=votre_key
  PUSHER_APP_SECRET=votre_secret
  PUSHER_HOST=votre_host
  PUSHER_CLUSTER=mt1
  ```

### Événements Broadcast
Les événements suivants sont prêts mais nécessitent Pusher:
- `MatchEventOccurred` - Buts, cartons, substitutions
- `MatchStatusOrStatsUpdated` - Changements de statut et stats
- `MatchUpdated` - Mise à jour générale du match
- `StandingsUpdated` - Classement mis à jour

---

## 6. 🖼️ Assets et Médias

### Éléments Manquants
- [ ] **Logos équipes** - À uploader dans `storage/app/public/teams/`
- [ ] **Photos joueurs** - À uploader dans `storage/app/public/players/`
- [ ] **Images galerie** - À uploader dans `storage/app/public/gallery/`

### Commandes Utiles
```bash
# Créer le lien symbolique
php artisan storage:link

# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 7. 📄 Pages et Fonctionnalités

### Pages Frontend (Blade)
- [x] `home.blade.php` - Page d'accueil
- [x] `matches/` - Liste et détails des matchs
- [x] `players/` - Liste et profils joueurs
- [x] `standings/` - Classements
- [x] `teams/` - Liste équipes
- [x] `gallery/` - Galerie photos

### Panneau Admin (Filament)
- [ ] Vérifier que Filament est accessible via `/admin`
- [ ] Configurer les ressources Filament si nécessaire
- [ ] Tester le CRUD sur chaque entité

### Pages Inertia (React)
Le projet contient aussi des pages React via Inertia qui ne sont pas encore connectées:
- [ ] Dashboard React (`/dashboard`)
- [ ] Matchs React (`/matches`)
- [ ] Joueurs React (`/players`)
- [ ] Classements React (`/standings`)

**Décision requise**: Utiliser Blade ou React/Inertia?

---

## 8. 🧪 Tests

### Tests Unitaires
- [ ] Créer des tests pour les modèles
- [ ] Créer des tests pour les contrôleurs
- [ ] Créer des tests pour les services (StandingService)

### Exécuter les Tests
```bash
php artisan test
```

---

## 9. 📚 Documentation

### Documentation Manquante
- [ ] README.md principal
- [ ] Documentation API
- [ ] Guide d'utilisation pour les admins
- [ ] Guide d'utilisation pour les utilisateurs

---

## 10. ⚡ Optimisations

### Performance
- [ ] Activer le caching en production
- [ ] Optimiser les requêtes Eloquent (lazy loading)
- [ ] Activer le mode maintenance si nécessaire

### Logs
- [ ] Configurer la rotation des logs
- [ ] Vérifier les logs d'erreurs régulièrement

---

## 11. 🚀 Déploiement

### Préparation Production
```bash
# Build du frontend
npm run build

# Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimisation
php artisan optimize
```

### Plateformes Supportées
Le projet supporte plusieurs plateformes:
- **Vercel** - Frontend serverless
- **Railway** - Backend complet
- **InfinityFree** - Hébergement gratuit
- **Docker** - Conteneurisation

---

## 📋 Ordre de Priorité

### Phase 1 - Fondations (Urgent)
1. Configurer la base de données
2. Créer l'admin
3. Tester l'authentification

### Phase 2 - Données (Important)
4. Remplir les universités
5. Remplir les équipes
6. Remplir les joueurs
7. Créer des matchs

### Phase 3 - Fonctionnalités (Normal)
8. Tester les pages publiques
9. Tester le panneau admin
10. Configurer le temps réel

### Phase 4 - Polish (Optionnel)
11. Optimisations
12. Documentation
13. Tests

---

## 🐛 Problèmes Connus

1. **Double système frontend** - Blade et Inertia cohabitent, décision à prendre
2. **Données de test manquantes** - Aucun seeder pour peupler la BDD
3. **Images manquantes** - Pas de logos/photos par défaut
4. **Tests non implémentés** - Suite de tests vide

---

## 💡 Recommandations

1. **Commencer simple** - Utiliser uniquement Blade pour le MVP
2. **Peupler la BDD** - Créer des seeders avec fausses données
3. **Images par défaut** - Ajouter des placeholder images
4. **Logs activés** - Activer APP_DEBUG en dev pour le debugging

---

## ⏱️ Estimation du Temps de Développement

**Hypothèses:**
- 2-4 heures/jour avec assistance IA
- Connaissances de base en Laravel/React
- Base de données du concepteur disponible

---

### 📊 Récapitulatif des Tâches

| Catégorie | Tâches | Complexité | Temps Estimé |
|-----------|--------|------------|--------------|
| **Installation** | Config BDD, dépendances, env | Faible | 1-2 heures |
| **Base de données** | Importer dump existant | Faible | 30 min - 1h |
| **Admin** | Créer compte admin | Faible | 30 min |
| **Médias** | Copier logos/photos | Faible | 1-2 heures |
| **Tests** | Vérifier chaque page | Moyenne | 2-4 heures |
| **Bug fixes** | Corrections imprévues | Variable | 2-8 heures |
| **Temps réel** | Config Pusher (optionnel) | Moyenne | 2-4 heures |
| **Optimisation** | Cache, perfs | Moyenne | 2-4 heures |
| **Documentation** | README, guides | Faible | 1-2 heures |

---

### 🎯 Scénarios de Temps

#### Scenario A: Import dump BDD existant + pas de modifs
| Phase | Temps |
|-------|-------|
| Installation dépendances | 1-2h |
| Import BDD | 1h |
| Tests & validation | 2-4h |
| **Total** | **4-7 heures** |

#### Scenario B: Import dump + quelques bugs + temps réel
| Phase | Temps |
|-------|-------|
| Installation | 1-2h |
| Import BDD | 1h |
| Bug fixes | 2-8h |
| Temps réel (Pusher) | 2-4h |
| Tests finaux | 2-4h |
| **Total** | **8-18 heures** |

#### Scenario C: Partir de zéro (sans dump)
| Phase | Temps |
|-------|-------|
| Installation | 1-2h |
| Configurer BDD | 1h |
| Créer seeders | 2-4h |
| Peupler données | 2-4h |
| Tests & corrections | 4-8h |
| **Total** | **10-18 heures** |

---

### 🚀 Plan d'Action Recommandé

**Jour 1 (2-4h):**
- [ ] Installer dépendances (`composer install`, `npm install`)
- [ ] Configurer `.env` avec BDD locale
- [ ] Tester connexion BDD

**Jour 2 (2-4h):**
- [ ] Importer dump SQL de ton ami
- [ ] Copier fichiers médias
- [ ] Créer compte admin
- [ ] Tester homepage

**Jour 3 (2-4h):**
- [ ] Tester toutes les pages
- [ ] Tester panneau admin
- [ ] Identifier et corriger bugs

**Jour 4+ (si nécessaire):**
- [ ] Configurer Pusher (temps réel)
- [ ] Optimisations
- [ ] Documentation

---

### ⚡ Facteurs qui peuvent accélérer

1. **Dump BDD disponible** → Gagne 4-8h
2. **Images déjà préparées** → Gagne 1-2h
3. **Bug fixes simples** → Gagne du temps
4. **Aide IA continue** → Accélère le debugging

### ⚠️ Facteurs qui peuvent ralentir

1. **Problèmes de compatibilité BDD** → +2-4h
2. **Fichiers médias corrompus** → +1-2h
3. **Bugs complexes** → +4-8h
4. **Configuration serveur** → +2-4h

---


---

## 📥 Importer la Base de Données d'Origine

Ton ami utilise PostgreSQL avec la base `ucup_db` et le mot de passe `elmish2003`.

### Étape 1: Demander à ton ami d'exporter sa base

Il doit exécuter cette commande sur son serveur:

```bash
# Export complet (structure + données)
pg_dump -U postgres -d ucup_db > ucup_backup.sql
```

**Demande-lui aussi de te partager son dossier `storage/app/public/`** (logos équipes, photos joueurs, images galerie).

### Étape 2: Configurer ton .env

Crée un fichier `.env` dans ton projet avec ces valeurs exactes:

```env
APP_NAME=UCUP
APP_ENV=local
APP_KEY=base64:WRRD3ByFIDp53mY9y5jNOYbvNHWauw7rf3xBTmCqQbY=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ucup_db
DB_USERNAME=postgres
DB_PASSWORD=elmish2003
DB_SSLMODE=prefer

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=pusher
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="emoukouanga@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Pusher - Temps réel
PUSHER_APP_ID=1937103
PUSHER_APP_KEY=1e779a950482592f6dae
PUSHER_APP_SECRET=203792019446d3e75871
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

CORS_ALLOWED_ORIGINS=http://localhost:8000,http://127.0.0.1:8000
```

### Étape 3: Importer le dump PostgreSQL

```bash
# 1. Créer la base locale (si pas déjà faite)
createdb -U postgres ucup_db

# 2. Importer le dump
psql -U postgres -d ucup_db < ucup_backup.sql
```

### Étape 4: Copier les fichiers médias

Copie les dossiers suivants de ton ami vers ton `storage/app/public/`:
- `teams/` - Logos équipes
- `players/` - Photos joueurs
- `gallery/` - Images galerie

### Étape 5: Lancer le projet

```bash
# Installer dépendances
composer install
npm install

# Créer le lien symbolique storage
php artisan storage:link

# Nettoyer les caches
php artisan config:clear
php artisan cache:clear

# Lancer le serveur
php artisan serve
```

### 🎯 Résultat Attendu

Une fois ces étapes terminées:
- ✅ Base de données identique à ton ami
- ✅ Temps réel (Pusher) configuré
- ✅ Images/logos affichés
- ✅ Accès admin: email `emoukouanga@gmail.com`, mot de passe `AdminUCup2026`

---



