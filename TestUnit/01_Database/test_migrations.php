<?php
/**
 * TEST #3 : Vérification de l'historique des migrations
 */

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

echo "=== TEST DATABASE MIGRATIONS ===\n\n";

try {
    $pdo = new \PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? '127.0.0.1') . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'farmshop'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
    echo "📊 Vérification de la table migrations...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
    if ($stmt->rowCount() === 0) {
        echo "❌ Table 'migrations' MANQUANTE\n";
        exit(1);
    }
    echo "✅ Table migrations existe\n\n";
    
    echo "📊 Liste des migrations exécutées...\n";
    $stmt = $pdo->query("SELECT * FROM migrations ORDER BY batch, id");
    $migrations = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    if (empty($migrations)) {
        echo "⚠️  Aucune migration trouvée\n";
    } else {
        echo "✅ " . count($migrations) . " migrations trouvées\n\n";
        
        $batches = [];
        foreach ($migrations as $migration) {
            $batch = $migration['batch'];
            if (!isset($batches[$batch])) {
                $batches[$batch] = [];
            }
            $batches[$batch][] = $migration['migration'];
            echo "  • [Batch {$batch}] {$migration['migration']}\n";
        }
        
        echo "\n📊 Statistiques...\n";
        echo "  • Total migrations: " . count($migrations) . "\n";
        echo "  • Nombre de batches: " . count($batches) . "\n";
    }
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "✅ Migrations: " . count($migrations) . " exécutées\n";
    echo "TEST RÉUSSI ✅\n";
    
} catch (\PDOException $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "TEST ÉCHOUÉ ❌\n";
}
