<?php
/**
 * Auth - Authentication and authorization handler
 */

namespace App\Core;

class Auth
{
    /**
     * Attempt to login user
     */
    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = ? AND status = 'active'",
            [$email]
        );
        
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        
        // Update last login
        Database::query(
            "UPDATE users SET last_login = NOW() WHERE id = ?",
            [$user['id']]
        );
        
        // Store user in session (without password)
        unset($user['password']);
        $_SESSION['user'] = $user;
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        return true;
    }
    
    /**
     * Check if user is logged in
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }
    
    /**
     * Get current user data
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * Get current user ID
     */
    public static function id(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    }
    
    /**
     * Check if user has role
     */
    public static function hasRole(string|array $roles): bool
    {
        if (!self::check()) {
            return false;
        }
        
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($_SESSION['user']['role'], $roles);
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin(): bool
    {
        return self::hasRole('admin');
    }
    
    /**
     * Check if user is seller
     */
    public static function isSeller(): bool
    {
        return self::hasRole(['seller', 'admin']);
    }
    
    /**
     * Logout user
     */
    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
    }
    
    /**
     * Generate password reset token
     */
    public static function generateResetToken(string $email): ?string
    {
        $user = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        
        if (!$user) {
            return null;
        }
        
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        Database::query(
            "UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?",
            [$token, $expires, $user['id']]
        );
        
        return $token;
    }
    
    /**
     * Validate password reset token
     */
    public static function validateResetToken(string $token): ?array
    {
        return Database::fetch(
            "SELECT id, email FROM users WHERE reset_token = ? AND reset_expires > NOW()",
            [$token]
        );
    }
    
    /**
     * Reset password using token
     */
    public static function resetPassword(string $token, string $password): bool
    {
        $user = self::validateResetToken($token);
        
        if (!$user) {
            return false;
        }
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        Database::query(
            "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?",
            [$hash, $user['id']]
        );
        
        return true;
    }
    
    /**
     * Update user session data
     */
    public static function refresh(): void
    {
        if (!self::check()) {
            return;
        }
        
        $user = Database::fetch(
            "SELECT * FROM users WHERE id = ?",
            [self::id()]
        );
        
        if ($user) {
            unset($user['password']);
            $_SESSION['user'] = $user;
        }
    }
}
