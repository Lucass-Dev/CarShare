<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <div class="data-card">
        <div class="data-card-header">
            <h2 class="data-card-title">
                <i class="fas fa-user" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Informations personnelles
            </h2>
        </div>
        <div class="data-card-body">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">ID</div>
                    <div><span class="badge badge-info">#<?= $user['id'] ?></span></div>
                </div>
                <div>
                    <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Nom complet</div>
                    <div style="font-size: 1.125rem; font-weight: 600;"><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></div>
                </div>
                <div>
                    <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Email</div>
                    <div>
                        <a href="mailto:<?= htmlspecialchars($user['email'] ?? '') ?>" style="color: #6f9fe6; text-decoration: none;">
                            <i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email'] ?? 'N/A') ?>
                        </a>
                    </div>
                </div>
                <div>
                    <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Statut</div>
                    <div>
                        <?php if ($user['is_verified_user']): ?>
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Vérifié
                            </span>
                        <?php else: ?>
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i> Non vérifié
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Date d'inscription</div>
                    <div>
                        <i class="fas fa-calendar"></i> 
                        <?php 
                        $createdAt = $user['created_at'] ?? null;
                        echo $createdAt ? date('d/m/Y à H:i', strtotime($createdAt)) : 'N/A';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="data-card">
        <div class="data-card-header">
            <h2 class="data-card-title">
                <i class="fas fa-car" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Véhicule
            </h2>
        </div>
        <div class="data-card-body">
            <?php if (!empty($user['car_brand'])): ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Marque</div>
                        <div style="font-size: 1.125rem; font-weight: 600;"><?= htmlspecialchars($user['car_brand']) ?></div>
                    </div>
                    <?php if (!empty($user['car_model'])): ?>
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Modèle</div>
                            <div><?= htmlspecialchars($user['car_model']) ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($user['car_plate'])): ?>
                        <div>
                            <div style="font-weight: 600; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">Immatriculation</div>
                            <div><span class="badge badge-info" style="font-family: monospace; font-size: 1rem;"><?= htmlspecialchars($user['car_plate']) ?></span></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="empty-state" style="padding: 2rem 1rem;">
                    <div class="empty-state-icon" style="font-size: 2rem;"><i class="fas fa-car-side"></i></div>
                    <div class="empty-state-title" style="font-size: 1rem;">Aucun véhicule enregistré</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value" style="font-size: 2rem;"><?= $stats['total_trips'] ?? 0 ?></div>
                <div class="stat-label">Trajets proposés</div>
            </div>
            <div class="stat-card-icon blue">
                <i class="fas fa-route"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value" style="font-size: 2rem;"><?= $stats['total_bookings'] ?? 0 ?></div>
                <div class="stat-label">Réservations</div>
            </div>
            <div class="stat-card-icon orange">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value" style="font-size: 2rem;"><?= number_format($stats['total_earned'] ?? 0, 2) ?> €</div>
                <div class="stat-label">Gains totaux</div>
            </div>
            <div class="stat-card-icon green">
                <i class="fas fa-euro-sign"></i>
            </div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h2 class="data-card-title">
            <i class="fas fa-tools" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
            Actions administrateur
        </h2>
    </div>
    <div class="data-card-body">
        <div class="action-buttons">
            <form method="POST" action="<?= url('index.php?action=admin_toggle_verification') ?>" style="display: inline;">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <input type="hidden" name="return_url" value="<?= url('index.php?action=admin_user_details&id=' . $user['id']) ?>">
                <button type="submit" class="btn <?= $user['is_verified_user'] ? 'btn-secondary' : 'btn-success' ?>">
                    <i class="fas fa-<?= $user['is_verified_user'] ? 'times' : 'check' ?>-circle"></i>
                    <?= $user['is_verified_user'] ? 'Retirer la vérification' : 'Vérifier l\'utilisateur' ?>
                </button>
            </form>
            
            <form method="POST" action="<?= url('index.php?action=admin_delete_user') ?>" style="display: inline;">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <button type="submit" class="btn btn-danger" data-confirm="Supprimer cet utilisateur ? Cette action est irréversible et supprimera tous ses trajets et réservations.">
                    <i class="fas fa-trash"></i> Supprimer l'utilisateur
                </button>
            </form>

            <a href="<?= url('index.php?action=admin_users') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>

<?php if (!empty($history)): ?>
    <div class="data-card">
        <div class="data-card-header">
            <h2 class="data-card-title">
                <i class="fas fa-history" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Historique récent
            </h2>
        </div>
        <div class="data-card-body" style="padding: 0;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Détails</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $item): ?>
                        <tr>
                            <td><span class="badge badge-info"><?= htmlspecialchars($item['type'] ?? 'N/A') ?></span></td>
                            <td><?= htmlspecialchars($item['details'] ?? 'N/A') ?></td>
                            <td>
                                <?php 
                                $itemDate = $item['date'] ?? null;
                                echo $itemDate ? date('d/m/Y H:i', strtotime($itemDate)) : 'N/A';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
