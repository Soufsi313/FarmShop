<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class FixProductEncoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fix-encoding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix HTML entity encoding issues in product names and descriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Recherche des produits avec problèmes d\'encodage...');
        
        // Rechercher les produits avec des entités HTML
        $products = Product::where('name', 'like', '%&#%')
                           ->orWhere('description', 'like', '%&#%')
                           ->orWhere('short_description', 'like', '%&#%')
                           ->get();

        if ($products->count() > 0) {
            $this->error("❌ Trouvé {$products->count()} produit(s) avec des problèmes d'encodage:");
            
            foreach ($products as $product) {
                $this->line("\n📦 ID: {$product->id}");
                $this->line("   Nom: {$product->name}");
                
                if (strpos($product->description, '&#') !== false) {
                    $this->line("   Description contient des entités HTML");
                }
                
                if (strpos($product->short_description, '&#') !== false) {
                    $this->line("   Description courte contient des entités HTML");
                }
            }
            
            $this->info("\n🔧 Correction automatique des entités HTML...");
            
            $fixed = 0;
            
            foreach ($products as $product) {
                $updated = false;
                $originalName = $product->name;
                
                // Décoder les entités HTML
                if (strpos($product->name, '&#') !== false) {
                    $product->name = html_entity_decode($product->name, ENT_QUOTES, 'UTF-8');
                    $updated = true;
                }
                
                if (strpos($product->description, '&#') !== false) {
                    $product->description = html_entity_decode($product->description, ENT_QUOTES, 'UTF-8');
                    $updated = true;
                }
                
                if (strpos($product->short_description, '&#') !== false) {
                    $product->short_description = html_entity_decode($product->short_description, ENT_QUOTES, 'UTF-8');
                    $updated = true;
                }
                
                if ($updated) {
                    $product->save();
                    $this->info("✅ Corrigé: {$originalName} → {$product->name}");
                    $fixed++;
                }
            }
            
            $this->info("\n🎉 {$fixed} produit(s) corrigé(s) avec succès!");
            
        } else {
            $this->info("✅ Aucun produit avec problème d'encodage trouvé.");
        }

        $this->info("\n🔍 Vérification finale...");
        $remaining = Product::where('name', 'like', '%&#%')
                           ->orWhere('description', 'like', '%&#%')
                           ->orWhere('short_description', 'like', '%&#%')
                           ->count();

        if ($remaining == 0) {
            $this->info("✅ Tous les problèmes d'encodage ont été corrigés!");
        } else {
            $this->error("⚠️  Il reste {$remaining} produit(s) avec des problèmes d'encodage.");
        }
        
        return 0;
    }
}
