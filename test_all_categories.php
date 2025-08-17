<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test d'un produit de chaque catégorie
$testProducts = [
    'fruits' => 'pommes-rouges-royal-gala-ozjz',
    'legumes' => 'carottes-bio-my8d',
    'cereales' => 'avoine-bio-25kg-ie46',
    'feculents' => 'pommes-de-terre-bintje-5kg-iagq',
    'produits-laitiers' => 'oeufs-fermiers-bio-x6-6i37',
    'outils-agricoles' => 'beche-inox-manche-bois-4o4e',
    'machines' => 'motoculteur-7cv-ejmo',
    'equipement' => 'remorque-basculante-6437',
    'semences' => 'graines-de-radis-bio-3hzf',
    'engrais' => 'compost-bio-40l-7knk',
    'irrigation' => 'tuyau-d039arrosage-25m',
    'protections' => 'voile-de-forcage-10m2-m6cj'
];

echo "Test des traductions par catégorie:\n\n";

foreach($testProducts as $category => $slug) {
    $product = App\Models\Product::where('slug', $slug)->first();
    
    if ($product) {
        echo "=== {$category} ===\n";
        echo "Slug: {$product->slug}\n";
        
        // Test FR
        App::setLocale('fr');
        echo "FR: " . trans_product($product, 'name') . "\n";
        
        // Test EN
        App::setLocale('en');
        echo "EN: " . trans_product($product, 'name') . "\n";
        
        // Test NL
        App::setLocale('nl');
        echo "NL: " . trans_product($product, 'name') . "\n\n";
    }
}
