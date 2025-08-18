<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST MANUEL ENVOI EMAILS ===\n\n";

// Rechercher votre commande
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508170236')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📋 Commande: {$order->order_number}\n";
echo "Status: {$order->status}\n";
echo "Email: {$order->user->email}\n\n";

// Test 1: Email de confirmation
echo "=== TEST 1: EMAIL DE CONFIRMATION ===\n";
try {
    \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
        new \App\Mail\RentalOrderConfirmed($order)
    );
    echo "✅ Email de confirmation envoyé avec succès\n";
} catch (\Exception $e) {
    echo "❌ Erreur email confirmation: " . $e->getMessage() . "\n";
}

// Test 2: Email de démarrage  
echo "\n=== TEST 2: EMAIL DE DÉMARRAGE ===\n";
try {
    \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
        new \App\Mail\RentalStartedMail($order)
    );
    echo "✅ Email de démarrage envoyé avec succès\n";
} catch (\Exception $e) {
    echo "❌ Erreur email démarrage: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier configuration email
echo "\n=== TEST 3: CONFIGURATION EMAIL ===\n";
echo "Mail driver: " . config('mail.default') . "\n";
echo "SMTP host: " . config('mail.mailers.smtp.host') . "\n";
echo "SMTP port: " . config('mail.mailers.smtp.port') . "\n";
echo "Mail from: " . config('mail.from.address') . "\n";

// Test 4: Event manual
echo "\n=== TEST 4: DÉCLENCHEMENT EVENT MANUEL ===\n";
try {
    // Simuler le changement de statut avec event
    $oldStatus = $order->status;
    event(new \App\Events\OrderLocationStatusChanged($order, $oldStatus, 'confirmed'));
    echo "✅ Event OrderLocationStatusChanged déclenché pour 'confirmed'\n";
    
    event(new \App\Events\OrderLocationStatusChanged($order, 'confirmed', 'active'));
    echo "✅ Event OrderLocationStatusChanged déclenché pour 'active'\n";
} catch (\Exception $e) {
    echo "❌ Erreur event: " . $e->getMessage() . "\n";
}

echo "\n📧 Vérifiez votre boîte email (et dossier spam) !\n";
