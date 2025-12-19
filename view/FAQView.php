<?php
class FAQView {
    public static function render() {
        ?>
        <link rel="stylesheet" href="./assets/styles/FAQ.css">
        <main>
            <h1>Foire Aux Questions</h1>

            <h2>Fonctionnement général</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq1" class="faq-toggle">
                <label for="faq1" class="faq-question">Comment fonctionne CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">CarShare met en relation conducteurs et passagers pour partager un trajet simplement et à moindre coût.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq2" class="faq-toggle">
                <label for="faq2" class="faq-question">Faut-il créer un compte pour utiliser CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, un compte est nécessaire pour proposer ou réserver un trajet.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq3" class="faq-toggle">
                <label for="faq3" class="faq-question">Le service est-il payant ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">L'inscription est gratuite. Seuls les trajets comportent une participation aux frais du conducteur.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq4" class="faq-toggle">
                <label for="faq4" class="faq-question">Comment contacter un conducteur ou un passager ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Une messagerie interne est disponible après la réservation.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq5" class="faq-toggle">
                <label for="faq5" class="faq-question">Puis-je voyager avec des bagages ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, mais il est conseillé de prévenir le conducteur pour vérifier la place disponible.</div>
            </div>

            <h2>Réservations et trajets</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq6" class="faq-toggle">
                <label for="faq6" class="faq-question">Comment réserver une place ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Cherchez un trajet, cliquez sur "Réserver" et validez le paiement sécurisé.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq7" class="faq-toggle">
                <label for="faq7" class="faq-question">Comment proposer un trajet ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Rendez-vous dans "Publier un trajet", puis entrez votre itinéraire, date, prix, nombre de places disponibles et détails (optionnel).</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq8" class="faq-toggle">
                <label for="faq8" class="faq-question">Comment annuler une réservation ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">L'annulation est possible depuis votre profil, jusqu'à 24h avant le départ.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq9" class="faq-toggle">
                <label for="faq9" class="faq-question">Que se passe-t-il si le conducteur ne vient pas ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Contactez le support CarShare pour signaler le problème et obtenir un remboursement.</div>
            </div>

            <h2>Sécurité et confiance</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq10" class="faq-toggle">
                <label for="faq10" class="faq-question">CarShare est-il sécurisé ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, les profils sont vérifiés et les avis utilisateurs garantissent la fiabilité.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq11" class="faq-toggle">
                <label for="faq11" class="faq-question">Comment signaler un comportement inapproprié ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Utilisez le bouton "Signaler" ou contactez notre support.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq12" class="faq-toggle">
                <label for="faq12" class="faq-question">Les paiements sont-ils sécurisés ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, toutes les transactions passent par un système de paiement certifié.</div>
            </div>

            <h2>Compte et assistance</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq13" class="faq-toggle">
                <label for="faq13" class="faq-question">J'ai oublié mon mot de passe, que faire ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Cliquez sur "Mot de passe oublié" à la connexion pour le réinitialiser.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq14" class="faq-toggle">
                <label for="faq14" class="faq-question">Comment modifier mes informations personnelles ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Depuis votre profil, vous pouvez modifier vos données à tout moment.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq15" class="faq-toggle">
                <label for="faq15" class="faq-question">Comment contacter le support CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Via la page "Contact" ou directement depuis la section "Aide".</div>
            </div>
        </main>

        <script>
            const items = document.querySelectorAll('.faq-item');
            items.forEach(item => {
                item.addEventListener('click', () => {
                    item.classList.toggle('active');
                    const icon = item.querySelector('.faq-icon');
                    icon.textContent = item.classList.contains('active') ? '–' : '+';
                });
            });
        </script>
        <?php
    }
}