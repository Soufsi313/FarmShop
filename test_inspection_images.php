<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🖼️  Test des images dans l'inspection\n";
echo "=====================================\n\n";

// Récupérer une commande pour test
$orderLocation = OrderLocation::with(['orderItemLocations.product'])
    ->whereHas('orderItemLocations')
    ->latest()
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande trouvée\n";
    exit;
}

echo "📦 Commande: {$orderLocation->order_number}\n";
echo "🔗 URL Admin: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation->id}\n\n";

echo "🖼️  Aperçu des images:\n";
echo "----------------------\n";

foreach ($orderLocation->orderItemLocations as $item) {
    echo "• {$item->product_name}:\n";
    
    if ($item->product && $item->product->main_image) {
        $imageUrl = asset('storage/' . $item->product->main_image);
        echo "  ✅ Image: {$imageUrl}\n";
        
        // Vérifier si l'image est accessible
        $imagePath = storage_path('app/public/' . $item->product->main_image);
        if (file_exists($imagePath)) {
            $fileSize = round(filesize($imagePath) / 1024, 1);
            echo "  📄 Taille: {$fileSize} KB\n";
        }
    } else {
        echo "  ❌ Pas d'image\n";
    }
    echo "\n";
}

echo "🎯 Vous pouvez maintenant aller sur l'URL Admin pour voir les images!\n";
?>
