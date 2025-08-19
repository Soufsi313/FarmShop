<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== SUPPRESSION COMPLÈTE DES 3 CATÉGORIES ===\n";

// Supprimer TOUS les produits des 3 catégories
$categories = ['machines', 'outils-agricoles', 'protections'];

foreach($categories as $categorySlug) {
    $category = Category::where('slug', $categorySlug)->first();
    if ($category) {
        $products = Product::where('category_id', $category->id)->get();
        echo "Catégorie: " . $category->name . " - " . $products->count() . " produits à supprimer\n";
        
        foreach($products as $product) {
            echo "Suppression: " . $product->name . " (ID: " . $product->id . ")\n";
            $product->delete();
        }
    }
}

echo "\n=== RECRÉATION AVEC TRADUCTIONS COMPLÈTES ===\n";

// Trouver les catégories
$machinesCategory = Category::where('slug', 'machines')->first();
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$protectionsCategory = Category::where('slug', 'protections')->first();

// MACHINES - 5 produits
$machinesProducts = [
    [
        'name' => json_encode([
            'fr' => 'Tondeuse Professionnelle',
            'en' => 'Professional Mower',
            'nl' => 'Professionele Maaier'
        ]),
        'description' => json_encode([
            'fr' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts',
            'en' => 'High-performance professional mower for green space maintenance',
            'nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes'
        ]),
        'price' => 2500.00,
        'unit_symbol' => 'pièce',
        'slug' => 'tondeuse-professionnelle-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Motoculteur',
            'en' => 'Rototiller',
            'nl' => 'Motoreg'
        ]),
        'description' => json_encode([
            'fr' => 'Motoculteur puissant pour travail du sol',
            'en' => 'Powerful rototiller for soil work',
            'nl' => 'Krachtige motoreg voor grondbewerking'
        ]),
        'price' => 1200.00,
        'unit_symbol' => 'pièce',
        'slug' => 'motoculteur-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Tondeuse Autoportée',
            'en' => 'Riding Mower',
            'nl' => 'Zitmaaier'
        ]),
        'description' => json_encode([
            'fr' => 'Tondeuse autoportée pour grandes surfaces',
            'en' => 'Riding mower for large areas',
            'nl' => 'Zitmaaier voor grote oppervlakten'
        ]),
        'price' => 3500.00,
        'unit_symbol' => 'pièce',
        'slug' => 'tondeuse-autoportee-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Épandeur d\'Engrais',
            'en' => 'Fertilizer Spreader',
            'nl' => 'Meststrooier'
        ]),
        'description' => json_encode([
            'fr' => 'Épandeur pour distribution uniforme d\'engrais',
            'en' => 'Spreader for uniform fertilizer distribution',
            'nl' => 'Strooier voor gelijkmatige mestverspreiding'
        ]),
        'price' => 850.00,
        'unit_symbol' => 'pièce',
        'slug' => 'epandeur-engrais-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Pulvérisateur Agricole',
            'en' => 'Agricultural Sprayer',
            'nl' => 'Landbouwspuit'
        ]),
        'description' => json_encode([
            'fr' => 'Pulvérisateur professionnel pour traitements',
            'en' => 'Professional sprayer for treatments',
            'nl' => 'Professionele spuit voor behandelingen'
        ]),
        'price' => 1800.00,
        'unit_symbol' => 'pièce',
        'slug' => 'pulverisateur-agricole-new'
    ]
];

// OUTILS AGRICOLES - 5 produits
$outilsProducts = [
    [
        'name' => json_encode([
            'fr' => 'Bêche Professionnelle',
            'en' => 'Professional Spade',
            'nl' => 'Professionele Spade'
        ]),
        'description' => json_encode([
            'fr' => 'Bêche robuste en acier trempé',
            'en' => 'Sturdy tempered steel spade',
            'nl' => 'Stevige gehard stalen spade'
        ]),
        'price' => 45.00,
        'unit_symbol' => 'pièce',
        'slug' => 'beche-professionnelle-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Sécateur de Qualité',
            'en' => 'Quality Pruner',
            'nl' => 'Kwaliteitssnoeischaar'
        ]),
        'description' => json_encode([
            'fr' => 'Sécateur ergonomique à lames affûtées',
            'en' => 'Ergonomic pruner with sharp blades',
            'nl' => 'Ergonomische snoeischaar met scherpe messen'
        ]),
        'price' => 35.00,
        'unit_symbol' => 'pièce',
        'slug' => 'secateur-qualite-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Râteau Multi-Usage',
            'en' => 'Multi-Purpose Rake',
            'nl' => 'Multifunctionele Hark'
        ]),
        'description' => json_encode([
            'fr' => 'Râteau polyvalent pour tous travaux',
            'en' => 'Versatile rake for all tasks',
            'nl' => 'Veelzijdige hark voor alle taken'
        ]),
        'price' => 28.00,
        'unit_symbol' => 'pièce',
        'slug' => 'rateau-multi-usage-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Houe de Précision',
            'en' => 'Precision Hoe',
            'nl' => 'Precisiehouwtje'
        ]),
        'description' => json_encode([
            'fr' => 'Houe précise pour travail entre les rangs',
            'en' => 'Precise hoe for work between rows',
            'nl' => 'Precieze houw voor werk tussen rijen'
        ]),
        'price' => 32.00,
        'unit_symbol' => 'pièce',
        'slug' => 'houe-precision-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Fourche à Fumier',
            'en' => 'Manure Fork',
            'nl' => 'Mestvork'
        ]),
        'description' => json_encode([
            'fr' => 'Fourche spécialisée pour manipulation du fumier',
            'en' => 'Specialized fork for manure handling',
            'nl' => 'Gespecialiseerde vork voor mestbehandeling'
        ]),
        'price' => 55.00,
        'unit_symbol' => 'pièce',
        'slug' => 'fourche-fumier-new'
    ]
];

// PROTECTIONS - 5 produits
$protectionsProducts = [
    [
        'name' => json_encode([
            'fr' => 'Gants de Protection Agricole',
            'en' => 'Agricultural Protection Gloves',
            'nl' => 'Landbouwbeschermingshandschoenen'
        ]),
        'description' => json_encode([
            'fr' => 'Gants résistants aux produits chimiques',
            'en' => 'Chemical-resistant gloves',
            'nl' => 'Chemisch bestendige handschoenen'
        ]),
        'price' => 15.00,
        'unit_symbol' => 'pièce',
        'slug' => 'gants-protection-agricole-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Masque Respiratoire',
            'en' => 'Respiratory Mask',
            'nl' => 'Ademhalingsmasker'
        ]),
        'description' => json_encode([
            'fr' => 'Masque filtrant pour protection respiratoire',
            'en' => 'Filter mask for respiratory protection',
            'nl' => 'Filtermasker voor ademhalingsbescherming'
        ]),
        'price' => 25.00,
        'unit_symbol' => 'pièce',
        'slug' => 'masque-respiratoire-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Lunettes de Sécurité',
            'en' => 'Safety Glasses',
            'nl' => 'Veiligheidsbril'
        ]),
        'description' => json_encode([
            'fr' => 'Lunettes de protection anti-projection',
            'en' => 'Anti-splash protection glasses',
            'nl' => 'Anti-spat beschermingsbril'
        ]),
        'price' => 18.00,
        'unit_symbol' => 'pièce',
        'slug' => 'lunettes-securite-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Combinaison de Protection',
            'en' => 'Protective Suit',
            'nl' => 'Beschermingspak'
        ]),
        'description' => json_encode([
            'fr' => 'Combinaison étanche pour pulvérisations',
            'en' => 'Waterproof suit for spraying',
            'nl' => 'Waterdicht pak voor spuiten'
        ]),
        'price' => 65.00,
        'unit_symbol' => 'pièce',
        'slug' => 'combinaison-protection-new'
    ],
    [
        'name' => json_encode([
            'fr' => 'Bottes de Sécurité',
            'en' => 'Safety Boots',
            'nl' => 'Veiligheidslaarzen'
        ]),
        'description' => json_encode([
            'fr' => 'Bottes renforcées anti-perforation',
            'en' => 'Reinforced puncture-resistant boots',
            'nl' => 'Versterkte punctiebestendige laarzen'
        ]),
        'price' => 85.00,
        'unit_symbol' => 'pièce',
        'slug' => 'bottes-securite-new'
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
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
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
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
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
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
}

echo "\n✅ TERMINÉ: 15 produits supprimés et recréés avec traductions complètes FR/EN/NL\n";

?>
