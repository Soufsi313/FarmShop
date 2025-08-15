<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateExistingDataToTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-existing-data-to-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing products and categories data to translation format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration to translation format...');

        // Backup existing data and convert to French (default language)
        $this->migrateProducts();
        $this->migrateCategories();

        $this->info('Data migration completed successfully!');
        return 0;
    }

    private function migrateProducts()
    {
        $this->info('Migrating products...');
        
        $products = DB::table('products')->get();
        
        foreach ($products as $product) {
            $translationData = [
                'name' => ['fr' => $product->name],
                'description' => ['fr' => $product->description],
                'short_description' => ['fr' => $product->short_description],
                'meta_title' => ['fr' => $product->meta_title],
                'meta_description' => ['fr' => $product->meta_description],
                'meta_keywords' => ['fr' => $product->meta_keywords],
            ];

            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'name' => json_encode($translationData['name']),
                    'description' => json_encode($translationData['description']),
                    'short_description' => json_encode($translationData['short_description']),
                    'meta_title' => json_encode($translationData['meta_title']),
                    'meta_description' => json_encode($translationData['meta_description']),
                    'meta_keywords' => json_encode($translationData['meta_keywords']),
                ]);
        }

        $this->info('Products migrated: ' . $products->count());
    }

    private function migrateCategories()
    {
        $this->info('Migrating categories...');
        
        $categories = DB::table('categories')->get();
        
        foreach ($categories as $category) {
            $translationData = [
                'name' => ['fr' => $category->name],
                'description' => ['fr' => $category->description],
                'meta_title' => ['fr' => $category->meta_title],
                'meta_description' => ['fr' => $category->meta_description],
            ];

            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'name' => json_encode($translationData['name']),
                    'description' => json_encode($translationData['description']),
                    'meta_title' => json_encode($translationData['meta_title']),
                    'meta_description' => json_encode($translationData['meta_description']),
                ]);
        }

        $this->info('Categories migrated: ' . $categories->count());
    }
}
