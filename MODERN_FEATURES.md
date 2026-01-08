# CarShare - Système de Recherche et Notation Moderne 2026

## 🚀 Nouvelles Fonctionnalités

### 1. Recherche Globale en Temps Réel
- **Localisation** : Barre de recherche dans le header (toutes les pages)
- **Fonctionnement** : Recherche simultanée d'utilisateurs et de trajets
- **Technologie** : Debouncing (300ms), API REST, autocomplete dynamique
- **Fichiers** :
  - `assets/js/global-search.js` - Frontend JavaScript
  - `api/search.php` - Backend API
  - `assets/styles/header.css` - Styles de la barre de recherche

**Utilisation** :
```javascript
// Recherche automatique à la saisie
// Résultats affichés en 2 catégories : Utilisateurs & Trajets
// Clic sur un résultat → navigation vers profil/trajet
```

### 2. Profils Publics Accessibles
- **Route** : `index.php?action=user_profile&id=X`
- **Visibilité** : Accessible sans connexion
- **Contenu** : Note globale, bio, véhicule, statistiques, avis récents
- **Fichiers** :
  - `controller/UserProfileController.php`
  - `view/UserProfileView.php`
  - `assets/styles/user-profile.css`

**Fonctionnalités** :
- ✅ Affichage des notes (étoiles + statistiques détaillées)
- ✅ Informations véhicule si conducteur
- ✅ Historique des avis
- ✅ Message "Connectez-vous" si pas authentifié
- ✅ Boutons Noter/Signaler si connecté

### 3. Système de Notation Modal (Dynamique)
**Plus besoin de page séparée !** Modales modernes avec animations.

**Fichiers** :
- `assets/js/rating-report-modals.js` - Gestion des modales
- `assets/styles/modal-system.css` - Design moderne
- `api/rating.php` - Backend notation
- `api/report.php` - Backend signalement

**Comment ouvrir une modale** :
```html
<!-- Bouton Noter -->
<button data-action="rate-user" 
        data-user-id="123" 
        data-user-name="John Doe">
    ⭐ Noter
</button>

<!-- Bouton Signaler -->
<button data-action="report-user" 
        data-user-id="123" 
        data-user-name="John Doe">
    ⚠️ Signaler
</button>
```

**JavaScript** :
```javascript
// Appel manuel si besoin
window.openRatingModal(userId, userName);
window.openReportModal(userId, userName);
```

### 4. Intégration dans l'Historique
- **Fichier** : `view/HistoryView.php`
- **Nouveautés** :
  - Liens vers profils des conducteurs
  - Boutons "Noter" et "Signaler" sur trajets terminés
  - Badge vert pour trajets complétés
  - Actions contextuelles

**Exemple d'intégration** :
```php
<a href="index.php?action=user_profile&id=<?= $userId ?>" class="user-link">
    <?= htmlspecialchars($userName) ?>
</a>

<button class="action-btn rate-btn" 
        data-action="rate-user" 
        data-user-id="<?= $userId ?>" 
        data-user-name="<?= htmlspecialchars($userName) ?>">
    ⭐ Noter
</button>
```

## 📊 Architecture des Données

### API Search Response
```json
{
  "users": [
    {
      "id": 123,
      "first_name": "John",
      "last_name": "Doe",
      "global_rating": 4.5,
      "car_brand": "Tesla",
      "car_model": "Model 3"
    }
  ],
  "trips": [
    {
      "id": 456,
      "start_location": "Paris",
      "end_location": "Lyon",
      "start_date": "2026-03-15 14:00:00",
      "price": 35.00,
      "available_places": 3,
      "first_name": "John",
      "last_name": "Doe"
    }
  ]
}
```

### API Rating Request
```json
{
  "user_id": 123,
  "rating": 5,
  "comment": "Excellent conducteur !",
  "punctuality": 5,
  "friendliness": 5,
  "safety": 5
}
```

### API Report Request
```json
{
  "user_id": 123,
  "reason": "inappropriate_behavior",
  "description": "Description du problème..."
}
```

## 🎨 Design System

### Couleurs
- **Primary** : `#4f46e5` → `#4338ca` (gradient bleu)
- **Warning** : `#fbbf24` → `#f59e0b` (notation)
- **Danger** : `#ef4444` → `#dc2626` (signalement)
- **Success** : `#10b981` (validé)

### Animations
- Transitions : `cubic-bezier(0.4, 0, 0.2, 1)`
- Durées : 0.2s (interactions), 0.3s (modales)
- Effets : Transform scale, translateY, backdrop-filter blur

### Responsive
- **Desktop** : 1200px max-width
- **Tablet** : 768px breakpoint
- **Mobile** : 480px breakpoint

## 🔒 Sécurité

### Frontend
- XSS Protection : `escapeHtml()` dans global-search.js
- Input sanitization : `htmlspecialchars()` dans toutes les vues

### Backend
- Prepared statements (PDO)
- Validation des données
- Vérification d'authentification (`$_SESSION['logged']`)
- Protection CSRF (à implémenter si nécessaire)

## 📝 TODO

- [ ] Ajouter système de pagination pour les résultats de recherche
- [ ] Implémenter filtres avancés dans la recherche
- [ ] Ajouter notifications temps réel pour nouvelles notes
- [ ] Créer page d'administration pour modération des signalements
- [ ] Ajouter upload de photos de profil
- [ ] Intégrer système de messages avec liens vers profils
- [ ] Ajouter historique de notation (qui a noté qui)
- [ ] Créer badge "Conducteur vérifié"

## 🚀 Déploiement

### Fichiers à charger globalement
```html
<!-- Dans header.php -->
<script src="/CarShare/assets/js/global-search.js"></script>

<!-- Dans index.php pour pages avec modales -->
<link rel="stylesheet" href="/CarShare/assets/styles/modal-system.css">
<script src="/CarShare/assets/js/rating-report-modals.js"></script>
```

### Routes à tester
1. `index.php?action=user_profile&id=1` - Profil utilisateur
2. `index.php?action=history` - Historique avec boutons action
3. Recherche dans header - Saisir "Paris" ou nom d'utilisateur
4. Clic sur bouton "Noter" - Modale avec étoiles
5. Clic sur bouton "Signaler" - Modale avec formulaire

## 💡 Tips Développeur

### Ajouter boutons Noter/Signaler ailleurs
```php
<!-- Dans n'importe quelle vue -->
<button data-action="rate-user" 
        data-user-id="<?= $userId ?>" 
        data-user-name="<?= htmlspecialchars($userName) ?>">
    ⭐ Noter
</button>
```
→ Nécessite `rating-report-modals.js` chargé sur la page

### Personnaliser recherche
```javascript
// Dans global-search.js, modifier la méthode performSearch()
// Pour changer l'API ou les critères de recherche
```

### Ajouter champs dans profil
1. Modifier `UserProfileController.php` (requête SQL)
2. Ajouter affichage dans `UserProfileView.php`
3. Styliser dans `user-profile.css`

---

**Version** : 2.0.0 (Mars 2026)
**Stack** : PHP 8.x, MySQL, Vanilla JS (ES6+), CSS3
**Compatibilité** : Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
