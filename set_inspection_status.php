<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Configuration des statuts d'inspection\n\n";

// Remettre les commandes 44, 45, 46 en mode inspection
$orderIds = [44, 45, 46];

foreach($orderIds as $orderId) {
    $order = App\Models\OrderLocation::find($orderId);
    if ($order) {
        // Mettre en mode inspection si elle ne l'est pas déjà
        if ($order->status !== 'inspecting') {
            $order->update([
                'status' => 'inspecting',  
                'inspection_status' => 'in_progress',
                'actual_return_date' => now(),
                'closed_at' => now(),
                'inspection_started_at' => now()
            ]);
            echo "✅ Commande {$orderId} mise en mode inspection\n";
        } else {
            echo "ℹ️  Commande {$orderId} déjà en mode inspection\n";
        }
        
        echo "   Status: {$order->status}\n";
        echo "   Inspection: {$order->inspection_status}\n";
        echo "   URL: http://127.0.0.1:8000/admin/rental-returns/{$orderId}\n\n";
    } else {
        echo "❌ Commande {$orderId} non trouvée\n\n";
    }
}

echo "🎯 Vous pouvez maintenant tester les formulaires d'inspection !\n";
