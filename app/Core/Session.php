<?php
/**
 * Session - Session management helper
 */

namespace App\Core;

class Session
{
    /**
     * Set session value
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }
    
    /**
     * Set flash message (available only for next request)
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type] = $message;
    }
    
    /**
     * Get flash messages and clear them
     */
    public static function getFlash(): array
    {
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }
    
    /**
     * Get specific flash message
     */
    public static function flash(string $type): ?string
    {
        $message = $_SESSION['_flash'][$type] ?? null;
        unset($_SESSION['_flash'][$type]);
        return $message;
    }
    
    /**
     * Store old input for form repopulation
     */
    public static function setOld(array $data): void
    {
        $_SESSION['_old_input'] = $data;
    }
    
    /**
     * Get old input value
     */
    public static function old(string $key, mixed $default = ''): mixed
    {
        $value = $_SESSION['_old_input'][$key] ?? $default;
        return $value;
    }
    
    /**
     * Clear old input
     */
    public static function clearOld(): void
    {
        unset($_SESSION['_old_input']);
    }
    
    /**
     * Store validation errors
     */
    public static function setErrors(array $errors): void
    {
        $_SESSION['_errors'] = $errors;
    }
    
    /**
     * Get validation errors
     */
    public static function getErrors(): array
    {
        $errors = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors']);
        return $errors;
    }
    
    /**
     * Check if field has error
     */
    public static function hasError(string $field): bool
    {
        return isset($_SESSION['_errors'][$field]);
    }
    
    /**
     * Get error for field
     */
    public static function error(string $field): ?string
    {
        $errors = $_SESSION['_errors'][$field] ?? [];
        return $errors[0] ?? null;
    }
    
    /**
     * Destroy session
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }
}
