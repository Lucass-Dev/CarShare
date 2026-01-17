# Guide de mise en production CarShare

## ✅ Modifications effectuées pour la production

### 1. **Système d'URLs dynamiques**

Un fichier [model/Config.php](../model/Config.php) a été créé pour gérer automatiquement les URLs en fonction de l'environnement :

```php
// Utilisation dans les contrôleurs
Config::redirect('home'); // au lieu de header('Location: /CarShare/...')
Config::url('login'); // génère l'URL complète
Config::asset('assets/styles/home.css'); // pour les ressources statiques
```

**Fonctionnement :**
- En local : `http://localhost/CarShare/index.php?action=home`
- En production : `https://votredomaine.com/index.php?action=home`

### 2. **Vues améliorées avec CSS moderne**

Les pages d'authentification par email ont été refaites :
- [view/ResetPasswordView.php](../view/ResetPasswordView.php) - Design moderne avec animations
- [view/RegistrationPendingView.php](../view/RegistrationPendingView.php) - Page d'attente améliorée
- [view/EmailValidationView.php](../view/EmailValidationView.php) - Validation avec feedback visuel

**Améliorations :**
- CSS embarqué (pas de dépendance externe)
- Design responsive mobile-first
- Animations et effets visuels
- URLs dynamiques avec `Config::url()`

### 3. **Contrôleurs mis à jour**

Tous les contrôleurs liés à l'authentification utilisent maintenant `Config` :
- [controller/RegisterController.php](../controller/RegisterController.php)
- [controller/ForgotPasswordController.php](../controller/ForgotPasswordController.php)
- [controller/ProfileController.php](../controller/ProfileController.php)
- [controller/EmailValidationController.php](../controller/EmailValidationController.php)

### 4. **Service email dynamique**

[model/EmailService.php](../model/EmailService.php) génère maintenant les liens de validation de manière dynamique.

---

## 📋 Checklist avant mise en production

### Étape 1 : Configuration serveur

- [ ] PHP 7.4+ installé
- [ ] Extension OpenSSL activée
- [ ] Extension PDO MySQL activée
- [ ] Permissions correctes sur `temp/tokens/` (chmod 700)
- [ ] .htaccess configuré si nécessaire

### Étape 2 : Base de données

- [ ] Importer la structure de base de données
- [ ] Vérifier que la table `users` existe
- [ ] Modifier [model/Database.php](../model/Database.php) avec les identifiants de production :

```php
private static $dbName   = 'votre_db_production';
private static $host     = 'localhost'; // ou IP du serveur MySQL
private static $user     = 'votre_user';
private static $password = 'votre_password_securise';
```

### Étape 3 : Configuration email

Dans [model/EmailService.php](../model/EmailService.php), vérifier les paramètres SMTP :

```php
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'carshare.cov@gmail.com';
$mail->Password = 'mhyyxhsdvhxgxvmn'; // Mot de passe applicatif Gmail
```

**Si vous changez de fournisseur email :**
- Modifier `Host`, `Port`, `Username`, `Password`
- Adapter `SMTPSecure` si nécessaire

### Étape 4 : Fichiers à transférer

Transférer tous les fichiers SAUF :
- `extra/` (documentation, peut être exclu)
- `.git/` (si vous utilisez Git)
- Fichiers de développement locaux

**Structure minimale requise :**
```
/
├── api/
├── assets/
├── controller/
├── model/
├── src/ (PHPMailer)
├── temp/
│   └── tokens/ (avec permissions 700)
├── view/
├── index.php
└── .htaccess (si Apache)
```

### Étape 5 : Configuration .htaccess (Apache)

Si votre hébergeur utilise Apache, créez/modifiez `.htaccess` :

```apache
# Activer le module de réécriture
RewriteEngine On

# Redirection HTTPS (recommandé en production)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Protection des dossiers sensibles
<FilesMatch "\.(sql|log|token)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Configuration PHP (si autorisé)
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value memory_limit 128M
php_flag display_errors Off
```

### Étape 6 : Sécurité

- [ ] Désactiver `display_errors` en production (dans php.ini ou .htaccess)
- [ ] Configurer `error_log` pour logger dans un fichier
- [ ] Sécuriser les permissions des fichiers (644 pour fichiers, 755 pour dossiers)
- [ ] Protéger `temp/tokens/` avec permissions 700
- [ ] Supprimer tout fichier de test (`test-*.php`, etc.)

### Étape 7 : Tests en production

Une fois déployé, tester :

1. **Navigation générale**
   - [ ] Page d'accueil accessible
   - [ ] Navigation entre les pages
   - [ ] Assets (CSS/JS) chargés correctement

2. **Inscription avec email**
   - [ ] Formulaire d'inscription fonctionne
   - [ ] Email de confirmation reçu
   - [ ] Lien de validation fonctionne
   - [ ] Connexion automatique après validation

3. **Mot de passe oublié**
   - [ ] Demande de reset fonctionne
   - [ ] Email de reset reçu
   - [ ] Lien de reset fonctionne
   - [ ] Nouveau mot de passe enregistré

4. **Changement mot de passe depuis profil**
   - [ ] Demande depuis profil fonctionne
   - [ ] Email de confirmation reçu
   - [ ] Nouveau mot de passe fonctionne

---

## 🔧 Dépannage production

### Problème : "Page not found" ou erreurs 404

**Cause :** Le chemin de base est incorrect

**Solution :** Vérifier que `Config::getBaseUrl()` détecte correctement votre environnement.

Ajouter du debug temporaire dans [model/Config.php](../model/Config.php) :
```php
public static function getBaseUrl() {
    error_log("SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME']);
    error_log("HTTP_HOST: " . $_SERVER['HTTP_HOST']);
    // ... reste du code
}
```

### Problème : Emails non reçus

**Causes possibles :**
1. Pare-feu bloquant le port 587
2. Identifiants SMTP incorrects
3. IP du serveur bloquée par Gmail

**Solutions :**
1. Vérifier les logs : `/var/log/apache2/error.log` ou équivalent
2. Activer le debug SMTP dans [model/EmailService.php](../model/EmailService.php) :
   ```php
   $mail->SMTPDebug = 2;
   $mail->Debugoutput = 'error_log';
   ```
3. Contacter l'hébergeur pour vérifier les restrictions SMTP

### Problème : Tokens invalides

**Cause :** Permissions incorrectes sur `temp/tokens/`

**Solution :**
```bash
chmod 700 temp/tokens/
chown www-data:www-data temp/tokens/ # ou l'utilisateur du serveur web
```

### Problème : CSS/JS non chargés

**Cause :** Chemins incorrects vers les assets

**Solution :** Utiliser `Config::asset()` dans les vues :
```php
<link rel="stylesheet" href="<?= Config::asset('assets/styles/home.css') ?>">
```

---

## 🚀 Optimisations recommandées

### 1. Cache et performances

Ajouter dans `.htaccess` :
```apache
# Cache pour les assets statiques
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript
</IfModule>
```

### 2. Logs et monitoring

Créer un système de logs centralisé :
```php
// Dans model/Config.php
public static function logError($message, $context = []) {
    $logFile = __DIR__ . '/../temp/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? json_encode($context) : '';
    file_put_contents($logFile, "[$timestamp] $message $contextStr\n", FILE_APPEND);
}
```

### 3. Backup automatique

Configurer des sauvegardes régulières :
- Base de données (mysqldump quotidien)
- Dossier `temp/tokens/`
- Fichiers de configuration

---

## 📞 Support

En cas de problème persistant :
1. Vérifier les logs serveur
2. Activer le mode debug temporairement
3. Consulter la documentation de l'hébergeur
4. Vérifier les forums PHP/MySQL de votre hébergeur

**Logs importants à vérifier :**
- `/var/log/apache2/error.log` (Apache)
- `/var/log/nginx/error.log` (Nginx)
- `temp/app.log` (si créé)
- Logs de l'hébergeur (via cPanel, Plesk, etc.)
