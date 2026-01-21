# Architecture de la Partie Admin - CarShare

## Vue d'ensemble

La partie admin de CarShare est une interface d'administration complète permettant de gérer les utilisateurs, trajets, véhicules et statistiques de la plateforme de covoiturage. Elle fonctionne en parallèle du site principal avec son propre système d'authentification et ses propres vues.

---

## 📁 Structure des Fichiers Admin

### Contrôleurs (Controller/)
```
AdminController.php                      # Vide (legacy)
AdminControllerUnified.php              # Contrôleur principal unifié pour toutes les actions admin
AdminLoginController.php                # Gestion de la connexion admin
AdminRegisterController.php             # Inscription des nouveaux admins
AdminEmailValidationController.php      # Validation email pour admins
```

### Modèles (Model/)
```
AdminModel.php                          # Modèle basique (legacy)
AdminModelEnhanced.php                  # Modèle principal avec toutes les fonctions
```

### Vues (View/)
```
admin_layout.php                        # Layout principal avec sidebar + topbar
admin_login.php                         # Page de connexion admin
admin_dashboard.php                     # Dashboard (legacy)

admin/                                  # Dossier des vues modernes
├── dashboard_content.php               # Contenu du tableau de bord
├── users_content.php                   # Liste des utilisateurs
├── user_details_content.php            # Détails d'un utilisateur
├── trips_content.php                   # Gestion des trajets
├── vehicles_content.php                # Gestion des véhicules
└── profile_content.php                 # Profil admin
```

### Assets
```
assets/styles/admin-modern.css          # Style principal de l'interface admin
assets/js/admin-autosuggest.js          # Autocomplétion admin
assets/js/admin-alerts.js               # Système d'alertes
```

---

## 🔄 Routing et Intégration dans le Site Principal

### Point d'Entrée Unique : index.php

Le fichier **index.php** est le routeur principal du site. Il gère à la fois les routes utilisateurs et les routes admin.

#### Routes Admin Unifiées (lignes 156-213)
```php
// Admin routes (unified controller) - EXCLUDE registration routes
$excludedAdminRoutes = ['admin_register', 'admin_registration_pending', 'admin_email_validation', 'admin_login'];
if (strpos($action, 'admin_') === 0 && !in_array($action, $excludedAdminRoutes)) {
    require_once __DIR__ . "/controller/AdminControllerUnified.php";
    $controller = new AdminControllerUnified();
    
    switch ($action) {
        case 'admin_dashboard':
            $controller->dashboard();
            break;
        case 'admin_users':
            $controller->users();
            break;
        case 'admin_user_details':
            $controller->userDetails();
            break;
        // ... autres actions admin
    }
    exit; // Important : empêche le rendu du header/footer
}
```

**Points clés :**
- Toute action commençant par `admin_` est routée vers `AdminControllerUnified`
- Les routes d'inscription/login admin sont exclues du contrôleur unifié
- `exit;` après le traitement empêche l'affichage du header/footer du site

#### Routes Admin Séparées (dans le switch principal)
```php
case "admin_login":
    require_once __DIR__ . "/controller/AdminLoginController.php";
    (new AdminLoginController())->render();
    break;

case "admin_register":
    require_once __DIR__ . "/controller/AdminRegisterController.php";
    (new AdminRegisterController())->render();
    break;
```

---

## 🔐 Système d'Authentification

### 1. Connexion Admin (AdminLoginController)

**Flux de connexion :**
1. Utilisateur accède à `?action=admin_login`
2. Affichage du formulaire de connexion spécifique admin
3. Soumission vers `?action=admin_process_login`
4. Vérification des credentials via `LoginModel`
5. Vérification supplémentaire du flag `is_admin = 1` dans la base de données
6. Création de la session avec `$_SESSION['is_admin'] = 1`
7. Redirection vers le dashboard

**Différences avec la connexion normale :**
- Vérification obligatoire de `is_admin = 1`
- Interface de login distincte (violet au lieu de bleu)
- Stockage de `$_SESSION['is_admin']` pour les vérifications ultérieures

### 2. Vérification d'Authentification

Chaque méthode du `AdminControllerUnified` appelle :
```php
private function checkAdminAuth() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        redirect(url('index.php?action=admin_login'));
        exit;
    }
}
```

### 3. Base de données

La table `users` contient un champ `is_admin` :
```sql
is_admin TINYINT(1) DEFAULT 0
```
- `0` = utilisateur normal
- `1` = administrateur

---

## 🎨 Layout et Interface

### Structure du Layout (admin_layout.php)

```
┌─────────────────────────────────────────────┐
│              Topbar                         │
│  [Titre]          [Nom Admin] [Avatar]      │
├──────────┬──────────────────────────────────┤
│          │                                   │
│ Sidebar  │        Main Content              │
│          │                                   │
│ - Dashboard                                  │
│ - Utilisateurs   [Contenu dynamique]        │
│ - Trajets                                   │
│ - Véhicules                                 │
│ - Mon profil                                │
│ - Voir le site                              │
│ - Déconnexion                               │
│          │                                   │
└──────────┴──────────────────────────────────┘
```

**Composants :**
- **Sidebar** : Navigation fixe avec liens actifs
- **Topbar** : Titre de la page + infos utilisateur
- **Main Content** : Zone dynamique chargée via `$content`

**Utilisation :**
```php
// Dans AdminControllerUnified
ob_start();
require_once __DIR__ . '/../view/admin/dashboard_content.php';
$content = ob_get_clean();

require_once __DIR__ . '/../view/admin_layout.php';
```

---

## 📊 Fonctionnalités Admin

### 1. Dashboard (`admin_dashboard`)
- Statistiques globales (utilisateurs, trajets, réservations, revenus)
- Graphiques et métriques
- Transactions récentes

### 2. Gestion Utilisateurs (`admin_users`)
**Fonctionnalités :**
- Liste paginée des utilisateurs (20 par page)
- Recherche par nom/email
- Filtres (vérifiés/non vérifiés)
- Voir détails utilisateur (`admin_user_details`)
- Supprimer utilisateur (`admin_delete_user`)
- Toggle vérification (`admin_toggle_verification`)
- Réinitialiser mot de passe (`admin_reset_user_password`)

### 3. Gestion Trajets (`admin_trips`)
- Liste des trajets
- Supprimer trajet (`admin_delete_trip`)
- Voir détails

### 4. Gestion Véhicules (`admin_vehicles`)
- Liste des véhicules enregistrés
- Statistiques par marque/modèle

### 5. Profil Admin (`admin_profile`)
- Voir/modifier profil
- Changer mot de passe (`admin_password_update`)
- Supprimer compte (`admin_delete_account`)

---

## 🔌 Modèle de Données (AdminModelEnhanced)

### Principales Méthodes

#### Authentification
```php
authenticateAdmin($email, $password)  // Connexion admin
```

#### Dashboard
```php
getDashboardStats()                   // Statistiques globales
getRecentTransactions($limit)         // Dernières transactions
```

#### Utilisateurs
```php
getUsers($page, $limit, $search, $filter)       // Liste paginée
getUsersCount($search, $filter)                 // Total pour pagination
getUserDetails($userId)                          // Détails complets
getUserStats($userId)                            // Stats utilisateur
getUserHistory($userId)                          // Historique
deleteUser($userId)                              // Suppression
toggleUserVerification($userId)                  // Toggle vérification
resetUserPassword($userId, $newPassword)         // Reset MDP
```

#### Trajets
```php
getTrips($page, $limit, $search)                // Liste trajets
getTripsCount($search)                           // Total trajets
deleteTrip($tripId)                              // Suppression
```

#### Véhicules
```php
getVehicles($page, $limit, $search)             // Liste véhicules
getVehiclesCount($search)                        // Total véhicules
```

---

## 🔗 Connexion avec le Site Principal

### Séparation des Interfaces

1. **Header/Footer** : L'interface admin n'utilise PAS les composants header/footer du site principal
   ```php
   // index.php ligne 154
   if (strpos($action, 'admin_') === 0 && !in_array($action, $excludedAdminRoutes)) {
       // ... traitement admin
       exit; // Pas de header/footer
   }
   ```

2. **Base de données partagée** : Admin et site utilisent la même DB
   - Table `users` commune avec flag `is_admin`
   - Accès complet aux données (carpoolings, bookings, etc.)

3. **Session partagée** : 
   - Même système de session PHP
   - Variable `$_SESSION['is_admin']` pour différencier

4. **Assets partagés** : 
   - Fonction `asset()` commune
   - Styles admin séparés (`admin-modern.css`)

### Liens Inter-Sites

**De Admin vers Site :**
```html
<a href="<?= url('index.php?action=home') ?>">Voir le site</a>
```

**De Site vers Admin :**
- Pas de lien direct (sécurité)
- Accès uniquement via URL directe : `?action=admin_login`

---

## 🛠️ Procédure de Merge

### Fichiers à Fusionner

#### 1. Contrôleurs (priorité HAUTE)
```
✅ Ajouter : AdminControllerUnified.php
✅ Ajouter : AdminLoginController.php
✅ Ajouter : AdminRegisterController.php
✅ Ajouter : AdminEmailValidationController.php
⚠️  Vérifier : AdminController.php (actuellement vide)
```

#### 2. Modèles (priorité HAUTE)
```
✅ Ajouter : AdminModelEnhanced.php
⚠️  Vérifier : AdminModel.php (legacy, peut être supprimé)
```

#### 3. Vues (priorité HAUTE)
```
✅ Ajouter : admin_layout.php
✅ Ajouter : admin_login.php
✅ Ajouter : view/admin/* (tous les fichiers)
⚠️  Vérifier : admin_dashboard.php (legacy)
```

#### 4. Assets (priorité MOYENNE)
```
✅ Ajouter : assets/styles/admin-modern.css
✅ Ajouter : assets/js/admin-autosuggest.js
✅ Ajouter : assets/js/admin-alerts.js
```

#### 5. Routes dans index.php (priorité CRITIQUE)
```
✅ Ajouter : Section de routing admin unifié (lignes 156-213)
✅ Ajouter : Routes admin_login, admin_register dans switch
✅ Ajouter : CSS/JS admin dans les tableaux $pageCss et $pageJs
```

### Étapes de Merge

1. **Backup de la branche principale**
   ```bash
   git checkout main
   git pull origin main
   git checkout -b backup-before-admin-merge
   ```

2. **Créer branche de merge**
   ```bash
   git checkout main
   git checkout -b feature/admin-integration
   ```

3. **Copier fichiers admin**
   - Copier tous les contrôleurs admin
   - Copier tous les modèles admin
   - Copier toutes les vues admin
   - Copier les assets admin

4. **Modifier index.php**
   - Ajouter la section de routing admin unifié
   - Ajouter les routes login/register admin
   - Ajouter CSS/JS admin dans les tableaux

5. **Tester l'intégration**
   ```
   ✓ Connexion admin : ?action=admin_login
   ✓ Dashboard : ?action=admin_dashboard
   ✓ Liste utilisateurs : ?action=admin_users
   ✓ Déconnexion : ?action=admin_logout
   ✓ Vérifier que le site principal fonctionne toujours
   ```

6. **Merger dans main**
   ```bash
   git add .
   git commit -m "feat: Intégration interface admin complète"
   git checkout main
   git merge feature/admin-integration
   git push origin main
   ```

### Points d'Attention lors du Merge

⚠️ **Conflits potentiels dans index.php** :
- Section de routing (lignes 156-213)
- Switch case pour actions admin
- Tableaux $pageCss et $pageJs

⚠️ **Dépendances** :
- Vérifier que Database.php existe
- Vérifier que LoginModel.php gère correctement is_admin
- Vérifier que la table users a bien le champ is_admin

⚠️ **Configuration** :
- Vérifier les URLs dans config.php
- Vérifier les permissions dossiers uploads/

---

## 📝 Tests Post-Merge

### Checklist de Tests

- [ ] Connexion admin fonctionne
- [ ] Dashboard s'affiche avec stats
- [ ] Liste utilisateurs paginée
- [ ] Détails utilisateur accessibles
- [ ] Recherche utilisateurs fonctionne
- [ ] Suppression utilisateur OK
- [ ] Toggle vérification OK
- [ ] Liste trajets affichée
- [ ] Liste véhicules affichée
- [ ] Profil admin éditable
- [ ] Déconnexion fonctionne
- [ ] Site principal non affecté
- [ ] Pas de conflits CSS/JS entre admin et site

---

## 🔒 Sécurité

### Mesures Implémentées

1. **Authentification stricte** : Vérification `is_admin = 1` obligatoire
2. **Protection CSRF** : Sessions PHP sécurisées
3. **Validation des entrées** : Sanitization dans RegisterController
4. **Injection SQL** : PDO avec prepared statements
5. **XSS** : htmlspecialchars() dans toutes les vues
6. **Séparation des rôles** : Admin et utilisateurs isolés

### Recommandations Supplémentaires

- [ ] Ajouter token CSRF dans formulaires admin
- [ ] Logger toutes les actions admin
- [ ] Limiter tentatives de connexion admin
- [ ] Implémenter 2FA pour admins
- [ ] Auditer régulièrement les accès admin

---

## 🎯 Résumé

L'interface admin de CarShare est :
- ✅ **Autonome** : Fonctionne indépendamment du site principal
- ✅ **Intégrée** : Partage la DB et le routeur principal
- ✅ **Sécurisée** : Authentification séparée avec flag is_admin
- ✅ **Complète** : Gestion users/trips/vehicles/stats
- ✅ **Moderne** : UI responsive avec sidebar navigation
- ✅ **Prête** : Tous les fichiers sont fonctionnels

**Pour merger** : Copier les fichiers et intégrer les routes dans index.php en suivant la procédure ci-dessus.
