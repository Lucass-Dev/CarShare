<?php require_once __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="<?= asset('styles/user-profile.css') ?>">

<style>
.admin-content {
    max-width: 1400px;
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.admin-section {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.admin-section h2 {
    margin-top: 0;
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: #f8f9fa;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #dee2e6;
}

.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.admin-table tr:hover {
    background: #f8f9fa;
}

.btn-admin {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.2s;
    border: none;
    cursor: pointer;
}

.btn-admin:hover {
    background: #0056b3;
}

.btn-admin-secondary {
    background: #6c757d;
}

.btn-admin-secondary:hover {
    background: #545b62;
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

    <h1 class="admin-title">Tableau de bord administrateur</h1>
    
    <div class="admin-nav">
        <a href="<?= url('index.php?action=admin_dashboard') ?>" class="active">Tableau de bord</a>
        <a href="<?= url('index.php?action=admin_users') ?>">Utilisateurs</a>
        <a href="<?= url('index.php?action=admin_trips') ?>">Trajets</a>
        <a href="<?= url('index.php?action=admin_vehicles') ?>">Véhicules</a>
        <a href="<?= url('index.php?action=admin_profile') ?>">Mon profil</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_users'] ?? 0 ?></div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['verified_users'] ?? 0 ?></div>
            <div class="stat-label">Utilisateurs vérifiés</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_trips'] ?? 0 ?></div>
            <div class="stat-label">Trajets</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_bookings'] ?? 0 ?></div>
            <div class="stat-label">Réservations</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['total_revenue'] ?? 0, 2) ?> €</div>
            <div class="stat-label">Chiffre d'affaires</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_vehicles'] ?? 0 ?></div>
            <div class="stat-label">Véhicules</div>
        </div>
    </div>

    <div class="admin-section">
        <h2>Transactions récentes</h2>
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
                <?php if (empty($recentTransactions)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666;">Aucune transaction</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentTransactions as $t): ?>
                        <tr>
                            <td>#<?= $t['id'] ?></td>
                            <td><?= htmlspecialchars($t['booker_first_name'] . ' ' . $t['booker_last_name']) ?></td>
                            <td><?= htmlspecialchars($t['provider_first_name'] . ' ' . $t['provider_last_name']) ?></td>
                            <td><?= htmlspecialchars($t['start_location'] . ' → ' . $t['end_location']) ?></td>
                            <td><?= number_format($t['price'], 2) ?> €</td>
                            <td><?= date('d/m/Y', strtotime($t['departure_date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
