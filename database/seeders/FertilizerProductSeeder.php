<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FertilizerProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fertilizers = [
            [
                'name' => 'Fumier de Cheval Composté Bio',
                'description' => 'Fumier de cheval entièrement composté et certifié biologique. Riche en matière organique, il améliore la structure du sol et nourrit les plantes en profondeur. Idéal pour toutes les cultures maraîchères et fruitières. Taux de matière organique : 45%. NPK : 0,7-0,3-0,8.',
                'short_description' => 'Fumier de cheval composté bio, riche en matière organique',
                'price' => 4.50,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 500,
                'sku' => 'FERT-FUMIER-CHEVAL-001',
                'category_id' => 9, // Engrais
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Fumier de Cheval Composté Bio - Engrais Naturel',
                'meta_description' => 'Fumier de cheval bio composté pour enrichir naturellement vos sols. Certification agriculture biologique.',
            ],
            [
                'name' => 'Compost de Déchets Verts Premium',
                'description' => 'Compost de haute qualité élaboré à partir de déchets verts sélectionnés. Fermentation contrôlée pendant 18 mois minimum. Améliore la fertilité et la rétention d\'eau du sol. Convient à tous types de cultures. Matière organique : 38%. pH : 6,8-7,2.',
                'short_description' => 'Compost premium de déchets verts, 18 mois de fermentation',
                'price' => 3.80,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 750,
                'sku' => 'FERT-COMPOST-VERT-002',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Compost de Déchets Verts Premium - Amendement Bio',
                'meta_description' => 'Compost de qualité supérieure pour améliorer la fertilité de vos sols naturellement.',
            ],
            [
                'name' => 'Engrais Liquide d\'Algues Marines',
                'description' => 'Extrait concentré d\'algues marines bretonnes (Ascophyllum nodosum). Stimule la croissance, renforce les défenses naturelles des plantes et améliore la qualité des fruits. Riche en oligo-éléments et hormones de croissance naturelles. Dilution : 5ml/L d\'eau.',
                'short_description' => 'Extrait d\'algues marines concentré, stimulant naturel',
                'price' => 12.90,
                'unit_symbol' => 'litre',
                'weight' => 1.100,
                'quantity' => 200,
                'sku' => 'FERT-ALGUES-LIQ-003',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Engrais Liquide d\'Algues Marines - Stimulant Naturel',
                'meta_description' => 'Extrait d\'algues marines pour stimuler la croissance et renforcer vos plantes naturellement.',
            ],
            [
                'name' => 'Corne Broyée Torréfiée',
                'description' => 'Engrais organique azoté obtenu par broyage et torréfaction de cornes bovines. Libération progressive de l\'azote sur 3-4 mois. Particulièrement adapté aux légumes feuilles, rosiers et arbres fruitiers. Teneur en azote : 13%. Origine France.',
                'short_description' => 'Engrais azoté à libération lente, origine France',
                'price' => 8.20,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 300,
                'sku' => 'FERT-CORNE-TOR-004',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Corne Broyée Torréfiée - Engrais Azoté Naturel',
                'meta_description' => 'Engrais organique azoté à libération progressive pour nourrir vos plantes durablement.',
            ],
            [
                'name' => 'Poudre d\'Os Marine',
                'description' => 'Farine d\'arêtes de poissons de mer, source naturelle de phosphore et calcium. Favorise le développement racinaire, la floraison et la fructification. Engrais de fond idéal pour la plantation. Phosphore : 12%, Calcium : 20%. Conditionnement en poudre fine.',
                'short_description' => 'Farine d\'os de poisson, riche en phosphore et calcium',
                'price' => 6.75,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 400,
                'sku' => 'FERT-OS-MARIN-005',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Poudre d\'Os Marine - Engrais Phosphoré Naturel',
                'meta_description' => 'Farine d\'os de poisson riche en phosphore pour favoriser racines, fleurs et fruits.',
            ],
            [
                'name' => 'Sang Séché Micronisé',
                'description' => 'Engrais organique à action rapide, obtenu par déshydratation du sang animal. Très riche en azote rapidement assimilable. Idéal pour le démarrage des cultures et la croissance végétative. Azote : 14%. Stimule la formation de chlorophylle.',
                'short_description' => 'Engrais azoté à action rapide, croissance végétative',
                'price' => 9.40,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 250,
                'sku' => 'FERT-SANG-MICRO-006',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Sang Séché Micronisé - Engrais Azoté Rapide',
                'meta_description' => 'Engrais organique riche en azote pour stimuler la croissance de vos plantes.',
            ],
            [
                'name' => 'Guano de Chauve-Souris des Andes',
                'description' => 'Guano naturel récolté dans les grottes andines du Pérou. Engrais complet NPK d\'exception, 100% naturel. Très concentré en nutriments et oligo-éléments. Action prolongée 4-6 mois. NPK : 10-10-2. Idéal pour toutes cultures exigeantes.',
                'short_description' => 'Guano péruvien naturel, engrais complet d\'exception',
                'price' => 15.80,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 150,
                'sku' => 'FERT-GUANO-ANDES-007',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Guano de Chauve-Souris des Andes - Engrais Premium',
                'meta_description' => 'Guano naturel péruvien, engrais complet d\'exception pour cultures exigeantes.',
            ],
            [
                'name' => 'Vinasse de Betterave Concentrée',
                'description' => 'Sous-produit de la sucrerie, riche en potassium et matière organique. Améliore la qualité gustative des légumes et fruits. Renforce la résistance au stress hydrique. Potassium : 8%, Matière organique : 60%. Épandage au printemps.',
                'short_description' => 'Engrais potassique organique, améliore le goût des fruits',
                'price' => 5.60,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 450,
                'sku' => 'FERT-VINASSE-BET-008',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Vinasse de Betterave - Engrais Potassique Naturel',
                'meta_description' => 'Engrais potassique d\'origine végétale pour améliorer qualité et goût de vos récoltes.',
            ],
            [
                'name' => 'Tourteau de Ricin Granulé',
                'description' => 'Résidu de l\'extraction d\'huile de ricin, riche en azote à libération lente. Action répulsive naturelle contre les rongeurs et certains insectes du sol. Azote : 6%, Phosphore : 2%. Durée d\'action : 3-4 mois. Granulés calibrés 2-4mm.',
                'short_description' => 'Engrais azoté avec action répulsive anti-nuisibles',
                'price' => 7.30,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 350,
                'sku' => 'FERT-TOURTEAU-RIC-009',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Tourteau de Ricin Granulé - Engrais Anti-Nuisibles',
                'meta_description' => 'Engrais organique azoté avec propriétés répulsives contre rongeurs et insectes.',
            ],
            [
                'name' => 'Lombricompost Pur de Vers de Terre',
                'description' => 'Lombricompost 100% pur produit par des vers de terre Eisenia fetida. Digestat extrêmement riche en nutriments assimilables et microorganismes bénéfiques. Améliore la structure du sol et stimule l\'activité biologique. pH neutre : 6,8-7,0.',
                'short_description' => 'Lombricompost pur, digestat de vers de terre premium',
                'price' => 11.20,
                'unit_symbol' => 'kg',
                'weight' => 1.000,
                'quantity' => 180,
                'sku' => 'FERT-LOMBRI-PUR-010',
                'category_id' => 9,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Lombricompost Pur - Fertilisant Biologique Premium',
                'meta_description' => 'Lombricompost de qualité supérieure pour enrichir naturellement vos sols.',
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($fertilizers as $fertilizerData) {
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $fertilizerData['name'])
                              ->orWhere('sku', $fertilizerData['sku'])
                              ->first();

            if (!$existing) {
                $fertilizerData['slug'] = Str::slug($fertilizerData['name']);
                Product::create($fertilizerData);
                $created++;
                echo "✅ Engrais créé : {$fertilizerData['name']}\n";
            } else {
                $skipped++;
                echo "⚠️  Engrais ignoré (existe déjà) : {$fertilizerData['name']}\n";
            }
        }

        echo "\n📊 Résumé :\n";
        echo "✅ {$created} nouveaux engrais créés\n";
        echo "⚠️  {$skipped} engrais ignorés (doublons)\n";
        echo "💰 Prix moyens : " . number_format(collect($fertilizers)->avg('price'), 2) . "€\n";
        echo "📦 Stock total ajouté : " . collect($fertilizers)->sum('quantity') . " unités\n";
    }
}
