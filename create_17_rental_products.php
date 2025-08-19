<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\RentalCategory;

echo "=== CRÉATION DE 17 PRODUITS DE LOCATION ===\n";

// Récupérer les catégories de location ET d'achat
$outilsRental = RentalCategory::where('slug', 'outils-agricoles')->first();
$machinesRental = RentalCategory::where('slug', 'machines')->first();
$equipementsRental = RentalCategory::where('slug', 'equipements')->first();

use App\Models\Category;
$outilsCategory = Category::where('slug', 'outils-agricoles')->first();
$machinesCategory = Category::where('slug', 'machines')->first();
$equipementCategory = Category::where('slug', 'equipement')->first();

// OUTILS AGRICOLES LOCATION (6 produits)
$outilsProducts = [
    [
        'name' => json_encode([
            'fr' => 'Tronçonneuse Professionnelle',
            'en' => 'Professional Chainsaw',
            'nl' => 'Professionele Kettingzaag'
        ]),
        'description' => json_encode([
            'fr' => 'Tronçonneuse puissante pour élagage et abattage',
            'en' => 'Powerful chainsaw for pruning and felling',
            'nl' => 'Krachtige kettingzaag voor snoeien en vellen'
        ]),
        'short_description' => json_encode([
            'fr' => 'Tronçonneuse puissante en location.',
            'en' => 'Powerful chainsaw for rent.',
            'nl' => 'Krachtige kettingzaag te huur.'
        ]),
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 150.00,
        'slug' => 'tronconneuse-professionnelle-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Débroussailleuse Thermique',
            'en' => 'Thermal Brush Cutter',
            'nl' => 'Thermische Bosmaaier'
        ]),
        'description' => json_encode([
            'fr' => 'Débroussailleuse thermique pour terrain difficile',
            'en' => 'Thermal brush cutter for difficult terrain',
            'nl' => 'Thermische bosmaaier voor moeilijk terrein'
        ]),
        'short_description' => json_encode([
            'fr' => 'Débroussailleuse thermique robuste.',
            'en' => 'Robust thermal brush cutter.',
            'nl' => 'Robuuste thermische bosmaaier.'
        ]),
        'rental_price_per_day' => 20.00,
        'deposit_amount' => 120.00,
        'slug' => 'debroussailleuse-thermique-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Taille-haie Électrique',
            'en' => 'Electric Hedge Trimmer',
            'nl' => 'Elektrische Heggenschaar'
        ]),
        'description' => json_encode([
            'fr' => 'Taille-haie électrique pour entretien précis',
            'en' => 'Electric hedge trimmer for precise maintenance',
            'nl' => 'Elektrische heggenschaar voor nauwkeurig onderhoud'
        ]),
        'short_description' => json_encode([
            'fr' => 'Taille-haie électrique précis.',
            'en' => 'Precise electric hedge trimmer.',
            'nl' => 'Precieze elektrische heggenschaar.'
        ]),
        'rental_price_per_day' => 15.00,
        'deposit_amount' => 80.00,
        'slug' => 'taille-haie-electrique-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Scie Circulaire Professionnelle',
            'en' => 'Professional Circular Saw',
            'nl' => 'Professionele Cirkelzaag'
        ]),
        'description' => json_encode([
            'fr' => 'Scie circulaire professionnelle pour découpe précise',
            'en' => 'Professional circular saw for precise cutting',
            'nl' => 'Professionele cirkelzaag voor nauwkeurig snijden'
        ]),
        'short_description' => json_encode([
            'fr' => 'Scie circulaire haute précision.',
            'en' => 'High precision circular saw.',
            'nl' => 'Hoge precisie cirkelzaag.'
        ]),
        'rental_price_per_day' => 18.00,
        'deposit_amount' => 100.00,
        'slug' => 'scie-circulaire-professionnelle-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Perceuse à Percussion',
            'en' => 'Hammer Drill',
            'nl' => 'Klopboor'
        ]),
        'description' => json_encode([
            'fr' => 'Perceuse à percussion pour travaux intensifs',
            'en' => 'Hammer drill for intensive work',
            'nl' => 'Klopboor voor intensief werk'
        ]),
        'short_description' => json_encode([
            'fr' => 'Perceuse à percussion puissante.',
            'en' => 'Powerful hammer drill.',
            'nl' => 'Krachtige klopboor.'
        ]),
        'rental_price_per_day' => 12.00,
        'deposit_amount' => 60.00,
        'slug' => 'perceuse-percussion-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Meuleuse d\'Angle Professionnelle',
            'en' => 'Professional Angle Grinder',
            'nl' => 'Professionele Haakse Slijper'
        ]),
        'description' => json_encode([
            'fr' => 'Meuleuse d\'angle professionnelle pour découpe métal',
            'en' => 'Professional angle grinder for metal cutting',
            'nl' => 'Professionele haakse slijper voor metaal snijden'
        ]),
        'short_description' => json_encode([
            'fr' => 'Meuleuse professionnelle robuste.',
            'en' => 'Robust professional grinder.',
            'nl' => 'Robuuste professionele slijper.'
        ]),
        'rental_price_per_day' => 14.00,
        'deposit_amount' => 70.00,
        'slug' => 'meuleuse-angle-professionnelle-location'
    ]
];

// MACHINES LOCATION (6 produits)
$machinesProducts = [
    [
        'name' => json_encode([
            'fr' => 'Mini-Pelle 1.5T',
            'en' => '1.5T Mini Excavator',
            'nl' => '1.5T Mini Graafmachine'
        ]),
        'description' => json_encode([
            'fr' => 'Mini-pelle compacte 1.5 tonne pour terrassement',
            'en' => 'Compact 1.5 ton mini excavator for earthworks',
            'nl' => 'Compacte 1.5 ton mini graafmachine voor grondwerk'
        ]),
        'short_description' => json_encode([
            'fr' => 'Mini-pelle compacte 1.5T.',
            'en' => 'Compact 1.5T mini excavator.',
            'nl' => 'Compacte 1.5T mini graafmachine.'
        ]),
        'rental_price_per_day' => 180.00,
        'deposit_amount' => 1000.00,
        'slug' => 'mini-pelle-15t-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Tracteur Compact avec Chargeur',
            'en' => 'Compact Tractor with Loader',
            'nl' => 'Compacte Tractor met Lader'
        ]),
        'description' => json_encode([
            'fr' => 'Tracteur compact équipé d\'un chargeur frontal',
            'en' => 'Compact tractor equipped with front loader',
            'nl' => 'Compacte tractor uitgerust met voorlader'
        ]),
        'short_description' => json_encode([
            'fr' => 'Tracteur compact avec chargeur.',
            'en' => 'Compact tractor with loader.',
            'nl' => 'Compacte tractor met lader.'
        ]),
        'rental_price_per_day' => 150.00,
        'deposit_amount' => 800.00,
        'slug' => 'tracteur-compact-chargeur-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Compresseur d\'Air 500L',
            'en' => '500L Air Compressor',
            'nl' => '500L Luchtcompressor'
        ]),
        'description' => json_encode([
            'fr' => 'Compresseur d\'air 500 litres haute pression',
            'en' => '500 liter high pressure air compressor',
            'nl' => '500 liter hogedruk luchtcompressor'
        ]),
        'short_description' => json_encode([
            'fr' => 'Compresseur 500L haute pression.',
            'en' => '500L high pressure compressor.',
            'nl' => '500L hogedruk compressor.'
        ]),
        'rental_price_per_day' => 35.00,
        'deposit_amount' => 200.00,
        'slug' => 'compresseur-air-500l-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Groupe Électrogène 10KW',
            'en' => '10KW Generator',
            'nl' => '10KW Generator'
        ]),
        'description' => json_encode([
            'fr' => 'Groupe électrogène diesel 10KW silencieux',
            'en' => 'Silent 10KW diesel generator',
            'nl' => 'Stille 10KW diesel generator'
        ]),
        'short_description' => json_encode([
            'fr' => 'Groupe électrogène 10KW silencieux.',
            'en' => 'Silent 10KW generator.',
            'nl' => 'Stille 10KW generator.'
        ]),
        'rental_price_per_day' => 45.00,
        'deposit_amount' => 300.00,
        'slug' => 'groupe-electrogene-10kw-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Nacelle Élévatrice 8m',
            'en' => '8m Aerial Lift',
            'nl' => '8m Hoogwerker'
        ]),
        'description' => json_encode([
            'fr' => 'Nacelle élévatrice électrique 8 mètres',
            'en' => 'Electric aerial lift 8 meters',
            'nl' => 'Elektrische hoogwerker 8 meter'
        ]),
        'short_description' => json_encode([
            'fr' => 'Nacelle élévatrice 8m électrique.',
            'en' => 'Electric 8m aerial lift.',
            'nl' => 'Elektrische 8m hoogwerker.'
        ]),
        'rental_price_per_day' => 120.00,
        'deposit_amount' => 600.00,
        'slug' => 'nacelle-elevatrice-8m-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Chariot Élévateur 2T',
            'en' => '2T Forklift',
            'nl' => '2T Vorkheftruck'
        ]),
        'description' => json_encode([
            'fr' => 'Chariot élévateur électrique 2 tonnes',
            'en' => 'Electric forklift 2 tons',
            'nl' => 'Elektrische vorkheftruck 2 ton'
        ]),
        'short_description' => json_encode([
            'fr' => 'Chariot élévateur 2T électrique.',
            'en' => 'Electric 2T forklift.',
            'nl' => 'Elektrische 2T vorkheftruck.'
        ]),
        'rental_price_per_day' => 90.00,
        'deposit_amount' => 500.00,
        'slug' => 'chariot-elevateur-2t-location'
    ]
];

// ÉQUIPEMENTS LOCATION (5 produits)
$equipementsProducts = [
    [
        'name' => json_encode([
            'fr' => 'Système d\'Irrigation Automatique',
            'en' => 'Automatic Irrigation System',
            'nl' => 'Automatisch Irrigatiesysteem'
        ]),
        'description' => json_encode([
            'fr' => 'Système d\'irrigation automatique programmable',
            'en' => 'Programmable automatic irrigation system',
            'nl' => 'Programmeerbaar automatisch irrigatiesysteem'
        ]),
        'short_description' => json_encode([
            'fr' => 'Irrigation automatique programmable.',
            'en' => 'Programmable automatic irrigation.',
            'nl' => 'Programmeerbare automatische irrigatie.'
        ]),
        'rental_price_per_day' => 30.00,
        'deposit_amount' => 200.00,
        'slug' => 'systeme-irrigation-automatique-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Serre Tunnel 50m²',
            'en' => '50m² Tunnel Greenhouse',
            'nl' => '50m² Tunnelkas'
        ]),
        'description' => json_encode([
            'fr' => 'Serre tunnel modulaire 50m² avec ventilation',
            'en' => 'Modular tunnel greenhouse 50m² with ventilation',
            'nl' => 'Modulaire tunnelkas 50m² met ventilatie'
        ]),
        'short_description' => json_encode([
            'fr' => 'Serre tunnel 50m² modulaire.',
            'en' => 'Modular 50m² tunnel greenhouse.',
            'nl' => 'Modulaire 50m² tunnelkas.'
        ]),
        'rental_price_per_day' => 25.00,
        'deposit_amount' => 300.00,
        'slug' => 'serre-tunnel-50m2-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Station Météo Connectée',
            'en' => 'Connected Weather Station',
            'nl' => 'Verbonden Weerstation'
        ]),
        'description' => json_encode([
            'fr' => 'Station météo connectée avec capteurs multiples',
            'en' => 'Connected weather station with multiple sensors',
            'nl' => 'Verbonden weerstation met meerdere sensoren'
        ]),
        'short_description' => json_encode([
            'fr' => 'Station météo connectée avancée.',
            'en' => 'Advanced connected weather station.',
            'nl' => 'Geavanceerd verbonden weerstation.'
        ]),
        'rental_price_per_day' => 8.00,
        'deposit_amount' => 100.00,
        'slug' => 'station-meteo-connectee-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Analyseur de Sol Numérique',
            'en' => 'Digital Soil Analyzer',
            'nl' => 'Digitale Bodemanalysator'
        ]),
        'description' => json_encode([
            'fr' => 'Analyseur de sol numérique pH et nutriments',
            'en' => 'Digital soil analyzer pH and nutrients',
            'nl' => 'Digitale bodemanalysator pH en voedingsstoffen'
        ]),
        'short_description' => json_encode([
            'fr' => 'Analyseur sol numérique complet.',
            'en' => 'Complete digital soil analyzer.',
            'nl' => 'Complete digitale bodemanalysator.'
        ]),
        'rental_price_per_day' => 12.00,
        'deposit_amount' => 150.00,
        'slug' => 'analyseur-sol-numerique-location'
    ],
    [
        'name' => json_encode([
            'fr' => 'Drone Agricole avec Caméra',
            'en' => 'Agricultural Drone with Camera',
            'nl' => 'Landbouwdrone met Camera'
        ]),
        'description' => json_encode([
            'fr' => 'Drone agricole professionnel avec caméra haute résolution',
            'en' => 'Professional agricultural drone with high resolution camera',
            'nl' => 'Professionele landbouwdrone met hoge resolutie camera'
        ]),
        'short_description' => json_encode([
            'fr' => 'Drone agricole avec caméra HD.',
            'en' => 'Agricultural drone with HD camera.',
            'nl' => 'Landbouwdrone met HD camera.'
        ]),
        'rental_price_per_day' => 50.00,
        'deposit_amount' => 400.00,
        'slug' => 'drone-agricole-camera-location'
    ]
];

// Créer les produits OUTILS AGRICOLES
echo "\n--- OUTILS AGRICOLES LOCATION (6 produits) ---\n";
foreach ($outilsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'price' => $productData['rental_price_per_day'] * 50, // Prix d'achat = 50x le prix de location
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(3, 8),
        'min_rental_days' => 1,
        'max_rental_days' => 30,
        'category_id' => $outilsCategory->id,
        'rental_category_id' => $outilsRental->id,
        'type' => 'rental',
        'is_rental_available' => true,
        'is_active' => true,
        'unit_symbol' => 'pièce'
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ") - " . $product->rental_price_per_day . "€/jour\n";
}

// Créer les produits MACHINES
echo "\n--- MACHINES LOCATION (6 produits) ---\n";
foreach ($machinesProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'price' => $productData['rental_price_per_day'] * 40, // Prix d'achat = 40x le prix de location
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(1, 3),
        'min_rental_days' => 1,
        'max_rental_days' => 14,
        'rental_category_id' => $machinesRental->id,
        'type' => 'rental',
        'is_rental_available' => true,
        'is_active' => true,
        'unit_symbol' => 'pièce'
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ") - " . $product->rental_price_per_day . "€/jour\n";
}

// Créer les produits ÉQUIPEMENTS
echo "\n--- ÉQUIPEMENTS LOCATION (5 produits) ---\n";
foreach ($equipementsProducts as $productData) {
    $product = Product::create([
        'name' => $productData['name'],
        'description' => $productData['description'],
        'short_description' => $productData['short_description'],
        'price' => $productData['rental_price_per_day'] * 60, // Prix d'achat = 60x le prix de location
        'rental_price_per_day' => $productData['rental_price_per_day'],
        'deposit_amount' => $productData['deposit_amount'],
        'slug' => $productData['slug'],
        'rental_stock' => rand(2, 5),
        'min_rental_days' => 1,
        'max_rental_days' => 21,
        'rental_category_id' => $equipementsRental->id,
        'type' => 'rental',
        'is_rental_available' => true,
        'is_active' => true,
        'unit_symbol' => 'pièce'
    ]);
    echo "✅ " . json_decode($product->name)->fr . " (ID: " . $product->id . ") - " . $product->rental_price_per_day . "€/jour\n";
}

echo "\n🎯 TERMINÉ: 17 produits de location créés avec succès !\n";
echo "📊 Répartition:\n";
echo "   - Outils agricoles: 6 produits\n";
echo "   - Machines: 6 produits\n";
echo "   - Équipements: 5 produits\n";
echo "\n✅ Tous traduits en FR/EN/NL avec prix de location et cautions appropriés !\n";

?>
