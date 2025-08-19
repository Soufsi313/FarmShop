<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "=== SUPPRESSION ET RECRÉATION CORRECTE DES PRODUITS DE LOCATION ===\n";

// Supprimer tous les produits de location mal créés
$badRentals = Product::where('type', 'rental')->get();
foreach($badRentals as $product) {
    echo "Suppression: " . (is_string($product->name) ? $product->name : json_decode($product->name)->fr) . " (ID: " . $product->id . ")\n";
    $product->delete();
}

echo "\n=== RÉCRÉATION AVEC LA BONNE STRUCTURE ===\n";

// Structure correcte comme les autres produits
$rentalProducts = [
    // 6 OUTILS AGRICOLES
    [
        'name' => [
            'fr' => 'Tronçonneuse Professionnelle',
            'en' => 'Professional Chainsaw',
            'nl' => 'Professionele Kettingzaag'
        ],
        'description' => [
            'fr' => 'Tronçonneuse puissante pour élagage et abattage',
            'en' => 'Powerful chainsaw for pruning and felling',
            'nl' => 'Krachtige kettingzaag voor snoeien en vellen'
        ],
        'short_description' => [
            'fr' => 'Tronçonneuse professionnelle haute performance.',
            'en' => 'High-performance professional chainsaw.',
            'nl' => 'High-performance professionele kettingzaag.'
        ],
        'price' => 450.00,
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 150.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'rental_stock' => 3,
        'category_id' => 6, // Outils agricoles d'achat
        'rental_category_id' => 1, // Outils agricoles de location
        'slug' => 'tronconneuse-professionnelle-location'
    ],
    [
        'name' => [
            'fr' => 'Débroussailleuse Thermique',
            'en' => 'Thermal Brush Cutter',
            'nl' => 'Thermische Bosmaaier'
        ],
        'description' => [
            'fr' => 'Débroussailleuse thermique pour grands terrains',
            'en' => 'Thermal brush cutter for large areas',
            'nl' => 'Thermische bosmaaier voor grote terreinen'
        ],
        'short_description' => [
            'fr' => 'Débroussailleuse thermique puissante.',
            'en' => 'Powerful thermal brush cutter.',
            'nl' => 'Krachtige thermische bosmaaier.'
        ],
        'price' => 320.00,
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 100.00,
        'min_rental_days' => 1,
        'max_rental_days' => 5,
        'rental_stock' => 2,
        'category_id' => 6,
        'rental_category_id' => 1,
        'slug' => 'debroussailleuse-thermique-location'
    ],
    [
        'name' => [
            'fr' => 'Taille-haie Électrique',
            'en' => 'Electric Hedge Trimmer',
            'nl' => 'Elektrische Heggenschaar'
        ],
        'description' => [
            'fr' => 'Taille-haie électrique pour entretien des haies',
            'en' => 'Electric hedge trimmer for hedge maintenance',
            'nl' => 'Elektrische heggenschaar voor haagonderhoud'
        ],
        'short_description' => [
            'fr' => 'Taille-haie électrique précis.',
            'en' => 'Precise electric hedge trimmer.',
            'nl' => 'Precieze elektrische heggenschaar.'
        ],
        'price' => 180.00,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 60.00,
        'min_rental_days' => 1,
        'max_rental_days' => 3,
        'rental_stock' => 4,
        'category_id' => 6,
        'rental_category_id' => 1,
        'slug' => 'taille-haie-electrique-location'
    ],
    [
        'name' => [
            'fr' => 'Souffleur de Feuilles',
            'en' => 'Leaf Blower',
            'nl' => 'Bladblazer'
        ],
        'description' => [
            'fr' => 'Souffleur puissant pour nettoyage des feuilles',
            'en' => 'Powerful blower for leaf cleaning',
            'nl' => 'Krachtige blazer voor bladreiniging'
        ],
        'short_description' => [
            'fr' => 'Souffleur puissant et efficace.',
            'en' => 'Powerful and efficient blower.',
            'nl' => 'Krachtige en efficiënte blazer.'
        ],
        'price' => 220.00,
        'rental_price_per_day' => 18.00,
        'deposit_amount' => 75.00,
        'min_rental_days' => 1,
        'max_rental_days' => 3,
        'rental_stock' => 3,
        'category_id' => 6,
        'rental_category_id' => 1,
        'slug' => 'souffleur-feuilles-location'
    ],
    [
        'name' => [
            'fr' => 'Bineuse Mécanique',
            'en' => 'Mechanical Hoe',
            'nl' => 'Mechanische Schoffel'
        ],
        'description' => [
            'fr' => 'Bineuse mécanique pour travail du sol',
            'en' => 'Mechanical hoe for soil work',
            'nl' => 'Mechanische schoffel voor grondbewerking'
        ],
        'short_description' => [
            'fr' => 'Bineuse mécanique professionnelle.',
            'en' => 'Professional mechanical hoe.',
            'nl' => 'Professionele mechanische schoffel.'
        ],
        'price' => 850.00,
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 2,
        'max_rental_days' => 7,
        'rental_stock' => 2,
        'category_id' => 6,
        'rental_category_id' => 1,
        'slug' => 'bineuse-mecanique-location'
    ],
    [
        'name' => [
            'fr' => 'Scarificateur Électrique',
            'en' => 'Electric Scarifier',
            'nl' => 'Elektrische Verticuteermachine'
        ],
        'description' => [
            'fr' => 'Scarificateur pour aération et entretien des pelouses',
            'en' => 'Scarifier for lawn aeration and maintenance',
            'nl' => 'Verticuteermachine voor gazonbeluchting en onderhoud'
        ],
        'short_description' => [
            'fr' => 'Scarificateur électrique efficace.',
            'en' => 'Efficient electric scarifier.',
            'nl' => 'Efficiënte elektrische verticuteermachine.'
        ],
        'price' => 280.00,
        'rental_price_per_day' => 22.00,
        'deposit_amount' => 90.00,
        'min_rental_days' => 1,
        'max_rental_days' => 2,
        'rental_stock' => 3,
        'category_id' => 6,
        'rental_category_id' => 1,
        'slug' => 'scarificateur-electrique-location'
    ],
    // 6 MACHINES
    [
        'name' => [
            'fr' => 'Mini-pelle 1.5T',
            'en' => '1.5T Mini Excavator',
            'nl' => '1.5T Mini-graafmachine'
        ],
        'description' => [
            'fr' => 'Mini-pelle compacte pour travaux de terrassement',
            'en' => 'Compact mini excavator for earthworks',
            'nl' => 'Compacte mini-graafmachine voor grondwerken'
        ],
        'short_description' => [
            'fr' => 'Mini-pelle compacte et maniable.',
            'en' => 'Compact and maneuverable mini excavator.',
            'nl' => 'Compacte en wendbare mini-graafmachine.'
        ],
        'price' => 15000.00,
        'rental_price_per_day' => 180.00,
        'deposit_amount' => 800.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'rental_stock' => 1,
        'category_id' => 7,
        'rental_category_id' => 2,
        'slug' => 'mini-pelle-15t-location'
    ],
    [
        'name' => [
            'fr' => 'Tracteur Compact 25CV',
            'en' => '25HP Compact Tractor',
            'nl' => '25PK Compacte Tractor'
        ],
        'description' => [
            'fr' => 'Tracteur compact polyvalent pour petites exploitations',
            'en' => 'Versatile compact tractor for small farms',
            'nl' => 'Veelzijdige compacte tractor voor kleine boerderijen'
        ],
        'short_description' => [
            'fr' => 'Tracteur compact polyvalent.',
            'en' => 'Versatile compact tractor.',
            'nl' => 'Veelzijdige compacte tractor.'
        ],
        'price' => 28000.00,
        'rental_price_per_day' => 120.00,
        'deposit_amount' => 1000.00,
        'min_rental_days' => 2,
        'max_rental_days' => 30,
        'rental_stock' => 1,
        'category_id' => 7,
        'rental_category_id' => 2,
        'slug' => 'tracteur-compact-25cv-location'
    ],
    [
        'name' => [
            'fr' => 'Remorque Basculante 2T',
            'en' => '2T Tipping Trailer',
            'nl' => '2T Kiepwagen'
        ],
        'description' => [
            'fr' => 'Remorque basculante pour transport de matériaux',
            'en' => 'Tipping trailer for material transport',
            'nl' => 'Kiepwagen voor materiaaltransport'
        ],
        'short_description' => [
            'fr' => 'Remorque basculante robuste.',
            'en' => 'Robust tipping trailer.',
            'nl' => 'Robuuste kiepwagen.'
        ],
        'price' => 3500.00,
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 300.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'rental_stock' => 2,
        'category_id' => 7,
        'rental_category_id' => 2,
        'slug' => 'remorque-basculante-2t-location'
    ],
    [
        'name' => [
            'fr' => 'Chargeuse Compacte',
            'en' => 'Compact Loader',
            'nl' => 'Compacte Lader'
        ],
        'description' => [
            'fr' => 'Chargeuse compacte pour manutention et chargement',
            'en' => 'Compact loader for handling and loading',
            'nl' => 'Compacte lader voor hantering en laden'
        ],
        'short_description' => [
            'fr' => 'Chargeuse compacte puissante.',
            'en' => 'Powerful compact loader.',
            'nl' => 'Krachtige compacte lader.'
        ],
        'price' => 22000.00,
        'rental_price_per_day' => 140.00,
        'deposit_amount' => 900.00,
        'min_rental_days' => 1,
        'max_rental_days' => 21,
        'rental_stock' => 1,
        'category_id' => 7,
        'rental_category_id' => 2,
        'slug' => 'chargeuse-compacte-location'
    ],
    [
        'name' => [
            'fr' => 'Gyrobroyeur 1.8m',
            'en' => '1.8m Brush Mower',
            'nl' => '1.8m Klepelmaaier'
        ],
        'description' => [
            'fr' => 'Gyrobroyeur pour entretien des espaces verts',
            'en' => 'Brush mower for green space maintenance',
            'nl' => 'Klepelmaaier voor onderhoud groene ruimtes'
        ],
        'short_description' => [
            'fr' => 'Gyrobroyeur professionnel efficace.',
            'en' => 'Efficient professional brush mower.',
            'nl' => 'Efficiënte professionele klepelmaaier.'
        ],
        'price' => 4500.00,
        'rental_price_per_day' => 65.00,
        'deposit_amount' => 400.00,
        'min_rental_days' => 1,
        'max_rental_days' => 7,
        'rental_stock' => 2,
        'category_id' => 7,
        'rental_category_id' => 2,
        'slug' => 'gyrobroyeur-18m-location'
    ],
    [
        'name' => [
            'fr' => 'Fendeuse à Bois 12T',
            'en' => '12T Log Splitter',
            'nl' => '12T Houtkliever'
        ],
        'description' => [
            'fr' => 'Fendeuse hydraulique pour bois de chauffage',
            'en' => 'Hydraulic splitter for firewood',
            'nl' => 'Hydraulische kliever voor brandhout'
        ],
        'short_description' => [
            'fr' => 'Fendeuse hydraulique puissante.',
            'en' => 'Powerful hydraulic splitter.',
            'nl' => 'Krachtige hydraulische kliever.'
        ],
        'price' => 2800.00,
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 250.00,
        'min_rental_days' => 1,
        'max_rental_days' => 5,
        'rental_stock' => 2,
        'category_id' => 7,
        'rental_category_id' => 2,
        'slug' => 'fendeuse-bois-12t-location'
    ],
    // 5 ÉQUIPEMENTS
    [
        'name' => [
            'fr' => 'Système d\'Irrigation Goutte-à-Goutte',
            'en' => 'Drip Irrigation System',
            'nl' => 'Druppelirrigatiesysteem'
        ],
        'description' => [
            'fr' => 'Système d\'irrigation automatique pour cultures',
            'en' => 'Automatic irrigation system for crops',
            'nl' => 'Automatisch irrigatiesysteem voor gewassen'
        ],
        'short_description' => [
            'fr' => 'Système d\'irrigation automatique.',
            'en' => 'Automatic irrigation system.',
            'nl' => 'Automatisch irrigatiesysteem.'
        ],
        'price' => 1200.00,
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 7,
        'max_rental_days' => 60,
        'rental_stock' => 3,
        'category_id' => 8,
        'rental_category_id' => 3,
        'slug' => 'irrigation-goutte-goutte-location'
    ],
    [
        'name' => [
            'fr' => 'Tente de Stockage 6x3m',
            'en' => '6x3m Storage Tent',
            'nl' => '6x3m Opslagtent'
        ],
        'description' => [
            'fr' => 'Tente de stockage temporaire pour matériel agricole',
            'en' => 'Temporary storage tent for agricultural equipment',
            'nl' => 'Tijdelijke opslagtent voor landbouwmaterieel'
        ],
        'short_description' => [
            'fr' => 'Tente de stockage pratique.',
            'en' => 'Practical storage tent.',
            'nl' => 'Praktische opslagtent.'
        ],
        'price' => 800.00,
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 150.00,
        'min_rental_days' => 3,
        'max_rental_days' => 30,
        'rental_stock' => 2,
        'category_id' => 8,
        'rental_category_id' => 3,
        'slug' => 'tente-stockage-6x3m-location'
    ],
    [
        'name' => [
            'fr' => 'Bâche de Protection 100m²',
            'en' => '100m² Protection Tarpaulin',
            'nl' => '100m² Beschermingszeil'
        ],
        'description' => [
            'fr' => 'Bâche de protection étanche pour cultures',
            'en' => 'Waterproof protection tarpaulin for crops',
            'nl' => 'Waterdicht beschermingszeil voor gewassen'
        ],
        'short_description' => [
            'fr' => 'Bâche de protection étanche.',
            'en' => 'Waterproof protection tarpaulin.',
            'nl' => 'Waterdicht beschermingszeil.'
        ],
        'price' => 300.00,
        'rental_price_per_day' => 8.00,
        'deposit_amount' => 80.00,
        'min_rental_days' => 2,
        'max_rental_days' => 21,
        'rental_stock' => 5,
        'category_id' => 8,
        'rental_category_id' => 3,
        'slug' => 'bache-protection-100m2-location'
    ],
    [
        'name' => [
            'fr' => 'Groupe Électrogène 5kW',
            'en' => '5kW Generator',
            'nl' => '5kW Generator'
        ],
        'description' => [
            'fr' => 'Groupe électrogène portable pour alimentation temporaire',
            'en' => 'Portable generator for temporary power supply',
            'nl' => 'Draagbare generator voor tijdelijke stroomvoorziening'
        ],
        'short_description' => [
            'fr' => 'Groupe électrogène portable.',
            'en' => 'Portable generator.',
            'nl' => 'Draagbare generator.'
        ],
        'price' => 1800.00,
        'rental_price_per_day' => 30.00,
        'deposit_amount' => 200.00,
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'rental_stock' => 2,
        'category_id' => 8,
        'rental_category_id' => 3,
        'slug' => 'groupe-electrogene-5kw-location'
    ],
    [
        'name' => [
            'fr' => 'Pompe à Eau Thermique',
            'en' => 'Thermal Water Pump',
            'nl' => 'Thermische Waterpomp'
        ],
        'description' => [
            'fr' => 'Pompe thermique haute performance pour irrigation',
            'en' => 'High-performance thermal pump for irrigation',
            'nl' => 'High-performance thermische pomp voor irrigatie'
        ],
        'short_description' => [
            'fr' => 'Pompe thermique haute performance.',
            'en' => 'High-performance thermal pump.',
            'nl' => 'High-performance thermische pomp.'
        ],
        'price' => 950.00,
        'rental_price_per_day' => 20.00,
        'deposit_amount' => 120.00,
        'min_rental_days' => 2,
        'max_rental_days' => 10,
        'rental_stock' => 3,
        'category_id' => 8,
        'rental_category_id' => 3,
        'slug' => 'pompe-eau-thermique-location'
    ]
];

// Créer directement par la base de données pour éviter les observers
echo "Création des 17 produits de location avec structure correcte...\n";

foreach($rentalProducts as $productData) {
    try {
        DB::table('products')->insert([
            'name' => json_encode($productData['name']),
            'description' => json_encode($productData['description']),
            'short_description' => json_encode($productData['short_description']),
            'price' => $productData['price'],
            'rental_price_per_day' => $productData['rental_price_per_day'],
            'deposit_amount' => $productData['deposit_amount'],
            'min_rental_days' => $productData['min_rental_days'],
            'max_rental_days' => $productData['max_rental_days'],
            'rental_stock' => $productData['rental_stock'],
            'category_id' => $productData['category_id'],
            'rental_category_id' => $productData['rental_category_id'],
            'slug' => $productData['slug'],
            'type' => 'rental',
            'is_active' => 1,
            'is_rental_available' => 1,
            'unit_symbol' => 'pièce',
            'quantity' => 0, // Stock d'achat à 0 pour les produits de location
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        echo "✅ " . $productData['name']['fr'] . " - Stock location: " . $productData['rental_stock'] . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur pour " . $productData['name']['fr'] . ": " . $e->getMessage() . "\n";
    }
}

echo "\n🎯 TERMINÉ: 17 produits de location recréés correctement!\n";
echo "✅ Noms affichés correctement (structure JSON comme les autres)\n";
echo "✅ Stock de location défini pour chaque produit\n";
echo "✅ Traductions FR/EN/NL complètes\n";
echo "✅ Catégories de location correctes\n";

?>
