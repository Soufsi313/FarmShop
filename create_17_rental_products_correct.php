<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\RentalCategory;

echo "=== VÉRIFICATION DES CATÉGORIES DE LOCATION ===\n";
$rentalCategories = RentalCategory::all();
foreach($rentalCategories as $cat) {
    echo "ID: " . $cat->id . " | Slug: " . $cat->slug . " | Nom: " . $cat->name . "\n";
}

echo "\n=== SUPPRESSION DES PRODUITS DE LOCATION MAL CRÉÉS ===\n";
$badRentals = Product::where('type', 'rental')->get();
foreach($badRentals as $product) {
    echo "Suppression: " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
    $product->delete();
}

echo "\n=== CRÉATION CORRECTE DES 17 PRODUITS DE LOCATION ===\n";

// Trouver les bonnes catégories de LOCATION
$outilsRental = RentalCategory::where('slug', 'outils-agricoles')->first();
$machinesRental = RentalCategory::where('slug', 'machines')->first();
$equipementRental = RentalCategory::where('slug', 'equipements')->first();

if (!$outilsRental || !$machinesRental || !$equipementRental) {
    echo "❌ ERREUR: Catégories de location manquantes\n";
    echo "Outils: " . ($outilsRental ? "✅" : "❌") . "\n";
    echo "Machines: " . ($machinesRental ? "✅" : "❌") . "\n";
    echo "Équipement: " . ($equipementRental ? "✅" : "❌") . "\n";
    exit;
}

// 6 OUTILS AGRICOLES DE LOCATION
$outilsRentals = [
    [
        'name' => json_encode(['fr' => 'Tronçonneuse Professionnelle', 'en' => 'Professional Chainsaw', 'nl' => 'Professionele Kettingzaag']),
        'description' => json_encode(['fr' => 'Tronçonneuse puissante pour élagage et abattage', 'en' => 'Powerful chainsaw for pruning and felling', 'nl' => 'Krachtige kettingzaag voor snoeien en vellen']),
        'price' => 450.00,
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 150.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'slug' => 'tronconneuse-professionnelle-location'
    ],
    [
        'name' => json_encode(['fr' => 'Débroussailleuse Thermique', 'en' => 'Thermal Brush Cutter', 'nl' => 'Thermische Bosmaaier']),
        'description' => json_encode(['fr' => 'Débroussailleuse thermique pour grands terrains', 'en' => 'Thermal brush cutter for large areas', 'nl' => 'Thermische bosmaaier voor grote terreinen']),
        'price' => 320.00,
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 100.00,
        'min_rental_days' => 1,
        'max_rental_days' => 5,
        'slug' => 'debroussailleuse-thermique-location'
    ],
    [
        'name' => json_encode(['fr' => 'Taille-haie Électrique', 'en' => 'Electric Hedge Trimmer', 'nl' => 'Elektrische Heggenschaar']),
        'description' => json_encode(['fr' => 'Taille-haie électrique pour entretien des haies', 'en' => 'Electric hedge trimmer for hedge maintenance', 'nl' => 'Elektrische heggenschaar voor haagonderhoud']),
        'price' => 180.00,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 60.00,
        'min_rental_days' => 1,
        'max_rental_days' => 3,
        'slug' => 'taille-haie-electrique-location'
    ],
    [
        'name' => json_encode(['fr' => 'Souffleur de Feuilles', 'en' => 'Leaf Blower', 'nl' => 'Bladblazer']),
        'description' => json_encode(['fr' => 'Souffleur puissant pour nettoyage des feuilles', 'en' => 'Powerful blower for leaf cleaning', 'nl' => 'Krachtige blazer voor bladreiniging']),
        'price' => 220.00,
        'rental_price_per_day' => 18.00,
        'deposit_amount' => 75.00,
        'min_rental_days' => 1,
        'max_rental_days' => 3,
        'slug' => 'souffleur-feuilles-location'
    ],
    [
        'name' => json_encode(['fr' => 'Bineuse Mécanique', 'en' => 'Mechanical Hoe', 'nl' => 'Mechanische Schoffel']),
        'description' => json_encode(['fr' => 'Bineuse mécanique pour travail du sol', 'en' => 'Mechanical hoe for soil work', 'nl' => 'Mechanische schoffel voor grondbewerking']),
        'price' => 850.00,
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 2,
        'max_rental_days' => 7,
        'slug' => 'bineuse-mecanique-location'
    ],
    [
        'name' => json_encode(['fr' => 'Scarificateur Électrique', 'en' => 'Electric Scarifier', 'nl' => 'Elektrische Verticuteermachine']),
        'description' => json_encode(['fr' => 'Scarificateur pour aération et entretien des pelouses', 'en' => 'Scarifier for lawn aeration and maintenance', 'nl' => 'Verticuteermachine voor gazonbeluchting en onderhoud']),
        'price' => 280.00,
        'rental_price_per_day' => 22.00,
        'deposit_amount' => 90.00,
        'min_rental_days' => 1,
        'max_rental_days' => 2,
        'slug' => 'scarificateur-electrique-location'
    ]
];

// 6 MACHINES DE LOCATION
$machinesRentals = [
    [
        'name' => json_encode(['fr' => 'Mini-pelle 1.5T', 'en' => '1.5T Mini Excavator', 'nl' => '1.5T Mini-graafmachine']),
        'description' => json_encode(['fr' => 'Mini-pelle compacte pour travaux de terrassement', 'en' => 'Compact mini excavator for earthworks', 'nl' => 'Compacte mini-graafmachine voor grondwerken']),
        'price' => 15000.00,
        'rental_price_per_day' => 180.00,
        'deposit_amount' => 800.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'slug' => 'mini-pelle-15t-location'
    ],
    [
        'name' => json_encode(['fr' => 'Tracteur Compact 25CV', 'en' => '25HP Compact Tractor', 'nl' => '25PK Compacte Tractor']),
        'description' => json_encode(['fr' => 'Tracteur compact polyvalent pour petites exploitations', 'en' => 'Versatile compact tractor for small farms', 'nl' => 'Veelzijdige compacte tractor voor kleine boerderijen']),
        'price' => 28000.00,
        'rental_price_per_day' => 120.00,
        'deposit_amount' => 1000.00,
        'min_rental_days' => 2,
        'max_rental_days' => 30,
        'slug' => 'tracteur-compact-25cv-location'
    ],
    [
        'name' => json_encode(['fr' => 'Remorque Basculante 2T', 'en' => '2T Tipping Trailer', 'nl' => '2T Kiepwagen']),
        'description' => json_encode(['fr' => 'Remorque basculante pour transport de matériaux', 'en' => 'Tipping trailer for material transport', 'nl' => 'Kiepwagen voor materiaaltransport']),
        'price' => 3500.00,
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 300.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'slug' => 'remorque-basculante-2t-location'
    ],
    [
        'name' => json_encode(['fr' => 'Chargeuse Compacte', 'en' => 'Compact Loader', 'nl' => 'Compacte Lader']),
        'description' => json_encode(['fr' => 'Chargeuse compacte pour manutention et chargement', 'en' => 'Compact loader for handling and loading', 'nl' => 'Compacte lader voor hantering en laden']),
        'price' => 22000.00,
        'rental_price_per_day' => 140.00,
        'deposit_amount' => 900.00,
        'min_rental_days' => 1,
        'max_rental_days' => 21,
        'slug' => 'chargeuse-compacte-location'
    ],
    [
        'name' => json_encode(['fr' => 'Gyrobroyeur 1.8m', 'en' => '1.8m Brush Mower', 'nl' => '1.8m Klepelmaaier']),
        'description' => json_encode(['fr' => 'Gyrobroyeur pour entretien des espaces verts', 'en' => 'Brush mower for green space maintenance', 'nl' => 'Klepelmaaier voor onderhoud groene ruimtes']),
        'price' => 4500.00,
        'rental_price_per_day' => 65.00,
        'deposit_amount' => 400.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'slug' => 'gyrobroyeur-18m-location'
    ],
    [
        'name' => json_encode(['fr' => 'Fendeuse à Bois 12T', 'en' => '12T Log Splitter', 'nl' => '12T Houtkliever']),
        'description' => json_encode(['fr' => 'Fendeuse hydraulique pour bois de chauffage', 'en' => 'Hydraulic splitter for firewood', 'nl' => 'Hydraulische kliever voor brandhout']),
        'price' => 2800.00,
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 250.00,
        'min_rental_days' => 1,
        'max_rental_days' => 5,
        'slug' => 'fendeuse-bois-12t-location'
    ]
];

// 5 ÉQUIPEMENTS DE LOCATION
$equipementRentals = [
    [
        'name' => json_encode(['fr' => 'Système d\'Irrigation Goutte-à-Goutte', 'en' => 'Drip Irrigation System', 'nl' => 'Druppelirrigatiesysteem']),
        'description' => json_encode(['fr' => 'Système d\'irrigation automatique pour cultures', 'en' => 'Automatic irrigation system for crops', 'nl' => 'Automatisch irrigatiesysteem voor gewassen']),
        'price' => 1200.00,
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 7,
        'max_rental_days' => 60,
        'slug' => 'irrigation-goutte-goutte-location'
    ],
    [
        'name' => json_encode(['fr' => 'Tente de Stockage 6x3m', 'en' => '6x3m Storage Tent', 'nl' => '6x3m Opslagtent']),
        'description' => json_encode(['fr' => 'Tente de stockage temporaire pour matériel agricole', 'en' => 'Temporary storage tent for agricultural equipment', 'nl' => 'Tijdelijke opslagtent voor landbouwmaterieel']),
        'price' => 800.00,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 150.00,
        'min_rental_days' => 3,
        'max_rental_days' => 30,
        'slug' => 'tente-stockage-6x3m-location'
    ],
    [
        'name' => json_encode(['fr' => 'Bâche de Protection 100m²', 'en' => '100m² Protection Tarpaulin', 'nl' => '100m² Beschermingszeil']),
        'description' => json_encode(['fr' => 'Bâche de protection étanche pour cultures', 'en' => 'Waterproof protection tarpaulin for crops', 'nl' => 'Waterdicht beschermingszeil voor gewassen']),
        'price' => 300.00,
        'rental_price_per_day' => 8.00,
        'deposit_amount' => 80.00,
        'min_rental_days' => 2,
        'max_rental_days' => 21,
        'slug' => 'bache-protection-100m2-location'
    ],
    [
        'name' => json_encode(['fr' => 'Groupe Électrogène 5kW', 'en' => '5kW Generator', 'nl' => '5kW Generator']),
        'description' => json_encode(['fr' => 'Groupe électrogène portable pour alimentation temporaire', 'en' => 'Portable generator for temporary power supply', 'nl' => 'Draagbare generator voor tijdelijke stroomvoorziening']),
        'price' => 1800.00,
        'rental_price_per_day' => 30.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'slug' => 'groupe-electrogene-5kw-location'
    ],
    [
        'name' => json_encode(['fr' => 'Pompe à Eau Thermique', 'en' => 'Thermal Water Pump', 'nl' => 'Thermische Waterpomp']),
        'description' => json_encode(['fr' => 'Pompe thermique haute performance pour irrigation', 'en' => 'High-performance thermal pump for irrigation', 'nl' => 'High-performance thermische pomp voor irrigatie']),
        'price' => 950.00,
        'rental_price_per_day' => 20.00,
        'deposit_amount' => 120.00,
        'min_rental_days' => 2,
        'max_rental_days' => 10,
        'slug' => 'pompe-eau-thermique-location'
    ]
];

// Créer les produits OUTILS AGRICOLES DE LOCATION
echo "\n--- OUTILS AGRICOLES DE LOCATION ---\n";
foreach ($outilsRentals as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => json_encode([
            'fr' => 'Outil agricole professionnel en location.',
            'en' => 'Professional agricultural tool for rent.',
            'nl' => 'Professioneel landbouwgereedschap te huur.'
        ]),
        'price' => $productData['price'],
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'min_rental_days' => $productData['min_rental_days'],
        'max_rental_days' => $productData['max_rental_days'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(2, 8),
        'category_id' => 7, // Catégorie Machines d'achat par défaut
        'rental_category_id' => $outilsRental->id,
        'type' => 'rental',
        'is_active' => true,
        'is_rental_available' => true,
        'unit_symbol' => 'pièce'
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
}

// Créer les produits MACHINES DE LOCATION
echo "\n--- MACHINES DE LOCATION ---\n";
foreach ($machinesRentals as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => json_encode([
            'fr' => 'Machine agricole professionnelle en location.',
            'en' => 'Professional agricultural machine for rent.',
            'nl' => 'Professionele landbouwmachine te huur.'
        ]),
        'price' => $productData['price'],
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'min_rental_days' => $productData['min_rental_days'],
        'max_rental_days' => $productData['max_rental_days'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(1, 3),
        'category_id' => 7, // Catégorie Machines d'achat par défaut
        'rental_category_id' => $machinesRental->id,
        'type' => 'rental',
        'is_active' => true,
        'is_rental_available' => true,
        'unit_symbol' => 'pièce'
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
}

// Créer les produits ÉQUIPEMENTS DE LOCATION
echo "\n--- ÉQUIPEMENTS DE LOCATION ---\n";
foreach ($equipementRentals as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => json_encode([
            'fr' => 'Équipement agricole spécialisé en location.',
            'en' => 'Specialized agricultural equipment for rent.',
            'nl' => 'Gespecialiseerde landbouwapparatuur te huur.'
        ]),
        'price' => $productData['price'],
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'min_rental_days' => $productData['min_rental_days'],
        'max_rental_days' => $productData['max_rental_days'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(1, 5),
        'category_id' => 8, // Catégorie Équipement d'achat par défaut
        'rental_category_id' => $equipementRental->id,
        'type' => 'rental',
        'is_active' => true,
        'is_rental_available' => true,
        'unit_symbol' => 'pièce'
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ")\n";
}

echo "\n🎯 TERMINÉ: 17 produits de location créés avec succès!\n";
echo "✅ 6 Outils agricoles de location\n";
echo "✅ 6 Machines de location\n";
echo "✅ 5 Équipements de location\n";
echo "✅ Tous avec traductions FR/EN/NL complètes\n";
echo "✅ Prix de location, cautions et durées configurés\n";

?>
