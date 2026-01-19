<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_users'] ?? 0) ?></div>
                <div class="stat-label">Utilisateurs</div>
            </div>
            <div class="stat-card-icon blue">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> +<?= $stats['new_users_month'] ?? 0 ?> ce mois
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['verified_users'] ?? 0) ?></div>
                <div class="stat-label">Utilisateurs vérifiés</div>
            </div>
            <div class="stat-card-icon green">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_trips'] ?? 0) ?></div>
                <div class="stat-label">Trajets</div>
            </div>
            <div class="stat-card-icon blue">
                <i class="fas fa-route"></i>
            </div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i> +<?= $stats['trips_this_month'] ?? 0 ?> ce mois
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_bookings'] ?? 0) ?></div>
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
                <div class="stat-value"><?= number_format($stats['total_revenue'] ?? 0, 2) ?> €</div>
                <div class="stat-label">Chiffre d'affaires</div>
            </div>
            <div class="stat-card-icon green">
                <i class="fas fa-euro-sign"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_vehicles'] ?? 0) ?></div>
                <div class="stat-label">Véhicules</div>
            </div>
            <div class="stat-card-icon blue">
                <i class="fas fa-car"></i>
            </div>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h2 class="data-card-title">
            <i class="fas fa-clock" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
            Transactions récentes
        </h2>
        <a href="<?= url('index.php?action=admin_users') ?>" class="btn btn-secondary btn-sm">
            Voir tous les utilisateurs
        </a>
    </div>
    <div class="data-card-body" style="padding: 0;">
        <?php if (empty($recentTransactions)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                <div class="empty-state-title">Aucune transaction</div>
                <p>Les transactions apparaîtront ici.</p>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Passager</th>
                        <th>Conducteur</th>
                        <th>Trajet</th>
                        <th>Prix</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentTransactions as $t): ?>
                        <tr>
                            <td><span class="badge badge-info">#<?= $t['id'] ?></span></td>
                            <td><?= htmlspecialchars($t['booker_first_name'] . ' ' . $t['booker_last_name']) ?></td>
                            <td><?= htmlspecialchars($t['provider_first_name'] . ' ' . $t['provider_last_name']) ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: #6b7280;">
                                    <?= htmlspecialchars($t['start_location']) ?>
                                    <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                                    <?= htmlspecialchars($t['end_location']) ?>
                                </div>
                            </td>
                            <td><strong style="color: #10b981;"><?= number_format($t['price'], 2) ?> €</strong></td>
                            <td><?= $t['departure_date'] ? date('d/m/Y', strtotime($t['departure_date'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
    <div class="data-card">
        <div class="data-card-header">
            <h3 class="data-card-title" style="font-size: 1rem;">
                <i class="fas fa-chart-pie" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
                Accès rapides
            </h3>
        </div>
        <div class="data-card-body">
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="<?= url('index.php?action=admin_users') ?>" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-users"></i> Gérer les utilisateurs
                </a>
                <a href="<?= url('index.php?action=admin_trips') ?>" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-route"></i> Gérer les trajets
                </a>
                <a href="<?= url('index.php?action=admin_vehicles') ?>" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fas fa-car"></i> Registre des véhicules
                </a>
            </div>
        </div>
    </div>
</div>
