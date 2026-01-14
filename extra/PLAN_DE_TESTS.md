# 🧪 Plan de Tests - Validation des Améliorations

## 🎯 Objectif
Valider que toutes les améliorations fonctionnent correctement avant mise en production.

---

## 🔒 TESTS DE SÉCURITÉ

### Test 1 : SQL Injection
**URL** : `http://localhost/CarShare/index.php?action=create_trip`

#### Étapes :
1. Dans le champ "Ville de départ", entrer : `Paris' OR 1=1--`
2. Cliquer en dehors du champ

**✅ Résultat attendu** :
- Champ devient rouge
- Notification apparaît : "La ville de départ contient des caractères interdits ou dangereux"
- Pas de soumission possible

---

### Test 2 : XSS (Cross-Site Scripting)
#### Étapes :
1. Dans le champ "Rue", entrer : `<script>alert('XSS')</script>`
2. Soumettre le formulaire

**✅ Résultat attendu** :
- Notification d'erreur apparaît
- Message : "La rue de départ contient des caractères interdits ou dangereux"
- Aucun script exécuté

---

### Test 3 : Valeurs négatives (Prix)
#### Étapes :
1. Remplir le formulaire normalement
2. Dans "Prix", entrer : `-100`
3. Essayer de soumettre

**✅ Résultat attendu** :
- Champ prix devient rouge
- Notification : "Le prix ne peut pas être négatif"
- Soumission bloquée

---

### Test 4 : Valeurs négatives (Places)
#### Étapes :
1. Dans le select "Nombre de places", sélectionner manuellement une valeur négative (via console)
   ```javascript
   document.getElementById('places').value = '-100'
   ```
2. Soumettre

**✅ Résultat attendu** :
- Validation bloque avec : "Le nombre de places doit être entre 1 et 10"

---

### Test 5 : Encodage hexadécimal
#### Étapes :
1. Dans "Ville", entrer : `\x50\x61\x72\x69\x73`
2. Tabuler vers le champ suivant

**✅ Résultat attendu** :
- Détection immédiate
- Champ rouge avec message d'erreur

---

### Test 6 : JavaScript injection
#### Étapes :
1. Dans "Rue", entrer : `javascript:alert(document.cookie)`
2. Soumettre

**✅ Résultat attendu** :
- Bloqué avec message de sécurité
- Aucun code exécuté

---

## 🎨 TESTS D'INTERFACE

### Test 7 : Notifications visuelles
#### Étapes :
1. Soumettre le formulaire avec 3 erreurs :
   - Ville vide
   - Prix négatif
   - Date passée
2. Observer les notifications

**✅ Résultat attendu** :
- Notification toast apparaît en haut à droite
- Animation slide-in fluide
- Liste des 3 erreurs visible
- Bouton X pour fermer
- Auto-fermeture après 8 secondes

---

### Test 8 : États de validation
#### Étapes :
1. Entrer une ville valide → Observer
2. Entrer un prix valide → Observer
3. Vider la ville → Observer

**✅ Résultat attendu** :
- Champ valide : Bordure verte
- Champ invalide : Bordure rouge + fond rosé + message inline
- Transitions fluides entre états

---

### Test 9 : Responsive mobile
#### Étapes :
1. Ouvrir DevTools (F12)
2. Mode responsive (Ctrl+Shift+M)
3. Tester à 375px (iPhone)
4. Tester à 768px (iPad)

**✅ Résultat attendu** :
- 375px : Layout vertical, boutons pleine largeur
- 768px : Layout adaptatif, navigation empilée
- Pas de scroll horizontal
- Tout reste accessible

---

### Test 10 : Accessibilité clavier
#### Étapes :
1. Charger la page
2. Naviguer uniquement avec Tab
3. Valider avec Enter

**✅ Résultat attendu** :
- Focus visible sur chaque élément
- Ordre logique de tabulation
- Soumission possible au clavier

---

## 🗺️ TESTS DE NAVIGATION

### Test 11 : Séparation Trajets/Historique
#### Étapes :
1. Aller sur : `?action=my_trips`
2. Observer le contenu
3. Cliquer sur "Historique passager"
4. Observer le changement

**✅ Résultat attendu** :
- `my_trips` : Seulement trajets créés (conducteur)
- `history` : Seulement réservations (passager)
- Navigation claire avec tabs actives
- Badge "Actif" vs "Terminé" selon statut

---

### Test 12 : Empty states
#### Étapes :
1. Se connecter avec un compte sans trajets
2. Aller sur "Mes trajets proposés"
3. Observer l'empty state

**✅ Résultat attendu** :
- Message : "Aucun trajet à venir"
- Icône SVG visible
- Bouton "Créer mon premier trajet"
- Design encourageant, pas d'erreur

---

### Test 13 : Actions sur cartes
#### Étapes :
1. Créer un trajet
2. Aller dans "Mes trajets proposés"
3. Tester les boutons "Détails" et "Modifier"

**✅ Résultat attendu** :
- Détails : Redirige vers page détails
- Modifier : Alerte "Fonctionnalité en développement" (pour l'instant)
- Hover effects visibles
- Transitions fluides

---

## ✅ TESTS FONCTIONNELS

### Test 14 : Soumission valide complète
#### Étapes :
1. Remplir tous les champs correctement :
   - Départ : Paris
   - Arrivée : Lyon
   - Date : Demain
   - Places : 3
   - Prix : 25.50
2. Soumettre

**✅ Résultat attendu** :
- Notification "Vérification en cours..."
- Redirection vers confirmation
- Message de succès élégant
- Trajet visible dans "Mes trajets proposés"

---

### Test 15 : Validation en temps réel
#### Étapes :
1. Commencer à taper une ville
2. Observer le feedback visuel
3. Effacer le contenu
4. Observer le changement

**✅ Résultat attendu** :
- Feedback immédiat (< 100ms)
- Pas de lag ou freeze
- Bordures changent en temps réel
- Messages d'erreur inline apparaissent

---

### Test 16 : Villes identiques
#### Étapes :
1. Entrer "Paris" en départ
2. Entrer "Paris" en arrivée
3. Soumettre

**✅ Résultat attendu** :
- Notification : "Les villes de départ et d'arrivée doivent être différentes"
- Les deux champs en rouge
- Soumission bloquée

---

### Test 17 : Date dans le passé
#### Étapes :
1. Sélectionner date d'hier
2. Tabuler vers le champ suivant

**✅ Résultat attendu** :
- Champ date devient rouge
- Message : "La date doit être aujourd'hui ou dans le futur"

---

### Test 18 : Date trop loin
#### Étapes :
1. Sélectionner date dans 2 ans
2. Soumettre

**✅ Résultat attendu** :
- Notification : "La date ne peut pas dépasser un an dans le futur"

---

## 🔄 TESTS DE RÉGRESSION

### Test 19 : Ancien formulaire ne fonctionne plus
#### Étapes :
1. Vérifier que `create-trip.js` n'est plus chargé
2. Vérifier que `create-trip.css` n'est plus chargé
3. Confirmer utilisation de `-enhanced` versions

**✅ Résultat attendu** :
- Seuls les nouveaux fichiers sont utilisés
- Pas de conflit entre ancien/nouveau code

---

### Test 20 : Compatibilité navigateurs
#### Tester sur :
- ✅ Chrome (dernière version)
- ✅ Firefox (dernière version)
- ✅ Edge (dernière version)
- ✅ Safari (si disponible)

**✅ Résultat attendu** :
- Même comportement sur tous
- Pas d'erreur console
- Design identique

---

## 📊 CHECKLIST FINALE

### Avant mise en production

#### Sécurité
- [ ] Test SQL injection OK
- [ ] Test XSS OK
- [ ] Valeurs négatives bloquées
- [ ] Encodages malveillants détectés
- [ ] JavaScript injection bloqué

#### Interface
- [ ] Notifications toast fonctionnent
- [ ] États de validation corrects
- [ ] Responsive mobile OK
- [ ] Accessibilité clavier OK
- [ ] Animations fluides

#### Navigation
- [ ] Séparation Trajets/Historique claire
- [ ] Empty states affichés
- [ ] Actions sur cartes fonctionnent
- [ ] Tabs navigation fluide

#### Fonctionnel
- [ ] Soumission valide réussit
- [ ] Validation temps réel OK
- [ ] Villes identiques détectées
- [ ] Dates validées correctement

#### Performance
- [ ] Pas de lag interface
- [ ] Fichiers chargent < 2s
- [ ] Pas d'erreur console
- [ ] Compatibilité navigateurs OK

---

## 🐛 BUGS CONNUS / LIMITATIONS

### À surveiller
1. **Modification de trajet** : Fonctionnalité pas encore implémentée
2. **Cache navigateur** : Peut nécessiter Ctrl+F5 après mise à jour
3. **Autocomplete villes** : Dépend de l'API cities.php existante

### Non bloquant
- Édition de trajet : alert() temporaire
- Stats et graphiques : À implémenter plus tard

---

## 📝 RAPPORT DE TEST

### Template à remplir

```
Date du test : __________
Testeur : __________
Navigateur : __________
OS : __________

Tests Sécurité (6) : __ / 6
Tests Interface (4) : __ / 4  
Tests Navigation (3) : __ / 3
Tests Fonctionnels (5) : __ / 5
Tests Régression (2) : __ / 2

TOTAL : __ / 20

Bugs trouvés : 
- [ ] Bug 1 : _______________
- [ ] Bug 2 : _______________

Validation finale : [ ] OUI  [ ] NON
```

---

## 🎉 VALIDATION

**Si tous les tests passent** :
✅ Le projet est prêt pour la production !

**Si des tests échouent** :
⚠️ Consulter la documentation dans `extra/AMELIORATIONS_JANVIER_2026.md`
⚠️ Vérifier les chemins de fichiers
⚠️ Vider le cache navigateur
⚠️ Vérifier logs PHP (/xampp/logs/)
