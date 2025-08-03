<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Récupérer les locations qui viennent d'être activées
$orders = App\Models\OrderLocation::whereIn('id', [16, 17])->get();

foreach($orders as $order) {
    echo "Envoi email pour location {$order->order_number} à {$order->user->email}\n";
    
    try {
        \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
            new \App\Mail\RentalStartedMail($order)
        );
        echo "Email envoyé avec succès!\n";
    } catch (\Exception $e) {
        echo "Erreur envoi email: " . $e->getMessage() . "\n";
    }
    
    echo "---\n";
}
