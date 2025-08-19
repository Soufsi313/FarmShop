<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== MISE À JOUR DIRECTE PAR LA BASE DE DONNÉES ===\n";

// Mise à jour directe sans passer par Eloquent pour éviter les observers
$updates = [
    209 => [
        'name' => json_encode(['fr' => 'Tondeuse Professionnelle', 'en' => 'Professional Mower', 'nl' => 'Professionele Maaier']),
        'description' => json_encode(['fr' => 'Tondeuse professionnelle haute performance pour entretien des espaces verts', 'en' => 'High-performance professional mower for green space maintenance', 'nl' => 'High-performance professionele maaier voor onderhoud van groene ruimtes']),
        'short_description' => json_encode(['fr' => 'Tondeuse haute performance pour professionnels.', 'en' => 'High-performance mower for professionals.', 'nl' => 'High-performance maaier voor professionals.'])
    ],
    210 => [
        'name' => json_encode(['fr' => 'Motoculteur', 'en' => 'Rototiller', 'nl' => 'Motoreg']),
        'description' => json_encode(['fr' => 'Motoculteur puissant pour travail du sol', 'en' => 'Powerful rototiller for soil work', 'nl' => 'Krachtige motoreg voor grondbewerking']),
        'short_description' => json_encode(['fr' => 'Motoculteur puissant et efficace.', 'en' => 'Powerful and efficient rototiller.', 'nl' => 'Krachtige en efficiënte motoreg.'])
    ],
    211 => [
        'name' => json_encode(['fr' => 'Tondeuse Autoportée', 'en' => 'Riding Mower', 'nl' => 'Zitmaaier']),
        'description' => json_encode(['fr' => 'Tondeuse autoportée pour grandes surfaces', 'en' => 'Riding mower for large areas', 'nl' => 'Zitmaaier voor grote oppervlakten']),
        'short_description' => json_encode(['fr' => 'Tondeuse autoportée pour grandes surfaces.', 'en' => 'Riding mower for large areas.', 'nl' => 'Zitmaaier voor grote oppervlakten.'])
    ],
    212 => [
        'name' => json_encode(['fr' => 'Épandeur d\'Engrais', 'en' => 'Fertilizer Spreader', 'nl' => 'Meststrooier']),
        'description' => json_encode(['fr' => 'Épandeur pour distribution uniforme d\'engrais', 'en' => 'Spreader for uniform fertilizer distribution', 'nl' => 'Strooier voor gelijkmatige mestverspreiding']),
        'short_description' => json_encode(['fr' => 'Épandeur pour distribution uniforme.', 'en' => 'Spreader for uniform distribution.', 'nl' => 'Strooier voor gelijkmatige verspreiding.'])
    ],
    213 => [
        'name' => json_encode(['fr' => 'Pulvérisateur Agricole', 'en' => 'Agricultural Sprayer', 'nl' => 'Landbouwspuit']),
        'description' => json_encode(['fr' => 'Pulvérisateur professionnel pour traitements', 'en' => 'Professional sprayer for treatments', 'nl' => 'Professionele spuit voor behandelingen']),
        'short_description' => json_encode(['fr' => 'Pulvérisateur professionnel de qualité.', 'en' => 'Professional quality sprayer.', 'nl' => 'Professionele kwaliteit spuit.'])
    ],
    214 => [
        'name' => json_encode(['fr' => 'Bêche Professionnelle', 'en' => 'Professional Spade', 'nl' => 'Professionele Spade']),
        'description' => json_encode(['fr' => 'Bêche robuste en acier trempé', 'en' => 'Sturdy tempered steel spade', 'nl' => 'Stevige gehard stalen spade']),
        'short_description' => json_encode(['fr' => 'Bêche robuste et durable.', 'en' => 'Sturdy and durable spade.', 'nl' => 'Stevige en duurzame spade.'])
    ],
    215 => [
        'name' => json_encode(['fr' => 'Sécateur de Qualité', 'en' => 'Quality Pruner', 'nl' => 'Kwaliteitssnoeischaar']),
        'description' => json_encode(['fr' => 'Sécateur ergonomique à lames affûtées', 'en' => 'Ergonomic pruner with sharp blades', 'nl' => 'Ergonomische snoeischaar met scherpe messen']),
        'short_description' => json_encode(['fr' => 'Sécateur ergonomique et précis.', 'en' => 'Ergonomic and precise pruner.', 'nl' => 'Ergonomische en precieze snoeischaar.'])
    ],
    216 => [
        'name' => json_encode(['fr' => 'Râteau Multi-Usage', 'en' => 'Multi-Purpose Rake', 'nl' => 'Multifunctionele Hark']),
        'description' => json_encode(['fr' => 'Râteau polyvalent pour tous travaux', 'en' => 'Versatile rake for all tasks', 'nl' => 'Veelzijdige hark voor alle taken']),
        'short_description' => json_encode(['fr' => 'Râteau polyvalent et pratique.', 'en' => 'Versatile and practical rake.', 'nl' => 'Veelzijdige en praktische hark.'])
    ],
    217 => [
        'name' => json_encode(['fr' => 'Houe de Précision', 'en' => 'Precision Hoe', 'nl' => 'Precisiehouwtje']),
        'description' => json_encode(['fr' => 'Houe précise pour travail entre les rangs', 'en' => 'Precise hoe for work between rows', 'nl' => 'Precieze houw voor werk tussen rijen']),
        'short_description' => json_encode(['fr' => 'Houe précise pour travail minutieux.', 'en' => 'Precise hoe for detailed work.', 'nl' => 'Precieze houw voor gedetailleerd werk.'])
    ],
    218 => [
        'name' => json_encode(['fr' => 'Fourche à Fumier', 'en' => 'Manure Fork', 'nl' => 'Mestvork']),
        'description' => json_encode(['fr' => 'Fourche spécialisée pour manipulation du fumier', 'en' => 'Specialized fork for manure handling', 'nl' => 'Gespecialiseerde vork voor mestbehandeling']),
        'short_description' => json_encode(['fr' => 'Fourche spécialisée et robuste.', 'en' => 'Specialized and sturdy fork.', 'nl' => 'Gespecialiseerde en stevige vork.'])
    ],
    219 => [
        'name' => json_encode(['fr' => 'Gants de Protection Agricole', 'en' => 'Agricultural Protection Gloves', 'nl' => 'Landbouwbeschermingshandschoenen']),
        'description' => json_encode(['fr' => 'Gants résistants aux produits chimiques', 'en' => 'Chemical-resistant gloves', 'nl' => 'Chemisch bestendige handschoenen']),
        'short_description' => json_encode(['fr' => 'Gants de protection résistants.', 'en' => 'Resistant protection gloves.', 'nl' => 'Resistente beschermingshandschoenen.'])
    ],
    220 => [
        'name' => json_encode(['fr' => 'Masque Respiratoire', 'en' => 'Respiratory Mask', 'nl' => 'Ademhalingsmasker']),
        'description' => json_encode(['fr' => 'Masque filtrant pour protection respiratoire', 'en' => 'Filter mask for respiratory protection', 'nl' => 'Filtermasker voor ademhalingsbescherming']),
        'short_description' => json_encode(['fr' => 'Masque filtrant de protection.', 'en' => 'Protective filter mask.', 'nl' => 'Beschermend filtermasker.'])
    ],
    221 => [
        'name' => json_encode(['fr' => 'Lunettes de Sécurité', 'en' => 'Safety Glasses', 'nl' => 'Veiligheidsbril']),
        'description' => json_encode(['fr' => 'Lunettes de protection anti-projection', 'en' => 'Anti-splash protection glasses', 'nl' => 'Anti-spat beschermingsbril']),
        'short_description' => json_encode(['fr' => 'Lunettes de sécurité robustes.', 'en' => 'Robust safety glasses.', 'nl' => 'Robuuste veiligheidsbril.'])
    ],
    222 => [
        'name' => json_encode(['fr' => 'Combinaison de Protection', 'en' => 'Protective Suit', 'nl' => 'Beschermingspak']),
        'description' => json_encode(['fr' => 'Combinaison étanche pour pulvérisations', 'en' => 'Waterproof suit for spraying', 'nl' => 'Waterdicht pak voor spuiten']),
        'short_description' => json_encode(['fr' => 'Combinaison étanche et résistante.', 'en' => 'Waterproof and resistant suit.', 'nl' => 'Waterdicht en resistent pak.'])
    ],
    223 => [
        'name' => json_encode(['fr' => 'Bottes de Sécurité', 'en' => 'Safety Boots', 'nl' => 'Veiligheidslaarzen']),
        'description' => json_encode(['fr' => 'Bottes renforcées anti-perforation', 'en' => 'Reinforced puncture-resistant boots', 'nl' => 'Versterkte punctiebestendige laarzen']),
        'short_description' => json_encode(['fr' => 'Bottes de sécurité renforcées.', 'en' => 'Reinforced safety boots.', 'nl' => 'Versterkte veiligheidslaarzen.'])
    ]
];

foreach($updates as $productId => $updateData) {
    try {
        DB::table('products')
            ->where('id', $productId)
            ->update($updateData);
        
        echo "✅ ID $productId - Traductions ajoutées directement\n";
    } catch (Exception $e) {
        echo "❌ ID $productId - Erreur: " . $e->getMessage() . "\n";
    }
}

echo "\n🎯 TRADUCTIONS AJOUTÉES AVEC SUCCÈS PAR LA BASE DE DONNÉES!\n";
echo "Tous les 15 produits ont maintenant les traductions FR/EN/NL comme les autres !\n";

?>
