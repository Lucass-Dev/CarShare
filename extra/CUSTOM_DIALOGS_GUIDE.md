# Guide des Dialogues Personnalisés

## Vue d'ensemble
Tous les dialogues JavaScript natifs (`alert()`, `confirm()`, `prompt()`) ont été remplacés par un système de dialogues personnalisés et élégants qui s'intègrent parfaitement avec le design du site.

## Fichiers du système

### CSS
- **custom-dialogs.css** : Styles modernes avec animations, responsive design et dark mode

### JavaScript
- **custom-dialogs.js** : Système complet de dialogues modaux avec API Promise

## API Disponibles

### 1. customConfirm() - Confirmation
```javascript
// Utilisation basique
const confirmed = await customConfirm("Êtes-vous sûr de vouloir supprimer ce trajet ?");
if (confirmed) {
    // L'utilisateur a confirmé
}

// Avec options
const result = await customConfirm(
    "Cette action est irréversible",
    {
        title: "Attention",
        confirmText: "Oui, supprimer",
        cancelText: "Annuler",
        confirmClass: "btn-danger",
        icon: "⚠️"
    }
);
```

### 2. customAlert() - Alerte
```javascript
// Alerte simple
await customAlert("Votre trajet a été publié avec succès !");

// Avec options
await customAlert(
    "Connexion réussie",
    {
        title: "Bienvenue",
        confirmText: "OK",
        icon: "✓"
    }
);
```

### 3. customPrompt() - Saisie utilisateur
```javascript
// Demander une entrée
const username = await customPrompt("Quel est votre pseudo ?");
if (username !== null) {
    console.log("Pseudo:", username);
}

// Avec valeur par défaut et options
const city = await customPrompt(
    "Quelle ville cherchez-vous ?",
    {
        title: "Recherche",
        placeholder: "Paris, Lyon...",
        defaultValue: "Paris",
        confirmText: "Rechercher",
        cancelText: "Annuler"
    }
);
```

### 4. Fonctions Helper

#### showSuccess()
```javascript
await showSuccess("Votre réservation a été confirmée !");
```

#### showError()
```javascript
await showError("Impossible de se connecter. Vérifiez vos identifiants.");
```

#### showWarning()
```javascript
await showWarning("Vous allez être déconnecté dans 5 minutes.");
```

#### showInfo()
```javascript
await showInfo("N'oubliez pas de vérifier votre profil.");
```

#### confirmDelete()
```javascript
const deleteConfirmed = await confirmDelete("ce trajet");
if (deleteConfirmed) {
    // Supprimer l'élément
}
```

## Utilisation avec attributs data-confirm

Pour les liens et boutons, utilisez l'attribut `data-confirm` :

```html
<!-- Lien avec confirmation -->
<a href="delete.php?id=123" 
   data-confirm="Êtes-vous sûr de vouloir supprimer ?"
   data-confirm-title="Confirmation"
   data-confirm-text="Oui, supprimer"
   data-cancel-text="Annuler"
   data-danger="true"
   class="btn btn--danger">
    Supprimer
</a>

<!-- Bouton de formulaire avec confirmation -->
<button type="submit"
        data-confirm="Envoyer ce message ?"
        class="btn btn--primary">
    Envoyer
</button>
```

## Avantages

1. **Design cohérent** : Tous les dialogues utilisent les couleurs et le style du site
2. **Animations fluides** : Apparition et disparition en douceur
3. **Responsive** : S'adapte automatiquement aux mobiles
4. **Accessibilité** : Support du clavier (ESC pour fermer)
5. **Dark mode** : Support automatique du mode sombre
6. **Promise-based** : Utilisation avec async/await pour un code moderne
7. **Personnalisable** : Options pour chaque dialogue (titre, texte, icônes, couleurs)
8. **Protection XSS** : Le contenu est automatiquement échappé

## Exemples réels dans le code

### MyTripsView.php - Suppression de trajet
```javascript
async function confirmDeleteTrip(tripId) {
    const confirmed = await customConfirm(
        "Êtes-vous sûr de vouloir supprimer ce trajet ? Cette action est irréversible.",
        {
            title: "Supprimer le trajet",
            confirmText: "Oui, supprimer",
            cancelText: "Annuler",
            confirmClass: "btn-danger",
            icon: "⚠️"
        }
    );
    
    if (confirmed) {
        window.location.href = `index.php?page=delete_trip&trip_id=${tripId}`;
    }
}
```

### signalement.html - Confirmation d'envoi
```javascript
document.getElementById('report-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    await showSuccess("Merci, votre signalement a bien été transmis à l'équipe CarShare.");
    this.reset();
});
```

### global-enhancements.js - Gestion automatique des confirmations
```javascript
function initConfirmDialogs() {
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', async function(e) {
            e.preventDefault();
            const message = this.getAttribute('data-confirm');
            const confirmed = await customConfirm(message);
            if (confirmed) {
                // Action confirmée
            }
        });
    });
}
```

## Migration depuis les dialogues natifs

### Avant (natif)
```javascript
if (confirm("Supprimer ?")) {
    deleteItem();
}
```

### Après (personnalisé)
```javascript
if (await customConfirm("Supprimer ?")) {
    deleteItem();
}
```

**Note importante** : Les fonctions personnalisées sont asynchrones, n'oubliez pas d'utiliser `await` et de déclarer la fonction parent comme `async`.

## Fichiers modifiés

### Fichiers créés
1. `/assets/styles/custom-dialogs.css` - Styles des dialogues
2. `/assets/js/custom-dialogs.js` - Système de dialogues
3. `/extra/CUSTOM_DIALOGS_GUIDE.md` - Ce guide

### Fichiers mis à jour
1. `/index.php` - Ajout des CSS/JS dans le head
2. `/view/MyTripsView.php` - Utilisation de customConfirm() pour la suppression
3. `/statique/signalement.html` - Utilisation de showSuccess()
4. `/extra/create-trip.js` - Utilisation de showError() pour la validation
5. `/assets/js/global-enhancements.js` - Migration vers customConfirm()

## Support navigateurs

- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Mobile browsers (iOS Safari 14+, Chrome Android)

## Notes techniques

- Les dialogues utilisent des Promises natives pour une intégration async/await
- Protection contre les injections XSS avec `escapeHtml()`
- Support du clavier : ESC pour fermer, Enter pour confirmer
- Clic sur le fond (backdrop) pour fermer
- Un seul dialogue affiché à la fois (auto-fermeture du précédent)
- Animations CSS avec `@keyframes` pour de meilleures performances

## Dépannage

**Le dialogue n'apparaît pas ?**
- Vérifiez que `custom-dialogs.js` est chargé avant les autres scripts
- Vérifiez que `custom-dialogs.css` est inclus dans la page

**Le style ne correspond pas ?**
- Vérifiez que les CSS sont chargés dans le bon ordre
- Videz le cache du navigateur

**Les Promises ne fonctionnent pas ?**
- Utilisez `async/await` ou `.then()`
- Vérifiez la compatibilité du navigateur

## Exemples de cas d'usage

### Formulaire de contact
```javascript
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const confirmed = await customConfirm(
        "Envoyer ce message ?",
        { icon: "📧" }
    );
    
    if (confirmed) {
        await sendMessage();
        await showSuccess("Message envoyé !");
    }
});
```

### Déconnexion
```javascript
async function logout() {
    const confirmed = await customConfirm(
        "Voulez-vous vraiment vous déconnecter ?",
        {
            title: "Déconnexion",
            confirmText: "Se déconnecter",
            icon: "🔒"
        }
    );
    
    if (confirmed) {
        window.location.href = 'logout.php';
    }
}
```

### Validation de formulaire
```javascript
async function validateForm() {
    const errors = checkErrors();
    
    if (errors.length > 0) {
        await showError(
            `Veuillez corriger les erreurs suivantes :\n\n${errors.join('\n')}`
        );
        return false;
    }
    
    return true;
}
```

---

**Date de création** : Janvier 2026
**Auteur** : CarShare Team
**Version** : 1.0
