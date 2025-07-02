<?php

require_once 'vendor/autoload.php';
use App\Models\OrderLocation;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Commandes de location disponibles ===\n\n";

$orders = OrderLocation::with('user')->orderBy('created_at', 'desc')->limit(10)->get();

foreach($orders as $order) {
    echo "ID: {$order->id}\n";
    echo "Numéro: {$order->order_number}\n"; 
    echo "Statut: {$order->status}\n";
    echo "Client: {$order->user->name}\n";
    echo "Début: {$order->rental_start_date}\n";
    echo "Fin: {$order->rental_end_date}\n";
    echo "Peut être récupéré: " . ($order->can_be_picked_up ? 'OUI' : 'NON') . "\n";
    echo "---\n";
}
