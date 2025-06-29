<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $tables = DB::select('SHOW TABLES');
    
    echo "=== TABLES DANS LA BASE DE DONNÉES ===\n\n";
    
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "✓ {$tableName}\n";
    }
    
    echo "\n=== VÉRIFICATION DES TABLES PRINCIPALES ===\n\n";
    
    $expectedTables = [
        'users', 'categories', 'products', 'product_images', 'carts', 'cart_locations',
        'orders', 'order_items', 'order_returns', 'rentals', 'rental_items', 'rental_penalties',
        'contacts', 'memberships', 'newsletters', 'newsletter_subscriptions',
        'blogs', 'blog_comments', 'blog_comment_reports',
        'cookies', 'cookie_consents', 'wishlists', 'product_likes'
    ];
    
    $existingTables = array_map(function($table) {
        return array_values((array)$table)[0];
    }, $tables);
    
    foreach ($expectedTables as $expectedTable) {
        if (in_array($expectedTable, $existingTables)) {
            echo "✅ {$expectedTable} - OK\n";
        } else {
            echo "❌ {$expectedTable} - MANQUANTE\n";
        }
    }
    
    echo "\n=== RÉSUMÉ ===\n";
    echo "Total des tables: " . count($existingTables) . "\n";
    echo "Tables attendues: " . count($expectedTables) . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
