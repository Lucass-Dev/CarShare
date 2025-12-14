<?php
// app/views/signalement/signalement.php
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CarShare — Signaler un utilisateur</title>

    <link rel="stylesheet" href="/assets/styles/report-user.css">
    <link rel="stylesheet" href="/assets/styles/header.css">
    <link rel="stylesheet" href="/assets/styles/footer.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="/assets/img/photo_hextech.jpeg" alt="CarShare Logo">
        CarShare
    </div>

    <div class="header-icons">
        <a href="/search" title="Rechercher">🔍</a>
        <a href="/login" title="Mon compte">👤</a>
    </div>
</header>

<main class="report-main">
    <section class="report-container">
        <h1 class="report-title">Signaler un utilisateur</h1>

        <?php if (isset($_GET['success'])): ?>
            <p class="report-hint" style="color:green;">
                ✅ Merci, votre signalement a bien été transmis à l'équipe CarShare.
            </p>
        <?php endif; ?>

        <!-- Résumé du profil -->
        <div class="profile-card">
            <div class="profile-avatar" aria-hidden="true">
                <span><?= strtoupper($user['name'][0]) ?></span>
            </div>

            <div class="profile-info">
                <div class="profile-line">
                    <span class="label">Utilisateur :</span>
                    <span class="value"><?= htmlspecialchars($user['name']) ?></span>
                </div>
                <div class="profile-line">
                    <span class="label">Trajet concerné :</span>
                    <span class="value"><?= htmlspecialchars($user['trip']) ?></span>
                </div>
                <div class="profile-line">
                    <span class="label">Note moyenne :</span>
                    <span class="value"><?= htmlspecialchars($user['avg']) ?></span>
                </div>
                <div class="profile-line">
                    <span class="label">Nombre de trajets :</span>
                    <span class="value"><?= htmlspecialchars($user['count']) ?></span>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="/signalement/submit" class="report-form">
            <input type="hidden" name="user" value="<?= htmlspecialchars($user['name']) ?>">

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
</main>

<footer>
    <div class="footer-container">
        <div>HexTech ®</div>
        <div>CGU</div>
        <div>Informations légales</div>
        <div>Tous droits réservés</div>
    </div>
</footer>
</body>
</html>
