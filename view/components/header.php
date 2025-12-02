<header>
    <div class="logo">
        <a href="?action=home" class="home-link">
            <img src="/assets/img/photo_hextech.jpeg" alt="CarShare Logo">
            
        </a>
    </div>

    <span class="site-name">CarShare</span>

    <div class="header-icons">
        <a href="?action=search" title="Rechercher" class="icon">🔍</a>
        <div class="dropdown">
            <a href="?action=<?php echo isset($_SESSION["logged"])  && $_SESSION["logged"] ? "profile":"login"?>" class="upp">
                <img src="../../assets/upp/<?php echo $profilePicturePath && $profilePicturePath != '' ? $profilePicturePath : 'default_pp.svg'?>" alt="Ma photo de profile" class="icon">
            </a>
            <?php
                if (isset($_SESSION['logged']) && $_SESSION['logged'] !=='') {
                    ?>
                    <ul class="hidden">
                        <li><a href="?action=profile">Profile</a></li>
                        <li><a href="?action=mp">Messages</a></li>
                        <li><a href="?action=disconnect">Se déconnecter</a></li>
                    </ul>
                    <?php
                }
            ?>
        </div>
        
    </div>
</header>