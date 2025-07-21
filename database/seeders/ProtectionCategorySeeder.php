<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProtectionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si la catégorie existe déjà
        $existing = Category::where('name', 'Protections')->first();
        
        if ($existing) {
            $this->command->warn("⚠️  La catégorie 'Protections' existe déjà (ID: {$existing->id})");
            return;
        }

        // Créer la nouvelle catégorie
        $category = Category::create([
            'name' => 'Protections',
            'slug' => Str::slug('Protections'),
            'description' => 'Produits de protection pour les cultures, les plants et les infrastructures fermières. Équipements de protection contre les intempéries, les nuisibles et les maladies.',
            'is_active' => true
        ]);

        $this->command->info("✅ Catégorie 'Protections' créée avec succès (ID: {$category->id})");
        $this->command->info("📝 Description: Protection des cultures et infrastructures fermières");
        $this->command->info("🎯 Destinée aux produits d'achat uniquement");
        $this->command->info("🛡️  Couvre: filets, voiles, pièges, répulsifs, équipements de protection");
    }
}
