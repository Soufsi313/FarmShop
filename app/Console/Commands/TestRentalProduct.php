<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class TestRentalProduct extends Command
{
    protected $signature = 'test:rental-product {slug}';
    protected $description = 'Tester un produit de location spécifique';

    public function handle()
    {
        $slug = $this->argument('slug');
        
        $this->info("=== TEST PRODUIT: {$slug} ===");
        $this->newLine();

        $product = Product::where('slug', $slug)->first();
        
        if (!$product) {
            $this->error("❌ Produit non trouvé avec le slug: {$slug}");
            
            // Chercher des slugs similaires
            $similar = Product::where('slug', 'like', "%{$slug}%")->pluck('slug', 'name');
            if ($similar->count() > 0) {
                $this->info("Slugs similaires trouvés:");
                foreach ($similar as $name => $similarSlug) {
                    $this->line("   - {$name}: {$similarSlug}");
                }
            }
            return;
        }

        $this->info("✅ PRODUIT TROUVÉ:");
        $this->line("   Nom: {$product->name}");
        $this->line("   ID: {$product->id}");
        $this->line("   Slug: {$product->slug}");
        $this->line("   Type: {$product->type}");
        $this->line("   Actif: " . ($product->is_active ? 'Oui' : 'Non'));
        $this->line("   Stock rental: {$product->rental_stock}");
        $this->line("   Prix: {$product->price}€");
        
        $this->newLine();
        $this->info("🔍 TESTS DE RENTABILITÉ:");
        
        try {
            $isRentable = $product->isRentable();
            $this->line("   isRentable(): " . ($isRentable ? 'Oui' : 'Non'));
            
            // Test détaillé
            $typeOk = in_array($product->type, ['rental', 'mixed']);
            $activeOk = $product->is_active;
            $stockOk = ($product->rental_stock ?? 0) > 0;
            
            $this->line("   Type OK (rental/mixed): " . ($typeOk ? 'Oui' : 'Non'));
            $this->line("   Actif OK: " . ($activeOk ? 'Oui' : 'Non'));
            $this->line("   Stock OK (>0): " . ($stockOk ? 'Oui' : 'Non'));
            
            if (!$isRentable) {
                $this->warn("⚠️  Produit NON rentable:");
                if (!$typeOk) $this->line("   - Type incorrect: {$product->type}");
                if (!$activeOk) $this->line("   - Produit inactif");
                if (!$stockOk) $this->line("   - Stock rental insuffisant: {$product->rental_stock}");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors du test isRentable(): " . $e->getMessage());
        }

        $this->newLine();
        $this->info("🌐 TEST D'URL:");
        $this->line("   URL attendue: /rentals/{$product->slug}");
        
        // Tester la route
        try {
            $url = route('rentals.show', $product);
            $this->line("   Route générée: {$url}");
        } catch (\Exception $e) {
            $this->error("   ❌ Erreur route: " . $e->getMessage());
        }
    }
}
