<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation d'email - CarShare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .container {
            max-width: 650px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(127, 167, 244, 0.3);
            padding: 3rem 2.5rem;
            text-align: center;
        }
        
        .icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .icon.success {
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
        }
        
        .icon.error {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        }
        
        .icon svg {
            width: 50px;
            height: 50px;
            stroke: white;
            stroke-width: 2.5;
            fill: none;
        }
        
        h1 {
            color: #1e293b;
            font-size: 2.25rem;
            margin: 0 0 1.25rem 0;
            font-weight: 700;
        }
        
        p {
            color: #64748b;
            line-height: 1.7;
            margin: 1rem 0;
            font-size: 1.1rem;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
            color: white;
            padding: 1.125rem 2.5rem;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            margin-top: 2rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(143, 155, 255, 0.3);
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(143, 155, 255, 0.4);
        }
        
        .error-text {
            color: #dc2626;
            font-weight: 600;
            font-size: 1.05rem;
            margin: 1.5rem 0;
        }
        
        .secondary-link {
            color: #8f9bff;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 1.5rem;
            transition: color 0.2s;
        }
        
        .secondary-link:hover {
            color: #7a89ff;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            
            .container {
                padding: 2.5rem 1.5rem;
            }
            
            h1 {
                font-size: 1.75rem;
            }
            
            p {
                font-size: 1rem;
            }
            
            .icon {
                width: 80px;
                height: 80px;
            }
            
            .icon svg {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../model/Config.php'; ?>
    <div class="container">
        <?php if ($success): ?>
            <div class="icon success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            
            <h1>✅ Email validé avec succès !</h1>
            
            <p>Bienvenue sur CarShare ! Votre compte est maintenant actif et vous êtes automatiquement connecté(e).</p>
            
            <p>Vous pouvez maintenant profiter de toutes les fonctionnalités de la plateforme : publier des trajets, rechercher des covoiturages, et bien plus encore !</p>
            
            <a href="<?= Config::url('home') ?>" class="button">Accéder à mon compte</a>
        <?php else: ?>
            <div class="icon error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
            
            <h1>❌ Erreur de validation</h1>
            
            <p class="error-text"><?= htmlspecialchars($error ?? 'Une erreur est survenue lors de la validation') ?></p>
            
            <p>Le lien de validation peut avoir expiré (24h) ou être invalide. Veuillez réessayer votre inscription.</p>
            
            <a href="<?= Config::url('register') ?>" class="button">Réessayer l'inscription</a>
            <br>
            <a href="<?= Config::url('home') ?>" class="secondary-link">Retour à l'accueil</a>
        <?php endif; ?>
    </div>
</body>
</html>
