<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\Product;

echo "=== STRUCTURE TABLE PRODUCTS ===\n";
$columns = Schema::getColumnListing('products');
foreach($columns as $col) {
    echo "- " . $col . "\n";
}

echo "\n=== CORRECTION DIRECTE DES TRADUCTIONS ===\n";

// Correction directe par ID
$translations = [
    178 => [ // Tondeuse Professionnelle
        'name_en' => 'Professional Mower',
        'name_nl' => 'Professionele Maaier',
        'description_en' => 'High-performance professional mower for green space maintenance',
        'description_nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    179 => [ // Motoculteur
        'name_en' => 'Rototiller',
        'name_nl' => 'Motoreg',
        'description_en' => 'Powerful rototiller for soil work',
        'description_nl' => 'Krachtige motoreg voor grondbewerking',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    180 => [ // Tondeuse Autoportée
        'name_en' => 'Riding Mower',
        'name_nl' => 'Zitmaaier',
        'description_en' => 'Riding mower for large areas',
        'description_nl' => 'Zitmaaier voor grote oppervlakten',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    181 => [ // Épandeur d'Engrais
        'name_en' => 'Fertilizer Spreader',
        'name_nl' => 'Meststrooier',
        'description_en' => 'Spreader for uniform fertilizer distribution',
        'description_nl' => 'Strooier voor gelijkmatige mestverspreiding',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    182 => [ // Pulvérisateur Agricole
        'name_en' => 'Agricultural Sprayer',
        'name_nl' => 'Landbouwspuit',
        'description_en' => 'Professional sprayer for treatments',
        'description_nl' => 'Professionele spuit voor behandelingen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    183 => [ // Bêche Professionnelle
        'name_en' => 'Professional Spade',
        'name_nl' => 'Professionele Spade',
        'description_en' => 'Sturdy tempered steel spade',
        'description_nl' => 'Stevige gehard stalen spade',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    184 => [ // Sécateur de Qualité
        'name_en' => 'Quality Pruner',
        'name_nl' => 'Kwaliteitssnoeischaar',
        'description_en' => 'Ergonomic pruner with sharp blades',
        'description_nl' => 'Ergonomische snoeischaar met scherpe messen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    185 => [ // Râteau Multi-Usage
        'name_en' => 'Multi-Purpose Rake',
        'name_nl' => 'Multifunctionele Hark',
        'description_en' => 'Versatile rake for all tasks',
        'description_nl' => 'Veelzijdige hark voor alle taken',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    186 => [ // Houe de Précision
        'name_en' => 'Precision Hoe',
        'name_nl' => 'Precisiehouwtje',
        'description_en' => 'Precise hoe for work between rows',
        'description_nl' => 'Precieze houw voor werk tussen rijen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    187 => [ // Fourche à Fumier
        'name_en' => 'Manure Fork',
        'name_nl' => 'Mestvork',
        'description_en' => 'Specialized fork for manure handling',
        'description_nl' => 'Gespecialiseerde vork voor mestbehandeling',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    188 => [ // Gants de Protection Agricole
        'name_en' => 'Agricultural Protection Gloves',
        'name_nl' => 'Landbouwbeschermingshandschoenen',
        'description_en' => 'Chemical-resistant gloves',
        'description_nl' => 'Chemisch bestendige handschoenen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    189 => [ // Masque Respiratoire
        'name_en' => 'Respiratory Mask',
        'name_nl' => 'Ademhalingsmasker',
        'description_en' => 'Filter mask for respiratory protection',
        'description_nl' => 'Filtermasker voor ademhalingsbescherming',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    190 => [ // Lunettes de Sécurité
        'name_en' => 'Safety Glasses',
        'name_nl' => 'Veiligheidsbril',
        'description_en' => 'Anti-splash protection glasses',
        'description_nl' => 'Anti-spat beschermingsbril',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    191 => [ // Combinaison de Protection
        'name_en' => 'Protective Suit',
        'name_nl' => 'Beschermingspak',
        'description_en' => 'Waterproof suit for spraying',
        'description_nl' => 'Waterdicht pak voor spuiten',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ],
    192 => [ // Bottes de Sécurité
        'name_en' => 'Safety Boots',
        'name_nl' => 'Veiligheidslaarzen',
        'description_en' => 'Reinforced puncture-resistant boots',
        'description_nl' => 'Versterkte punctiebestendige laarzen',
        'unit_en' => 'pieces',
        'unit_nl' => 'stuks'
    ]
];

foreach($translations as $productId => $translationData) {
    $product = Product::find($productId);
    if ($product) {
        try {
            $product->update($translationData);
            echo "✅ ID $productId ({$product->name}) - Traduit\n";
        } catch (Exception $e) {
            echo "❌ ID $productId - Erreur: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ ID $productId - Produit non trouvé\n";
    }
}

echo "\n=== VÉRIFICATION FINALE DÉTAILLÉE ===\n";
foreach([178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192] as $id) {
    $product = Product::find($id);
    if ($product) {
        echo "ID $id: " . $product->name . "\n";
        echo "  EN: " . ($product->name_en ?? 'NULL') . "\n";
        echo "  NL: " . ($product->name_nl ?? 'NULL') . "\n";
        echo "  Unité EN: " . ($product->unit_en ?? 'NULL') . "\n";
        echo "  Unité NL: " . ($product->unit_nl ?? 'NULL') . "\n";
        echo "  ---\n";
    }
}

echo "🎯 CORRECTION TERMINÉE!\n";

?>
