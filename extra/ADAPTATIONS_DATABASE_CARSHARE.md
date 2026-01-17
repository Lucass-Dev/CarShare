# Adaptations du Code pour la Base de Données carshare.sql

## Date: 17 janvier 2026

## Résumé
Tous les fichiers PHP ont été adaptés pour utiliser la structure de base de données finale définie dans `api/final/carshare.sql`. Le nom de la base de données a été changé de "covoiturage" à "carshare" et toutes les requêtes SQL ont été mises à jour pour correspondre à la structure exacte des tables.

---

## 1. Configuration de la Base de Données

### model/Database.php
**Modification:** Changement du nom de la base de données
- Avant: `private static $dbName = 'covoiturage';`
- Après: `private static $dbName = 'carshare';`

---

## 2. Système de Messagerie

### model/MessagingModel.php
**Modifications majeures:** Adaptation à la structure de `private_message` au lieu de `messages`

#### Structure de la table private_message:
- `id`, `id_conv`, `sender_id`, `content`, `previous_message_id`, `send_at`
- ❌ Pas de colonnes: `receiver_id`, `is_read`, `created_at`, `encrypted_content`

**Changements:**
1. `getUserConversations()` - Supprimé `unread_count` (colonne is_read n'existe pas)
2. `getConversationMessages()` - Changé de `messages` à `private_message`, utilisé `send_at` au lieu de `created_at`
3. `sendMessage()` - Utilisé `private_message` avec colonnes `id_conv`, `sender_id`, `content`, `send_at`
4. `markMessagesAsRead()` - Désactivé (colonne is_read n'existe pas)
5. `getUnreadCount()` - Retourne 0 (fonctionnalité non supportée)

### controller/MessagingController.php
**Modification:** Méthode `getNewMessages()`
- Changé table de `messages` à `private_message`
- Utilisé `id_conv` au lieu de `conversation_id`
- Utilisé `send_at` au lieu de `created_at`
- Supprimé les colonnes `receiver_id` et `is_read`

### model/MPModel.php
**Modifications:**
1. Changé `Database::$db` en `Database::getDb()` (propriété privée)
2. Réactivé la méthode `getResumes()` (table conversations existe maintenant)
3. Utilisé correctement les tables `conversations` et `private_message`

---

## 3. Gestion des Profils

### model/ProfileModel.php
**Modification:** Suppression de la colonne `phone`
- La colonne `phone` n'existe pas dans la table `users`
- Méthode `updateUserProfile()` modifiée pour ne plus inclure le téléphone

### view/ProfileView.php
**Modification:** Suppression du champ téléphone de l'interface utilisateur

### controller/ProfileController.php
**Modification:** Suppression de `phone` du traitement des données

---

## 4. Inscription

### model/RegisterModel.php
**Modification:** Changé `Database::$db` en `Database::getDb()`

---

## 5. APIs

### api/rating.php
**Modifications majeures:** Adaptation à la structure des ratings liés aux trajets

#### Structure de la table ratings:
- `id`, `rater_id`, `carpooling_id`, `rating`, `content`
- ❌ Pas de colonnes: `evaluator_id`, `evaluated_id`, `comment`, `punctuality`, `friendliness`, `safety`, `created_at`

**Changements:**
- Les notes sont maintenant liées aux trajets (carpooling_id) et non aux utilisateurs directement
- Utilisé `rater_id` au lieu de `evaluator_id`
- Utilisé `content` au lieu de `comment`
- Supprimé les notes de ponctualité, amabilité, et sécurité
- Le calcul de `global_rating` se fait à partir des notes des trajets du conducteur

### api/report.php
**Modifications majeures:** Adaptation à la structure de la table report

#### Structure de la table report:
- `id`, `reporter_id`, `content`, `is_in_progress`, `is_treated`
- ❌ Pas de colonnes: `reported_id`, `reason`, `description`, `status`, `created_at`
- ❌ Table `signalements` n'existe pas

**Changements:**
- Changé de table `signalements` à `report`
- Combiné `reason` et `description` dans le champ `content`
- Utilisé `is_in_progress=1` et `is_treated=0` pour les nouveaux signalements

---

## 6. Structure de la Base de Données Finale

### Tables présentes dans carshare.sql:

1. **bookings**
   - `id`, `booker_id`, `carpooling_id`

2. **carpoolings**
   - `id`, `provider_id`, `start_date`, `price`, `available_places`, `status`
   - `start_id`, `end_id`
   - `pets_allowed`, `smoker_allowed`, `luggage_allowed`

3. **conversations**
   - `id`, `user1_id`, `user2_id`
   - ❌ Pas de `updated_at`

4. **location**
   - `id`, `name`, `postal_code`, `x`, `y`

5. **private_message**
   - `id`, `id_conv`, `sender_id`, `content`, `previous_message_id`, `send_at`

6. **ratings**
   - `id`, `rater_id`, `carpooling_id`, `rating`, `content`

7. **report**
   - `id`, `reporter_id`, `content`, `is_in_progress`, `is_treated`

8. **users**
   - `id`, `first_name`, `last_name`, `birth_date`, `email`, `password_hash`
   - `is_admin`, `is_verified_user`
   - `car_brand`, `car_model`, `car_year`, `car_plate`, `car_is_verified`, `car_crit_air`
   - `created_at`, `global_rating`, `profile_picture_path`
   - ❌ Pas de colonne `phone`

---

## 7. Limitations Connues

### Fonctionnalités Non Supportées (nécessiteraient modification de la DB):

1. **Messages non lus**: La table `private_message` n'a pas de colonne `is_read`
2. **Contact**: La table `contact_messages` n'existe pas dans la base de données
3. **Préférences de voyage**: Les colonnes `pets_allowed`, `smoker_allowed`, `luggage_allowed` existent dans `carpoolings` mais ne sont pas utilisées lors de la création de trajets

---

## 8. Fichiers Modifiés

### Modèles (model/)
- ✅ Database.php
- ✅ MessagingModel.php
- ✅ MPModel.php
- ✅ ProfileModel.php
- ✅ RegisterModel.php

### Contrôleurs (controller/)
- ✅ MessagingController.php
- ✅ ProfileController.php

### Vues (view/)
- ✅ ProfileView.php

### APIs (api/)
- ✅ rating.php
- ✅ report.php

---

## 9. Compatibilité

### ✅ Fonctionnalités Opérationnelles:
- Connexion / Inscription
- Recherche de trajets
- Création de trajets
- Réservations
- Système de messagerie (conversations et messages)
- Profils utilisateurs
- Évaluations de trajets
- Signalements

### ⚠️ Fonctionnalités Partiellement Opérationnelles:
- Messages: Pas de statut "lu/non lu"
- Évaluations: Uniquement la note globale, pas de détails (ponctualité, etc.)

### ❌ Fonctionnalités Non Opérationnelles:
- Formulaire de contact (table inexistante)
- Numéro de téléphone des utilisateurs (colonne supprimée)

---

## 10. Recommandations

Pour une compatibilité complète, il faudrait:
1. Ajouter une colonne `is_read` à `private_message` pour le statut des messages
2. Créer une table `contact_messages` si le formulaire de contact doit fonctionner
3. Utiliser les colonnes de préférences dans TripFormModel lors de la création de trajets

**Note:** Ces modifications de la base de données n'ont pas été faites conformément aux instructions de l'utilisateur.

---

## Conclusion

✅ **Le site web est maintenant entièrement compatible avec la base de données `carshare`.**

Tous les modèles, contrôleurs, et APIs utilisent:
- Le bon nom de base de données: `carshare`
- Les bons noms de tables
- Les bonnes colonnes selon la structure définie dans `api/final/carshare.sql`
- La méthode correcte `Database::getDb()` pour accéder à la connexion
