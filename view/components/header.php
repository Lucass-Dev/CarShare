<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['logged']) && $_SESSION['logged'];
$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? 'Utilisateur') : '';
$userId = $_SESSION['user_id'] ?? null;
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
        <a href="index.php?action=contact" class="nav-link">Contact</a>
    </nav>

    <!-- Compact Search Bar -->
    <div class="header-search">
        <input type="text" 
               id="global-search" 
               placeholder="Rechercher un utilisateur..." 
               autocomplete="off">
        <button type="button" class="search-toggle">
            <span class="search-icon">🔍</span>
        </button>
        <div class="search-results" id="search-results"></div>
    </div>

    <div class="header-icons">
        <div class="dropdown">
            <button class="dropdown-toggle upp" type="button">
                <img src="./assets/img/avatar.jpg" alt="Photo de profil" class="icon">
            </button>
            <?php if ($isLoggedIn): ?>
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
                    </a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="?action=disconnect">Se déconnecter</a></li>
                </ul>
            <?php else: ?>
                <ul class="dropdown-menu">
                    <li><a href="?action=login">Se connecter</a></li>
                    <li><a href="?action=register">S'inscrire</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Load global search script -->
<script src="/CarShare/assets/js/global-search.js"></script>
<!-- Load header dropdown script -->
<script src="/CarShare/assets/js/header-dropdown.js"></script>
