<header>
    <div class="logo">
        <a href="?controller=home" class="home-link">
            <img src="/assets/img/photo_hextech.jpeg" alt="CarShare Logo">
            
        </a>
    </div>

    <span class="site-name">CarShare</span>

    <div class="header-icons">
        <a href="?controller=trip&action=search" title="Rechercher" class="icon">🔍</a>
        <div class="dropdown">
            <a href="?controller=<?php echo isset($_SESSION["logged"])  && $_SESSION["logged"] ? "profile":"login"?>" class="upp">
                <img src="../../assets/upp/<?php echo $profilePicturePath && $profilePicturePath != '' ? $profilePicturePath : 'default_pp.svg'?>" alt="Ma photo de profile" class="icon">
            </a>
            <?php
                if (isset($_SESSION['logged']) && $_SESSION['logged'] !=='') {
                    ?>
                    <ul class="hidden">
                        <li><a href="?controller=profile">Profile</a></li>
                        <li><a href="?controller=mp">Messages</a></li>
                        <li><a href="?controller=disconnect">Se déconnecter</a></li>
                    </ul>
                    <?php
                }
            ?>
        </div>
        
    </div>
</header>
