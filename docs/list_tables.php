<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration de la base de données (depuis le .env)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$database = $_ENV['DB_DATABASE'] ?? 'FarmShop';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lister toutes les tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Tables système à exclure
    $systemTables = [
        'failed_jobs', 'jobs', 'job_batches', 'cache', 'cache_locks', 
        'migrations', 'password_reset_tokens', 'sessions'
    ];
    
    echo "=== TABLES DANS LA BASE DE DONNEES FARMSHOP ===\n\n";
    echo "Total des tables: " . count($tables) . "\n";
    echo "Tables système exclues: " . count($systemTables) . "\n";
    echo "Tables métier: " . (count($tables) - count($systemTables)) . "\n\n";
    
    echo "TABLES METIER:\n";
    $businessTables = [];
    foreach ($tables as $table) {
        if (!in_array($table, $systemTables)) {
            echo "- $table\n";
            $businessTables[] = $table;
        }
    }
    
    echo "\n=== TABLES SYSTEME (EXCLUES) ===\n";
    foreach ($systemTables as $table) {
        if (in_array($table, $tables)) {
            echo "- $table\n";
        }
    }
    
    echo "\n=== RESUME ===\n";
    echo "Tables métier trouvées: " . count($businessTables) . "\n";
    echo "Il faut documenter toutes ces tables dans le dictionnaire de données.\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

?>
