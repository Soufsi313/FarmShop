<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNOSTIC PRODUIT BROYEUR ===\n\n";

// 1. Trouver le produit
$product = App\Models\Product::where('slug', 'broyeur-de-vegetaux-1027')->first();

if (!$product) {
    echo "❌ Produit non trouvé\n";
    exit;
}

echo "✅ Produit trouvé:\n";
echo "ID: {$product->id}\n";
echo "Name (DB): " . ($product->name ?? 'NULL') . "\n";
echo "Description (DB): " . (strlen($product->description ?? '') > 0 ? substr($product->description, 0, 100) . '...' : 'NULL') . "\n";
echo "Short Description (DB): " . ($product->short_description ?? 'NULL') . "\n\n";

// 2. Vérifier les tables de traduction possibles
$tables = DB::select('SHOW TABLES');
$tableNames = [];
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    if (str_contains($tableName, 'translation') || str_contains($tableName, 'product')) {
        $tableNames[] = $tableName;
    }
}

echo "📋 Tables liées aux produits/traductions:\n";
foreach ($tableNames as $table) {
    echo "  • $table\n";
}
echo "\n";

// 3. Tester notre fonction trans_product
echo "🔍 Test de la fonction trans_product:\n";
try {
    $frName = trans_product($product, 'name', 'fr');
    $enName = trans_product($product, 'name', 'en');
    $nlName = trans_product($product, 'name', 'nl');
    
    echo "FR: $frName\n";
    echo "EN: $enName\n";
    echo "NL: $nlName\n\n";
    
    $frDesc = trans_product($product, 'description', 'fr');
    $enDesc = trans_product($product, 'description', 'en');
    
    echo "Description FR: " . substr($frDesc, 0, 100) . "...\n";
    echo "Description EN: " . substr($enDesc, 0, 100) . "...\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
