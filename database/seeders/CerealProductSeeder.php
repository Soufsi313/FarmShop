<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CerealProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Céréales"
        $cerealCategory = Category::where('name', 'Céréales')->first();
        
        if (!$cerealCategory) {
            $this->command->error('Catégorie "Céréales" non trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $cereals = [
            [
                'name' => 'Blé Tendre Bio',
                'description' => 'Blé tendre biologique français de haute qualité, variété ancienne. Riche en gluten, idéal pour la panification artisanale et la pâtisserie. Cultivé sans pesticides selon les normes bio européennes.',
                'short_description' => 'Blé tendre bio français, variété ancienne pour boulangerie',
                'sku' => 'CEREAL-BLE-TENDRE-001',
                'price' => 1.85,
                'quantity' => 2500,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 250,
                'low_stock_threshold' => 500,
                'out_of_stock_threshold' => 100,
                'meta_title' => 'Blé Tendre Bio - Variété Ancienne Française',
                'meta_description' => 'Blé tendre biologique de qualité supérieure, parfait pour boulangerie artisanale et pâtisserie.',
                'meta_keywords' => 'blé tendre, bio, boulangerie, variété ancienne, français'
            ],
            [
                'name' => 'Avoine Complète Bio',
                'description' => 'Avoine complète biologique, céréale nutritive riche en fibres bêta-glucanes. Excellente pour flocons d\'avoine, porridge et préparations diététiques. Source naturelle de protéines végétales.',
                'short_description' => 'Avoine complète bio, riche en fibres et protéines',
                'sku' => 'CEREAL-AVOINE-001',
                'price' => 2.20,
                'quantity' => 1800,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 180,
                'low_stock_threshold' => 360,
                'out_of_stock_threshold' => 90,
                'meta_title' => 'Avoine Complète Bio - Riche en Fibres',
                'meta_description' => 'Avoine biologique complète, source naturelle de fibres et protéines. Idéale pour petit-déjeuner sain.',
                'meta_keywords' => 'avoine, bio, fibres, protéines, porridge, flocons'
            ],
            [
                'name' => 'Orge Perlé Bio',
                'description' => 'Orge perlé biologique, céréale ancestrale mondée et polie. Texture crémeuse après cuisson, parfait pour soupes, risottos et salades composées. Faible indice glycémique.',
                'short_description' => 'Orge perlé bio, texture crémeuse pour soupes et risottos',
                'sku' => 'CEREAL-ORGE-PERLE-001',
                'price' => 2.95,
                'quantity' => 1200,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 120,
                'low_stock_threshold' => 240,
                'out_of_stock_threshold' => 60,
                'meta_title' => 'Orge Perlé Bio - Céréale Ancestrale',
                'meta_description' => 'Orge perlé biologique, céréale ancienne à texture crémeuse. Parfait pour soupes et plats mijotés.',
                'meta_keywords' => 'orge perlé, bio, céréale ancestrale, soupe, risotto'
            ],
            [
                'name' => 'Seigle Complet Bio',
                'description' => 'Seigle complet biologique, céréale robuste au goût prononcé et authentique. Traditionnellement utilisé pour le pain de seigle et les préparations nordiques. Riche en fibres et minéraux.',
                'short_description' => 'Seigle complet bio, goût authentique pour pain traditionnel',
                'sku' => 'CEREAL-SEIGLE-001',
                'price' => 2.10,
                'quantity' => 1500,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 150,
                'low_stock_threshold' => 300,
                'out_of_stock_threshold' => 75,
                'meta_title' => 'Seigle Complet Bio - Pain Traditionnel',
                'meta_description' => 'Seigle biologique complet au goût authentique, idéal pour pain traditionnel et préparations nordiques.',
                'meta_keywords' => 'seigle, bio, pain traditionnel, nordique, fibres'
            ],
            [
                'name' => 'Épeautre Décortiqué Bio',
                'description' => 'Épeautre décortiqué biologique, ancêtre du blé moderne. Céréale rustique au goût de noisette, naturellement digeste. Excellente alternative au blé classique pour personnes sensibles.',
                'short_description' => 'Épeautre bio décortiqué, goût noisette et digeste',
                'sku' => 'CEREAL-EPEAUTRE-001',
                'price' => 3.45,
                'quantity' => 900,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 90,
                'low_stock_threshold' => 180,
                'out_of_stock_threshold' => 45,
                'meta_title' => 'Épeautre Décortiqué Bio - Ancêtre du Blé',
                'meta_description' => 'Épeautre biologique décortiqué, céréale ancestrale digeste au goût de noisette. Alternative naturelle au blé.',
                'meta_keywords' => 'épeautre, bio, ancestral, digeste, noisette, alternative blé'
            ],
            [
                'name' => 'Quinoa Blanc Bio',
                'description' => 'Quinoa blanc biologique des Andes, pseudo-céréale sans gluten. Protéine complète avec tous les acides aminés essentiels. Cuisson rapide, texture légère et croquante.',
                'short_description' => 'Quinoa blanc bio des Andes, protéine complète sans gluten',
                'sku' => 'CEREAL-QUINOA-BLANC-001',
                'price' => 8.90,
                'quantity' => 400,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 40,
                'low_stock_threshold' => 80,
                'out_of_stock_threshold' => 20,
                'meta_title' => 'Quinoa Blanc Bio - Protéine Complète des Andes',
                'meta_description' => 'Quinoa blanc biologique, super-aliment andin riche en protéines complètes. Sans gluten, cuisson rapide.',
                'meta_keywords' => 'quinoa, bio, Andes, protéine complète, sans gluten, super-aliment'
            ],
            [
                'name' => 'Sarrasin Décortiqué Bio',
                'description' => 'Sarrasin décortiqué biologique français, pseudo-céréale sans gluten au goût rustique. Riche en magnésium et rutine. Parfait pour galettes bretonnes, kasha et plats végétariens.',
                'short_description' => 'Sarrasin bio français, sans gluten pour galettes bretonnes',
                'sku' => 'CEREAL-SARRASIN-001',
                'price' => 4.20,
                'quantity' => 650,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 65,
                'low_stock_threshold' => 130,
                'out_of_stock_threshold' => 30,
                'meta_title' => 'Sarrasin Décortiqué Bio - Galettes Bretonnes',
                'meta_description' => 'Sarrasin biologique français, parfait pour galettes bretonnes authentiques. Sans gluten, riche en magnésium.',
                'meta_keywords' => 'sarrasin, bio, français, galettes bretonnes, sans gluten, magnésium'
            ],
            [
                'name' => 'Millet Doré Bio',
                'description' => 'Millet doré biologique, petite graine ancienne naturellement sans gluten. Saveur douce et légèrement sucrée, texture moelleuse. Riche en silice, bon pour cheveux, peau et ongles.',
                'short_description' => 'Millet doré bio, graine ancienne douce et nutritive',
                'sku' => 'CEREAL-MILLET-001',
                'price' => 5.60,
                'quantity' => 350,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 35,
                'low_stock_threshold' => 70,
                'out_of_stock_threshold' => 15,
                'meta_title' => 'Millet Doré Bio - Graine Ancienne',
                'meta_description' => 'Millet biologique doré, graine ancienne sans gluten. Saveur douce, riche en silice pour beauté naturelle.',
                'meta_keywords' => 'millet, bio, graine ancienne, sans gluten, silice, beauté'
            ],
            [
                'name' => 'Riz Complet de Camargue Bio',
                'description' => 'Riz complet de Camargue biologique, production française d\'exception. Grain long parfumé, conservation de toutes les propriétés nutritionnelles. IGP Riz de Camargue garanti.',
                'short_description' => 'Riz complet de Camargue bio IGP, production française',
                'sku' => 'CEREAL-RIZ-CAMARGUE-001',
                'price' => 6.80,
                'quantity' => 800,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 80,
                'low_stock_threshold' => 160,
                'out_of_stock_threshold' => 40,
                'meta_title' => 'Riz Complet de Camargue Bio IGP',
                'meta_description' => 'Riz complet biologique de Camargue, production française d\'exception. IGP garanti, grain long parfumé.',
                'meta_keywords' => 'riz complet, Camargue, bio, IGP, français, grain long'
            ],
            [
                'name' => 'Maïs Concassé Bio',
                'description' => 'Maïs concassé biologique, grains dorés grossièrement broyés. Idéal pour polenta italienne, semoule de maïs et accompagnements rustiques. Sans gluten, riche en antioxydants caroténoïdes.',
                'short_description' => 'Maïs concassé bio pour polenta, sans gluten et doré',
                'sku' => 'CEREAL-MAIS-CONCASSE-001',
                'price' => 3.75,
                'quantity' => 1100,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $cerealCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 110,
                'low_stock_threshold' => 220,
                'out_of_stock_threshold' => 55,
                'meta_title' => 'Maïs Concassé Bio - Polenta Italienne',
                'meta_description' => 'Maïs biologique concassé, parfait pour polenta authentique. Sans gluten, riche en caroténoïdes.',
                'meta_keywords' => 'maïs concassé, bio, polenta, sans gluten, caroténoïdes, italien'
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($cereals as $cerealData) {
            // Générer le slug
            $cerealData['slug'] = Str::slug($cerealData['name']);
            
            // Vérifier si le produit existe déjà
            $existing = Product::where('name', $cerealData['name'])
                              ->where('category_id', $cerealCategory->id)
                              ->first();
            
            if (!$existing) {
                Product::create($cerealData);
                $created++;
                $this->command->info("✅ Créé: {$cerealData['name']} - {$cerealData['price']}€/kg");
            } else {
                $skipped++;
                $this->command->warn("⚠️  Existe déjà: {$cerealData['name']}");
            }
        }

        $this->command->info("\n🌾 === CÉRÉALES BIOLOGIQUES AJOUTÉES ===");
        $this->command->info("📊 Résumé de l'opération:");
        $this->command->info("✅ {$created} nouvelles céréales créées");
        $this->command->info("⚠️  {$skipped} céréales déjà existantes");
        
        if ($created > 0) {
            $this->command->info("\n🌾 Gamme complète de céréales bio:");
            $this->command->info("• Céréales classiques: Blé tendre, Avoine, Orge perlé, Seigle");
            $this->command->info("• Céréales anciennes: Épeautre, Sarrasin, Millet");
            $this->command->info("• Super-aliments: Quinoa des Andes, Riz de Camargue IGP");
            $this->command->info("• Spécialités: Maïs concassé pour polenta");
            $this->command->info("💰 Prix de 1,85€ à 8,90€/kg selon qualité et rareté");
            $this->command->info("🏷️  Toutes certifiées biologiques européennes");
            $this->command->info("🍞 Parfaites pour boulangerie, cuisine et alimentation saine");
        }
    }
}
