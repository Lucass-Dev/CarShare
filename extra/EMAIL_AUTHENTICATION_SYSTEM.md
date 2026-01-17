# Système d'authentification par email - CarShare

## Fonctionnalités implémentées

### 1. Validation d'inscription par email ✅

Lors de l'inscription d'un nouvel utilisateur :
- L'utilisateur remplit le formulaire d'inscription
- Un compte est créé en base de données
- Un email de confirmation est envoyé avec un lien de validation valable 24h
- L'utilisateur clique sur le lien pour activer son compte
- Il est automatiquement connecté après validation

**Flux :**
1. `index.php?action=register` → Formulaire d'inscription
2. Soumission → Email envoyé
3. `index.php?action=registration_pending` → Page d'attente
4. Clic sur lien email → `index.php?action=validate_email&token=xxx`
5. Validation OK → Connexion auto → Redirection accueil

### 2. Mot de passe oublié avec reset par email ✅

Pour réinitialiser un mot de passe oublié :
- L'utilisateur demande un reset depuis la page de connexion
- Il entre son email
- Un email avec un lien sécurisé est envoyé (valable 1h)
- Il clique sur le lien et définit un nouveau mot de passe
- Il est redirigé vers la page de connexion

**Flux :**
1. `index.php?action=forgot_password` → Formulaire email
2. Soumission → Email envoyé
3. Clic sur lien email → `index.php?action=reset_password&token=xxx`
4. Formulaire nouveau mot de passe
5. Soumission → Mot de passe changé → Redirection login

### 3. Changement de mot de passe depuis le profil ✅

Pour modifier son mot de passe depuis les paramètres :
- L'utilisateur va dans son profil
- Il clique sur "Modifier le mot de passe"
- Il entre son mot de passe actuel pour confirmation
- Un email avec lien sécurisé est envoyé (valable 1h)
- Il clique sur le lien et définit le nouveau mot de passe
- Il est redirigé vers la page de connexion

**Flux :**
1. `index.php?action=profile` → Section "Sécurité"
2. Saisie mot de passe actuel → Email envoyé
3. Clic sur lien email → `index.php?action=reset_password&token=xxx`
4. Formulaire nouveau mot de passe
5. Soumission → Mot de passe changé → Redirection login

## Architecture technique

### Fichiers créés/modifiés

**Nouveaux fichiers :**
- `model/EmailService.php` - Service centralisé pour l'envoi d'emails via PHPMailer
- `model/TokenManager.php` - Gestion des tokens sécurisés (stockage fichiers)
- `controller/EmailValidationController.php` - Gestion validation email inscription
- `view/RegistrationPendingView.php` - Page d'attente après inscription
- `view/EmailValidationView.php` - Page de confirmation validation email
- `view/ResetPasswordView.php` - Page de saisie nouveau mot de passe
- `temp/tokens/` - Dossier pour stockage temporaire des tokens

**Fichiers modifiés :**
- `controller/RegisterController.php` - Envoi email validation
- `controller/ForgotPasswordController.php` - Nouveau flux reset par email
- `controller/ProfileController.php` - Changement mot de passe par email
- `model/LoginModel.php` - Ajout méthode `getUserByEmail()`
- `model/RegisterModel.php` - Ajout méthode `getUserById()`
- `view/ForgotPasswordView.php` - Formulaire simplifié (juste email)
- `view/ProfileView.php` - Formulaire simplifié (juste mot de passe actuel)
- `view/LoginView.php` - Message succès après reset
- `view/HomeView.php` - Message bienvenue après inscription
- `index.php` - Ajout routes `registration_pending`, `validate_email`, `reset_password`

### Configuration email (PHPMailer)

**Gmail SMTP configuré dans `EmailService.php` :**
```php
Host: smtp.gmail.com
Port: 587
Username: carshare.cov@gmail.com
Password: mhyyxhsdvhxgxvmn (mot de passe applicatif)
Encryption: STARTTLS
```

### Système de tokens sécurisés

Les tokens sont stockés dans des fichiers temporaires (pas de modification DB nécessaire) :
- **Stockage :** `temp/tokens/[hash].token`
- **Format :** JSON avec user_id, email, type, timestamps
- **Types :** `email_validation` (24h) ou `password_reset` (1h)
- **Sécurité :** Tokens de 64 caractères hexadécimaux générés aléatoirement
- **Nettoyage :** Automatique au chargement du TokenManager

### Templates emails

Tous les emails sont en HTML responsive avec :
- Design moderne cohérent avec le style CarShare
- Version texte brut (AltBody) pour compatibilité
- Boutons CTA clairs
- Instructions pas à pas
- Durée de validité visible
- Footer avec copyright

## Tests à effectuer

### Test 1 : Inscription complète
1. Aller sur `/CarShare/index.php?action=register`
2. Remplir le formulaire d'inscription
3. Vérifier réception email dans carshare.cov@gmail.com
4. Cliquer sur le lien de validation
5. Vérifier connexion automatique et redirection accueil

### Test 2 : Mot de passe oublié
1. Aller sur `/CarShare/index.php?action=login`
2. Cliquer "Mot de passe oublié ?"
3. Entrer un email existant
4. Vérifier réception email
5. Cliquer sur le lien
6. Définir nouveau mot de passe
7. Se connecter avec le nouveau mot de passe

### Test 3 : Changement mot de passe depuis profil
1. Se connecter
2. Aller dans profil (`/CarShare/index.php?action=profile`)
3. Cliquer "Modifier le mot de passe"
4. Entrer mot de passe actuel
5. Vérifier réception email
6. Cliquer sur le lien
7. Définir nouveau mot de passe
8. Se connecter avec le nouveau mot de passe

### Test 4 : Expiration tokens
1. Demander un reset de mot de passe
2. Attendre 1h
3. Essayer d'utiliser le lien → Doit afficher "Token expiré"
4. Possibilité de refaire une demande

## Sécurité

✅ **Pas de modification de la base de données** - Tokens en fichiers temporaires  
✅ **Tokens sécurisés** - 64 caractères aléatoires  
✅ **Expiration** - 24h (inscription) / 1h (reset password)  
✅ **Usage unique** - Token supprimé après utilisation  
✅ **Validation email** - Format et existence vérifiés  
✅ **Complexité mot de passe** - 12 caractères, majuscule, minuscule, chiffre, spécial  
✅ **Emails HTML sécurisés** - Échappement des données utilisateur  
✅ **SSL/TLS** - Connexion SMTP chiffrée  

## Dépannage

### Email non reçu
1. Vérifier les logs PHP : `C:\xampp\php\logs\php_error_log`
2. Vérifier le dossier spam
3. Vérifier que le mot de passe applicatif Gmail est correct
4. Vérifier qu'OpenSSL est activé dans php.ini

### Token invalide/expiré
- Les tokens d'inscription expirent après 24h
- Les tokens de reset expirent après 1h
- Les tokens ne peuvent être utilisés qu'une seule fois
- Faire une nouvelle demande si nécessaire

### Problèmes SMTP
- Vérifier la connexion Internet
- Vérifier les paramètres firewall/antivirus
- Activer les logs SMTP en décommentant dans `EmailService.php` :
  ```php
  $mail->SMTPDebug = 2;
  $mail->Debugoutput = 'error_log';
  ```

## Améliorations futures possibles

- [ ] Ajouter un système de resend email (renvoyer le lien)
- [ ] Limiter le nombre de tentatives de reset (rate limiting)
- [ ] Ajouter une notification email lors du changement d'email
- [ ] Implémenter une authentification à deux facteurs (2FA)
- [ ] Migrer les tokens en base de données pour un environnement distribué
- [ ] Ajouter des statistiques d'utilisation des emails
