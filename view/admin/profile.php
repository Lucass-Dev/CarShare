<!-- NAVIGATION PAR ONGLETS -->
<div class="admin-tabs">
    <a href="<?= url('index.php?action=admin_dashboard') ?>" class="admin-tab">Tableau de bord</a>
    <a href="<?= url('index.php?action=admin_users') ?>" class="admin-tab">Utilisateurs</a>
    <a href="<?= url('index.php?action=admin_trips') ?>" class="admin-tab">Trajets</a>
    <a href="<?= url('index.php?action=admin_vehicles') ?>" class="admin-tab">Véhicules</a>
</div>

<!-- CONTENU -->
<div class="admin-content">
    <h2 style="margin-bottom: 1.5rem; color: #1f2937;">👤 Mon profil administrateur</h2>
    
    <!-- INFORMATIONS ACTUELLES -->
    <div class="stat-card" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 1rem; color: #374151;">📋 Informations actuelles</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div>
                <strong style="color: #6b7280;">ID:</strong> #<?= $admin['id'] ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Nom complet:</strong> <?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Email:</strong> <?= htmlspecialchars($admin['email']) ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Date d'inscription:</strong> <?= date('d/m/Y', strtotime($admin['created_at'])) ?>
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
        <!-- MODIFIER INFORMATIONS -->
        <div class="stat-card">
            <h3 style="margin-bottom: 1rem; color: #374151;">✏️ Modifier mes informations</h3>
            <form method="POST" action="<?= url('index.php?action=admin_profile_update') ?>">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">Prénom</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($admin['first_name']) ?>" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">Nom</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($admin['last_name']) ?>" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%;">💾 Enregistrer les modifications</button>
            </form>
        </div>
        
        <!-- MODIFIER MOT DE PASSE -->
        <div class="stat-card">
            <h3 style="margin-bottom: 1rem; color: #374151;">🔒 Modifier mon mot de passe</h3>
            <form method="POST" action="<?= url('index.php?action=admin_password_update') ?>">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">Mot de passe actuel</label>
                    <input type="password" name="current_password" required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">Nouveau mot de passe</label>
                    <input type="password" name="new_password" required minlength="8"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                    <small style="color: #6b7280; font-size: 0.875rem;">Minimum 8 caractères</small>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" required minlength="8"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%;">🔒 Changer le mot de passe</button>
            </form>
        </div>
    </div>
</div>
