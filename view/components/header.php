<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? 'Utilisateur') : '';
?>
<header>
    <div class="logo">
        <a href="/CarShare/index.php?action=home">
            <img 
                src="/CarShare/assets/img/carshare.png" 
                alt="CarShare Logo"
                class="logo-img"
            >
        </a>
    </div>

    <div class="header-icons">
        <a href="/CarShare/index.php?action=search" title="Rechercher">🔍</a>
        <?php if ($isLoggedIn): ?>
            <a href="/CarShare/index.php?action=profile" title="Mon profil">👤 <?= htmlspecialchars($userName) ?></a>
        <?php else: ?>
            <a href="/CarShare/index.php?action=login" title="Connexion">👤</a>
        <?php endif; ?>
    </div>
</header>
