<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;

echo "=== VÉRIFICATION DES DERNIÈRES COMMANDES ===\n\n";

// Dernières commandes
$lastOrders = Order::latest()->take(3)->get();

if ($lastOrders->count() > 0) {
    foreach ($lastOrders as $order) {
        echo "Commande ID: {$order->id}\n";
        echo "Statut: {$order->status}\n";
        echo "Email: {$order->user_email}\n";
        echo "Créée le: {$order->created_at->format('d/m/Y H:i:s')}\n";
        echo "Mise à jour: {$order->updated_at->format('d/m/Y H:i:s')}\n";
        echo "Total: {$order->total}€\n";
        
        if ($order->items) {
            echo "Items:\n";
            foreach ($order->items as $item) {
                echo "  - {$item->product_name} (qty: {$item->quantity})\n";
            }
        }
        echo "---\n";
    }
} else {
    echo "Aucune commande trouvée\n";
}

echo "\n=== VÉRIFICATION DU SYSTÈME DE QUEUE ===\n";

// Vérifier les jobs en attente
$jobs = \DB::table('jobs')->count();
echo "Jobs en attente: {$jobs}\n";

$failedJobs = \DB::table('failed_jobs')->count();
echo "Jobs échoués: {$failedJobs}\n";

// Vérifier si un worker est actif
echo "\nStatut des workers: Vérifiez manuellement avec 'php artisan queue:work'\n";
