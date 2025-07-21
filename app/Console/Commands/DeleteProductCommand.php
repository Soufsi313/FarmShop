<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteProductCommand extends Command
{
    protected $signature = 'product:delete {id : ID du produit à supprimer}';
    protected $description = 'Supprimer un produit et toutes ses images';

    public function handle()
    {
        $productId = $this->argument('id');
        $product = Product::find($productId);

        if (!$product) {
            $this->error("Produit ID {$productId} non trouvé.");
            return 1;
        }

        $this->info("Produit trouvé: {$product->name}");
        
        if (!$this->confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
            $this->info('Suppression annulée.');
            return 0;
        }

        try {
            // Supprimer les images
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
                $this->info("Image principale supprimée: {$product->main_image}");
            }

            if ($product->gallery_images) {
                foreach ($product->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                    $this->info("Image de galerie supprimée: {$image}");
                }
            }

            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                    $this->info("Image supplémentaire supprimée: {$image}");
                }
            }

            // Supprimer le produit
            $product->delete();
            
            $this->info("✅ Produit '{$product->name}' supprimé avec succès !");
            return 0;

        } catch (\Exception $e) {
            $this->error("Erreur lors de la suppression: " . $e->getMessage());
            return 1;
        }
    }
}
