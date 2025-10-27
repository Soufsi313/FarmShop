<?php
/**
 * TEST #5 : Vérification de l'intégrité des données
 */

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

echo "=== TEST DATA INTEGRITY ===\n\n";

try {
    $pdo = new \PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? '127.0.0.1') . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'farmshop'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    $issues = 0;
    
    // Test 1: Enregistrements orphelins dans order_items
    echo "📊 Test 1: Vérification des enregistrements orphelins...\n";
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM order_items oi 
        LEFT JOIN orders o ON oi.order_id = o.id 
        WHERE o.id IS NULL
    ");
    $orphans = $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    
    if ($orphans > 0) {
        echo "  ⚠️  $orphans order_items orphelins trouvés\n";
        $issues++;
    } else {
        echo "  ✅ Aucun order_item orphelin\n";
    }
    
    // Test 2: Produits avec quantité négative
    echo "\n📊 Test 2: Vérification des quantités négatives...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE quantity < 0");
    $negative = $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    
    if ($negative > 0) {
        echo "  ⚠️  $negative produits avec quantité négative\n";
        $issues++;
    } else {
        echo "  ✅ Aucune quantité négative\n";
    }
    
    // Test 3: Prix négatifs
    echo "\n📊 Test 3: Vérification des prix négatifs...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE price < 0");
    $negativePrice = $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    
    if ($negativePrice > 0) {
        echo "  ⚠️  $negativePrice produits avec prix négatif\n";
        $issues++;
    } else {
        echo "  ✅ Aucun prix négatif\n";
    }
    
    // Test 4: Utilisateurs avec email dupliqué
    echo "\n📊 Test 4: Vérification des emails dupliqués...\n";
    $stmt = $pdo->query("
        SELECT email, COUNT(*) as count 
        FROM users 
        GROUP BY email 
        HAVING count > 1
    ");
    $duplicates = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    if (!empty($duplicates)) {
        echo "  ⚠️  " . count($duplicates) . " emails dupliqués trouvés\n";
        $issues++;
    } else {
        echo "  ✅ Aucun email dupliqué\n";
    }
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "📊 Problèmes trouvés: $issues\n";
    
    if ($issues === 0) {
        echo "TEST RÉUSSI ✅\n";
    } else {
        echo "TEST AVEC AVERTISSEMENTS ⚠️\n";
    }
    
} catch (\PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
