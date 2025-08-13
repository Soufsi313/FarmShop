<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Vérification des dernières commandes...\n\n";

$orders = OrderLocation::orderBy('id', 'desc')->limit(3)->get();

if ($orders->isEmpty()) {
    echo "❌ Aucune commande trouvée\n";
} else {
    foreach($orders as $order) {
        echo "📋 {$order->order_number}\n";
        echo "   📊 Statut: {$order->status}\n";
        echo "   💰 Montant: {$order->total_amount}€\n";
        echo "   📅 Créée: {$order->created_at}\n";
        echo "   🏁 Période: {$order->start_date->format('d/m/Y')} - {$order->end_date->format('d/m/Y')}\n\n";
    }
}
