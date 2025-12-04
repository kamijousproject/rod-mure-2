<?php
/**
 * Seed Runner
 * Run: php seeds/seed.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? 'used_car_db';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

echo "Starting seeding...\n";
echo "Database: {$database}\n";

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$database}",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Run seed file
    $seedFile = __DIR__ . '/seed.sql';
    
    if (!file_exists($seedFile)) {
        echo "Seed file not found: {$seedFile}\n";
        exit(1);
    }
    
    $sql = file_get_contents($seedFile);
    
    // Split by semicolon and execute each statement
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($s) => !empty($s) && !str_starts_with($s, '--')
    );
    
    foreach ($statements as $statement) {
        if (!empty(trim($statement))) {
            $pdo->exec($statement);
        }
    }
    
    echo "Seeding completed successfully!\n";
    echo "Sample data inserted.\n";
    
    // Show credentials
    echo "\n=== Test Accounts ===\n";
    echo "Admin: admin@usedcar.test / password123\n";
    echo "Seller: seller1@usedcar.test / password123\n";
    echo "Buyer: buyer1@usedcar.test / password123\n";
    
} catch (PDOException $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
