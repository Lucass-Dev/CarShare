<!-- NAVIGATION PAR ONGLETS -->
<div class="admin-tabs">
    <a href="<?= url('index.php?action=admin_dashboard') ?>" class="admin-tab">Tableau de bord</a>
    <a href="<?= url('index.php?action=admin_users') ?>" class="admin-tab active">Utilisateurs</a>
    <a href="<?= url('index.php?action=admin_trips') ?>" class="admin-tab">Trajets</a>
    <a href="<?= url('index.php?action=admin_vehicles') ?>" class="admin-tab">Véhicules</a>
</div>

<!-- CONTENU -->
<div class="admin-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: #1f2937;">👤 Profil de <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
        <a href="<?= url('index.php?action=admin_users') ?>" class="btn-secondary">← Retour</a>
    </div>
    
    <!-- INFORMATIONS UTILISATEUR -->
    <div class="stat-card" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 1rem; color: #374151;">📋 Informations personnelles</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div>
                <strong style="color: #6b7280;">ID:</strong> #<?= $user['id'] ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Nom complet:</strong> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Email:</strong> <?= htmlspecialchars($user['email']) ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Date d'inscription:</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?>
            </div>
            <div>
                <strong style="color: #6b7280;">Statut:</strong> 
                <?php if ($user['is_verified_user']): ?>
                    <span class="badge badge-success">✓ Vérifié</span>
                <?php else: ?>
                    <span class="badge badge-warning">⚠ Non vérifié</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- STATISTIQUES UTILISATEUR -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['trips_provided'] ?></div>
            <div class="stat-label">🚗 Trajets proposés</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['trips_booked'] ?></div>
            <div class="stat-label">📅 Trajets réservés</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['total_revenue'], 2) ?>€</div>
            <div class="stat-label">💰 Chiffre d'affaires</div>
        </div>
    </div>
    
    <!-- HISTORIQUE -->
    <h3 style="margin-bottom: 1rem; color: #374151;">📜 Historique</h3>
    
    <?php if (empty($history['provided']) && empty($history['booked'])): ?>
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Aucun historique</p>
    <?php else: ?>
        <!-- TRAJETS PROPOSÉS -->
        <?php if (!empty($history['provided'])): ?>
            <h4 style="margin: 1rem 0; color: #6b7280;">🚗 Trajets proposés (<?= count($history['provided']) ?>)</h4>
            <table class="admin-table" style="margin-bottom: 2rem;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Départ → Arrivée</th>
                        <th>Date départ</th>
                        <th>Prix</th>
                        <th>Places</th>
                        <th>Réservations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history['provided'] as $trip): ?>
                        <tr>
                            <td>#<?= $trip['id'] ?></td>
                            <td><?= htmlspecialchars($trip['start_location'] . ' → ' . $trip['end_location']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($trip['departure_date'])) ?></td>
                            <td><?= number_format($trip['price'], 2) ?>€</td>
                            <td><?= $trip['available_seats'] ?></td>
                            <td><span class="badge badge-info"><?= $trip['booking_count'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <!-- TRAJETS RÉSERVÉS -->
        <?php if (!empty($history['booked'])): ?>
            <h4 style="margin: 1rem 0; color: #6b7280;">📅 Trajets réservés (<?= count($history['booked']) ?>)</h4>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Départ → Arrivée</th>
                        <th>Date départ</th>
                        <th>Prix</th>
                        <th>Conducteur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history['booked'] as $trip): ?>
                        <tr>
                            <td>#<?= $trip['id'] ?></td>
                            <td><?= htmlspecialchars($trip['start_location'] . ' → ' . $trip['end_location']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($trip['departure_date'])) ?></td>
                            <td><?= number_format($trip['price'], 2) ?>€</td>
                            <td><?= htmlspecialchars($trip['provider_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- ACTIONS -->
    <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
        <form method="POST" action="<?= url('index.php?action=admin_toggle_verification') ?>">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="return_url" value="<?= url('index.php?action=admin_user_details&id=' . $user['id']) ?>">
            <?php if ($user['is_verified_user']): ?>
                <button type="submit" class="btn-secondary" onclick="return confirm('Marquer cet utilisateur comme non vérifié ?')">Marquer non vérifié</button>
            <?php else: ?>
                <button type="submit" class="btn-primary" onclick="return confirm('Marquer cet utilisateur comme vérifié ?')">Marquer vérifié</button>
            <?php endif; ?>
        </form>
        
        <button type="button" class="btn-primary" onclick="document.getElementById('resetPasswordModal').style.display='block'">Réinitialiser mot de passe</button>
        
        <form method="POST" action="<?= url('index.php?action=admin_delete_user') ?>">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <button type="submit" class="btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">Supprimer l\'utilisateur</button>
        </form>
    </div>
</div>

<!-- MODAL RÉINITIALISATION MOT DE PASSE -->
<div id="resetPasswordModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 16px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 1.5rem; color: #1f2937;">Réinitialiser le mot de passe</h3>
        <form method="POST" action="<?= url('index.php?action=admin_reset_user_password') ?>">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            
            <div class="admin-form-group">
                <label class="admin-form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="admin-form-input" required minlength="8">
                <small class="admin-form-hint">Minimum 8 caractères</small>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary">Réinitialiser</button>
                <button type="button" class="btn-secondary" onclick="document.getElementById('resetPasswordModal').style.display='none'">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
// Afficher le modal avec flexbox
document.getElementById('resetPasswordModal').onclick = function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
};

// Afficher le modal
function showResetModal() {
    document.getElementById('resetPasswordModal').style.display = 'flex';
}
</script>
