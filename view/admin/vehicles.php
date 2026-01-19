<!-- NAVIGATION PAR ONGLETS -->
<div class="admin-tabs">
    <a href="<?= url('index.php?action=admin_dashboard') ?>" class="admin-tab">Tableau de bord</a>
    <a href="<?= url('index.php?action=admin_users') ?>" class="admin-tab">Utilisateurs</a>
    <a href="<?= url('index.php?action=admin_trips') ?>" class="admin-tab">Trajets</a>
    <a href="<?= url('index.php?action=admin_vehicles') ?>" class="admin-tab active">Véhicules</a>
</div>

<!-- CONTENU -->
<div class="admin-content">
    <h2 style="margin-bottom: 1.5rem; color: #1f2937;">🚘 Registre des véhicules</h2>
    
    <!-- BARRE DE RECHERCHE -->
    <div style="margin-bottom: 1.5rem;">
        <form method="GET" action="<?= url('index.php') ?>" style="display: flex; gap: 1rem;">
            <input type="hidden" name="action" value="admin_vehicles">
            <div class="search-bar" style="flex: 1;">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Rechercher par marque, modèle ou immatriculation..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
    </div>
    
    <?php if (empty($vehicles)): ?>
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Aucun véhicule trouvé</p>
    <?php else: ?>
        <!-- TABLEAU VÉHICULES -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Immatriculation</th>
                    <th>Propriétaire</th>
                    <th>Email propriétaire</th>
                    <th>Trajets effectués</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td>#<?= $vehicle['id'] ?></td>
                        <td><?= htmlspecialchars($vehicle['brand']) ?></td>
                        <td><?= htmlspecialchars($vehicle['model']) ?></td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($vehicle['license_plate']) ?></span></td>
                        <td>
                            <a href="<?= url('index.php?action=admin_user_details&id=' . $vehicle['owner_id']) ?>" style="color: #7c3aed; text-decoration: none;">
                                <?= htmlspecialchars($vehicle['owner_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($vehicle['owner_email']) ?></td>
                        <td><span class="badge badge-success"><?= $vehicle['trip_count'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= url('index.php?action=admin_vehicles&page=' . ($page - 1) . ($search ? '&search=' . urlencode($search) : '')) ?>" class="page-link">← Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= url('index.php?action=admin_vehicles&page=' . $i . ($search ? '&search=' . urlencode($search) : '')) ?>" 
                       class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="<?= url('index.php?action=admin_vehicles&page=' . ($page + 1) . ($search ? '&search=' . urlencode($search) : '')) ?>" class="page-link">Suivant →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
