<?php
class FAQView {
    public static function render() {
        ?>
        <link rel="stylesheet" href="./assets/styles/FAQ.css">
        <main>
            <h1>Foire Aux Questions</h1>

            <h2>Pourquoi publier sur CarShare ?</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq-pub1" class="faq-toggle">
                <label for="faq-pub1" class="faq-question">Quels sont les avantages de publier un trajet sur CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">
                    <p>Publier un trajet sur CarShare vous permet de :</p>
                    <ul>
                        <li><strong>Réduire vos coûts de transport</strong> : Partagez les frais de carburant, péages et parking avec vos passagers</li>
                        <li><strong>Voyager en bonne compagnie</strong> : Rencontrez de nouvelles personnes et rendez vos trajets plus agréables</li>
                        <li><strong>Contribuer à l'environnement</strong> : Chaque trajet partagé réduit les émissions de CO₂ jusqu'à 50%</li>
                        <li><strong>Accéder à une communauté fiable</strong> : Tous les profils sont vérifiés et les avis utilisateurs garantissent la sécurité</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq-pub2" class="faq-toggle">
                <label for="faq-pub2" class="faq-question">Ma publication est-elle visible immédiatement ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, votre trajet est publié instantanément dès validation du formulaire. Il apparaît immédiatement dans les résultats de recherche et les passagers peuvent le réserver sans délai. Vous recevrez une notification pour chaque demande de réservation.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq-pub3" class="faq-toggle">
                <label for="faq-pub3" class="faq-question">Comment communiquer avec mes passagers ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">CarShare intègre une messagerie privée qui vous permet d'échanger avec vos passagers directement sur la plateforme. Vous pouvez ainsi coordonner les points de rencontre, préciser les horaires ou partager des informations pratiques avant le départ, le tout en toute sécurité.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq-pub4" class="faq-toggle">
                <label for="faq-pub4" class="faq-question">Combien puis-je facturer pour mon trajet ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">CarShare fonctionne sur le principe du partage des frais, pas du profit. Le prix que vous fixez doit correspondre à une participation équitable aux coûts réels du trajet (carburant, péages, usure du véhicule). Nous recommandons de calculer environ 0,08€ à 0,12€ par kilomètre et par passager. Le prix maximum autorisé est de 250€ par personne.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq-pub5" class="faq-toggle">
                <label for="faq-pub5" class="faq-question">Puis-je gérer mes trajets et réservations facilement ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Absolument ! Votre tableau de bord personnel vous offre une vue complète sur tous vos trajets publiés, les réservations en cours et l'historique. Vous pouvez modifier les détails de vos trajets, accepter ou refuser des demandes, et annuler un trajet si nécessaire (sous réserve de respecter les conditions d'annulation).</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq-pub6" class="faq-toggle">
                <label for="faq-pub6" class="faq-question">Quel est l'impact écologique du covoiturage ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Le covoiturage a un impact environnemental significatif. En moyenne, chaque trajet partagé permet de réduire les émissions de CO₂ de 50% et économise environ 2,5 kg de CO₂ par trajet. En partageant votre véhicule, vous contribuez directement à diminuer le nombre de voitures sur les routes et à préserver notre planète pour les générations futures.</div>
            </div>

            <h2>Fonctionnement général</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq1" class="faq-toggle">
                <label for="faq1" class="faq-question">Comment fonctionne CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">CarShare est une plateforme de covoiturage qui met en relation des conducteurs proposant des places libres avec des passagers recherchant un trajet. Les conducteurs publient leurs itinéraires avec la date, l'heure et le prix. Les passagers recherchent un trajet correspondant à leurs besoins et réservent une ou plusieurs places. Le paiement s'effectue de manière sécurisée via notre plateforme, et une messagerie intégrée permet aux utilisateurs de communiquer avant et pendant le voyage.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq2" class="faq-toggle">
                <label for="faq2" class="faq-question">Faut-il créer un compte pour utiliser CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, la création d'un compte est obligatoire pour proposer ou réserver un trajet sur CarShare. Cette exigence garantit la sécurité de tous les utilisateurs en permettant la vérification des profils et la traçabilité des transactions. L'inscription est gratuite, rapide et sécurisée. Vous aurez simplement besoin d'une adresse email valide et de créer un mot de passe robuste.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq3" class="faq-toggle">
                <label for="faq3" class="faq-question">Le service est-il payant ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">L'inscription et l'utilisation de CarShare sont entièrement gratuites. Aucun abonnement ni frais cachés ne vous seront demandés. Seule la participation aux frais du trajet, fixée par le conducteur, est à régler lors de la réservation. Cette somme correspond au partage équitable des coûts de transport (carburant, péages, usure du véhicule) et ne constitue pas un profit pour le conducteur.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq4" class="faq-toggle">
                <label for="faq4" class="faq-question">Comment contacter un conducteur ou un passager ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Une fois une réservation confirmée, une messagerie privée et sécurisée est automatiquement activée entre le conducteur et les passagers. Cette messagerie intégrée vous permet d'échanger tous les détails pratiques : point de rencontre précis, heure exacte de départ, numéro de téléphone si souhaité, etc. Toutes les communications restent tracées sur la plateforme pour votre sécurité.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq5" class="faq-toggle">
                <label for="faq5" class="faq-question">Puis-je voyager avec des bagages ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, vous pouvez généralement voyager avec des bagages. Cependant, l'espace disponible varie selon le véhicule et le nombre de passagers. Nous vous recommandons vivement de préciser le volume de vos bagages dans la messagerie avant la réservation définitive. Certains conducteurs indiquent explicitement dans leur annonce s'ils acceptent les bagages volumineux (valises, équipements sportifs, etc.).</div>
            </div>

            <h2>Réservations et trajets</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq6" class="faq-toggle">
                <label for="faq6" class="faq-question">Comment réserver une place ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Pour réserver une place, commencez par rechercher un trajet en indiquant votre ville de départ, votre destination et la date souhaitée. Parcourez les résultats et sélectionnez le trajet qui vous convient. Cliquez sur "Réserver", choisissez le nombre de places nécessaires, puis procédez au paiement sécurisé par carte bancaire. Vous recevrez immédiatement une confirmation par email avec les coordonnées du conducteur.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq7" class="faq-toggle">
                <label for="faq7" class="faq-question">Comment proposer un trajet ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Pour publier un trajet, connectez-vous à votre compte et cliquez sur "Publier un trajet" dans le menu principal. Remplissez le formulaire en indiquant votre point de départ (ville et adresse précise), votre destination, la date et l'heure de départ. Fixez ensuite le prix par passager (participation aux frais) et le nombre de places disponibles. Vous pouvez également préciser vos préférences : acceptez-vous les animaux, les fumeurs, les bagages volumineux ? Une fois le formulaire validé, votre trajet est instantanément visible par tous les utilisateurs.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq8" class="faq-toggle">
                <label for="faq8" class="faq-question">Comment annuler une réservation ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Pour annuler une réservation, rendez-vous dans votre profil, section "Mes réservations". Sélectionnez le trajet concerné et cliquez sur "Annuler la réservation". L'annulation est possible jusqu'à 24 heures avant le départ sans pénalité. En cas d'annulation moins de 24h avant le départ ou de non-présentation, aucun remboursement ne sera effectué afin de respecter l'engagement du conducteur. En cas de circonstances exceptionnelles, contactez notre service client.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq9" class="faq-toggle">
                <label for="faq9" class="faq-question">Que se passe-t-il si le conducteur ne vient pas ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Si le conducteur ne se présente pas au point de rencontre ou annule le trajet de manière abusive, vous êtes intégralement remboursé. Contactez immédiatement notre support via la page "Contact" ou la section "Aide" en précisant le numéro de réservation. Nous traiterons votre demande en priorité et procéderons au remboursement dans les 48 heures. Le conducteur fautif fera l'objet de sanctions pouvant aller jusqu'à la suspension de son compte.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq10-bis" class="faq-toggle">
                <label for="faq10-bis" class="faq-question">Puis-je modifier un trajet après publication ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, tant qu'aucune réservation n'a été acceptée, vous pouvez modifier librement tous les détails de votre trajet (horaires, prix, nombre de places, options). Si des passagers ont déjà réservé, les modifications sont limitées pour respecter leur engagement. Dans ce cas, vous devrez annuler le trajet actuel et en republier un nouveau, ou contacter directement vos passagers via la messagerie pour convenir des changements.</div>
            </div>

            <h2>Sécurité et confiance</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq10" class="faq-toggle">
                <label for="faq10" class="faq-question">CarShare est-il sécurisé ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">La sécurité est notre priorité absolue. Tous les profils utilisateurs sont vérifiés lors de l'inscription (validation email, vérification téléphonique). Notre système d'avis et de notes permet à la communauté d'évaluer chaque membre après chaque trajet. Les paiements sont 100% sécurisés et transitent par des prestataires certifiés PCI-DSS. De plus, notre équipe de modération surveille l'activité de la plateforme 24h/24 et peut intervenir rapidement en cas de comportement suspect.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq11" class="faq-toggle">
                <label for="faq11" class="faq-question">Comment signaler un comportement inapproprié ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Si vous êtes témoin ou victime d'un comportement inapproprié (langage offensant, harcèlement, non-respect des règles, fraude), vous pouvez signaler l'utilisateur directement depuis son profil en cliquant sur le bouton "Signaler". Vous pouvez également contacter notre équipe support via la page "Contact" en détaillant la situation. Tous les signalements sont traités de manière confidentielle et peuvent entraîner des sanctions immédiates (avertissement, suspension temporaire, bannissement définitif).</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq12" class="faq-toggle">
                <label for="faq12" class="faq-question">Les paiements sont-ils sécurisés ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Absolument. Toutes les transactions financières sur CarShare passent par des prestataires de paiement certifiés et conformes aux normes bancaires internationales (PCI-DSS). Vos données bancaires sont cryptées et ne sont jamais stockées sur nos serveurs. Le paiement est sécurisé par 3D Secure pour une protection maximale contre la fraude. Le conducteur reçoit son paiement uniquement après la réalisation effective du trajet.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq13-bis" class="faq-toggle">
                <label for="faq13-bis" class="faq-question">Que faire en cas de litige avec un autre utilisateur ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">En cas de désaccord ou de conflit avec un conducteur ou un passager, contactez immédiatement notre service de médiation via la page "Contact". Notre équipe analysera la situation de manière impartiale en s'appuyant sur l'historique des messages et les preuves fournies. Nous nous engageons à trouver une solution équitable dans les meilleurs délais. En cas de préjudice avéré, des compensations peuvent être accordées.</div>
            </div>

            <h2>Compte et assistance</h2>

            <div class="faq-item">
                <input type="checkbox" id="faq13" class="faq-toggle">
                <label for="faq13" class="faq-question">J'ai oublié mon mot de passe, que faire ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Sur la page de connexion, cliquez sur le lien "Mot de passe oublié ?" situé sous le formulaire. Saisissez l'adresse email associée à votre compte CarShare. Vous recevrez immédiatement un email contenant un lien sécurisé valable 1 heure pour réinitialiser votre mot de passe. Si vous ne recevez pas l'email, vérifiez vos spams ou contactez notre support. Pour votre sécurité, choisissez un nouveau mot de passe robuste (12 caractères minimum avec majuscules, minuscules, chiffres et symboles).</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq14" class="faq-toggle">
                <label for="faq14" class="faq-question">Comment modifier mes informations personnelles ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Pour modifier vos informations personnelles, connectez-vous à votre compte et accédez à votre profil via le menu utilisateur. Vous pouvez y mettre à jour votre nom, prénom, numéro de téléphone, photo de profil et préférences de voyage. Pour modifier votre adresse email ou votre mot de passe, rendez-vous dans les paramètres de sécurité. Certaines modifications sensibles (email, mot de passe) nécessitent une confirmation par email pour garantir la sécurité de votre compte.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq15" class="faq-toggle">
                <label for="faq15" class="faq-question">Comment contacter le support CarShare ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Notre équipe support est disponible pour vous assister. Vous pouvez nous contacter de plusieurs façons : via le formulaire de contact accessible depuis la page "Contact" du site, par email à l'adresse carshare.cov@gmail.com, ou directement depuis la section "Aide" de votre profil. Nous nous engageons à répondre à toutes les demandes dans un délai maximum de 24 heures ouvrées. Pour les urgences (trajet imminent, problème de sécurité), précisez-le dans l'objet de votre message.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq16" class="faq-toggle">
                <label for="faq16" class="faq-question">Puis-je supprimer mon compte ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Oui, vous pouvez supprimer votre compte CarShare à tout moment depuis les paramètres de votre profil, section "Confidentialité et données". Attention : cette action est irréversible. Toutes vos données personnelles seront définitivement supprimées conformément au RGPD. Assurez-vous d'avoir honoré tous vos engagements en cours (trajets réservés ou publiés) avant la suppression. En cas de trajet à venir, vous devrez d'abord les annuler pour pouvoir supprimer votre compte.</div>
            </div>

            <div class="faq-item">
                <input type="checkbox" id="faq17" class="faq-toggle">
                <label for="faq17" class="faq-question">Comment fonctionne le système d'avis et de notation ? <span class="faq-icon">+</span></label>
                <div class="faq-answer">Après chaque trajet, conducteurs et passagers peuvent s'évaluer mutuellement en attribuant une note de 1 à 5 étoiles et en laissant un commentaire. Ces avis apparaissent sur les profils et aident la communauté à identifier les membres fiables. La note moyenne est calculée automatiquement et mise à jour après chaque évaluation. Les avis doivent rester respectueux et objectifs ; tout avis diffamatoire ou mensonger peut être signalé et sera modéré par notre équipe. Un profil avec une note élevée et de nombreux avis positifs inspire naturellement plus de confiance.</div>
            </div>
        </main>

        <script>
            const items = document.querySelectorAll('.faq-item');
            items.forEach(item => {
                const toggle = item.querySelector('.faq-toggle');
                toggle.addEventListener('change', () => {
                    const icon = item.querySelector('.faq-icon');
                    icon.textContent = toggle.checked ? '–' : '+';
                });
            });
        </script>
        <?php
    }
}