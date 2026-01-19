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

.admin-section {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.search-bar {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.search-bar input {
    flex: 1;
    min-width: 250px;
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

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: #f8f9fa;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.admin-table tr:hover {
    background: #f8f9fa;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.pagination a {
    padding: 0.5rem 1rem;
    background: white;
    color: #007bff;
    text-decoration: none;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.pagination a:hover,
.pagination a.active {
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

    <h1 class="admin-title">Registre des véhicules</h1>
    
    <div class="admin-nav">
        <a href="<?= url('index.php?action=admin_dashboard') ?>">Tableau de bord</a>
        <a href="<?= url('index.php?action=admin_users') ?>">Utilisateurs</a>
        <a href="<?= url('index.php?action=admin_trips') ?>">Trajets</a>
        <a href="<?= url('index.php?action=admin_vehicles') ?>" class="active">Véhicules</a>
        <a href="<?= url('index.php?action=admin_profile') ?>">Mon profil</a>
    </div>

    <div class="admin-section">
        <form method="GET" action="<?= url('index.php') ?>" class="search-bar">
            <input type="hidden" name="action" value="admin_vehicles">
            <input type="text" name="search" placeholder="Rechercher par marque, modèle, immatriculation..." value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn-admin">Rechercher</button>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Propriétaire</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Immatriculation</th>
                    <th>Places</th>
                    <th>Email propriétaire</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vehicles)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666;">Aucun véhicule trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td>
                                <a href="<?= url('index.php?action=admin_user_details&id=' . $vehicle['user_id']) ?>" style="color: #007bff; text-decoration: none;">
                                    <?= htmlspecialchars($vehicle['owner_name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($vehicle['car_brand']) ?></td>
                            <td><?= htmlspecialchars($vehicle['car_model'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($vehicle['car_plate'] ?? 'Non renseignée') ?></td>
                            <td><?= $vehicle['number_places'] ?? '-' ?></td>
                            <td><?= htmlspecialchars($vehicle['email']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= url('index.php?action=admin_vehicles&page=' . $i . '&search=' . urlencode($search ?? '')) ?>" 
                       class="<?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
