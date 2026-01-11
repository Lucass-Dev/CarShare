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
            <a href="/index.php?controller=profile&action=login" class="upp">
                <?php
                    if (!file_exists("../../assets/upp/".$profilePicturePath)) {
                        $profilePicturePath = 'avatar.jpg';
                    }
                ?>
                <img src="../../assets/upp/<?php echo $profilePicturePath?>" alt="Ma photo de profile" class="icon">
            </a>
            <?php
                if (isset($_SESSION['logged']) && $_SESSION['logged'] !=='') {
                    ?>
                    <ul class="hidden">
                        <li><a href="?controller=profile&action=show">Profile</a></li>
                        <li><a href="?controller=profile&action=mp">Messages</a></li>
                        <li><a href="?controller=profile&action=disconnect">Se déconnecter</a></li>
                    </ul>
                    <?php
                }
            ?>
        </div>
        
    </div>
</header>
