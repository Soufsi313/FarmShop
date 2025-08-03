<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Démarrage des locations en retard...' . "\n";

// Trouver les locations qui devraient être en cours
$ordersToStart = App\Models\OrderLocation::where('status', 'confirmed')
    ->whereDate('start_date', '<=', now())
    ->get();

echo 'Locations à démarrer: ' . $ordersToStart->count() . "\n";

foreach($ordersToStart as $order) {
    echo 'Démarrage de la location ' . $order->order_number . "\n";
    
    try {
        // Utiliser la méthode updateStatus du modèle qui gère 'active'
        $order->updateStatus('active');
        
        echo 'Statut mis à jour vers: ' . $order->fresh()->status . "\n";
        
        // Envoyer l'email de notification
        \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
            new \App\Mail\RentalStartedMail($order)
        );
        echo 'Email de démarrage envoyé à: ' . $order->user->email . "\n";
        
    } catch (\Exception $e) {
        echo 'Erreur: ' . $e->getMessage() . "\n";
    }
    
    echo '---' . "\n";
}
