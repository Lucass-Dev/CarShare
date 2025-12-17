<?php
// Vue simple d'information utilisateur
?>
<div class="card">
    <h2>Profil utilisateur</h2>
    <div style="display:flex; align-items:center; gap:16px; margin-bottom:16px;">
        <img src="/assets/img/avatar.jpg" alt="Photo de profil" style="width:64px; height:64px; border-radius:50%; object-fit:cover;">
        <div>
            <div><strong><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></strong></div>
            <div>Note globale: <?= htmlspecialchars($user['global_rating'] ?? 'N/A') ?> ⭐</div>
            <div>Email: <?= htmlspecialchars($user['email'] ?? '') ?></div>
        </div>
    </div>

    <h3>Véhicule</h3>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
        <div>Marque: <strong><?= htmlspecialchars($user['car_brand'] ?? '—') ?></strong></div>
        <div>Modèle: <strong><?= htmlspecialchars($user['car_model'] ?? '—') ?></strong></div>
        <div>Plaque: <strong><?= htmlspecialchars($user['car_plate'] ?? '—') ?></strong></div>
        <div>Vérifié: <strong><?= isset($user['car_is_verified']) && (int)$user['car_is_verified'] === 1 ? 'Oui' : 'Non' ?></strong></div>
    </div>

    <div style="margin-top:20px;">
        <a class="btn" href="index.php?controller=trip&action=search">Retour aux trajets</a>
    </div>
</div>
