<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Produits mentionnés par l'utilisateur
$missingProducts = [
    'epinards-frais-dwlo',
    'haricots-verts-h7vg', 
    'poireaux-5i0n'
];

echo "Vérification des produits manquants:\n\n";

foreach($missingProducts as $slug) {
    $product = App\Models\Product::where('slug', $slug)->first();
    if ($product) {
        echo "Slug: $slug\n";
        echo "Nom en DB: '{$product->name}'\n";
        echo "Catégorie: {$product->category->slug}\n";
        
        // Test traductions
        App::setLocale('fr');
        $fr = trans_product($product, 'name');
        App::setLocale('en');
        $en = trans_product($product, 'name');
        App::setLocale('nl');
        $nl = trans_product($product, 'name');
        
        echo "Traductions: FR='$fr' | EN='$en' | NL='$nl'\n\n";
    } else {
        echo "Produit non trouvé: $slug\n\n";
    }
}

// Listons tous les légumes pour voir lesquels manquent
echo "=== TOUS LES LEGUMES ===\n";
$legumes = App\Models\Product::whereHas('category', function($query) {
    $query->where('slug', 'legumes');
})->get(['slug', 'name']);

foreach($legumes as $legume) {
    $translationKey = "app.product_names.{$legume->slug}";
    $frTranslation = __($translationKey, [], 'fr');
    $hasTranslation = $frTranslation !== $translationKey ? '✅' : '❌';
    echo "$hasTranslation {$legume->slug}\n";
}
