# ⚙️ CONFIGURATION GOOGLE MAPS - CARSHARE FUSION

## 🔑 Obtenir une clé API Google Maps

### Étape 1 : Créer un compte Google Cloud Platform
1. Aller sur : https://console.cloud.google.com/
2. Se connecter avec compte Google
3. Créer un nouveau projet "CarShare"

### Étape 2 : Activer les APIs nécessaires
Dans Google Cloud Console → APIs & Services → Library :

✅ **Maps JavaScript API** (pour carte interactive)
✅ **Maps Embed API** (pour iframe carte)
✅ **Places API** (pour autocomplete villes)
✅ **Geocoding API** (optionnel : conversion adresses ↔ coordonnées)
✅ **Directions API** (optionnel : itinéraires détaillés)

### Étape 3 : Créer une clé API
1. APIs & Services → Credentials
2. Create Credentials → API Key
3. Copier la clé générée (ex: `AIzaSyBxxxxxxxxxxxxxxxxxxxxxxx`)

### Étape 4 : Sécuriser la clé (IMPORTANT)
**Restrictions d'application :**
- HTTP referrers : 
  - `http://localhost/*`
  - `http://127.0.0.1/*`
  - `https://votre-domaine.com/*`

**Restrictions d'API :**
- Limiter aux APIs activées uniquement

---

## 📝 Configuration dans CarShare

### 1. Fichier `config.php`
```php
// ===== API KEYS =====
define('API_MAPS', 'VOTRE_CLE_API_GOOGLE_MAPS_ICI');
```

**Remplacer :**
```php
define('API_MAPS', ''); // VIDE
```

**Par :**
```php
define('API_MAPS', 'AIzaSyBxxxxxxxxxxxxxxxxxxxxxxx'); // VOTRE CLÉ
```

### 2. Vérifier l'intégration dans `index.php`
La clé est automatiquement utilisée :
```php
<script src="https://maps.googleapis.com/maps/api/js?key=<?= API_MAPS ?>&libraries=places&language=fr"></script>
```

---

## 🗄️ Base de données : Coordonnées GPS

### Structure table `locations`
```sql
CREATE TABLE IF NOT EXISTS locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 8),      -- Nouveau
    longitude DECIMAL(11, 8),     -- Nouveau
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Ajouter colonnes si manquantes
```sql
ALTER TABLE locations ADD COLUMN latitude DECIMAL(10, 8);
ALTER TABLE locations ADD COLUMN longitude DECIMAL(11, 8);
```

### Remplir les coordonnées (script PHP)
```php
<?php
require_once 'config.php';
require_once 'model/Database.php';

$db = Database::getInstance()->getConnection();

// Liste villes françaises avec coordonnées
$cities = [
    ['Paris', 48.8566, 2.3522],
    ['Lyon', 45.7640, 4.8357],
    ['Marseille', 43.2965, 5.3698],
    ['Toulouse', 43.6047, 1.4442],
    ['Nice', 43.7102, 7.2620],
    ['Nantes', 47.2184, -1.5536],
    ['Strasbourg', 48.5734, 7.7521],
    ['Montpellier', 43.6108, 3.8767],
    ['Bordeaux', 44.8378, -0.5792],
    ['Lille', 50.6292, 3.0573]
];

$stmt = $db->prepare("UPDATE locations SET latitude = ?, longitude = ? WHERE name = ?");

foreach ($cities as $city) {
    $stmt->execute([$city[1], $city[2], $city[0]]);
    echo "✅ {$city[0]} : {$city[1]}, {$city[2]}\n";
}

echo "\n🎉 Coordonnées GPS mises à jour !\n";
?>
```

---

## 🧪 Tests de validation

### Test 1 : Vérifier la clé API
```
URL : https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE&libraries=places
Résultat attendu : Script JavaScript chargé sans erreur
```

### Test 2 : Page détails trajet
```
URL : http://localhost/carshare_fusion/index.php?action=trip_details&id=1
Vérifications :
✅ Carte Google Maps affichée
✅ Itinéraire tracé entre départ et arrivée
✅ Pas d'erreur console navigateur (F12)
```

### Test 3 : Page recherche
```
URL : http://localhost/carshare_fusion/index.php?action=display_search&form_start_input=1&form_end_input=2
Vérifications :
✅ Carte affichée à droite (desktop)
✅ Marqueurs présents (vert/orange)
✅ Hover card → highlight map
✅ Bouton toggle fonctionne (mobile)
```

### Console JavaScript (F12)
**Commandes de débogage :**
```javascript
// Vérifier données trajets
console.log(window.tripsMapData);

// Vérifier objet map créé
console.log(map);

// Vérifier marqueurs
console.log(markers);

// Forcer initialisation carte
initSearchMap();
```

---

## ❌ Résolution de problèmes

### Erreur : "Google Maps JavaScript API error: RefererNotAllowedMapError"
**Cause :** Restrictions HTTP referrers trop strictes

**Solution :**
1. Google Cloud Console → Credentials
2. Éditer la clé API
3. Application restrictions → HTTP referrers
4. Ajouter : `http://localhost/*` et `http://127.0.0.1/*`

---

### Carte vide / grise
**Cause :** Clé API non configurée ou invalide

**Solution :**
1. Vérifier `config.php` : `define('API_MAPS', '...');`
2. Vérifier console navigateur (F12) pour message d'erreur
3. Tester clé API avec URL directe
4. Vérifier APIs activées sur Google Cloud

---

### Marqueurs ne s'affichent pas
**Cause :** Coordonnées GPS manquantes dans BDD

**Solution :**
```sql
-- Vérifier données
SELECT id, name, latitude, longitude FROM locations;

-- Si NULL, ajouter coordonnées
UPDATE locations SET latitude = 48.8566, longitude = 2.3522 WHERE name = 'Paris';
```

---

### Carte ne se charge pas sur mobile
**Cause :** JavaScript non exécuté

**Solution :**
1. Vérifier `searchMapIntegration.js` chargé
2. Console mobile : Active debugging
3. Vérifier événement `DOMContentLoaded` trigger
4. Tester fonction `toggleMapView()`

---

## 💰 Tarification Google Maps API

### Quotas gratuits (mensuel)
- **Maps JavaScript API :** 28 000 chargements
- **Maps Embed API :** Illimité
- **Places API :** 3000 requêtes
- **Geocoding API :** 1000 requêtes

### Estimation CarShare
**Avec 100 utilisateurs/jour :**
- Chargements Maps : ~200/jour → **6000/mois** ✅ Gratuit
- Autocomplete Places : ~50/jour → **1500/mois** ✅ Gratuit

**Conclusion :** Usage normal totalement gratuit ! 🎉

---

## 📱 Optimisations Performance

### Lazy loading Maps
```javascript
// Carte chargée uniquement au scroll (optionnel)
const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && !map) {
        initSearchMap();
    }
});
observer.observe(document.getElementById('search-map-container'));
```

### Cache coordonnées
```php
// Stocker coordonnées dans session/cache
$_SESSION['city_coords'] = [
    'Paris' => [48.8566, 2.3522],
    // ...
];
```

---

## 🔐 Sécurité

### Protéger la clé API
**Méthode 1 : Variables d'environnement**
```php
// .env (ne pas commiter)
GOOGLE_MAPS_API_KEY=AIzaSyBxxx...

// config.php
define('API_MAPS', $_ENV['GOOGLE_MAPS_API_KEY']);
```

**Méthode 2 : Backend proxy**
```php
// api/maps-proxy.php
<?php
header('Content-Type: application/json');
$key = API_MAPS; // Clé serveur
$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$_GET['address']}&key=$key";
echo file_get_contents($url);
```

---

## ✅ Checklist finale

- [ ] Clé API Google Maps obtenue
- [ ] APIs activées (JavaScript, Embed, Places)
- [ ] Clé configurée dans `config.php`
- [ ] Restrictions HTTP referrers configurées
- [ ] Table `locations` avec colonnes latitude/longitude
- [ ] Coordonnées GPS remplies pour villes principales
- [ ] Test page détails trajet → Carte OK
- [ ] Test page recherche → Carte + marqueurs OK
- [ ] Test mobile → Bouton toggle OK
- [ ] Console navigateur → Aucune erreur

---

**📞 Support Google Maps :**
- Documentation : https://developers.google.com/maps/documentation
- Console : https://console.cloud.google.com/
- Status : https://status.cloud.google.com/

**🎉 Configuration terminée ! Profitez de votre intégration Google Maps ! 🎉**
