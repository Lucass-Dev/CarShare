# 🚀 Guide Rapide - URLs Dynamiques CarShare

## ✅ Ce qui a été fait

Le système génère maintenant **automatiquement** les bonnes URLs selon l'environnement, sans configuration manuelle.

## 🌐 Environnements supportés automatiquement

### 1. Localhost (XAMPP/WAMP/MAMP)
```
http://localhost/carshare_fusion/
http://localhost/CarShare_fusion/
http://127.0.0.1/carshare_fusion/
```

### 2. Serveur de développement
```
http://dev.carshare.com/
http://192.168.1.100/carshare/
```

### 3. Serveur de staging
```
https://staging.carshare.com/
http://test.mondomaine.com/apps/carshare/
```

### 4. Production
```
https://www.carshare.com/
https://carshare.mondomaine.com/
https://www.mondomaine.com/carshare/
```

## 🔍 Comment vérifier

1. Accédez à : `http://localhost/carshare_fusion/?action=debug_config`
2. Vous verrez toutes les URLs détectées
3. Vérifiez que les liens sont corrects

## 📧 Liens dans les emails

Les emails générés contiendront automatiquement :

### Sur localhost :
```
http://localhost/carshare_fusion/index.php?action=validate_email&token=...
```

### En production HTTPS :
```
https://www.carshare.com/index.php?action=validate_email&token=...
```

## 🎯 Exemple pratique

Déplacez votre projet de :
- `C:\xampp\htdocs\carshare_fusion\` → Les liens utiliseront `/carshare_fusion/`
- `C:\xampp\htdocs\CarShare_fusion\` → Les liens utiliseront `/CarShare_fusion/`
- `C:\xampp\htdocs\apps\carshare\` → Les liens utiliseront `/apps/carshare/`

**Aucun changement de code nécessaire** ! 🎉

## 🔧 Configuration manuelle (optionnelle)

Seulement si vous avez un environnement complexe avec proxy/load balancer :

Ajoutez au début de `config.php` :
```php
putenv('PRODUCTION_URL=https://www.mondomaine.com/carshare/');
```

## ✅ Avantages

- ✅ Fonctionne sur localhost immédiatement
- ✅ Pas de configuration lors du déploiement
- ✅ Détecte automatiquement HTTP/HTTPS
- ✅ Supporte les sous-dossiers
- ✅ Les emails fonctionnent partout

## 🧪 Tests

1. **Test local** :
   - Inscrivez-vous avec un email
   - Vérifiez le lien dans l'email reçu
   - Il doit pointer vers `http://localhost/...`

2. **Test production** :
   - Déployez sur serveur
   - Inscrivez-vous
   - Le lien doit pointer vers `https://votredomaine.com/...`

## 📝 Fichiers modifiés

- ✅ `config.php` - Détection automatique du protocole et du chemin
- ✅ `EmailService.php` - Utilise déjà `Config::getProductionUrl()`
- ✅ Page debug ajoutée : `?action=debug_config`

## ⚠️ Important

La page de debug (`?action=debug_config`) ne fonctionne **que sur localhost** pour des raisons de sécurité.
