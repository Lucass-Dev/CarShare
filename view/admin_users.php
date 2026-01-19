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

.search-bar input,
.search-bar select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.search-bar input {
    flex: 1;
    min-width: 250px;
}

.btn-admin {
    padding: 0.75rem 1.5rem;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
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

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    margin-right: 0.5rem;
}

.btn-danger {
    background: #dc3545;
}

.btn-danger:hover {
    background: #c82333;
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

    <h1 class="admin-title">Gestion des utilisateurs</h1>
    
    <div class="admin-nav">
        <a href="<?= url('index.php?action=admin_dashboard') ?>">Tableau de bord</a>
        <a href="<?= url('index.php?action=admin_users') ?>" class="active">Utilisateurs</a>
        <a href="<?= url('index.php?action=admin_trips') ?>">Trajets</a>
        <a href="<?= url('index.php?action=admin_vehicles') ?>">Véhicules</a>
        <a href="<?= url('index.php?action=admin_profile') ?>">Mon profil</a>
    </div>

    <div class="admin-section">
        <form method="GET" action="<?= url('index.php') ?>" class="search-bar">
            <input type="hidden" name="action" value="admin_users">
            <input type="text" name="search" placeholder="Rechercher par nom, email..." value="<?= htmlspecialchars($search ?? '') ?>">
            <select name="filter">
                <option value="">Tous</option>
                <option value="verified" <?= ($filter ?? '') === 'verified' ? 'selected' : '' ?>>Vérifiés</option>
                <option value="unverified" <?= ($filter ?? '') === 'unverified' ? 'selected' : '' ?>>Non vérifiés</option>
            </select>
            <button type="submit" class="btn-admin">Rechercher</button>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666;">Aucun utilisateur trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone_number'] ?? '-') ?></td>
                            <td>
                                <?php if ($user['is_verified_user']): ?>
                                    <span class="badge badge-success">Vérifié</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Non vérifié</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= url('index.php?action=admin_user_details&id=' . $user['id']) ?>" class="btn-admin btn-sm">Voir</a>
                                <a href="<?= url('index.php?action=admin_toggle_verification&id=' . $user['id']) ?>" 
                                   class="btn-admin btn-sm admin-confirm-link" 
                                   data-confirm="Modifier le statut de vérification ?">
                                    <?= $user['is_verified_user'] ? 'Retirer' : 'Vérifier' ?>
                                </a>
                                <a href="<?= url('index.php?action=admin_delete_user&id=' . $user['id']) ?>" 
                                   class="btn-admin btn-sm btn-danger admin-confirm-link" 
                                   data-confirm="Supprimer cet utilisateur ? (Action irréversible)">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= url('index.php?action=admin_users&page=' . $i . '&search=' . urlencode($search ?? '') . '&filter=' . urlencode($filter ?? '')) ?>" 
                       class="<?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>
