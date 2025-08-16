<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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

foreach($testProducts as $categorySlug => $productSlug) {
    $product = App\Models\Product::where('slug', $productSlug)->first();
    $category = App\Models\Category::where('slug', $categorySlug)->first();
    
    if ($product && $category) {
        echo "=== CATEGORIE: " . strtoupper($categorySlug) . " ===\n";
        
        // Test catégorie
        App::setLocale('fr');
        $catFr = trans_category($category);
        App::setLocale('en');
        $catEn = trans_category($category);
        App::setLocale('nl');
        $catNl = trans_category($category);
        
        echo "Catégorie: FR=$catFr | EN=$catEn | NL=$catNl\n";
        
        // Test produit
        App::setLocale('fr');
        $prodFr = trans_product($product, 'name');
        App::setLocale('en');
        $prodEn = trans_product($product, 'name');
        App::setLocale('nl');
        $prodNl = trans_product($product, 'name');
        
        echo "Produit: FR=$prodFr | EN=$prodEn | NL=$prodNl\n\n";
    }
}
