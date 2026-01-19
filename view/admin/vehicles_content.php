<div class="data-card">
    <div class="data-card-header">
        <h2 class="data-card-title">
            <i class="fas fa-car" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
            Registre des véhicules
        </h2>
    </div>
    <div class="data-card-body">
        <form method="GET" action="<?= url('index.php') ?>" class="admin-filters">
            <input type="hidden" name="action" value="admin_vehicles">
            <input type="text" 
                   name="search" 
                   class="admin-search-input" 
                   placeholder="Rechercher par marque, modèle ou immatriculation..." 
                   value="<?= htmlspecialchars($search) ?>"
                   autocomplete="off"
                   data-autosuggest="vehicles">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>

        <?php if (empty($vehicles)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-car"></i></div>
                <div class="empty-state-title">Aucun véhicule trouvé</div>
                <p><?= !empty($search) ? 'Essayez avec d\'autres mots-clés' : 'Les véhicules apparaîtront ici' ?></p>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Propriétaire</th>
                        <th>Véhicule</th>
                        <th>Immatriculation</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td>
                                <a href="<?= url('index.php?action=admin_user_details&id=' . $vehicle['user_id']) ?>" 
                                   style="color: #6f9fe6; text-decoration: none; font-weight: 600;">
                                    <?= htmlspecialchars($vehicle['owner_name'] ?? 'N/A') ?>
                                </a>
                            </td>
                            <td>
                                <div>
                                    <strong><?= htmlspecialchars($vehicle['car_brand'] ?? 'N/A') ?></strong>
                                    <?php if (!empty($vehicle['car_model'])): ?>
                                        <div style="font-size: 0.875rem; color: #6b7280;">
                                            <?= htmlspecialchars($vehicle['car_model']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($vehicle['car_plate'])): ?>
                                    <span class="badge badge-info" style="font-family: monospace; font-size: 0.875rem;">
                                        <?= htmlspecialchars($vehicle['car_plate']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #9ca3af; font-style: italic;">Non renseignée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($vehicle['email'] ?? '') ?>" 
                                   style="color: #6f9fe6; text-decoration: none; font-size: 0.875rem;">
                                    <i class="fas fa-envelope"></i>
                                    <?= htmlspecialchars($vehicle['email'] ?? 'N/A') ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= url('index.php?action=admin_user_details&id=' . $vehicle['user_id']) ?>" 
                                   class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="<?= url('index.php?action=admin_vehicles&page=' . ($page - 1) . '&search=' . urlencode($search)) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === 1 || $i === $totalPages || abs($i - $page) <= 2): ?>
                            <a href="<?= url('index.php?action=admin_vehicles&page=' . $i . '&search=' . urlencode($search)) ?>" 
                               class="<?= $i === $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php elseif (abs($i - $page) === 3): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="<?= url('index.php?action=admin_vehicles&page=' . ($page + 1) . '&search=' . urlencode($search)) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
