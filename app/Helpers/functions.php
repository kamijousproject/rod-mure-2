<?php
/**
 * Helper Functions - Global utility functions
 */

use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\Router;

/**
 * Get config value
 */
function config(string $key, mixed $default = null): mixed
{
    static $config = null;
    
    if ($config === null) {
        $config = require BASE_PATH . '/config/config.php';
    }
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    
    return $value;
}

/**
 * Get base URL
 */
function base_url(string $path = ''): string
{
    $baseUrl = config('app.url', 'http://localhost');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Generate URL with base path (for subfolder installations)
 */
function url(string $path = ''): string
{
    // Base path for subfolder installation
    $basePath = '/used-car/public';
    
    // If running via PHP built-in server on port 8000, no base path needed
    if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '8000') {
        $basePath = '';
    }
    
    return $basePath . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

/**
 * Generate URL for named route
 */
function route(string $name, array $params = []): string
{
    return Router::url($name, $params);
}

/**
 * Escape HTML
 */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF field
 */
function csrf_field(): string
{
    return CSRF::field();
}

/**
 * Get CSRF token
 */
function csrf_token(): string
{
    return CSRF::generate();
}

/**
 * Get old input value
 */
function old(string $key, mixed $default = ''): mixed
{
    return Session::old($key, $default);
}

/**
 * Check if user is authenticated
 */
function auth(): ?array
{
    return Auth::user();
}

/**
 * Check if user is guest
 */
function guest(): bool
{
    return !Auth::check();
}

/**
 * Format price with Thai Baht
 */
function format_price(float|int $price): string
{
    return '฿' . number_format($price, 0, '.', ',');
}

/**
 * Format number with commas
 */
function format_number(float|int $number): string
{
    return number_format($number, 0, '.', ',');
}

/**
 * Format date
 */
function format_date(string $date, string $format = 'd M Y'): string
{
    return date($format, strtotime($date));
}

/**
 * Generate slug from string
 */
function slugify(string $text): string
{
    // Replace non-alphanumeric characters with dashes
    $text = preg_replace('/[^a-zA-Z0-9\-\s]/', '', $text);
    $text = preg_replace('/[\s\-]+/', '-', $text);
    $text = trim($text, '-');
    
    return strtolower($text);
}

/**
 * Truncate text
 */
function str_limit(string $text, int $limit = 100, string $end = '...'): string
{
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    
    return mb_substr($text, 0, $limit) . $end;
}

/**
 * Get file extension
 */
function get_extension(string $filename): string
{
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Generate unique filename
 */
function unique_filename(string $extension): string
{
    return uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
}

/**
 * Get upload path
 */
function upload_path(string $path = ''): string
{
    return BASE_PATH . '/storage/uploads/' . ltrim($path, '/');
}

/**
 * Get upload URL
 */
function upload_url(string $path = ''): string
{
    // Use url() to include base path for subfolder installation
    return url('/storage/' . ltrim($path, '/'));
}

/**
 * Check if request is POST
 */
function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is AJAX
 */
function is_ajax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Get client IP address
 */
function get_ip(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

/**
 * Debug dump and die
 */
function dd(mixed ...$vars): void
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    exit;
}

/**
 * Flash message helper
 */
function flash(string $type, string $message): void
{
    Session::setFlash($type, $message);
}

/**
 * Redirect helper
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Year options for select
 */
function year_options(int $start = 1990): array
{
    $years = [];
    $currentYear = (int)date('Y');
    
    for ($year = $currentYear + 1; $year >= $start; $year--) {
        $years[$year] = $year;
    }
    
    return $years;
}
