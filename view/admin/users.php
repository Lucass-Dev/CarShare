<!-- NAVIGATION PAR ONGLETS -->
<div class="admin-tabs">
    <a href="<?= url('index.php?action=admin_dashboard') ?>" class="admin-tab">Tableau de bord</a>
    <a href="<?= url('index.php?action=admin_users') ?>" class="admin-tab active">Utilisateurs</a>
    <a href="<?= url('index.php?action=admin_trips') ?>" class="admin-tab">Trajets</a>
    <a href="<?= url('index.php?action=admin_vehicles') ?>" class="admin-tab">Véhicules</a>
</div>

<!-- CONTENU -->
<div class="admin-content">
    <h2 style="margin-bottom: 1.5rem; color: #1f2937;">👥 Gestion des utilisateurs</h2>
    
    <!-- BARRE DE RECHERCHE ET FILTRES -->
    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <form method="GET" action="<?= url('index.php') ?>" style="flex: 1; display: flex; gap: 1rem;">
            <input type="hidden" name="action" value="admin_users">
            <div class="search-bar" style="flex: 1;">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Rechercher par nom ou email..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
        
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= url('index.php?action=admin_users') ?>" class="filter-btn <?= empty($filter) ? 'active' : '' ?>">Tous</a>
            <a href="<?= url('index.php?action=admin_users&filter=verified') ?>" class="filter-btn <?= ($filter ?? '') === 'verified' ? 'active' : '' ?>">✓ Vérifiés</a>
            <a href="<?= url('index.php?action=admin_users&filter=unverified') ?>" class="filter-btn <?= ($filter ?? '') === 'unverified' ? 'active' : '' ?>">⚠ Non vérifiés</a>
        </div>
    </div>
    
    <?php if (empty($users)): ?>
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Aucun utilisateur trouvé</p>
    <?php else: ?>
        <!-- TABLEAU UTILISATEURS -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['is_verified_user']): ?>
                                <span class="badge badge-success">✓ Vérifié</span>
                            <?php else: ?>
                                <span class="badge badge-warning">⚠ Non vérifié</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="<?= url('index.php?action=admin_user_details&id=' . $user['id']) ?>" class="btn-action btn-view" title="Voir détails">👁️</a>
                                
                                <form method="POST" action="<?= url('index.php?action=admin_toggle_verification') ?>" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <?php if ($user['is_verified_user']): ?>
                                        <button type="submit" class="btn-action btn-warning" title="Marquer non vérifié" onclick="return confirm('Marquer cet utilisateur comme non vérifié ?')">⚠</button>
                                    <?php else: ?>
                                        <button type="submit" class="btn-action btn-success" title="Marquer vérifié" onclick="return confirm('Marquer cet utilisateur comme vérifié ?')">✓</button>
                                    <?php endif; ?>
                                </form>
                                
                                <form method="POST" action="<?= url('index.php?action=admin_delete_user') ?>" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= url('index.php?action=admin_users&page=' . ($page - 1) . ($search ? '&search=' . urlencode($search) : '') . ($filter ? '&filter=' . $filter : '')) ?>" class="page-link">← Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= url('index.php?action=admin_users&page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($filter ? '&filter=' . $filter : '')) ?>" 
                       class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="<?= url('index.php?action=admin_users&page=' . ($page + 1) . ($search ? '&search=' . urlencode($search) : '') . ($filter ? '&filter=' . $filter : '')) ?>" class="page-link">Suivant →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
