<div style="max-width: 900px;">
    <div class="data-card" style="margin-bottom: 2rem;">
        <div class="data-card-header">
            <h2 class="data-card-title">
                <i class="fas fa-user-circle" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Informations personnelles
            </h2>
        </div>
        <div class="data-card-body">
            <form method="POST" action="<?= url('index.php?action=admin_profile_update') ?>">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Prénom <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" 
                               name="first_name" 
                               value="<?= htmlspecialchars($admin['first_name'] ?? '') ?>" 
                               required
                               class="admin-search-input"
                               style="width: 100%;">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Nom <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" 
                               name="last_name" 
                               value="<?= htmlspecialchars($admin['last_name'] ?? '') ?>" 
                               required
                               class="admin-search-input"
                               style="width: 100%;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Email <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="<?= htmlspecialchars($admin['email'] ?? '') ?>" 
                               required
                               class="admin-search-input"
                               style="width: 100%;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour le profil
                </button>
            </form>
        </div>
    </div>

    <div class="data-card">
        <div class="data-card-header">
            <h2 class="data-card-title">
                <i class="fas fa-lock" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Changer le mot de passe
            </h2>
        </div>
        <div class="data-card-body">
            <form method="POST" action="<?= url('index.php?action=admin_password_update') ?>" onsubmit="return validatePasswordForm(this)">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                        Mot de passe actuel <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           required
                           class="admin-search-input"
                           style="width: 100%;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Nouveau mot de passe <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               required
                               minlength="8"
                               class="admin-search-input"
                               style="width: 100%;">
                        <div style="font-size: 0.813rem; color: #6b7280; margin-top: 0.25rem;">
                            Minimum 8 caractères
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Confirmer le mot de passe <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="password" 
                               name="confirm_password" 
                               id="confirm_password"
                               required
                               minlength="8"
                               class="admin-search-input"
                               style="width: 100%;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Changer le mot de passe
                </button>
            </form>
        </div>
    </div>

    <div class="data-card" style="margin-top: 2rem;">
        <div class="data-card-header">
            <h2 class="data-card-title">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Informations du compte
            </h2>
        </div>
        <div class="data-card-body">
            <div style="display: grid; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9fafb; border-radius: 6px;">
                    <span style="font-weight: 600; color: #6b7280;">ID Administrateur:</span>
                    <span class="badge badge-info">#<?= $admin['id'] ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9fafb; border-radius: 6px;">
                    <span style="font-weight: 600; color: #6b7280;">Date de création:</span>
                    <span><?= date('d/m/Y à H:i', strtotime($admin['created_at'])) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9fafb; border-radius: 6px;">
                    <span style="font-weight: 600; color: #6b7280;">Type de compte:</span>
                    <span class="badge badge-success">
                        <i class="fas fa-shield-alt"></i> Administrateur
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validatePasswordForm(form) {
    const newPassword = form.new_password.value;
    const confirmPassword = form.confirm_password.value;
    
    if (newPassword !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas !');
        return false;
    }
    
    if (newPassword.length < 8) {
        alert('Le mot de passe doit contenir au moins 8 caractères !');
        return false;
    }
    
    return confirm('Confirmer le changement de mot de passe ?');
}
</script>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
