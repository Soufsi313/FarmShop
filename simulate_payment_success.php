<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;

echo "=== SIMULATION CONFIRMATION PAIEMENT ===" . PHP_EOL;

$order = OrderLocation::where('order_number', 'LOC-202508026857')->first();

if ($order) {
    echo "✅ Commande trouvée: " . $order->order_number . PHP_EOL;
    echo "Status actuel: " . $order->status . PHP_EOL;
    echo "Payment status actuel: " . $order->payment_status . PHP_EOL;
    
    // Simuler la confirmation du paiement
    $stripeService = new StripeService();
    
    try {
        // Marquer le paiement comme confirmé
        $order->update([
            'payment_status' => 'confirmed',
            'status' => 'confirmed'
        ]);
        
        echo "✅ Paiement marqué comme confirmé" . PHP_EOL;
        
        // Appeler la méthode de traitement du succès
        $result = $stripeService->processSuccessfulRental($order);
        
        if ($result) {
            echo "✅ Traitement du succès effectué" . PHP_EOL;
        } else {
            echo "❌ Erreur lors du traitement" . PHP_EOL;
        }
        
        // Vérifier le stock après
        $order->refresh();
        foreach ($order->items as $item) {
            if ($item->product) {
                echo "Stock après traitement: " . $item->product->rental_stock . PHP_EOL;
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . PHP_EOL;
    }
    
} else {
    echo "❌ Commande non trouvée" . PHP_EOL;
}
