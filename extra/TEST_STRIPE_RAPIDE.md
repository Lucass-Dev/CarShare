# 🧪 Test Rapide du Système de Paiement Stripe

## ⚡ Instructions de Test - 5 Minutes

### Étape 1 : Accédez à la page de paiement
Ouvrez votre navigateur et allez sur :
```
http://localhost/CarShare/index.php?action=payment&carpooling_id=52
```
*(Si le trajet 52 n'existe pas, remplacez par l'ID d'un trajet existant)*

---

### Étape 2 : Remplissez le formulaire

**👤 Nom sur la carte :**
```
Marie Dupont
```

**💳 Informations de carte (champ unique Stripe) :**
Tapez directement dans le champ :
```
4242424242424242   12/28   123
```
*(Ou avec espaces : 4242 4242 4242 4242)*

**✅ Badge de statut :** 
Vous devriez voir le badge passer de "En attente" → "En cours" → "✅ Valide"

**📜 Conditions :**
☑️ Cochez "J'accepte les conditions CarShare"

---

### Étape 3 : Validez

Cliquez sur **"Vérifier ma carte et confirmer"**

**Ce qui se passe :**
1. ⏳ Overlay de traitement s'affiche
2. 🔐 Stripe valide la carte
3. 💾 Réservation créée dans la base de données
4. 📧 2 emails envoyés (passager + conducteur)
5. 💬 Message privé envoyé au conducteur
6. ✅ Redirection vers page de confirmation

---

### Étape 4 : Vérifications

#### ✅ Dans le navigateur
- Vous êtes redirigé vers : `/index.php?action=booking_confirmation&booking_id=XX`
- Page de confirmation affichée

#### ✅ Dans la base de données

**Table `bookings` :**
```sql
SELECT * FROM bookings ORDER BY id DESC LIMIT 1;
```
→ Vous devriez voir votre nouvelle réservation

**Table `carpoolings` :**
```sql
SELECT available_places FROM carpoolings WHERE id = 52;
```
→ Le nombre de places a diminué de 1

**Table `private_message` :**
```sql
SELECT * FROM private_message ORDER BY send_at DESC LIMIT 1;
```
→ Un message a été envoyé au conducteur

#### ✅ Emails

Vérifiez les boîtes email (ou logs si mode développement) :
- **Email 1** : Passager reçoit confirmation
- **Email 2** : Conducteur reçoit notification

---

## 🧪 Test de Carte Refusée

Pour tester la gestion d'erreurs :

**Carte qui sera refusée :**
```
4000000000000002   12/28   123
```

**Résultat attendu :**
- ❌ Message d'erreur "Carte refusée par votre banque"
- 🔄 Formulaire reste accessible pour réessayer
- 📝 Badge passe à "❌ Erreur"

---

## 🔍 Debug

Si quelque chose ne fonctionne pas :

### 1. Console JavaScript (F12)
```javascript
// Vous devriez voir :
✅ Stripe Elements monté avec succès
📝 État du champ carte: {...}
🚀 Soumission du formulaire de paiement...
🔐 Appel à stripe.confirmCardSetup...
```

### 2. Erreurs PHP
Vérifiez `C:\xampp\php\logs\php_error_log` ou activez les erreurs :
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### 3. Vérifiez Stripe Dashboard
Allez sur : https://dashboard.stripe.com/test/payments
→ Vous devriez voir les tentatives de vérification

---

## ✅ Checklist Complète

- [ ] Page de paiement s'affiche correctement
- [ ] Champ Stripe Elements se charge
- [ ] Carte de test acceptée (4242...)
- [ ] Badge de statut passe à "Valide"
- [ ] Formulaire se soumet sans erreur
- [ ] Redirection vers confirmation
- [ ] Réservation dans `bookings` table
- [ ] Places diminuées dans `carpoolings`
- [ ] Message privé créé
- [ ] 2 emails envoyés (si configuré)

---

## 🎯 Résultat Final Attendu

**Après une réservation réussie :**

```
✅ Réservation confirmée !
📧 Email envoyé au passager : "Réservation confirmée - CarShare"
📧 Email envoyé au conducteur : "Nouvelle réservation sur votre trajet - CarShare"
💬 Message privé dans la messagerie du conducteur
📊 Base de données mise à jour
```

---

## 🚀 Prêt à Tester !

Tout est configuré et prêt. Suivez les étapes ci-dessus et tout devrait fonctionner parfaitement !

**Carte de test à copier-coller :**
```
4242424242424242
```

Bon test ! 🎉
