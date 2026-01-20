<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['logged']) && $_SESSION['logged'];
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? 'Utilisateur') : '';
$userId = $_SESSION['user_id'] ?? null;

// Get unread message count (only for normal users, not admin)
$unreadCount = 0;
if ($isLoggedIn && $userId && !$isAdmin) {
    require_once __DIR__ . '/../../model/MessagingModel.php';
    $messagingModel = new MessagingModel();
    $unreadCount = $messagingModel->getUnreadMessageCount($userId);
}
?>
<header>
    <div class="logo">
        <a href="index.php" class="home-link">
            <img src="./assets/img/carshare.png" alt="CarShare Logo">
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="header-nav">
        <a href="index.php?action=search" class="nav-link">Trouver un trajet</a>
        <a href="index.php?action=create_trip" class="nav-link">Proposer un trajet</a>
        <a href="index.php?action=offers" class="nav-link">Offres</a>
    </nav>

    <!-- Compact Search Bar -->
    <div class="header-search">
        <div class="search-input-container">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" 
                   id="global-search" 
                   placeholder="Rechercher un utilisateur...">
        </div>
        <div class="search-results" id="search-results"></div>
    </div>

    <!-- Contact Link -->
    <div class="header-actions">
        <a href="index.php?action=contact" class="nav-link nav-link--contact">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
            </svg>
            <span>Contact</span>
        </a>
    </div>

    <div class="header-icons">
        <div class="dropdown">
            <button class="dropdown-toggle upp" type="button">
                <img src="./assets/img/avatar.jpg" alt="Photo de profil" class="icon">
            </button>
            <?php if ($isLoggedIn): ?>
                <?php if ($isAdmin): ?>
                    <!-- MENU ADMIN -->
                    <ul class="dropdown-menu">
                        <li class="dropdown-header"><?= htmlspecialchars($userName) ?> (Admin)</li>
                        <li><a href="?action=admin_dashboard">
                            <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Tableau de bord
                        </a></li>
                        <li><a href="?action=admin_profile">
                            <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mon profil admin
                        </a></li>
                        <li class="dropdown-divider"></li>
                        <li><a href="?action=admin_logout">Se déconnecter</a></li>
                    </ul>
                <?php else: ?>
                    <!-- MENU UTILISATEUR NORMAL -->
                    <ul class="dropdown-menu">
                        <li class="dropdown-header"><?= htmlspecialchars($userName) ?></li>
                        <li><a href="?action=profile">Mon profil</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a href="?action=my_trips">
                            <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                            Mes trajets proposés
                        </a></li>
                        <li><a href="?action=history">
                            <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Mes réservations
                        </a></li>
                        <li><a href="?action=messaging">
                            <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                            Messages
                            <?php if ($unreadCount > 0): ?>
                                <span class="notification-badge"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a></li>
                        <li class="dropdown-divider"></li>
                        <li><a href="?action=disconnect">Se déconnecter</a></li>
                    </ul>
                <?php endif; ?>
            <?php else: ?>
                <ul class="dropdown-menu">
                    <li><a href="?action=login&return_url=<?= urlencode('?action=' . ($action ?? 'home')) ?>">Se connecter</a></li>
                    <li><a href="?action=register">S'inscrire</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="?action=admin_login" style="color: #6b21a8; font-weight: 600;">
                        <svg style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Admin
                    </a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
// Fonction utilitaire pour générer les URLs API
function apiUrl(endpoint) {
    const basePath = '<?= BASE_PATH ?>';
    const cleanEndpoint = endpoint.replace(/^\//, '');
    return basePath + 'assets/api/' + cleanEndpoint;
}

// Fonction utilitaire pour générer les URLs
function url(path) {
    const basePath = '<?= BASE_PATH ?>';
    const cleanPath = path.replace(/^\//, '');
    return basePath + cleanPath;
}
</script>

<!-- Load global search script with cache busting -->
<script src="<?= asset('js/global-search.js?v=' . time()) ?>"></script>
<!-- Load header dropdown script -->
<script src="<?= asset('js/header-dropdown.js?v=' . time()) ?>"></script>
