<?php

require_once __DIR__ . '/../model/ContactModel.php';

class ContactController {
    private $contactModel;

    public function __construct() {
        $this->contactModel = new ContactModel();
    }
    
    /**
     * Sanitize user input to prevent XSS and other attacks
     */
    private function sanitizeInput($input) {
        if (!is_string($input)) {
            return '';
        }
        
        // Remove control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }
    
    /**
     * Validate input for security threats
     */
    private function validateSecurity($value, $fieldName = 'champ') {
        if (empty($value)) {
            return true;
        }
        
        // Patterns de détection d'attaques
        $patterns = [
            'sql' => '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT)\b|--|;|\/\*|\*\/|xp_|sp_)/i',
            'xss' => '/<script|<iframe|<object|<embed|javascript:|onerror|onload|onclick|onmouseover/i',
            'hex' => '/(\\\\x[0-9a-fA-F]{2}|%[0-9a-fA-F]{2}){3,}/',
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }
        
        return true;
    }

    public function render() {
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        
        require_once __DIR__ . '/../view/ContactView.php';
        $view = new ContactView();
        $view->display($success, $error);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /CarShare/index.php?action=contact&error=invalid_method');
            exit();
        }

        $name = $this->sanitizeInput($_POST['name'] ?? '');
        $email = $this->sanitizeInput($_POST['email'] ?? '');
        $subject = $this->sanitizeInput($_POST['subject'] ?? '');
        $message = $this->sanitizeInput($_POST['message'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            header('Location: /CarShare/index.php?action=contact&error=missing_fields');
            exit();
        }
        
        // Security validation
        if (!$this->validateSecurity($name, 'nom') || 
            !$this->validateSecurity($subject, 'sujet') || 
            !$this->validateSecurity($message, 'message')) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_input');
            exit();
        }
        
        // Validate name length and format
        if (strlen($name) < 2 || strlen($name) > 100) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_name');
            exit();
        }
        
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/u", $name)) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_name');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_email');
            exit();
        }
        
        // Validate subject length
        if (strlen($subject) < 3 || strlen($subject) > 200) {
            header('Location: /CarShare/index.php?action=contact&error=invalid_subject');
            exit();
        }

        if (strlen($message) < 10 || strlen($message) > 5000) {
            header('Location: /CarShare/index.php?action=contact&error=message_length');
            exit();
        }

        // Save message to database with sanitized data
        $result = $this->contactModel->saveMessage($name, $email, $subject, $message);

        if ($result) {
            header('Location: /CarShare/index.php?action=contact&success=1');
        } else {
            header('Location: /CarShare/index.php?action=contact&error=db_error');
        }
        exit();
    }
}
