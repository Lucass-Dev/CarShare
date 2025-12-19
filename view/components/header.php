<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? 'Utilisateur') : '';
?>
<header>
    <div class="logo">
<<<<<<< Updated upstream
        <a href="/CarShare/index.php?action=home">
            <img 
                src="/CarShare/assets/img/carshare.png" 
                alt="CarShare Logo"
                class="logo-img"
            >
=======
        <a href="index.php" class="home-link">
            <img src="./assets/img/carshare.png" alt="CarShare Logo">
            
>>>>>>> Stashed changes
        </a>
    </div>

    <div class="header-icons">
<<<<<<< Updated upstream
        <a href="/CarShare/index.php?action=search" title="Rechercher">🔍</a>
        <?php if ($isLoggedIn): ?>
            <a href="/CarShare/index.php?action=profile" title="Mon profil">👤 <?= htmlspecialchars($userName) ?></a>
        <?php else: ?>
            <a href="/CarShare/index.php?action=login" title="Connexion">👤</a>
        <?php endif; ?>
=======
        <a href="?controller=trip&action=search" title="Rechercher" class="icon">🔍</a>
        <div class="dropdown">
            <a href="?controller=<?php echo isset($_SESSION["logged"])  && $_SESSION["logged"] ? "profile":"login"?>" class="upp">
                <img src="./assets/img/avatar.jpg" alt="Ma photo de profile" class="icon">
            </a>
            <?php
                if (isset($_SESSION['logged']) && $_SESSION['logged'] !=='') {
                    ?>
                    <ul class="hidden">
                        <li><a href="?action=profile">Profile</a></li>
                        <li><a href="?action=history">Historique</a></li>
                        <li><a href="?action=mp">Messages</a></li>
                        <li><a href="?action=disconnect">Se déconnecter</a></li>
                    </ul>
                    <?php
                }
            ?>
        </div>
        
>>>>>>> Stashed changes
    </div>
</header>
