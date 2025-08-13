<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer le compte administrateur
        $this->call(AdminUserSeeder::class);
        
        // Créer les utilisateurs normaux
        $this->call(UserSeeder::class);
        
        // Créer les catégories
        $this->call(CategorySeeder::class);
        
        // Créer les produits personnalisés
        $this->call(CustomProductSeeder::class);
        
        // Créer les catégories de location
        $this->call(RentalCategorySeeder::class);
        
        // Créer les produits de location
        $this->call(RentalProductSeeder::class);
        
        // Créer les contraintes de location
        $this->call(RentalConstraintsSeeder::class);
        
        // Créer les messages (datafixture)
        $this->call(MessageSeeder::class);
        
        // Créer les catégories de blog
        $this->call(BlogCategorySeeder::class);
        
        // Créer les articles de blog
        $this->call(BlogPostSeeder::class);
        
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
