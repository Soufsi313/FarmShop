<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';

// Démarrer l'application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use Carbon\Carbon;

echo "🔍 Diagnostic des commandes en préparation...\n\n";

// Récupérer les commandes en préparation
$orders = Order::where('status', 'preparation')
    ->select('id', 'order_number', 'status', 'confirmed_at', 'preparation_at', 'created_at')
    ->get();

if ($orders->count() > 0) {
    foreach ($orders as $order) {
        echo "📦 Commande #{$order->order_number}\n";
        echo "   Status actuel: {$order->status}\n";
        echo "   Confirmée le: " . ($order->confirmed_at ?? 'NON DÉFINI') . "\n";
        echo "   En préparation le: " . ($order->preparation_at ?? 'NON DÉFINI') . "\n";
        
        if ($order->preparation_at) {
            $preparationAt = new DateTime($order->preparation_at);
            $now = new DateTime();
            $diff = $now->diff($preparationAt);
            
            $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            echo "   ⏰ En préparation depuis: {$minutes} minutes\n";
            
            if ($minutes >= 90) { // 1h30 = 90 minutes
                echo "   ✅ Éligible pour passage en expédiée\n";
            } else {
                $remaining = 90 - $minutes;
                echo "   ⏳ Encore {$remaining} minutes avant passage en expédiée\n";
            }
        } else {
            echo "   ❌ PAS DE DATE DE PRÉPARATION - C'est le problème !\n";
        }
        echo "\n";
    }
} else {
    echo "❌ Aucune commande en préparation trouvée\n";
}

echo "🎯 Diagnostic terminé!\n";
