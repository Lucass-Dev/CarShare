<?php
/**
 * LoginView.php (CHAMELEON FINAL)
 * Design: Eliarisoa (100% préservé - auth.css)
 * Architecture: Lucas (convention MVC - {Feature}View.php)
 * CSS: ZERO INLINE - Extracted to assets/styles/auth.css
 */

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<?php require_once __DIR__ . '/components/header.php'; ?>

<!-- CHAMELEON: Link to extracted CSS (NO MORE INLINE) -->
<link rel="stylesheet" href="<?= asset('styles/auth.css') ?>">

<div class="auth-container">
            <div class="auth-card">
                <h1>Connexion</h1>
                <p class="auth-subtitle">Connectez-vous pour accéder à votre compte</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= e($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="<?= url('index.php?controller=profile&action=processLogin') ?>" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember_me" value="1">
                            <span>Se souvenir de moi</span>
                        </label>
                        <a href="<?= url('index.php?controller=profile&action=forgotPassword') ?>" class="link">
                            Mot de passe oublié ?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>
                
                <div class="auth-footer">
                    <p>Pas encore de compte ? 
                        <a href="<?= url('index.php?controller=profile&action=register') ?>" class="link">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>

<!-- CHAMELEON: CSS extracted to auth.css (ZERO INLINE enforcement) -->

<?php require_once __DIR__ . '/components/footer.php'; ?>
