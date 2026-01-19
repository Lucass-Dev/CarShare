# 🚗 Gestion des Places Disponibles - CarShare Fusion

## 📊 Structure de la Base de Données

### Table `carpoolings`

```sql
CREATE TABLE `carpoolings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` timestamp NOT NULL,              ← Date/heure du DÉPART du trajet
  `price` float DEFAULT NULL,
  `available_places` int(11) DEFAULT NULL,      ← Nombre de places RESTANTES
  `status` tinyint(1) DEFAULT '1',              ← 1 = actif, 0 = inactif
  `start_id` bigint(20) DEFAULT NULL,
  `end_id` bigint(20) DEFAULT NULL,
  `pets_allowed` tinyint(4) NOT NULL DEFAULT '0',
  `smoker_allowed` tinyint(4) NOT NULL DEFAULT '0',
  `luggage_allowed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**⚠️ IMPORTANT** : `start_date` = Date/heure du **DÉPART DU TRAJET** (pas la date de création)

---

## 🔍 Logique d'Affichage par Page

### 🌍 Page **Offres** (`/index.php?action=offers`)

**Objectif** : Afficher uniquement les trajets **réservables**

```php
WHERE c.start_date >= NOW()        ← Trajets FUTURS uniquement
AND c.available_places > 0         ← Au moins 1 place disponible
```

✅ **Visible** :
- Trajet futur avec places disponibles (status 0 ou 1)

❌ **Masqué** :
- Trajet passé (start_date < NOW)
- Trajet complet (available_places = 0)

**Badges affichés** :
- 🔴 **Inactif** si `status = 0`
- 🟡 **Complet** si `available_places = 0` (ne devrait pas arriver car filtré)
- 🟡 **Peu de places** si `available_places <= 2`
- 🟢 **Nombreuses places** si `available_places >= 5`

---

### 🚗 Page **Mes Trajets** (`/index.php?action=my_trips`)

**Objectif** : Le conducteur voit **TOUS** ses trajets (passés et futurs)

```php
WHERE c.provider_id = :userId      ← Trajets créés par l'utilisateur
// PAS de filtre sur start_date
// PAS de filtre sur available_places
```

✅ **Visible** :
- Tous les trajets créés (passés, futurs, complets, avec places)

**Sections** :
1. **Trajets à venir** : `start_date > NOW()`
2. **Trajets passés** : `start_date <= NOW()`

**Badges affichés** :
- 🟢 **Disponible** si `available_places > 0`
- 🟡 **Complet** si `available_places = 0`

---

### 📋 Page **Mes Réservations** (`/index.php?action=history` ou `my_bookings`)

**Objectif** : Le passager voit **TOUTES** ses réservations (passées et futures)

```php
WHERE b.booker_id = :userId        ← Réservations de l'utilisateur
// PAS de filtre sur start_date
```

✅ **Visible** :
- Toutes les réservations (passées et futures)

**Sections** :
1. **Trajets à venir** : `start_date > NOW()`
2. **Historique** : `start_date <= NOW()`

---

### 🔎 Page **Recherche** (`/index.php?action=display_search`)

**Objectif** : Rechercher des trajets avec critères spécifiques

```php
WHERE c.start_id = :start_id
AND c.end_id = :end_id
AND c.start_date >= :start_date    ← Dans la plage horaire recherchée
AND c.start_date <= :tolerance
AND c.available_places >= :seats   ← Assez de places pour la demande
AND c.available_places > 0         ← Au moins 1 place dispo
```

✅ **Visible** :
- Trajets correspondant aux critères avec places disponibles

---

## 🔄 Logique de Réservation

### Scénario : Trajet avec 5 places

#### Étape 1 : Création du trajet
```
Conducteur publie trajet pour le 25/01/2026 à 14h00
→ start_date = '2026-01-25 14:00:00'
→ available_places = 5
→ status = 1

✅ Visible dans "Offres" (futur + places dispo)
✅ Visible dans "Mes trajets" du conducteur
```

#### Étape 2 : Réservation de 2 places
```
Passager réserve 2 places
→ available_places = 5 - 2 = 3

✅ RESTE visible dans "Offres" (3 > 0)
✅ Visible dans "Mes réservations" du passager
✅ Visible dans "Mes trajets" du conducteur avec 3 places dispo
```

#### Étape 3 : Réservation de 3 places
```
Autre passager réserve 3 places
→ available_places = 3 - 3 = 0

❌ Disparaît de "Offres" (0 places)
✅ Toujours dans "Mes réservations" des 2 passagers
✅ Toujours dans "Mes trajets" du conducteur (badge "Complet")
```

#### Étape 4 : Date du trajet atteinte
```
Le 25/01/2026 à 14h00, le trajet démarre
→ start_date < NOW()

❌ Disparaît de "Offres" (date passée)
✅ Passe en "Historique" pour les passagers
✅ Passe en "Trajets passés" pour le conducteur
```

---

## 🎨 Indicateurs Visuels

### Page Offres

| Condition | Badge | Couleur |
|-----------|-------|---------|
| `available_places <= 2` | **Peu de places** | 🟡 Jaune |
| `available_places >= 5` | **Nombreuses places** | 🟢 Vert |
| `status = 0` | **Inactif** | 🔴 Rouge |

### Page Mes Trajets

| Condition | Badge | Couleur |
|-----------|-------|---------|
| `available_places > 0` | **Disponible** | 🟢 Vert |
| `available_places = 0` | **Complet** | 🟡 Jaune |

---

## ✅ Résumé des Règles

### 📍 **Page Offres**
- ✅ Futur (`start_date >= NOW()`)
- ✅ Places disponibles (`available_places > 0`)
- ✅ Tous status (0 et 1)

### 📍 **Mes Trajets (Conducteur)**
- ✅ Tous les trajets du conducteur
- ✅ Passés ET futurs
- ✅ Complets ET avec places

### 📍 **Mes Réservations (Passager)**
- ✅ Toutes les réservations du passager
- ✅ Passées ET futures
- ✅ Peu importe les places restantes

### 📍 **Recherche**
- ✅ Selon critères utilisateur
- ✅ Futur (dans plage horaire)
- ✅ Places suffisantes pour la demande

---

## 🔒 Sécurité et Transactions

### Réservation atomique
```php
try {
    $this->db->beginTransaction();
    
    // 1. Vérifier disponibilité
    SELECT available_places FROM carpoolings WHERE id = ?
    
    // 2. Créer réservation
    INSERT INTO bookings (booker_id, carpooling_id) VALUES (?, ?)
    
    // 3. Décrémenter places
    UPDATE carpoolings SET available_places = available_places - 1 WHERE id = ?
    
    $this->db->commit();
} catch (PDOException $e) {
    $this->db->rollBack();
}
```

---

## 📝 Fichiers Concernés

| Fichier | Filtres appliqués |
|---------|-------------------|
| `model/OffersModel.php` | `start_date >= NOW()` AND `available_places > 0` |
| `model/BookingModel.php` | `provider_id = :userId` (TOUS trajets) |
| `model/SearchPageModel.php` | `start_date` dans plage + `available_places >= :seats` |
| `view/OffersView.php` | Badges visuels (Inactif, Peu/Nombreuses places) |
| `view/MyTripsView.php` | Séparation À venir / Passés |
| `view/MyBookingsView.php` | Séparation À venir / Historique |

---

**Date mise à jour** : 18 janvier 2026  
**Version** : 2.0  
**Projet** : CarShare Fusion
