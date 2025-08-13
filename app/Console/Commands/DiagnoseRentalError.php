<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DiagnoseRentalError extends Command
{
    protected $signature = 'app:diagnose-rental-error';
    protected $description = 'Diagnostiquer l\'erreur 500 sur la page des locations';

    public function handle()
    {
        $this->info('=== DIAGNOSTIC DE L\'ERREUR 500 SUR /RENTALS ===');

        try {
            // Test 1: Vérifier la connexion DB
            $this->info('1. Test de connexion à la base de données...');
            $dbConnection = DB::connection()->getPdo();
            $this->info('✓ Connexion DB réussie');

            // Test 2: Vérifier l'existence de la table products
            $this->info('2. Vérification de la table products...');
            $productsCount = DB::table('products')->count();
            $this->info("✓ Table products trouvée avec {$productsCount} produits");

            // Test 3: Vérifier les produits louables
            $this->info('3. Vérification des produits louables...');
            $rentalProducts = Product::where('is_active', true)
                ->whereIn('type', ['rental', 'mixed'])
                ->count();
            $this->info("✓ {$rentalProducts} produits louables trouvés");

            // Test 4: Vérifier les colonnes nécessaires
            $this->info('4. Vérification des colonnes...');
            $columns = ['rental_stock', 'type', 'is_active', 'price'];
            foreach ($columns as $column) {
                if (DB::getSchemaBuilder()->hasColumn('products', $column)) {
                    $this->info("✓ Colonne '{$column}' présente");
                } else {
                    $this->error("✗ Colonne '{$column}' manquante");
                }
            }

            // Test 5: Tester la requête principale du contrôleur
            $this->info('5. Test de la requête principale du RentalController...');
            $query = Product::with(['category'])
                ->where('is_active', true)
                ->whereIn('type', ['rental', 'mixed'])
                ->where('rental_stock', '>', 0);
            
            $products = $query->get();
            $this->info("✓ Requête réussie, {$products->count()} produits trouvés");

            // Test 6: Vérifier les catégories
            $this->info('6. Vérification des catégories...');
            $categories = Category::whereHas('products', function($query) {
                $query->whereIn('type', ['rental', 'mixed'])->where('is_active', true);
            })->count();
            $this->info("✓ {$categories} catégories avec produits louables trouvées");

            // Test 7: Test des statistiques de prix
            $this->info('7. Test des statistiques de prix...');
            $priceStats = Product::whereIn('type', ['rental', 'mixed'])
                ->where('is_active', true)
                ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                ->first();
            
            if ($priceStats) {
                $this->info("✓ Prix min: {$priceStats->min_price}, Prix max: {$priceStats->max_price}");
            } else {
                $this->error("✗ Impossible de récupérer les statistiques de prix");
            }

            // Test 8: Vérifier la vue
            $this->info('8. Vérification de l\'existence de la vue...');
            $viewPath = resource_path('views/web/rentals/index.blade.php');
            if (file_exists($viewPath)) {
                $this->info('✓ Vue web.rentals.index trouvée');
            } else {
                $this->error('✗ Vue web.rentals.index manquante');
                $this->info('Vues disponibles dans web/rentals :');
                $rentalViewsPath = resource_path('views/web/rentals');
                if (is_dir($rentalViewsPath)) {
                    $files = scandir($rentalViewsPath);
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..') {
                            $this->info("  - {$file}");
                        }
                    }
                }
            }

            // Test 9: Tester la méthode isRentable()
            $this->info('9. Test de la méthode isRentable()...');
            $firstProduct = Product::first();
            if ($firstProduct) {
                $isRentable = $firstProduct->isRentable();
                $this->info("✓ Méthode isRentable() fonctionne (produit ID {$firstProduct->id}: " . ($isRentable ? 'louable' : 'non louable') . ")");
            }

            $this->info('=== DIAGNOSTIC TERMINÉ ===');

        } catch (\Exception $e) {
            $this->error('ERREUR DÉTECTÉE:');
            $this->error("Message: {$e->getMessage()}");
            $this->error("Fichier: {$e->getFile()}:{$e->getLine()}");
            $this->error("Trace:");
            $this->error($e->getTraceAsString());
            
            Log::error('Erreur lors du diagnostic rental', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
