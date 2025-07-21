<?php

// Script de suppression directe du produit 294

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

echo "=== SUPPRESSION DU PRODUIT 294 ===\n\n";

try {
    $product = Product::find(294);
    
    if (!$product) {
        echo "❌ Produit ID 294 non trouvé.\n";
        exit(1);
    }
    
    echo "Produit trouvé: {$product->name}\n";
    echo "ID: {$product->id}\n\n";
    
    // Supprimer l'image principale
    if ($product->main_image) {
        echo "Suppression de l'image principale: {$product->main_image}\n";
        Storage::disk('public')->delete($product->main_image);
    }
    
    // Supprimer les images de galerie
    if ($product->gallery_images && count($product->gallery_images) > 0) {
        echo "Suppression des images de galerie:\n";
        foreach ($product->gallery_images as $index => $image) {
            echo "  - {$image}\n";
            Storage::disk('public')->delete($image);
        }
    }
    
    // Supprimer les images supplémentaires
    if ($product->images && count($product->images) > 0) {
        echo "Suppression des images supplémentaires:\n";
        foreach ($product->images as $index => $image) {
            echo "  - {$image}\n";
            Storage::disk('public')->delete($image);
        }
    }
    
    // Supprimer le produit de la base de données
    echo "\nSuppression du produit de la base de données...\n";
    $product->delete();
    
    echo "\n✅ SUCCÈS ! Le produit '{$product->name}' a été supprimé avec succès.\n";
    echo "Toutes les images associées ont également été supprimées.\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n=== FIN DE LA SUPPRESSION ===\n";
