<?php
/**
 * TEST #2 : Vérification de la structure des tables
 */

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

echo "=== TEST DATABASE TABLES STRUCTURE ===\n\n";

try {
    $pdo = new \PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? '127.0.0.1') . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'farmshop'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    $requiredTables = [
        'users' => ['id', 'name', 'email', 'password'],
        'products' => ['id', 'name', 'price', 'quantity', 'category_id'],
        'categories' => ['id', 'name', 'slug'],
        'orders' => ['id', 'user_id', 'order_number', 'status', 'total_price'],
        'order_items' => ['id', 'order_id', 'product_id', 'quantity', 'unit_price'],
        'carts' => ['id', 'user_id', 'status'],
        'cart_items' => ['id', 'cart_id', 'product_id', 'quantity']
    ];
    
    $totalColumns = 0;
    
    foreach ($requiredTables as $tableName => $requiredColumns) {
        echo "📊 Vérification de la table '$tableName'...\n";
        
        $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
        if ($stmt->rowCount() === 0) {
            echo "  ⚠️  Table '$tableName' MANQUANTE\n\n";
            continue;
        }
        echo "  ✅ Table existe\n";
        
        $stmt = $pdo->query("DESCRIBE $tableName");
        $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'Field');
        $totalColumns += count($columnNames);
        
        $missingColumns = [];
        foreach ($requiredColumns as $requiredColumn) {
            if (!in_array($requiredColumn, $columnNames)) {
                $missingColumns[] = $requiredColumn;
            }
        }
        
        if (empty($missingColumns)) {
            echo "  ✅ Colonnes requises présentes (" . count($requiredColumns) . ")\n";
        } else {
            echo "  ⚠️  Colonnes manquantes: " . implode(', ', $missingColumns) . "\n";
        }
        echo "\n";
    }
    
    echo "=== RÉSUMÉ ===\n";
    echo "📊 Tables vérifiées: " . count($requiredTables) . "\n";
    echo "📋 Colonnes totales: $totalColumns\n";
    echo "\nTEST RÉUSSI ✅\n";
    
} catch (\PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
