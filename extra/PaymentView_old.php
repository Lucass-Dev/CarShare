<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation - CarShare</title>
    <link rel="stylesheet" href="/CarShare/assets/styles/global.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .payment-header h1 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .payment-subtitle {
            color: #7f8c8d;
            font-size: 1.1em;
        }

        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message svg {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .payment-grid {
                grid-template-columns: 1fr;
            }
        }

        .summary-card, .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-header svg {
            width: 28px;
            height: 28px;
            color: #4CAF50;
        }

        .card-header h2 {
            color: #2c3e50;
            font-size: 1.3em;
            margin: 0;
        }

        .trip-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6c757d;
            font-weight: 500;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 600;
        }

        .route-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .route-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #4CAF50;
        }

        .route-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(to right, #4CAF50, #2196F3);
        }

        .route-text {
            font-weight: 600;
            color: #2c3e50;
        }

        .price-total {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
        }

        .price-total .label {
            font-size: 0.9em;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .price-total .amount {
            font-size: 2em;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .checkbox-group {
            display: flex;
            align-items: start;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s;
        }

        .checkbox-group:hover {
            border-color: #4CAF50;
        }

        .checkbox-group input[type="checkbox"] {
            margin-top: 3px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            font-weight: normal;
            line-height: 1.5;
        }

        .checkbox-group a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .security-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 15px;
            color: #6c757d;
            font-size: 0.9em;
        }

        .security-notice svg {
            width: 16px;
            height: 16px;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .info-box p {
            margin: 0;
            color: #1976D2;
            font-size: 0.95em;
        }
    </style>
</head>
<body>
    <?php require __DIR__ . "/components/header.php"; ?>

    <div class="payment-container">
        <div class="payment-header">
            <h1>
                <svg style="width: 40px; height: 40px; vertical-align: middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Confirmation de votre réservation
            </h1>
            <p class="payment-subtitle">Vérifiez les détails et confirmez votre réservation</p>
        </div>

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
                    <h2>Récapitulatif de votre trajet</h2>
                </div>

                <div class="trip-details">
                    <div class="route-indicator">
                        <div class="route-dot"></div>
                        <div class="route-line"></div>
                        <div class="route-dot" style="background: #2196F3;"></div>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">📍 Départ</span>
                        <span class="detail-value"><?= htmlspecialchars($carpooling['start_location']) ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">📍 Arrivée</span>
                        <span class="detail-value"><?= htmlspecialchars($carpooling['end_location']) ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">📅 Date</span>
                        <span class="detail-value"><?= date('d/m/Y', strtotime($carpooling['start_date'])) ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">🕐 Heure</span>
                        <span class="detail-value"><?= date('H:i', strtotime($carpooling['start_date'])) ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">👤 Places disponibles</span>
                        <span class="detail-value"><?= (int)$carpooling['available_seats'] ?></span>
                    </div>
                </div>

                <div class="price-total">
                    <div class="label">Prix par place</div>
                    <div class="amount"><?= number_format($carpooling['price'], 2) ?> €</div>
                </div>

                <div class="info-box">
                    <p><strong>ℹ️ Mode test académique</strong><br>
                    Aucun paiement réel ne sera effectué. Cette réservation sert uniquement à des fins de démonstration.</p>
                </div>
            </div>

            <!-- Formulaire de confirmation -->
            <div class="form-card">
                <div class="card-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    <h2>Confirmer ma réservation</h2>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="accept_terms" name="accept_terms" required>
                            <label for="accept_terms">
                                J'accepte les <a href="/CarShare/index.php?action=cgv" target="_blank">Conditions Générales de Vente (CGV)</a>, 
                                les <a href="/CarShare/index.php?action=cgu" target="_blank">Conditions Générales d'Utilisation (CGU)</a> 
                                et les <a href="/CarShare/index.php?action=legal" target="_blank">Mentions légales</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        ✓ Valider ma réservation
                    </button>

                    <div class="security-notice">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <span>Réservation 100% sécurisée</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require __DIR__ . "/components/footer.php"; ?>
</body>
</html>
