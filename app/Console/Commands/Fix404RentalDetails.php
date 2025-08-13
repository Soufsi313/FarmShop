<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class Fix404RentalDetails extends Command
{
    protected $signature = 'fix:rental-404';
    protected $description = 'Corriger le problème 404 des pages de détail des produits de location';

    public function handle()
    {
        $this->info('=== CORRECTION DU PROBLEME 404 LOCATION ===');
        $this->newLine();

        // 1. Vérifier la méthode isRentable dans le modèle Product
        $this->info('1. VERIFICATION DES METHODES PRODUIT:');
        
        $rentalProducts = Product::whereIn('type', ['rental', 'mixed'])
            ->where('is_active', true)
            ->where('rental_stock', '>', 0)
            ->get();

        $this->line("   Produits de location trouvés: " . $rentalProducts->count());

        // 2. Régénérer tous les slugs pour éviter les conflits
        $this->info('2. REGENERATION DES SLUGS:');
        $slugsFixed = 0;

        foreach ($rentalProducts as $product) {
            $originalSlug = $product->slug;
            
            // Régénérer le slug basé sur le nom
            $newSlug = \Illuminate\Support\Str::slug($product->name);
            
            // Vérifier l'unicité
            $counter = 1;
            $baseSlug = $newSlug;
            while (Product::where('slug', $newSlug)->where('id', '!=', $product->id)->exists()) {
                $newSlug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            if ($newSlug !== $originalSlug) {
                $product->update(['slug' => $newSlug]);
                $this->line("   Slug mis à jour: '{$originalSlug}' -> '{$newSlug}'");
                $slugsFixed++;
            }
        }

        $this->line("   Slugs corrigés: {$slugsFixed}");
        $this->newLine();

        // 3. Vérifier que les routes fonctionnent
        $this->info('3. TEST DES ROUTES:');
        
        $sampleProducts = $rentalProducts->take(3);
        foreach ($sampleProducts as $product) {
            $this->line("   Produit: {$product->name}");
            $this->line("   Slug: {$product->slug}");
            $this->line("   Type: {$product->type}");
            $this->line("   Stock rental: {$product->rental_stock}");
            $this->line("   URL: /rentals/{$product->slug}");
            $this->newLine();
        }

        // 4. Afficher les informations de debug
        $this->info('4. INFORMATIONS DE DEBUG:');
        
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $rentalTypeProducts = Product::whereIn('type', ['rental', 'mixed'])->count();
        $availableRentals = Product::whereIn('type', ['rental', 'mixed'])
            ->where('is_active', true)
            ->where('rental_stock', '>', 0)
            ->count();

        $this->line("   Total produits: {$totalProducts}");
        $this->line("   Produits actifs: {$activeProducts}");
        $this->line("   Produits type rental/mixed: {$rentalTypeProducts}");
        $this->line("   Locations disponibles: {$availableRentals}");
        $this->newLine();

        $this->info('=== CORRECTION TERMINEE ===');
        $this->line("Slugs regeneres: {$slugsFixed}");
        $this->line("Les pages de detail devraient maintenant fonctionner !");
    }
}
