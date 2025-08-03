<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Mise à jour manuelle des statuts de location...' . "\n";

// Trouver les locations qui devraient être en cours
$ordersToStart = App\Models\OrderLocation::where('status', 'confirmed')
    ->whereDate('start_date', '<=', now())
    ->get();

echo 'Locations à démarrer: ' . $ordersToStart->count() . "\n";

foreach($ordersToStart as $order) {
    echo 'Démarrage de la location ' . $order->order_number . "\n";
    
    // Mettre à jour le statut
    $order->update([
        'status' => 'in_progress',
        'started_at' => now()
    ]);
    
    echo 'Statut mis à jour: ' . $order->status . "\n";
    
    // Envoyer l'email de notification
    try {
        \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
            new \App\Mail\RentalStartedMail($order)
        );
        echo 'Email de démarrage envoyé à: ' . $order->user->email . "\n";
    } catch (\Exception $e) {
        echo 'Erreur envoi email: ' . $e->getMessage() . "\n";
    }
    
    echo '---' . "\n";
}
