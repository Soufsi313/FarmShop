<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;

class CheckRentalStock extends Command
{
    protected $signature = 'check:rental-stock';
    protected $description = 'Vérifier le stock des produits de location';

    public function handle()
    {
        $this->info('=== VÉRIFICATION DES PRODUITS DE LOCATION ===');
        $this->newLine();

        // 1. Compter tous les produits par type
        $this->info('1. RÉPARTITION DES PRODUITS PAR TYPE:');
        $purchaseCount = Product::where('type', 'purchase')->count();
        $rentalCount = Product::where('type', 'rental')->count();
        $mixedCount = Product::where('type', 'mixed')->count();

        $this->line("   - Purchase: $purchaseCount produits");
        $this->line("   - Rental: $rentalCount produits");
        $this->line("   - Mixed: $mixedCount produits");
        $this->line("   - Total: " . ($purchaseCount + $rentalCount + $mixedCount) . " produits");
        $this->newLine();

        // 2. Vérifier les produits de location actifs
        $this->info('2. PRODUITS DE LOCATION ACTIFS:');
        $activeRentals = Product::where('type', 'rental')
            ->where('is_active', true)
            ->get();

        $this->line("   Nombre de produits de location actifs: " . $activeRentals->count());
        $this->newLine();

        if ($activeRentals->count() > 0) {
            $this->info('   DÉTAILS DES PRODUITS DE LOCATION:');
            foreach ($activeRentals as $product) {
                $this->line("   🔧 {$product->name}");
                $this->line("      - ID: {$product->id}");
                $this->line("      - SKU: {$product->sku}");
                $this->line("      - Prix: {$product->price}€/jour");
                $this->line("      - Stock rental: {$product->rental_stock}");
                $this->line("      - Actif: " . ($product->is_active ? 'Oui' : 'Non'));
                $this->line("      - Catégorie: " . ($product->category ? $product->category->name : 'Aucune'));
                $this->line("      - Image: " . ($product->main_image ? 'Oui' : 'Non'));
                $this->newLine();
            }
        }

        // 3. Vérifier les produits mixtes (qui peuvent être loués)
        $this->info('3. PRODUITS MIXTES (ACHAT + LOCATION):');
        $mixedProducts = Product::where('type', 'mixed')
            ->where('is_active', true)
            ->get();

        $this->line("   Nombre de produits mixtes actifs: " . $mixedProducts->count());
        $this->newLine();

        if ($mixedProducts->count() > 0) {
            $this->info('   DÉTAILS DES PRODUITS MIXTES:');
            foreach ($mixedProducts as $product) {
                $this->line("   🔧 {$product->name}");
                $this->line("      - ID: {$product->id}");
                $this->line("      - Stock rental: {$product->rental_stock}");
                $this->line("      - Prix location: {$product->price}€/jour");
                $this->newLine();
            }
        }

        // 4. Total des produits disponibles en location
        $totalRentalAvailable = Product::whereIn('type', ['rental', 'mixed'])
            ->where('is_active', true)
            ->where('rental_stock', '>', 0)
            ->count();

        $this->info("4. PRODUITS DISPONIBLES EN LOCATION (stock > 0): $totalRentalAvailable");
        $this->newLine();

        // 5. Vérifier les catégories pour les locations
        $this->info('5. CATÉGORIES POUR LES LOCATIONS:');
        $rentalCategories = Category::whereHas('products', function($query) {
            $query->whereIn('type', ['rental', 'mixed'])->where('is_active', true);
        })->get();

        foreach ($rentalCategories as $category) {
            $productCount = $category->products()->whereIn('type', ['rental', 'mixed'])->where('is_active', true)->count();
            $this->line("   - {$category->name}: $productCount produits");
        }

        $this->newLine();
        $this->info('=== DIAGNOSTIC TERMINÉ ===');
        
        // Recommandations
        if ($totalRentalAvailable == 0) {
            $this->newLine();
            $this->warn('⚠️  AUCUN PRODUIT DISPONIBLE EN LOCATION !');
            $this->warn('Solutions possibles :');
            $this->warn('1. Ajouter du stock rental aux produits existants');
            $this->warn('2. Créer de nouveaux produits de location');
            $this->warn('3. Vérifier que les produits sont bien actifs');
        }
    }
}
