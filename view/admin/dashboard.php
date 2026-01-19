<!-- STATISTIQUES -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
                <div class="stat-label">Utilisateurs totaux</div>
            </div>
            <div class="stat-icon purple">👥</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['verified_users']) ?></div>
                <div class="stat-label">Utilisateurs vérifiés</div>
            </div>
            <div class="stat-icon green">✓</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_trips']) ?></div>
                <div class="stat-label">Trajets totaux</div>
            </div>
            <div class="stat-icon blue">🚗</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_bookings']) ?></div>
                <div class="stat-label">Réservations</div>
            </div>
            <div class="stat-icon orange">📅</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_revenue'], 2) ?>€</div>
                <div class="stat-label">Chiffre d'affaires</div>
            </div>
            <div class="stat-icon green">💰</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['total_vehicles']) ?></div>
                <div class="stat-label">Véhicules</div>
            </div>
            <div class="stat-icon blue">🚘</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['new_users_month']) ?></div>
                <div class="stat-label">Nouveaux utilisateurs (mois)</div>
            </div>
            <div class="stat-icon purple">📈</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header">
            <div>
                <div class="stat-value"><?= number_format($stats['trips_this_month']) ?></div>
                <div class="stat-label">Trajets (mois)</div>
            </div>
            <div class="stat-icon orange">🚙</div>
        </div>
    </div>
</div>

<!-- GRAPHIQUES -->
<div class="grid-2">
    <!-- Évolution des inscriptions -->
    <div class="chart-container">
        <div class="chart-title">Inscriptions (6 derniers mois)</div>
        <canvas id="registrationsChart" height="200"></canvas>
    </div>
    
    <!-- Évolution des trajets -->
    <div class="chart-container">
        <div class="chart-title">Trajets créés (6 derniers mois)</div>
        <canvas id="tripsChart" height="200"></canvas>
    </div>
    
    <!-- Revenus mensuels -->
    <div class="chart-container">
        <div class="chart-title">Revenus mensuels</div>
        <canvas id="revenueChart" height="200"></canvas>
    </div>
    
    <!-- Top 10 villes -->
    <div class="chart-container">
        <div class="chart-title">Top 10 villes les plus actives</div>
        <canvas id="citiesChart" height="200"></canvas>
    </div>
</div>

<!-- NAVIGATION PAR ONGLETS -->
<div class="admin-tabs">
    <a href="<?= url('index.php?action=admin_dashboard') ?>" class="admin-tab active">Tableau de bord</a>
    <a href="<?= url('index.php?action=admin_users') ?>" class="admin-tab">Utilisateurs</a>
    <a href="<?= url('index.php?action=admin_trips') ?>" class="admin-tab">Trajets</a>
    <a href="<?= url('index.php?action=admin_vehicles') ?>" class="admin-tab">Véhicules</a>
</div>

<!-- CONTENU -->
<div class="admin-content">
    <h2 style="margin-bottom: 1.5rem; color: #1f2937;">Transactions récentes</h2>
    
    <?php if (empty($recentTransactions)): ?>
        <p style="text-align: center; color: #6b7280; padding: 2rem;">Aucune transaction</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Passager</th>
                    <th>Conducteur</th>
                    <th>Trajet</th>
                    <th>Date</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentTransactions as $transaction): ?>
                    <tr>
                        <td>#<?= $transaction['id'] ?></td>
                        <td><?= htmlspecialchars($transaction['booker_first_name'] . ' ' . $transaction['booker_last_name']) ?></td>
                        <td><?= htmlspecialchars($transaction['provider_first_name'] . ' ' . $transaction['provider_last_name']) ?></td>
                        <td><?= htmlspecialchars($transaction['start_location'] . ' → ' . $transaction['end_location']) ?></td>
                        <td><?= date('d/m/Y', strtotime($transaction['departure_date'])) ?></td>
                        <td><strong><?= number_format($transaction['price'], 2) ?>€</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
// Configuration Chart.js
Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
Chart.defaults.color = '#6b7280';

// Données pour les graphiques
const monthlyRegistrations = <?= json_encode($monthlyRegistrations) ?>;
const monthlyTrips = <?= json_encode($monthlyTrips) ?>;
const monthlyRevenue = <?= json_encode($monthlyRevenue) ?>;
const cityStats = <?= json_encode($cityStats) ?>;

// Graphique des inscriptions
new Chart(document.getElementById('registrationsChart'), {
    type: 'line',
    data: {
        labels: monthlyRegistrations.map(d => d.month),
        datasets: [{
            label: 'Inscriptions',
            data: monthlyRegistrations.map(d => d.count),
            borderColor: '#9333ea',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Graphique des trajets
new Chart(document.getElementById('tripsChart'), {
    type: 'bar',
    data: {
        labels: monthlyTrips.map(d => d.month),
        datasets: [{
            label: 'Trajets',
            data: monthlyTrips.map(d => d.count),
            backgroundColor: '#3b82f6'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Graphique des revenus
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: monthlyRevenue.map(d => d.month),
        datasets: [{
            label: 'Revenus (€)',
            data: monthlyRevenue.map(d => parseFloat(d.revenue)),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Graphique des villes
new Chart(document.getElementById('citiesChart'), {
    type: 'bar',
    data: {
        labels: cityStats.map(d => d.city),
        datasets: [{
            label: 'Trajets',
            data: cityStats.map(d => d.total_trips),
            backgroundColor: '#f97316'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true } }
    }
});
</script>
