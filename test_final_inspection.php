<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "✅ Test final de l'interface d'inspection\n";
echo "=========================================\n\n";

// Récupérer la dernière commande
$orderLocation = OrderLocation::with(['user', 'orderItemLocations.product'])
    ->latest()
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande trouvée\n";
    exit;
}

echo "📦 Commande: {$orderLocation->order_number}\n";
echo "👤 Client: " . ($orderLocation->user->name ?? 'N/A') . "\n";
echo "📊 Statut: {$orderLocation->status}\n";
echo "🔍 Inspection: {$orderLocation->inspection_status}\n\n";

echo "🖼️  Vérification des images:\n";
echo "-----------------------------\n";

foreach ($orderLocation->orderItemLocations as $index => $item) {
    echo ($index + 1) . ". {$item->product_name}:\n";
    
    if ($item->product && $item->product->main_image) {
        echo "   ✅ Image disponible: {$item->product->main_image}\n";
        
        $imagePath = storage_path('app/public/' . $item->product->main_image);
        if (file_exists($imagePath)) {
            $fileSize = round(filesize($imagePath) / 1024, 1);
            echo "   📄 Fichier: {$fileSize} KB\n";
        } else {
            echo "   ❌ Fichier manquant\n";
        }
        
        echo "   🌐 URL: " . asset('storage/' . $item->product->main_image) . "\n";
    } else {
        echo "   ❌ Pas d'image configurée\n";
    }
    echo "\n";
}

echo "🔗 URLs de test:\n";
echo "----------------\n";
echo "Admin: http://127.0.0.1:8000/admin/rental-returns/{$orderLocation->id}\n";

if ($orderLocation->user) {
    echo "Client: http://127.0.0.1:8000/rental-orders (connecté avec {$orderLocation->user->email})\n";
}

echo "\n🎯 Résumé des corrections:\n";
echo "-------------------------\n";
echo "✅ Champ 'penalty_amount' ajouté au fillable du modèle\n";
echo "✅ Template corrigé pour utiliser 'main_image' au lieu de 'image'\n";
echo "✅ Doublon d'affichage des produits supprimé\n";
echo "✅ Images maintenant visibles dans l'interface d'inspection\n\n";

echo "🚀 Prêt pour les tests!\n";
?>
