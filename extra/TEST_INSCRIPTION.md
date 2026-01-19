# 🧪 Tests à effectuer - Formulaire d'inscription

## URL de test
```
http://localhost/CarShare/index.php?action=register
```

---

## ✅ Test 1 : Bouton "Afficher mot de passe" APRÈS erreur

### Procédure :
1. Aller sur la page d'inscription
2. Remplir UNIQUEMENT le mot de passe (ex: `TestPassword123!`)
3. Appuyer sur **Entrée** (ou cliquer sur "S'inscrire")
4. ⚠️ Une erreur apparaît : "Le nom est obligatoire"
5. **TESTER** : Cocher/décocher la checkbox "Afficher les mots de passe"

### ✅ Résultat attendu :
- ✓ La checkbox doit fonctionner normalement
- ✓ Les mots de passe doivent s'afficher/se masquer
- ✓ Aucun blocage, aucune erreur dans la console

---

## ✅ Test 2 : Soumission APRÈS correction des erreurs

### Procédure :
1. Aller sur la page d'inscription
2. Remplir le formulaire avec des données invalides :
   - Nom: `Test`
   - Prénom: `User`
   - Email: `test@example.com`
   - Confirmation email: `different@example.com` ❌ (différent)
   - Mot de passe: `Short1!` ❌ (trop court)
3. Cliquer sur "S'inscrire" → Erreur : "Les adresses email ne correspondent pas"
4. **CORRIGER** tous les champs :
   - Email: `test@example.com`
   - Confirmation email: `test@example.com` ✓
   - Mot de passe: `ValidPassword123!` ✓
   - Confirmation: `ValidPassword123!` ✓
5. Cocher "J'accepte les CGU..."
6. Cliquer sur "S'inscrire"

### ✅ Résultat attendu :
- ✓ Le formulaire doit se soumettre
- ✓ Le bouton passe à "Inscription..."
- ✓ Redirection vers la page de confirmation

---

## ✅ Test 3 : Multiples erreurs successives

### Procédure :
1. Cliquer sur "S'inscrire" sans rien remplir → Erreur CGU
2. Cocher CGU, cliquer → Erreur nom
3. Remplir nom, cliquer → Erreur prénom
4. Remplir prénom, cliquer → Erreur email
5. À chaque étape, tester le bouton "Afficher mot de passe"

### ✅ Résultat attendu :
- ✓ Le toggle password fonctionne à CHAQUE étape
- ✓ Les erreurs s'affichent correctement
- ✓ Le bouton "S'inscrire" reste cliquable

---

## ✅ Test 4 : Inscription complète

### Données valides :
```
Nom: Dupont
Prénom: Jean
Email: jean.dupont@test.com
Confirmation: jean.dupont@test.com
Mot de passe: MonMotDePasse2026!
Confirmation: MonMotDePasse2026!
☑ J'accepte les CGU...
```

### ✅ Résultat attendu :
- ✓ Soumission réussie
- ✓ Email de confirmation envoyé
- ✓ Redirection vers page "En attente de validation"

---

## 🔍 Console JavaScript

Ouvrir la console (F12) et vérifier les messages :
```
[CarShare] Initialisation validation formulaire
[PasswordToggle] ✓ Initialized 1 toggle(s)
[CarShare] Validation formulaire initialisée ✓
```

En cas d'erreur, vérifier :
```
[CarShare] Validation échouée
[PasswordToggle] Password visibility: text
```

---

## 🐛 Si problèmes persistent

### 1. Vider le cache du navigateur
- Chrome : `Ctrl + Shift + Delete`
- Firefox : `Ctrl + Shift + Delete`
- Cocher "Images et fichiers en cache"

### 2. Forcer le rechargement
- `Ctrl + F5` (hard reload)

### 3. Vérifier les fichiers modifiés
- `view/RegisterView.php` : Script de validation v3
- `assets/js/password-toggle.js` : Version ultra-robuste

### 4. Logs à vérifier
Console JavaScript (F12) doit afficher :
- ✓ Initialisation des scripts
- ✓ Changements d'état du toggle
- ✗ Aucune erreur rouge

---

## 📝 Notes techniques

### Architecture corrigée :
1. **Script de validation** (RegisterView.php)
   - Utilise un flag `formValidated` pour éviter double validation
   - Appelle `form.submit()` via JavaScript après validation
   - Réinitialise le flag sur `input` event

2. **Script password-toggle** (password-toggle.js)
   - Indépendant du script de validation
   - Utilise `replaceWith()` pour éviter les listeners dupliqués
   - Flag `initialized` pour éviter double initialisation
   - Réinitialisation automatique après erreur globale

3. **Séparation des responsabilités**
   - Validation : Gère la soumission du formulaire
   - Toggle : Gère uniquement l'affichage des mots de passe
   - Aucune interférence entre les deux

---

## ✅ Checklist finale

- [ ] Toggle password fonctionne avant erreur
- [ ] Toggle password fonctionne après erreur
- [ ] Soumission fonctionne après correction
- [ ] Multiples erreurs successives ne bloquent pas
- [ ] Inscription complète fonctionne
- [ ] Console sans erreur rouge
- [ ] Emails envoyés correctement

---

**Date du test** : _______________  
**Résultat** : ⬜ PASS / ⬜ FAIL  
**Commentaires** : _________________________________
