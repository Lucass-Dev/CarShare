# Guide Visuel - Flux Admin CarShare

## 📊 Diagramme de Flux Complet

```
┌─────────────────────────────────────────────────────────────────┐
│                      UTILISATEUR VISITE                          │
│                   index.php?action=admin_login                   │
└────────────────────────────────┬────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────┐
│                        INDEX.PHP (Router)                        │
│                                                                  │
│  1. Parse $_GET['action']                                       │
│  2. Vérifie si action commence par "admin_"                     │
│  3. Route vers le bon contrôleur                                │
└────────────────────────────────┬────────────────────────────────┘
                                 │
                ┌────────────────┴────────────────┐
                │                                 │
                ▼                                 ▼
    ┌───────────────────────┐        ┌──────────────────────────┐
    │   Routes Séparées     │        │  Routes Unifiées         │
    │   (Login/Register)    │        │  (Dashboard/Users/etc)   │
    └───────────┬───────────┘        └────────────┬─────────────┘
                │                                 │
                ▼                                 ▼
    ┌───────────────────────┐        ┌──────────────────────────┐
    │ AdminLoginController  │        │ AdminControllerUnified   │
    │ AdminRegisterController│       │  - checkAdminAuth()      │
    └───────────┬───────────┘        └────────────┬─────────────┘
                │                                 │
                └──────────┬──────────────────────┘
                           │
                           ▼
            ┌──────────────────────────┐
            │   AdminModelEnhanced     │
            │   - getDashboardStats()  │
            │   - getUsers()           │
            │   - getTrips()           │
            │   - etc.                 │
            └──────────┬───────────────┘
                       │
                       ▼
            ┌──────────────────────────┐
            │      DATABASE (PDO)      │
            │   - users (is_admin=1)   │
            │   - carpoolings          │
            │   - bookings             │
            └──────────┬───────────────┘
                       │
                       ▼
            ┌──────────────────────────┐
            │    VIEW (admin_layout)   │
            │   + content dynamique    │
            └──────────────────────────┘
```

---

## 🔐 Flux d'Authentification Détaillé

```
START: Utilisateur non connecté
│
├─► Visite: index.php?action=admin_login
│   │
│   ├─► index.php détecte action="admin_login"
│   │   └─► Route vers AdminLoginController->render()
│   │
│   ├─► Affiche view/admin_login.php
│   │   └─► Formulaire avec email + password
│   │
│   └─► User soumet formulaire
│       │
│       └─► POST vers ?action=admin_process_login
│
├─► AdminLoginController->processLogin()
│   │
│   ├─► 1. Récupère $_POST['email'] et $_POST['password']
│   │
│   ├─► 2. Appelle LoginModel->authenticate()
│   │   └─► Requête SQL: SELECT * FROM users WHERE email=? AND is_admin=1
│   │
│   ├─► 3. Vérifie password_verify()
│   │
│   ├─► 4. Si OK, crée session:
│   │   ├─► $_SESSION['user_id'] = ...
│   │   ├─► $_SESSION['email'] = ...
│   │   ├─► $_SESSION['is_admin'] = 1  ◄── Important !
│   │   └─► $_SESSION['login_time'] = time()
│   │
│   └─► 5. Redirect vers ?action=admin_dashboard
│
├─► AdminControllerUnified->dashboard()
│   │
│   ├─► checkAdminAuth() ◄── Vérifie $_SESSION['is_admin'] == 1
│   │   └─► Si pas admin → redirect vers login
│   │
│   ├─► Récupère stats via AdminModelEnhanced
│   │
│   └─► Affiche admin_layout.php + dashboard_content.php
│
└─► ADMIN CONNECTÉ - Accès complet
```

---

## 🗂️ Structure des Fichiers - Vue Détaillée

```
CarShare/
│
├── index.php  ◄────────────────────────── POINT D'ENTRÉE UNIQUE
│   │
│   ├── Ligne 73-81: Chargement CSS admin
│   ├── Ligne 127-129: Chargement JS admin
│   └── Ligne 156-213: ROUTING ADMIN UNIFIÉ ★
│
├── controller/
│   ├── AdminControllerUnified.php  ◄──── CONTRÔLEUR PRINCIPAL
│   │   ├── dashboard()
│   │   ├── users()
│   │   ├── userDetails()
│   │   ├── deleteUser()
│   │   ├── trips()
│   │   ├── vehicles()
│   │   ├── profile()
│   │   └── logout()
│   │
│   ├── AdminLoginController.php  ◄──────  CONNEXION
│   │   ├── render()
│   │   ├── processLogin()
│   │   └── logout()
│   │
│   ├── AdminRegisterController.php  ◄──── INSCRIPTION
│   │   └── render()
│   │
│   └── AdminEmailValidationController.php
│
├── model/
│   ├── AdminModelEnhanced.php  ◄────────  LOGIQUE MÉTIER
│   │   ├── authenticateAdmin()
│   │   ├── getDashboardStats()
│   │   ├── getUsers()
│   │   ├── getUserDetails()
│   │   ├── deleteUser()
│   │   ├── getTrips()
│   │   ├── getVehicles()
│   │   └── ... (708 lignes)
│   │
│   └── AdminModel.php  (legacy)
│
├── view/
│   ├── admin_layout.php  ◄───────────────  TEMPLATE PRINCIPAL
│   │   ├── Sidebar (navigation)
│   │   ├── Topbar (titre + user)
│   │   └── Main content ($content)
│   │
│   ├── admin_login.php  ◄─────────────────  PAGE CONNEXION
│   │
│   └── admin/  ◄─────────────────────────── VUES MODERNES
│       ├── dashboard_content.php
│       ├── users_content.php
│       ├── user_details_content.php
│       ├── trips_content.php
│       ├── vehicles_content.php
│       └── profile_content.php
│
└── assets/
    ├── styles/
    │   └── admin-modern.css  ◄────────────  STYLES ADMIN
    │
    └── js/
        ├── admin-autosuggest.js
        └── admin-alerts.js
```

---

## 🔄 Routing dans index.php - Explication Ligne par Ligne

### Section 1: Routes Admin Unifiées (lignes 156-213)

```php
// Ligne 157: Liste des routes qui NE passent PAS par le contrôleur unifié
$excludedAdminRoutes = [
    'admin_login',                  // → AdminLoginController
    'admin_register',               // → AdminRegisterController
    'admin_registration_pending',   // → EmailValidationController
    'admin_email_validation'        // → EmailValidationController
];

// Ligne 158: Détection des routes admin
if (strpos($action, 'admin_') === 0 && !in_array($action, $excludedAdminRoutes)) {
    // ↑ Si action commence par "admin_" ET n'est pas dans excludedAdminRoutes
    
    // Ligne 159: Charger le contrôleur unifié
    require_once __DIR__ . "/controller/AdminControllerUnified.php";
    $controller = new AdminControllerUnified();
    
    // Ligne 162-210: Switch pour router vers la bonne méthode
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
            
        // ... autres actions
    }
    
    // Ligne 213: EXIT IMPORTANT !
    exit;  // ← Empêche l'affichage du header/footer du site
}
```

**Pourquoi `exit;` ?**
- Sans `exit`, le code continuerait et afficherait le header/footer du site
- L'admin a son propre layout (admin_layout.php) donc pas besoin du site

### Section 2: Routes Séparées (dans le switch principal)

```php
// Ligne 271: Login admin
case "admin_login":
    require_once __DIR__ . "/controller/AdminLoginController.php";
    (new AdminLoginController())->render();
    break;

// Ligne 275: Register admin
case "admin_register":
    require_once __DIR__ . "/controller/AdminRegisterController.php";
    (new AdminRegisterController())->render();
    break;
```

**Pourquoi séparés ?**
- Ces routes doivent afficher le header/footer du site
- Elles n'ont pas besoin de vérification admin

---

## 🎨 Layout Admin - Composition

```
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="admin-modern.css">  ◄── Styles admin
</head>
<body class="admin-layout">

┌─────────────────────────────────────────────────────────────┐
│                         TOPBAR                              │
│  ┌─────────────────────┐         ┌────────────────────┐    │
│  │ Tableau de bord     │         │ Admin Name  [A]    │    │
│  └─────────────────────┘         └────────────────────┘    │
└─────────────────────────────────────────────────────────────┘

┌─────────────┬───────────────────────────────────────────────┐
│             │                                               │
│  SIDEBAR    │           MAIN CONTENT                        │
│             │                                               │
│  📊 Dashboard  │  ┌─────────────────────────────────┐       │
│  👥 Users   │  │                                 │       │
│  🚗 Trips   │  │  <?php echo $content; ?>        │       │
│  🚙 Vehicles│  │  ↑                              │       │
│  👤 Profile │  │  Contenu dynamique injecté      │       │
│             │  │                                 │       │
│  🌐 Voir site  │  └─────────────────────────────────┘       │
│  🚪 Logout  │                                               │
│             │                                               │
└─────────────┴───────────────────────────────────────────────┘

</body>
</html>
```

**Injection du contenu :**
```php
// Dans AdminControllerUnified->dashboard()

ob_start();  // Commence la capture
require_once __DIR__ . '/../view/admin/dashboard_content.php';
$content = ob_get_clean();  // Récupère le HTML capturé

// $content contient maintenant tout le HTML de dashboard_content.php
require_once __DIR__ . '/../view/admin_layout.php';
// admin_layout.php fait: echo $content;
```

---

## 🔍 Exemple Concret: Gestion d'un Utilisateur

### Scénario: Admin veut voir les détails d'un utilisateur

```
1. Admin clique sur "Utilisateurs" dans sidebar
   └─► URL: index.php?action=admin_users
   
2. index.php route vers AdminControllerUnified->users()
   │
   ├─► checkAdminAuth() ✓
   │
   ├─► Récupère $_GET['page'], $_GET['search'], $_GET['filter']
   │
   ├─► Appelle AdminModelEnhanced->getUsers($page, $limit, $search, $filter)
   │   └─► SQL: SELECT id, first_name, last_name, email, ... FROM users 
   │            WHERE is_admin = 0 
   │            ORDER BY created_at DESC 
   │            LIMIT 20 OFFSET 0
   │
   ├─► Affiche view/admin/users_content.php
   │   └─► Boucle sur $users et affiche tableau HTML
   │
   └─► Injecte dans admin_layout.php

3. Admin voit tableau avec bouton "Voir détails" pour chaque user
   └─► Clique sur "Voir détails" pour user #42
   
4. URL: index.php?action=admin_user_details&id=42
   │
   ├─► index.php route vers AdminControllerUnified->userDetails()
   │
   ├─► checkAdminAuth() ✓
   │
   ├─► Récupère $_GET['id'] = 42
   │
   ├─► Appelle AdminModelEnhanced->getUserDetails(42)
   │   └─► SQL: SELECT * FROM users WHERE id = 42
   │
   ├─► Appelle AdminModelEnhanced->getUserStats(42)
   │   └─► SQL: SELECT COUNT(*) FROM carpoolings WHERE provider_id = 42
   │   └─► SQL: SELECT COUNT(*) FROM bookings WHERE booker_id = 42
   │
   ├─► Affiche view/admin/user_details_content.php
   │   ├─► Infos utilisateur
   │   ├─► Statistiques
   │   └─► Boutons d'action (supprimer, réinitialiser MDP, etc.)
   │
   └─► Injecte dans admin_layout.php

5. Admin clique sur "Supprimer utilisateur"
   └─► URL: index.php?action=admin_delete_user&id=42
       │
       ├─► AdminControllerUnified->deleteUser()
       │
       ├─► checkAdminAuth() ✓
       │
       ├─► Appelle AdminModelEnhanced->deleteUser(42)
       │   └─► SQL: DELETE FROM users WHERE id = 42
       │
       ├─► $_SESSION['admin_success'] = "Utilisateur supprimé"
       │
       └─► Redirect vers index.php?action=admin_users
```

---

## 📋 Checklist de Vérification Pré-Merge

### ✅ Fichiers à Vérifier

```
controller/
  ✓ AdminControllerUnified.php existe
  ✓ AdminLoginController.php existe
  ✓ AdminRegisterController.php existe
  ✓ AdminEmailValidationController.php existe

model/
  ✓ AdminModelEnhanced.php existe
  ✓ Database.php existe et fonctionne

view/
  ✓ admin_layout.php existe
  ✓ admin_login.php existe
  ✓ admin/ dossier existe avec tous les *_content.php

assets/
  ✓ styles/admin-modern.css existe
  ✓ js/admin-autosuggest.js existe
  ✓ js/admin-alerts.js existe
```

### ✅ Modifications index.php

```
✓ Lignes 73-81: CSS admin ajoutés dans $pageCss
✓ Lignes 127-129: JS admin ajoutés dans $pageJs
✓ Lignes 156-213: Section routing admin unifié ajoutée
✓ Ligne 271+: Routes admin_login et admin_register ajoutées
```

### ✅ Base de Données

```
✓ Table users a le champ is_admin TINYINT(1)
✓ Au moins un compte avec is_admin = 1 existe
✓ Toutes les tables nécessaires existent (carpoolings, bookings, location)
```

### ✅ Configuration

```
✓ config.php définit BASE_URL et BASE_PATH
✓ Fonction url() existe et fonctionne
✓ Fonction asset() existe et fonctionne
✓ Sessions PHP activées
```

---

## 🚀 Commandes Git pour le Merge

```bash
# 1. Sauvegarder l'état actuel
git checkout main
git pull origin main
git checkout -b backup-avant-admin

# 2. Créer branche de travail
git checkout main
git checkout -b feature/admin-interface

# 3. Copier tous les fichiers admin de votre version
# (manuel ou via git merge si dans une autre branche)

# 4. Vérifier les changements
git status
git diff index.php

# 5. Tester en local
# Vérifier que tout fonctionne

# 6. Commit
git add controller/Admin*.php
git add model/AdminModel*.php
git add view/admin*
git add view/admin/
git add assets/styles/admin-modern.css
git add assets/js/admin-*.js
git add extra/ARCHITECTURE_ADMIN.md
git commit -m "feat: Ajout interface admin complète

- AdminControllerUnified pour toutes les actions admin
- AdminLoginController pour authentification séparée
- AdminModelEnhanced avec toutes les fonctions CRUD
- Layout admin moderne avec sidebar
- Vues pour dashboard, users, trips, vehicles
- Routing unifié dans index.php"

# 7. Merger dans main
git checkout main
git merge feature/admin-interface

# 8. Tester à nouveau sur main

# 9. Push
git push origin main
```

---

## 🔥 Résolution de Conflits Potentiels

### Conflit dans index.php

Si vous avez un conflit lors du merge de index.php:

```php
<<<<<<< HEAD
// Votre code actuel
=======
// Code admin à intégrer
>>>>>>> feature/admin-interface
```

**Solution :**
1. Garder votre code HEAD
2. Ajouter APRÈS votre code le nouveau code admin
3. Vérifier que les numéros de ligne correspondent à la doc

### Conflit dans config.php

Généralement pas de conflit, mais vérifier:
- `BASE_URL` et `BASE_PATH` sont définis
- Fonction `url()` existe
- Fonction `asset()` existe

### Conflit dans Database.php

Pas de modification nécessaire normalement.
AdminModelEnhanced utilise le Database.php existant.

---

## 💡 Bonnes Pratiques Post-Merge

1. **Tester tous les endpoints admin**
2. **Vérifier que le site normal fonctionne toujours**
3. **Créer un compte admin de test**
4. **Documenter les accès admin pour l'équipe**
5. **Configurer les logs pour les actions admin**
6. **Planifier formation équipe sur interface admin**

---

## 📞 Contact

Si problèmes lors du merge:
1. Vérifier cette documentation
2. Vérifier ARCHITECTURE_ADMIN.md
3. Comparer avec version fonctionnelle
4. Tester endpoint par endpoint

Bonne chance pour le merge ! 🚀
