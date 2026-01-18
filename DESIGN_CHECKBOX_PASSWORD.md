# 🎨 Design des Checkboxes - Affichage du Mot de Passe

## 📁 Fichiers Créés/Modifiés

### Nouveaux Fichiers CSS & JS
- **`assets/styles/password-toggle-checkbox.css`** - Styles modernes pour les checkboxes
- **`assets/js/password-toggle.js`** - Logique réutilisable pour le toggle des mots de passe

### Fichiers Modifiés
- **`view/LoginView.php`** - Formulaire de connexion avec checkbox stylisée
- **`view/RegisterView.php`** - Formulaire d'inscription avec checkbox stylisée
- **`assets/styles/inscr.css`** - Suppression des anciens styles de checkbox
- **`assets/styles/conn.css`** - Déjà nettoyé précédemment

## ✨ Caractéristiques du Design

### 1. **Custom Checkbox Moderne**
- ✅ Checkbox personnalisée avec animation fluide
- ✅ État checked avec dégradé bleu élégant (`#a9b2ff` → `#8f9bff`)
- ✅ Icône checkmark (✓) animée qui apparaît au clic
- ✅ Box-shadow subtile pour l'effet de profondeur

### 2. **Interactions Avancées**
- 🎯 **Hover** : Background léger + transformation scale(1.05)
- 🎯 **Active** : Animation scale(0.95) au clic
- 🎯 **Focus** : Outline visible pour l'accessibilité
- 🎯 **Checked** : Dégradé de fond + checkmark visible

### 3. **Animations Fluides**
- 🔄 Transition cubic-bezier pour un mouvement naturel
- 🔄 Animation du checkmark avec effet "bounce"
- 🔄 Transformation scale subtile des champs de mot de passe lors du toggle
- 🔄 Animation d'entrée (slideIn) au chargement de la page

### 4. **Design Professionnel**
- 🎨 Couleurs cohérentes avec la charte graphique du site
- 🎨 Spacing et padding optimisés
- 🎨 Typographie claire (14px, font-weight 500)
- 🎨 Responsive (adapté mobile avec tailles réduites)

### 5. **Accessibilité**
- ♿ Support du clavier (Enter/Space pour toggle)
- ♿ Focus visible pour la navigation au clavier
- ♿ Labels sémantiques pour les lecteurs d'écran
- ♿ Contraste de couleurs suffisant (WCAG AA)

## 🚀 Utilisation

### Dans les Vues PHP
```php
<!-- Inclure le CSS -->
<link rel="stylesheet" href="<?= asset('styles/password-toggle-checkbox.css?v=' . time()) ?>">

<!-- HTML de la checkbox -->
<div class="show-password-container">
  <label class="show-password-label">
    <input type="checkbox" id="show-password-login" />
    <span>Afficher le mot de passe</span>
  </label>
</div>

<!-- Inclure le JS -->
<script src="<?= asset('js/password-toggle.js?v=' . time()) ?>"></script>
```

### Configuration JavaScript
Le script s'initialise automatiquement pour :
- **Login** : `#show-password-login` + `#login-password`
- **Register** : `#show-password-toggle` + `#password-input` + `#confirm-password-input`

Pour une utilisation personnalisée :
```javascript
window.PasswordToggle.init({
  toggleId: 'mon-toggle-id',
  passwordInputIds: ['password1', 'password2'] // ou 'password1' pour un seul
});
```

## 📱 Responsive

### Desktop (> 768px)
- Checkbox : 20x20px
- Font-size : 14px
- Padding : 8px 12px

### Mobile (< 480px)
- Checkbox : 18x18px
- Font-size : 13px
- Padding : 6px 10px

## 🎯 Amélioration par Rapport à l'Ancien Design

### Avant ❌
- Checkbox native du navigateur (non stylisée)
- Pas d'animation
- Design basique et peu moderne
- Positionnement pas optimal

### Après ✅
- Checkbox custom avec design professionnel
- Animations fluides et élégantes
- Icône checkmark visible
- Hover states et transitions
- Cohérence visuelle avec le reste du site
- Meilleure accessibilité

## 🔧 Maintenance

Pour modifier les couleurs, éditer dans `password-toggle-checkbox.css` :
```css
/* Couleur principale (checked state) */
background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);

/* Hover effect */
border-color: #a9b2ff;
```

## 📝 Notes Techniques

- Utilise `::before` et `::after` pour créer la checkbox custom
- Cache la checkbox native avec `opacity: 0`
- Support des sélecteurs modernes (`:has()` avec fallback)
- JavaScript vanilla (pas de dépendance)
- Compatible avec tous les navigateurs modernes
