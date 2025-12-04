<?php
/**
 * CSRF - Cross-Site Request Forgery protection
 */

namespace App\Core;

class CSRF
{
    private const TOKEN_KEY = '_csrf_token';
    
    /**
     * Generate CSRF token
     */
    public static function generate(): string
    {
        if (!isset($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION[self::TOKEN_KEY];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verify(?string $token): bool
    {
        if (empty($token) || !isset($_SESSION[self::TOKEN_KEY])) {
            return false;
        }
        
        return hash_equals($_SESSION[self::TOKEN_KEY], $token);
    }
    
    /**
     * Get hidden input field for forms
     */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Validate and abort if invalid
     */
    public static function check(): void
    {
        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        
        if (!self::verify($token)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid CSRF token']);
            exit;
        }
    }
    
    /**
     * Regenerate token
     */
    public static function regenerate(): string
    {
        unset($_SESSION[self::TOKEN_KEY]);
        return self::generate();
    }
}
