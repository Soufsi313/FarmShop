<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FruitProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer la catégorie "Fruits"
        $fruitCategory = Category::where('name', 'Fruits')->first();
        
        if (!$fruitCategory) {
            $this->command->error('Catégorie "Fruits" non trouvée. Veuillez d\'abord exécuter CategorySeeder.');
            return;
        }

        $fruits = [
            [
                'name' => 'Pommes Vertes Bio',
                'description' => 'Pommes vertes Granny Smith biologiques, croquantes et acidulées. Parfaites pour les tartes et à croquer. Cultivées localement sans pesticides.',
                'short_description' => 'Pommes vertes Granny Smith bio, croquantes et savoureuses',
                'sku' => 'FRUIT-POMME-VERTE-001',
                'price' => 3.20,
                'quantity' => 150,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 10,
                'low_stock_threshold' => 25,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Pommes Vertes Bio - Granny Smith Fraîches',
                'meta_description' => 'Pommes vertes biologiques Granny Smith, croquantes et acidulées. Cultivées localement, parfaites pour vos recettes.',
                'meta_keywords' => 'pommes vertes, bio, granny smith, fruits, agriculture biologique'
            ],
            [
                'name' => 'Pommes Rouges Bio',
                'description' => 'Pommes rouges Red Delicious biologiques, sucrées et juteuses. Idéales pour les enfants et les collations saines. Fraîchement cueillies.',
                'short_description' => 'Pommes rouges Red Delicious bio, sucrées et juteuses',
                'sku' => 'FRUIT-POMME-ROUGE-001',
                'price' => 3.50,
                'quantity' => 180,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 10,
                'low_stock_threshold' => 25,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Pommes Rouges Bio - Red Delicious Sucrées',
                'meta_description' => 'Pommes rouges biologiques Red Delicious, sucrées et juteuses. Parfaites pour les collations et desserts.',
                'meta_keywords' => 'pommes rouges, bio, red delicious, fruits, sucré'
            ],
            [
                'name' => 'Pommes Jaunes Bio',
                'description' => 'Pommes jaunes Golden Delicious biologiques, équilibre parfait entre sucré et acidulé. Texture fondante et arôme délicat. Idéales pour toutes préparations.',
                'short_description' => 'Pommes Golden Delicious bio, équilibre sucré-acidulé parfait',
                'sku' => 'FRUIT-POMME-JAUNE-001',
                'price' => 3.30,
                'quantity' => 160,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 10,
                'low_stock_threshold' => 25,
                'out_of_stock_threshold' => 5,
                'meta_title' => 'Pommes Jaunes Bio - Golden Delicious',
                'meta_description' => 'Pommes jaunes biologiques Golden Delicious, texture fondante et goût équilibré. Parfaites pour tous usages.',
                'meta_keywords' => 'pommes jaunes, bio, golden delicious, fruits, équilibré'
            ],
            [
                'name' => 'Nectarines Bio',
                'description' => 'Nectarines biologiques à chair blanche, sucrées et parfumées. Fruit d\'été par excellence, gorgé de soleil. Peau lisse et chair fondante.',
                'short_description' => 'Nectarines bio à chair blanche, sucrées et parfumées',
                'sku' => 'FRUIT-NECTARINE-001',
                'price' => 4.80,
                'quantity' => 90,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 8,
                'low_stock_threshold' => 20,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Nectarines Bio - Chair Blanche Sucrée',
                'meta_description' => 'Nectarines biologiques à chair blanche, sucrées et parfumées. Fruit d\'été gorgé de soleil.',
                'meta_keywords' => 'nectarines, bio, été, chair blanche, sucré'
            ],
            [
                'name' => 'Prunes Violettes Bio',
                'description' => 'Prunes violettes biologiques variété Reine-Claude, à la chair juteuse et sucrée. Excellentes fraîches ou en confiture. Récolte de saison.',
                'short_description' => 'Prunes violettes Reine-Claude bio, juteuses et sucrées',
                'sku' => 'FRUIT-PRUNE-VIOLETTE-001',
                'price' => 4.20,
                'quantity' => 75,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 5,
                'low_stock_threshold' => 15,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Prunes Violettes Bio - Reine-Claude',
                'meta_description' => 'Prunes violettes biologiques Reine-Claude, juteuses et sucrées. Parfaites fraîches ou en confiture.',
                'meta_keywords' => 'prunes, violettes, bio, reine-claude, juteux'
            ],
            [
                'name' => 'Poires Bio',
                'description' => 'Poires biologiques variété Conférence, chair fondante et sucrée. Forme allongée caractéristique, parfaites pour les desserts et à croquer.',
                'short_description' => 'Poires Conférence bio, chair fondante et sucrée',
                'sku' => 'FRUIT-POIRE-001',
                'price' => 3.80,
                'quantity' => 120,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 8,
                'low_stock_threshold' => 20,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Poires Bio - Conférence Fondantes',
                'meta_description' => 'Poires biologiques Conférence, chair fondante et sucrée. Parfaites pour desserts et collations.',
                'meta_keywords' => 'poires, bio, conférence, fondant, sucré'
            ],
            [
                'name' => 'Pêches Bio',
                'description' => 'Pêches biologiques à chair jaune, gorgées de soleil et de saveur. Duvet caractéristique, chair juteuse et parfumée. Fruit d\'été emblématique.',
                'short_description' => 'Pêches bio à chair jaune, juteuses et parfumées',
                'sku' => 'FRUIT-PECHE-001',
                'price' => 4.50,
                'quantity' => 85,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 6,
                'low_stock_threshold' => 18,
                'out_of_stock_threshold' => 3,
                'meta_title' => 'Pêches Bio - Chair Jaune Juteuse',
                'meta_description' => 'Pêches biologiques à chair jaune, juteuses et parfumées. Gorgées de soleil, parfaites pour l\'été.',
                'meta_keywords' => 'pêches, bio, chair jaune, juteux, été'
            ],
            [
                'name' => 'Bananes Bio',
                'description' => 'Bananes biologiques équitables, naturellement sucrées et riches en potassium. Parfaites pour le petit-déjeuner et les collations sportives.',
                'short_description' => 'Bananes bio équitables, sucrées et riches en potassium',
                'sku' => 'FRUIT-BANANE-001',
                'price' => 2.90,
                'quantity' => 200,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 15,
                'low_stock_threshold' => 35,
                'out_of_stock_threshold' => 8,
                'meta_title' => 'Bananes Bio - Équitables et Naturelles',
                'meta_description' => 'Bananes biologiques équitables, naturellement sucrées et riches en potassium. Idéales pour tous.',
                'meta_keywords' => 'bananes, bio, équitable, potassium, naturel'
            ],
            [
                'name' => 'Raisins Bio',
                'description' => 'Raisins biologiques variété Chasselas, grains dorés et sucrés. Sans pépins, parfaits pour les enfants. Grappes généreuses et savoureuses.',
                'short_description' => 'Raisins Chasselas bio, grains dorés sans pépins',
                'sku' => 'FRUIT-RAISIN-001',
                'price' => 5.20,
                'quantity' => 60,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => false,
                'critical_threshold' => 5,
                'low_stock_threshold' => 12,
                'out_of_stock_threshold' => 2,
                'meta_title' => 'Raisins Bio - Chasselas Sans Pépins',
                'meta_description' => 'Raisins biologiques Chasselas, grains dorés et sucrés sans pépins. Parfaits pour toute la famille.',
                'meta_keywords' => 'raisins, bio, chasselas, sans pépins, doré'
            ],
            [
                'name' => 'Cerises Bio',
                'description' => 'Cerises biologiques variété Bigarreau, rouge vif et croquantes. Chair ferme et sucrée, noyau qui se détache facilement. Fruit de saison premium.',
                'short_description' => 'Cerises Bigarreau bio, rouge vif et croquantes',
                'sku' => 'FRUIT-CERISE-001',
                'price' => 8.90,
                'quantity' => 45,
                'unit_symbol' => 'kg',
                'weight' => '1.00',
                'type' => 'sale',
                'category_id' => $fruitCategory->id,
                'is_active' => true,
                'is_featured' => true,
                'critical_threshold' => 3,
                'low_stock_threshold' => 10,
                'out_of_stock_threshold' => 1,
                'meta_title' => 'Cerises Bio - Bigarreau Premium',
                'meta_description' => 'Cerises biologiques Bigarreau, rouge vif et croquantes. Chair ferme et sucrée, fruit de saison premium.',
                'meta_keywords' => 'cerises, bio, bigarreau, rouge, croquant, premium'
            ],
        ];

        foreach ($fruits as $fruitData) {
            // Générer le slug
            $fruitData['slug'] = Str::slug($fruitData['name']);
            
            // Créer le produit
            Product::create($fruitData);
        }

        $this->command->info('✅ 10 produits fruits biologiques créés avec succès !');
        $this->command->info('🍎 Gamme complète : Pommes (3 variétés), Nectarines, Prunes, Poires, Pêches, Bananes, Raisins, Cerises');
        $this->command->info('💰 Prix compétitifs : de 2,90€ à 8,90€/kg - "La qualité bio avant tout !"');
    }
}
