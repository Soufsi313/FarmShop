<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class TestCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test category creation system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Test de création d\'une catégorie...');

        try {
            $category = Category::create([
                'name' => 'Légumes Bio',
                'description' => 'Catégorie de légumes biologiques frais',
                'type' => Category::TYPE_BOTH, // Achat et location
                'is_active' => true,
                'sort_order' => 1
            ]);

            $this->info("✅ Catégorie créée avec succès ! ID: {$category->id}");
            $this->info("   - Nom: {$category->name}");
            $this->info("   - Slug: {$category->slug}");
            $this->info("   - Description: {$category->description}");
            $this->info("   - Type: {$category->getTypeLabel()}");
            $this->info("   - Active: " . ($category->is_active ? 'Oui' : 'Non'));
            $this->info("   - Ordre: {$category->sort_order}");
            $this->info("   - URL image: {$category->image_url}");

            // Test des relations
            $productsCount = $category->products()->count();
            $this->info("   - Nombre de produits: {$productsCount}");

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la création de la catégorie: " . $e->getMessage());
            return 1;
        }
    }
}
