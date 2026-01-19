<?php
/**
 * SecurityValidator - Validation et sanitization côté serveur
 * Protection contre: XSS, SQL injection, hex encoding, caractères dangereux
 * Version: 2.0 - Réutilisable pour tout le site
 */

class SecurityValidator {
    
    /**
     * Patterns de détection d'attaques
     */
    private static $dangerousPatterns = [
        'sql' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/i',
        'xss' => '/<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover|eval\(|expression\(/i',
        'hex' => '/(\\\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/',
        'binary' => '/[01]{32,}/',
        'unicode' => '/\\\\u[0-9a-fA-F]{4}/i',
        'control' => '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/',
        'dangerous' => '/[<>{}[\]\\\\|`]/'
    ];
    
    /**
     * Nettoie une entrée utilisateur
     */
    public static function sanitizeInput($input) {
        if (!is_string($input)) {
            return '';
        }
        
        // Supprimer caractères de contrôle
        $input = preg_replace(self::$dangerousPatterns['control'], '', $input);
        
        // Supprimer backslashes
        $input = str_replace('\\', '', $input);
        
        // Trim
        $input = trim($input);
        
        return $input;
    }
    
    /**
     * Détecte les menaces de sécurité
     */
    public static function detectThreats($value, &$errors = [], $fieldName = 'champ') {
        if (empty($value)) {
            return [];
        }
        
        $threats = [];
        
        // SQL Injection
        if (preg_match(self::$dangerousPatterns['sql'], $value)) {
            $threats[] = 'SQL Injection';
            $errors[] = "Le champ '{$fieldName}' contient des mots-clés SQL interdits";
        }
        
        // XSS
        if (preg_match(self::$dangerousPatterns['xss'], $value)) {
            $threats[] = 'XSS';
            $errors[] = "Le champ '{$fieldName}' contient du code JavaScript interdit";
        }
        
        // Hex encoding
        if (preg_match(self::$dangerousPatterns['hex'], $value)) {
            $threats[] = 'Hex encoding';
            $errors[] = "Le champ '{$fieldName}' contient de l'encodage hexadécimal suspect";
        }
        
        // Binary encoding
        if (preg_match(self::$dangerousPatterns['binary'], $value)) {
            $threats[] = 'Binary encoding';
            $errors[] = "Le champ '{$fieldName}' contient de l'encodage binaire suspect";
        }
        
        // Unicode exploits
        if (preg_match(self::$dangerousPatterns['unicode'], $value)) {
            $threats[] = 'Unicode exploit';
            $errors[] = "Le champ '{$fieldName}' contient des séquences Unicode suspectes";
        }
        
        // Caractères dangereux (backslash, chevrons, accolades)
        if (preg_match(self::$dangerousPatterns['dangerous'], $value)) {
            $threats[] = 'Caractères dangereux';
            $errors[] = "Le champ '{$fieldName}' contient des caractères interdits (< > { } [ ] \\ | `)";
        }
        
        return $threats;
    }
    
    /**
     * Valide un numéro de rue - STRICT
     * Accepte: chiffres, bis/ter/quater, lettres, tiret
     * BLOQUE: décimaux, slash, backslash
     */
    public static function validateStreetNumber($value, &$errors = [], $fieldName = 'numéro de voie') {
        $sanitized = self::sanitizeInput($value);
        
        // Optionnel
        if (empty($sanitized)) {
            return $sanitized;
        }
        
        // Détecter menaces
        self::detectThreats($sanitized, $errors, $fieldName);
        
        // Bloquer décimaux
        if (preg_match('/[.,\/\\\\]/', $sanitized)) {
            $errors[] = "Le {$fieldName} ne peut pas contenir de point, virgule, slash ou backslash";
            return $sanitized;
        }
        
        // Format strict: nombre + suffixe optionnel
        if (!preg_match('/^\d+\s*(bis|ter|quater|[A-Za-z])?\s*(-\d+)?$/i', $sanitized)) {
            $errors[] = "Format invalide pour {$fieldName}. Exemples: 10, 10bis, 10B, 10-12";
            return $sanitized;
        }
        
        // Longueur max
        if (strlen($sanitized) > 10) {
            $errors[] = "Le {$fieldName} est trop long (maximum 10 caractères)";
        }
        
        return $sanitized;
    }
    
    /**
     * Types de voies valides en France
     */
    private static $validStreetTypes = [
        'rue', 'avenue', 'boulevard', 'place', 'impasse', 'allée', 'chemin', 
        'route', 'voie', 'cours', 'quai', 'passage', 'square', 'esplanade',
        'promenade', 'montée', 'descente', 'côte', 'rampe', 'sente', 'sentier',
        'traverse', 'venelle', 'ruelle', 'cour', 'mail', 'parvis', 'galerie',
        'pont', 'port', 'porte', 'cité', 'résidence', 'lotissement', 'hameau',
        'lieu-dit', 'zone', 'parc', 'jardin', 'villa', 'enclos', 'clos',
        'allées', 'av', 'bd', 'pl', 'imp', 'ch', 'rte', 'sq', 'esp'
    ];

    /**
     * Valide un nom de rue - FORMAT FRANÇAIS STRICT
     * Formats acceptés:
     * - "Rue de la République"
     * - "Avenue des Champs-Élysées"
     * - "Boulevard Victor Hugo"
     * - "12 Rue du Commerce" (avec numéro)
     * - "Rue du 8 Mai 1945" (avec date)
     */
    public static function validateStreetName($value, &$errors = [], $fieldName = 'rue') {
        $sanitized = self::sanitizeInput($value);
        
        // Optionnel
        if (empty($sanitized)) {
            return $sanitized;
        }
        
        // Détecter menaces
        self::detectThreats($sanitized, $errors, $fieldName);
        
        // Bloquer caractères dangereux de base
        if (preg_match('/[<>{}[\]\\\\|`]/', $sanitized)) {
            $errors[] = "La {$fieldName} contient des caractères interdits";
            return $sanitized;
        }
        
        // Format: lettres, chiffres, espaces, tirets, apostrophes, points, virgules, slashes
        if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\-\',.\/]+$/u', $sanitized)) {
            $errors[] = "La {$fieldName} contient des caractères non valides";
            return $sanitized;
        }
        
        // VALIDATION MINIMALE : Une rue doit contenir au moins 2 lettres consécutives
        // Bloque: "/1000", "12345", "....", "///"
        // Accepte: "Rue 123", "12 Rue", "Avenue A"
        if (!preg_match('/[a-zA-ZÀ-ÿ]{2,}/u', $sanitized)) {
            $errors[] = "La {$fieldName} doit contenir au moins 2 lettres consécutives";
            return $sanitized;
        }
        
        // Bloquer séquences de chiffres trop longues (> 5)
        // Bloque: "123456", "Rue 1234567" mais accepte "Rue 12345", "75001"
        if (preg_match('/\d{6,}/', $sanitized)) {
            $errors[] = "La {$fieldName} contient une séquence de chiffres trop longue (max 5)";
            return $sanitized;
        }
        
        // Bloquer répétitions excessives du même caractère (> 3 fois)
        // Bloque: "aaaa", "1111", "....", "----"
        if (preg_match('/(.)\\1{3,}/', $sanitized)) {
            $errors[] = "La {$fieldName} contient une répétition excessive de caractères";
            return $sanitized;
        }
        
        // Bloquer alternance trop fréquente chiffres/lettres (indicateur de gibberish)
        // Bloque: "a1b2c3d4", "r5t6y7u8", "fhbih8"
        $letterDigitSwitches = preg_match_all('/[a-zA-Z]\d|\d[a-zA-Z]/', $sanitized);
        if ($letterDigitSwitches > 3) {
            $errors[] = "La {$fieldName} a un format invalide (trop d'alternances lettres/chiffres)";
            return $sanitized;
        }
        
        // Vérifier présence de voyelles (au moins 20% des lettres)
        // Bloque: "fhbih8", "grtpl", "xyz123"
        preg_match_all('/[a-zA-ZÀ-ÿ]/u', $sanitized, $lettersMatch);
        preg_match_all('/[aeiouyàâäéèêëïîôùûüÿœæAEIOUYÀÂÄÉÈÊËÏÎÔÙÛÜŸŒÆ]/ui', $sanitized, $vowelsMatch);
        $letterCount = count($lettersMatch[0]);
        $vowelCount = count($vowelsMatch[0]);
        if ($letterCount > 4 && $vowelCount / $letterCount < 0.2) {
            $errors[] = "La {$fieldName} a un format invalide (pas assez de voyelles)";
            return $sanitized;
        }
        
        // Vérifier longueur
        if (strlen($sanitized) > 150) {
            $errors[] = "Le nom de {$fieldName} est trop long (maximum 150 caractères)";
        }
        
        return $sanitized;
    }
    
    /**
     * Valide un nom de ville - STRICT
     * Seulement lettres, pas de chiffres
     */
    public static function validateCityName($value, &$errors = [], $fieldName = 'ville', $required = true) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized)) {
            if ($required) {
                $errors[] = "La {$fieldName} est obligatoire";
            }
            return $sanitized;
        }
        
        // Détecter menaces
        self::detectThreats($sanitized, $errors, $fieldName);
        
        // Bloquer chiffres et caractères dangereux
        if (preg_match('/[<>{}[\]\\\\|`0-9]/', $sanitized)) {
            $errors[] = "La {$fieldName} ne peut pas contenir de chiffres ou caractères spéciaux";
            return $sanitized;
        }
        
        // Format: lettres, espaces, tirets, apostrophes
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $sanitized)) {
            $errors[] = "La {$fieldName} contient des caractères non autorisés";
            return $sanitized;
        }
        
        if (strlen($sanitized) < 2 || strlen($sanitized) > 100) {
            $errors[] = "La {$fieldName} doit faire entre 2 et 100 caractères";
        }
        
        return $sanitized;
    }
    
    /**
     * Valide un nom/prénom
     */
    public static function validateName($value, &$errors = [], $fieldName = 'nom', $required = true) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized)) {
            if ($required) {
                $errors[] = "Le {$fieldName} est obligatoire";
            }
            return $sanitized;
        }
        
        // Détecter menaces
        self::detectThreats($sanitized, $errors, $fieldName);
        
        // Format: lettres, espaces, tirets, apostrophes
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/u', $sanitized)) {
            $errors[] = "Le {$fieldName} ne peut contenir que des lettres";
            return $sanitized;
        }
        
        if (strlen($sanitized) < 2 || strlen($sanitized) > 50) {
            $errors[] = "Le {$fieldName} doit faire entre 2 et 50 caractères";
        }
        
        return $sanitized;
    }
    
    /**
     * Valide un email
     */
    public static function validateEmail($value, &$errors = [], $required = true) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized)) {
            if ($required) {
                $errors[] = "L'email est obligatoire";
            }
            return $sanitized;
        }
        
        // Détecter menaces
        self::detectThreats($sanitized, $errors, 'email');
        
        // Validation email
        if (!filter_var($sanitized, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
            return $sanitized;
        }
        
        // Longueur max
        if (strlen($sanitized) > 254) {
            $errors[] = "L'email est trop long";
        }
        
        return $sanitized;
    }
    
    /**
     * Valide un prix/montant
     */
    public static function validatePrice($value, &$errors = [], $min = 0, $max = 250, $required = false) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized)) {
            if ($required) {
                $errors[] = "Le prix est obligatoire";
            }
            return null;
        }
        
        // Format: nombres et point décimal uniquement
        if (!preg_match('/^\d+(\.\d{1,2})?$/', $sanitized)) {
            $errors[] = "Format de prix invalide. Exemple: 15.50";
            return null;
        }
        
        $price = floatval($sanitized);
        
        if ($price < $min || $price > $max) {
            $errors[] = "Le prix doit être entre {$min}€ et {$max}€";
        }
        
        return $price;
    }
    
    /**
     * Valide un textarea (message, description)
     */
    public static function validateTextarea($value, &$errors = [], $fieldName = 'message', $minLength = 10, $maxLength = 1000, $required = true) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized)) {
            if ($required) {
                $errors[] = "Le {$fieldName} est obligatoire";
            }
            return $sanitized;
        }
        
        // Détecter menaces
        self::detectThreats($sanitized, $errors, $fieldName);
        
        // Bloquer caractères dangereux
        if (preg_match('/[<>{}[\]\\\\|`]/', $sanitized)) {
            $errors[] = "Le {$fieldName} contient des caractères non autorisés";
            return $sanitized;
        }
        
        if (strlen($sanitized) < $minLength) {
            $errors[] = "Le {$fieldName} doit faire au moins {$minLength} caractères";
        }
        
        if (strlen($sanitized) > $maxLength) {
            $errors[] = "Le {$fieldName} ne peut pas dépasser {$maxLength} caractères";
        }
        
        return $sanitized;
    }
    
    /**
     * Valide un numéro de téléphone français
     */
    public static function validatePhone($value, &$errors = [], $required = false) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized)) {
            if ($required) {
                $errors[] = "Le numéro de téléphone est obligatoire";
            }
            return $sanitized;
        }
        
        // Supprimer espaces, points, tirets
        $cleaned = preg_replace('/[\s\.\-]/', '', $sanitized);
        
        // Format français: 10 chiffres commençant par 0
        if (!preg_match('/^0[1-9]\d{8}$/', $cleaned)) {
            $errors[] = "Format de téléphone invalide. Exemple: 0612345678";
            return $sanitized;
        }
        
        return $cleaned;
    }
    
    /**
     * Valide un mot de passe
     */
    public static function validatePassword($value, &$errors = [], $minLength = 8) {
        if (empty($value)) {
            $errors[] = "Le mot de passe est obligatoire";
            return false;
        }
        
        if (strlen($value) < $minLength) {
            $errors[] = "Le mot de passe doit faire au moins {$minLength} caractères";
        }
        
        // Vérifier complexité
        $hasLower = preg_match('/[a-z]/', $value);
        $hasUpper = preg_match('/[A-Z]/', $value);
        $hasNumber = preg_match('/\d/', $value);
        
        if (!$hasLower || !$hasUpper || !$hasNumber) {
            $errors[] = "Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre";
        }
        
        return empty($errors);
    }
    
    /**
     * Valide un nombre entier dans une plage
     */
    public static function validateInteger($value, &$errors = [], $fieldName = 'nombre', $min = null, $max = null, $required = true) {
        $sanitized = self::sanitizeInput($value);
        
        if (empty($sanitized) && $sanitized !== '0') {
            if ($required) {
                $errors[] = "Le {$fieldName} est obligatoire";
            }
            return null;
        }
        
        if (!ctype_digit($sanitized)) {
            $errors[] = "Le {$fieldName} doit être un nombre entier";
            return null;
        }
        
        $int = intval($sanitized);
        
        if ($min !== null && $int < $min) {
            $errors[] = "Le {$fieldName} doit être au moins {$min}";
        }
        
        if ($max !== null && $int > $max) {
            $errors[] = "Le {$fieldName} ne peut pas dépasser {$max}";
        }
        
        return $int;
    }
}
