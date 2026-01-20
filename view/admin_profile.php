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

    <!-- Delete Account Card - DANGER ZONE -->
    <div class="profile-card" style="border: 2px solid #dc3545;">
        <h3 style="color: #dc3545; border-color: #dc3545;">⚠️ Zone de danger</h3>
        <form method="POST" action="<?= url('index.php?action=admin_delete_account') ?>" id="deleteAdminAccountForm">
            <input type="hidden" name="delete_account" value="1">
            
            <div class="danger-warning" style="background: #fff5f5; border: 2px solid #fecaca; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0; margin-top: 4px;">
                        <path d="M12 2L1 21h22L12 2zm0 6l1 8h-2l1-8zm0 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z" fill="#dc3545"/>
                    </svg>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 8px 0; color: #dc3545; font-size: 1.1rem; cursor: pointer;" id="showDeleteFieldsBtn">
                            Supprimer mon compte administrateur
                        </h4>
                        <p style="margin: 0; color: #721c24; line-height: 1.6;">
                            Cette action est <strong>définitive et irréversible</strong>. Toutes vos données personnelles seront supprimées de manière permanente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="delete-fields" style="display: none;">
                <div class="form-group">
                    <label for="confirm_password_delete">Votre mot de passe <span style="color: #dc3545;">*</span></label>
                    <input type="password" id="confirm_password_delete" name="confirm_password" class="form-input" placeholder="Confirmez votre mot de passe" required>
                </div>

                <div class="form-group">
                    <label for="confirm_text">Tapez "SUPPRIMER" pour confirmer <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="confirm_text" name="confirm_text" class="form-input" placeholder="SUPPRIMER" required style="text-transform: uppercase;">
                    <small style="color: #6c757d; font-size: 0.9rem; display: block; margin-top: 0.5rem;">
                        Tapez le mot SUPPRIMER en majuscules pour confirmer la suppression.
                    </small>
                </div>

                <div class="info-warning" style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 1rem; margin: 1.5rem 0;">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="flex-shrink: 0; margin-top: 2px;">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#856404"/>
                        </svg>
                        <p style="margin: 0; color: #856404; font-size: 0.95rem; line-height: 1.6;">
                            <strong>Rappel :</strong> Un email de confirmation vous sera envoyé après la suppression de votre compte administrateur.
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn-admin" style="background: #dc3545; flex: 1;" id="confirmDeleteAdminBtn">
                        Je comprends, supprimer mon compte
                    </button>
                    <button type="button" class="btn-admin" style="background: #6c757d; flex: 1;" id="cancelDeleteAdminBtn">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Alertes personnalisées Admin -->
<script src="<?= asset('js/admin-alerts.js') ?>"></script>

<script>
// Delete account confirmation pour admin
(function() {
    const deleteForm = document.getElementById('deleteAdminAccountForm');
    const deleteFields = deleteForm.querySelector('.delete-fields');
    const showBtn = document.getElementById('showDeleteFieldsBtn');
    const cancelBtn = document.getElementById('cancelDeleteAdminBtn');
    const confirmBtn = document.getElementById('confirmDeleteAdminBtn');
    
    // Afficher les champs de confirmation
    showBtn.addEventListener('click', function() {
        deleteFields.style.display = 'block';
        this.style.cursor = 'default';
    });
    
    // Annuler la suppression
    cancelBtn.addEventListener('click', function() {
        deleteFields.style.display = 'none';
        deleteForm.reset();
    });
    
    // Confirmation finale avant soumission
    confirmBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const password = deleteForm.querySelector('input[name="confirm_password"]').value;
        const confirmText = deleteForm.querySelector('input[name="confirm_text"]').value;
        
        if (!password) {
            adminAlert.error('Veuillez entrer votre mot de passe');
            return;
        }
        
        if (confirmText.toUpperCase() !== 'SUPPRIMER') {
            adminAlert.error('Veuillez taper "SUPPRIMER" pour confirmer');
            return;
        }
        
        adminAlert.confirm(
            'Êtes-vous absolument sûr de vouloir supprimer définitivement votre compte administrateur ? Cette action est irréversible !',
            () => deleteForm.submit()
        );
    });
})();
</script>

<?php require_once __DIR__ . '/components/footer.php'; ?>
