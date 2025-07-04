<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';

// Démarrer l'application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Utiliser le modèle Order
use App\Models\Order;

echo "🔍 Analyse des commandes confirmées...\n\n";

// Récupérer les commandes confirmées
$orders = Order::where('status', 'confirmed')
    ->select('id', 'order_number', 'status', 'confirmed_at', 'created_at')
    ->get();

if ($orders->count() > 0) {
    foreach ($orders as $order) {
        echo "📦 Commande #{$order->order_number}\n";
        echo "   Status: {$order->status}\n";
        echo "   Créée le: {$order->created_at}\n";
        echo "   Confirmée le: {$order->confirmed_at}\n";
        
        if ($order->confirmed_at) {
            $confirmedAt = new DateTime($order->confirmed_at);
            $now = new DateTime();
            $diff = $now->diff($confirmedAt);
            
            $totalSeconds = ($diff->days * 24 * 60 * 60) + ($diff->h * 60 * 60) + ($diff->i * 60) + $diff->s;
            $minutes = floor($totalSeconds / 60);
            $seconds = $totalSeconds % 60;
            
            echo "   ⏰ Confirmée depuis: {$minutes} minutes et {$seconds} secondes\n";
            
            if ($totalSeconds >= 45) { // 45 secondes
                echo "   ✅ Éligible pour passage en préparation\n";
            } else {
                $remaining = 45 - $totalSeconds;
                echo "   ⏳ Encore {$remaining} secondes avant passage en préparation\n";
            }
        }
        echo "\n";
    }
} else {
    echo "❌ Aucune commande confirmée trouvée\n";
}

echo "🎯 Analyse terminée!\n";
