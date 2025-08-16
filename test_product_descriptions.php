<?php
// Test product description translations
require_once 'vendor/autoload.php';

// Test the trans_product helper function
$test_products = [
    (object)['slug' => 'motoculteur-7cv-ejmo'],
    (object)['slug' => 'beche-inox-manche-bois-4o4e'],
    (object)['slug' => 'oeufs-fermiers-bio-x6-6i37']
];

echo "=== TESTING PRODUCT DESCRIPTION TRANSLATIONS ===\n\n";

foreach (['fr', 'en', 'nl'] as $lang) {
    echo "🌍 Language: " . strtoupper($lang) . "\n";
    
    $translations = include "resources/lang/$lang/app.php";
    $descriptions = $translations['product_descriptions'] ?? [];
    
    foreach ($test_products as $product) {
        $description = $descriptions[$product->slug] ?? 'MISSING';
        $short_desc = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
        echo "  {$product->slug}: $short_desc\n";
    }
    echo "\n";
}

echo "=== TESTING COMPLETE ===\n";
?>
