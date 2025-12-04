<?php
/**
 * Entry Point - Used Car Marketplace
 * All requests are routed through this file
 */

declare(strict_types=1);

// Start session
session_start();

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoload
require BASE_PATH . '/vendor/autoload.php';

// Load configuration
$config = require BASE_PATH . '/config/config.php';

// Set error reporting based on environment
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set timezone
date_default_timezone_set('Asia/Bangkok');

// Initialize application
use App\Core\Router;
use App\Core\Database;
use App\Core\RateLimiter;
use App\Core\ErrorHandler;

// Register error handler
ErrorHandler::register($config['app']['debug']);

// Initialize database connection
Database::init($config['database']);

// Rate limiting
$rateLimiter = new RateLimiter($config['rate_limit']['requests'], $config['rate_limit']['period']);
if (!$rateLimiter->check($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1')) {
    http_response_code(429);
    echo json_encode(['error' => 'Too many requests. Please try again later.']);
    exit;
}

// Initialize router
$router = new Router();

// Load routes
require BASE_PATH . '/app/routes.php';

// Dispatch request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path for subfolder installation (e.g., /used-car/public)
$basePath = '/used-car/public';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath)) ?: '/';
}

// Handle storage file requests directly
if (str_starts_with($uri, '/storage/')) {
    $path = substr($uri, 9); // Remove '/storage/'
    $path = str_replace(['..', '\\'], ['', '/'], $path); // Sanitize
    $filePath = BASE_PATH . '/storage/uploads/' . $path;
    
    if (file_exists($filePath) && is_file($filePath)) {
        $mimeTypes = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png', 'gif' => 'image/gif',
            'webp' => 'image/webp', 'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
        ];
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mime = $mimeTypes[$ext] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=31536000');
        readfile($filePath);
        exit;
    }
    
    http_response_code(404);
    echo 'File not found';
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// Handle request
$router->dispatch($uri, $method);
