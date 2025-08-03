<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Force le 03/08 à 01:00 UTC...' . "\n";

// Forcer le 03/08 à 01:00 UTC (= 03:00 heure française)
$testTime = \Carbon\Carbon::create(2025, 8, 3, 1, 0, 0, 'UTC');
\Carbon\Carbon::setTestNow($testTime);

echo 'Now() forcé: ' . now()->format('Y-m-d H:i:s T') . "\n";

// Maintenant tester la requête
$ordersToStart = App\Models\OrderLocation::where('status', 'confirmed')
    ->whereDate('start_date', '<=', now())
    ->get();

echo 'Locations à démarrer: ' . $ordersToStart->count() . "\n";

foreach($ordersToStart as $order) {
    echo 'Démarrage de la location ' . $order->order_number . "\n";
    
    // Mettre à jour le statut
    $order->update([
        'status' => 'active',
        'started_at' => now()
    ]);
    
    echo 'Statut mis à jour: ' . $order->status . "\n";
    
    // Vérifier si l'email template existe
    if (class_exists(\App\Mail\RentalStartedMail::class)) {
        try {
            \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
                new \App\Mail\RentalStartedMail($order)
            );
            echo 'Email de démarrage envoyé à: ' . $order->user->email . "\n";
        } catch (\Exception $e) {
            echo 'Erreur envoi email: ' . $e->getMessage() . "\n";
        }
    } else {
        echo 'Classe RentalStartedMail introuvable' . "\n";
    }
    
    echo '---' . "\n";
}

// Remettre le temps normal
\Carbon\Carbon::setTestNow();
