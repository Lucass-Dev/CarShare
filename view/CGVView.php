<?php
class CGVView {
    public static function render() {
        ?>
        <link rel="stylesheet" href="./assets/styles/legal.css">
        <div class="legal-container">
            <div class="legal-header">
                <h1>Conditions Générales de Vente (CGV)</h1>
                <p class="legal-date">Date de dernière mise à jour : 6 janvier 2026</p>
            </div>

                <div class="legal-section">
                    <h2>1. Mentions Légales</h2>
                    <p>Le site Carshare.fr est édité par la société Hextech, SAS au capital de 3000 €, dont le siège social est situé au 10 rue de Vanves, 92130 Issy-Les-Moulineaux, immatriculée au RCS de Nanterre sous le numéro 123 456 789 101112.</p>
                    <div class="contact-info">
                        <p><strong>Contact :</strong> carshare.cov@gmail.com</p>
                        <p><strong>TVA :</strong> Non applicable (Article 293 B du CGI)</p>
                    </div>
                </div>

                <div class="legal-section">
                    <h2>2. Objet et Description des Services</h2>
                    <p>Hextech exploite une plateforme de mise en relation (intermédiation numérique) facilitant le covoiturage privé entre particuliers. Les services incluent :</p>
                    <ul>
                        <li>La publication et la recherche d'annonces de trajets.</li>
                        <li>La mise en relation via une messagerie interne sécurisée.</li>
                        <li>La réservation de places et la gestion des paiements.</li>
                        <li>Un système d'évaluation et de notation entre membres.</li>
                    </ul>
                    <div class="important-notice">
                        <p><strong>Important :</strong> Hextech n'est pas une société de transport. Son rôle se limite à la fourniture de l'infrastructure technologique. Les conducteurs agissent sous leur propre responsabilité.</p>
                    </div>
                </div>

                <div class="legal-section">
                    <h2>3. Conditions Financières</h2>
                    <h3>Prix du trajet</h3>
                    <p>Fixé librement par le conducteur, il constitue une participation aux frais et ne doit en aucun cas générer un profit. Il doit respecter le barème kilométrique fiscal en vigueur.</p>
                    
                    <h3>Frais de service</h3>
                    <p>Pour chaque réservation confirmée, Hextech perçoit des frais de service (ex: 10% TTC du montant du trajet ou un forfait fixe par place) pour assurer le fonctionnement de la plateforme.</p>
                    
                    <h3>Paiement</h3>
                    <p>Le règlement s'effectue par carte bancaire (Visa, Mastercard, CB) via le prestataire sécurisé Stripe/Mangopay. Les fonds sont conservés jusqu'à la réalisation effective du trajet.</p>
                </div>

                <div class="legal-section">
                    <h2>4. Modalités d'Exécution (Livraison du service)</h2>
                    <p>Le service est considéré comme "fourni" au moment où le trajet a lieu, aux dates, heures et lieux convenus entre le conducteur et le passager via l'annonce publiée sur Carshare.fr. La zone de couverture principale est la France métropolitaine.</p>
                </div>

                <div class="legal-section">
                    <h2>5. Absence de Droit de Rétractation</h2>
                    <p>Conformément à l'Article L221-28 12° du Code de la consommation, le droit de rétractation de 14 jours ne s'applique pas aux prestations de services de transport de passagers devant être fournis à une date ou à une période déterminée. Toutefois, Hextech propose une politique d'annulation spécifique (ex : remboursement total si annulation plus de 24h avant le départ, frais retenus au-delà).</p>
                </div>

                <div class="legal-section">
                    <h2>6. Garanties et Responsabilités</h2>
                    <h3>Garantie légale</h3>
                    <p>Hextech garantit la conformité de sa plateforme numérique conformément aux articles L224-25-12 et suivants du Code de la consommation.</p>
                    
                    <h3>Assurance</h3>
                    <p>Hextech est titulaire d'une assurance Responsabilité Civile Professionnelle.</p>
                    
                    <h3>Obligation des conducteurs</h3>
                    <p>Le conducteur s'engage à posséder un permis de conduire valide et une assurance automobile couvrant le transport de passagers dans le cadre d'un partage de frais.</p>
                </div>

                <div class="legal-section">
                    <h2>7. Médiation et Litiges</h2>
                    <p>En cas de litige non résolu par le service client (carshare.cov@gmail.com), l'Utilisateur peut recourir gratuitement au médiateur de la consommation suivant : <strong>AME Conso</strong></p>
                    <p>Les présentes conditions sont régies par la loi française. En cas de contestation, et à défaut d'accord amiable, les tribunaux français seront seuls compétents.</p>
                </div>
            </div>
        <?php
    }
}
