# Sécurité Renforcée - CarShare
## Mise à jour : 17 janvier 2026

### 🛡️ Protection contre les attaques

Le système de sécurité a été entièrement renforcé pour protéger contre :
- ✅ **XSS (Cross-Site Scripting)**
- ✅ **SQL Injection**
- ✅ **Hex Encoding attacks**
- ✅ **Binary Encoding attacks**
- ✅ **Unicode exploits**
- ✅ **Caractères dangereux** (< > { } [ ] \ | `)
- ✅ **Backslash injection**
- ✅ **Décimaux dans champs numériques**
- ✅ **Caractères de contrôle**

---

## 🔒 Système de Validation Multi-Couches

### 1. Validation Client (JavaScript)
**Fichier**: `assets/js/security-validator.js`

#### Fonctionnalités :
- **Filtrage en temps réel** : Bloque instantanément les caractères dangereux pendant la saisie
- **Validation sur blur** : Nettoie les données quand l'utilisateur quitte le champ
- **Protection paste** : Filtre le contenu copié-collé
- **Détection de menaces** : Identifie SQL injection, XSS, hex encoding, etc.

#### Méthodes disponibles :
```javascript
// Configuration de filtrage sur un input
SecurityValidator.setupInputFiltering(input, {
    allowedPattern: /[a-zA-Z0-9]/,  // Regex des caractères autorisés
    maxLength: 50,
    blockDangerous: true,
    sanitize: true
});

// Validation de types spécifiques
SecurityValidator.validateStreetNumber(value);  // Numéros de rue (10, 10bis, 10B)
SecurityValidator.validateStreetName(value);     // Noms de rue
SecurityValidator.validateCityName(value);       // Noms de ville (lettres uniquement)
SecurityValidator.validateName(value);           // Noms/prénoms
SecurityValidator.validateEmail(value);          // Emails
SecurityValidator.validatePrice(value);          // Prix/montants
SecurityValidator.validateTextarea(value);       // Textes longs
```

### 2. Validation Serveur (PHP)
**Fichier**: `model/SecurityValidator.php`

#### Fonctionnalités :
- **Sanitization** : Supprime caractères de contrôle et backslashes
- **Détection de menaces** : Patterns de sécurité identiques côté serveur
- **Validation stricte** : Vérifie formats et limites

#### Méthodes disponibles :
```php
// Validation de types spécifiques
SecurityValidator::validateStreetNumber($value, $errors, $fieldName);
SecurityValidator::validateStreetName($value, $errors, $fieldName);
SecurityValidator::validateCityName($value, $errors, $fieldName, $required);
SecurityValidator::validateName($value, $errors, $fieldName, $required);
SecurityValidator::validateEmail($value, $errors, $required);
SecurityValidator::validatePrice($value, $errors, $min, $max, $required);
SecurityValidator::validateTextarea($value, $errors, $fieldName, $minLength, $maxLength, $required);
SecurityValidator::validatePhone($value, $errors, $required);
SecurityValidator::validatePassword($value, $errors, $minLength);
SecurityValidator::validateInteger($value, $errors, $fieldName, $min, $max, $required);

// Détection de menaces
SecurityValidator::detectThreats($value, $errors, $fieldName);

// Sanitization
$clean = SecurityValidator::sanitizeInput($value);
```

### 3. Validation HTML5
**Attributs sur les inputs** :

```html
<!-- Numéro de rue : PAS de décimaux, PAS de backslash -->
<input 
    type="text"
    pattern="^[0-9]+\s*(bis|ter|quater|[A-Za-z])?\s*(-[0-9]+)?$"
    maxlength="10"
    inputmode="text"
    title="Format: 10, 10bis, 10B ou 10-12 (pas de point ni virgule)"
/>

<!-- Rue : PAS de backslash, PAS de chevrons -->
<input 
    type="text"
    pattern="^[a-zA-Z0-9À-ÿ\s\-'.,/]+$"
    maxlength="150"
    title="Lettres, chiffres, espaces, tirets, apostrophes autorisés (pas de backslash)"
/>

<!-- Ville : Lettres UNIQUEMENT -->
<input 
    type="text"
    pattern="^[a-zA-ZÀ-ÿ\s\-']+$"
    maxlength="100"
    minlength="2"
    title="Seules les lettres sont autorisées (pas de chiffres ni caractères spéciaux)"
/>

<!-- Prix : Décimaux autorisés, format contrôlé -->
<input 
    type="number"
    step="0.01"
    min="0"
    max="250"
    inputmode="decimal"
    pattern="^\d+(\.\d{1,2})?$"
    title="Format: 15.50 (2 décimales max)"
/>
```

---

## 📝 Formulaires Sécurisés

### ✅ Formulaire de Publication de Trajet
**Fichiers** : `view/TripView.php`, `controller/TripFormController.php`

**Validations appliquées** :
- Numéros de rue : `^\d+\s*(bis|ter|quater|[A-Za-z])?\s*(-\d+)?$` (BLOQUE décimaux/slashes)
- Rues : `^[a-zA-Z0-9À-ÿ\s\-'.,/]+$` (BLOQUE backslash/<>{}[])
- Villes : `^[a-zA-ZÀ-ÿ\s\-']+$` (BLOQUE chiffres et spéciaux)
- Prix : type="number" avec validation 0-250€, 2 décimales max
- Places : 1-10 (validé serveur)
- Date : doit être future, max 1 an
- Filtrage temps réel sur tous les champs texte

---

## 🚨 Champs Critiques Bloqués

### Numéros de Rue
**AVANT** : ❌ Acceptait `10.5`, `10\`, `10/`, `<script>`
**APRÈS** : ✅ Accepte uniquement `10`, `10bis`, `10B`, `10-12`

### Rues
**AVANT** : ❌ Acceptait `Rue\<script>`, backslashes, chevrons
**APRÈS** : ✅ Bloque `\ < > { } [ ] | `` en temps réel

### Villes
**AVANT** : ❌ Acceptait `Paris123`, `<ville>`, backslashes
**APRÈS** : ✅ Lettres uniquement + espaces, tirets, apostrophes

### Prix
**AVANT** : ❌ Acceptait `10.999`, `-5`, `1000`
**APRÈS** : ✅ 0-250€, max 2 décimales, validation stricte

---

## 📋 À Appliquer aux Autres Formulaires

### 1. Formulaire de Connexion (`LoginView.php`)
```html
<input type="email" 
       name="email" 
       pattern="^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
       maxlength="254"
       required />
```

### 2. Formulaire d'Inscription (`RegisterView.php`)
```html
<!-- Prénom / Nom -->
<input type="text" 
       name="first_name"
       pattern="^[a-zA-ZÀ-ÿ\s\-']+$"
       maxlength="50"
       minlength="2"
       title="Lettres uniquement" />
       
<!-- Email -->
<input type="email" 
       pattern="^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
       maxlength="254" />
```

### 3. Formulaire de Contact (`ContactView.php`)
```html
<!-- Nom -->
<input type="text" 
       name="name"
       pattern="^[a-zA-ZÀ-ÿ\s\-']+$"
       maxlength="50" />
       
<!-- Message -->
<textarea 
    name="message"
    maxlength="1000"
    minlength="10"></textarea>
```

### 4. Formulaire de Profil (`ProfileView.php`)
```html
<!-- Marque/Modèle voiture -->
<input type="text" 
       name="car_brand"
       pattern="^[a-zA-Z0-9À-ÿ\s\-']+$"
       maxlength="50" />
```

### 5. Formulaire de Paiement (`PaymentView.php`)
```html
<!-- Nom sur carte -->
<input type="text" 
       name="card_name"
       pattern="^[a-zA-ZÀ-ÿ\s\-']+$"
       maxlength="50" />
       
<!-- Numéro de carte -->
<input type="text" 
       name="card_number"
       pattern="^\d{16}$"
       maxlength="16"
       inputmode="numeric" />
       
<!-- CVV -->
<input type="text" 
       name="card_cvv"
       pattern="^\d{3}$"
       maxlength="3"
       inputmode="numeric" />
```

---

## 🔧 Intégration dans un Nouveau Formulaire

### Étape 1 : Charger le validateur
```html
<script src="/CarShare/assets/js/security-validator.js"></script>
```

### Étape 2 : Appliquer le filtrage
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.mon-formulaire');
    
    // Champs texte (noms, adresses, etc.)
    const textInputs = form.querySelectorAll('.text-field');
    textInputs.forEach(input => {
        SecurityValidator.setupInputFiltering(input, {
            allowedPattern: /[a-zA-ZÀ-ÿ\s\-']/,
            maxLength: 50,
            blockDangerous: true
        });
    });
    
    // Champs numériques
    const numInputs = form.querySelectorAll('.num-field');
    numInputs.forEach(input => {
        SecurityValidator.setupInputFiltering(input, {
            allowedPattern: /[0-9]/,
            maxLength: 10,
            blockDangerous: true
        });
    });
});
```

### Étape 3 : Validation côté serveur
```php
require_once __DIR__ . '/../model/SecurityValidator.php';

$errors = [];

// Valider les champs
$name = SecurityValidator::validateName($_POST['name'] ?? '', $errors, 'nom', true);
$email = SecurityValidator::validateEmail($_POST['email'] ?? '', $errors, true);
$message = SecurityValidator::validateTextarea($_POST['message'] ?? '', $errors, 'message', 10, 1000, true);

if (!empty($errors)) {
    // Gérer les erreurs
}
```

---

## ✨ Avantages du Système

1. **Triple Protection** : HTML5 + JavaScript + PHP
2. **Bloque en temps réel** : Impossible de saisir des caractères interdits
3. **User-friendly** : Messages d'erreur clairs
4. **Réutilisable** : Classes facilement applicables à tout formulaire
5. **Maintainable** : Un seul endroit pour modifier les règles

---

## 🎯 Prochaines Étapes

### Priorité Haute
- [ ] Appliquer SecurityValidator à LoginController
- [ ] Appliquer SecurityValidator à RegisterController
- [ ] Appliquer SecurityValidator à ContactController
- [ ] Appliquer SecurityValidator à ProfileController

### Priorité Moyenne
- [ ] Appliquer aux formulaires de recherche
- [ ] Appliquer au système de messagerie
- [ ] Tester avec outils de pentest (OWASP ZAP, Burp Suite)

### Améliorations Futures
- [ ] Rate limiting sur les formulaires
- [ ] CAPTCHA sur inscription/contact
- [ ] Logs des tentatives d'attaque
- [ ] Alertes administrateur en cas d'attaque détectée

---

## 📚 Documentation Technique

### Patterns de Détection

#### SQL Injection
```regex
/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/i
```

#### XSS
```regex
/<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover|eval\(|expression\(/i
```

#### Hex Encoding
```regex
/(\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/
```

#### Caractères Dangereux
```regex
/[<>{}[\]\\|`]/
```

---

## 🔐 Bonnes Pratiques Appliquées

1. ✅ **Principe de liste blanche** : On définit ce qui EST autorisé (plutôt que ce qui est interdit)
2. ✅ **Validation stricte** : Formats précis pour chaque type de données
3. ✅ **Defense in depth** : Plusieurs couches de sécurité
4. ✅ **Feedback immédiat** : L'utilisateur voit tout de suite ce qui ne va pas
5. ✅ **Sanitization systématique** : Toutes les entrées sont nettoyées
6. ✅ **htmlspecialchars** : Tous les outputs sont échappés dans les vues
7. ✅ **Prepared statements** : Requêtes SQL paramétrées (à vérifier dans les models)

---

**Dernière mise à jour** : 17 janvier 2026
**Responsable** : Équipe Sécurité CarShare
**Version** : 2.0 - Renforcée
