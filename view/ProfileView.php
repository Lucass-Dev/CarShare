<<<<<<< Updated upstream
<?php if (isset($error)): ?>
    <div style="color: red; text-align: center; margin: 20px;">
        <?= htmlspecialchars($error) ?>
=======
<link rel="stylesheet" href="./assets/styles/page_profil.css">
<main>
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
        
        <form method="POST" action="/CarShare/index.php?action=profile" class="profile-form">
            <div class="form-fields">
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" name="update_profile" class="btn btn-primary">Modifier</button>
                <a href="/CarShare/index.php?action=logout" class="btn btn-danger">Déconnexion</a>
            </div>
        </form>
>>>>>>> Stashed changes
    </div>

    <!-- Véhicule -->
    <div class="card">
        <h2>Mon véhicule</h2>

        <form method="POST" action="/CarShare/index.php?action=profile" class="profile-form">
            <div class="form-fields">
                <div class="form-group">
                    <label>Marque</label>
                    <input type="text" name="car_brand" value="<?= htmlspecialchars($user['car_brand'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Modèle</label>
                    <input type="text" name="car_model" value="<?= htmlspecialchars($user['car_model'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Plaque d'immatriculation</label>
                    <input type="text" name="car_plate" value="<?= htmlspecialchars($user['car_plate'] ?? '') ?>">
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" name="update_vehicle" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</main>