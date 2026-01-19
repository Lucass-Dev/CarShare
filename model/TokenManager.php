<?php

/**
 * Manages secure tokens for email validation and password reset
 * Uses file-based storage (no database modification needed)
 */
class TokenManager {
    
    private $tokenDir;
    
    public function __construct() {
        // Store tokens in a temporary directory
        $this->tokenDir = __DIR__ . '/../temp/tokens';
        
        // Create directory if it doesn't exist
        if (!is_dir($this->tokenDir)) {
            mkdir($this->tokenDir, 0700, true);
        }
        
        // Clean old tokens on initialization
        $this->cleanExpiredTokens();
    }
    
    /**
     * Generate a secure token
     * 
     * @param string $type Token type ('email_validation' or 'password_reset')
     * @param int $userId User ID
     * @param string $email User email
     * @param int $expiry Expiry time in seconds (default 24h)
     * @return string Token
     */
    public function generateToken($type, $userId, $email, $expiry = 86400) {
        // Generate random token
        $token = bin2hex(random_bytes(32));
        
        // Store token data
        $data = [
            'type' => $type,
            'user_id' => $userId,
            'email' => $email,
            'created_at' => time(),
            'expires_at' => time() + $expiry
        ];
        
        $filename = $this->getTokenFilename($token);
        file_put_contents($filename, json_encode($data));
        
        return $token;
    }
    
    /**
     * Validate and retrieve token data
     * 
     * @param string $token Token to validate
     * @param string $type Expected token type
     * @return array|null Token data or null if invalid
     */
    public function validateToken($token, $type) {
        $filename = $this->getTokenFilename($token);
        
        if (!file_exists($filename)) {
            return null;
        }
        
        $data = json_decode(file_get_contents($filename), true);
        
        if (!$data) {
            return null;
        }
        
        // Check if token is expired
        if ($data['expires_at'] < time()) {
            $this->deleteToken($token);
            return null;
        }
        
        // Check token type
        if ($data['type'] !== $type) {
            return null;
        }
        
        return $data;
    }
    
    /**
     * Delete a token
     * 
     * @param string $token Token to delete
     */
    public function deleteToken($token) {
        $filename = $this->getTokenFilename($token);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
    
    /**
     * Clean all expired tokens
     */
    private function cleanExpiredTokens() {
        if (!is_dir($this->tokenDir)) {
            return;
        }
        
        $files = glob($this->tokenDir . '/*.token');
        $now = time();
        
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data && isset($data['expires_at']) && $data['expires_at'] < $now) {
                unlink($file);
            }
        }
    }
    
    /**
     * Get filename for a token
     * 
     * @param string $token Token
     * @return string Filename
     */
    private function getTokenFilename($token) {
        return $this->tokenDir . '/' . hash('sha256', $token) . '.token';
    }
}
