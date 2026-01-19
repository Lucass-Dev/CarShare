<div class="data-card">
    <div class="data-card-header">
        <h2 class="data-card-title">
            <i class="fas fa-users" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
            Liste des utilisateurs
        </h2>
    </div>
    <div class="data-card-body">
        <form method="GET" action="<?= url('index.php') ?>" class="admin-filters">
            <input type="hidden" name="action" value="admin_users">
            <input type="text" 
                   name="search" 
                   class="admin-search-input" 
                   placeholder="Rechercher par nom, email..." 
                   value="<?= htmlspecialchars($search) ?>"
                   autocomplete="off"
                   data-autosuggest="users">
            <select name="filter" class="admin-select">
                <option value="">Tous</option>
                <option value="verified" <?= $filter === 'verified' ? 'selected' : '' ?>>Vérifiés</option>
                <option value="unverified" <?= $filter === 'unverified' ? 'selected' : '' ?>>Non vérifiés</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>

        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-users"></i></div>
                <div class="empty-state-title">Aucun utilisateur trouvé</div>
                <p><?= !empty($search) ? 'Essayez avec d\'autres mots-clés' : 'Les utilisateurs apparaîtront ici' ?></p>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><span class="badge badge-info">#<?= $user['id'] ?></span></td>
                            <td><?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($user['is_verified_user']): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Vérifié
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i> Non vérifié
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= url('index.php?action=admin_user_details&id=' . $user['id']) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="<?= url('index.php?action=admin_toggle_verification') ?>" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm" title="<?= $user['is_verified_user'] ? 'Retirer vérification' : 'Vérifier' ?>">
                                            <i class="fas fa-<?= $user['is_verified_user'] ? 'times' : 'check' ?>"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="<?= url('index.php?action=admin_delete_user') ?>" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Supprimer cet utilisateur ? (Irréversible)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?= url('index.php?action=admin_users&page=' . ($page - 1) . '&search=' . urlencode($search) . '&filter=' . urlencode($filter)) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === 1 || $i === $totalPages || abs($i - $page) <= 2): ?>
                            <a href="<?= url('index.php?action=admin_users&page=' . $i . '&search=' . urlencode($search) . '&filter=' . urlencode($filter)) ?>" 
                               class="<?= $i === $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php elseif (abs($i - $page) === 3): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="<?= url('index.php?action=admin_users&page=' . ($page + 1) . '&search=' . urlencode($search) . '&filter=' . urlencode($filter)) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.querySelector('select[name="filter"]');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
