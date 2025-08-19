<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== EXEMPLE PRODUIT QUI FONCTIONNE ===\n";
$goodProduct = Product::where('type', 'sale')->where('id', '<', 178)->first();
if ($goodProduct) {
    echo "Nom: " . $goodProduct->name . "\n";
    echo "Description: " . $goodProduct->description . "\n";
    echo "Unit: " . $goodProduct->unit_symbol . "\n";
    echo "Structure complète:\n";
    print_r($goodProduct->toArray());
}

echo "\n=== SUPPRESSION DES PRODUITS FOIRÉS ===\n";
$categories = ['machines', 'outils-agricoles', 'protections'];

foreach($categories as $categorySlug) {
    $category = Category::where('slug', $categorySlug)->first();
    if ($category) {
        $products = Product::where('category_id', $category->id)->get();
        foreach($products as $product) {
            echo "Suppression: " . $product->name . " (ID: " . $product->id . ")\n";
            $product->delete();
        }
    }
}

echo "\n=== RECRÉATION CORRECTE ===\n";

// Trouver les catégories
$machinesCategory = Category::where('slug', 'machines')->first();
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$protectionsCategory = Category::where('slug', 'protections')->first();

// MACHINES - 5 produits (STRUCTURE CORRECTE)
$machinesProducts = [
    [
        'name' => 'Tondeuse Professionnelle',
        'description' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts',
        'price' => 2500.00,
        'unit_symbol' => 'pièce',
        'slug' => 'tondeuse-professionnelle-correct'
    ],
    [
        'name' => 'Motoculteur',
        'description' => 'Motoculteur puissant pour travail du sol',
        'price' => 1200.00,
        'unit_symbol' => 'pièce',
        'slug' => 'motoculteur-correct'
    ],
    [
        'name' => 'Tondeuse Autoportée',
        'description' => 'Tondeuse autoportée pour grandes surfaces',
        'price' => 3500.00,
        'unit_symbol' => 'pièce',
        'slug' => 'tondeuse-autoportee-correct'
    ],
    [
        'name' => 'Épandeur d\'Engrais',
        'description' => 'Épandeur pour distribution uniforme d\'engrais',
        'price' => 850.00,
        'unit_symbol' => 'pièce',
        'slug' => 'epandeur-engrais-correct'
    ],
    [
        'name' => 'Pulvérisateur Agricole',
        'description' => 'Pulvérisateur professionnel pour traitements',
        'price' => 1800.00,
        'unit_symbol' => 'pièce',
        'slug' => 'pulverisateur-agricole-correct'
    ]
];

// OUTILS AGRICOLES - 5 produits
$outilsProducts = [
    [
        'name' => 'Bêche Professionnelle',
        'description' => 'Bêche robuste en acier trempé',
        'price' => 45.00,
        'unit_symbol' => 'pièce',
        'slug' => 'beche-professionnelle-correct'
    ],
    [
        'name' => 'Sécateur de Qualité',
        'description' => 'Sécateur ergonomique à lames affûtées',
        'price' => 35.00,
        'unit_symbol' => 'pièce',
        'slug' => 'secateur-qualite-correct'
    ],
    [
        'name' => 'Râteau Multi-Usage',
        'description' => 'Râteau polyvalent pour tous travaux',
        'price' => 28.00,
        'unit_symbol' => 'pièce',
        'slug' => 'rateau-multi-usage-correct'
    ],
    [
        'name' => 'Houe de Précision',
        'description' => 'Houe précise pour travail entre les rangs',
        'price' => 32.00,
        'unit_symbol' => 'pièce',
        'slug' => 'houe-precision-correct'
    ],
    [
        'name' => 'Fourche à Fumier',
        'description' => 'Fourche spécialisée pour manipulation du fumier',
        'price' => 55.00,
        'unit_symbol' => 'pièce',
        'slug' => 'fourche-fumier-correct'
    ]
];

// PROTECTIONS - 5 produits
$protectionsProducts = [
    [
        'name' => 'Gants de Protection Agricole',
        'description' => 'Gants résistants aux produits chimiques',
        'price' => 15.00,
        'unit_symbol' => 'pièce',
        'slug' => 'gants-protection-agricole-correct'
    ],
    [
        'name' => 'Masque Respiratoire',
        'description' => 'Masque filtrant pour protection respiratoire',
        'price' => 25.00,
        'unit_symbol' => 'pièce',
        'slug' => 'masque-respiratoire-correct'
    ],
    [
        'name' => 'Lunettes de Sécurité',
        'description' => 'Lunettes de protection anti-projection',
        'price' => 18.00,
        'unit_symbol' => 'pièce',
        'slug' => 'lunettes-securite-correct'
    ],
    [
        'name' => 'Combinaison de Protection',
        'description' => 'Combinaison étanche pour pulvérisations',
        'price' => 65.00,
        'unit_symbol' => 'pièce',
        'slug' => 'combinaison-protection-correct'
    ],
    [
        'name' => 'Bottes de Sécurité',
        'description' => 'Bottes renforcées anti-perforation',
        'price' => 85.00,
        'unit_symbol' => 'pièce',
        'slug' => 'bottes-securite-correct'
    ]
];

// Créer les produits MACHINES
echo "\n--- MACHINES ---\n";
foreach ($machinesProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'slug' => $productData['slug'],
        'quantity' => rand(5, 50),
        'category_id' => $machinesCategory->id,
        'type' => 'sale',
        'is_active' => true
    ]);
    echo "✅ " . $product->name . " (ID: " . $product->id . ")\n";
}

// Créer les produits OUTILS AGRICOLES
echo "\n--- OUTILS AGRICOLES ---\n";
foreach ($outilsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'slug' => $productData['slug'],
        'quantity' => rand(10, 100),
        'category_id' => $outilsCategory->id,
        'type' => 'sale',
        'is_active' => true
    ]);
    echo "✅ " . $product->name . " (ID: " . $product->id . ")\n";
}

// Créer les produits PROTECTIONS
echo "\n--- PROTECTIONS ---\n";
foreach ($protectionsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'slug' => $productData['slug'],
        'quantity' => rand(20, 150),
        'category_id' => $protectionsCategory->id,
        'type' => 'sale',
        'is_active' => true
    ]);
    echo "✅ " . $product->name . " (ID: " . $product->id . ")\n";
}

echo "\n✅ CORRIGÉ: 15 produits recréés avec la BONNE structure comme les autres !\n";

?>
