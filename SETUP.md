# CarShare - Plateforme de Covoiturage

## Installation et Configuration

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) avec mod_rewrite activé

### Configuration de la Base de Données

1. Créer la base de données MySQL nommée `covoiturage`
2. Exécuter le script de création des tables (fourni dans le problème)
3. Importer les données de localisation :
   ```bash
   mysql -u root -p covoiturage < init_locations.sql
   ```

4. Configuration de la connexion dans `model/Database.php` :
   ```php
   private static $dbName   = 'covoiturage';
   private static $host     = 'localhost';
   private static $user     = 'root';
   private static $password = ''; 
   ```

### Structure du Projet

Le projet suit l'architecture MVC (Modèle-Vue-Contrôleur) :

```
/
├── index.php                 # Point d'entrée principal avec routage
├── controller/               # Contrôleurs
│   ├── HomeController.php
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── ProfileController.php
│   ├── CarpoolingController.php
│   ├── PaymentController.php
│   ├── BookingController.php
│   ├── AdminController.php
│   ├── FAQController.php
│   ├── RatingController.php
│   └── SignalementController.php
├── model/                    # Modèles (logique métier)
│   ├── Database.php
│   ├── LoginModel.php
│   ├── RegisterModel.php
│   ├── ProfileModel.php
│   ├── CarpoolingModel.php
│   ├── BookingModel.php
│   ├── AdminModel.php
│   └── ...
├── view/                     # Vues (interface utilisateur)
│   ├── components/
│   │   ├── header.php
│   │   └── footer.php
│   ├── HomeView.php
│   ├── LoginView.php
│   ├── RegisterView.php
│   └── ...
├── assets/                   # Ressources statiques
│   ├── styles/              # CSS
│   ├── img/                 # Images
│   └── js/                  # JavaScript
└── statique HTML/           # Pages HTML statiques originales (backup)
```

## Fonctionnalités Implémentées

### 1. Authentification
- **Inscription** (`/index.php?action=register`) : Création de compte utilisateur
- **Connexion** (`/index.php?action=login`) : Authentification avec email/mot de passe
- **Déconnexion** (`/index.php?action=logout`)

### 2. Gestion de Profil
- **Profil utilisateur** (`/index.php?action=profile`) : Modification des informations personnelles et du véhicule

### 3. Gestion des Trajets
- **Recherche de trajets** (`/index.php?action=search`) : Rechercher des covoiturages disponibles
- **Créer un trajet** (`/index.php?action=create_trip`) : Publier un nouveau trajet
- **Détails du trajet** (`/index.php?action=trip_details&id=X`) : Voir les détails d'un trajet

### 4. Réservations
- **Paiement** (`/index.php?action=payment&carpooling_id=X`) : Réserver et payer un trajet
- **Confirmation** (`/index.php?action=booking_confirmation&booking_id=X`) : Confirmation de réservation
- **Historique** (`/index.php?action=history`) : Voir ses trajets passés et à venir

### 5. Évaluation et Signalement
- **Notation** (`/index.php?action=rating`) : Évaluer un conducteur après un trajet
- **Signalement** (`/index.php?action=signalement`) : Signaler un utilisateur

### 6. Administration
- **Dashboard Admin** (`/index.php?action=admin`) : Tableau de bord administrateur avec statistiques
  - Nécessite is_admin = 1 dans la table users

### 7. Autres
- **FAQ** (`/index.php?action=faq`) : Questions fréquentes

## Utilisation

### Accès à l'Application
- URL principale : `http://localhost/CarShare/index.php`
- Page d'accueil : `http://localhost/CarShare/index.php?action=home`

### Routes Principales
| Action | URL | Description |
|--------|-----|-------------|
| home | `?action=home` | Page d'accueil |
| login | `?action=login` | Connexion |
| register | `?action=register` | Inscription |
| profile | `?action=profile` | Profil utilisateur |
| create_trip | `?action=create_trip` | Créer un trajet |
| create_trip_submit | `?action=create_trip_submit` | Soumettre un nouveau trajet |
| search | `?action=search` | Rechercher un trajet |
| trip_details | `?action=trip_details&id=X` | Détails d'un trajet |
| payment | `?action=payment&carpooling_id=X` | Paiement |
| history | `?action=history` | Historique |
| rating | `?action=rating` | Noter un conducteur |
| rating_submit | `?action=rating_submit` | Soumettre une notation |
| rating_get_carpoolings | `?action=rating_get_carpoolings&user_id=X` | API: Obtenir les trajets d'un utilisateur |
| signalement | `?action=signalement` | Signaler un utilisateur |
| signalement_submit | `?action=signalement_submit` | Soumettre un signalement |
| signalement_get_carpoolings | `?action=signalement_get_carpoolings&user_id=X` | API: Obtenir les trajets d'un utilisateur |
| admin | `?action=admin` | Dashboard admin |
| faq | `?action=faq` | FAQ |

### Accès Direct aux Pages

En plus de l'accès via `index.php`, les pages principales peuvent être accédées directement :
- **Notation** : `http://localhost/CarShare/rating.php` ou `rating.php?action=view`
- **Signalement** : `http://localhost/CarShare/signalement.php` ou `signalement.php?action=view`
- **Créer un trajet** : `http://localhost/CarShare/create-trip.php` ou `create-trip.php?action=view`

## Base de Données

### Structure des Tables

- **users** : Utilisateurs de la plateforme
- **location** : Villes et localisations
- **carpoolings** : Trajets proposés
- **bookings** : Réservations de trajets

### Créer un Compte Admin

```sql
UPDATE users SET is_admin = 1 WHERE email = 'admin@carshare.com';
```

## Sécurité

- Les mots de passe sont hashés avec `password_hash()` (bcrypt)
- Protection contre les injections SQL avec PDO et requêtes préparées
- Validation des entrées utilisateur côté client (JavaScript) et serveur (PHP)
- Sessions PHP sécurisées
- Protection contre l'auto-notation et l'auto-signalement
- Validation des numéros de voie et des prix
- Gestion des valeurs NULL dans la base de données

## Validation des Formulaires

### Création de Trajet
- **Obligatoire** : Ville de départ, ville d'arrivée, date, nombre de places
- **Optionnel** : Rue, numéro de voie, heure, prix, options (animaux, fumeur)
- **Validations** :
  - Les villes doivent être différentes
  - La date doit être dans le futur
  - Le nombre de places doit être entre 1 et 10
  - Les numéros de voie doivent être positifs
  - Le prix doit être positif

### Notation d'Utilisateur
- Sélection d'un utilisateur et d'un trajet (excluant l'utilisateur actuel)
- Note de 1 à 5 étoiles (obligatoire)
- Commentaire (optionnel)
- Impossible de se noter soi-même

### Signalement d'Utilisateur
- Sélection d'un utilisateur et d'un trajet (excluant l'utilisateur actuel)
- Motif obligatoire
- Description obligatoire (minimum 10 caractères)
- Impossible de se signaler soi-même

## Notes de Développement

- Toutes les pages HTML statiques originales ont été sauvegardées dans le dossier `statique HTML/`
- Les vues utilisent les composants `header.php` et `footer.php` pour éviter la duplication
- Le système utilise des sessions PHP pour gérer l'authentification
- Les routes sont gérées par le fichier `index.php` principal

## TODO / Améliorations Futures

- Implémenter la vérification d'email
- Ajouter une messagerie entre utilisateurs
- Système de paiement réel (intégration Stripe/PayPal)
- Upload de photo de profil
- Filtres avancés de recherche
- Notifications en temps réel
- API REST pour application mobile
