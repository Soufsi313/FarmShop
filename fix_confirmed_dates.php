<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';

// Démarrer l'application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use Carbon\Carbon;

echo "🔧 Correction des dates de confirmation manquantes...\n\n";

// Récupérer les commandes confirmées sans date de confirmation
$orders = Order::where('status', 'confirmed')
    ->whereNull('confirmed_at')
    ->get();

if ($orders->count() > 0) {
    foreach ($orders as $order) {
        // Mettre la date de confirmation à "maintenant - 2 heures" pour qu'elles soient éligibles
        $confirmedAt = Carbon::now()->subHours(2);
        
        $order->confirmed_at = $confirmedAt;
        $order->save();
        
        echo "✅ Commande #{$order->order_number}\n";
        echo "   Date de confirmation ajoutée: {$confirmedAt}\n";
        echo "   Elle sera maintenant éligible pour la transition!\n\n";
    }
    
    echo "🎉 {$orders->count()} commande(s) corrigée(s)!\n";
    echo "🚀 Vous pouvez maintenant tester l'automatisation.\n";
} else {
    echo "✅ Toutes les commandes confirmées ont déjà une date de confirmation.\n";
}

echo "\n🎯 Correction terminée!\n";
