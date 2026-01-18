<?php
if (!isset($userData) || !is_array($userData) || empty($userData['id'])) {
    header('Location: ' . url('index.php?action=signalement'));
    exit;
}
?>
<section class="report-main">
    <section class="report-container">
        <h1 class="report-title">Signaler un utilisateur</h1>

        <?php if (isset($_GET['success'])): ?>
            <p class="report-hint" style="color:#155724; background:#d4edda; padding:15px; border-radius:8px; margin:15px 0;">
                ✅ Signalement reçu ! Merci, votre signalement a bien été transmis à l'équipe CarShare.
            </p>
        <?php elseif (isset($_GET['error'])): ?>
            <?php 
            $errorMsg = 'Une erreur est survenue';
            if ($_GET['error'] === 'user_not_found') $errorMsg = 'Utilisateur non trouvé';
            elseif ($_GET['error'] === 'carpooling_not_found') $errorMsg = 'Trajet non trouvé';
            elseif ($_GET['error'] === 'save_failed') $errorMsg = 'Erreur lors de l\'enregistrement';
            elseif ($_GET['error'] === 'empty_description') $errorMsg = 'La description est obligatoire';
            elseif ($_GET['error'] === 'empty_reason') $errorMsg = 'Le motif est obligatoire';
            elseif ($_GET['error'] === 'self_reporting') $errorMsg = 'Vous ne pouvez pas vous signaler vous-même';
            ?>
            <p style="margin:12px 0; padding:15px; border-radius:8px; background:#f8d7da; color:#721c24;">
                ❌ <?= htmlspecialchars($errorMsg) ?>
            </p>
        <?php endif; ?>

        <!-- Résumé du profil -->
        <div class="profile-card">
            <div class="profile-avatar" aria-hidden="true">
                <span><?= strtoupper(substr($userData['name'], 0, 1)) ?></span>
            </div>

            <div class="profile-info">
                <div class="profile-line">
                    <span class="label">Utilisateur :</span>
                    <span class="value"><?= htmlspecialchars($userData['name']) ?></span>
                </div>
                <div class="profile-line">
                    <span class="label">Note moyenne :</span>
                    <span class="value"><?= htmlspecialchars($userData['avg']) ?></span>
                </div>
                <div class="profile-line">
                    <span class="label">Avis reçus :</span>
                    <span class="value"><?= htmlspecialchars($userData['reviews']) ?></span>
                </div>
                <div class="profile-line">
                    <span class="label">Nombre de trajets :</span>
                    <span class="value"><?= htmlspecialchars($userData['count']) ?></span>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="<?= url('index.php?action=signalement_submit') ?>" class="report-form">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($userData['id']) ?>">
            <?php if (!empty($userData['carpooling_id'])): ?>
            <input type="hidden" name="carpooling_id" value="<?= htmlspecialchars($userData['carpooling_id']) ?>">
            <?php endif; ?>

            <div class="form-row">
                <label for="reason" class="form-label">Motif du signalement</label>
                <select id="reason" name="reason" class="form-select" required>
                    <option value="">Sélectionnez un motif</option>
                    <option value="behavior">Comportement inapproprié</option>
                    <option value="security">Problème de sécurité</option>
                    <option value="payment">Problème de paiement</option>
                    <option value="vehicle">Problème avec le véhicule</option>
                    <option value="other">Autre</option>
                </select>
            </div>

            <div class="form-row">
                <label for="description" class="form-label">
                    Décrivez le problème <span class="required">*</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    class="form-textarea"
                    required
                    placeholder="Expliquez ce qu'il s'est passé de manière précise et factuelle."
                ></textarea>
            </div>

            <p class="report-hint">
                ⚠ Les signalements abusifs peuvent entraîner des sanctions sur votre compte.
            </p>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    Envoyer le signalement
                </button>
            </div>
        </form>
    </section>

<script src="<?= asset('js/signalement-form.js') ?>"></script>
