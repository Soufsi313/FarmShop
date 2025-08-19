<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=== RECRÉATION CORRECTE DES 15 PRODUITS ===\n";

// Récupérer les catégories
$machinesCategory = Category::where('slug', 'machines')->first();
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$protectionsCategory = Category::where('slug', 'protections')->first();

// MACHINES (5 produits)
$machinesProducts = [
    [
        'name' => [
            'fr' => 'Tondeuse Professionnelle',
            'en' => 'Professional Mower',
            'nl' => 'Professionele Maaier'
        ],
        'description' => [
            'fr' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts',
            'en' => 'High-performance professional mower for green space maintenance',
            'nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes'
        ],
        'short_description' => [
            'fr' => 'Tondeuse haute performance pour professionnels',
            'en' => 'High-performance mower for professionals',
            'nl' => 'High-performance maaier voor professionals'
        ],
        'meta_title' => [
            'fr' => 'Achat Tondeuse Professionnelle - FarmShop',
            'en' => 'Buy Professional Mower - FarmShop',
            'nl' => 'Koop Professionele Maaier - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez une tondeuse professionnelle haute performance sur FarmShop. Livraison rapide.',
            'en' => 'Buy high-performance professional mower on FarmShop. Fast delivery.',
            'nl' => 'Koop high-performance professionele maaier op FarmShop. Snelle levering.'
        ],
        'price' => 2500.00,
        'unit_symbol' => 'pièce'
    ],
    [
        'name' => [
            'fr' => 'Motoculteur',
            'en' => 'Rototiller',
            'nl' => 'Motoreg'
        ],
        'description' => [
            'fr' => 'Motoculteur puissant pour travail du sol',
            'en' => 'Powerful rototiller for soil work',
            'nl' => 'Krachtige motoreg voor grondbewerking'
        ],
        'short_description' => [
            'fr' => 'Motoculteur puissant pour travaux agricoles',
            'en' => 'Powerful rototiller for agricultural work',
            'nl' => 'Krachtige motoreg voor landbouwwerkzaamheden'
        ],
        'meta_title' => [
            'fr' => 'Achat Motoculteur - FarmShop',
            'en' => 'Buy Rototiller - FarmShop',
            'nl' => 'Koop Motoreg - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez un motoculteur puissant sur FarmShop. Idéal pour travaux du sol.',
            'en' => 'Buy powerful rototiller on FarmShop. Ideal for soil work.',
            'nl' => 'Koop krachtige motoreg op FarmShop. Ideaal voor grondwerk.'
        ],
        'price' => 1200.00,
        'unit_symbol' => 'pièce'
    ],
    [
        'name' => [
            'fr' => 'Tondeuse Autoportée',
            'en' => 'Riding Mower',
            'nl' => 'Zitmaaier'
        ],
        'description' => [
            'fr' => 'Tondeuse autoportée pour grandes surfaces',
            'en' => 'Riding mower for large areas',
            'nl' => 'Zitmaaier voor grote oppervlakten'
        ],
        'short_description' => [
            'fr' => 'Tondeuse autoportée confortable',
            'en' => 'Comfortable riding mower',
            'nl' => 'Comfortabele zitmaaier'
        ],
        'meta_title' => [
            'fr' => 'Achat Tondeuse Autoportée - FarmShop',
            'en' => 'Buy Riding Mower - FarmShop',
            'nl' => 'Koop Zitmaaier - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez une tondeuse autoportée sur FarmShop. Parfaite pour grandes surfaces.',
            'en' => 'Buy riding mower on FarmShop. Perfect for large areas.',
            'nl' => 'Koop zitmaaier op FarmShop. Perfect voor grote oppervlakten.'
        ],
        'price' => 3500.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Épandeur d\'Engrais',
            'en' => 'Fertilizer Spreader',
            'nl' => 'Meststrooier'
        ],
        'description' => [
            'fr' => 'Épandeur pour distribution uniforme d\'engrais',
            'en' => 'Spreader for uniform fertilizer distribution',
            'nl' => 'Strooier voor gelijkmatige mestverspreiding'
        ],
        'short_description' => [
            'fr' => 'Épandeur d\'engrais professionnel',
            'en' => 'Professional fertilizer spreader',
            'nl' => 'Professionele meststrooier'
        ],
        'meta_title' => [
            'fr' => 'Achat Épandeur d\'Engrais - FarmShop',
            'en' => 'Buy Fertilizer Spreader - FarmShop',
            'nl' => 'Koop Meststrooier - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez un épandeur d\'engrais professionnel sur FarmShop. Distribution uniforme.',
            'en' => 'Buy professional fertilizer spreader on FarmShop. Uniform distribution.',
            'nl' => 'Koop professionele meststrooier op FarmShop. Gelijkmatige verspreiding.'
        ],
        'price' => 850.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Pulvérisateur Agricole',
            'en' => 'Agricultural Sprayer',
            'nl' => 'Landbouwspuit'
        ],
        'description' => [
            'fr' => 'Pulvérisateur professionnel pour traitements',
            'en' => 'Professional sprayer for treatments',
            'nl' => 'Professionele spuit voor behandelingen'
        ],
        'short_description' => [
            'fr' => 'Pulvérisateur professionnel',
            'en' => 'Professional sprayer',
            'nl' => 'Professionele spuit'
        ],
        'meta_title' => [
            'fr' => 'Achat Pulvérisateur Agricole - FarmShop',
            'en' => 'Buy Agricultural Sprayer - FarmShop',
            'nl' => 'Koop Landbouwspuit - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez un pulvérisateur agricole professionnel sur FarmShop. Traitements efficaces.',
            'en' => 'Buy professional agricultural sprayer on FarmShop. Effective treatments.',
            'nl' => 'Koop professionele landbouwspuit op FarmShop. Effectieve behandelingen.'
        ],
        'price' => 1800.00,
        'unit_symbol' => 'pièces'
    ]
];

// OUTILS AGRICOLES (5 produits)
$outilsProducts = [
    [
        'name' => [
            'fr' => 'Bêche Professionnelle',
            'en' => 'Professional Spade',
            'nl' => 'Professionele Spade'
        ],
        'description' => [
            'fr' => 'Bêche robuste en acier trempé',
            'en' => 'Sturdy tempered steel spade',
            'nl' => 'Stevige gehard stalen spade'
        ],
        'short_description' => [
            'fr' => 'Bêche robuste en acier',
            'en' => 'Sturdy steel spade',
            'nl' => 'Stevige stalen spade'
        ],
        'meta_title' => [
            'fr' => 'Achat Bêche Professionnelle - FarmShop',
            'en' => 'Buy Professional Spade - FarmShop',
            'nl' => 'Koop Professionele Spade - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez une bêche professionnelle robuste sur FarmShop. Acier trempé.',
            'en' => 'Buy sturdy professional spade on FarmShop. Tempered steel.',
            'nl' => 'Koop stevige professionele spade op FarmShop. Gehard staal.'
        ],
        'price' => 45.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Sécateur de Qualité',
            'en' => 'Quality Pruner',
            'nl' => 'Kwaliteitssnoeischaar'
        ],
        'description' => [
            'fr' => 'Sécateur ergonomique à lames affûtées',
            'en' => 'Ergonomic pruner with sharp blades',
            'nl' => 'Ergonomische snoeischaar met scherpe messen'
        ],
        'short_description' => [
            'fr' => 'Sécateur ergonomique',
            'en' => 'Ergonomic pruner',
            'nl' => 'Ergonomische snoeischaar'
        ],
        'meta_title' => [
            'fr' => 'Achat Sécateur de Qualité - FarmShop',
            'en' => 'Buy Quality Pruner - FarmShop',
            'nl' => 'Koop Kwaliteitssnoeischaar - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez un sécateur de qualité ergonomique sur FarmShop. Lames affûtées.',
            'en' => 'Buy quality ergonomic pruner on FarmShop. Sharp blades.',
            'nl' => 'Koop kwaliteit ergonomische snoeischaar op FarmShop. Scherpe messen.'
        ],
        'price' => 35.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Râteau Multi-Usage',
            'en' => 'Multi-Purpose Rake',
            'nl' => 'Multifunctionele Hark'
        ],
        'description' => [
            'fr' => 'Râteau polyvalent pour tous travaux',
            'en' => 'Versatile rake for all tasks',
            'nl' => 'Veelzijdige hark voor alle taken'
        ],
        'short_description' => [
            'fr' => 'Râteau polyvalent',
            'en' => 'Versatile rake',
            'nl' => 'Veelzijdige hark'
        ],
        'meta_title' => [
            'fr' => 'Achat Râteau Multi-Usage - FarmShop',
            'en' => 'Buy Multi-Purpose Rake - FarmShop',
            'nl' => 'Koop Multifunctionele Hark - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez un râteau multi-usage polyvalent sur FarmShop. Tous travaux.',
            'en' => 'Buy versatile multi-purpose rake on FarmShop. All tasks.',
            'nl' => 'Koop veelzijdige multifunctionele hark op FarmShop. Alle taken.'
        ],
        'price' => 28.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Houe de Précision',
            'en' => 'Precision Hoe',
            'nl' => 'Precisiehouwtje'
        ],
        'description' => [
            'fr' => 'Houe précise pour travail entre les rangs',
            'en' => 'Precise hoe for work between rows',
            'nl' => 'Precieze houw voor werk tussen rijen'
        ],
        'short_description' => [
            'fr' => 'Houe de précision',
            'en' => 'Precision hoe',
            'nl' => 'Precisiehouwtje'
        ],
        'meta_title' => [
            'fr' => 'Achat Houe de Précision - FarmShop',
            'en' => 'Buy Precision Hoe - FarmShop',
            'nl' => 'Koop Precisiehouwtje - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez une houe de précision sur FarmShop. Travail entre rangs.',
            'en' => 'Buy precision hoe on FarmShop. Work between rows.',
            'nl' => 'Koop precisiehouwtje op FarmShop. Werk tussen rijen.'
        ],
        'price' => 32.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Fourche à Fumier',
            'en' => 'Manure Fork',
            'nl' => 'Mestvork'
        ],
        'description' => [
            'fr' => 'Fourche spécialisée pour manipulation du fumier',
            'en' => 'Specialized fork for manure handling',
            'nl' => 'Gespecialiseerde vork voor mestbehandeling'
        ],
        'short_description' => [
            'fr' => 'Fourche spécialisée',
            'en' => 'Specialized fork',
            'nl' => 'Gespecialiseerde vork'
        ],
        'meta_title' => [
            'fr' => 'Achat Fourche à Fumier - FarmShop',
            'en' => 'Buy Manure Fork - FarmShop',
            'nl' => 'Koop Mestvork - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez une fourche à fumier spécialisée sur FarmShop. Manipulation optimale.',
            'en' => 'Buy specialized manure fork on FarmShop. Optimal handling.',
            'nl' => 'Koop gespecialiseerde mestvork op FarmShop. Optimale behandeling.'
        ],
        'price' => 55.00,
        'unit_symbol' => 'pièces'
    ]
];

// PROTECTIONS (5 produits)
$protectionsProducts = [
    [
        'name' => [
            'fr' => 'Gants de Protection Agricole',
            'en' => 'Agricultural Protection Gloves',
            'nl' => 'Landbouwbeschermingshandschoenen'
        ],
        'description' => [
            'fr' => 'Gants résistants aux produits chimiques',
            'en' => 'Chemical-resistant gloves',
            'nl' => 'Chemisch bestendige handschoenen'
        ],
        'short_description' => [
            'fr' => 'Gants de protection résistants',
            'en' => 'Resistant protection gloves',
            'nl' => 'Bestendige beschermingshandschoenen'
        ],
        'meta_title' => [
            'fr' => 'Achat Gants Protection Agricole - FarmShop',
            'en' => 'Buy Agricultural Protection Gloves - FarmShop',
            'nl' => 'Koop Landbouwbeschermingshandschoenen - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez des gants de protection agricole sur FarmShop. Résistants chimiques.',
            'en' => 'Buy agricultural protection gloves on FarmShop. Chemical resistant.',
            'nl' => 'Koop landbouwbeschermingshandschoenen op FarmShop. Chemisch bestendig.'
        ],
        'price' => 15.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Masque Respiratoire',
            'en' => 'Respiratory Mask',
            'nl' => 'Ademhalingsmasker'
        ],
        'description' => [
            'fr' => 'Masque filtrant pour protection respiratoire',
            'en' => 'Filter mask for respiratory protection',
            'nl' => 'Filtermasker voor ademhalingsbescherming'
        ],
        'short_description' => [
            'fr' => 'Masque respiratoire filtrant',
            'en' => 'Filter respiratory mask',
            'nl' => 'Filter ademhalingsmasker'
        ],
        'meta_title' => [
            'fr' => 'Achat Masque Respiratoire - FarmShop',
            'en' => 'Buy Respiratory Mask - FarmShop',
            'nl' => 'Koop Ademhalingsmasker - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez un masque respiratoire filtrant sur FarmShop. Protection optimale.',
            'en' => 'Buy filter respiratory mask on FarmShop. Optimal protection.',
            'nl' => 'Koop filter ademhalingsmasker op FarmShop. Optimale bescherming.'
        ],
        'price' => 25.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Lunettes de Sécurité',
            'en' => 'Safety Glasses',
            'nl' => 'Veiligheidsbril'
        ],
        'description' => [
            'fr' => 'Lunettes de protection anti-projection',
            'en' => 'Anti-splash protection glasses',
            'nl' => 'Anti-spat beschermingsbril'
        ],
        'short_description' => [
            'fr' => 'Lunettes protection anti-projection',
            'en' => 'Anti-splash protection glasses',
            'nl' => 'Anti-spat beschermingsbril'
        ],
        'meta_title' => [
            'fr' => 'Achat Lunettes de Sécurité - FarmShop',
            'en' => 'Buy Safety Glasses - FarmShop',
            'nl' => 'Koop Veiligheidsbril - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez des lunettes de sécurité sur FarmShop. Protection anti-projection.',
            'en' => 'Buy safety glasses on FarmShop. Anti-splash protection.',
            'nl' => 'Koop veiligheidsbril op FarmShop. Anti-spat bescherming.'
        ],
        'price' => 18.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Combinaison de Protection',
            'en' => 'Protective Suit',
            'nl' => 'Beschermingspak'
        ],
        'description' => [
            'fr' => 'Combinaison étanche pour pulvérisations',
            'en' => 'Waterproof suit for spraying',
            'nl' => 'Waterdicht pak voor spuiten'
        ],
        'short_description' => [
            'fr' => 'Combinaison étanche',
            'en' => 'Waterproof suit',
            'nl' => 'Waterdicht pak'
        ],
        'meta_title' => [
            'fr' => 'Achat Combinaison Protection - FarmShop',
            'en' => 'Buy Protective Suit - FarmShop',
            'nl' => 'Koop Beschermingspak - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez une combinaison de protection sur FarmShop. Étanche pulvérisations.',
            'en' => 'Buy protective suit on FarmShop. Waterproof for spraying.',
            'nl' => 'Koop beschermingspak op FarmShop. Waterdicht voor spuiten.'
        ],
        'price' => 65.00,
        'unit_symbol' => 'pièces'
    ],
    [
        'name' => [
            'fr' => 'Bottes de Sécurité',
            'en' => 'Safety Boots',
            'nl' => 'Veiligheidslaarzen'
        ],
        'description' => [
            'fr' => 'Bottes renforcées anti-perforation',
            'en' => 'Reinforced puncture-resistant boots',
            'nl' => 'Versterkte punctiebestendige laarzen'
        ],
        'short_description' => [
            'fr' => 'Bottes renforcées sécurité',
            'en' => 'Reinforced safety boots',
            'nl' => 'Versterkte veiligheidslaarzen'
        ],
        'meta_title' => [
            'fr' => 'Achat Bottes de Sécurité - FarmShop',
            'en' => 'Buy Safety Boots - FarmShop',
            'nl' => 'Koop Veiligheidslaarzen - FarmShop'
        ],
        'meta_description' => [
            'fr' => 'Achetez des bottes de sécurité sur FarmShop. Renforcées anti-perforation.',
            'en' => 'Buy safety boots on FarmShop. Reinforced puncture-resistant.',
            'nl' => 'Koop veiligheidslaarzen op FarmShop. Versterkte punctiebestendig.'
        ],
        'price' => 85.00,
        'unit_symbol' => 'pièces'
    ]
];

function createProduct($productData, $categoryId) {
    return Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'meta_title' => $productData['meta_title'],
        'meta_description' => $productData['meta_description'],
        'price' => $productData['price'],
        'unit_symbol' => $productData['unit_symbol'],
        'quantity' => rand(10, 100),
        'category_id' => $categoryId,
        'type' => 'sale',
        'is_active' => true,
        'critical_threshold' => 5,
        'low_stock_threshold' => 15,
        'min_rental_days' => 1,
        'max_rental_days' => 7
    ]);
}

// Créer MACHINES
echo "\n--- CRÉATION MACHINES ---\n";
foreach ($machinesProducts as $productData) {
    $product = createProduct($productData, $machinesCategory->id);
    echo "✅ Créé: " . $product->name['fr'] . " (ID: " . $product->id . ")\n";
}

// Créer OUTILS AGRICOLES
echo "\n--- CRÉATION OUTILS AGRICOLES ---\n";
foreach ($outilsProducts as $productData) {
    $product = createProduct($productData, $outilsCategory->id);
    echo "✅ Créé: " . $product->name['fr'] . " (ID: " . $product->id . ")\n";
}

// Créer PROTECTIONS
echo "\n--- CRÉATION PROTECTIONS ---\n";
foreach ($protectionsProducts as $productData) {
    $product = createProduct($productData, $protectionsCategory->id);
    echo "✅ Créé: " . $product->name['fr'] . " (ID: " . $product->id . ")\n";
}

echo "\n=== RÉSUMÉ FINAL ===\n";
$totalProducts = Product::where('type', 'sale')->count();
echo "📊 Total des produits d'achat: " . $totalProducts . "\n";

echo "\n🎯 RECRÉATION TERMINÉE AVEC SUCCÈS!\n";
echo "✅ Les 15 produits ont été recréés avec traductions complètes FR/EN/NL\n";

?>
