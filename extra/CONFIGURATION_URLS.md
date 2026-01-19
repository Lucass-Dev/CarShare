# Configuration des URLs - CarShare Fusion

## 🌐 Détection automatique de l'environnement

Le système détecte automatiquement l'environnement et génère les URLs appropriées sans configuration manuelle.

## Fonctionnement

### 1. Détection du protocole (HTTP/HTTPS)
```php
// Détecte automatiquement :
// - HTTPS si certificat SSL présent
// - HTTP sinon
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            ? 'https://' : 'http://';
```

### 2. Détection du chemin de base
```php
// Détecte automatiquement le chemin (ex: /carshare_fusion/, /CarShare_fusion/, /)
$scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_PATH', $scriptPath . '/');
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . BASE_PATH);
```

### 3. URL de production (pour emails)
```php
// Utilise BASE_URL (détection auto) sauf si PRODUCTION_URL est définie
define('PRODUCTION_URL', getenv('PRODUCTION_URL') ?: BASE_URL);
```

## 📍 Environnements supportés

### Développement local (XAMPP)
```
http://localhost/carshare_fusion/
http://localhost/CarShare_fusion/
http://127.0.0.1/carshare_fusion/
```
✅ **Fonctionne automatiquement** - Aucune configuration nécessaire

### Serveur de staging
```
http://staging.votredomaine.com/carshare/
https://test.votredomaine.com/
```
✅ **Fonctionne automatiquement** - Aucune configuration nécessaire

### Production avec HTTPS
```
https://www.carshare.com/
https://carshare.votredomaine.com/
```
✅ **Fonctionne automatiquement** - Détecte HTTPS automatiquement

### Production avec sous-dossier
```
https://www.votredomaine.com/carshare/
https://www.votredomaine.com/apps/carshare/
```
✅ **Fonctionne automatiquement** - Détecte le chemin automatiquement

## 🔧 Configuration manuelle (optionnelle)

Si vous voulez forcer une URL spécifique (par exemple, pour un environnement complexe avec proxy), définissez la variable d'environnement :

### Apache (.htaccess)
```apache
SetEnv PRODUCTION_URL "https://www.mondomaine.com/carshare/"
```

### Nginx (fichier de config)
```nginx
fastcgi_param PRODUCTION_URL "https://www.mondomaine.com/carshare/";
```

### PHP (au début de config.php)
```php
putenv('PRODUCTION_URL=https://www.mondomaine.com/carshare/');
```

## 📧 Utilisation dans les emails

### Méthode 1 : Via Config (recommandé dans EmailService)
```php
$baseUrl = Config::getProductionUrl();
$validationLink = $baseUrl . "index.php?action=validate_email&token=" . urlencode($token);
```

### Méthode 2 : Via fonction helper (nouveau)
```php
$validationLink = absoluteUrl("index.php?action=validate_email&token=" . urlencode($token));
```

## 🎯 Exemples de liens générés

### Localhost XAMPP
```
http://localhost/carshare_fusion/index.php?action=validate_email&token=abc123
```

### Serveur staging
```
http://staging.carshare.com/index.php?action=validate_email&token=abc123
```

### Production HTTPS
```
https://www.carshare.com/index.php?action=validate_email&token=abc123
```

### Production avec sous-dossier HTTPS
```
https://www.monsite.com/apps/carshare/index.php?action=validate_email&token=abc123
```

## ✅ Avantages

1. **Zéro configuration** - Fonctionne immédiatement sur tout environnement
2. **Sécurité** - Détecte automatiquement HTTPS
3. **Portabilité** - Déplacer l'application ne nécessite aucun changement
4. **Multi-environnement** - Dev, staging, prod sans modification
5. **Flexibilité** - Configuration manuelle possible si nécessaire

## 🔍 Débogage

Pour vérifier les URLs détectées, ajoutez temporairement :

```php
// Dans config.php (après les defines)
if (isset($_GET['debug_urls'])) {
    echo "BASE_PATH: " . BASE_PATH . "<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
    echo "PRODUCTION_URL: " . PRODUCTION_URL . "<br>";
    exit;
}
```

Puis visitez : `http://localhost/carshare_fusion/?debug_urls`

## 📝 Notes importantes

- Les liens dans les emails utilisent toujours `PRODUCTION_URL` (détection auto ou manuelle)
- Les liens internes dans l'application utilisent `url()` qui utilise `BASE_URL`
- La fonction `absoluteUrl()` est un alias de `Config::getProductionUrl()` pour faciliter l'usage
- Le protocole HTTPS est détecté automatiquement (certificat SSL, port 443, proxy X-Forwarded-Proto)
