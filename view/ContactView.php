<?php

class ContactView {
    public function display($success, $error) {
        ?>
        <link rel="stylesheet" href="./assets/styles/contact-modern.css">
        
        <main>
            <div class="contact-container">
                    <!-- Hero Section -->
                    <div class="contact-hero">
                        <div class="contact-hero-content">
                            <div class="contact-hero-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4M12 8h.01"/>
                                </svg>
                                <span>Support client</span>
                            </div>
                            <h1 class="contact-hero-title">Une question ?<br>Nous sommes là</h1>
                            <p class="contact-hero-subtitle">Notre équipe dédiée vous accompagne dans votre expérience de covoiturage. Posez-nous vos questions, nous vous répondons rapidement.</p>
                            
                            <div class="contact-stats">
                                <div class="stat-item">
                                    <div class="stat-number">24h</div>
                                    <div class="stat-label">Temps de réponse</div>
                                </div>
                                <div class="stat-divider"></div>
                                <div class="stat-item">
                                    <div class="stat-number">98%</div>
                                    <div class="stat-label">Satisfaction client</div>
                                </div>
                                <div class="stat-divider"></div>
                                <div class="stat-item">
                                    <div class="stat-number">7j/7</div>
                                    <div class="stat-label">Disponibilité</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-hero-visual">
                            <div class="visual-decoration visual-decoration-1"></div>
                            <div class="visual-decoration visual-decoration-2"></div>
                            <div class="visual-decoration visual-decoration-3"></div>
                        </div>
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
                            <div class="form-header">
                                <div class="form-header-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2>Envoyez-nous un message</h2>
                                    <p>Remplissez le formulaire ci-dessous et nous vous répondrons rapidement</p>
                                </div>
                            </div>

                            <form action="index.php?action=contact_submit" method="POST" class="contact-form-modern">
                                <div class="form-row">
                                    <div class="form-field">
                                        <label for="name">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            Nom complet <span class="required">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input" required placeholder="Jean Dupont">
                                    </div>

                                    <div class="form-field">
                                        <label for="email">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                                <polyline points="22,6 12,13 2,6"/>
                                            </svg>
                                            Adresse email <span class="required">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" class="form-input" required placeholder="exemple@email.com">
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label for="subject">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="16" x2="12" y2="12"/>
                                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                                        </svg>
                                        Sujet de votre message <span class="required">*</span>
                                    </label>
                                    <select id="subject" name="subject" class="form-input" required>
                                        <option value="">Sélectionnez un sujet</option>
                                        <option value="question">Question générale</option>
                                        <option value="reservation">Problème de réservation</option>
                                        <option value="payment">Question sur les paiements</option>
                                        <option value="account">Problème de compte</option>
                                        <option value="safety">Sécurité et confiance</option>
                                        <option value="suggestion">Suggestion d'amélioration</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>

                                <div class="form-field">
                                    <label for="message">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Votre message <span class="required">*</span>
                                    </label>
                                    <textarea id="message" name="message" rows="7" class="form-input" required placeholder="Décrivez votre demande en détail. Plus votre message sera précis, plus nous pourrons vous aider efficacement..."></textarea>
                                    <small class="field-hint">Minimum 10 caractères</small>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary" onclick="document.querySelector('.contact-form-modern').reset()">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                        Effacer
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <span>Envoyer le message</span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="22" y1="2" x2="11" y2="13"/>
                                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="contact-sidebar">
                            <div class="sidebar-section">
                                <h3 class="sidebar-title">Autres moyens de contact</h3>
                                
                                <div class="contact-method">
                                    <div class="method-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                            <polyline points="22,6 12,13 2,6"/>
                                        </svg>
                                    </div>
                                    <div class="method-content">
                                        <h4>Email direct</h4>
                                        <a href="mailto:carshare.cov@gmail.com">carshare.cov@gmail.com</a>
                                    </div>
                                </div>

                                <div class="contact-method">
                                    <div class="method-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                                        </svg>
                                    </div>
                                    <div class="method-content">
                                        <h4>Centre d'aide</h4>
                                        <a href="index.php?action=faq">Consultez la FAQ</a>
                                    </div>
                                </div>

                                <div class="contact-method">
                                    <div class="method-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                        </svg>
                                    </div>
                                    <div class="method-content">
                                        <h4>Communauté</h4>
                                        <p>Rejoignez nos covoitureurs</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-box">
                                <div class="info-box-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                </div>
                                <h4>Vos données sont protégées</h4>
                                <p>Nous respectons votre vie privée. Vos informations ne seront jamais partagées avec des tiers.</p>
                            </div>

                            <div class="info-box info-box-accent">
                                <div class="info-box-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                </div>
                                <h4>Réponse sous 24h</h4>
                                <p>Notre équipe s'engage à vous répondre dans les meilleurs délais, généralement sous 24h ouvrées.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        <?php
    }
}
