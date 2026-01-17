<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - CarShare</title>
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
            width: 100%;
            max-width: 550px;
            margin: 0 auto 3rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(127, 167, 244, 0.15);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        
        .header h1 {
            font-size: clamp(1.5rem, 4vw, 1.75rem);
            font-weight: 700;
            margin: 0;
        }
        
        .header p {
            margin: 0.75rem 0 0 0;
            opacity: 0.95;
            font-size: clamp(0.9rem, 2.5vw, 0.95rem);
        }
        
        .content {
            padding: 2.5rem 2rem;
        }
        
        .error-message, .success-message {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            line-height: 1.5;
        }
        
        .error-message {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #7f1d1d;
            border-left: 4px solid #dc2626;
        }
        
        .success-message {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-group label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: clamp(0.9rem, 2.5vw, 0.95rem);
        }
        
        .input-group input {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: clamp(0.95rem, 2.5vw, 1rem);
            font-family: inherit;
            transition: all 0.2s;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #8f9bff;
            box-shadow: 0 0 0 3px rgba(143, 155, 255, 0.1);
        }
        
        .password-requirements {
            background: #f9f8ff;
            border-left: 4px solid #8f9bff;
            padding: 1.25rem;
            margin: 1.5rem 0;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .password-requirements strong {
            display: block;
            color: #1e293b;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #64748b;
        }
        
        .password-requirements li {
            margin: 0.5rem 0;
        }
        
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%);
            color: white;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: clamp(0.95rem, 2.5vw, 1rem);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(143, 155, 255, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(143, 155, 255, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #8f9bff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .back-link:hover {
            color: #7a89ff;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 2rem 0.5rem;
            }
            
            .container {
                margin-bottom: 2rem;
            }
            
            .header {
                padding: 2rem 1.5rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .content {
                padding: 2rem 1.5rem;
            }
        }
        
        /* Très petit mobile */
        @media (max-width: 400px) {
            .header {
                padding: 1.5rem 1rem;
            }
            
            .content {
                padding: 1.5rem 1rem;
            }
        }
            
            .content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../model/Config.php'; ?>
    <div class="container">
        <div class="header">
            <h1>🔒 Nouveau mot de passe</h1>
            <p>Définissez un nouveau mot de passe sécurisé pour votre compte</p>
        </div>

        <div class="content">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <span>⚠️</span>
                    <div>
                        <?= htmlspecialchars($error) ?>
                        <?php if (strpos($error, 'expiré') !== false || strpos($error, 'invalide') !== false): ?>
                            <br><br>
                            <a href="<?= Config::url('forgot_password') ?>" style="color: #8f9bff; font-weight: bold;">Faire une nouvelle demande</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($tokenData) && !isset($error)): ?>
                <form method="POST" action="">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
                    
                    <div class="input-group">
                        <label for="new_password">Nouveau mot de passe *</label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            required
                            placeholder="Entrez votre nouveau mot de passe"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">Confirmer le mot de passe *</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            required
                            placeholder="Confirmez votre nouveau mot de passe"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="password-requirements">
                        <strong>🔒 Exigences du mot de passe :</strong>
                        <ul>
                            <li>Au moins 12 caractères</li>
                            <li>Au moins une majuscule (A-Z)</li>
                            <li>Au moins une minuscule (a-z)</li>
                            <li>Au moins un chiffre (0-9)</li>
                            <li>Au moins un caractère spécial (!@#$%...)</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn-primary">Confirmer le nouveau mot de passe</button>
                </form>
            <?php elseif (!isset($error)): ?>
                <div class="error-message">
                    <span>⚠️</span>
                    <div>
                        Token manquant ou invalide.
                        <br><br>
                        <a href="<?= Config::url('forgot_password') ?>" style="color: #8f9bff; font-weight: bold;">Faire une nouvelle demande</a>
                    </div>
                </div>
            <?php endif; ?>

            <a href="<?= Config::url('login') ?>" class="back-link">← Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
