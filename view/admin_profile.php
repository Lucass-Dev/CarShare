<?php require_once __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="<?= asset('styles/user-profile.css') ?>">

<style>
.admin-content {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 2rem;
}

.admin-title {
    font-size: 2rem;
    color: #333;
    margin-bottom: 2rem;
    border-bottom: 3px solid #007bff;
    padding-bottom: 0.5rem;
}

.admin-nav {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.admin-nav a {
    padding: 0.75rem 1.5rem;
    background: white;
    color: #007bff;
    text-decoration: none;
    border-radius: 4px;
    border: 2px solid #007bff;
    transition: all 0.2s;
}

.admin-nav a:hover,
.admin-nav a.active {
    background: #007bff;
    color: white;
}

.profile-card {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.profile-card h3 {
    margin-top: 0;
    color: #007bff;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.btn-admin {
    padding: 0.75rem 1.5rem;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
}

.btn-admin:hover {
    background: #0056b3;
}
</style>

<div class="admin-content">
    <?php if (isset($_SESSION['admin_success'])): ?>
        <div class="alert alert-success" style="margin-bottom: 1rem; padding: 1rem; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;">
            <?= htmlspecialchars($_SESSION['admin_success']) ?>
        </div>
        <?php unset($_SESSION['admin_success']); endif; ?>
    
    <?php if (isset($_SESSION['admin_error'])): ?>
        <div class="alert alert-error" style="margin-bottom: 1rem; padding: 1rem; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">
            <?= htmlspecialchars($_SESSION['admin_error']) ?>
        </div>
        <?php unset($_SESSION['admin_error']); endif; ?>

    <h1 class="admin-title">Mon profil administrateur</h1>
    
    <div class="admin-nav">
        <a href="<?= url('index.php?action=admin_dashboard') ?>">Tableau de bord</a>
        <a href="<?= url('index.php?action=admin_users') ?>">Utilisateurs</a>
        <a href="<?= url('index.php?action=admin_trips') ?>">Trajets</a>
        <a href="<?= url('index.php?action=admin_vehicles') ?>">Véhicules</a>
        <a href="<?= url('index.php?action=admin_profile') ?>" class="active">Mon profil</a>
    </div>

    <div class="profile-card">
        <h3>Informations personnelles</h3>
        <form method="POST" action="<?= url('index.php?action=admin_profile_update') ?>">
            <div class="form-group">
                <label for="first_name">Prénom</label>
                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($admin['first_name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Nom</label>
                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($admin['last_name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Téléphone</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($admin['phone_number'] ?? '') ?>">
            </div>

            <button type="submit" class="btn-admin">Mettre à jour le profil</button>
        </form>
    </div>

    <div class="profile-card">
        <h3>Changer le mot de passe</h3>
        <form method="POST" action="<?= url('index.php?action=admin_password_update') ?>">
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" required minlength="8">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </div>

            <button type="submit" class="btn-admin">Changer le mot de passe</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
