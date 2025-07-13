<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class VegetableProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie Légumes
        $category = Category::where('name', 'Légumes')->first();
        
        if (!$category) {
            $this->command->error('La catégorie "Légumes" n\'existe pas. Veuillez d\'abord créer cette catégorie.');
            return;
        }

        $vegetables = [
            [
                'name' => 'Pommes de terre Charlotte Bio',
                'description' => 'Variété précoce de pommes de terre biologiques, chair ferme et goût délicat. Parfaites pour cuisson vapeur, salade ou rissolées. Cultivées en Belgique selon les normes bio européennes.',
                'short_description' => 'Pommes de terre bio précoces, chair ferme, idéales cuisson vapeur',
                'price' => 2.90,
                'quantity' => 50,
                'unit_symbol' => 'kg',
                'critical_threshold' => 10,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Carottes Nantaises Bio',
                'description' => 'Carottes biologiques de variété Nantaise, douces et croquantes. Riches en bêta-carotène et cultivées dans le respect de l\'environnement. Parfaites crues ou cuites.',
                'short_description' => 'Carottes bio douces et croquantes, riches en vitamines',
                'price' => 3.20,
                'quantity' => 40,
                'unit_symbol' => 'kg',
                'critical_threshold' => 8,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Poireaux d\'Hiver Bio',
                'description' => 'Poireaux biologiques résistants au froid, cultivés en Belgique. Excellente source de fibres et de vitamines. Idéaux pour soupes, gratins et pot-au-feu.',
                'short_description' => 'Poireaux bio d\'hiver, résistants et savoureux',
                'price' => 4.50,
                'quantity' => 30,
                'unit_symbol' => 'kg',
                'critical_threshold' => 6,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Choux de Bruxelles Bio',
                'description' => 'Authentiques choux de Bruxelles biologiques, spécialité belge. Riches en vitamine C et antioxydants. Parfaits revenus au beurre ou gratinés.',
                'short_description' => 'Choux de Bruxelles bio, spécialité belge authentique',
                'price' => 5.80,
                'quantity' => 25,
                'unit_symbol' => 'kg',
                'critical_threshold' => 5,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Épinards Frais Bio',
                'description' => 'Épinards biologiques frais, résistants au froid belge. Riches en fer et acide folique. Parfaits pour salades jeunes pousses ou cuisson rapide.',
                'short_description' => 'Épinards bio frais, riches en fer et vitamines',
                'price' => 6.90,
                'quantity' => 20,
                'unit_symbol' => 'kg',
                'critical_threshold' => 4,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Radis Roses Bio',
                'description' => 'Radis biologiques à croissance rapide, croquants et légèrement piquants. Cultivés sans pesticides. Parfaits pour apéritifs, salades et crudités.',
                'short_description' => 'Radis bio croquants, croissance rapide et naturelle',
                'price' => 3.50,
                'quantity' => 35,
                'unit_symbol' => 'kg',
                'critical_threshold' => 7,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Betteraves Rouges Bio',
                'description' => 'Betteraves rouges biologiques, excellentes en climat belge. Riches en antioxydants et nitrates naturels. Parfaites cuites, en salade ou jus détox.',
                'short_description' => 'Betteraves bio riches en antioxydants, polyvalentes',
                'price' => 4.20,
                'quantity' => 28,
                'unit_symbol' => 'kg',
                'critical_threshold' => 6,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Courgettes Vertes Bio',
                'description' => 'Courgettes biologiques productives, cultivées durant l\'été belge. Faibles en calories, riches en eau et vitamines. Idéales grillées, farcies ou en ratatouille.',
                'short_description' => 'Courgettes bio d\'été, légères et nutritives',
                'price' => 3.80,
                'quantity' => 32,
                'unit_symbol' => 'kg',
                'critical_threshold' => 8,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Laitue Résistante Bio',
                'description' => 'Laitue biologique résistante aux variations climatiques belges. Feuilles tendres et croquantes. Parfaite pour salades fraîches et sandwichs.',
                'short_description' => 'Laitue bio résistante, feuilles tendres et fraîches',
                'price' => 2.50,
                'quantity' => 45,
                'unit_symbol' => 'pièce',
                'critical_threshold' => 10,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Oignons de Conservation Bio',
                'description' => 'Oignons biologiques de conservation, excellente tenue. Cultivés en Belgique pour une longue conservation naturelle. Base indispensable de la cuisine.',
                'short_description' => 'Oignons bio de conservation, base culinaire essentielle',
                'price' => 3.60,
                'quantity' => 38,
                'unit_symbol' => 'kg',
                'critical_threshold' => 8,
                'category_id' => $category->id,
                'type' => 'sale',
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($vegetables as $vegetableData) {
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $vegetableData['name'])
                              ->where('category_id', $category->id)
                              ->first();
            
            if (!$existing) {
                Product::create($vegetableData);
                $created++;
                $this->command->info("✅ Créé: {$vegetableData['name']}");
            } else {
                $skipped++;
                $this->command->warn("⚠️  Existe déjà: {$vegetableData['name']}");
            }
        }

        $this->command->info("\n📊 Résumé:");
        $this->command->info("✅ {$created} légumes créés");
        $this->command->info("⚠️  {$skipped} légumes déjà existants");
        $this->command->info("💚 Prix compétitifs de 2,50€ à 6,90€");
        $this->command->info("🇧🇪 Tous adaptés au climat et à la culture belge");
        $this->command->info("📦 Unités conformes : kg et pièce uniquement");
    }
}
