<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== VÉRIFICATION DES TRADUCTIONS ===\n";

// Vérifier les catégories concernées
$categories = ['machines', 'outils-agricoles', 'protections'];

foreach($categories as $categorySlug) {
    echo "\n--- CATÉGORIE: " . strtoupper($categorySlug) . " ---\n";
    
    $category = Category::where('slug', $categorySlug)->first();
    if (!$category) {
        echo "❌ Catégorie non trouvée: $categorySlug\n";
        continue;
    }
    
    $products = Product::where('category_id', $category->id)->get();
    
    foreach($products as $product) {
        echo "ID: " . $product->id . " | " . $product->name . "\n";
        echo "  EN: " . ($product->name_en ?? '❌ MANQUANT') . "\n";
        echo "  NL: " . ($product->name_nl ?? '❌ MANQUANT') . "\n";
        echo "  Description EN: " . ($product->description_en ?? '❌ MANQUANT') . "\n";
        echo "  Description NL: " . ($product->description_nl ?? '❌ MANQUANT') . "\n";
        echo "  Unité EN: " . ($product->unit_en ?? '❌ MANQUANT') . "\n";
        echo "  Unité NL: " . ($product->unit_nl ?? '❌ MANQUANT') . "\n";
        echo "  ---\n";
    }
}

echo "\n=== CORRECTION DES TRADUCTIONS ===\n";

// Corrections pour MACHINES
$machinesCategory = Category::where('slug', 'machines')->first();
$machinesProducts = Product::where('category_id', $machinesCategory->id)->get();

$machinesTranslations = [
    'Tondeuse Professionnelle' => [
        'name_en' => 'Professional Mower',
        'name_nl' => 'Professionele Maaier',
        'description_en' => 'High-performance professional mower for green space maintenance',
        'description_nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Motoculteur' => [
        'name_en' => 'Rototiller',
        'name_nl' => 'Motoreg',
        'description_en' => 'Powerful rototiller for soil work',
        'description_nl' => 'Krachtige motoreg voor grondbewerking',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Tondeuse Autoportée' => [
        'name_en' => 'Riding Mower',
        'name_nl' => 'Zitmaaier',
        'description_en' => 'Riding mower for large areas',
        'description_nl' => 'Zitmaaier voor grote oppervlakten',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Épandeur d\'Engrais' => [
        'name_en' => 'Fertilizer Spreader',
        'name_nl' => 'Meststrooier',
        'description_en' => 'Spreader for uniform fertilizer distribution',
        'description_nl' => 'Strooier voor gelijkmatige mestverspreiding',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Pulvérisateur Agricole' => [
        'name_en' => 'Agricultural Sprayer',
        'name_nl' => 'Landbouwspuit',
        'description_en' => 'Professional sprayer for treatments',
        'description_nl' => 'Professionele spuit voor behandelingen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

foreach($machinesProducts as $product) {
    if (isset($machinesTranslations[$product->name])) {
        $translations = $machinesTranslations[$product->name];
        $product->update($translations);
        echo "✅ Machines - Traduit: " . $product->name . "\n";
    }
}

// Corrections pour OUTILS AGRICOLES
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$outilsProducts = Product::where('category_id', $outilsCategory->id)->get();

$outilsTranslations = [
    'Bêche Professionnelle' => [
        'name_en' => 'Professional Spade',
        'name_nl' => 'Professionele Spade',
        'description_en' => 'Sturdy tempered steel spade',
        'description_nl' => 'Stevige gehard stalen spade',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Sécateur de Qualité' => [
        'name_en' => 'Quality Pruner',
        'name_nl' => 'Kwaliteitssnoeischaar',
        'description_en' => 'Ergonomic pruner with sharp blades',
        'description_nl' => 'Ergonomische snoeischaar met scherpe messen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Râteau Multi-Usage' => [
        'name_en' => 'Multi-Purpose Rake',
        'name_nl' => 'Multifunctionele Hark',
        'description_en' => 'Versatile rake for all tasks',
        'description_nl' => 'Veelzijdige hark voor alle taken',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Houe de Précision' => [
        'name_en' => 'Precision Hoe',
        'name_nl' => 'Precisiehouwtje',
        'description_en' => 'Precise hoe for work between rows',
        'description_nl' => 'Precieze houw voor werk tussen rijen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Fourche à Fumier' => [
        'name_en' => 'Manure Fork',
        'name_nl' => 'Mestvork',
        'description_en' => 'Specialized fork for manure handling',
        'description_nl' => 'Gespecialiseerde vork voor mestbehandeling',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

foreach($outilsProducts as $product) {
    if (isset($outilsTranslations[$product->name])) {
        $translations = $outilsTranslations[$product->name];
        $product->update($translations);
        echo "✅ Outils - Traduit: " . $product->name . "\n";
    }
}

// Corrections pour PROTECTIONS
$protectionsCategory = Category::where('slug', 'protections')->first();
$protectionsProducts = Product::where('category_id', $protectionsCategory->id)->get();

$protectionsTranslations = [
    'Gants de Protection Agricole' => [
        'name_en' => 'Agricultural Protection Gloves',
        'name_nl' => 'Landbouwbeschermingshandschoenen',
        'description_en' => 'Chemical-resistant gloves',
        'description_nl' => 'Chemisch bestendige handschoenen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Masque Respiratoire' => [
        'name_en' => 'Respiratory Mask',
        'name_nl' => 'Ademhalingsmasker',
        'description_en' => 'Filter mask for respiratory protection',
        'description_nl' => 'Filtermasker voor ademhalingsbescherming',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Lunettes de Sécurité' => [
        'name_en' => 'Safety Glasses',
        'name_nl' => 'Veiligheidsbril',
        'description_en' => 'Anti-splash protection glasses',
        'description_nl' => 'Anti-spat beschermingsbril',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Combinaison de Protection' => [
        'name_en' => 'Protective Suit',
        'name_nl' => 'Beschermingspak',
        'description_en' => 'Waterproof suit for spraying',
        'description_nl' => 'Waterdicht pak voor spuiten',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    'Bottes de Sécurité' => [
        'name_en' => 'Safety Boots',
        'name_nl' => 'Veiligheidslaarzen',
        'description_en' => 'Reinforced puncture-resistant boots',
        'description_nl' => 'Versterkte punctiebestendige laarzen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

foreach($protectionsProducts as $product) {
    if (isset($protectionsTranslations[$product->name])) {
        $translations = $protectionsTranslations[$product->name];
        $product->update($translations);
        echo "✅ Protections - Traduit: " . $product->name . "\n";
    }
}

echo "\n=== VÉRIFICATION FINALE ===\n";
foreach($categories as $categorySlug) {
    $category = Category::where('slug', $categorySlug)->first();
    $products = Product::where('category_id', $category->id)->get();
    $allTranslated = true;
    
    foreach($products as $product) {
        if (!$product->name_en || !$product->name_nl || !$product->description_en || !$product->description_nl) {
            $allTranslated = false;
            break;
        }
    }
    
    echo ($allTranslated ? "✅" : "❌") . " " . strtoupper($categorySlug) . " - " . $products->count() . " produits\n";
}

echo "\n🎯 TRADUCTIONS CORRIGÉES AVEC SUCCÈS!\n";

?>
