<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SemencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Semences"
        $semencesCategory = Category::where('name', 'Semences')->first();
        
        if (!$semencesCategory) {
            $this->command->error('Catégorie "Semences" non trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $semencesProducts = [
            [
                'name' => 'Graines de Radis Rose de 18 Jours',
                'description' => 'Graines de radis rose de 18 jours, variété hâtive traditionnelle française. Production de notre propre récolte porte-graines. Germination rapide et excellente, récolte précoce. Radis croquants et peu piquants, parfaits pour débutants.',
                'short_description' => 'Graines radis rose 18 jours, variété hâtive fermière',
                'sku' => 'SEMENCES-RADIS-18J-001',
                'price' => 2.80,
                'quantity' => 150,
                'unit_symbol' => 'pièce',
                'weight' => '0.01',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 15,
                'low_stock_threshold' => 30,
                'out_of_stock_threshold' => 8,
                'meta_title' => 'Graines Radis Rose 18 Jours - Semences Fermières',
                'meta_description' => 'Graines de radis rose de 18 jours, production fermière. Variété hâtive traditionnelle, germination excellente.',
                'meta_keywords' => 'graines, radis, 18 jours, hâtive, fermier, semences'
            ],
            [
                'name' => 'Graines de Laitue Batavia Blonde de Paris',
                'description' => 'Graines de laitue Batavia Blonde de Paris, variété ancienne rustique. Semences reproductibles issues de nos cultures maraîchères. Salade volumineuse résistante à la montée en graines. Production échelonnée possible.',
                'short_description' => 'Graines laitue Batavia blonde, variété ancienne rustique',
                'sku' => 'SEMENCES-LAITUE-BATAVIA-002',
                'price' => 3.20,
                'quantity' => 120,
                'unit_symbol' => 'pièce',
                'weight' => '0.01',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 12,
                'low_stock_threshold' => 24,
                'out_of_stock_threshold' => 6,
                'meta_title' => 'Graines Laitue Batavia Blonde Paris - Variété Ancienne',
                'meta_description' => 'Graines de laitue Batavia Blonde de Paris, variété ancienne rustique. Semences reproductibles fermières.',
                'meta_keywords' => 'graines, laitue, batavia, blonde, paris, ancienne'
            ],
            [
                'name' => 'Graines de Tomate Cœur de Bœuf Rouge',
                'description' => 'Graines de tomate Cœur de Bœuf rouge, variété ancienne à gros fruits. Sélection fermière sur plants mères exceptionnels. Tomates charnues de 300-500g, goût authentique. Semis sous abri recommandé.',
                'short_description' => 'Graines tomate Cœur de Bœuf, variété ancienne à gros fruits',
                'sku' => 'SEMENCES-TOMATE-COEUR-003',
                'price' => 4.50,
                'quantity' => 80,
                'unit_symbol' => 'pièce',
                'weight' => '0.01',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 8,
                'low_stock_threshold' => 16,
                'out_of_stock_threshold' => 4,
                'meta_title' => 'Graines Tomate Cœur de Bœuf - Variété Ancienne',
                'meta_description' => 'Graines de tomate Cœur de Bœuf rouge, sélection fermière. Gros fruits charnus 300-500g, goût authentique.',
                'meta_keywords' => 'graines, tomate, cœur de bœuf, ancienne, gros fruits'
            ],
            [
                'name' => 'Graines de Haricot Vert Fin de Bagnols',
                'description' => 'Graines de haricot vert fin de Bagnols, variété traditionnelle du Sud. Production fermière sélectionnée pour la finesse des gousses. Haricots mangetout très tendres, sans fils. Récolte abondante et échelonnée.',
                'short_description' => 'Graines haricot vert fin Bagnols, tradition du Sud',
                'sku' => 'SEMENCES-HARICOT-BAGNOLS-004',
                'price' => 5.20,
                'quantity' => 100,
                'unit_symbol' => 'pièce',
                'weight' => '0.05',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 10,
                'low_stock_threshold' => 20,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Graines Haricot Vert Fin Bagnols - Tradition Sud',
                'meta_description' => 'Graines de haricot vert fin de Bagnols, variété traditionnelle. Gousses très tendres sans fils.',
                'meta_keywords' => 'graines, haricot vert, bagnols, fin, tradition, sud'
            ],
            [
                'name' => 'Graines de Carotte de Colmar à Cœur Rouge',
                'description' => 'Graines de carotte de Colmar à cœur rouge, variété ancienne alsacienne. Semences issues de nos parcelles de conservation. Racines longues et coniques, chair rouge-orangé. Conservation hivernale excellente.',
                'short_description' => 'Graines carotte Colmar cœur rouge, variété alsacienne',
                'sku' => 'SEMENCES-CAROTTE-COLMAR-005',
                'price' => 3.80,
                'quantity' => 90,
                'unit_symbol' => 'pièce',
                'weight' => '0.01',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 9,
                'low_stock_threshold' => 18,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Graines Carotte Colmar Cœur Rouge - Alsacienne',
                'meta_description' => 'Graines de carotte de Colmar à cœur rouge, variété ancienne alsacienne. Conservation hivernale excellente.',
                'meta_keywords' => 'graines, carotte, colmar, cœur rouge, alsacienne'
            ],
            [
                'name' => 'Graines de Basilic Grand Vert Genovese',
                'description' => 'Graines de basilic Grand Vert Genovese, variété italienne authentique. Sélection fermière pour l\'arôme intense et les grandes feuilles. Basilic traditionnel pour pesto et cuisine méditerranéenne. Germination rapide.',
                'short_description' => 'Graines basilic Genovese, variété italienne authentique',
                'sku' => 'SEMENCES-BASILIC-GENOVESE-006',
                'price' => 3.50,
                'quantity' => 110,
                'unit_symbol' => 'pièce',
                'weight' => '0.005',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 11,
                'low_stock_threshold' => 22,
                'out_of_stock_threshold' => 6,
                'meta_title' => 'Graines Basilic Grand Vert Genovese - Italien',
                'meta_description' => 'Graines de basilic Grand Vert Genovese, variété italienne. Arôme intense, grandes feuilles pour pesto.',
                'meta_keywords' => 'graines, basilic, genovese, italien, pesto, arôme'
            ],
            [
                'name' => 'Graines de Persil Plat Géant d\'Italie',
                'description' => 'Graines de persil plat Géant d\'Italie, variété vigoureuse à grandes feuilles. Production fermière pour cuisine fraîche et conservation. Feuillage abondant, repousse rapide après coupe. Résistant au froid.',
                'short_description' => 'Graines persil plat géant, variété vigoureuse italienne',
                'sku' => 'SEMENCES-PERSIL-GEANT-007',
                'price' => 2.90,
                'quantity' => 130,
                'unit_symbol' => 'pièce',
                'weight' => '0.01',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 13,
                'low_stock_threshold' => 26,
                'out_of_stock_threshold' => 7,
                'meta_title' => 'Graines Persil Plat Géant Italie - Vigoureux',
                'meta_description' => 'Graines de persil plat Géant d\'Italie, variété vigoureuse. Grandes feuilles, repousse rapide.',
                'meta_keywords' => 'graines, persil, plat, géant, italie, vigoureux'
            ],
            [
                'name' => 'Graines de Courgette Ronde de Nice',
                'description' => 'Graines de courgette Ronde de Nice, variété ancienne provençale. Sélection fermière sur fruits uniformes et savoureux. Courgettes rondes parfaites pour farcir. Production étalée tout l\'été.',
                'short_description' => 'Graines courgette ronde Nice, variété provençale ancienne',
                'sku' => 'SEMENCES-COURGETTE-NICE-008',
                'price' => 4.20,
                'quantity' => 75,
                'unit_symbol' => 'pièce',
                'weight' => '0.02',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 8,
                'low_stock_threshold' => 15,
                'out_of_stock_threshold' => 4,
                'meta_title' => 'Graines Courgette Ronde Nice - Provençale',
                'meta_description' => 'Graines de courgette Ronde de Nice, variété ancienne provençale. Parfaites à farcir, production étalée.',
                'meta_keywords' => 'graines, courgette, ronde, nice, provençale, farcir'
            ],
            [
                'name' => 'Graines de Melon Charentais Cantaloup',
                'description' => 'Graines de melon Charentais Cantaloup, variété traditionnelle française. Semences issues de nos cultures sous tunnel. Melons sucrés à chair orange, parfum intense. Semis précoce sous abri nécessaire.',
                'short_description' => 'Graines melon Charentais, tradition française sucrée',
                'sku' => 'SEMENCES-MELON-CHARENTAIS-009',
                'price' => 6.50,
                'quantity' => 60,
                'unit_symbol' => 'pièce',
                'weight' => '0.01',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 6,
                'low_stock_threshold' => 12,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Graines Melon Charentais Cantaloup - Français',
                'meta_description' => 'Graines de melon Charentais Cantaloup, variété française. Chair orange sucrée, parfum intense.',
                'meta_keywords' => 'graines, melon, charentais, cantaloup, français, sucré'
            ],
            [
                'name' => 'Graines de Tournesol Géant de Russie',
                'description' => 'Graines de tournesol Géant de Russie, variété spectaculaire pour decoration et graines. Sélection fermière sur tiges robustes et capitules énormes. Hauteur 3-4 mètres, graines comestibles excellentes. Attraction pour pollinisateurs.',
                'short_description' => 'Graines tournesol géant, spectaculaire décoratif et comestible',
                'sku' => 'SEMENCES-TOURNESOL-GEANT-010',
                'price' => 3.90,
                'quantity' => 85,
                'unit_symbol' => 'pièce',
                'weight' => '0.02',
                'type' => 'sale',
                'category_id' => $semencesCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 9,
                'low_stock_threshold' => 17,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Graines Tournesol Géant Russie - Spectaculaire',
                'meta_description' => 'Graines de tournesol Géant de Russie, variété spectaculaire. Hauteur 3-4m, capitules énormes.',
                'meta_keywords' => 'graines, tournesol, géant, russie, spectaculaire, décoratif'
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($semencesProducts as $productData) {
            // Générer le slug
            $productData['slug'] = Str::slug($productData['name']);
            
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $productData['name'])
                              ->where('category_id', $semencesCategory->id)
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

        $this->command->info("\n🌱 === SEMENCES FERMIÈRES AJOUTÉES ===");
        $this->command->info("📊 Résumé de l'opération:");
        $this->command->info("✅ {$created} nouvelles semences créées");
        $this->command->info("⚠️  {$skipped} produits déjà existants");
        
        if ($created > 0) {
            $this->command->info("\n🌾 Gamme complète de semences fermières:");
            $this->command->info("• Légumes racines: Radis 18 jours, Carotte Colmar");
            $this->command->info("• Légumes feuilles: Laitue Batavia, Persil géant");
            $this->command->info("• Légumes fruits: Tomate Cœur de Bœuf, Courgette Nice, Melon Charentais");
            $this->command->info("• Légumineuses: Haricot vert fin de Bagnols");
            $this->command->info("• Aromatiques: Basilic Genovese");
            $this->command->info("• Décoratif: Tournesol géant de Russie");
            $this->command->info("💰 Prix de 2,80€ à 6,50€ selon variété et rareté");
            $this->command->info("🏷️  100% semences fermières reproductibles");
            $this->command->info("🌱 Sélection de variétés anciennes et traditionnelles");
        }
    }
}
