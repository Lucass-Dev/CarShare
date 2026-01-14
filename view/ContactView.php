<?php

class ContactView {
    public function display($success, $error) {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Contact - CarShare</title>
            <link rel="stylesheet" href="./assets/styles/header.css">
            <link rel="stylesheet" href="./assets/styles/footer.css">
            <link rel="stylesheet" href="./assets/styles/contact.css">
            <link rel="stylesheet" href="./assets/styles/design-improvements.css">
        </head>
        <body>
            <?php require_once __DIR__ . '/components/header.php'; ?>

            <main>
                <div class="contact-container">
                    <div class="contact-header">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                        </div>
                        <h1>Contactez-nous</h1>
                        <p class="contact-subtitle">Une question, une suggestion ou besoin d'aide ? Notre équipe est là pour vous accompagner dans votre expérience de covoiturage.</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            <span>Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.</span>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span>
                                <?php
                                switch($error) {
                                    case 'missing_fields':
                                        echo 'Veuillez remplir tous les champs obligatoires.';
                                        break;
                                    case 'invalid_email':
                                        echo 'Veuillez entrer une adresse email valide.';
                                        break;
                                    case 'message_too_short':
                                        echo 'Votre message doit contenir au moins 10 caractères.';
                                        break;
                                    case 'db_error':
                                        echo 'Une erreur est survenue. Veuillez réessayer.';
                                        break;
                                    default:
                                        echo 'Une erreur est survenue.';
                                }
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="contact-content">
                        <div class="contact-form-wrapper">
                            <form action="index.php?action=contact_submit" method="POST" class="contact-form">
                                <div class="form-group">
                                    <label for="name">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        Nom complet
                                    </label>
                                    <input type="text" id="name" name="name" required placeholder="Ex: Jean Dupont">
                                </div>

                                <div class="form-group">
                                    <label for="email">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                            <polyline points="22,6 12,13 2,6"/>
                                        </svg>
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email" required placeholder="exemple@email.com">
                                </div>

                                <div class="form-group">
                                    <label for="subject">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="16" x2="12" y2="12"/>
                                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                                        </svg>
                                        Sujet
                                    </label>
                                    <select id="subject" name="subject" required>
                                        <option value="">Choisissez un sujet</option>
                                        <option value="question">Question générale</option>
                                        <option value="reservation">Problème de réservation</option>
                                        <option value="payment">Question sur les paiements</option>
                                        <option value="account">Problème de compte</option>
                                        <option value="safety">Sécurité et confiance</option>
                                        <option value="suggestion">Suggestion d'amélioration</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="message">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                        </svg>
                                        Message
                                    </label>
                                    <textarea id="message" name="message" rows="6" required placeholder="Décrivez votre demande en détail..."></textarea>
                                </div>

                                <button type="submit" class="submit-btn">
                                    <span>Envoyer le message</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="22" y1="2" x2="11" y2="13"/>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="contact-info">
                            <div class="info-card">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                </div>
                                <h3>Réponse rapide</h3>
                                <p>Nous répondons généralement sous 24h ouvrées</p>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </div>
                                <h3>Email direct</h3>
                                <p><a href="mailto:support@carshare.com">support@carshare.com</a></p>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                </div>
                                <h3>Communauté</h3>
                                <p>Rejoignez notre communauté de covoitureurs engagés</p>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                </div>
                                <h3>FAQ</h3>
                                <p><a href="index.php?action=faq">Consultez notre foire aux questions</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php require_once __DIR__ . '/components/footer.php'; ?>
        </body>
        </html>
        <?php
    }
}
