<?php
class DailyCarShareView {
    public static function render() {
        ?>
        <link rel="stylesheet" href="./assets/styles/daily-carshare.css">
        
        <main class="daily-carshare-page">
            <div class="daily-hero">
                <h1>Covoiturage au quotidien</h1>
                <p class="hero-subtitle">Partagez vos trajets, réduisez vos coûts et contribuez à un avenir plus durable</p>
            </div>

            <div class="daily-container">
                <!-- Pourquoi publier sur CarShare -->
                <section class="daily-section">
                    <h2 class="section-title">Pourquoi publier vos trajets sur CarShare ?</h2>
                    
                    <div class="benefits-grid">
                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <h3>Sécurité garantie</h3>
                            <p>Tous les profils sont vérifiés et notre système d'avis permet à la communauté de s'auto-réguler. Voyagez en toute confiance.</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <h3>Publication immédiate</h3>
                            <p>Votre trajet est visible instantanément après publication. Pas d'attente, vos passagers peuvent réserver immédiatement.</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                            </div>
                            <h3>Communication facilitée</h3>
                            <p>Messagerie intégrée avec vos passagers pour coordonner facilement les détails de votre trajet.</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                                </svg>
                            </div>
                            <h3>Réduisez vos frais</h3>
                            <p>Partagez les coûts de carburant, péages et stationnement. Économisez jusqu'à 60% sur vos frais de transport.</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                    <polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                            </div>
                            <h3>Flexibilité totale</h3>
                            <p>Gérez vos trajets en toute liberté. Modifiez, annulez ou ajoutez des trajets selon vos besoins.</p>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                                </svg>
                            </div>
                            <h3>Impact écologique positif</h3>
                            <p>Contribuez activement à la réduction des émissions de CO₂ et à la préservation de notre planète.</p>
                        </div>
                    </div>
                </section>

                <!-- Impact écologique détaillé -->
                <section class="daily-section ecology-section">
                    <div class="section-header-large">
                        <div class="section-icon-large">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="section-title">Impact écologique</h2>
                            <p class="section-subtitle">Ensemble, faisons la différence pour notre planète</p>
                        </div>
                    </div>

                    <div class="ecology-content">
                        <p class="ecology-intro">En partageant votre trajet, vous contribuez activement à la protection de l'environnement. Chaque kilomètre partagé est un pas vers un avenir plus durable.</p>
                        
                        <div class="ecology-stats-large">
                            <div class="ecology-stat-large">
                                <span class="stat-value">-50%</span>
                                <span class="stat-label">d'émissions CO₂ par trajet partagé</span>
                            </div>
                            <div class="ecology-stat-large">
                                <span class="stat-value">2,5kg</span>
                                <span class="stat-label">CO₂ économisés en moyenne</span>
                            </div>
                            <div class="ecology-stat-large">
                                <span class="stat-value">3 millions</span>
                                <span class="stat-label">de tonnes de CO₂ évitées en France en 2025</span>
                            </div>
                        </div>

                        <div class="ecology-benefits-large">
                            <div class="benefit-item-large">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <div>
                                    <strong>Réduction de l'empreinte carbone</strong>
                                    <p>Diminuez votre impact personnel sur le réchauffement climatique</p>
                                </div>
                            </div>
                            <div class="benefit-item-large">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <div>
                                    <strong>Moins de véhicules sur la route</strong>
                                    <p>Réduisez les embouteillages et la pollution de l'air en ville</p>
                                </div>
                            </div>
                            <div class="benefit-item-large">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <div>
                                    <strong>Contribution au développement durable</strong>
                                    <p>Participez à la transition énergétique et écologique</p>
                                </div>
                            </div>
                            <div class="benefit-item-large">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                <div>
                                    <strong>Préservation des ressources</strong>
                                    <p>Optimisez l'utilisation des véhicules existants sans surproduction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bonnes pratiques -->
                <section class="daily-section practices-section">
                    <div class="section-header-large">
                        <div class="section-icon-large practices-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="section-title">Bonnes pratiques du covoiturage</h2>
                            <p class="section-subtitle">Conseils pour des trajets réussis et agréables</p>
                        </div>
                    </div>

                    <div class="practices-grid">
                        <div class="practice-card">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <h3>Points de rencontre accessibles</h3>
                            <p>Choisissez des lieux faciles à trouver (gare, parking, station-service) pour éviter les retards et confusions.</p>
                        </div>

                        <div class="practice-card">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <h3>Ponctualité et communication</h3>
                            <p>Soyez à l'heure et prévenez immédiatement en cas de retard ou d'imprévu via la messagerie.</p>
                        </div>

                        <div class="practice-card">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <h3>Réactivité aux demandes</h3>
                            <p>Acceptez ou refusez les réservations rapidement pour permettre aux passagers de s'organiser.</p>
                        </div>

                        <div class="practice-card">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <h3>Respect du nombre de places</h3>
                            <p>N'acceptez que le nombre de passagers annoncé pour garantir le confort de tous.</p>
                        </div>

                        <div class="practice-card">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <h3>Conditions de trajet claires</h3>
                            <p>Précisez vos préférences (musique, pauses, bagages) dès la publication pour éviter les malentendus.</p>
                        </div>

                        <div class="practice-card">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <h3>Respect et convivialité</h3>
                            <p>Créez une atmosphère agréable par votre politesse, votre ouverture d'esprit et votre bonne humeur.</p>
                        </div>
                    </div>
                </section>

                <!-- Call to Action -->
                <section class="daily-cta">
                    <h2>Prêt à partager vos trajets ?</h2>
                    <p>Rejoignez la communauté CarShare et commencez à publier vos trajets dès aujourd'hui</p>
                    <a href="/CarShare/index.php?action=create_trip" class="cta-button">
                        <span>Publier un trajet</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                </section>
            </div>
        </main>
        <?php
    }
}
