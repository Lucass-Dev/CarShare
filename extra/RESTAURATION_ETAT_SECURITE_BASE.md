# 🔄 Restauration État Sécurité de Base
**Date**: 17 janvier 2026  
**Action**: Retour à l'état après implémentation de la sécurité de base

---

## 🎯 État Restauré

Le formulaire de publication de trajet a été **restauré** à l'état **après la demande de sécurité de base**, mais **avant la validation française stricte**.

### ✅ Ce qui est CONSERVÉ

1. **Système SecurityValidator complet** (JS + PHP)
   - Détection des menaces (XSS, SQL injection, hex encoding, etc.)
   - Sanitisation des inputs
   - Blocage des caractères dangereux de base : `< > { } [ ] \ | \``
   - Filtrage temps réel avec `setupInputFiltering()`

2. **Triple validation**
   - HTML5 patterns
   - JavaScript temps réel
   - PHP serveur

3. **Protection contre décimaux dans numéros de rue**
   - Pattern: `/^[0-9]+\s*(bis|ter|quater|[A-Za-z])?\s*(-[0-9]+)?$/`
   - Bloque: `10.5`, `12,3`, etc.

### ✅ Ce qui est RÉAUTORISÉ

Dans les **champs de rue** uniquement :

- **Points** `.` → Ex: `Rue Dr. Martin`
- **Virgules** `,` → Ex: `Rue de la Paix, prolongée`
- **Slashes** `/` → Ex: `Rue A/B`

**Pattern actuel** : `^[a-zA-Z0-9À-ÿ\s\-'.,/]+$`

### ❌ Ce qui est SUPPRIMÉ

1. **Validation française stricte**
   - ✗ Liste des 47 types de voies (rue, avenue, boulevard...)
   - ✗ Vérification sémantique des formats français
   - ✗ Détection patterns suspects (séquences de chiffres > 5, répétitions > 4)
   - ✗ Logs des formats inhabituels

2. **Fichiers de test/documentation français strict**
   - ✗ `VALIDATION_RUES_STRICT.md` supprimé
   - ✗ `test-validation-rues.html` supprimé

---

## 📁 Fichiers Modifiés

### 1. `assets/js/security-validator.js`

**Changements** :
```javascript
// AVANT (français strict)
static validStreetTypes = ['rue', 'avenue', ...]; // 47 types
if (!/^[a-zA-Z0-9À-ÿ\s\-']+$/.test(sanitized)) // Bloquait .,/
if (/\d{5,}/.test(sanitized)) // Bloquait séquences de chiffres

// APRÈS (sécurité de base)
// Pas de validStreetTypes
if (!/^[a-zA-Z0-9À-ÿ\s\-'.,\/]+$/.test(sanitized)) // Autorise .,/
// Pas de blocage séquences chiffres
```

**Caractères autorisés dans rues** :
- `a-z A-Z` - Lettres
- `À-ÿ` - Lettres accentuées
- `0-9` - Chiffres
- ` ` - Espaces
- `-` - Tirets
- `'` - Apostrophes
- **.** - Points (réautorisé)
- **,** - Virgules (réautorisé)
- **/** - Slashes (réautorisé)

**Caractères bloqués** :
- `< > { } [ ] \ | \`` - Caractères dangereux de base
- Détection menaces : XSS, SQL injection, hex encoding, etc.

### 2. `model/SecurityValidator.php`

**Changements** :
```php
// AVANT (français strict)
private static $validStreetTypes = ['rue', 'avenue', ...]; // 47 types
if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\-\']+$/u', $sanitized)) // Bloquait .,/
if (preg_match('/\d{5,}/', $sanitized)) // Bloquait séquences

// APRÈS (sécurité de base)
// Pas de $validStreetTypes
if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\-\',.\/]+$/u', $sanitized)) // Autorise .,/
// Pas de blocage séquences
```

**Pattern PHP** : `/^[a-zA-Z0-9À-ÿ\s\-\',.\/]+$/u`

### 3. `view/TripView.php`

**Changements** :
```html
<!-- AVANT (français strict) -->
pattern="^[a-zA-Z0-9À-ÿ\s\-']+$"
title="... (lettres, chiffres, espaces, tirets, apostrophes uniquement)"

<!-- APRÈS (sécurité de base) -->
pattern="^[a-zA-Z0-9À-ÿ\s\-'.,/]+$"
title="Format: Rue de la République, Avenue Victor Hugo"
```

**Champs modifiés** :
- `dep-street` (rue de départ)
- `arr-street` (rue d'arrivée)

### 4. `assets/js/create-trip-enhanced.js`

**Changements** :
```javascript
// AVANT (français strict)
allowedPattern: /[a-zA-Z0-9À-ÿ\s\-']/,  // Sans .,/

// APRÈS (sécurité de base)
allowedPattern: /[a-zA-Z0-9À-ÿ\s\-'.,\/]/,  // Avec .,/
```

**Commentaire** : "Rues - avec points, virgules, slashes autorisés"

---

## 🛡️ Niveau de Sécurité Actuel

### Protections ACTIVES ✅

| Protection | Statut | Détails |
|------------|--------|---------|
| **XSS** | ✅ Active | Chevrons `<>` bloqués |
| **SQL Injection** | ✅ Active | Détection patterns SQL |
| **Hex Encoding** | ✅ Active | Détection `%XX` |
| **Binary Encoding** | ✅ Active | Détection `\x` |
| **Unicode Exploits** | ✅ Active | Détection `\u` |
| **Control Characters** | ✅ Active | Détection caractères contrôle |
| **Path Traversal Partiel** | ⚠️ Partielle | Backslash `\` bloqué, mais slash `/` autorisé |
| **Décimaux Numéros** | ✅ Active | `10.5` bloqué dans numéros de rue |
| **Command Injection** | ⚠️ Partielle | Certains caractères bloqués (`,` autorisé) |

### Vulnérabilités POSSIBLES ⚠️

1. **Slashes autorisés** `/`
   - Peut permettre : `C:/Windows/System32`
   - **Risque** : Path traversal si utilisé dans chemins fichiers
   - **Mitigation** : Validation côté serveur + pas d'utilisation directe dans chemins

2. **Virgules autorisées** `,`
   - Peut permettre : `Rue,test@mail.com,cc:attacker@evil.com`
   - **Risque** : Email injection si utilisé dans headers email
   - **Mitigation** : Sanitisation dans envoi emails

3. **Points autorisés** `.`
   - Peut permettre : `Rue..././etc/passwd`
   - **Risque** : Path traversal relatif
   - **Mitigation** : Pas d'utilisation directe dans chemins fichiers

4. **Pas de limite séquences chiffres**
   - Peut permettre : `Rue123456789012345678901234567890`
   - **Risque** : Buffer overflow potentiel (très faible)
   - **Mitigation** : Limite maxlength=150 en place

---

## 📊 Comparaison États

| Aspect | Sécurité de Base (ACTUEL) | Français Strict (PRÉCÉDENT) |
|--------|---------------------------|------------------------------|
| **Caractères rues** | `a-zA-Z0-9À-ÿ\s\-'.,/` | `a-zA-Z0-9À-ÿ\s\-'` |
| **Points** | ✅ Autorisés | ❌ Bloqués |
| **Virgules** | ✅ Autorisées | ❌ Bloquées |
| **Slashes** | ✅ Autorisés | ❌ Bloqués |
| **Validation française** | ❌ Absente | ✅ 47 types de voies |
| **Patterns suspects** | ❌ Absente | ✅ Séquences > 5, répétitions > 4 |
| **Niveau sécurité** | ⭐⭐⭐⭐ (Bon) | ⭐⭐⭐⭐⭐ (Maximal) |

---

## 🧪 Tests à Effectuer

### Tests VALIDES (doivent passer) ✅

```
✓ Rue de la République
✓ Avenue des Champs-Élysées
✓ Boulevard Victor Hugo
✓ Rue Dr. Martin (avec point)
✓ Rue de la Paix, prolongée (avec virgule)
✓ Rue A/B (avec slash)
✓ 12 Rue du Commerce
✓ Rue du 8 Mai 1945
✓ Rue123456789 (séquence de chiffres longue OK maintenant)
```

### Tests INVALIDES (doivent être bloqués) ❌

```
✗ Rue<script>alert(1)</script> (chevrons bloqués)
✗ Rue{test} (accolades bloquées)
✗ Rue\test (backslash bloqué)
✗ Rue|test (pipe bloqué)
✗ Rue`test` (backtick bloqué)
✗ Rue'; DROP TABLE (détection SQL injection)
✗ Rue%3Cscript%3E (détection hex encoding)
```

---

## 📝 Recommandations

### Si vulnérabilités détectées

**Option 1** : Revenir à la validation française stricte
- Bloquer à nouveau .,/
- Réactiver validation sémantique
- Récupérer fichiers : `git checkout VALIDATION_RUES_STRICT.md test-validation-rues.html`

**Option 2** : Validation contextuelle
- Autoriser .,/ en saisie
- Bloquer en cas de patterns suspects détectés
- Ajouter validation serveur plus stricte

**Option 3** : Compromis
- Autoriser points `.` (Dr., St., etc.)
- Bloquer virgules `,` et slashes `/`
- Garder détection menaces actuelle

### Utilisation sécurisée des données

⚠️ **IMPORTANT** : Ne JAMAIS utiliser les rues saisies dans :
- Chemins de fichiers : `fopen($rue)`, `file_get_contents($rue)`
- Commandes shell : `exec("command " . $rue)`
- Headers email : `mail($to, $subject, $message, "From: " . $rue)`
- Requêtes SQL sans préparation : `"SELECT * FROM ... WHERE rue = '$rue'"`

✅ **Usage sûr** :
- Affichage HTML (avec `htmlspecialchars()`)
- Requêtes préparées : `$stmt->execute([$rue])`
- Stockage base de données
- Comparaisons textuelles

---

## 🔐 Niveau de Protection Actuel

### Note Globale : ⭐⭐⭐⭐ (4/5)

| Catégorie | Note | Commentaire |
|-----------|------|-------------|
| **XSS** | ⭐⭐⭐⭐⭐ | Excellente protection |
| **SQL Injection** | ⭐⭐⭐⭐⭐ | Excellente protection |
| **Path Traversal** | ⭐⭐⭐ | Slash autorisé = risque |
| **Command Injection** | ⭐⭐⭐⭐ | Bonne protection |
| **Format Validation** | ⭐⭐⭐ | Base mais pas français strict |

---

## 📚 Documentation Associée

- **Sécurité générale** : `SECURITE_RENFORCEE.md`
- **Guide de test** : `PLAN_DE_TESTS.md`
- **Adaptations BDD** : `extra/ADAPTATIONS_DATABASE_CARSHARE.md`

---

**État restauré** : Sécurité de Base ✅  
**Dernière mise à jour** : 17 janvier 2026  
**Niveau sécurité** : 4/5 ⭐⭐⭐⭐
