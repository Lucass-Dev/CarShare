<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CarShare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('styles/AdminView.css') ?>">
    <link rel="stylesheet" href="./assets/styles/footer.css">
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-car"></i>
            <span>CarShare Admin</span>
        </div>
        <nav class="nav-menu">
            <a href="<?= url('index.php?action=admin') ?>" class="nav-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= url('index.php?action=profile') ?>" class="nav-item">
                <i class="fas fa-user"></i>
                <span>Mon profil</span>
            </a>
            <a href="<?= url('index.php?action=logout') ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div>
            <h1>Dashboard - Suivi des performances</h1>
            <p>Vue d'ensemble de la plateforme de covoiturage</p>
        </div>

        <!-- KPI Cards -->
        <section class="kpi-section">
            <div class="kpi-card">
                <div class="kpi-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="kpi-info">
                    <h3><?= $stats['total_users'] ?></h3>
                    <p>Utilisateurs inscrits</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon green">
                    <i class="fas fa-route"></i>
                </div>
                <div class="kpi-info">
                    <h3><?= $stats['total_carpoolings'] ?></h3>
                    <p>Trajets créés</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="kpi-info">
                    <h3><?= $stats['total_bookings'] ?></h3>
                    <p>Réservations totales</p>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon purple">
                    <i class="fas fa-star"></i>
                </div>
                <div class="kpi-info">
                    <h3><?= $stats['active_carpoolings'] ?></h3>
                    <p>Trajets actifs</p>
                </div>
            </div>
        </section>

        <!-- Recent Users Table -->
        <section class="data-table">
            <h2><i class="fas fa-users"></i> Utilisateurs récents</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Vérifié</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($users, 0, 10) as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td><?= $user['is_verified_user'] ? '✓' : '✗' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Recent Carpoolings Table -->
        <section class="data-table">
            <h2><i class="fas fa-route"></i> Trajets récents</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Conducteur</th>
                        <th>Trajet</th>
                        <th>Date</th>
                        <th>Places</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($carpoolings, 0, 10) as $trip): ?>
                    <tr>
                        <td><?= $trip['id'] ?></td>
                        <td><?= htmlspecialchars($trip['first_name'] . ' ' . $trip['last_name']) ?></td>
                        <td><?= htmlspecialchars($trip['start_location']) ?> → <?= htmlspecialchars($trip['end_location']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($trip['start_date'])) ?></td>
                        <td><?= $trip['available_places'] ?></td>
                        <td><?= number_format($trip['price'], 2) ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>

    <?php require_once __DIR__ . '/components/footer.php'; ?>

</body>
</html>
