<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer définitivement toutes les catégories existantes (y compris soft-deleted)
        BlogCategory::withTrashed()->forceDelete();
        
        // Récupérer l'utilisateur admin
        $admin = \App\Models\User::where('role', 'Admin')->first();
        
        if (!$admin) {
            $this->command->error('Aucun utilisateur admin trouvé. Veuillez créer un admin d\'abord.');
            return;
        }

        $categories = [
            [
                'name' => 'Le saviez-vous',
                'description' => 'Découvrez des faits surprenants et des anecdotes fascinantes sur l\'agriculture et la nature',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Trucs et astuces',
                'description' => 'Conseils pratiques et techniques pour optimiser vos cultures et votre exploitation',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'Potager et Legumes',
                'description' => 'Tout savoir sur la culture des légumes, du semis à la récolte',
                'color' => '#22C55E',
                'sort_order' => 3,
            ],
            [
                'name' => 'Fruits et Verger',
                'description' => 'Plantation, entretien et récolte des arbres fruitiers',
                'color' => '#F59E0B',
                'sort_order' => 4,
            ],
            [
                'name' => 'Plantes Aromatiques',
                'description' => 'Culture et utilisation des herbes aromatiques et épices',
                'color' => '#8B5CF6',
                'sort_order' => 5,
            ],
            [
                'name' => 'Jardinage Bio',
                'description' => 'Techniques de jardinage biologique et respectueuses de l\'environnement',
                'color' => '#059669',
                'sort_order' => 6,
            ],
            [
                'name' => 'Animaux de Basse-cour',
                'description' => 'Élevage et soin des poules, canards, lapins et autres animaux de ferme',
                'color' => '#DC2626',
                'sort_order' => 7,
            ],
            [
                'name' => 'Apiculture',
                'description' => 'L\'art de l\'élevage des abeilles et la production de miel',
                'color' => '#F59E0B',
                'sort_order' => 8,
            ],
            [
                'name' => 'Elevage Responsable',
                'description' => 'Pratiques d\'élevage éthiques et durables',
                'color' => '#7C2D12',
                'sort_order' => 9,
            ],
            [
                'name' => 'Recettes de Saison',
                'description' => 'Cuisiner avec les produits frais selon les saisons',
                'color' => '#EC4899',
                'sort_order' => 10,
            ],
            [
                'name' => 'Conservation et Transformation',
                'description' => 'Techniques de conservation et de transformation des produits agricoles',
                'color' => '#6366F1',
                'sort_order' => 11,
            ],
            [
                'name' => 'Produits du Terroir',
                'description' => 'Découverte des spécialités locales et produits artisanaux',
                'color' => '#92400E',
                'sort_order' => 12,
            ],
            [
                'name' => 'Agriculture Durable',
                'description' => 'Pratiques agricoles respectueuses de l\'environnement',
                'color' => '#047857',
                'sort_order' => 13,
            ],
            [
                'name' => 'Compostage et Recyclage',
                'description' => 'Gestion écologique des déchets organiques et recyclage',
                'color' => '#65A30D',
                'sort_order' => 14,
            ],
            [
                'name' => 'Biodiversite',
                'description' => 'Protection et préservation de la diversité biologique',
                'color' => '#0891B2',
                'sort_order' => 15,
            ],
            [
                'name' => 'Agenda du Fermier',
                'description' => 'Calendrier des travaux agricoles et saisonniers',
                'color' => '#7C3AED',
                'sort_order' => 16,
            ],
            [
                'name' => 'Materiel et Outils',
                'description' => 'Tests, comparatifs et entretien du matériel agricole',
                'color' => '#374151',
                'sort_order' => 17,
            ],
            [
                'name' => 'Actualites Agricoles',
                'description' => 'Informations sur les réglementations et événements du secteur',
                'color' => '#1F2937',
                'sort_order' => 18,
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'color' => $category['color'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
                'posts_count' => 0,
                'views_count' => 0,
                'created_by' => $admin->id,
            ]);
        }

        $this->command->info('Blog categories created successfully!');
    }
}
