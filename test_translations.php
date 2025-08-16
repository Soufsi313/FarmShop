<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test de la fonction trans_product avec les pommes
$product = App\Models\Product::where('slug', 'pommes-rouges-royal-gala-ozjz')->first();

if ($product) {
    echo "Test de traduction du produit: {$product->slug}\n\n";
    
    // Test en français (par défaut)
    App::setLocale('fr');
    echo "FR: " . trans_product($product, 'name') . "\n";
    
    // Test en anglais
    App::setLocale('en');
    echo "EN: " . trans_product($product, 'name') . "\n";
    
    // Test en néerlandais
    App::setLocale('nl');
    echo "NL: " . trans_product($product, 'name') . "\n";
} else {
    echo "Produit non trouvé\n";
}
