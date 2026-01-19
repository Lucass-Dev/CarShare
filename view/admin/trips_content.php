<div class="data-card">
    <div class="data-card-header">
        <h2 class="data-card-title">
            <i class="fas fa-route" style="margin-right: 0.5rem; color: #6f9fe6;"></i>
            Liste des trajets
        </h2>
    </div>
    <div class="data-card-body">
        <form method="GET" action="<?= url('index.php') ?>" class="admin-filters" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <input type="hidden" name="action" value="admin_trips">
            
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">
                    <i class="fas fa-search"></i> Rechercher ville
                </label>
                <input type="text" 
                       name="search" 
                       class="admin-search-input" 
                       placeholder="Rechercher par ville (départ ou arrivée)..." 
                       value="<?= htmlspecialchars($search) ?>"
                       autocomplete="off"
                       data-autosuggest="cities"
                       style="width: 100%;">
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">
                    <i class="fas fa-filter"></i> Statut
                </label>
                <select name="filter" class="admin-select" style="width: 100%;">
                    <option value="">Tous les trajets</option>
                    <option value="upcoming" <?= $filter === 'upcoming' ? 'selected' : '' ?>>Trajets à venir</option>
                    <option value="past" <?= $filter === 'past' ? 'selected' : '' ?>>Trajets passés</option>
                </select>
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">
                    <i class="fas fa-calendar-alt"></i> Date début
                </label>
                <input type="date" 
                       name="date_from" 
                       value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"
                       class="admin-search-input"
                       style="width: 100%;">
            </div>
            
            <div style="min-width: 150px;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem;">
                    <i class="fas fa-calendar-check"></i> Date fin
                </label>
                <input type="date" 
                       name="date_to" 
                       value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"
                       class="admin-search-input"
                       style="width: 100%;">
            </div>
            
            <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                <?php if (!empty($search) || !empty($filter) || !empty($_GET['date_from']) || !empty($_GET['date_to'])): ?>
                    <a href="<?= url('index.php?action=admin_trips') ?>" class="btn btn-secondary" style="white-space: nowrap;">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <?php if (empty($trips)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-route"></i></div>
                <div class="empty-state-title">Aucun trajet trouvé</div>
                <p><?= !empty($search) ? 'Essayez avec d\'autres mots-clés' : 'Les trajets apparaîtront ici' ?></p>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Conducteur</th>
                        <th>Trajet</th>
                        <th>Date</th>
                        <th>Prix</th>
                        <th>Places</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trips as $trip): ?>
                        <tr>
                            <td><span class="badge badge-info">#<?= $trip['id'] ?></span></td>
                            <td><?= htmlspecialchars($trip['provider_name'] ?? 'N/A') ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.938rem;">
                                    <span><?= htmlspecialchars($trip['start_location'] ?? 'N/A') ?></span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.75rem; color: #6b7280;"></i>
                                    <span><?= htmlspecialchars($trip['end_location'] ?? 'N/A') ?></span>
                                </div>
                            </td>
                            <td>
                                <?php 
                                $date = $trip['start_date'] ?? null;
                                echo $date ? date('d/m/Y H:i', strtotime($date)) : '-';
                                ?>
                            </td>
                            <td><strong style="color: #10b981;"><?= number_format($trip['price'] ?? 0, 2) ?> €</strong></td>
                            <td>
                                <span class="badge badge-success">
                                    <?= $trip['available_places'] ?? $trip['available_seats'] ?? 0 ?> places
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="<?= url('index.php?action=admin_delete_trip') ?>" style="display: inline;">
                                        <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="Supprimer ce trajet ?">
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
                        <a href="<?= url('index.php?action=admin_trips&page=' . ($page - 1) . '&search=' . urlencode($search) . '&filter=' . urlencode($filter)) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i === 1 || $i === $totalPages || abs($i - $page) <= 2): ?>
                            <a href="<?= url('index.php?action=admin_trips&page=' . $i . '&search=' . urlencode($search) . '&filter=' . urlencode($filter)) ?>" 
                               class="<?= $i === $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php elseif (abs($i - $page) === 3): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="<?= url('index.php?action=admin_trips&page=' . ($page + 1) . '&search=' . urlencode($search) . '&filter=' . urlencode($filter)) ?>">
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
    // Auto-submit form on filter change
    const filterSelect = document.querySelector('select[name="filter"]');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
