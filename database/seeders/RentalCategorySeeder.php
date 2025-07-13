<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalCategory;
use Carbon\Carbon;

class RentalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentalCategories = [
            [
                'name' => 'Outils agricoles',
                'description' => 'Location d\'outils manuels et électriques pour l\'agriculture et le jardinage. Bêches, houes, sécateurs, fourches, tronçonneuses et outillage professionnel disponible à la location.',
                'icon' => '🔨',
                'is_active' => true,
                'display_order' => 1,
                'meta_title' => 'Location d\'outils agricoles professionnels - FarmShop',
                'meta_description' => 'Louez des outils agricoles de qualité professionnelle. Large choix d\'outillage manuel et électrique pour tous vos besoins agricoles et de jardinage.'
            ],
            [
                'name' => 'Machines',
                'description' => 'Location de machines agricoles légères et équipements motorisés. Motoculteurs, débroussailleuses, tondeuses, rotavators, scarificateurs et matériel mécanisé pour optimiser vos travaux.',
                'icon' => '🚜',
                'is_active' => true,
                'display_order' => 2,
                'meta_title' => 'Location de machines agricoles et équipements motorisés - FarmShop',
                'meta_description' => 'Machines agricoles légères en location. Motoculteurs, débroussailleuses et équipements motorisés pour optimiser votre travail agricole sans investissement.'
            ],
            [
                'name' => 'Équipements',
                'description' => 'Équipements et accessoires agricoles. Serres, bâches, contenants, gants, tenues, matériel de stockage et équipements spécialisés.',
                'icon' => '⚙️',
                'is_active' => true,
                'display_order' => 3,
                'meta_title' => 'Location d\'équipements agricoles et accessoires - FarmShop',
                'meta_description' => 'Équipements agricoles professionnels et accessoires en location. Serres, matériel de stockage et équipements spécialisés pour améliorer votre productivité.'
            ]
        ];

        foreach ($rentalCategories as $categoryData) {
            RentalCategory::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'is_active' => $categoryData['is_active'],
                'meta_title' => $categoryData['meta_title'],
                'meta_description' => $categoryData['meta_description'],
                'icon' => $categoryData['icon'],
                'display_order' => $categoryData['display_order'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        echo "✅ 3 catégories de location créées avec succès !\n";
        echo "📊 Catégories créées :\n";
        echo "   🔨 Outils agricoles - Outillage manuel et électrique\n";
        echo "   🚜 Machines - Équipements motorisés et machines légères\n";
        echo "   ⚙️ Équipements - Accessoires et matériel spécialisé\n";
        echo "🎯 Toutes les catégories sont actives avec SEO optimisé pour la location\n";
        echo "📱 Icônes emoji assignées pour une meilleure visualisation\n";
    }
}
