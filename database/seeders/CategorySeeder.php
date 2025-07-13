<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Catégories alimentaires
            [
                'name' => 'Fruits',
                'description' => 'Fruits frais de saison, locaux et biologiques. Pommes, poires, fraises, cerises et bien plus encore.',
                'icon' => '🍎',
                'is_food' => true,
                'is_active' => true,
                'display_order' => 1,
                'seo_title' => 'Fruits frais bio et locaux - FarmShop',
                'seo_description' => 'Découvrez notre sélection de fruits frais, biologiques et de saison. Directement du producteur à votre table.'
            ],
            [
                'name' => 'Légumes',
                'description' => 'Légumes frais cultivés localement. Tomates, carottes, salade, courgettes et légumes de saison.',
                'icon' => '🥕',
                'is_food' => true,
                'is_active' => true,
                'display_order' => 2,
                'seo_title' => 'Légumes frais bio et locaux - FarmShop',
                'seo_description' => 'Légumes frais cultivés avec passion par nos producteurs locaux. Qualité et fraîcheur garanties.'
            ],
            [
                'name' => 'Céréales',
                'description' => 'Céréales complètes et biologiques. Blé, avoine, orge, seigle pour une alimentation saine et équilibrée.',
                'icon' => '🌾',
                'is_food' => true,
                'is_active' => true,
                'display_order' => 3,
                'seo_title' => 'Céréales bio et complètes - FarmShop',
                'seo_description' => 'Céréales biologiques et complètes directement des champs. Nutrition et saveur authentique.'
            ],
            [
                'name' => 'Féculents',
                'description' => 'Pommes de terre, légumineuses et tubercules. Base nutritive essentielle pour une alimentation équilibrée.',
                'icon' => '🥔',
                'is_food' => true,
                'is_active' => true,
                'display_order' => 4,
                'seo_title' => 'Féculents et légumineuses bio - FarmShop',
                'seo_description' => 'Pommes de terre, haricots, lentilles et tubercules cultivés naturellement pour votre bien-être.'
            ],

            // Catégories non alimentaires
            [
                'name' => 'Outils agricoles',
                'description' => 'Outils manuels pour l\'agriculture et le jardinage. Bêches, houes, sécateurs, fourches et outillage professionnel.',
                'icon' => '🔨',
                'is_food' => false,
                'is_active' => true,
                'display_order' => 5,
                'seo_title' => 'Outils agricoles professionnels - FarmShop',
                'seo_description' => 'Outillage agricole de qualité pour professionnels et amateurs. Durabilité et performance garanties.'
            ],
            [
                'name' => 'Machines',
                'description' => 'Machines agricoles légères et équipements motorisés. Motoculteurs, débroussailleuses, tondeuses et matériel mécanisé.',
                'icon' => '🚜',
                'is_food' => false,
                'is_active' => true,
                'display_order' => 6,
                'seo_title' => 'Machines agricoles et équipements motorisés - FarmShop',
                'seo_description' => 'Machines agricoles légères et équipements motorisés pour optimiser votre travail agricole.'
            ],
            [
                'name' => 'Équipement',
                'description' => 'Équipements et accessoires agricoles. Serres, bâches, contenants, matériel de stockage et équipements spécialisés.',
                'icon' => '⚙️',
                'is_food' => false,
                'is_active' => true,
                'display_order' => 7,
                'seo_title' => 'Équipements agricoles et accessoires - FarmShop',
                'seo_description' => 'Équipements agricoles professionnels et accessoires pour améliorer votre productivité.'
            ],
            [
                'name' => 'Semences',
                'description' => 'Graines et semences biologiques certifiées. Légumes, fleurs, aromates et semences pour toutes cultures.',
                'icon' => '🌱',
                'is_food' => false,
                'is_active' => true,
                'display_order' => 8,
                'seo_title' => 'Semences biologiques certifiées - FarmShop',
                'seo_description' => 'Semences biologiques de qualité supérieure pour un jardinage naturel et productif.'
            ],
            [
                'name' => 'Engrais',
                'description' => 'Engrais naturels et biologiques. Compost, fumier, engrais verts pour nourrir vos sols naturellement.',
                'icon' => '🌿',
                'is_food' => false,
                'is_active' => true,
                'display_order' => 9,
                'seo_title' => 'Engrais naturels et biologiques - FarmShop',
                'seo_description' => 'Engrais biologiques et amendements naturels pour des sols fertiles et des récoltes abondantes.'
            ],
            [
                'name' => 'Irrigation',
                'description' => 'Systèmes d\'irrigation et matériel d\'arrosage. Tuyaux, asperseurs, goutte-à-goutte et solutions d\'arrosage.',
                'icon' => '💧',
                'is_food' => false,
                'is_active' => true,
                'display_order' => 10,
                'seo_title' => 'Systèmes d\'irrigation et arrosage - FarmShop',
                'seo_description' => 'Solutions d\'irrigation efficaces et économiques pour optimiser l\'arrosage de vos cultures.'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'is_active' => $categoryData['is_active'],
                'food_type' => $categoryData['is_food'] ? 'alimentaire' : 'non_alimentaire',
                'is_returnable' => false,
                'meta_title' => $categoryData['seo_title'],
                'meta_description' => $categoryData['seo_description'],
                'icon' => $categoryData['icon'],
                'display_order' => $categoryData['display_order'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        echo "✅ 10 catégories créées avec succès !\n";
        echo "📊 Répartition :\n";
        echo "   🍎 4 catégories alimentaires : Fruits, Légumes, Céréales, Féculents\n";
        echo "   🔧 6 catégories non alimentaires : Outils agricoles, Machines, Équipement, Semences, Engrais, Irrigation\n";
        echo "🎯 Toutes les catégories sont actives avec SEO optimisé\n";
        echo "📱 Icônes emoji assignées pour une meilleure visualisation\n";
    }
}
