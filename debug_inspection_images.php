<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Diagnostic des images dans l'inspection\n";
echo "==========================================\n\n";

// Récupérer la dernière commande de location avec des produits
$orderLocation = OrderLocation::with(['orderItemLocations.product'])
    ->whereHas('orderItemLocations')
    ->latest()
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande de location trouvée\n";
    exit;
}

echo "📦 Commande: {$orderLocation->order_number}\n";
echo "👤 Client: {$orderLocation->user->name}\n";
echo "📅 Statut: {$orderLocation->status}\n\n";

echo "🔍 Analyse des produits:\n";
echo "------------------------\n";

foreach ($orderLocation->orderItemLocations as $item) {
    echo "🏷️  Produit: {$item->product_name}\n";
    echo "   - Quantité: {$item->quantity}\n";
    
    if ($item->product) {
        echo "   - Produit trouvé dans la DB: ✅\n";
        echo "   - ID produit: {$item->product->id}\n";
        echo "   - Nom produit: {$item->product->name}\n";
        
        if ($item->product->main_image) {
            echo "   - Champ main_image: {$item->product->main_image}\n";
            
            // Vérifier si le fichier existe
            $imagePath = storage_path('app/public/' . $item->product->main_image);
            if (file_exists($imagePath)) {
                echo "   - Fichier image: ✅ (existe)\n";
                echo "   - Chemin: {$imagePath}\n";
            } else {
                echo "   - Fichier image: ❌ (n'existe pas)\n";
                echo "   - Chemin attendu: {$imagePath}\n";
            }
            
            // URL publique
            $publicUrl = asset('storage/' . $item->product->main_image);
            echo "   - URL publique: {$publicUrl}\n";
        } else {
            echo "   - Champ main_image: ❌ (vide ou null)\n";
        }
    } else {
        echo "   - Produit trouvé dans la DB: ❌\n";
        echo "   - product_id dans OrderItemLocation: {$item->product_id}\n";
    }
    echo "\n";
}

// Vérifier les liens symboliques pour storage
echo "🔗 Vérification du stockage:\n";
echo "-----------------------------\n";

$storagePublicPath = public_path('storage');
if (is_link($storagePublicPath)) {
    echo "✅ Lien symbolique public/storage existe\n";
    echo "   -> Pointe vers: " . readlink($storagePublicPath) . "\n";
} else {
    echo "❌ Lien symbolique public/storage n'existe pas\n";
    echo "   Exécuter: php artisan storage:link\n";
}

echo "\n🔍 Test avec un produit spécifique:\n";
echo "------------------------------------\n";

// Chercher un produit avec une image
$productWithImage = Product::whereNotNull('main_image')->first();
if ($productWithImage) {
    echo "✅ Produit avec image trouvé:\n";
    echo "   - Nom: {$productWithImage->name}\n";
    echo "   - Image: {$productWithImage->main_image}\n";
    
    $imagePath = storage_path('app/public/' . $productWithImage->main_image);
    echo "   - Fichier existe: " . (file_exists($imagePath) ? "✅" : "❌") . "\n";
    echo "   - URL: " . asset('storage/' . $productWithImage->main_image) . "\n";
} else {
    echo "❌ Aucun produit avec image trouvé dans la base\n";
}

echo "\n✨ Diagnostic terminé!\n";
?>
