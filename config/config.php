<?php
/**
 * Application Configuration
 * Loads environment variables and provides configuration access
 */

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return [
    // Application
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'Used Car Marketplace',
        'env' => $_ENV['APP_ENV'] ?? 'production',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        'key' => $_ENV['APP_KEY'] ?? '',
    ],
    
    // Database
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'database' => $_ENV['DB_DATABASE'] ?? 'used_car_db',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    // Mail (simulated)
    'mail' => [
        'log_path' => $_ENV['MAIL_LOG_PATH'] ?? 'storage/logs/mail.log',
    ],
    
    // Upload
    'upload' => [
        'max_size' => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 5242880),
        'allowed_types' => explode(',', $_ENV['UPLOAD_ALLOWED_TYPES'] ?? 'jpg,jpeg,png,gif,webp'),
        'path' => 'storage/uploads/cars/',
    ],
    
    // Rate Limiting
    'rate_limit' => [
        'requests' => (int)($_ENV['RATE_LIMIT_REQUESTS'] ?? 60),
        'period' => (int)($_ENV['RATE_LIMIT_PERIOD'] ?? 60),
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => 12,
    ],
];
