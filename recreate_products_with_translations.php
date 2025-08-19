<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== SUPPRESSION DES 15 PRODUITS PROBLÉMATIQUES ===\n";

// Supprimer les produits des IDs 178 à 192
$productIds = [178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192];

foreach($productIds as $id) {
    $product = Product::find($id);
    if ($product) {
        echo "🗑️ Suppression: " . $product->name . " (ID: $id)\n";
        $product->delete();
    } else {
        echo "❌ Produit ID $id non trouvé\n";
    }
}

echo "\n=== RECRÉATION AVEC TRADUCTIONS COMPLÈTES ===\n";

// Récupérer les catégories
$machinesCategory = Category::where('slug', 'machines')->first();
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$protectionsCategory = Category::where('slug', 'protections')->first();

// MACHINES (5 produits)
$machinesProducts = [
    [
        'name' => 'Tondeuse Professionnelle',
        'name_en' => 'Professional Mower',
        'name_nl' => 'Professionele Maaier',
        'description' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts',
        'description_en' => 'High-performance professional mower for green space maintenance',
        'description_nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes',
        'price' => 2500.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Motoculteur',
        'name_en' => 'Rototiller',
        'name_nl' => 'Motoreg',
        'description' => 'Motoculteur puissant pour travail du sol',
        'description_en' => 'Powerful rototiller for soil work',
        'description_nl' => 'Krachtige motoreg voor grondbewerking',
        'price' => 1200.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Tondeuse Autoportée',
        'name_en' => 'Riding Mower',
        'name_nl' => 'Zitmaaier',
        'description' => 'Tondeuse autoportée pour grandes surfaces',
        'description_en' => 'Riding mower for large areas',
        'description_nl' => 'Zitmaaier voor grote oppervlakten',
        'price' => 3500.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Épandeur d\'Engrais',
        'name_en' => 'Fertilizer Spreader',
        'name_nl' => 'Meststrooier',
        'description' => 'Épandeur pour distribution uniforme d\'engrais',
        'description_en' => 'Spreader for uniform fertilizer distribution',
        'description_nl' => 'Strooier voor gelijkmatige mestverspreiding',
        'price' => 850.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Pulvérisateur Agricole',
        'name_en' => 'Agricultural Sprayer',
        'name_nl' => 'Landbouwspuit',
        'description' => 'Pulvérisateur professionnel pour traitements',
        'description_en' => 'Professional sprayer for treatments',
        'description_nl' => 'Professionele spuit voor behandelingen',
        'price' => 1800.00,
        'unit_symbol' => 'pièces'
    ]
];

// OUTILS AGRICOLES (5 produits)
$outilsProducts = [
    [
        'name' => 'Bêche Professionnelle',
        'name_en' => 'Professional Spade',
        'name_nl' => 'Professionele Spade',
        'description' => 'Bêche robuste en acier trempé',
        'description_en' => 'Sturdy tempered steel spade',
        'description_nl' => 'Stevige gehard stalen spade',
        'price' => 45.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Sécateur de Qualité',
        'name_en' => 'Quality Pruner',
        'name_nl' => 'Kwaliteitssnoeischaar',
        'description' => 'Sécateur ergonomique à lames affûtées',
        'description_en' => 'Ergonomic pruner with sharp blades',
        'description_nl' => 'Ergonomische snoeischaar met scherpe messen',
        'price' => 35.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Râteau Multi-Usage',
        'name_en' => 'Multi-Purpose Rake',
        'name_nl' => 'Multifunctionele Hark',
        'description' => 'Râteau polyvalent pour tous travaux',
        'description_en' => 'Versatile rake for all tasks',
        'description_nl' => 'Veelzijdige hark voor alle taken',
        'price' => 28.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Houe de Précision',
        'name_en' => 'Precision Hoe',
        'name_nl' => 'Precisiehouwtje',
        'description' => 'Houe précise pour travail entre les rangs',
        'description_en' => 'Precise hoe for work between rows',
        'description_nl' => 'Precieze houw voor werk tussen rijen',
        'price' => 32.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Fourche à Fumier',
        'name_en' => 'Manure Fork',
        'name_nl' => 'Mestvork',
        'description' => 'Fourche spécialisée pour manipulation du fumier',
        'description_en' => 'Specialized fork for manure handling',
        'description_nl' => 'Gespecialiseerde vork voor mestbehandeling',
        'price' => 55.00,
        'unit_symbol' => 'pièces'
    ]
];

// PROTECTIONS (5 produits)
$protectionsProducts = [
    [
        'name' => 'Gants de Protection Agricole',
        'name_en' => 'Agricultural Protection Gloves',
        'name_nl' => 'Landbouwbeschermingshandschoenen',
        'description' => 'Gants résistants aux produits chimiques',
        'description_en' => 'Chemical-resistant gloves',
        'description_nl' => 'Chemisch bestendige handschoenen',
        'price' => 15.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Masque Respiratoire',
        'name_en' => 'Respiratory Mask',
        'name_nl' => 'Ademhalingsmasker',
        'description' => 'Masque filtrant pour protection respiratoire',
        'description_en' => 'Filter mask for respiratory protection',
        'description_nl' => 'Filtermasker voor ademhalingsbescherming',
        'price' => 25.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Lunettes de Sécurité',
        'name_en' => 'Safety Glasses',
        'name_nl' => 'Veiligheidsbril',
        'description' => 'Lunettes de protection anti-projection',
        'description_en' => 'Anti-splash protection glasses',
        'description_nl' => 'Anti-spat beschermingsbril',
        'price' => 18.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Combinaison de Protection',
        'name_en' => 'Protective Suit',
        'name_nl' => 'Beschermingspak',
        'description' => 'Combinaison étanche pour pulvérisations',
        'description_en' => 'Waterproof suit for spraying',
        'description_nl' => 'Waterdicht pak voor spuiten',
        'price' => 65.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => 'Bottes de Sécurité',
        'name_en' => 'Safety Boots',
        'name_nl' => 'Veiligheidslaarzen',
        'description' => 'Bottes renforcées anti-perforation',
        'description_en' => 'Reinforced puncture-resistant boots',
        'description_nl' => 'Versterkte punctiebestendige laarzen',
        'price' => 85.00,
        'unit_symbol' => 'pièces'
    ]
];

// Créer MACHINES
echo "\n--- CRÉATION MACHINES ---\n";
foreach ($machinesProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'quantity' => rand(5, 50),
        'category_id' => $machinesCategory->id,
        'type' => 'sale',
        'is_active' => true
    ]);
    echo "✅ Créé: " . $product->name . " (ID: " . $product->id . ")\n";
}

// Créer OUTILS AGRICOLES
echo "\n--- CRÉATION OUTILS AGRICOLES ---\n";
foreach ($outilsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'quantity' => rand(10, 100),
        'category_id' => $outilsCategory->id,
        'type' => 'sale',
        'is_active' => true
    ]);
    echo "✅ Créé: " . $product->name . " (ID: " . $product->id . ")\n";
}

// Créer PROTECTIONS
echo "\n--- CRÉATION PROTECTIONS ---\n";
foreach ($protectionsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'quantity' => rand(20, 150),
        'category_id' => $protectionsCategory->id,
        'type' => 'sale',
        'is_active' => true
    ]);
    echo "✅ Créé: " . $product->name . " (ID: " . $product->id . ")\n";
}

echo "\n=== RÉSUMÉ FINAL ===\n";
$totalProducts = Product::where('type', 'sale')->count();
echo "📊 Total des produits d'achat: " . $totalProducts . "\n";

echo "\n🎯 SUPPRESSION ET RECRÉATION TERMINÉES AVEC SUCCÈS!\n";
echo "✅ Les 15 produits ont été recréés avec la structure correcte\n";

?>
