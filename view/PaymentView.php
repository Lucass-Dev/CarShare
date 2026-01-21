<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - CarShare</title>
    <link rel="stylesheet" href="<?= asset('styles/trip_payment.css') ?>">
</head>
<body>

    <main>
        <div class="payment-header">
            <h1>
                <svg class="header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
                Paiement du trajet
            </h1>
            <p class="payment-subtitle">Complétez vos informations pour finaliser votre réservation</p>
        </div>

        <div class="payment-container">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="payment-grid">
                <!-- Récapitulatif -->
                <div class="summary-card">
                    <div class="card-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                        <h2>Récapitulatif du trajet</h2>
                    </div>

                    <div class="trip-details">
                        <!-- Route -->
                        <div class="detail-row route">
                            <div class="route-indicator">
                                <div class="route-dot start"></div>
                                <div class="route-line"></div>
                                <div class="route-dot end"></div>
                            </div>
                            <div class="route-info">
                                <div class="location-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="10" r="3"/>
                                        <path d="M12 21.7C17.3 17 20 13 20 10a8 8 0 1 0-16 0c0 3 2.7 7 8 11.7z"/>
                                    </svg>
                                    <?= htmlspecialchars($carpooling['start_location']) ?>
                                </div>
                                <div class="arrow-separator">↓</div>
                                <div class="location-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <?= htmlspecialchars($carpooling['end_location']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="detail-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <div>
                                <div class="label">Date de départ</div>
                                <div class="value"><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></div>
                            </div>
                        </div>

                        <!-- Heure -->
                        <div class="detail-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <div>
                                <div class="label">Heure de départ</div>
                                <div class="value"><?= date('H:i', strtotime($carpooling['start_date'])) ?></div>
                            </div>
                        </div>

                        <!-- Conducteur -->
                        <div class="detail-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <div>
                                <div class="label">Conducteur</div>
                                <div class="value">
                                    <?= htmlspecialchars($carpooling['first_name']) ?>
                                    <span class="rating">★ <?= $carpooling['global_rating'] ? round($carpooling['global_rating'], 1) : 'N/A' ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Places disponibles -->
                        <div class="detail-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            <div>
                                <div class="label">Places</div>
                                <div class="value">
                                    <?php 
                                        $totalPlaces = (int)$carpooling['available_places'] + (int)$bookedPlacesCount;
                                        echo $carpooling['available_places'] . ' disponibles / ' . $totalPlaces . ' totales';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prix -->
                    <div class="price-summary">
                        <div class="price-row">
                            <span>Prix par place</span>
                            <span id="unit-price"><?= number_format($carpooling['price'], 2) ?> €</span>
                        </div>
                        <div class="price-row" id="seats-row" style="display: none;">
                            <span>Nombre de places</span>
                            <span id="selected-seats">1</span>
                        </div>
                        <div class="price-total">
                            <span style="font-weight: 700; font-size: 18px;">Total</span>
                            <span class="total-amount" id="total-amount" data-unit-price="<?= $carpooling['price'] ?>"><?= number_format($carpooling['price'], 2) ?> €</span>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement -->
                <div class="payment-card">
                    <div class="card-header">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                        <h2>Informations de paiement</h2>
                    </div>

                    <form id="payment-form" method="POST" action="<?= url('index.php?action=payment&carpooling_id=' . $carpooling['id']) ?>" novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                        <!-- Nombre de places à réserver -->
                        <div class="form-group">
                            <label>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                Nombre de places à réserver
                            </label>
                            <select 
                                id="seats_count" 
                                name="seats_count" 
                                required
                                style="width: 100%; padding: 14px 16px; border: 2px solid #e0e6ed; border-radius: 12px; font-size: 15px; background: white; cursor: pointer;"
                            >
                                <?php for ($i = 1; $i <= min(5, $carpooling['available_places']); $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> place<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="error-msg" id="error-seats_count"></div>
                        </div>

                        <!-- Nom sur la carte -->
                        <div class="form-group">
                            <label>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Nom sur la carte
                            </label>
                            <input 
                                type="text" 
                                id="card_holder" 
                                name="card_holder" 
                                placeholder="Ex : Marie Dupont" 
                                required
                            >
                            <div class="error-msg" id="error-card_holder"></div>
                        </div>

                        <!-- Numéro de carte -->
                        <div class="form-group">
                            <label>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                                    <line x1="2" y1="10" x2="22" y2="10"/>
                                </svg>
                                Numéro de carte
                            </label>
                            <input 
                                type="text" 
                                id="card_number" 
                                name="card_number" 
                                placeholder="1234 5678 9012 3456" 
                                maxlength="19"
                                required
                            >
                            <div class="error-msg" id="error-card_number"></div>
                        </div>

                        <!-- Expiration et CVV -->
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    Expiration
                                </label>
                                <input 
                                    type="text" 
                                    id="expiry_date" 
                                    name="expiry_date" 
                                    placeholder="MM/AA" 
                                    maxlength="5"
                                    required
                                >
                                <div class="error-msg" id="error-expiry_date"></div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    CVV
                                </label>
                                <input 
                                    type="text" 
                                    id="cvv" 
                                    name="cvv" 
                                    placeholder="123" 
                                    maxlength="4"
                                    required
                                >
                                <div class="error-msg" id="error-cvv"></div>
                            </div>
                        </div>

                        <!-- Badge sécurité -->
                        <div class="security-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <span>Paiement 100% sécurisé et crypté</span>
                        </div>

                        <!-- Acceptation CGV -->
                        <div class="legal-consent">
                            <label class="checkbox-label">
                                <input type="checkbox" id="accept_terms" name="accept_terms" required>
                                <span class="consent-text">
                                    J'accepte les conditions CarShare pour cette réservation (
                                    <a href="<?= url('index.php?action=cgv') ?>" target="_blank">CGV</a>,
                                    <a href="<?= url('index.php?action=cgu') ?>" target="_blank">CGU</a>,
                                    <a href="<?= url('index.php?action=legal') ?>" target="_blank">Mentions légales</a>
                                    )
                                </span>
                            </label>
                            <div class="error-msg" id="error-accept_terms"></div>
                        </div>

                        <!-- Bouton payer -->
                        <button type="submit" class="btn-payer" id="btn-submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Confirmer la réservation
                        </button>

                        <p class="payment-info-text">
                            En cliquant sur "Confirmer la réservation", vous acceptez que vos informations de paiement soient traitées de manière sécurisée.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="<?= asset('js/payment-form.js') ?>"></script>
</body>
</html>
