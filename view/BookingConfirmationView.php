<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation Confirmée - CarShare</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #eef3fb 0%, #f5f8ff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .success-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin-top: 80px;
        }
        
        .success-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(43, 77, 154, 0.15);
            padding: 50px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.6s ease-out 0.2s both;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .success-icon svg {
            width: 60px;
            height: 60px;
            stroke: white;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        
        .checkmark {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawCheck 0.8s ease-out 0.5s forwards;
        }
        
        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }
        
        h1 {
            color: #2b4d9a;
            font-size: 32px;
            margin: 0 0 15px 0;
            font-weight: 700;
        }
        
        .subtitle {
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .info-section {
            background: linear-gradient(135deg, #eef3fb 0%, #f5f8ff 100%);
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
        }
        
        .info-section h2 {
            color: #2b4d9a;
            font-size: 20px;
            margin: 0 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-section h2 svg {
            width: 24px;
            height: 24px;
            stroke: #2b4d9a;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(43, 77, 154, 0.1);
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #666;
            font-weight: 500;
        }
        
        .info-value {
            color: #2b4d9a;
            font-weight: 600;
        }
        
        .message-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .message-badge svg {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
        }
        
        .message-badge p {
            margin: 0;
            text-align: left;
            line-height: 1.5;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .btn {
            flex: 1;
            min-width: 200px;
            padding: 16px 30px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2b4d9a 0%, #1e3a7a 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(43, 77, 154, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(43, 77, 154, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #2b4d9a;
            border: 2px solid #2b4d9a;
        }
        
        .btn-secondary:hover {
            background: #eef3fb;
            transform: translateY(-2px);
        }
        
        .btn svg {
            width: 20px;
            height: 20px;
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .subtitle {
                font-size: 16px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/components/header.php'; ?>
    
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <svg viewBox="0 0 52 52">
                    <circle cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark" fill="none" d="M14 27l7.5 7.5L38 18"/>
                </svg>
            </div>
            
            <h1>Réservation confirmée</h1>
            <p class="subtitle">
                Félicitations ! Votre trajet a été réservé avec succès.
            </p>
            
            <div class="message-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
                <p>
                    <strong>Message envoyé au conducteur</strong><br>
                    Un récapitulatif de votre réservation a été automatiquement envoyé dans votre messagerie privée.
                </p>
            </div>
            
            <div class="info-section">
                <h2>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Prochaines étapes
                </h2>
                <div class="info-row">
                    <span class="info-label">Consulter vos messages</span>
                    <span class="info-value">Messagerie privée</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Voir votre historique</span>
                    <span class="info-value">Mes réservations</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Contacter le conducteur</span>
                    <span class="info-value">Via la messagerie</span>
                </div>
            </div>
            
            <div class="button-group">
                <a href="/CarShare/index.php?action=messaging" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                    Voir mes messages
                </a>
                <a href="/CarShare/index.php?action=history" class="btn btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Mon historique
                </a>
            </div>
            
            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #e0e0e0;">
                <a href="/CarShare/index.php?action=home" style="color: #2b4d9a; text-decoration: none; font-weight: 500;">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/components/footer.php'; ?>
</body>
</html>