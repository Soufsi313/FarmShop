<?php
// Test translations in all languages
require_once 'vendor/autoload.php';

$languages = ['fr', 'en', 'nl'];
$test_keys = [
    'machines',
    'outils-agricoles', 
    'produits-laitiers',
    'protections',
    'semences'
];

echo "=== TESTING CATEGORY TRANSLATIONS ===\n\n";

foreach ($languages as $lang) {
    echo "🌍 Language: " . strtoupper($lang) . "\n";
    
    $translations = include "resources/lang/$lang/app.php";
    $categories = $translations['categories'] ?? [];
    
    foreach ($test_keys as $key) {
        $translation = $categories[$key] ?? 'MISSING';
        echo "  $key => $translation\n";
    }
    echo "\n";
}

echo "=== TESTING COMPLETE ===\n";
?>
