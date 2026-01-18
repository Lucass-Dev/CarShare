# 🚗 CarShare - Plateforme de Covoiturage

**Version 2.0 - Janvier 2026**

Application web moderne de covoiturage permettant aux utilisateurs de publier et réserver des trajets en toute simplicité.

---

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Technologies utilisées](#-technologies-utilisées)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure du projet](#-structure-du-projet)
- [Bugs corrigés](#-bugs-corrigés-version-20)
- [Sécurité](#-sécurité)

---

## ✨ Fonctionnalités

### Gestion des utilisateurs
- ✅ Inscription avec validation d'email
- ✅ Connexion sécurisée
- ✅ Profil utilisateur personnalisable
- ✅ Système de notation (⭐)
- ✅ Signalement d'utilisateurs

### Covoiturage
- 🚗 Publication de trajets
- 🔍 Recherche avancée de trajets (ville, date, places)
- 📅 Réservation en temps réel
- 💬 Messagerie intégrée entre conducteurs et passagers
- 💳 Paiement sécurisé (Stripe/PayPal)

### Recherche
- 🔎 **Recherche utilisateurs en temps réel**
- 🎯 Suggestions dynamiques (AJAX)
- 📊 Page de résultats filtrables
- ⚡ Cache intelligent pour performance optimale

---

## 🛠 Technologies utilisées

- **Backend** : PHP 8.x
- **Frontend** : HTML5, CSS3, JavaScript (ES6+)
- **Base de données** : MySQL 8.0
- **Architecture** : MVC (Model-View-Controller)
- **APIs** : REST JSON
- **Paiement** : Stripe, PayPal
- **Email** : PHPMailer

---

## 📦 Installation

### Prérequis

- PHP >= 8.0
- MySQL >= 8.0
- Serveur web (Apache/Nginx) ou XAMPP
- Composer (optionnel)

### Étapes d'installation

#### 1. Cloner ou télécharger le projet

```bash
git clone https://github.com/votre-repo/carshare.git
cd carshare
```

#### 2. Créer la base de données

```bash
# Se connecter à MySQL
mysql -u root -p

# Créer la base de données
CREATE DATABASE carshare CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Importer le schéma
mysql -u root -p carshare < sql/carshare.sql
```

#### 3. Configuration (voir section suivante)

#### 4. Lancer l'application

**Avec XAMPP :**
- Placer le projet dans `C:\xampp\htdocs\carshare`
- Démarrer Apache et MySQL
- Accéder à `http://localhost/carshare`

**Avec PHP Built-in Server :**
```bash
php -S localhost:8000
```
Puis accéder à `http://localhost:8000`

---

## ⚙️ Configuration

### Configuration automatique

L'application détecte **automatiquement** son environnement grâce au système de configuration dynamique.

✅ **Fonctionne en :**
- Localhost racine (`http://localhost/`)
- Sous-dossier (`http://localhost/carshare/`)
- Sous-dossier personnalisé (`http://localhost/mon-projet/`)
- Production (`https://monsite.com/`)

### Fichier `config.php`

Le fichier `config.php` à la racine gère automatiquement :

```php
// Détection automatique de l'URL de base
define('BASE_URL', 'http://localhost/carshare');   // Exemple
define('BASE_PATH', '/carshare');                   // Exemple
define('ENVIRONMENT', 'development');               // development | production

// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'carshare');
define('DB_USER', 'root');
define('DB_PASS', '');
```

**⚠️ Modification nécessaire :**
- Seuls les paramètres de base de données (`DB_*`) doivent être modifiés selon votre configuration
- Le reste est détecté automatiquement

### Configuration Stripe (Paiements)

Créer un fichier `.env` ou modifier `model/StripeConfig.php` :

```php
define('STRIPE_SECRET_KEY', 'sk_test_votre_cle_secrete');
define('STRIPE_PUBLIC_KEY', 'pk_test_votre_cle_publique');
```

---

## 📁 Structure du projet

```
carshare/
├── assets/
│   ├── api/               # APIs REST (JSON)
│   │   ├── check-email.php
│   │   ├── cities.php
│   │   ├── rating.php
│   │   ├── report.php
│   │   └── search.php
│   ├── img/               # Images et icônes
│   ├── js/                # Scripts JavaScript
│   │   ├── url-helper.js  # Helper URLs dynamiques
│   │   ├── global-search.js
│   │   ├── register-validation.js
│   │   └── ...
│   └── styles/            # Fichiers CSS
│
├── controller/            # Contrôleurs MVC
│   ├── RegisterController.php
│   ├── LoginController.php
│   ├── SearchPageController.php
│   └── ...
│
├── model/                 # Modèles (accès BDD)
│   ├── Database.php
│   ├── RegisterModel.php
│   └── ...
│
├── view/                  # Vues (templates PHP)
│   ├── components/
│   │   ├── header.php
│   │   └── footer.php
│   ├── RegisterView.php
│   ├── LoginView.php
│   └── ...
│
├── sql/
│   └── carshare.sql       # Schéma de la base de données
│
├── config.php             # Configuration globale (URLs dynamiques)
├── index.php              # Point d'entrée principal
└── README.md              # Ce fichier
```

---

## 🐛 Bugs corrigés (Version 2.0)

### 1. **Formulaire d'inscription bloqué** ✅ CORRIGÉ
**Problème :** Après une erreur de validation serveur, le bouton "S'inscrire" restait bloqué en mode "Chargement..." et les champs étaient désactivés. L'utilisateur ne pouvait plus corriger ses erreurs sans rafraîchir la page.

**Solution :**
- Ajout de `forceFormReactivation()` au chargement de la page
- Gestion des événements `pageshow` et `visibilitychange`
- Détection automatique des erreurs serveur pour réactivation immédiate
- Support complet de la touche "Entrée" pour validation

**Fichiers modifiés :**
- `assets/js/register-validation.js`

### 2. **URLs hardcodées** ✅ CORRIGÉ
**Problème :** Tous les chemins étaient en dur (`/CarShare/...`), empêchant l'application de fonctionner dans différents environnements.

**Solution :**
- Création de `config.php` avec détection automatique de l'environnement
- Ajout de `url-helper.js` pour JavaScript
- Fonctions helper : `url()`, `asset()`, `apiUrl()`, `full_url()`
- Remplacement de tous les chemins hardcodés

**Fichiers modifiés :**
- `config.php` (nouveau)
- `assets/js/url-helper.js` (nouveau)
- `index.php`
- Tous les fichiers View (`view/*.php`)
- Tous les fichiers JavaScript

### 3. **Recherche utilisateurs optimisée** ✅ AMÉLIORÉ
**Fonctionnalités ajoutées :**
- Suggestions en temps réel dès la 1ère lettre tapée
- Cache intelligent pour éviter les requêtes redondantes
- Debounce réduit à 150ms pour réactivité maximale
- Redirectionversune page de résultats complète avec filtres (touche Entrée)
- Design moderne avec icônes et visuels

**Fichiers modifiés :**
- `assets/js/global-search.js` (refonte complète)
- `assets/api/search.php`

---

## 🔒 Sécurité

### Mesures de sécurité implémentées

✅ **Validation des entrées**
- Filtrage et échappement de toutes les entrées utilisateur
- Protection contre XSS (Cross-Site Scripting)
- Protection contre injection SQL (requêtes préparées PDO)

✅ **Authentification**
- Hashage des mots de passe (bcrypt)
- Validation d'email obligatoire
- Tokens de session sécurisés

✅ **Transactions**
- Intégration Stripe en mode sécurisé
- Vérification des paiements côté serveur
- Protection contre les fraudes

✅ **HTTPS recommandé en production**

---

## 📝 Notes importantes

### Base de données
⚠️ **NE JAMAIS modifier la structure de la base de données** sans mettre à jour `sql/carshare.sql`

### Production
Avant de déployer en production :
1. Modifier `ENVIRONMENT` dans `config.php` vers `'production'`
2. Activer HTTPS
3. Modifier les clés Stripe pour utiliser les clés de production
4. Désactiver `display_errors` PHP

---

## 👨‍💻 Support

Pour toute question ou problème :
- 📧 Email : support@carshare.com
- 🐛 Issues : [GitHub Issues](https://github.com/votre-repo/carshare/issues)

---

## 📄 Licence

Projet propriétaire - © 2026 CarShare

---

**Made with ❤️ by the CarShare Team**
