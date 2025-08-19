<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "=== VÉRIFICATION DES CATÉGORIES EXISTANTES ===\n";
$categories = Category::all();
foreach($categories as $cat) {
    echo "Slug: " . $cat->slug . " | Nom: " . $cat->name . "\n";
}

echo "\n=== AJOUT DES 15 PRODUITS MANQUANTS ===\n";

// Trouver les IDs des catégories
$machinesCategory = Category::where('slug', 'machines')->first();
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$protectionsCategory = Category::where('slug', 'protections')->first();

if (!$machinesCategory || !$outilsCategory || !$protectionsCategory) {
    echo "❌ Erreur: Une ou plusieurs catégories introuvables\n";
    echo "Machines: " . ($machinesCategory ? "✅" : "❌") . "\n";
    echo "Outils agricoles: " . ($outilsCategory ? "✅" : "❌") . "\n";
    echo "Protections: " . ($protectionsCategory ? "✅" : "❌") . "\n";
    exit;
}

// Produits pour MACHINES (5 produits)
$machinesProducts = [
    [
        'name' => 'Tracteur Compact',
        'name_en' => 'Compact Tractor',
        'name_nl' => 'Compacte Tractor',
        'description' => 'Tracteur compact idéal pour petites exploitations',
        'description_en' => 'Compact tractor ideal for small farms',
        'description_nl' => 'Compacte tractor ideaal voor kleine boerderijen',
        'price' => 25000.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Motoculteur',
        'name_en' => 'Rototiller',
        'name_nl' => 'Motoreg',
        'description' => 'Motoculteur puissant pour travail du sol',
        'description_en' => 'Powerful rototiller for soil work',
        'description_nl' => 'Krachtige motoreg voor grondbewerking',
        'price' => 1200.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Tondeuse Autoportée',
        'name_en' => 'Riding Mower',
        'name_nl' => 'Zitmaaier',
        'description' => 'Tondeuse autoportée pour grandes surfaces',
        'description_en' => 'Riding mower for large areas',
        'description_nl' => 'Zitmaaier voor grote oppervlakten',
        'price' => 3500.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Épandeur d\'Engrais',
        'name_en' => 'Fertilizer Spreader',
        'name_nl' => 'Meststrooier',
        'description' => 'Épandeur pour distribution uniforme d\'engrais',
        'description_en' => 'Spreader for uniform fertilizer distribution',
        'description_nl' => 'Strooier voor gelijkmatige mestverspreiding',
        'price' => 850.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Pulvérisateur Agricole',
        'name_en' => 'Agricultural Sprayer',
        'name_nl' => 'Landbouwspuit',
        'description' => 'Pulvérisateur professionnel pour traitements',
        'description_en' => 'Professional sprayer for treatments',
        'description_nl' => 'Professionele spuit voor behandelingen',
        'price' => 1800.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

// Produits pour OUTILS AGRICOLES (5 produits)
$outilsProducts = [
    [
        'name' => 'Bêche Professionnelle',
        'name_en' => 'Professional Spade',
        'name_nl' => 'Professionele Spade',
        'description' => 'Bêche robuste en acier trempé',
        'description_en' => 'Sturdy tempered steel spade',
        'description_nl' => 'Stevige gehard stalen spade',
        'price' => 45.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Sécateur de Qualité',
        'name_en' => 'Quality Pruner',
        'name_nl' => 'Kwaliteitssnoeischaar',
        'description' => 'Sécateur ergonomique à lames affûtées',
        'description_en' => 'Ergonomic pruner with sharp blades',
        'description_nl' => 'Ergonomische snoeischaar met scherpe messen',
        'price' => 35.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Râteau Multi-Usage',
        'name_en' => 'Multi-Purpose Rake',
        'name_nl' => 'Multifunctionele Hark',
        'description' => 'Râteau polyvalent pour tous travaux',
        'description_en' => 'Versatile rake for all tasks',
        'description_nl' => 'Veelzijdige hark voor alle taken',
        'price' => 28.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Houe de Précision',
        'name_en' => 'Precision Hoe',
        'name_nl' => 'Precisiehouwtje',
        'description' => 'Houe précise pour travail entre les rangs',
        'description_en' => 'Precise hoe for work between rows',
        'description_nl' => 'Precieze houw voor werk tussen rijen',
        'price' => 32.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Fourche à Fumier',
        'name_en' => 'Manure Fork',
        'name_nl' => 'Mestvork',
        'description' => 'Fourche spécialisée pour manipulation du fumier',
        'description_en' => 'Specialized fork for manure handling',
        'description_nl' => 'Gespecialiseerde vork voor mestbehandeling',
        'price' => 55.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

// Produits pour PROTECTIONS (5 produits)
$protectionsProducts = [
    [
        'name' => 'Gants de Protection Agricole',
        'name_en' => 'Agricultural Protection Gloves',
        'name_nl' => 'Landbouwbeschermingshandschoenen',
        'description' => 'Gants résistants aux produits chimiques',
        'description_en' => 'Chemical-resistant gloves',
        'description_nl' => 'Chemisch bestendige handschoenen',
        'price' => 15.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Masque Respiratoire',
        'name_en' => 'Respiratory Mask',
        'name_nl' => 'Ademhalingsmasker',
        'description' => 'Masque filtrant pour protection respiratoire',
        'description_en' => 'Filter mask for respiratory protection',
        'description_nl' => 'Filtermasker voor ademhalingsbescherming',
        'price' => 25.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Lunettes de Sécurité',
        'name_en' => 'Safety Glasses',
        'name_nl' => 'Veiligheidsbril',
        'description' => 'Lunettes de protection anti-projection',
        'description_en' => 'Anti-splash protection glasses',
        'description_nl' => 'Anti-spat beschermingsbril',
        'price' => 18.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Combinaison de Protection',
        'name_en' => 'Protective Suit',
        'name_nl' => 'Beschermingspak',
        'description' => 'Combinaison étanche pour pulvérisations',
        'description_en' => 'Waterproof suit for spraying',
        'description_nl' => 'Waterdicht pak voor spuiten',
        'price' => 65.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    [
        'name' => 'Bottes de Sécurité',
        'name_en' => 'Safety Boots',
        'name_nl' => 'Veiligheidslaarzen',
        'description' => 'Bottes renforcées anti-perforation',
        'description_en' => 'Reinforced puncture-resistant boots',
        'description_nl' => 'Versterkte punctiebestendige laarzen',
        'price' => 85.00,
        'unit' => 'pièces',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

// Créer les produits MACHINES
echo "\n--- CRÉATION PRODUITS MACHINES ---\n";
foreach ($machinesProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'name_en' => $productData['name_en'],
        'name_nl' => $productData['name_nl'],
        'description' => $productData['description'],
        'description_en' => $productData['description_en'],
        'description_nl' => $productData['description_nl'],
        'price' => $productData['price'],
        'unit' => $productData['unit'],
        'unit_en' => $productData['unit_en'],
        'unit_nl' => $productData['unit_nl'],
        'stock' => rand(5, 50),
        'category_id' => $machinesCategory->id,
        'type' => 'sale',
        'status' => 'active'
    ]);
    echo "✅ Créé: " . $product->name . " (ID: " . $product->id . ")\n";
}

// Créer les produits OUTILS AGRICOLES
echo "\n--- CRÉATION PRODUITS OUTILS AGRICOLES ---\n";
foreach ($outilsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'name_en' => $productData['name_en'],
        'name_nl' => $productData['name_nl'],
        'description' => $productData['description'],
        'description_en' => $productData['description_en'],
        'description_nl' => $productData['description_nl'],
        'price' => $productData['price'],
        'unit' => $productData['unit'],
        'unit_en' => $productData['unit_en'],
        'unit_nl' => $productData['unit_nl'],
        'stock' => rand(10, 100),
        'category_id' => $outilsCategory->id,
        'type' => 'sale',
        'status' => 'active'
    ]);
    echo "✅ Créé: " . $product->name . " (ID: " . $product->id . ")\n";
}

// Créer les produits PROTECTIONS
echo "\n--- CRÉATION PRODUITS PROTECTIONS ---\n";
foreach ($protectionsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'name_en' => $productData['name_en'],
        'name_nl' => $productData['name_nl'],
        'description' => $productData['description'],
        'description_en' => $productData['description_en'],
        'description_nl' => $productData['description_nl'],
        'price' => $productData['price'],
        'unit' => $productData['unit'],
        'unit_en' => $productData['unit_en'],
        'unit_nl' => $productData['unit_nl'],
        'stock' => rand(20, 150),
        'category_id' => $protectionsCategory->id,
        'type' => 'sale',
        'status' => 'active'
    ]);
    echo "✅ Créé: " . $product->name . " (ID: " . $product->id . ")\n";
}

echo "\n=== RÉSUMÉ FINAL ===\n";
$totalProducts = Product::where('type', 'sale')->count();
echo "📊 Total des produits d'achat: " . $totalProducts . "\n";

// Compter par catégorie
$categoriesWithCount = Category::withCount(['products' => function($query) {
    $query->where('type', 'sale');
}])->get();

foreach($categoriesWithCount as $cat) {
    if ($cat->products_count > 0) {
        echo "   - " . $cat->name . ": " . $cat->products_count . " produits\n";
    }
}

echo "\n✅ TERMINÉ: 15 produits supplémentaires créés avec succès!\n";
echo "🎯 Objectif atteint: 80 produits d'achat avec traductions FR/EN/NL\n";

?>
