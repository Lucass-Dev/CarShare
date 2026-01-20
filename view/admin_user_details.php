<?php require_once __DIR__ . '/components/header.php'; ?>

<link rel="stylesheet" href="<?= asset('styles/user-profile.css') ?>">

<style>
.admin-content {
    max-width: 1200px;
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

.admin-nav a:hover {
    background: #007bff;
    color: white;
}

.user-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.detail-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.detail-card h3 {
    margin-top: 0;
    color: #007bff;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-label {
    font-weight: 600;
    color: #666;
    display: block;
    margin-bottom: 0.25rem;
}

.detail-value {
    color: #333;
}

.btn-admin {
    padding: 0.75rem 1.5rem;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    display: inline-block;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.btn-admin:hover {
    background: #0056b3;
}

.btn-danger {
    background: #dc3545;
}

.btn-danger:hover {
    background: #c82333;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.admin-table th {
    background: #f8f9fa;
    padding: 0.75rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.admin-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .user-details-grid {
        grid-template-columns: 1fr;
    }
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

    <h1 class="admin-title">Détails de l'utilisateur</h1>
    
    <div class="admin-nav">
        <a href="<?= url('index.php?action=admin_dashboard') ?>">Tableau de bord</a>
        <a href="<?= url('index.php?action=admin_users') ?>">Utilisateurs</a>
        <a href="<?= url('index.php?action=admin_trips') ?>">Trajets</a>
        <a href="<?= url('index.php?action=admin_vehicles') ?>">Véhicules</a>
        <a href="<?= url('index.php?action=admin_profile') ?>">Mon profil</a>
    </div>

    <div class="user-details-grid">
        <div class="detail-card">
            <h3>Informations personnelles</h3>
            <div class="detail-item">
                <span class="detail-label">ID :</span>
                <span class="detail-value">#<?= $user['id'] ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nom :</span>
                <span class="detail-value"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email :</span>
                <span class="detail-value"><?= htmlspecialchars($user['email']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Téléphone :</span>
                <span class="detail-value"><?= htmlspecialchars($user['phone_number'] ?? 'Non renseigné') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Statut :</span>
                <span class="detail-value">
                    <?= $user['is_verified_user'] ? '✓ Vérifié' : '✗ Non vérifié' ?>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Inscription :</span>
                <span class="detail-value"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
            </div>
        </div>

        <div class="detail-card">
            <h3>Véhicule</h3>
            <?php if ($user['car_brand']): ?>
                <div class="detail-item">
                    <span class="detail-label">Marque :</span>
                    <span class="detail-value"><?= htmlspecialchars($user['car_brand']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Modèle :</span>
                    <span class="detail-value"><?= htmlspecialchars($user['car_model'] ?? '-') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Immatriculation :</span>
                    <span class="detail-value"><?= htmlspecialchars($user['car_plate'] ?? 'Non renseignée') ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Places :</span>
                    <span class="detail-value"><?= $user['number_places'] ?? '-' ?></span>
                </div>
            <?php else: ?>
                <p style="color: #666;">Aucun véhicule enregistré</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="detail-card" style="margin-bottom: 2rem;">
        <h3>Statistiques</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <div style="font-size: 2rem; font-weight: bold; color: #007bff;"><?= $stats['total_trips'] ?? 0 ?></div>
                <div style="color: #666;">Trajets proposés</div>
            </div>
            <div>
                <div style="font-size: 2rem; font-weight: bold; color: #007bff;"><?= $stats['total_bookings'] ?? 0 ?></div>
                <div style="color: #666;">Réservations</div>
            </div>
            <div>
                <div style="font-size: 2rem; font-weight: bold; color: #007bff;"><?= number_format($stats['total_earned'] ?? 0, 2) ?> €</div>
                <div style="color: #666;">Gains totaux</div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h3>Actions administrateur</h3>
        <form method="POST" action="<?= url('index.php?action=admin_toggle_verification') ?>" style="display: inline;">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <input type="hidden" name="return_url" value="<?= url('index.php?action=admin_user_details&id=' . $user['id']) ?>">
            <button type="submit" class="btn-admin">
                <?= $user['is_verified_user'] ? 'Retirer la vérification' : 'Vérifier l\'utilisateur' ?>
            </button>
        </form>
        
        <form method="POST" action="<?= url('index.php?action=admin_delete_user') ?>" style="display: inline;" class="admin-delete-form" data-confirm="Supprimer cet utilisateur ? (Action irréversible)">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <button type="submit" class="btn-admin btn-danger">Supprimer l'utilisateur</button>
        </form>

        <a href="<?= url('index.php?action=admin_users') ?>" class="btn-admin" style="background: #6c757d;">
            Retour à la liste
        </a>
    </div>

    <?php if (!empty($history)): ?>
        <div class="detail-card">
            <h3>Historique récent</h3>
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
                            <td><?= htmlspecialchars($item['type']) ?></td>
                            <td><?= htmlspecialchars($item['details']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($item['date'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
// Gérer les formulaires avec confirmation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.admin-delete-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const confirmMsg = this.dataset.confirm;
            adminAlert.confirm(
                confirmMsg,
                () => {
                    // Clone form and submit without event listener
                    const newForm = this.cloneNode(true);
                    this.parentNode.replaceChild(newForm, this);
                    newForm.submit();
                }
            );
        });
    });
});
</script>

<?php require_once __DIR__ . '/components/footer.php'; ?>
