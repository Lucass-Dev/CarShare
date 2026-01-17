# 🚗 CarShare - Plateforme de Covoiturage

## ✅ Système Restauré (17 Janvier 2026)

Le système de réservation a été **complètement restauré** à son état simple et fonctionnel.

### 🎯 État Actuel

- ✅ **Réservation directe** - Un clic pour réserver (sans paiement)
- ✅ **Notifications automatiques** - Emails et messages privés
- ✅ **Interface moderne** - Design épuré et professionnel
- ✅ **Sécurité maintenue** - Protection CSRF active

---

## 🚀 Démarrage Rapide

### 1. Accéder à l'application
```
http://localhost/CarShare/
```

### 2. Tester une réservation
```
http://localhost/CarShare/index.php?action=payment&carpooling_id=52
```

### 3. Processus de réservation

1. Connectez-vous avec votre compte
2. Recherchez un trajet ou accédez directement via l'URL ci-dessus
3. Cliquez sur "Réserver"
4. Acceptez les CGV/CGU
5. Validez → Réservation créée instantanément!

---

## 📚 Documentation

### Documents Principaux

- **[LISEZ-MOI.md](LISEZ-MOI.md)** ⭐ → À lire en premier!
- **[RESTAURATION_COMPLETE_2026-01-17.md](RESTAURATION_COMPLETE_2026-01-17.md)** → Détails techniques complets
- **[extra/README_EXTRA.md](extra/README_EXTRA.md)** → Index des fichiers archivés

### Guides Spécifiques

- Publication de trajet
- Système de messagerie
- Gestion des réservations
- Notation et signalements

---

## 🗂️ Structure du Projet

```
carshare/
├── index.php                    # Point d'entrée
├── controller/                  # Contrôleurs
│   ├── PaymentController.php   # ✅ Restauré
│   ├── BookingController.php
│   └── ...
├── view/                        # Vues
│   ├── PaymentView.php         # ✅ Recréé
│   └── ...
├── model/                       # Modèles
├── assets/                      # CSS, JS, Images
├── extra/                       # 📦 Archives
│   ├── PaymentStripeView.php   # Ancien système Stripe
│   ├── StripeConfig.php
│   ├── PayPalConfig.php
│   └── ... (documentation)
└── sql/                         # Scripts SQL
```

---

## 🔄 Ce Qui a Changé (17/01/2026)

### ❌ Supprimé
- Intégration Stripe (vérification carte)
- Intégration PayPal
- Routes de paiement (`create_paypal_order`, `capture_paypal_order`)
- Dépendance composer `stripe/stripe-php`

### ✅ Ajouté/Restauré
- Réservation directe sans paiement
- Interface de réservation simplifiée
- Documentation complète de la restauration
- Archivage des fichiers Stripe/PayPal dans `extra/`

---

## 🛡️ Sécurité

Le système maintient toutes les protections de sécurité:

- ✅ Protection CSRF (tokens anti-rejeu)
- ✅ Validation de session utilisateur
- ✅ Sanitization des données
- ✅ Prévention XSS et SQL Injection
- ✅ Contrôle des permissions

---

## 📧 Notifications

### Emails Automatiques

Lors d'une réservation, les emails suivants sont envoyés:

1. **Au passager:** Confirmation de réservation avec détails du trajet
2. **Au conducteur:** Notification de nouvelle réservation

### Messages Privés

Un message automatique est également envoyé au conducteur via la messagerie interne.

---

## 🎨 Fonctionnalités

### Pour les Passagers
- 🔍 Recherche de trajets
- 📅 Réservation en un clic
- 💬 Messagerie avec conducteurs
- 📊 Historique des réservations
- ⭐ Notation des trajets

### Pour les Conducteurs
- ✍️ Publication de trajets
- 📋 Gestion des réservations
- 💬 Messagerie avec passagers
- 📈 Tableau de bord
- 🚗 Gestion des trajets publiés

### Pour Tous
- 👤 Profil utilisateur complet
- 📧 Notifications email
- 🔒 Système de sécurité
- ⚠️ Signalements

---

## 💾 Base de Données

### Tables Principales

- `users` - Utilisateurs
- `carpoolings` - Trajets publiés
- `bookings` - Réservations
- `messages` - Messages privés
- `conversations` - Conversations
- `ratings` - Évaluations
- `signalements` - Signalements

### Migrations

Scripts SQL disponibles dans le dossier `sql/`.

---

## 🔧 Configuration

### Prérequis

- PHP >= 7.4
- MySQL/MariaDB
- Apache (XAMPP recommandé)

### Variables d'Environnement

Configurez votre base de données dans `model/Config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'carshare');
define('DB_USER', 'root');
define('DB_PASS', '');
```

---

## 🧪 Mode Test

L'application fonctionne en **mode test académique**:

- Aucun paiement réel n'est effectué
- Les emails peuvent être désactivés (configuration)
- Idéal pour démonstration et développement

---

## 📦 Fichiers Archivés

Les anciens fichiers liés aux paiements Stripe/PayPal sont dans `extra/`:

- Configuration Stripe complète
- Vues avec intégration Stripe Elements
- Documentation d'installation
- Scripts de test

**Conservés pour référence future ou réintégration.**

---

## 🚨 Dépannage

### La page de réservation ne s'affiche pas?

1. Vérifiez que vous êtes connecté
2. Vérifiez que le trajet existe (carpooling_id valide)
3. Consultez les logs Apache dans XAMPP
4. Vérifiez la console navigateur (F12)

### Les emails ne sont pas envoyés?

1. Vérifiez la configuration SMTP dans `model/EmailService.php`
2. Vérifiez les logs PHP
3. En mode test, les emails peuvent être désactivés

### Erreur 500?

1. Consultez les logs d'erreur Apache
2. Vérifiez la connexion à la base de données
3. Vérifiez les permissions des fichiers

---

## 📞 Support

### Documentation

- Consultez [LISEZ-MOI.md](LISEZ-MOI.md) pour les instructions détaillées
- Consultez [RESTAURATION_COMPLETE_2026-01-17.md](RESTAURATION_COMPLETE_2026-01-17.md) pour les détails techniques

### Logs

- Logs Apache: `xampp/apache/logs/error.log`
- Logs PHP: Vérifiez `php.ini` pour `error_log`

---

## 🎯 Prochaines Étapes

1. ✅ **Tester la réservation** - Vérifiez que tout fonctionne
2. ✅ **Personnaliser les emails** - Adaptez les templates à vos besoins
3. ✅ **Ajouter des trajets** - Publiez des trajets de test
4. ✅ **Inviter des utilisateurs** - Testez avec plusieurs comptes

---

## 📝 Version

**Version actuelle:** Système Simple Sans Paiement  
**Date de restauration:** 17 janvier 2026  
**Statut:** ✅ Production Ready  

---

## 🙏 Crédits

**Projet:** CarShare  
**Type:** Plateforme de covoiturage académique  
**Technologie:** PHP, MySQL, JavaScript  

---

**Bon covoiturage! 🚗💨**
