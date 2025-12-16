<?php if (isset($error)): ?>
    <div style="color: red; text-align: center; margin: 20px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div style="color: green; text-align: center; margin: 20px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<!-- Profil -->
<div class="card">
    <h2>Résumé du profil</h2>
    
    <form method="POST" action="/CarShare/index.php?action=profile">
        <label>Prénom</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>

        <label>Nom</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>

        <label>Mail</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <button type="submit" name="update_profile" class="btn">Modifier</button>
    </form>
    
    <div style="margin-top: 20px;">
        <a href="/CarShare/index.php?action=logout" class="btn" style="background-color: #ff4444;">Déconnexion</a>
    </div>
</div>

<!-- Véhicule -->
<div class="card">
    <h2>Mon véhicule</h2>

    <form method="POST" action="/CarShare/index.php?action=profile">
        <label>Marque</label>
        <input type="text" name="car_brand" value="<?= htmlspecialchars($user['car_brand'] ?? '') ?>">

        <label>Modèle</label>
        <input type="text" name="car_model" value="<?= htmlspecialchars($user['car_model'] ?? '') ?>">

        <label>Plaque d'immatriculation</label>
        <input type="text" name="car_plate" value="<?= htmlspecialchars($user['car_plate'] ?? '') ?>">

        <button type="submit" name="update_vehicle" class="btn">Modifier</button>
    </form>
</div>