<header>
    <div class="logo">
        <a href="?action=home" class="home-link">
            <img src="/assets/img/photo_hextech.jpeg" alt="CarShare Logo">
            
        </a>
    </div>

    <span>CarShare</span>

    <div class="header-icons">
        <a href="?action=search" title="Rechercher">🔍</a>
        <a href="?action=<?php echo isset($_SESSION["logged"])  && $_SESSION["logged"] ? "profile":"login"?>" title="Mon compte">
            <img src="../../assets/upp/<?php echo $profilePicturePath && $profilePicturePath != '' ? $profilePicturePath : 'default_pp.svg'?>" alt="Ma photo de profile" class="icon">
        </a>
        <div class="">

        </div>
    </div>
</header>