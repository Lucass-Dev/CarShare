<!-- NAVIGATION PAR ONGLETS -->
<div class="admin-tabs">
    <a href="<?= url('index.php?action=admin_dashboard') ?>" class="admin-tab">Tableau de bord</a>
    <a href="<?= url('index.php?action=admin_users') ?>" class="admin-tab">Utilisateurs</a>
    <a href="<?= url('index.php?action=admin_trips') ?>" class="admin-tab active">Trajets</a>
    <a href="<?= url('index.php?action=admin_vehicles') ?>" class="admin-tab">Véhicules</a>
</div>

<!-- CONTENU -->
<div class="admin-content">
    <h2 style="margin-bottom: 1.5rem; color: #1f2937;">🚗 Gestion des trajets</h2>
    
    <!-- BARRE DE RECHERCHE ET FILTRES -->
    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <form method="GET" action="<?= url('index.php') ?>" style="flex: 1; display: flex; gap: 1rem;">
            <input type="hidden" name="action" value="admin_trips">
            <div class="search-bar" style="flex: 1;">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Rechercher par lieu de départ ou d'arrivée..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <button type="submit" class="btn-primary">Rechercher</button>
        </form>
        
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= url('index.php?action=admin_trips') ?>" class="filter-btn <?= empty($filter) ? 'active' : '' ?>">Tous</a>
            <a href="<?= url('index.php?action=admin_trips&filter=upcoming') ?>" class="filter-btn <?= ($filter ?? '') === 'upcoming' ? 'active' : '' ?>">📅 À venir</a>
            <a href="<?= url('index.php?action=admin_trips&filter=past') ?>" class="filter-btn <?= ($filter ?? '') === 'past' ? 'active' : '' ?>">🕒 Passés</a>
        </div>
    </div>
    
    <?php if (empty($trips)): ?>
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Aucun trajet trouvé</p>
    <?php else: ?>
        <!-- TABLEAU TRAJETS -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Conducteur</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date départ</th>
                    <th>Prix</th>
                    <th>Places</th>
                    <th>Véhicule</th>
                    <th>Réservations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trips as $trip): ?>
                    <tr>
                        <td>#<?= $trip['id'] ?></td>
                        <td><?= htmlspecialchars($trip['provider_name']) ?></td>
                        <td><?= htmlspecialchars($trip['start_location']) ?></td>
                        <td><?= htmlspecialchars($trip['end_location']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($trip['departure_date'])) ?></td>
                        <td><strong><?= number_format($trip['price'], 2) ?>€</strong></td>
                        <td><?= $trip['available_seats'] ?></td>
                        <td>
                            <?php if ($trip['vehicle_brand'] && $trip['vehicle_model']): ?>
                                <?= htmlspecialchars($trip['vehicle_brand'] . ' ' . $trip['vehicle_model']) ?>
                            <?php else: ?>
                                <span style="color: #9ca3af;">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-info"><?= $trip['booking_count'] ?></span>
                        </td>
                        <td>
                            <form method="POST" action="<?= url('index.php?action=admin_delete_trip') ?>" style="display: inline;">
                                <input type="hidden" name="trip_id" value="<?= $trip['id'] ?>">
                                <button type="submit" class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce trajet ? Cette action est irréversible.')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="<?= url('index.php?action=admin_trips&page=' . ($page - 1) . ($search ? '&search=' . urlencode($search) : '') . ($filter ? '&filter=' . $filter : '')) ?>" class="page-link">← Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= url('index.php?action=admin_trips&page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($filter ? '&filter=' . $filter : '')) ?>" 
                       class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="<?= url('index.php?action=admin_trips&page=' . ($page + 1) . ($search ? '&search=' . urlencode($search) : '') . ($filter ? '&filter=' . $filter : '')) ?>" class="page-link">Suivant →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
