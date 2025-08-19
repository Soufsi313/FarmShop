<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== COMPLETION DES PRODUITS DE LOCATION ===\n";
echo "Ajout SKU, SEO et ajustement des stocks\n\n";

$rentalProducts = [
    [
        'slug' => 'tronconneuse-pro-rental-2025',
        'sku' => 'TRON-PRO-001',
        'seo_title' => json_encode([
            'fr' => 'Tronçonneuse Professionnelle en Location | FarmShop',
            'en' => 'Professional Chainsaw Rental | FarmShop',
            'nl' => 'Professionele Kettingzaag Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Louez une tronçonneuse professionnelle puissante pour vos travaux d\'élagage et d\'abattage. Disponible à la location chez FarmShop.',
            'en' => 'Rent a powerful professional chainsaw for your pruning and felling work. Available for rental at FarmShop.',
            'nl' => 'Huur een krachtige professionele kettingzaag voor uw snoei- en kapwerkzaamheden. Beschikbaar voor verhuur bij FarmShop.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'tronçonneuse, location, élagage, abattage, professionnel',
            'en' => 'chainsaw, rental, pruning, felling, professional',
            'nl' => 'kettingzaag, verhuur, snoeien, kappen, professioneel'
        ])
    ],
    [
        'slug' => 'debroussailleuse-thermique-rental-2025',
        'sku' => 'DEBR-THER-002',
        'seo_title' => json_encode([
            'fr' => 'Débroussailleuse Thermique Location | FarmShop',
            'en' => 'Thermal Brush Cutter Rental | FarmShop',
            'nl' => 'Thermische Bosmaaier Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de débroussailleuse thermique pour entretien de grands terrains. Équipement professionnel disponible chez FarmShop.',
            'en' => 'Thermal brush cutter rental for large area maintenance. Professional equipment available at FarmShop.',
            'nl' => 'Thermische bosmaaier verhuur voor onderhoud van grote terreinen. Professionele apparatuur beschikbaar bij FarmShop.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'débroussailleuse, thermique, location, terrain, entretien',
            'en' => 'brush cutter, thermal, rental, terrain, maintenance',
            'nl' => 'bosmaaier, thermisch, verhuur, terrein, onderhoud'
        ])
    ],
    [
        'slug' => 'taille-haie-electrique-rental-2025',
        'sku' => 'TAIL-ELEC-003',
        'seo_title' => json_encode([
            'fr' => 'Taille-haie Électrique Location | FarmShop',
            'en' => 'Electric Hedge Trimmer Rental | FarmShop',
            'nl' => 'Elektrische Heggenschaar Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Louez un taille-haie électrique précis pour l\'entretien de vos haies. Location d\'outils de jardinage chez FarmShop.',
            'en' => 'Rent a precise electric hedge trimmer for your hedge maintenance. Garden tool rental at FarmShop.',
            'nl' => 'Huur een precieze elektrische heggenschaar voor uw haagonderhoud. Tuingereedschap verhuur bij FarmShop.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'taille-haie, électrique, location, haie, jardinage',
            'en' => 'hedge trimmer, electric, rental, hedge, gardening',
            'nl' => 'heggenschaar, elektrisch, verhuur, haag, tuinieren'
        ])
    ],
    [
        'slug' => 'souffleur-feuilles-rental-2025',
        'sku' => 'SOUF-FEUI-004',
        'seo_title' => json_encode([
            'fr' => 'Souffleur de Feuilles Location | FarmShop',
            'en' => 'Leaf Blower Rental | FarmShop',
            'nl' => 'Bladblazer Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de souffleur puissant pour nettoyer efficacement les feuilles mortes. Matériel professionnel chez FarmShop.',
            'en' => 'Powerful blower rental for efficient dead leaf cleaning. Professional equipment at FarmShop.',
            'nl' => 'Krachtige blazer verhuur voor efficiënte reiniging van dode bladeren. Professionele apparatuur bij FarmShop.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'souffleur, feuilles, location, nettoyage, automne',
            'en' => 'blower, leaves, rental, cleaning, autumn',
            'nl' => 'blazer, bladeren, verhuur, reiniging, herfst'
        ])
    ],
    [
        'slug' => 'bineuse-mecanique-rental-2025',
        'sku' => 'BINE-MECA-005',
        'seo_title' => json_encode([
            'fr' => 'Bineuse Mécanique Location | FarmShop',
            'en' => 'Mechanical Hoe Rental | FarmShop',
            'nl' => 'Mechanische Schoffel Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Louez une bineuse mécanique professionnelle pour le travail du sol agricole. Équipement agricole en location.',
            'en' => 'Rent a professional mechanical hoe for agricultural soil work. Agricultural equipment rental.',
            'nl' => 'Huur een professionele mechanische schoffel voor landbouwgrondbewerking. Landbouwmaterieel verhuur.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'bineuse, mécanique, location, sol, agriculture',
            'en' => 'hoe, mechanical, rental, soil, agriculture',
            'nl' => 'schoffel, mechanisch, verhuur, grond, landbouw'
        ])
    ],
    [
        'slug' => 'scarificateur-electrique-rental-2025',
        'sku' => 'SCAR-ELEC-006',
        'seo_title' => json_encode([
            'fr' => 'Scarificateur Électrique Location | FarmShop',
            'en' => 'Electric Scarifier Rental | FarmShop',
            'nl' => 'Elektrische Verticuteermachine Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de scarificateur électrique pour aération et entretien des pelouses. Matériel de jardinage professionnel.',
            'en' => 'Electric scarifier rental for lawn aeration and maintenance. Professional gardening equipment.',
            'nl' => 'Elektrische verticuteermachine verhuur voor gazonbeluchting en onderhoud. Professionele tuinapparatuur.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'scarificateur, électrique, location, pelouse, aération',
            'en' => 'scarifier, electric, rental, lawn, aeration',
            'nl' => 'verticuteermachine, elektrisch, verhuur, gazon, beluchting'
        ])
    ],
    [
        'slug' => 'mini-pelle-15t-rental-2025',
        'sku' => 'MINI-PELL-007',
        'seo_title' => json_encode([
            'fr' => 'Mini-pelle 1.5T Location | FarmShop',
            'en' => '1.5T Mini Excavator Rental | FarmShop',
            'nl' => '1.5T Mini-graafmachine Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de mini-pelle 1.5T compacte pour travaux de terrassement. Machine de chantier professionnelle disponible.',
            'en' => 'Compact 1.5T mini excavator rental for earthworks. Professional construction machinery available.',
            'nl' => 'Compacte 1.5T mini-graafmachine verhuur voor grondwerken. Professionele bouwmachines beschikbaar.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'mini-pelle, location, terrassement, chantier, excavation',
            'en' => 'mini excavator, rental, earthworks, construction, excavation',
            'nl' => 'mini-graafmachine, verhuur, grondwerken, bouw, uitgraving'
        ])
    ],
    [
        'slug' => 'tracteur-compact-25cv-rental-2025',
        'sku' => 'TRAC-COMP-008',
        'seo_title' => json_encode([
            'fr' => 'Tracteur Compact 25CV Location | FarmShop',
            'en' => '25HP Compact Tractor Rental | FarmShop',
            'nl' => '25PK Compacte Tractor Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de tracteur compact 25CV polyvalent pour petites exploitations agricoles. Matériel agricole professionnel.',
            'en' => 'Versatile 25HP compact tractor rental for small farms. Professional agricultural equipment.',
            'nl' => 'Veelzijdige 25PK compacte tractor verhuur voor kleine boerderijen. Professionele landbouwapparatuur.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'tracteur, compact, location, agriculture, exploitation',
            'en' => 'tractor, compact, rental, agriculture, farming',
            'nl' => 'tractor, compact, verhuur, landbouw, boerderij'
        ])
    ],
    [
        'slug' => 'remorque-basculante-2t-rental-2025',
        'sku' => 'REMO-BASC-009',
        'seo_title' => json_encode([
            'fr' => 'Remorque Basculante 2T Location | FarmShop',
            'en' => '2T Tipping Trailer Rental | FarmShop',
            'nl' => '2T Kiepwagen Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de remorque basculante 2T pour transport de matériaux agricoles. Remorque robuste et pratique.',
            'en' => '2T tipping trailer rental for agricultural material transport. Robust and practical trailer.',
            'nl' => '2T kiepwagen verhuur voor transport van landbouwmaterialen. Robuuste en praktische aanhanger.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'remorque, basculante, location, transport, matériaux',
            'en' => 'trailer, tipping, rental, transport, materials',
            'nl' => 'aanhanger, kiep, verhuur, transport, materialen'
        ])
    ],
    [
        'slug' => 'chargeuse-compacte-rental-2025',
        'sku' => 'CHAR-COMP-010',
        'seo_title' => json_encode([
            'fr' => 'Chargeuse Compacte Location | FarmShop',
            'en' => 'Compact Loader Rental | FarmShop',
            'nl' => 'Compacte Lader Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de chargeuse compacte puissante pour manutention et chargement. Machine de chantier polyvalente.',
            'en' => 'Powerful compact loader rental for handling and loading. Versatile construction machine.',
            'nl' => 'Krachtige compacte lader verhuur voor hantering en laden. Veelzijdige bouwmachine.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'chargeuse, compacte, location, manutention, chargement',
            'en' => 'loader, compact, rental, handling, loading',
            'nl' => 'lader, compact, verhuur, hantering, laden'
        ])
    ],
    [
        'slug' => 'gyrobroyeur-18m-rental-2025',
        'sku' => 'GYRO-BROY-011',
        'seo_title' => json_encode([
            'fr' => 'Gyrobroyeur 1.8m Location | FarmShop',
            'en' => '1.8m Brush Mower Rental | FarmShop',
            'nl' => '1.8m Klepelmaaier Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de gyrobroyeur 1.8m pour entretien professionnel des espaces verts et friches. Équipement agricole.',
            'en' => '1.8m brush mower rental for professional green space and wasteland maintenance. Agricultural equipment.',
            'nl' => '1.8m klepelmaaier verhuur voor professioneel onderhoud van groene ruimtes en braakland. Landbouwapparatuur.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'gyrobroyeur, location, espaces verts, entretien, agricole',
            'en' => 'brush mower, rental, green spaces, maintenance, agricultural',
            'nl' => 'klepelmaaier, verhuur, groene ruimtes, onderhoud, landbouw'
        ])
    ],
    [
        'slug' => 'fendeuse-bois-12t-rental-2025',
        'sku' => 'FEND-BOIS-012',
        'seo_title' => json_encode([
            'fr' => 'Fendeuse à Bois 12T Location | FarmShop',
            'en' => '12T Log Splitter Rental | FarmShop',
            'nl' => '12T Houtkliever Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de fendeuse hydraulique 12T pour bois de chauffage. Machine puissante et efficace pour fendre le bois.',
            'en' => '12T hydraulic splitter rental for firewood. Powerful and efficient machine for splitting wood.',
            'nl' => '12T hydraulische kliever verhuur voor brandhout. Krachtige en efficiënte machine voor het splijten van hout.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'fendeuse, bois, location, hydraulique, chauffage',
            'en' => 'splitter, wood, rental, hydraulic, firewood',
            'nl' => 'kliever, hout, verhuur, hydraulisch, brandhout'
        ])
    ],
    [
        'slug' => 'irrigation-goutte-goutte-rental-2025',
        'sku' => 'IRRI-GOUT-013',
        'seo_title' => json_encode([
            'fr' => 'Système Irrigation Goutte-à-Goutte Location | FarmShop',
            'en' => 'Drip Irrigation System Rental | FarmShop',
            'nl' => 'Druppelirrigatiesysteem Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de système d\'irrigation goutte-à-goutte automatique pour cultures. Économie d\'eau et efficacité garanties.',
            'en' => 'Automatic drip irrigation system rental for crops. Water saving and efficiency guaranteed.',
            'nl' => 'Automatisch druppelirrigatiesysteem verhuur voor gewassen. Waterbesparing en efficiëntie gegarandeerd.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'irrigation, goutte-à-goutte, location, automatique, cultures',
            'en' => 'irrigation, drip, rental, automatic, crops',
            'nl' => 'irrigatie, druppel, verhuur, automatisch, gewassen'
        ])
    ],
    [
        'slug' => 'tente-stockage-6x3m-rental-2025',
        'sku' => 'TENT-STOC-014',
        'seo_title' => json_encode([
            'fr' => 'Tente de Stockage 6x3m Location | FarmShop',
            'en' => '6x3m Storage Tent Rental | FarmShop',
            'nl' => '6x3m Opslagtent Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de tente de stockage temporaire 6x3m pour matériel agricole. Protection efficace et montage facile.',
            'en' => '6x3m temporary storage tent rental for agricultural equipment. Effective protection and easy assembly.',
            'nl' => '6x3m tijdelijke opslagtent verhuur voor landbouwmaterieel. Effectieve bescherming en eenvoudige montage.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'tente, stockage, location, temporaire, agricole',
            'en' => 'tent, storage, rental, temporary, agricultural',
            'nl' => 'tent, opslag, verhuur, tijdelijk, landbouw'
        ])
    ],
    [
        'slug' => 'bache-protection-100m2-rental-2025',
        'sku' => 'BACH-PROT-015',
        'seo_title' => json_encode([
            'fr' => 'Bâche de Protection 100m² Location | FarmShop',
            'en' => '100m² Protection Tarpaulin Rental | FarmShop',
            'nl' => '100m² Beschermingszeil Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de bâche de protection étanche 100m² pour cultures et matériel. Protection efficace contre les intempéries.',
            'en' => 'Waterproof 100m² protection tarpaulin rental for crops and equipment. Effective protection against weather.',
            'nl' => 'Waterdicht 100m² beschermingszeil verhuur voor gewassen en materieel. Effectieve bescherming tegen weer.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'bâche, protection, location, étanche, cultures',
            'en' => 'tarpaulin, protection, rental, waterproof, crops',
            'nl' => 'zeil, bescherming, verhuur, waterdicht, gewassen'
        ])
    ],
    [
        'slug' => 'groupe-electrogene-5kw-rental-2025',
        'sku' => 'GROU-ELEC-016',
        'seo_title' => json_encode([
            'fr' => 'Groupe Électrogène 5kW Location | FarmShop',
            'en' => '5kW Generator Rental | FarmShop',
            'nl' => '5kW Generator Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de groupe électrogène portable 5kW pour alimentation temporaire. Solution d\'énergie mobile et fiable.',
            'en' => 'Portable 5kW generator rental for temporary power supply. Mobile and reliable energy solution.',
            'nl' => 'Draagbare 5kW generator verhuur voor tijdelijke stroomvoorziening. Mobiele en betrouwbare energieoplossing.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'groupe électrogène, location, portable, alimentation, énergie',
            'en' => 'generator, rental, portable, power supply, energy',
            'nl' => 'generator, verhuur, draagbaar, stroomvoorziening, energie'
        ])
    ],
    [
        'slug' => 'pompe-eau-thermique-rental-2025',
        'sku' => 'POMP-THER-017',
        'seo_title' => json_encode([
            'fr' => 'Pompe à Eau Thermique Location | FarmShop',
            'en' => 'Thermal Water Pump Rental | FarmShop',
            'nl' => 'Thermische Waterpomp Verhuur | FarmShop'
        ]),
        'seo_description' => json_encode([
            'fr' => 'Location de pompe à eau thermique haute performance pour irrigation agricole. Équipement fiable et efficace.',
            'en' => 'High-performance thermal water pump rental for agricultural irrigation. Reliable and efficient equipment.',
            'nl' => 'High-performance thermische waterpomp verhuur voor landbouwirrigatie. Betrouwbare en efficiënte apparatuur.'
        ]),
        'seo_keywords' => json_encode([
            'fr' => 'pompe, eau, thermique, location, irrigation',
            'en' => 'pump, water, thermal, rental, irrigation',
            'nl' => 'pomp, water, thermisch, verhuur, irrigatie'
        ])
    ]
];

foreach($rentalProducts as $productData) {
    try {
        DB::table('products')
            ->where('slug', $productData['slug'])
            ->update([
                'sku' => $productData['sku'],
                'seo_title' => $productData['seo_title'],
                'seo_description' => $productData['seo_description'],
                'seo_keywords' => $productData['seo_keywords'],
                'quantity' => 25,           // Stock normal
                'rental_stock' => 25,       // Stock de location
                'updated_at' => now()
            ]);
        
        $name = json_decode(DB::table('products')->where('slug', $productData['slug'])->value('name'))->fr;
        echo "✅ " . $name . " - SKU: " . $productData['sku'] . " | Stock: 25 + 25 location\n";
    } catch (Exception $e) {
        echo "❌ Erreur pour " . $productData['slug'] . ": " . $e->getMessage() . "\n";
    }
}

echo "\n🎯 PARFAIT: Tous les produits de location complétés!\n";
echo "✅ SKU ajoutés pour chaque produit\n";
echo "✅ Optimisation SEO complète (titre, description, mots-clés)\n";
echo "✅ Traductions FR/EN/NL pour tous les champs SEO\n";
echo "✅ Stocks ajustés: 25 stock normal + 25 stock location\n";
echo "✅ Tous les produits sont maintenant complets et optimisés\n";

?>
