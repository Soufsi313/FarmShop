<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test manuel du paiement pour LOC-202508026380...\n";

$order = \App\Models\OrderLocation::find(11);

if ($order && $order->stripe_payment_intent_id) {
    echo "📋 Commande : {$order->order_number}\n";
    echo "- Payment Intent: {$order->stripe_payment_intent_id}\n";
    
    try {
        $stripeService = new \App\Services\StripeService();
        $result = $stripeService->handleSuccessfulPayment($order->stripe_payment_intent_id);
        echo "✅ Résultat: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
        
        // Vérifier les changements
        $order->refresh();
        echo "\n📊 État après traitement :\n";
        echo "- Status: {$order->status}\n";
        echo "- Payment status: {$order->payment_status}\n";
        
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Commande ou Payment Intent manquant\n";
}
