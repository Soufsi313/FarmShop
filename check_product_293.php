<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== VÉRIFICATION PRODUIT ID 294 (Lait de Chèvre) ===\n";

$product = Product::find(294);

if ($product) {
    echo "✅ Produit trouvé:\n";
    echo "ID: {$product->id}\n";
    echo "Nom: {$product->name}\n";
    echo "Statut: " . ($product->is_active ? 'Actif' : 'Inactif') . "\n";
    
    // Vérifier les images
    $galleryImages = $product->gallery_images ?? [];
    echo "Images de galerie: " . count($galleryImages) . "\n";
    foreach ($galleryImages as $index => $image) {
        echo "  [{$index}] {$image}\n";
    }

    $additionalImages = $product->images ?? [];
    echo "Images supplémentaires: " . count($additionalImages) . "\n";
    
    echo "\n=== SUPPRESSION MANUELLE DU PRODUIT ===\n";
    echo "Voulez-vous supprimer ce produit manuellement ? (o/n): ";
    
    // Simulation de suppression
    echo "o\n"; // Auto-réponse pour le script
    
    try {
        // Supprimer les images de galerie
        if ($galleryImages) {
            foreach ($galleryImages as $image) {
                $imagePath = storage_path('app/public/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                    echo "✅ Image supprimée: {$image}\n";
                } else {
                    echo "⚠️  Image non trouvée: {$image}\n";
                }
            }
        }
        
        // Supprimer les images supplémentaires
        if ($additionalImages) {
            foreach ($additionalImages as $image) {
                $imagePath = storage_path('app/public/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                    echo "✅ Image supplémentaire supprimée: {$image}\n";
                } else {
                    echo "⚠️  Image supplémentaire non trouvée: {$image}\n";
                }
            }
        }
        
        // Supprimer l'image principale si elle existe
        if ($product->main_image) {
            $mainImagePath = storage_path('app/public/' . $product->main_image);
            if (file_exists($mainImagePath)) {
                unlink($mainImagePath);
                echo "✅ Image principale supprimée: {$product->main_image}\n";
            }
        }
        
        // Supprimer le produit de la base de données
        $product->delete();
        
        echo "\n🎉 PRODUIT SUPPRIMÉ AVEC SUCCÈS !\n";
        echo "Le produit '{$product->name}' et toutes ses images ont été supprimés.\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de la suppression: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Produit ID 294 non trouvé\n";
}
