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
        <a href="index.php?action=search" class="nav-link">Voyager</a>
        <a href="index.php?action=offers" class="nav-link">Offres</a>
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
                    <li><a href="?action=history">Historique</a></li>
                    <li><a href="?action=my_bookings">Mes réservations</a></li>
                    <li><a href="?action=messaging">Messages</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a href="?action=create_trip">Publier un trajet</a></li>
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
