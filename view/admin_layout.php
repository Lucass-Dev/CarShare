<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Administration' ?> - CarShare Admin</title>
    <link rel="icon" href="<?= asset('img/carshare.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= asset('styles/admin-modern.css?v=' . time()) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-layout">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header" style="text-align: center;">
                <h2>Admin</h2>
            </div>
            
            <nav class="admin-sidebar-nav">
                <a href="<?= url('index.php?action=admin_dashboard') ?>" class="<?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i>
                    Tableau de bord
                </a>
                <a href="<?= url('index.php?action=admin_users') ?>" class="<?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    Utilisateurs
                </a>
                <a href="<?= url('index.php?action=admin_trips') ?>" class="<?= ($currentPage ?? '') === 'trips' ? 'active' : '' ?>">
                    <i class="fas fa-route"></i>
                    Trajets
                </a>
                <a href="<?= url('index.php?action=admin_vehicles') ?>" class="<?= ($currentPage ?? '') === 'vehicles' ? 'active' : '' ?>">
                    <i class="fas fa-car"></i>
                    Véhicules
                </a>
                <a href="<?= url('index.php?action=admin_profile') ?>" class="<?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-circle"></i>
                    Mon profil
                </a>
                <a href="<?= url('index.php?action=home') ?>" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 1rem; padding-top: 1rem;">
                    <i class="fas fa-globe"></i>
                    Voir le site
                </a>
                <a href="<?= url('index.php?action=admin_logout') ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main-content">
            <!-- Topbar -->
            <header class="admin-topbar">
                <h1 class="admin-topbar-title"><?= $pageTitle ?? 'Administration' ?></h1>
                <div class="admin-user-menu">
                    <span style="color: #6b7280; font-size: 0.938rem;">
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                    </span>
                    <div class="admin-user-avatar">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="admin-content">
                <?php if (isset($_SESSION['admin_success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($_SESSION['admin_success']) ?>
                    </div>
                    <?php unset($_SESSION['admin_success']); endif; ?>
                
                <?php if (isset($_SESSION['admin_error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= htmlspecialchars($_SESSION['admin_error']) ?>
                    </div>
                    <?php unset($_SESSION['admin_error']); endif; ?>

                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.admin-sidebar');
        const menuBtn = document.querySelector('.mobile-menu-btn');
        
        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
            });
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !menuBtn?.contains(e.target)) {
                sidebar.classList.remove('mobile-open');
            }
        });
        
        // Confirm delete actions
        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm(btn.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });
    });
    </script>
    
    <!-- Autosuggestion Admin -->
    <script src="<?= url('assets/js/admin-autosuggest.js?v=' . time()) ?>"></script>
</body>
</html>
