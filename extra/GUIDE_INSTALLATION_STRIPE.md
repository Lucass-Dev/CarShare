# 🚀 Guide d'Installation Stripe pour CarShare

## Mode Test - Projet Académique (Sans débit réel)

Ce guide vous explique comment configurer Stripe en **mode TEST** pour vérifier les cartes bancaires sans effectuer de vrais débits.

---

## 📋 Étape 1 : Installation de Composer & Stripe

### Windows avec XAMPP

1. **Vérifier si Composer est installé**
   ```powershell
   cd c:\xampp\htdocs\carshare
   composer --version
   ```

2. **Si Composer n'est pas installé**, téléchargez-le :
   - 🔗 [Télécharger Composer](https://getcomposer.org/Composer-Setup.exe)
   - Installer avec les options par défaut
   - Redémarrer le terminal

3. **Installer la bibliothèque Stripe**
   ```powershell
   cd c:\xampp\htdocs\carshare
   composer install
   ```

---

## 🔑 Étape 2 : Obtenir vos clés API Stripe (GRATUITES)

### 2.1 Créer un compte Stripe

1. Allez sur : **https://dashboard.stripe.com/register**
2. Inscrivez-vous avec votre email universitaire ou personnel
3. **Pas besoin de compte bancaire** pour le mode TEST !

### 2.2 Activer le mode TEST

1. Une fois connecté au dashboard Stripe
2. En haut à gauche, vérifiez que le bouton **"Mode test"** est activé (il doit être VIOLET)
   - ✅ Mode test = ACTIF → Parfait !
   - ❌ Mode réel = À éviter pour projet académique

### 2.3 Récupérer vos clés TEST

1. Dans le menu latéral, cliquez sur **"Développeurs"**
2. Cliquez sur **"Clés API"**
3. Vous verrez deux clés :
   - 🔓 **Clé publiable** (commence par `pk_test_...`) 
   - 🔒 **Clé secrète** (commence par `sk_test_...`)

4. **Copiez ces deux clés** (gardez-les dans un fichier temporaire)

---

## ⚙️ Étape 3 : Configuration de CarShare

1. **Ouvrir le fichier de configuration**
   - Fichier : `c:\xampp\htdocs\carshare\model\StripeConfig.php`

2. **Remplacer les clés par défaut**

   Trouvez ces lignes :
   ```php
   const STRIPE_PUBLIC_KEY = 'pk_test_VOTRE_CLE_PUBLIQUE_ICI';
   const STRIPE_SECRET_KEY = 'sk_test_VOTRE_CLE_SECRETE_ICI';
   ```

   Remplacez par vos vraies clés TEST :
   ```php
   const STRIPE_PUBLIC_KEY = 'pk_test_51Abc123...VotreCléPublique';
   const STRIPE_SECRET_KEY = 'sk_test_51Abc123...VotreCléSecrète';
   ```

3. **Enregistrer le fichier**

---

## 🗄️ Étape 4 : Créer la table de base de données

1. **Ouvrir phpMyAdmin**
   - URL : http://localhost/phpmyadmin
   - Sélectionner votre base de données `covoiturage`

2. **Exécuter le script SQL**
   - Aller dans l'onglet **"SQL"**
   - Copier tout le contenu du fichier `sql/card_verifications.sql`
   - Cliquer sur **"Exécuter"**

   ✅ La table `card_verifications` est créée !

---

## 🧪 Étape 5 : Tester le système

### 5.1 Démarrer XAMPP
- Lancer **Apache** et **MySQL**

### 5.2 Accéder à la page de paiement
1. Connectez-vous à CarShare
2. Recherchez un trajet
3. Cliquez sur **"Réserver"**
4. Vous arrivez sur la nouvelle page de vérification Stripe

### 5.3 Utiliser les cartes de test Stripe

**🎯 Cartes bancaires de test gratuites :**

| Numéro de carte         | Résultat                    |
|------------------------|----------------------------|
| `4242 4242 4242 4242`  | ✅ Carte valide (acceptée) |
| `4000 0000 0000 0002`  | ❌ Carte refusée          |
| `4000 0000 0000 9995`  | ⏱️ Insuffisant de fonds   |

**Autres informations :**
- **Date d'expiration** : N'importe quelle date FUTURE (ex: 12/25)
- **CVV** : N'importe quel 3 chiffres (ex: 123)
- **Nom** : N'importe quel nom

### 5.4 Vérifier le résultat

1. Entrez la carte `4242 4242 4242 4242`
2. Cliquez sur **"Vérifier ma carte et confirmer"**
3. ✅ **Résultat attendu** : Réservation confirmée SANS débit !

---

## 📊 Vérifier les vérifications dans la base de données

```sql
SELECT 
    cv.*,
    u.email,
    u.first_name,
    u.last_name
FROM card_verifications cv
JOIN users u ON cv.user_id = u.id
ORDER BY cv.created_at DESC
LIMIT 10;
```

Vous verrez :
- `verification_status` = `succeeded` (si carte valide)
- `card_last4` = `4242` (derniers chiffres de la carte test)
- `card_brand` = `visa`
- `amount_verified` = `0.00` (aucun débit effectué)

---

## 🔍 Fonctionnement du système

### Ce qui se passe en arrière-plan :

1. **L'utilisateur entre sa carte** → Stripe Elements (sécurisé)
2. **Stripe vérifie la carte** → SetupIntent (sans débit)
3. **Carte validée** → Confirmation envoyée au serveur
4. **Réservation créée** → Aucun argent prélevé
5. **Historique sauvegardé** → Table `card_verifications`

### Sécurité garantie :

- ✅ **Aucune donnée bancaire stockée** sur votre serveur
- ✅ **Conforme PCI-DSS** (Stripe s'occupe de tout)
- ✅ **Mode TEST** : Impossible de débiter une vraie carte
- ✅ **Seuls les 4 derniers chiffres** sont conservés en base

---

## ❓ Dépannage

### Erreur : "Composer n'est pas reconnu"
**Solution :** Installer Composer depuis https://getcomposer.org/

### Erreur : "Class '\Stripe\Stripe' not found"
**Solution :** 
```powershell
cd c:\xampp\htdocs\carshare
composer install
```

### Erreur : "Clé API invalide"
**Causes possibles :**
1. Vous êtes en mode PRODUCTION au lieu de TEST
   - ⚠️ Les clés doivent commencer par `pk_test_` et `sk_test_`
2. Vous n'avez pas copié la clé complète
   - Vérifiez qu'il n'y a pas d'espace avant/après

### La page affiche "Configuration Stripe requise"
**Solution :** Ouvrir `model/StripeConfig.php` et remplacer les clés par défaut par vos vraies clés TEST

### Erreur SQL : "Table 'card_verifications' doesn't exist"
**Solution :** Exécuter le fichier `sql/card_verifications.sql` dans phpMyAdmin

---

## 🎓 Pour votre rapport académique

### Points à mentionner :

✅ **Pas de paiement réel** : Mode TEST uniquement  
✅ **Sécurité maximale** : Conformité PCI-DSS via Stripe  
✅ **Aucune donnée sensible stockée** : Uniquement les 4 derniers chiffres  
✅ **Vérification automatique** : SetupIntent Stripe sans débit  
✅ **Traçabilité** : Historique complet dans `card_verifications`  

### Capture d'écran à inclure :
1. Page de vérification Stripe avec bannière "Mode Test"
2. Dashboard Stripe montrant le mode TEST activé
3. Confirmation de réservation après vérification
4. Table `card_verifications` avec des enregistrements

---

## 📚 Ressources supplémentaires

- **Documentation Stripe (FR)** : https://stripe.com/docs/testing
- **Cartes de test Stripe** : https://stripe.com/docs/testing#cards
- **Mode Test vs Production** : https://stripe.com/docs/keys#test-live-modes

---

## 🎉 C'est terminé !

Votre système de vérification de carte est maintenant fonctionnel en mode TEST.

**Avantages pour votre projet académique :**
- Démo réaliste sans risque financier
- Conformité aux standards de l'industrie
- Facile à présenter en soutenance
- Code prêt pour production (si besoin ultérieur)

**Bon courage pour votre projet ! 🚀**
