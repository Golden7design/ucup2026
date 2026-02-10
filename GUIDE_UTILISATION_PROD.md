# 📖 Guide d'Utilisation en Production - UCup

## Table des Matières

1. [Gestion des Universités](#1-gestion-des-universités)
2. [Gestion des Équipes](#2-gestion-des-équipes)
3. [Gestion des Joueurs](#3-gestion-des-joueurs)
4. [Création d'un Match](#4-création-dun-match)
5. [Gestion des Matchs en Direct](#5-gestion-des-matchs-en-direct)
6. [Événements de Match](#6-événements-de-match)
7. [Classements Automatiques](#7-classements-automatiques)
8. [Galerie Photos](#8-galerie-photos)

---

## 1. Gestion des Universités

### Créer une Université

1. Se connecter au panneau admin (`/admin`)
2. Naviguer vers **Universités** → **Créer**
3. Remplir le formulaire:
   - **Nom complet**: Université de Pointe-Noire
   - **Nom court**: UPN
   - **Couleurs**: Blue/White (optionnel)
   - **Description**: Description de l'université (optionnel)
4. Cliquer sur **Créer**

### Modifier/Supprimer

- Depuis la liste des universités, utiliser les boutons d'action
- La suppression n'est possible que si aucune équipe n'est associée

---

## 2. Gestion des Équipes

### Créer une Équipe

1. **D'abord créer l'université** associée
2. Naviguer vers **Équipes** → **Créer**
3. Remplir le formulaire:
   - **Université**: Sélectionner l'université parente
   - **Nom de l'équipe**: UPN Lions
   - **Entraîneur**: Nom du coach
   - **Catégorie**: Senior / Junior / U20 / etc.
   - **Année**: 2025
4. Cliquer sur **Créer**

### Affecter des Joueurs

1. Éditer l'équipe créée
2. Ajouter des joueurs via l'interface
- Ou créer les joueurs d'abord puis les associer

---

## 3. Gestion des Joueurs

### Créer un Joueur

1. Naviguer vers **Joueurs** → **Créer**
2. Remplir le formulaire:
   - **Prénom**: John
   - **Nom**: Doe
   - **Numéro de camiseta**: 10
   - **Poste**: Attaquant / Milieu / Défenseur / Gardien
   - **Équipe**: Sélectionner l'équipe
   - **Date de naissance**: JJ/MM/AAAA
   - **Nationalité**: CG (Congo)
   - **Taille**: en cm (optionnel)
3. Cliquer sur **Créer**

### Import en Masse

Pour créer plusieurs joueurs d'un coup:

1. Naviguer vers **Joueurs** → **Import en masse**
2. Préparer un fichier CSV avec les colonnes:
   ```
   first_name,last_name,jersey_number,position,team_id
   John,Doe,10,forward,1
   Jane,Smith,5,defender,1
   ```
3. Uploader et valider

---

## 4. Création d'un Match

### Étape 1: Créer le Match

1. Naviguer vers **Matchs** → **Créer**
2. Remplir le formulaire:
   - **Équipe à domicile**: Sélectionner l'équipe
   - **Équipe visiteuse**: Sélectionner l'équipe
   - **Date et heure**: JJ/MM/AAAA HH:MM
   - **Lieu**: Stade Municipal
   - **Type de match**: Phase de groupes / Eliminatoire / Amical
   - **Groupe**: A, B, C... (si phase de groupes)
   - **Journée**: 1, 2, 3...
3. Cliquer sur **Créer**

### Étape 2: Définir les Compositions (Avant le Match)

1. Éditer le match créé
2. Cliquer sur **Composition d'équipe**
3. Pour chaque équipe:
   - Sélectionner les 11 titulaires
   - Sélectionner les remplaçants
   - Définir la formation tactique (ex: 4-4-2, 4-3-3)
   - Assigner le capitaine
4. Cliquer sur **Enregistrer la composition**

### Étape 3: Valider pour le Live

Avant de démarrer le match:
- [ ] Au moins 11 titulaires par équipe
- [ ] Formation tactique définie pour les deux équipes
- [ ] Joueurs correctement positionnés

---

## 5. Gestion des Matchs en Direct

### Démarrer un Match

1. Naviguer vers **Admin** → **Matchs en direct**
2. Cliquer sur le match à démarrer
3. Cliquer sur **Démarrer le match**
4. Le statut passe à "LIVE"
5. Le minuteur commence

### Actions Disponibles en Direct

| Action | Description |
|--------|-------------|
| **Marquer un but** | Ajouter un événement "But" |
| **Carton jaune** | Ajouter un avertissement |
| **Carton rouge** | Exclure le joueur |
| **Substitution** | Remplacer un joueur |
| **Mettre en pause** | Arrêter le minuteur |
| **Reprendre** | Redémarrer le minuteur |
| **Mi-temps** | Passer en pause mi-temps |
| **Terminer** | Finir le match |

---

## 6. Événements de Match

### Ajouter un But

1. Cliquer sur **Ajouter un événement**
2. Type: **But**
3. Équipe: Domicile / Extérieur
4. Joueur: Sélectionner le buteur
5. Passe décisive: Sélectionner le passeur (optionnel)
6. Minute: 23' (par exemple)
7. Cliquer sur **Ajouter**

### Ajouter un Carton

1. Cliquer sur **Ajouter un événement**
2. Type: **Carton jaune** ou **Carton rouge**
3. Équipe: Domicile / Extérieur
4. Joueur: Sélectionner le joueur sanctionné
5. Minute: 45'+2 (temps additionnel)
6. Cliquer sur **Ajouter**

### Effectuer une Substitution

1. Cliquer sur **Ajouter un événement**
2. Type: **Substitution**
3. Équipe: Domicile / Extérieur
4. Joueur entrant: Sélectionner
5. Joueur sortant: Sélectionner
6. Minute: 65' (par exemple)
7. Cliquer sur **Ajouter**

### Timeline des Événements

Tous les événements apparaissent automatiquement dans la timeline:
- Ordre chronologique
- Indicateurs visuels (jaune/rouge pour cartons)
- Lien vers les profils joueurs

---

## 7. Classements Automatiques

### Comment ça Marche

Les classements se calculent automatiquement après chaque match:

| Résultat | Points |
|----------|--------|
| Victoire | 3 points |
| Nul | 1 point |
| Défaite | 0 point |

### Critères de Classement

1. Points totaux
2. Différence de buts
3. Buts marqués
4. Résultats head-to-head

### Recalculer Manuellement

En cas de problème:

1. Naviguer vers **Admin** → **Classements**
2. Cliquer sur **Recalculer tous les classements**

### Données Affichées

- Position
- Équipe
- Matchs joués
- Victoires
- Nuls
- Défaites
- Buts marqués
- Buts encaissés
- Différence de buts
- Points

---

## 8. Galerie Photos

### Ajouter une Image

1. Naviguer vers **Admin** → **Galerie**
2. Cliquer sur **Ajouter une image**
3. Remplir le formulaire:
   - **Titre**: Finale U-Cup 2025
   - **Description**: Description de la photo
   - **Catégorie**: Match / Entraînement / Célébration
   - **Fichier**: Uploader l'image
4. Cliquer sur **Ajouter**

### Gérer les Images

- **Modifier**: Changer le titre, description ou catégorie
- **Supprimer**: Supprimer une image
- **Réordonner**: Modifier l'ordre d'affichage

### Catégories Disponibles

- Matchs
- Entraînements
- Cérémonies
- Équipes
- Joueurs
- Autres

---

## 🔧 Maintenance Quotidienne

### Avant un Match

1. Vérifier les compositions d'équipes
2. Confirmer les titulaires
3. Vérifier les statistiques joueurs

### Pendant un Match

1. Ouvrir le Live Center
2. Ajouter les événements en temps réel
3. Mettre à jour le score
4. Gérer les substitutions

### Après un Match

1. Terminer le match proprement
2. Vérifier les événements ajoutés
3. Consulter le classement mis à jour
4. Ajouter des photos à la galerie

---

## 📊 Statistiques Suivies

### Statistiques de Match

- Tirs
- Tirs cadrés
- Possession de balle
- Corners
- Fautes
- Hors-jeu
- Cartons jaunes/rouges

### Statistiques de Joueur

- Buts
- Passes décisives
- Minutes jouées
- Cartons reçus
- Matchs disputés

---

## 🚨 Résolution de Problèmes

### Le Match ne Démarre Pas

**Cause possible:** Composition incomplète
- Solution: Ajouter au moins 11 titulaires par équipe

### Les Points ne se Mettent pas à Jour

**Cause possible:** Match non marqué comme terminé
- Solution: Terminer le match dans le Live Center

### Les Images ne s'Affichent Pas

**Cause possible:** Lien symbolique non créé
- Solution: `php artisan storage:link`

### Le Temps Réel ne Fonctionne Pas

**Cause possible:** Pusher non configuré
- Solution: Vérifier les credentials Pusher dans `.env`

---

## 📱 Accès Mobile

L'application est responsive et fonctionne sur:
- Smartphones
- Tablettes
- Ordinateurs

Toutes les fonctionnalités sont disponibles sur mobile, y compris le Live Center pour les admins.

---

## 🔐 Rôles et Permissions

| Rôle | Permissions |
|------|-------------|
| **Admin** | Accès complet à toutes les fonctionnalités |
| **Modérateur** | Gestion des matchs en direct uniquement |
| **Utilisateur** | Pages publiques seulement |

---

## 📞 Support

Pour toute question ou problème:

1. Consulter les logs: `storage/logs/laravel.log`
2. Vérifier la documentation
3. Contacter l'équipe de développement
