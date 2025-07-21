<?php

use App\Models\Product;

// Récupérer un produit de location avec des images de galerie
$product = Product::whereNotNull('rental_price_per_day')
                  ->whereNotNull('gallery_images')
                  ->first();

if ($product) {
    echo "Produit trouvé: {$product->name}\n";
    echo "ID: {$product->id}\n";
    echo "Prix de location: {$product->rental_price_per_day}€/jour\n";
    echo "Image principale: " . ($product->main_image ? "Oui ({$product->main_image})" : "Non") . "\n";
    echo "Images de galerie: " . (is_array($product->gallery_images) ? count($product->gallery_images) . " images" : "Aucune") . "\n";
    
    if (is_array($product->gallery_images) && count($product->gallery_images) > 0) {
        echo "Liste des images de galerie:\n";
        foreach ($product->gallery_images as $index => $image) {
            echo "  - Image " . ($index + 1) . ": {$image}\n";
        }
    }
    
    echo "\nURL pour tester: " . route('rentals.show', $product) . "\n";
} else {
    echo "Aucun produit de location avec images de galerie trouvé.\n";
}
