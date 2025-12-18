<?php
    class HomeView{
        public static function render(){
            ?>
            <link rel="stylesheet" href="./assets/styles/home.css">
            <section class="hero">
                <div class="search-container">
                    <form class="search-form" action="">
                        <input hidden name="controller" value="trip">
                        <input hidden name="action" value="search">
                        <input type="hidden" id="form_start_input" name="form_start_input" value="">
                        <input type="hidden" id="form_end_input" name="form_end_input" value="">

                        <div class="field">
                            <label for="start_place">Start place</label>
                            <input type="text" id="start_place" placeholder="City or address" required value="">

                            <div id="start-suggestion-box">

                            </div>
                        </div>

                        <div class="field">
                            <label for="end_place">End place</label>
                            <input type="text" id="end_place" placeholder="City or address" value="">
                            <div id="end-suggestion-box">

                            </div>
                        </div>

                        <button class="search-button">Rechercher</button>
                    </form>

                    <p style="margin-top:10px; font-size:14px; color:#555;">
                        Trouvez des trajets abordables et partagez vos voyages avec CarShare !
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

                </div>
            </section>
            <?php
        }
    }
?>
