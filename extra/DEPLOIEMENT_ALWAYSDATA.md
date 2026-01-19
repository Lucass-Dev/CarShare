# 🚀 Guide de Déploiement AlwaysData

## 📋 Pré-requis
- Compte AlwaysData actif
- Accès FTP ou SSH
- Base de données MySQL créée sur AlwaysData

## 🔧 Étapes de déploiement

### 1. Configuration de la base de données

#### Option A: Importer via phpMyAdmin AlwaysData
1. Connectez-vous à phpMyAdmin sur AlwaysData
2. Sélectionnez votre base de données
3. Onglet "Importer"
4. Uploadez `sql/carshare.sql`
5. Cliquez sur "Exécuter"

#### Option B: Importer via SSH
```bash
mysql -h mysql-VOTRE-COMPTE.alwaysdata.net -u VOTRE_USER -p VOTRE_DB < sql/carshare.sql
```

### 2. Upload des fichiers

#### Via FTP (FileZilla, Cyberduck, etc.)
1. Connectez-vous à `ftp-VOTRE-COMPTE.alwaysdata.net`
2. Naviguez vers `/www/` (ou votre dossier cible)
3. Uploadez TOUS les fichiers du projet:
   - `assets/`
   - `controller/`
   - `model/`
   - `view/`
   - `src/`
   - `sql/`
   - `uploads/`
   - `index.php`
   - `config.php`
   - `.htaccess`

#### Via SSH (plus rapide)
```bash
# Depuis votre machine locale
scp -r carshare_fusion/* VOTRE-COMPTE@ssh-VOTRE-COMPTE.alwaysdata.net:~/www/
```

### 3. Configuration du fichier config.php

Le fichier [config.php](config.php) détecte **automatiquement** l'environnement:

#### ✅ Détection automatique (recommandé)
```php
// Local: localhost, 127.0.0.1
// Production: tout le reste (AlwaysData, etc.)

if ($isProduction) {
    // Sera automatiquement utilisé sur AlwaysData
    define('DB_HOST', 'mysq-carshare-mailsacrifice14-49e2.k.aivencloud.com');
    define('DB_PORT', '12919');
    define('DB_NAME', 'defaultdb');
    define('DB_USER', 'avnadmin');
    define('DB_PASS', 'AVNS_XNovxzBfxwaL50YjpsJ');
    define('DB_SSL_MODE', 'REQUIRED');
}
```

#### ⚙️ Configuration manuelle (si besoin)
Si vous utilisez la base MySQL d'AlwaysData au lieu d'Aiven:

```php
if ($isProduction) {
    define('DB_HOST', 'mysql-VOTRE-COMPTE.alwaysdata.net');
    define('DB_PORT', '3306');
    define('DB_NAME', 'VOTRE-COMPTE_carshare');
    define('DB_USER', 'VOTRE-COMPTE');
    define('DB_PASS', 'VOTRE-MOT-DE-PASSE-MYSQL');
    define('DB_SSL_MODE', 'DISABLED');
}
```

### 4. Configuration du site AlwaysData

#### Dans le panneau d'administration AlwaysData:
1. **Sites** → **Ajouter un site**
2. **Type**: PHP
3. **Adresse**: votre-domaine.alwaysdata.net (ou domaine personnalisé)
4. **Répertoire**: `/www/` ou `/www/carshare_fusion/`
5. **Version PHP**: 8.0 ou supérieure

### 5. Permissions des dossiers
```bash
# Via SSH
chmod 755 ~/www
chmod 755 ~/www/uploads
chmod 755 ~/www/uploads/profile_pictures
chmod 644 ~/www/config.php
chmod 644 ~/www/.htaccess
```

### 6. Vérification

#### ✅ Tests à effectuer:
1. **Page d'accueil**: `https://votre-site.alwaysdata.net/`
2. **Assets CSS/JS**: Vérifier dans l'inspecteur que les fichiers se chargent
3. **Connexion BDD**: Tester une connexion utilisateur
4. **Inscription**: Créer un compte test
5. **Upload**: Tester l'upload d'une photo de profil

### 7. Configuration BASE_PATH

Le système détecte **automatiquement** le chemin:

| Cas | BASE_PATH | BASE_URL |
|-----|-----------|----------|
| Racine `www/` | `/` | `https://monsite.alwaysdata.net/` |
| Sous-dossier `www/carshare/` | `/carshare/` | `https://monsite.alwaysdata.net/carshare/` |

Pas de modification manuelle nécessaire! 🎉

## 🐛 Dépannage

### Problème: Pages blanches
**Solution**: Vérifier les logs PHP
```bash
# Via SSH
tail -f ~/admin/logs/php/YYYY-MM-DD.log
```

### Problème: Erreur 500
**Causes courantes**:
- Permissions incorrectes
- Erreur dans config.php
- Syntaxe PHP incorrecte

**Solution**: Activer l'affichage des erreurs temporairement
```php
// En haut de index.php (UNIQUEMENT pour debug)
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Problème: CSS/JS ne se chargent pas
**Causes**:
- Chemin BASE_PATH incorrect
- Fichiers .htaccess mal configuré
- Assets non uploadés

**Solution**: Vérifier dans l'inspecteur (F12) les URLs des ressources

### Problème: Connexion BDD échoue
**Vérifications**:
1. Credentials dans config.php
2. Base de données créée sur AlwaysData
3. Utilisateur MySQL a les droits

**Test de connexion**:
```php
// test_db.php
<?php
require_once 'config.php';
require_once 'model/Database.php';
try {
    $db = Database::getInstance()->getConnection();
    echo "✅ Connexion réussie!";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
```

### Problème: Upload de fichiers ne fonctionne pas
**Vérifications**:
1. Dossier `uploads/` existe
2. Permissions 755 sur `uploads/` et `uploads/profile_pictures/`
3. `upload_max_filesize` dans php.ini (géré par AlwaysData)

## 🔒 Sécurité en production

### ✅ Checklist de sécurité:
- [ ] `.htaccess` en place (protège model/, controller/, etc.)
- [ ] Fichiers sensibles non accessibles
- [ ] HTTPS activé (certificat SSL gratuit AlwaysData)
- [ ] Tokens CSRF activés
- [ ] Validation des inputs côté serveur
- [ ] Prepared statements PDO (déjà implémenté)
- [ ] Passwords hashés avec password_hash() (déjà fait)

## 📊 Optimisations

### Cache PHP Opcache
Dans le panneau AlwaysData:
- **Environnement** → **PHP** → Activer **Opcache**

### Compression gzip
Déjà configurée dans `.htaccess`

### CDN (optionnel)
Pour les assets statiques, utiliser Cloudflare devant AlwaysData

## 📝 Maintenance

### Backup de la base
```bash
# Via SSH
mysqldump -h mysql-VOTRE-COMPTE.alwaysdata.net -u VOTRE_USER -p VOTRE_DB > backup_$(date +%Y%m%d).sql
```

### Mise à jour du code
```bash
# Via SSH
cd ~/www
# Pull depuis Git ou upload via FTP
```

### Vider le cache
```bash
# Supprimer les fichiers temporaires
rm -rf ~/www/temp/*
```

## 🎉 C'est tout!

Votre application CarShare est maintenant déployée sur AlwaysData! 🚗

**URL de production**: `https://votre-site.alwaysdata.net`

---

**Support**: En cas de problème, consulter:
- Documentation AlwaysData: https://help.alwaysdata.com/
- Logs PHP: `~/admin/logs/php/`
- Logs Apache: `~/admin/logs/http/`
