# Navigation par Étapes - Formulaire de Publication

## Vue d'ensemble

Le formulaire de publication de trajet a été transformé en un système de navigation par étapes pour améliorer l'expérience utilisateur. Au lieu de scroller dans un long formulaire, l'utilisateur progresse page par page à travers 3 étapes claires.

## Structure des Étapes

### Étape 1 : Itinéraire
- **Champs départ** : N° voie, Rue, Ville (obligatoire)
- **Séparateur visuel** : Flèche animée
- **Champs arrivée** : N° voie, Rue, Ville (obligatoire)
- **Validation** : Les deux villes doivent être renseignées et différentes

### Étape 2 : Horaires & Prix
- **Date du trajet** : Obligatoire, ne peut pas être dans le passé
- **Heure de départ** : Optionnelle
- **Prix par passager** : Optionnel, maximum 250€
- **Validation** : La date doit être renseignée et future

### Étape 3 : Options
- **Nombre de places** : Obligatoire, de 1 à 10 places
- **Options de confort** (checkboxes indépendantes) :
  - Animaux acceptés
  - Fumeur accepté
  - Bagages volumineux possibles
- **Validation** : Au moins le nombre de places doit être renseigné
- **Note** : Les checkboxes peuvent être cochées indépendamment (0, 1, 2 ou 3)

## Fonctionnalités

### Navigation
- **Bouton "Suivant"** : Passe à l'étape suivante après validation
- **Bouton "Précédent"** : Retourne à l'étape précédente sans validation
- **Bouton "Publier"** : Visible uniquement à la dernière étape, soumet le formulaire

### Indicateur de Progression
- 3 steps visuels en haut du formulaire
- **Step actif** : Bleu avec numéro blanc
- **Step complété** : Vert avec checkmark
- **Step futur** : Gris
- **Cliquable** : On peut retourner en arrière en cliquant sur un step précédent

### Animations
- Transition fadeIn lors du changement d'étape
- Scroll automatique vers le haut du formulaire
- Hover effect sur les steps

### Validation
- Validation à chaque étape avant de continuer
- Messages d'alerte clairs en cas d'erreur
- Prévention de dates dans le passé
- Vérification des villes identiques

## Fichiers Modifiés

### 1. view/TripView.php
- Ajout des attributs `data-section="1|2|3"` sur chaque `.form-section`
- Ajout du `.section-divider` dans la section 1
- Modification des boutons : prev/next/submit avec visibilité conditionnelle
- Import du nouveau script `trip-form-steps.js`

### 2. assets/styles/trip-form-modern.css
- `.form-section` : `display: none` par défaut
- `.form-section.active` : `display: block` avec animation fadeIn
- `.step` : Style pour indicateur cliquable avec hover
- `.step.active` : Bleu avec shadow
- `.step.completed` : Vert pour steps validés
- Responsive : Steps en ligne sur desktop, adaptés sur mobile

### 3. assets/js/trip-form-steps.js (NOUVEAU)
- **initSteps()** : Initialise l'affichage à l'étape 1
- **updateDisplay()** : Gère l'affichage des sections et boutons
- **validateStep()** : Valide les champs obligatoires de chaque étape
- **Navigation** : Event listeners sur prev/next/submit
- **Steps cliquables** : Permet de retourner aux étapes précédentes
- **Autocomplétion** : Intégration avec l'API cities.php

## Expérience Utilisateur

### Avantages
✅ **Focus** : Une seule chose à faire à la fois
✅ **Moins de scroll** : Navigation fluide entre étapes
✅ **Validation progressive** : Erreurs détectées tôt
✅ **Progression visible** : L'utilisateur sait où il en est
✅ **Retour facile** : Possibilité de corriger facilement

### Comportement
- Le formulaire ne scroll plus verticalement
- Chaque étape occupe l'écran de manière optimale
- Transitions douces entre les étapes
- Validation immédiate avant de continuer
- Possibilité de revenir en arrière sans perdre les données

## Page "Covoiturage au Quotidien"

Tous les éléments non-formulaire (impact écologique, bonnes pratiques, arguments) ont été déplacés vers une nouvelle page dédiée accessible depuis le footer.

### Fichiers Créés
1. **view/DailyCarShareView.php** : Vue complète avec hero, bénéfices, écologie, pratiques
2. **assets/styles/daily-carshare.css** : Design moderne et responsive
3. **controller/DailyCarShareController.php** : Contrôleur pour la route
4. **Route** : `index.php?action=daily_carshare`

### Lien Footer
Le lien "Covoiturage au quotidien" dans le footer pointe maintenant vers cette page au lieu de la FAQ.

## Tests Recommandés

1. ✅ Navigation entre les 3 étapes
2. ✅ Validation des champs obligatoires
3. ✅ Retour en arrière en cliquant sur steps
4. ✅ Checkboxes indépendantes (0, 1, 2 ou 3)
5. ✅ Date dans le passé refusée
6. ✅ Villes identiques refusées
7. ✅ Autocomplétion des villes
8. ✅ Responsive mobile/tablette/desktop
9. ✅ Soumission du formulaire à l'étape 3

## Notes Techniques

- **JavaScript Vanilla** : Pas de dépendances externes
- **Progressive Enhancement** : Le formulaire fonctionne sans JS (un seul bloc)
- **Accessibilité** : Labels, aria-labels, navigation au clavier
- **Performance** : Animations CSS, pas de librairies lourdes
- **Compatibilité** : Chrome, Firefox, Safari, Edge modernes

## Maintenance

Pour ajouter une étape :
1. Créer une nouvelle `.form-section` avec `data-section="4"`
2. Ajouter un `.step` dans `.progress-steps`
3. Mettre à jour `totalSteps` dans le JavaScript
4. Ajouter la validation dans `validateStep()`

Pour modifier l'ordre des sections :
1. Changer les attributs `data-section` dans le HTML
2. Aucune modification JavaScript nécessaire
