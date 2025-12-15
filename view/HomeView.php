<section class="hero">
    <div class="search-container">
        <form class="search-form">
            <input type="text" placeholder="Départ">
            <input type="text" placeholder="Arrivée">
            <input type="text" placeholder="Date">
            <input type="text" placeholder="Nombre de passagers">

            <button class="search-button">Rechercher</button>
        </form>

        <p style="margin-top:10px; font-size:14px; color:#555;">
            <?= htmlspecialchars($hello) ?>
        </p>
    </div>
</section>

<section class="reviews-section">
<?php for ($i = 0; $i < 4; $i++): ?>
    <div class="review-card">
        <h4>BlaBlaCar est noté</h4>
        <h3>Mauvais</h3>
        <p class="review-source">Basé sur 1 112 avis</p>

        <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </div>

        <div class="trustpilot-logo">
            <i class="fas fa-star"></i> Trustpilot
        </div>
    </div>
<?php endfor; ?>
</section>

<section class="features-section">
    <div class="feature-text">
        <h2>Partagez vos trajets simplement.</h2>
        <p>
            Économique, écologique et convivial, CarShare favorise le partage
            et réduit l’impact environnemental.
        </p>
    </div>

    <div class="feature-image">
        <img src="/CarShare/assets/img/ville.jpg" alt="Ville écologique">
    </div>
</section>
