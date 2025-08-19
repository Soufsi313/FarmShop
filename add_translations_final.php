<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== AJOUT DES TRADUCTIONS SANS MODIFIER LES SLUGS ===\n";

// Traductions pour les 15 nouveaux produits (IDs 209-223)
$translations = [
    209 => [ // Tondeuse Professionnelle
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
            'fr' => 'Tondeuse haute performance pour professionnels.',
            'en' => 'High-performance mower for professionals.',
            'nl' => 'High-performance maaier voor professionals.'
        ]
    ],
    210 => [ // Motoculteur
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
            'fr' => 'Motoculteur puissant et efficace.',
            'en' => 'Powerful and efficient rototiller.',
            'nl' => 'Krachtige en efficiënte motoreg.'
        ]
    ],
    211 => [ // Tondeuse Autoportée
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
            'fr' => 'Tondeuse autoportée pour grandes surfaces.',
            'en' => 'Riding mower for large areas.',
            'nl' => 'Zitmaaier voor grote oppervlakten.'
        ]
    ],
    212 => [ // Épandeur d'Engrais
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
            'fr' => 'Épandeur pour distribution uniforme.',
            'en' => 'Spreader for uniform distribution.',
            'nl' => 'Strooier voor gelijkmatige verspreiding.'
        ]
    ],
    213 => [ // Pulvérisateur Agricole
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
            'fr' => 'Pulvérisateur professionnel de qualité.',
            'en' => 'Professional quality sprayer.',
            'nl' => 'Professionele kwaliteit spuit.'
        ]
    ],
    // Les 5 outils agricoles (214-218)
    214 => [
        'name' => ['fr' => 'Bêche Professionnelle', 'en' => 'Professional Spade', 'nl' => 'Professionele Spade'],
        'description' => ['fr' => 'Bêche robuste en acier trempé', 'en' => 'Sturdy tempered steel spade', 'nl' => 'Stevige gehard stalen spade'],
        'short_description' => ['fr' => 'Bêche robuste et durable.', 'en' => 'Sturdy and durable spade.', 'nl' => 'Stevige en duurzame spade.']
    ],
    215 => [
        'name' => ['fr' => 'Sécateur de Qualité', 'en' => 'Quality Pruner', 'nl' => 'Kwaliteitssnoeischaar'],
        'description' => ['fr' => 'Sécateur ergonomique à lames affûtées', 'en' => 'Ergonomic pruner with sharp blades', 'nl' => 'Ergonomische snoeischaar met scherpe messen'],
        'short_description' => ['fr' => 'Sécateur ergonomique et précis.', 'en' => 'Ergonomic and precise pruner.', 'nl' => 'Ergonomische en precieze snoeischaar.']
    ],
    216 => [
        'name' => ['fr' => 'Râteau Multi-Usage', 'en' => 'Multi-Purpose Rake', 'nl' => 'Multifunctionele Hark'],
        'description' => ['fr' => 'Râteau polyvalent pour tous travaux', 'en' => 'Versatile rake for all tasks', 'nl' => 'Veelzijdige hark voor alle taken'],
        'short_description' => ['fr' => 'Râteau polyvalent et pratique.', 'en' => 'Versatile and practical rake.', 'nl' => 'Veelzijdige en praktische hark.']
    ],
    217 => [
        'name' => ['fr' => 'Houe de Précision', 'en' => 'Precision Hoe', 'nl' => 'Precisiehouwtje'],
        'description' => ['fr' => 'Houe précise pour travail entre les rangs', 'en' => 'Precise hoe for work between rows', 'nl' => 'Precieze houw voor werk tussen rijen'],
        'short_description' => ['fr' => 'Houe précise pour travail minutieux.', 'en' => 'Precise hoe for detailed work.', 'nl' => 'Precieze houw voor gedetailleerd werk.']
    ],
    218 => [
        'name' => ['fr' => 'Fourche à Fumier', 'en' => 'Manure Fork', 'nl' => 'Mestvork'],
        'description' => ['fr' => 'Fourche spécialisée pour manipulation du fumier', 'en' => 'Specialized fork for manure handling', 'nl' => 'Gespecialiseerde vork voor mestbehandeling'],
        'short_description' => ['fr' => 'Fourche spécialisée et robuste.', 'en' => 'Specialized and sturdy fork.', 'nl' => 'Gespecialiseerde en stevige vork.']
    ],
    // Les 5 protections (219-223)
    219 => [
        'name' => ['fr' => 'Gants de Protection Agricole', 'en' => 'Agricultural Protection Gloves', 'nl' => 'Landbouwbeschermingshandschoenen'],
        'description' => ['fr' => 'Gants résistants aux produits chimiques', 'en' => 'Chemical-resistant gloves', 'nl' => 'Chemisch bestendige handschoenen'],
        'short_description' => ['fr' => 'Gants de protection résistants.', 'en' => 'Resistant protection gloves.', 'nl' => 'Resistente beschermingshandschoenen.']
    ],
    220 => [
        'name' => ['fr' => 'Masque Respiratoire', 'en' => 'Respiratory Mask', 'nl' => 'Ademhalingsmasker'],
        'description' => ['fr' => 'Masque filtrant pour protection respiratoire', 'en' => 'Filter mask for respiratory protection', 'nl' => 'Filtermasker voor ademhalingsbescherming'],
        'short_description' => ['fr' => 'Masque filtrant de protection.', 'en' => 'Protective filter mask.', 'nl' => 'Beschermend filtermasker.']
    ],
    221 => [
        'name' => ['fr' => 'Lunettes de Sécurité', 'en' => 'Safety Glasses', 'nl' => 'Veiligheidsbril'],
        'description' => ['fr' => 'Lunettes de protection anti-projection', 'en' => 'Anti-splash protection glasses', 'nl' => 'Anti-spat beschermingsbril'],
        'short_description' => ['fr' => 'Lunettes de sécurité robustes.', 'en' => 'Robust safety glasses.', 'nl' => 'Robuuste veiligheidsbril.']
    ],
    222 => [
        'name' => ['fr' => 'Combinaison de Protection', 'en' => 'Protective Suit', 'nl' => 'Beschermingspak'],
        'description' => ['fr' => 'Combinaison étanche pour pulvérisations', 'en' => 'Waterproof suit for spraying', 'nl' => 'Waterdicht pak voor spuiten'],
        'short_description' => ['fr' => 'Combinaison étanche et résistante.', 'en' => 'Waterproof and resistant suit.', 'nl' => 'Waterdicht en resistent pak.']
    ],
    223 => [
        'name' => ['fr' => 'Bottes de Sécurité', 'en' => 'Safety Boots', 'nl' => 'Veiligheidslaarzen'],
        'description' => ['fr' => 'Bottes renforcées anti-perforation', 'en' => 'Reinforced puncture-resistant boots', 'nl' => 'Versterkte punctiebestendige laarzen'],
        'short_description' => ['fr' => 'Bottes de sécurité renforcées.', 'en' => 'Reinforced safety boots.', 'nl' => 'Versterkte veiligheidslaarzen.']
    ]
];

foreach($translations as $productId => $translationData) {
    $product = Product::find($productId);
    if ($product) {
        // Mise à jour SANS le slug pour éviter les conflits
        $updateData = [
            'name' => $translationData['name'],
            'description' => $translationData['description'],
            'short_description' => $translationData['short_description']
        ];
        
        $product->update($updateData);
        echo "✅ ID $productId - Traductions ajoutées: " . $translationData['name']['fr'] . "\n";
    } else {
        echo "❌ ID $productId - Produit non trouvé\n";
    }
}

echo "\n🎯 TRADUCTIONS COMPLÈTES AJOUTÉES AVEC SUCCÈS!\n";
echo "Tous les produits ont maintenant les traductions FR/EN/NL comme les autres !\n";

?>
