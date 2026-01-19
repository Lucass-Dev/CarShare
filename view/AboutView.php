<?php
class AboutView {
    public static function render() {
        ?>
        <link rel="stylesheet" href="./assets/styles/about.css">
        <main class="about-main">
            <div class="about-container">
                <!-- Header Section -->
                <div class="about-header">
                    <h1 class="about-title">Qui sommes-nous ?</h1>
                    <p class="about-subtitle">Quand la technologie devient le moteur du lien humain.</p>
                </div>

                <!-- Hero Section with Logo -->
                <div class="about-hero">
                    <div class="hero-content">
                        <div class="hero-text">
                            <p>Bienvenue sur <strong>Carshare</strong>. Si l'interface que vous voyez est simple, l'ambition qui l'anime est vaste. Carshare n'est pas seulement une plateforme de covoiturage, c'est la concrétisation d'une vision portée par notre maison mère : <strong>Hextech</strong>.</p>
                        </div>
                        <div class="hero-logo">
                            <img src="./assets/img/photo_hextech.jpeg" alt="Logo Hextech" class="hextech-logo">
                        </div>
                    </div>
                </div>

                <!-- DNA Section -->
                <div class="about-section dna-section">
                    <div class="section-icon">⚙️</div>
                    <h2>L'ADN Hextech : La magie du code</h2>
                    <p>Derrière Carshare se trouve l'équipe d'<strong>Hextech</strong>, une entreprise de développement web née d'une conviction forte : toute technologie suffisamment avancée doit simplifier la vie, presque comme par magie.</p>
                    <p>Inspirés par l'univers de l'innovation et du progrès (un clin d'œil assumé à la culture pop qui a bercé notre équipe), nous avons fondé Hextech avec l'idée de bâtir des ponts. Nous ne voyons pas le code comme des lignes de texte, mais comme des rouages complexes capables de créer du mouvement.</p>
                </div>

                <!-- Why Carshare Section -->
                <div class="about-section why-section">
                    <div class="section-icon">🚗</div>
                    <h2>Pourquoi Carshare ?</h2>
                    <p>Dans les fictions qui nous inspirent, la technologie "Hextech" permet de téléporter la matière et de rapprocher des mondes éloignés. Dans notre réalité, le moyen le plus efficace de rapprocher les gens, c'est la mobilité partagée.</p>
                    <p>Nous avons créé Carshare pour être votre <strong>portail moderne</strong>.</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">🧮</div>
                            <h3>Nos algorithmes sont notre ingénierie</h3>
                            <p>Ils optimisent vos trajets, calculent les meilleures routes et sécurisent vos échanges.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">✨</div>
                            <h3>Votre communauté est la magie</h3>
                            <p>C'est la rencontre entre un conducteur et un passager, transformant un trajet solitaire en une expérience partagée.</p>
                        </div>
                    </div>
                </div>

                <!-- Mission Section -->
                <div class="about-section mission-section">
                    <div class="section-icon">🎯</div>
                    <h2>Notre Mission : Le progrès accessible à tous</h2>
                    <p>Chez Hextech, nous croyons que le progrès ne vaut que s'il est partagé par tous. C'est pourquoi Carshare a été conçu pour être :</p>
                    
                    <div class="mission-grid">
                        <div class="mission-card">
                            <div class="mission-icon">💡</div>
                            <h3>Intuitif</h3>
                            <p>Une technologie de pointe cachée derrière une simplicité d'utilisation absolue.</p>
                        </div>
                        <div class="mission-card">
                            <div class="mission-icon">🌱</div>
                            <h3>Durable</h3>
                            <p>Utiliser la tech pour réduire notre empreinte carbone, un trajet à la fois.</p>
                        </div>
                        <div class="mission-card">
                            <div class="mission-icon">❤️</div>
                            <h3>Humain</h3>
                            <p>Parce qu'au bout du code, il y a vous.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="about-footer-section">
                    <div class="tagline">
                        <p class="tagline-text"><strong>Hextech</strong> développe le futur.</p>
                        <p class="tagline-text"><strong>Carshare</strong> vous y conduit.</p>
                    </div>
                </div>
            </div>
        </main>
        <?php
    }
}
