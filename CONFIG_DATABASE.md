# 🚗 CarShare - Configuration Base de Données

## 📋 Configuration Rapide (XAMPP)

### 1. Fichier .env

Le fichier `.env` a été créé avec la configuration par défaut de XAMPP :

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=carshare
DB_USER=root
DB_PASS=
```

### 2. Démarrer XAMPP

1. Ouvrez le **Control Panel XAMPP**
2. Démarrez **Apache** et **MySQL**

### 3. Créer la base de données

**Option A - Via le script automatique :**
```
http://localhost/CarShare/create_database.php
```

**Option B - Via phpMyAdmin :**
1. Accédez à http://localhost/phpmyadmin
2. Créez une nouvelle base nommée `carshare`
3. Importez le fichier `sql/carshare.sql`

### 4. Tester la connexion

```
http://localhost/CarShare/test_db_connection.php
```

Ce script vérifie :
- ✅ Configuration des variables d'environnement
- ✅ Connexion au serveur MySQL
- ✅ Accès à la base de données
- ✅ Présence des tables nécessaires

### 5. Accéder au site

```
http://localhost/CarShare/
```

## 🔧 Dépannage

### Erreur : "Erreur de connexion à la base de données"

**Solutions :**

1. **Vérifier que MySQL est démarré**
   - Ouvrez XAMPP Control Panel
   - Cliquez sur "Start" pour MySQL
   - Le statut doit être vert

2. **Vérifier le fichier .env**
   - Le fichier `.env` doit exister à la racine
   - Vérifier que `DB_NAME=carshare` correspond au nom de votre base

3. **Vérifier que la base existe**
   - Accédez à phpMyAdmin (http://localhost/phpmyadmin)
   - Vérifiez qu'une base nommée `carshare` existe
   - Sinon, créez-la ou lancez `create_database.php`

4. **Importer la structure**
   - Dans phpMyAdmin, sélectionnez la base `carshare`
   - Cliquez sur "Importer"
   - Sélectionnez le fichier `sql/carshare.sql`
   - Cliquez sur "Exécuter"

### Erreur : "Access denied for user"

Vérifiez dans le fichier `.env` :
- `DB_USER=root` (par défaut XAMPP)
- `DB_PASS=` (vide par défaut XAMPP)

### Port MySQL différent

Si MySQL utilise un port différent (ex: 3307) :
```env
DB_PORT=3307
```

## 📁 Fichiers de configuration

- `.env` - Configuration locale (ne pas commit)
- `.env.example` - Exemple de configuration
- `config.php` - Charge les variables d'environnement
- `model/Database.php` - Classe de connexion PDO

## 🔐 Sécurité

⚠️ Le fichier `.env` est ignoré par Git et ne doit **jamais** être commité !

Pour la production, créez un nouveau fichier `.env` avec des identifiants sécurisés.

## 📞 Support

Si vous rencontrez toujours des problèmes :
1. Lancez `test_db_connection.php` pour un diagnostic détaillé
2. Vérifiez les logs d'erreur PHP dans XAMPP
3. Consultez la documentation XAMPP

---

✨ Configuration créée le 21 janvier 2026
