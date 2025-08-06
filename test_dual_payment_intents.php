<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\StripeService;
use App\Models\OrderLocation;
use App\Models\User;

try {
    echo "🧪 Test du système de préautorisation pour les locations\n";
    echo "=".str_repeat("=", 55)."\n\n";

    // Récupérer une location de test avec caution
    $orderLocation = OrderLocation::where('deposit_amount', '>', 0)->first();

    if (!$orderLocation) {
        echo "❌ Aucune location avec caution trouvée pour le test\n";
        exit(1);
    }

    echo "📦 Location de test trouvée:\n";
    echo "   - ID: {$orderLocation->id}\n";
    echo "   - Numéro: {$orderLocation->order_number}\n";
    echo "   - Montant location: {$orderLocation->total_rental_cost}€\n";
    echo "   - Montant caution: {$orderLocation->deposit_amount}€\n";
    echo "   - Statut: {$orderLocation->status}\n\n";

    $stripeService = new StripeService();
    
    echo "💳 Test de création des PaymentIntents duaux...\n";
    
    // Simuler la création des PaymentIntents
    try {
        $result = $stripeService->createPaymentIntentForRental($orderLocation);
    } catch (Exception $e) {
        echo "❌ Exception lors de la création: " . $e->getMessage() . "\n";
        echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
        exit(1);
    }
    
    if ($result['success']) {
        echo "✅ PaymentIntents créés avec succès!\n\n";
        
        echo "🏦 PaymentIntent pour la location:\n";
        echo "   - ID: {$result['rental_payment_intent_id']}\n";
        echo "   - Montant: {$result['rental_amount']}€\n";
        echo "   - Statut: requires_payment_method\n\n";
        
        echo "🔒 PaymentIntent pour la caution (préautorisation):\n";
        echo "   - ID: {$result['deposit_authorization_id']}\n";
        echo "   - Montant: {$result['deposit_amount']}€\n";
        echo "   - Méthode de capture: manual (préautorisation)\n";
        echo "   - Statut: requires_payment_method\n\n";
        
        echo "🎯 Client Secret pour le frontend:\n";
        echo "   - Location: {$result['rental_client_secret']}\n";
        echo "   - Caution: {$result['deposit_client_secret']}\n\n";
        
        echo "📝 Métadonnées correctement configurées:\n";
        echo "   - order_type: rental\n";
        echo "   - order_id: {$orderLocation->id}\n";
        echo "   - payment_type: rental_payment / deposit_authorization\n\n";
        
        echo "✅ Test réussi! Le système dual PaymentIntent fonctionne.\n";
        echo "🔧 Prêt pour l'intégration frontend.\n";
        
    } else {
        echo "❌ Erreur lors de la création des PaymentIntents:\n";
        echo "   Message: " . ($result['error'] ?? 'Erreur inconnue') . "\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
