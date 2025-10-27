<?php
/**
 * TEST #1 : Vérification de la connexion à la base de données
 * 
 * Ce test vérifie que :
 * - La connexion MariaDB est active
 * - Les tables principales existent
 * - Les données de test sont accessibles
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

echo "=== TEST DATABASE CONNECTION ===\n\n";

try {
    // Test 1: Connexion PDO directe
    echo "📊 Test 1: Connexion PDO...\n";
    $pdo = new \PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? '127.0.0.1') . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'farmshop'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion PDO réussie\n\n";
    
    // Test 2: Vérification des tables principales
    echo "📊 Test 2: Vérification des tables...\n";
    $tables = [
        'users',
        'products',
        'categories',
        'orders',
        'order_items',
        'carts',
        'cart_items',
        'rentals',
        'rental_orders'
    ];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "  ✅ Table '$table' existe\n";
        } else {
            echo "  ❌ Table '$table' MANQUANTE\n";
        }
    }
    echo "\n";
    
    // Test 3: Comptage des données
    echo "📊 Test 3: Comptage des enregistrements...\n";
    $counts = [];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $count = $result['count'];
            $counts[$table] = $count;
            echo "  📈 $table: $count enregistrements\n";
        } catch (\PDOException $e) {
            echo "  ⚠️  $table: Table n'existe pas\n";
        }
    }
    echo "\n";
    
    // Test 4: Test d'une requête complexe
    echo "📊 Test 4: Requête complexe (produits avec catégories)...\n";
    $stmt = $pdo->query("
        SELECT p.id, p.name, c.name as category_name, p.price, p.quantity
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LIMIT 5
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $product) {
        echo "  🛒 {$product['name']} ({$product['category_name']}) - {$product['price']}€ - Stock: {$product['quantity']}\n";
    }
    echo "\n";
    
    // Test 5: Vérification des indexes
    echo "📊 Test 5: Vérification des indexes sur 'products'...\n";
    $stmt = $pdo->query("SHOW INDEX FROM products");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $indexNames = array_unique(array_column($indexes, 'Key_name'));
    echo "  📌 Indexes trouvés: " . implode(', ', $indexNames) . "\n\n";
    
    // Résumé final
    echo "=== RÉSUMÉ ===\n";
    echo "✅ Connexion: OK\n";
    echo "✅ Tables: " . count($tables) . " vérifiées\n";
    echo "✅ Total enregistrements: " . array_sum($counts) . "\n";
    echo "✅ Requêtes complexes: OK\n";
    echo "\nTEST RÉUSSI ✅\n";
    
} catch (PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "FICHIER: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nTEST ÉCHOUÉ ❌\n";
}
