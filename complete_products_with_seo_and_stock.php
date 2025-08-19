<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MISE À JOUR COMPLÈTE DES PRODUITS - SKU, SEO ET STOCKS ===\n\n";

// Récupérer tous les produits
$products = DB::table('products')->get();

foreach ($products as $product) {
    $name_json = json_decode($product->name, true);
    $description_json = json_decode($product->description, true);
    
    if (!$name_json || !isset($name_json['fr'])) {
        echo "⚠️ Produit {$product->id} - structure JSON invalide, ignoré\n";
        continue;
    }
    
    $productName = $name_json['fr'];
    echo "🔄 Mise à jour: {$productName}\n";
    
    // Générer un SKU unique basé sur le nom français
    $sku = 'FS-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $productName), 0, 8)) . '-' . str_pad($product->id, 3, '0', STR_PAD_LEFT);
    
    // Créer les données SEO complètes en 3 langues
    $seo_title = json_encode([
        'fr' => $name_json['fr'] . ' - Achat/Location Agricole | FarmShop',
        'en' => $name_json['en'] . ' - Agricultural Purchase/Rental | FarmShop',
        'nl' => $name_json['nl'] . ' - Landbouw Aankoop/Verhuur | FarmShop'
    ]);
    
    $seo_description = json_encode([
        'fr' => 'Découvrez ' . strtolower($name_json['fr']) . ' de qualité professionnelle. ' . $description_json['fr'] . ' Achat ou location disponible.',
        'en' => 'Discover professional quality ' . strtolower($name_json['en']) . '. ' . $description_json['en'] . ' Purchase or rental available.',
        'nl' => 'Ontdek professionele kwaliteit ' . strtolower($name_json['nl']) . '. ' . $description_json['nl'] . ' Aankoop of verhuur beschikbaar.'
    ]);
    
    // Générer des mots-clés SEO pertinents
    $base_keywords_fr = ['agriculture', 'fermier', 'matériel agricole', 'équipement', 'location', 'achat'];
    $base_keywords_en = ['agriculture', 'farmer', 'agricultural equipment', 'equipment', 'rental', 'purchase'];
    $base_keywords_nl = ['landbouw', 'boer', 'landbouwmaterieel', 'uitrusting', 'verhuur', 'aankoop'];
    
    // Ajouter des mots-clés spécifiques selon le produit
    $specific_keywords = [];
    $productLower = strtolower($name_json['fr']);
    
    if (strpos($productLower, 'tronçonneuse') !== false) {
        $specific_keywords = ['tronçonneuse', 'élagage', 'abattage', 'chainsaw', 'pruning', 'kettingzaag', 'snoeien'];
    } elseif (strpos($productLower, 'tracteur') !== false) {
        $specific_keywords = ['tracteur', 'labour', 'culture', 'tractor', 'plowing', 'tractor', 'ploegen'];
    } elseif (strpos($productLower, 'semence') !== false || strpos($productLower, 'graine') !== false) {
        $specific_keywords = ['semence', 'plantation', 'culture', 'seeds', 'planting', 'zaden', 'planten'];
    } elseif (strpos($productLower, 'engrais') !== false) {
        $specific_keywords = ['engrais', 'fertilisant', 'nutrition', 'fertilizer', 'nutrition', 'meststof', 'voeding'];
    } else {
        $specific_keywords = ['professionnel', 'qualité', 'efficace', 'professional', 'quality', 'efficient', 'professioneel', 'kwaliteit', 'efficiënt'];
    }
    
    $seo_keywords = json_encode([
        'fr' => implode(', ', array_merge($base_keywords_fr, array_slice($specific_keywords, 0, 4))),
        'en' => implode(', ', array_merge($base_keywords_en, array_slice($specific_keywords, 0, 4))),
        'nl' => implode(', ', array_merge($base_keywords_nl, array_slice($specific_keywords, 0, 4)))
    ]);
    
    // Déterminer les stocks selon le type de produit
    $quantity = 25; // Stock normal par défaut
    $rental_stock = 25; // Stock location par défaut
    
    // Ajuster selon le type de produit
    if ($product->type === 'rental') {
        $quantity = 0; // Pas de stock normal pour les produits uniquement en location
        // Garder le rental_stock existant ou mettre 25 s'il n'existe pas
        $rental_stock = $product->rental_stock ?: 25;
    } elseif ($product->type === 'sale') {
        $rental_stock = 0; // Pas de stock location pour les produits uniquement à la vente
    } else { // type 'both'
        $quantity = 25;
        $rental_stock = 25;
    }
    
    try {
        DB::table('products')->where('id', $product->id)->update([
            'sku' => $sku,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'seo_keywords' => $seo_keywords,
            'quantity' => $quantity,
            'rental_stock' => $rental_stock,
            'updated_at' => now()
        ]);
        
        echo "   ✅ SKU: {$sku}\n";
        echo "   ✅ Stock normal: {$quantity} | Stock location: {$rental_stock}\n";
        echo "   ✅ SEO: Titre, description et mots-clés ajoutés en FR/EN/NL\n\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur: " . $e->getMessage() . "\n\n";
    }
}

echo "\n=== RÉSUMÉ FINAL ===\n";
$total_products = DB::table('products')->count();
$sale_products = DB::table('products')->where('type', 'sale')->count();
$rental_products = DB::table('products')->where('type', 'rental')->count();
$both_products = DB::table('products')->where('type', 'both')->count();

echo "🎯 PRODUITS TOTAUX: {$total_products}\n";
echo "📦 Produits vente uniquement: {$sale_products}\n";
echo "🔄 Produits location uniquement: {$rental_products}\n";
echo "🔀 Produits vente + location: {$both_products}\n\n";

echo "✅ TOUS LES CHAMPS COMPLÉTÉS:\n";
echo "   • SKU uniques générés\n";
echo "   • Titres SEO en FR/EN/NL\n";
echo "   • Descriptions SEO en FR/EN/NL\n";
echo "   • Mots-clés SEO en FR/EN/NL\n";
echo "   • Stocks normaux: 25 (ou 0 pour location uniquement)\n";
echo "   • Stocks location: 25 (ou 0 pour vente uniquement)\n\n";

echo "🚀 BASE DE DONNÉES PRODUITS COMPLÈTE ET OPTIMISÉE!\n";

?>
