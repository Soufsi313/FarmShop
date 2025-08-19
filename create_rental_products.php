<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\RentalCategory;

echo "=== CRÉATION DE 17 PRODUITS DE LOCATION ===\n";

// Vérifier les catégories de location disponibles
echo "--- CATÉGORIES DE LOCATION DISPONIBLES ---\n";
$rentalCategories = RentalCategory::all();
foreach($rentalCategories as $cat) {
    echo "ID: " . $cat->id . " | Slug: " . $cat->slug . " | Nom: " . $cat->name . "\n";
}

// Trouver les catégories de location
$outilsRentalCategory = RentalCategory::where('slug', 'outils-agricoles')->first();
$machinesRentalCategory = RentalCategory::where('slug', 'machines')->first();
$equipementRentalCategory = RentalCategory::where('slug', 'equipements')->first();

if (!$outilsRentalCategory || !$machinesRentalCategory || !$equipementRentalCategory) {
    echo "❌ Erreur: Catégories de location manquantes\n";
    echo "Outils agricoles: " . ($outilsRentalCategory ? "✅" : "❌") . "\n";
    echo "Machines: " . ($machinesRentalCategory ? "✅" : "❌") . "\n";
    echo "Équipement: " . ($equipementRentalCategory ? "✅" : "❌") . "\n";
    exit;
}

echo "\n=== CRÉATION DES PRODUITS DE LOCATION ===\n";

// OUTILS AGRICOLES - 6 produits de location
$outilsRentalProducts = [
    [
        'name' => json_encode([
            'fr' => 'Débroussailleuse Professionnelle',
            'en' => 'Professional Brush Cutter',
            'nl' => 'Professionele Bosmaaier'
        ]),
        'description' => json_encode([
            'fr' => 'Débroussailleuse puissante pour terrain difficile, idéale pour défrichage',
            'en' => 'Powerful brush cutter for difficult terrain, ideal for clearing',
            'nl' => 'Krachtige bosmaaier voor moeilijk terrein, ideaal voor opruimen'
        ]),
        'short_description' => json_encode([
            'fr' => 'Débroussailleuse puissante pour défrichage.',
            'en' => 'Powerful brush cutter for clearing.',
            'nl' => 'Krachtige bosmaaier voor opruimen.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'unit_symbol' => 'pièce',
        'slug' => 'debroussailleuse-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Tronçonneuse Électrique',
            'en' => 'Electric Chainsaw',
            'nl' => 'Elektrische Kettingzaag'
        ]),
        'description' => json_encode([
            'fr' => 'Tronçonneuse électrique silencieuse pour travaux de précision',
            'en' => 'Silent electric chainsaw for precision work',
            'nl' => 'Stille elektrische kettingzaag voor precisiewerk'
        ]),
        'short_description' => json_encode([
            'fr' => 'Tronçonneuse électrique silencieuse.',
            'en' => 'Silent electric chainsaw.',
            'nl' => 'Stille elektrische kettingzaag.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 150.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'unit_symbol' => 'pièce',
        'slug' => 'tronconneuse-electrique-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Broyeur de Végétaux',
            'en' => 'Garden Shredder',
            'nl' => 'Tuinversnipperaar'
        ]),
        'description' => json_encode([
            'fr' => 'Broyeur puissant pour réduire les déchets verts en compost',
            'en' => 'Powerful shredder to reduce green waste into compost',
            'nl' => 'Krachtige versnipperaar om groenafval tot compost te verwerken'
        ]),
        'short_description' => json_encode([
            'fr' => 'Broyeur puissant pour déchets verts.',
            'en' => 'Powerful shredder for green waste.',
            'nl' => 'Krachtige versnipperaar voor groenafval.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 55.00,
        'deposit_amount' => 300.00,
        'min_rental_days' => 1,
        'max_rental_days' => 10,
        'unit_symbol' => 'pièce',
        'slug' => 'broyeur-vegetaux-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Souffleur de Feuilles',
            'en' => 'Leaf Blower',
            'nl' => 'Bladblazer'
        ]),
        'description' => json_encode([
            'fr' => 'Souffleur thermique haute performance pour nettoyage rapide',
            'en' => 'High-performance thermal blower for quick cleaning',
            'nl' => 'Krachtige thermische blazer voor snel schoonmaken'
        ]),
        'short_description' => json_encode([
            'fr' => 'Souffleur thermique haute performance.',
            'en' => 'High-performance thermal blower.',
            'nl' => 'Krachtige thermische blazer.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 100.00,
        'min_rental_days' => 1,
        'max_rental_days' => 5,
        'unit_symbol' => 'pièce',
        'slug' => 'souffleur-feuilles-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Cultivateur Motorisé',
            'en' => 'Motorized Cultivator',
            'nl' => 'Gemotoriseerde Cultivator'
        ]),
        'description' => json_encode([
            'fr' => 'Cultivateur motorisé pour préparation efficace du sol',
            'en' => 'Motorized cultivator for efficient soil preparation',
            'nl' => 'Gemotoriseerde cultivator voor efficiënte grondbewerking'
        ]),
        'short_description' => json_encode([
            'fr' => 'Cultivateur motorisé pour le sol.',
            'en' => 'Motorized cultivator for soil.',
            'nl' => 'Gemotoriseerde cultivator voor grond.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 40.00,
        'deposit_amount' => 180.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'unit_symbol' => 'pièce',
        'slug' => 'cultivateur-motorise-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Tarière Manuelle',
            'en' => 'Manual Auger',
            'nl' => 'Handboormachine'
        ]),
        'description' => json_encode([
            'fr' => 'Tarière manuelle pour creuser des trous précis rapidement',
            'en' => 'Manual auger for digging precise holes quickly',
            'nl' => 'Handboormachine voor het snel graven van precieze gaten'
        ]),
        'short_description' => json_encode([
            'fr' => 'Tarière manuelle pour trous précis.',
            'en' => 'Manual auger for precise holes.',
            'nl' => 'Handboormachine voor precieze gaten.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 20.00,
        'deposit_amount' => 80.00,
        'min_rental_days' => 1,
        'max_rental_days' => 3,
        'unit_symbol' => 'pièce',
        'slug' => 'tariere-manuelle-location'
    ]
];

// MACHINES - 6 produits de location
$machinesRentalProducts = [
    [
        'name' => json_encode([
            'fr' => 'Mini-Tracteur Compact',
            'en' => 'Compact Mini Tractor',
            'nl' => 'Compacte Minitractor'
        ]),
        'description' => json_encode([
            'fr' => 'Mini-tracteur compact polyvalent pour tous travaux agricoles',
            'en' => 'Versatile compact mini tractor for all agricultural work',
            'nl' => 'Veelzijdige compacte minitractor voor alle landbouwwerkzaamheden'
        ]),
        'short_description' => json_encode([
            'fr' => 'Mini-tracteur compact polyvalent.',
            'en' => 'Versatile compact mini tractor.',
            'nl' => 'Veelzijdige compacte minitractor.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 120.00,
        'deposit_amount' => 800.00,
        'min_rental_days' => 1,
        'max_rental_days' => 30,
        'unit_symbol' => 'pièce',
        'slug' => 'mini-tracteur-compact-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Chargeuse Compacte',
            'en' => 'Compact Loader',
            'nl' => 'Compacte Lader'
        ]),
        'description' => json_encode([
            'fr' => 'Chargeuse compacte pour manutention et terrassement',
            'en' => 'Compact loader for handling and earthwork',
            'nl' => 'Compacte lader voor materiaalbehandeling en grondwerk'
        ]),
        'short_description' => json_encode([
            'fr' => 'Chargeuse compacte pour manutention.',
            'en' => 'Compact loader for handling.',
            'nl' => 'Compacte lader voor materiaalbehandeling.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 150.00,
        'deposit_amount' => 1000.00,
        'min_rental_days' => 1,
        'max_rental_days' => 21,
        'unit_symbol' => 'pièce',
        'slug' => 'chargeuse-compacte-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Remorque Agricole',
            'en' => 'Agricultural Trailer',
            'nl' => 'Landbouwaanhanger'
        ]),
        'description' => json_encode([
            'fr' => 'Remorque agricole robuste pour transport de matériaux',
            'en' => 'Robust agricultural trailer for material transport',
            'nl' => 'Robuuste landbouwaanhanger voor materiaaltransport'
        ]),
        'short_description' => json_encode([
            'fr' => 'Remorque agricole robuste.',
            'en' => 'Robust agricultural trailer.',
            'nl' => 'Robuuste landbouwaanhanger.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'unit_symbol' => 'pièce',
        'slug' => 'remorque-agricole-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Tracteur Tondeuse',
            'en' => 'Riding Mower Tractor',
            'nl' => 'Zitmaaier Tractor'
        ]),
        'description' => json_encode([
            'fr' => 'Tracteur tondeuse professionnel pour grandes surfaces',
            'en' => 'Professional riding mower tractor for large areas',
            'nl' => 'Professionele zitmaaier tractor voor grote oppervlakten'
        ]),
        'short_description' => json_encode([
            'fr' => 'Tracteur tondeuse professionnel.',
            'en' => 'Professional riding mower tractor.',
            'nl' => 'Professionele zitmaaier tractor.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 80.00,
        'deposit_amount' => 500.00,
        'min_rental_days' => 1,
        'max_rental_days' => 10,
        'unit_symbol' => 'pièce',
        'slug' => 'tracteur-tondeuse-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Épandeur à Fumier',
            'en' => 'Manure Spreader',
            'nl' => 'Meststrooier'
        ]),
        'description' => json_encode([
            'fr' => 'Épandeur à fumier pour fertilisation naturelle des sols',
            'en' => 'Manure spreader for natural soil fertilization',
            'nl' => 'Meststrooier voor natuurlijke grondbemesting'
        ]),
        'short_description' => json_encode([
            'fr' => 'Épandeur à fumier pour fertilisation.',
            'en' => 'Manure spreader for fertilization.',
            'nl' => 'Meststrooier voor bemesting.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 60.00,
        'deposit_amount' => 350.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'unit_symbol' => 'pièce',
        'slug' => 'epandeur-fumier-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Grue Mobile Agricole',
            'en' => 'Mobile Agricultural Crane',
            'nl' => 'Mobiele Landbouwkraan'
        ]),
        'description' => json_encode([
            'fr' => 'Grue mobile compacte pour levage et manutention agricole',
            'en' => 'Compact mobile crane for agricultural lifting and handling',
            'nl' => 'Compacte mobiele kraan voor landbouwheffen en behandeling'
        ]),
        'short_description' => json_encode([
            'fr' => 'Grue mobile pour levage agricole.',
            'en' => 'Mobile crane for agricultural lifting.',
            'nl' => 'Mobiele kraan voor landbouwheffen.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 90.00,
        'deposit_amount' => 600.00,
        'min_rental_days' => 1,
        'max_rental_days' => 5,
        'unit_symbol' => 'pièce',
        'slug' => 'grue-mobile-agricole-location'
    ]
];

// ÉQUIPEMENT - 5 produits de location
$equipementRentalProducts = [
    [
        'name' => json_encode([
            'fr' => 'Groupe Électrogène',
            'en' => 'Generator',
            'nl' => 'Generator'
        ]),
        'description' => json_encode([
            'fr' => 'Groupe électrogène silencieux pour alimentation électrique autonome',
            'en' => 'Silent generator for autonomous power supply',
            'nl' => 'Stille generator voor autonome stroomvoorziening'
        ]),
        'short_description' => json_encode([
            'fr' => 'Groupe électrogène silencieux.',
            'en' => 'Silent generator.',
            'nl' => 'Stille generator.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 65.00,
        'deposit_amount' => 400.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'unit_symbol' => 'pièce',
        'slug' => 'groupe-electrogene-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Pompe à Eau Agricole',
            'en' => 'Agricultural Water Pump',
            'nl' => 'Landbouwwaterpomp'
        ]),
        'description' => json_encode([
            'fr' => 'Pompe à eau haute capacité pour irrigation et drainage',
            'en' => 'High-capacity water pump for irrigation and drainage',
            'nl' => 'Hogekapaciteit waterpomp voor irrigatie en drainage'
        ]),
        'short_description' => json_encode([
            'fr' => 'Pompe à eau haute capacité.',
            'en' => 'High-capacity water pump.',
            'nl' => 'Hogekapaciteit waterpomp.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 250.00,
        'min_rental_days' => 1,
        'max_rental_days' => 10,
        'unit_symbol' => 'pièce',
        'slug' => 'pompe-eau-agricole-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Bâche de Protection',
            'en' => 'Protection Tarp',
            'nl' => 'Beschermingszeil'
        ]),
        'description' => json_encode([
            'fr' => 'Bâche de protection résistante pour couvrir récoltes et matériel',
            'en' => 'Resistant protection tarp to cover crops and equipment',
            'nl' => 'Bestand beschermingszeil om gewassen en uitrusting te bedekken'
        ]),
        'short_description' => json_encode([
            'fr' => 'Bâche de protection résistante.',
            'en' => 'Resistant protection tarp.',
            'nl' => 'Bestand beschermingszeil.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 50.00,
        'min_rental_days' => 3,
        'max_rental_days' => 30,
        'unit_symbol' => 'pièce',
        'slug' => 'bache-protection-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Système d\'Irrigation Mobile',
            'en' => 'Mobile Irrigation System',
            'nl' => 'Mobiel Irrigatiesysteem'
        ]),
        'description' => json_encode([
            'fr' => 'Système d\'irrigation mobile automatique pour arrosage précis',
            'en' => 'Automatic mobile irrigation system for precise watering',
            'nl' => 'Automatisch mobiel irrigatiesysteem voor precieze bewatering'
        ]),
        'short_description' => json_encode([
            'fr' => 'Système d\'irrigation mobile automatique.',
            'en' => 'Automatic mobile irrigation system.',
            'nl' => 'Automatisch mobiel irrigatiesysteem.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 75.00,
        'deposit_amount' => 450.00,
        'min_rental_days' => 3,
        'max_rental_days' => 21,
        'unit_symbol' => 'pièce',
        'slug' => 'irrigation-mobile-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Balance Agricole Électronique',
            'en' => 'Electronic Agricultural Scale',
            'nl' => 'Elektronische Landbouwweegschaal'
        ]),
        'description' => json_encode([
            'fr' => 'Balance électronique de précision pour pesage des récoltes',
            'en' => 'Precision electronic scale for crop weighing',
            'nl' => 'Precisie elektronische weegschaal voor gewas wegen'
        ]),
        'short_description' => json_encode([
            'fr' => 'Balance électronique de précision.',
            'en' => 'Precision electronic scale.',
            'nl' => 'Precisie elektronische weegschaal.'
        ]),
        'price' => 0,
        'rental_price_per_day' => 30.00,
        'deposit_amount' => 150.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'unit_symbol' => 'pièce',
        'slug' => 'balance-agricole-location'
    ]
];

// Créer les produits OUTILS AGRICOLES (location)
echo "\n--- OUTILS AGRICOLES (LOCATION) ---\n";
foreach ($outilsRentalProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'price' => $productData['price'],
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'min_rental_days' => $productData['min_rental_days'],
        'max_rental_days' => $productData['max_rental_days'],
        'unit_symbol' => $productData['unit_symbol'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(2, 8),
        'quantity' => 0, // Pas de stock d'achat pour les produits de location
        'category_id' => 1, // Catégorie par défaut
        'rental_category_id' => $outilsRentalCategory->id,
        'type' => 'rental',
        'is_rental_available' => true,
        'is_active' => true
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ") - " . $product->rental_price_per_day . "€/jour\n";
}

// Créer les produits MACHINES (location)
echo "\n--- MACHINES (LOCATION) ---\n";
foreach ($machinesRentalProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'price' => $productData['price'],
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'min_rental_days' => $productData['min_rental_days'],
        'max_rental_days' => $productData['max_rental_days'],
        'unit_symbol' => $productData['unit_symbol'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(1, 5),
        'quantity' => 0,
        'rental_category_id' => $machinesRentalCategory->id,
        'type' => 'rental',
        'is_rental_available' => true,
        'is_active' => true
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ") - " . $product->rental_price_per_day . "€/jour\n";
}

// Créer les produits ÉQUIPEMENT (location)
echo "\n--- ÉQUIPEMENT (LOCATION) ---\n";
foreach ($equipementRentalProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'price' => $productData['price'],
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'min_rental_days' => $productData['min_rental_days'],
        'max_rental_days' => $productData['max_rental_days'],
        'unit_symbol' => $productData['unit_symbol'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(3, 10),
        'quantity' => 0,
        'rental_category_id' => $equipementRentalCategory->id,
        'type' => 'rental',
        'is_rental_available' => true,
        'is_active' => true
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ") - " . $product->rental_price_per_day . "€/jour\n";
}

echo "\n🎯 SUCCÈS: 17 produits de location créés avec traductions FR/EN/NL!\n";
echo "📊 Répartition:\n";
echo "   - Outils agricoles: 6 produits\n";
echo "   - Machines: 6 produits\n";
echo "   - Équipement: 5 produits\n";
echo "\n✅ Tous les produits ont prix de location, caution, durées min/max et stock de location!\n";

?>
