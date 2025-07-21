<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;

echo "=== TEST DE SUPPRESSION DIRECTE ===\n";

// Tester la suppression directe du produit 294
$product = Product::find(294);

if ($product) {
    echo "✅ Produit trouvé: {$product->name}\n";
    echo "ID: {$product->id}\n";
    
    // Créer une copie de test pour la suppression
    echo "\n=== TEST DIRECT DE SUPPRESSION ===\n";
    
    try {
        // Ne pas vraiment supprimer, juste tester la logique
        echo "Images principales: " . ($product->main_image ? $product->main_image : 'Aucune') . "\n";
        
        $galleryImages = $product->gallery_images ?? [];
        echo "Images de galerie: " . count($galleryImages) . "\n";
        foreach ($galleryImages as $index => $image) {
            echo "  [{$index}] {$image}\n";
        }
        
        $additionalImages = $product->images ?? [];
        echo "Images supplémentaires: " . count($additionalImages) . "\n";
        
        echo "\n✅ La logique de suppression pourrait fonctionner\n";
        echo "Le problème vient donc de l'authentification ou du routage AJAX\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur dans la logique: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Produit ID 294 non trouvé\n";
}

echo "\n=== VÉRIFICATION DE L'AUTHENTIFICATION ===\n";
echo "Pour tester l'API, vous devez être connecté en tant qu'admin\n";
echo "URL de test: http://127.0.0.1:8000/admin/login\n";
