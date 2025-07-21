<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;

class CheckDairyProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:check-dairy {--delete-old : Supprimer les anciens produits non fermiers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier et nettoyer les produits laitiers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer la catégorie "Produits laitiers"
        $dairyCategory = Category::where('name', 'Produits laitiers')->first();

        if (!$dairyCategory) {
            $this->error('Catégorie "Produits laitiers" non trouvée.');
            return 1;
        }

        $this->info("Catégorie trouvée: {$dairyCategory->name} (ID: {$dairyCategory->id})");
        
        $products = Product::where('category_id', $dairyCategory->id)->get(['id', 'name', 'price', 'sku']);
        
        $this->info("\nProduits dans la catégorie 'Produits laitiers' ({$products->count()} produits):");
        $this->line(str_repeat("-", 80));
        
        foreach ($products as $product) {
            $this->line("• {$product->name} - {$product->price}€ (SKU: {$product->sku})");
        }
        
        // Identifier les anciens vs nouveaux produits
        $oldProducts = $products->filter(function($p) {
            return !str_starts_with($p->sku, 'DAIRY-');
        });
        
        $newProducts = $products->filter(function($p) {
            return str_starts_with($p->sku, 'DAIRY-');
        });
        
        $this->line("\n" . str_repeat("=", 80));
        $this->info("RÉSUMÉ:");
        $this->line("• Anciens produits (non fermiers): {$oldProducts->count()}");
        $this->line("• Nouveaux produits fermiers: {$newProducts->count()}");
        $this->line("• Total: {$products->count()}");
        
        if ($oldProducts->count() > 0) {
            $this->warn("\nAnciens produits (probablement de grande surface):");
            foreach ($oldProducts as $old) {
                $this->line("- {$old->name} (SKU: {$old->sku})");
            }
            
            if ($this->option('delete-old')) {
                if ($this->confirm('Voulez-vous supprimer ces anciens produits non fermiers ?')) {
                    $deleted = 0;
                    foreach ($oldProducts as $old) {
                        $old->delete();
                        $this->info("✅ Supprimé: {$old->name}");
                        $deleted++;
                    }
                    $this->info("\n🗑️  {$deleted} ancien(s) produit(s) supprimé(s)");
                }
            } else {
                $this->line("\n💡 Utilisez --delete-old pour supprimer les anciens produits");
            }
        }
        
        return 0;
    }
}
