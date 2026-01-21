<?php
/**
 * Configuration fusionnée CarShare
 * Architecture Lucas + Sécurité/Fonctionnalités Eliarisoa
 */

// ===== LOAD .ENV FILE =====
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parser la ligne KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Supprimer les guillemets si présents
            $value = trim($value, '"\'');
            
            // Définir la variable d'environnement
            if (!getenv($name)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
    return true;
}

// Charger le fichier .env
$envPath = __DIR__ . '/.env';
loadEnv($envPath);

// ===== DATABASE CONFIGURATION =====
// Détection environnement (production vs local)
$isProduction = (
    isset($_SERVER['HTTP_HOST']) && 
    strpos($_SERVER['HTTP_HOST'], 'localhost') === false && 
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === false
);

if ($isProduction) {
     // Configuration production
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_PORT', '3306');
    define('DB_NAME', getenv('DB_NAME') ?: 'carshare');
    define('DB_USER', getenv('DB_USER') ?: 'root');
    define('DB_PASS', getenv('DB_PASS') ?: '');
    define('DB_SSL_MODE', false);
} else {
    // Configuration locale (XAMPP)
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_PORT', '3306');
    define('DB_NAME', getenv('DB_NAME') ?: 'carshare');
    define('DB_USER', getenv('DB_USER') ?: 'root');
    define('DB_PASS', getenv('DB_PASS') ?: ''); // XAMPP default
    define('DB_SSL_MODE', false);
}

// ===== PATH CONFIGURATION =====
// Détection automatique du chemin base
$scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

// Normaliser le chemin (enlever doubles slashes, s'assurer qu'il se termine par /)
if ($scriptPath === '/' || $scriptPath === '') {
    $basePath = '/';
} else {
    $basePath = rtrim($scriptPath, '/') . '/';
}

define('BASE_PATH', $basePath);

// Détection automatique du protocole (HTTP/HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            ? 'https://' : 'http://';

define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . BASE_PATH);
define('UPP_BASE_PATH', 'uploads/profile_pictures/');

// ===== PRODUCTION URL (pour les emails) =====
// Détection automatique de l'environnement
// 1. Si PRODUCTION_URL est définie en variable d'environnement, l'utiliser
// 2. Sinon, utiliser BASE_URL (s'adapte automatiquement à localhost, staging, prod)
define('PRODUCTION_URL', getenv('PRODUCTION_URL') ?: BASE_URL);

// ===== API KEYS =====
define('API_MAPS', 'AIzaSyCST_1-YvBtvMCvCgX3qFb2KCsBoacIRa0'); // Google Maps API Key de Lucas

// ===== EMAIL CONFIGURATION (PHPMailer) =====
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'carshare.cov@gmail.com'); // Real Gmail account
define('SMTP_PASSWORD', 'mhyyxhsdvhxgxvmn'); // Real app password
define('SMTP_FROM_EMAIL', 'carshare.cov@gmail.com');
define('SMTP_FROM_NAME', 'CarShare');

// ===== SECURITY CONFIGURATION =====
define('TOKEN_EXPIRY', 86400); // 24 hours for email/password tokens
define('SESSION_LIFETIME', 3600 * 24 * 7); // 7 days

// ===== HELPER FUNCTIONS =====

/**
 * Generate a URL with base path
 * @param string $path The relative path
 * @return string The full URL
 */
function url($path = '') {
    // Remove leading slash if present
    $path = ltrim($path, '/');
    return BASE_URL . $path;
}

/**
 * Get asset path (CSS, JS, images)
 * @param string $asset The asset path relative to assets folder
 * @return string The asset URL
 */
function asset($asset) {
    // Remove leading slash and 'assets/' if present
    $asset = ltrim($asset, '/');
    $asset = preg_replace('#^assets/#', '', $asset);
    return BASE_PATH . 'assets/' . $asset;
}

/**
 * Get API path
 * @param string $endpoint The API endpoint
 * @return string The API URL
 */
function apiUrl($endpoint) {
    // Remove leading slash if present
    $endpoint = ltrim($endpoint, '/');
    return BASE_PATH . 'assets/api/' . $endpoint;
}

/**
 * Sanitize output for HTML
 * @param string $value
 * @return string
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is admin
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

/**
 * Redirect to a URL
 * @param string $url
 */
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

/**
 * Generate an absolute URL for emails and external links
 * Automatically adapts to the current environment (localhost, staging, production)
 * 
 * @param string $path The relative path (e.g., 'index.php?action=login')
 * @return string The absolute URL
 */
function absoluteUrl($path = '') {
    $path = ltrim($path, '/');
    return PRODUCTION_URL . $path;
}

/**
 * Configuration helper class for static methods
 */
class Config {
    /**
     * Get the base URL of the application
     * @return string The base URL (e.g., http://localhost/carshare_fusion/)
     */
    public static function getBaseUrl() {
        return BASE_URL;
    }
    
    /**
     * Get the production URL (for emails and external links)
     * @return string The production URL
     */
    public static function getProductionUrl() {
        return PRODUCTION_URL;
    }
    
    /**
     * Get the base path of the application
     * @return string The base path (e.g., /carshare_fusion/)
     */
    public static function getBasePath() {
        return BASE_PATH;
    }
    
    /**
     * Debug: Display all detected URLs
     * Access via: ?action=debug_config (only in development)
     */
    public static function debugUrls() {
        if (!isset($_GET['action']) || $_GET['action'] !== 'debug_config') {
            return;
        }
        
        // Only in development (localhost)
        if (!in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1'])) {
            die('Debug mode only available on localhost');
        }
        
        echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration URLs - CarShare Debug</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; border-bottom: 3px solid #8f9bff; padding-bottom: 10px; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .label { font-weight: bold; color: #666; }
        .value { color: #059669; font-family: monospace; background: #f0fdf4; padding: 8px 12px; border-radius: 4px; display: inline-block; margin: 5px 0; }
        .success { color: #059669; }
        .info { background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <h1>🔧 Configuration des URLs - CarShare Fusion</h1>
    
    <div class="section">
        <h2>📍 Environnement détecté</h2>
        <p><span class="label">Hôte:</span> <span class="value">' . $_SERVER['HTTP_HOST'] . '</span></p>
        <p><span class="label">Protocole:</span> <span class="value">' . (strpos(BASE_URL, 'https://') === 0 ? 'HTTPS ✅' : 'HTTP') . '</span></p>
        <p><span class="label">Script:</span> <span class="value">' . $_SERVER['SCRIPT_NAME'] . '</span></p>
    </div>
    
    <div class="section">
        <h2>🌐 URLs configurées</h2>
        <p><span class="label">BASE_PATH:</span><br><span class="value">' . BASE_PATH . '</span></p>
        <p><span class="label">BASE_URL:</span><br><span class="value">' . BASE_URL . '</span></p>
        <p><span class="label">PRODUCTION_URL:</span><br><span class="value">' . PRODUCTION_URL . '</span></p>
    </div>
    
    <div class="section">
        <h2>✉️ Exemples de liens pour emails</h2>
        <p><span class="label">Validation email:</span><br>
        <span class="value">' . PRODUCTION_URL . 'index.php?action=validate_email&token=EXEMPLE_TOKEN</span></p>
        
        <p><span class="label">Reset password:</span><br>
        <span class="value">' . PRODUCTION_URL . 'index.php?action=reset_password&token=EXEMPLE_TOKEN</span></p>
        
        <p><span class="label">Admin validation:</span><br>
        <span class="value">' . PRODUCTION_URL . 'index.php?action=validate_admin_email&token=EXEMPLE_TOKEN</span></p>
    </div>
    
    <div class="section">
        <h2>🔗 Exemples de liens internes</h2>
        <p><span class="label">Accueil:</span><br>
        <span class="value">' . url('index.php') . '</span></p>
        
        <p><span class="label">Connexion:</span><br>
        <span class="value">' . url('index.php?action=login') . '</span></p>
        
        <p><span class="label">Profil:</span><br>
        <span class="value">' . url('index.php?action=profile') . '</span></p>
    </div>
    
    <div class="info">
        <strong>ℹ️ Information:</strong> Cette page est uniquement accessible en localhost pour des raisons de sécurité.
        Les URLs sont détectées automatiquement et s\'adaptent à votre environnement.
    </div>
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="' . url('index.php') . '" style="background: #8f9bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
            ← Retour à l\'accueil
        </a>
    </p>
</body>
</html>';
        exit;
    }
}

// Initialize debug mode if requested
Config::debugUrls();
