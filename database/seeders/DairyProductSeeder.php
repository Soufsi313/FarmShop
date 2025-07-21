<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DairyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Produits laitiers"
        $dairyCategory = Category::where('name', 'Produits laitiers')->first();
        
        if (!$dairyCategory) {
            $this->command->error('Catégorie "Produits laitiers" non trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $dairyProducts = [
            [
                'name' => 'Œufs Frais de Poules Élevées au Sol',
                'description' => 'Œufs extra-frais de poules élevées au sol dans nos prairies. Poules nourries aux grains fermiers et parcourant librement nos terrains. Coquille résistante, jaune orangé intense. Collectés quotidiennement, datés du jour de ponte.',
                'short_description' => 'Œufs frais fermiers de poules au sol, collecte quotidienne',
                'sku' => 'DAIRY-OEUFS-SOL-001',
                'price' => 3.50,
                'quantity' => 200,
                'unit_symbol' => 'pièce',
                'weight' => '0.75',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 20,
                'low_stock_threshold' => 40,
                'out_of_stock_threshold' => 10,
                'meta_title' => 'Œufs Frais Fermiers - Poules au Sol',
                'meta_description' => 'Œufs extra-frais de poules élevées au sol. Collecte quotidienne, alimentation naturelle fermière.',
                'meta_keywords' => 'œufs frais, fermier, poules sol, collecte quotidienne'
            ],
            [
                'name' => 'Lait Cru de Vache Entier',
                'description' => 'Lait cru entier non pasteurisé de nos vaches laitières. Collecté matin et soir, refroidi immédiatement. Saveur authentique et naturelle. Vaches nourries à l\'herbe fraîche et au foin de nos prairies. Respecte la saisonnalité.',
                'short_description' => 'Lait cru entier fermier, traite quotidienne',
                'sku' => 'DAIRY-LAIT-CRU-002',
                'price' => 1.20,
                'quantity' => 150,
                'unit_symbol' => 'litre',
                'weight' => '1.03',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 15,
                'low_stock_threshold' => 30,
                'out_of_stock_threshold' => 8,
                'meta_title' => 'Lait Cru de Vache Entier - Ferme Directe',
                'meta_description' => 'Lait cru entier authentique, traite quotidienne. Vaches nourries naturellement aux prairies.',
                'meta_keywords' => 'lait cru, vache, entier, fermier, traite'
            ],
            [
                'name' => 'Lait de Chèvre Frais',
                'description' => 'Lait frais de chèvres alpines élevées dans nos pâturages. Goût délicat et digestible, naturellement riche en vitamines. Chèvres nourries exclusivement à l\'herbe et au fourrage fermier. Traite manuelle traditionnelle.',
                'short_description' => 'Lait de chèvre frais, chèvres alpines pâturage',
                'sku' => 'DAIRY-LAIT-CHEVRE-003',
                'price' => 2.80,
                'quantity' => 80,
                'unit_symbol' => 'litre',
                'weight' => '1.02',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 8,
                'low_stock_threshold' => 16,
                'out_of_stock_threshold' => 4,
                'meta_title' => 'Lait de Chèvre Frais - Traite Traditionnelle',
                'meta_description' => 'Lait frais de chèvres alpines, traite manuelle. Goût délicat, élevage naturel au pâturage.',
                'meta_keywords' => 'lait chèvre, frais, alpine, pâturage, traite manuelle'
            ],
            [
                'name' => 'Crème Fraîche Fermière Épaisse',
                'description' => 'Crème fraîche épaisse élaborée artisanalement avec le lait de nos vaches. Écrémage traditionnel et maturation lente. Texture onctueuse naturelle, goût authentique de crème de ferme. Sans additifs ni conservateurs.',
                'short_description' => 'Crème fraîche fermière artisanale, maturation lente',
                'sku' => 'DAIRY-CREME-FERMIERE-004',
                'price' => 4.20,
                'quantity' => 60,
                'unit_symbol' => 'pièce',
                'weight' => '0.25',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 6,
                'low_stock_threshold' => 12,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Crème Fraîche Fermière - Fabrication Artisanale',
                'meta_description' => 'Crème fraîche épaisse fermière, écrémage traditionnel. Sans additifs, goût authentique.',
                'meta_keywords' => 'crème fraîche, fermière, artisanale, écrémage, traditionnel'
            ],
            [
                'name' => 'Beurre Fermier Baratte à la Main',
                'description' => 'Beurre traditionnel fabriqué à la baratte manuelle avec notre crème fraîche. Barattage lent pour développer tous les arômes. Texture fondante, goût de noisette authentique. Moulé et emballé à la ferme.',
                'short_description' => 'Beurre fermier baratte manuelle, goût noisette',
                'sku' => 'DAIRY-BEURRE-BARATTE-005',
                'price' => 5.50,
                'quantity' => 40,
                'unit_symbol' => 'pièce',
                'weight' => '0.25',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 4,
                'low_stock_threshold' => 8,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Beurre Fermier Baratte Manuelle - Tradition',
                'meta_description' => 'Beurre traditionnel baratte à la main, crème fermière. Texture fondante, arômes développés.',
                'meta_keywords' => 'beurre fermier, baratte manuelle, tradition, noisette'
            ],
            [
                'name' => 'Fromage Blanc Fermier Onctueux',
                'description' => 'Fromage blanc traditionnel élaboré avec le lait de nos vaches. Égouttage naturel en faisselles, texture crémeuse et onctueuse. Goût frais et authentique. Idéal nature ou en dessert avec du miel de la ferme.',
                'short_description' => 'Fromage blanc fermier traditionnel, égouttage naturel',
                'sku' => 'DAIRY-FROMAGE-BLANC-006',
                'price' => 3.80,
                'quantity' => 50,
                'unit_symbol' => 'pièce',
                'weight' => '0.50',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 5,
                'low_stock_threshold' => 10,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Fromage Blanc Fermier - Égouttage Traditionnel',
                'meta_description' => 'Fromage blanc onctueux, fabrication fermière traditionnelle. Égouttage naturel en faisselles.',
                'meta_keywords' => 'fromage blanc, fermier, égouttage, faisselle, traditionnel'
            ],
            [
                'name' => 'Yaourt Fermier au Lait Entier',
                'description' => 'Yaourt artisanal préparé avec le lait entier de nos vaches. Fermentation lente avec des ferments naturels. Texture crémeuse sans additifs. Conditionnement en pots de verre consignés pour une démarche écologique.',
                'short_description' => 'Yaourt fermier artisanal, fermentation lente naturelle',
                'sku' => 'DAIRY-YAOURT-FERMIER-007',
                'price' => 1.50,
                'quantity' => 120,
                'unit_symbol' => 'pièce',
                'weight' => '0.18',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 12,
                'low_stock_threshold' => 24,
                'out_of_stock_threshold' => 6,
                'meta_title' => 'Yaourt Fermier Artisanal - Pot Verre Consigné',
                'meta_description' => 'Yaourt fermier lait entier, fermentation naturelle. Pot verre consigné, démarche écologique.',
                'meta_keywords' => 'yaourt fermier, artisanal, fermentation naturelle, pot verre'
            ],
            [
                'name' => 'Petit-Lait Frais de Fabrication',
                'description' => 'Petit-lait frais obtenu lors de la fabrication de nos fromages fermiers. Riche en protéines et minéraux, rafraîchissant naturel. Idéal pour la cuisine, les smoothies ou à boire nature. Produit traditionnel de la ferme.',
                'short_description' => 'Petit-lait frais de fromagerie, riche en protéines',
                'sku' => 'DAIRY-PETIT-LAIT-008',
                'price' => 0.80,
                'quantity' => 30,
                'unit_symbol' => 'litre',
                'weight' => '1.01',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 3,
                'low_stock_threshold' => 6,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Petit-Lait Frais Fermier - Sous-Produit Fromager',
                'meta_description' => 'Petit-lait frais de fabrication fromagère, riche en protéines. Produit traditionnel fermier.',
                'meta_keywords' => 'petit-lait, frais, fromagerie, protéines, traditionnel'
            ],
            [
                'name' => 'Lait Ribot Fermier Traditionnel',
                'description' => 'Lait ribot traditionnel breton obtenu par barattage du beurre. Goût acidulé rafraîchissant, légèrement pétillant naturellement. Riche en probiotiques naturels. Boisson traditionnelle de nos grand-mères.',
                'short_description' => 'Lait ribot traditionnel breton, barattage du beurre',
                'sku' => 'DAIRY-LAIT-RIBOT-009',
                'price' => 1.80,
                'quantity' => 25,
                'unit_symbol' => 'litre',
                'weight' => '1.02',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 2,
                'low_stock_threshold' => 5,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Lait Ribot Fermier - Tradition Bretonne',
                'meta_description' => 'Lait ribot traditionnel, sous-produit du barattage. Goût acidulé, probiotiques naturels.',
                'meta_keywords' => 'lait ribot, traditionnel, breton, barattage, probiotiques'
            ],
            [
                'name' => 'Œufs de Cane Fermiers',
                'description' => 'Œufs frais de canes élevées en liberté près de notre mare naturelle. Plus gros que les œufs de poule, jaune intense et saveur prononcée. Excellents pour la pâtisserie et la cuisine gastronomique. Collecte hebdomadaire.',
                'short_description' => 'Œufs de cane fermiers, élevage en liberté près mare',
                'sku' => 'DAIRY-OEUFS-CANE-010',
                'price' => 6.00,
                'quantity' => 15,
                'unit_symbol' => 'pièce',
                'weight' => '0.80',
                'type' => 'sale',
                'category_id' => $dairyCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 1,
                'low_stock_threshold' => 3,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Œufs de Cane Fermiers - Élevage Liberté',
                'meta_description' => 'Œufs frais de canes en liberté, saveur intense. Excellents pour pâtisserie gastronomique.',
                'meta_keywords' => 'œufs cane, fermiers, liberté, mare, pâtisserie'
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($dairyProducts as $productData) {
            // Générer le slug
            $productData['slug'] = Str::slug($productData['name']);
            
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $productData['name'])
                              ->where('category_id', $dairyCategory->id)
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

        $this->command->info("\n� === PRODUITS FERMIERS AJOUTÉS ===");
        $this->command->info("📊 Résumé de l'opération:");
        $this->command->info("✅ {$created} nouveaux produits fermiers créés");
        $this->command->info("⚠️  {$skipped} produits déjà existants");
        
        if ($created > 0) {
            $this->command->info("\n🐄 Gamme complète de produits fermiers:");
            $this->command->info("• Œufs frais: Poules au sol, Canes en liberté");
            $this->command->info("• Laits crus: Vache entier, Chèvre alpine");
            $this->command->info("• Crémerie fermière: Crème épaisse, Beurre baratte manuelle");
            $this->command->info("• Spécialités: Fromage blanc, Yaourt fermier, Petit-lait, Lait ribot");
            $this->command->info("💰 Prix de 0,80€ à 6,00€ selon produit et rareté");
            $this->command->info("🏷️  Production fermière traditionnelle 100% locale");
            $this->command->info("🚜 Directement de nos animaux à votre table");
        }
    }
}
