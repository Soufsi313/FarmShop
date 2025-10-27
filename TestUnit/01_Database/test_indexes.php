<?php
/**
 * TEST #4 : Vérification des index de base de données
 */

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

echo "=== TEST DATABASE INDEXES ===\n\n";

try {
    $pdo = new \PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? '127.0.0.1') . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'farmshop'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    $tables = ['users', 'products', 'orders', 'order_items'];
    $totalIndexes = 0;
    
    foreach ($tables as $table) {
        echo "📊 Index de la table '$table'...\n";
        
        $stmt = $pdo->query("SHOW INDEX FROM $table");
        $indexes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $indexNames = array_unique(array_column($indexes, 'Key_name'));
        $totalIndexes += count($indexNames);
        
        if (empty($indexes)) {
            echo "  ⚠️  Aucun index trouvé\n";
        } else {
            echo "  ✅ " . count($indexNames) . " index trouvés: " . implode(', ', $indexNames) . "\n";
        }
        echo "\n";
    }
    
    echo "=== RÉSUMÉ ===\n";
    echo "📊 Total d'index: $totalIndexes\n";
    echo "TEST RÉUSSI ✅\n";
    
} catch (\PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
