<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription en attente - CarShare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f9f8ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }
        
        .container {
            max-width: 650px;
            width: 100%;
            margin: 0 auto 3rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(127, 167, 244, 0.15);
            padding: 2rem;
            text-align: center;
        }
        
        .icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
            border-radius: 50%;
            margin: 2rem auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(169, 178, 255, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(169, 178, 255, 0);
            }
        }
        
        .icon svg {
            width: 50px;
            height: 50px;
            stroke: white;
            stroke-width: 2;
            fill: none;
        }
        
        h1 {
            color: #1e293b;
            font-size: clamp(1.5rem, 5vw, 2.25rem);
            margin: 0 auto 1rem;
            font-weight: 700;
            padding: 0 1rem;
            max-width: 100%;
        }
        
        p {
            color: #64748b;
            line-height: 1.7;
            margin: 1rem auto;
            font-size: clamp(0.95rem, 3vw, 1.1rem);
            max-width: 90%;
            padding: 0 1rem;
        }
        
        .email {
            color: #8f9bff;
            font-weight: 700;
            word-break: break-word;
            display: inline-block;
            max-width: 100%;
        }
        
        .info-box {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-left: 5px solid #8f9bff;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem auto 2rem;
            text-align: center;
            max-width: 100%;
        }
        
        .info-box strong {
            color: #1e293b;
            display: block;
            margin: 0 auto 1rem;
            font-size: clamp(1rem, 3vw, 1.1rem);
        }
        
        .info-box p {
            margin: 0 auto;
            color: #475569;
            line-height: 2;
            text-align: center;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            padding: 0 0.5rem;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
            color: white;
            padding: 1.125rem 2.5rem;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            margin: 1.5rem auto 2rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(143, 155, 255, 0.3);
            max-width: 90%;
            font-size: clamp(0.9rem, 2.5vw, 1rem);
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(143, 155, 255, 0.4);
        }
        
        .timer {
            font-size: clamp(0.85rem, 2.5vw, 0.95rem);
            color: #64748b;
            margin: 1.5rem auto;
            padding: 0 1rem;
        }
        
        /* Tablette (Portrait) */
        @media (max-width: 768px) and (min-width: 641px) {
            .container {
                padding: 2.5rem 2rem;
            }
            
            .info-box {
                padding: 1.75rem;
                margin: 2rem auto;
            }
        }
        
        /* Mobile */
        @media (max-width: 640px) {
            body {
                padding: 1rem 0.5rem;
            }
            
            .container {
                padding: 1.5rem 1rem;
                border-radius: 16px;
            }
            
            .icon {
                width: 80px;
                height: 80px;
                margin: 1.5rem auto 1.5rem;
            }
            
            .icon svg {
                width: 40px;
                height: 40px;
            }
            
            .info-box {
                padding: 1.5rem 1rem;
                margin: 1.5rem auto;
                border-radius: 10px;
            }
            
            .button {
                padding: 1rem 2rem;
                width: 100%;
                max-width: 280px;
            }
        }
        
        /* Très petit mobile */
        @media (max-width: 400px) {
            .container {
                padding: 1.25rem 0.75rem;
            }
            
            .icon {
                width: 70px;
                height: 70px;
                margin: 1rem auto;
            }
            
            .icon svg {
                width: 35px;
                height: 35px;
            }
            
            .info-box {
                padding: 1.25rem 0.75rem;
            }
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../config.php'; ?>
    <div class="container">
        <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        
        <h1>📧 Vérifiez votre email</h1>
        
        <p>Votre inscription est presque terminée !</p>
        
        <?php if (isset($email)): ?>
            <p>Nous avons envoyé un email de confirmation à <span class="email"><?= htmlspecialchars($email) ?></span></p>
        <?php else: ?>
            <p>Nous avons envoyé un email de confirmation à votre adresse.</p>
        <?php endif; ?>
        
        <div class="info-box">
            <strong>📋 Prochaines étapes :</strong>
            <p>
                1. Ouvrez votre boîte de réception<br>
                2. Trouvez l'email de CarShare (vérifiez les spams si besoin)<br>
                3. Cliquez sur le lien de validation<br>
                4. Votre compte sera activé instantanément !
            </p>
        </div>
        
        <p class="timer">
            ⏱️ Le lien de validation est valable pendant 24 heures.
        </p>
        
        <a href="<?= url('index.php?action=home') ?>" class="button">Retour à l'accueil</a>
    </div>
</body>
</html>
