<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeculentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Féculents"
        $feculentsCategory = Category::where('name', 'Féculents')->first();
        
        if (!$feculentsCategory) {
            $this->command->error('Catégorie "Féculents" non trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $feculentsProducts = [
            [
                'name' => 'Pommes de Terre Charlotte Féculents Bio',
                'description' => 'Pommes de terre Charlotte biologiques cultivées dans nos terres argilo-calcaires. Variété à chair ferme, excellente en cuisson vapeur, sautées ou en salade. Récolte récente, conservation naturelle en cave. Calibre moyen à gros, sans traitement post-récolte.',
                'short_description' => 'Pommes de terre Charlotte bio spécial féculents, chair ferme',
                'sku' => 'FECULENTS-PDT-CHARLOTTE-001',
                'price' => 2.80,
                'quantity' => 500,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 50,
                'low_stock_threshold' => 100,
                'out_of_stock_threshold' => 25,
                'meta_title' => 'Pommes de Terre Charlotte Bio Féculents - Production Fermière',
                'meta_description' => 'Pommes de terre Charlotte biologiques à chair ferme, cultivées sans traitement. Parfaites pour toutes cuissons.',
                'meta_keywords' => 'pommes de terre, charlotte, bio, chair ferme, fermier, féculents'
            ],
            [
                'name' => 'Pommes de Terre Bintje Féculents Bio',
                'description' => 'Pommes de terre Bintje biologiques, variété polyvalente à chair jaune. Idéales pour frites, purée et cuisson au four. Cultivées selon les méthodes traditionnelles dans nos champs en rotation. Tubercules de belle forme, conservation optimale.',
                'short_description' => 'Pommes de terre Bintje bio féculents, polyvalentes pour toutes préparations',
                'sku' => 'FECULENTS-PDT-BINTJE-002',
                'price' => 2.50,
                'quantity' => 600,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 60,
                'low_stock_threshold' => 120,
                'out_of_stock_threshold' => 30,
                'meta_title' => 'Pommes de Terre Bintje Bio Féculents - Variété Polyvalente',
                'meta_description' => 'Pommes de terre Bintje biologiques à chair jaune, parfaites pour frites et purée. Culture fermière traditionnelle.',
                'meta_keywords' => 'pommes de terre, bintje, bio, frites, purée, fermier, féculents'
            ],
            [
                'name' => 'Topinambours Frais de Saison',
                'description' => 'Topinambours frais récoltés dans nos jardins de légumes anciens. Tubercules à la saveur délicate rappelant l\'artichaut. Riches en inuline, excellents pour la digestion. Récolte manuelle, lavage minimal pour préserver la fraîcheur.',
                'short_description' => 'Topinambours frais fermiers, légume ancien aux multiples bienfaits',
                'sku' => 'FECULENTS-TOPINAMBOUR-003',
                'price' => 4.50,
                'quantity' => 80,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 8,
                'low_stock_threshold' => 16,
                'out_of_stock_threshold' => 4,
                'meta_title' => 'Topinambours Frais - Légume Ancien Fermier',
                'meta_description' => 'Topinambours frais de production fermière, légume ancien riche en inuline. Saveur délicate et originale.',
                'meta_keywords' => 'topinambours, légume ancien, inuline, fermier, frais'
            ],
            [
                'name' => 'Patates Douces Orange Bio',
                'description' => 'Patates douces à chair orange cultivées sous tunnel dans nos serres chauffées naturellement. Variété sucrée et fondante, riche en bêta-carotène. Récolte manuelle, séchage traditionnel au soleil. Conservation longue durée en cave.',
                'short_description' => 'Patates douces orange bio, sucrées et riches en vitamines',
                'sku' => 'FECULENTS-PATATE-DOUCE-004',
                'price' => 6.20,
                'quantity' => 120,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 12,
                'low_stock_threshold' => 24,
                'out_of_stock_threshold' => 6,
                'meta_title' => 'Patates Douces Orange Bio - Culture Sous Serre',
                'meta_description' => 'Patates douces biologiques à chair orange, cultivées sous serre. Riches en bêta-carotène, saveur sucrée.',
                'meta_keywords' => 'patates douces, orange, bio, bêta-carotène, serre'
            ],
            [
                'name' => 'Haricots Coco Secs de la Ferme',
                'description' => 'Haricots coco secs récoltés à parfaite maturité dans nos champs de légumineuses. Variété traditionnelle française, grains blancs cremeux. Séchage naturel au soleil, tri manuel. Excellents en cassoulet ou en accompagnement mijoté.',
                'short_description' => 'Haricots coco secs fermiers, variété traditionnelle française',
                'sku' => 'FECULENTS-HARICOT-COCO-005',
                'price' => 8.50,
                'quantity' => 150,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 15,
                'low_stock_threshold' => 30,
                'out_of_stock_threshold' => 8,
                'meta_title' => 'Haricots Coco Secs - Légumineuses Fermières',
                'meta_description' => 'Haricots coco secs de production fermière, variété française traditionnelle. Parfaits pour cassoulet.',
                'meta_keywords' => 'haricots coco, secs, légumineuses, cassoulet, fermier'
            ],
            [
                'name' => 'Lentilles Vertes du Puy Fermières',
                'description' => 'Lentilles vertes du Puy cultivées selon la tradition dans nos parcelles volcaniques. AOC respectée, tri minutieux à la main. Cuisson rapide, goût authentique de terroir. Séchage naturel, conservation en sacs de toile.',
                'short_description' => 'Lentilles vertes du Puy AOC, production fermière traditionnelle',
                'sku' => 'FECULENTS-LENTILLES-PUY-006',
                'price' => 12.00,
                'quantity' => 100,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 10,
                'low_stock_threshold' => 20,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Lentilles Vertes du Puy AOC - Production Fermière',
                'meta_description' => 'Lentilles vertes du Puy AOC de production fermière, cultivées en terroir volcanique. Goût authentique.',
                'meta_keywords' => 'lentilles vertes, puy, AOC, volcanique, fermier'
            ],
            [
                'name' => 'Pois Chiches Secs Biologiques',
                'description' => 'Pois chiches biologiques cultivés dans nos champs en agriculture biologique certifiée. Variété à gros grains, riche en protéines végétales. Récolte tardive pour optimiser la concentration en nutriments. Trempage et cuisson traditionnels recommandés.',
                'short_description' => 'Pois chiches bio fermiers, riches en protéines végétales',
                'sku' => 'FECULENTS-POIS-CHICHES-007',
                'price' => 7.80,
                'quantity' => 200,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 20,
                'low_stock_threshold' => 40,
                'out_of_stock_threshold' => 10,
                'meta_title' => 'Pois Chiches Bio - Légumineuses Protéinées',
                'meta_description' => 'Pois chiches biologiques fermiers, riches en protéines végétales. Culture certifiée biologique.',
                'meta_keywords' => 'pois chiches, bio, protéines, légumineuses, fermier'
            ],
            [
                'name' => 'Quinoa Blanc Local Expérimental',
                'description' => 'Quinoa blanc cultivé expérimentalement dans nos parcelles d\'essai. Première production locale réussie, graines parfaitement formées. Alternative locale aux importations, riche en acides aminés essentiels. Décorticage artisanal minutieux.',
                'short_description' => 'Quinoa blanc local, première production fermière française',
                'sku' => 'FECULENTS-QUINOA-BLANC-008',
                'price' => 18.00,
                'quantity' => 25,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 2,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Quinoa Blanc Local - Production Expérimentale',
                'meta_description' => 'Quinoa blanc de production fermière locale, alternative aux importations. Riche en acides aminés.',
                'meta_keywords' => 'quinoa blanc, local, expérimental, acides aminés, fermier'
            ],
            [
                'name' => 'Châtaignes Fraîches de Nos Vergers',
                'description' => 'Châtaignes fraîches récoltées dans nos châtaigneraies centenaires. Variétés anciennes préservées, calibre extra. Ramassage manuel quotidien, tri rigoureux. Conservation naturelle en cageots aérés. Idéales grillées, en farine ou en accompagnement.',
                'short_description' => 'Châtaignes fraîches de vergers centenaires, variétés anciennes',
                'sku' => 'FECULENTS-CHATAIGNES-009',
                'price' => 9.50,
                'quantity' => 180,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 18,
                'low_stock_threshold' => 36,
                'out_of_stock_threshold' => 9,
                'meta_title' => 'Châtaignes Fraîches - Vergers Centenaires',
                'meta_description' => 'Châtaignes fraîches de vergers centenaires, variétés anciennes préservées. Ramassage manuel quotidien.',
                'meta_keywords' => 'châtaignes, fraîches, vergers, anciennes, ramassage'
            ],
            [
                'name' => 'Farine de Blé Ancien Moulue à la Meule',
                'description' => 'Farine de blé ancien type 80 moulue à la meule de pierre dans notre moulin fermier. Blés paysans non hybridés, cultivés sans pesticides. Mouture lente préservant tous les nutriments. Goût authentique de blé, parfaite pour pain traditionnel.',
                'short_description' => 'Farine de blé ancien moulue à la meule, moulin fermier',
                'sku' => 'FECULENTS-FARINE-BLE-010',
                'price' => 3.20,
                'quantity' => 300,
                'unit_symbol' => 'kg',
                'weight' => '1.0',
                'type' => 'sale',
                'category_id' => $feculentsCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 30,
                'low_stock_threshold' => 60,
                'out_of_stock_threshold' => 15,
                'meta_title' => 'Farine Blé Ancien - Moulin à Meule de Pierre',
                'meta_description' => 'Farine de blé ancien type 80, moulue à la meule de pierre. Blés paysans non hybridés, mouture lente.',
                'meta_keywords' => 'farine, blé ancien, meule pierre, paysans, moulin'
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($feculentsProducts as $productData) {
            // Générer le slug
            $productData['slug'] = Str::slug($productData['name']);
            
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $productData['name'])
                              ->where('category_id', $feculentsCategory->id)
                              ->first();
            
            if (!$existing) {
                Product::create($productData);
                $created++;
                $this->command->info("✅ Créé: {$productData['name']} - {$productData['price']}€");
            } else {
                $skipped++;
                $this->command->warn("⚠️  Existe déjà: {$productData['name']}");
            }
        }

        $this->command->info("\n🥔 === FÉCULENTS FERMIERS AJOUTÉS ===");
        $this->command->info("📊 Résumé de l'opération:");
        $this->command->info("✅ {$created} nouveaux féculents créés");
        $this->command->info("⚠️  {$skipped} produits déjà existants");
        
        if ($created > 0) {
            $this->command->info("\n🌾 Gamme complète de féculents fermiers:");
            $this->command->info("• Tubercules: Pommes de terre Charlotte & Bintje, Topinambours, Patates douces");
            $this->command->info("• Légumineuses: Haricots coco, Lentilles du Puy AOC, Pois chiches bio");
            $this->command->info("• Spécialités: Quinoa local expérimental, Châtaignes fraîches");
            $this->command->info("• Transformation: Farine de blé ancien moulue à la meule");
            $this->command->info("💰 Prix de 2,50€ à 18,00€ selon rareté et transformation");
            $this->command->info("🏷️  Production 100% fermière avec méthodes traditionnelles");
            $this->command->info("🌱 Focus sur variétés anciennes et alternatives locales");
        }
    }
}
