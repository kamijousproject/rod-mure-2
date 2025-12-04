<?php
/**
 * PHPUnit Bootstrap
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

// Load test environment
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Override for testing
$_ENV['APP_ENV'] = 'testing';
$_ENV['DB_DATABASE'] = $_ENV['DB_DATABASE'] ?? 'used_car_test';
