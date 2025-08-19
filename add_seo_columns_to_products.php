<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== AJOUT DES COLONNES SEO ET SKU À LA TABLE PRODUCTS ===\n\n";

try {
    // Vérifier si les colonnes existent déjà
    $columns = Schema::getColumnListing('products');
    
    echo "📋 Colonnes existantes dans products:\n";
    foreach ($columns as $column) {
        echo "   - $column\n";
    }
    
    echo "\n🔧 Ajout des colonnes manquantes...\n";
    
    // Ajouter les colonnes manquantes
    $columnsToAdd = [
        'sku' => "ADD COLUMN sku VARCHAR(50) UNIQUE AFTER id",
        'seo_title' => "ADD COLUMN seo_title JSON NULL",
        'seo_description' => "ADD COLUMN seo_description JSON NULL", 
        'seo_keywords' => "ADD COLUMN seo_keywords JSON NULL"
    ];
    
    foreach ($columnsToAdd as $columnName => $sql) {
        if (!in_array($columnName, $columns)) {
            try {
                DB::statement("ALTER TABLE products $sql");
                echo "✅ Colonne '$columnName' ajoutée\n";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                    echo "⚠️ Colonne '$columnName' existe déjà\n";
                } else {
                    echo "❌ Erreur pour '$columnName': " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "✅ Colonne '$columnName' existe déjà\n";
        }
    }
    
    echo "\n🎯 STRUCTURE DE TABLE MISE À JOUR!\n";
    echo "✅ SKU (code produit unique)\n";
    echo "✅ SEO Title (titre SEO multilingue)\n";
    echo "✅ SEO Description (description SEO multilingue)\n";
    echo "✅ SEO Keywords (mots-clés SEO multilingues)\n\n";
    
    echo "🚀 Vous pouvez maintenant exécuter le script de mise à jour des produits!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}

?>
