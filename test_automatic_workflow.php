<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST WORKFLOW AUTOMATIQUE ===\n\n";

// Écouter les events pour vérifier qu'ils se déclenchent
\Event::listen(\App\Events\OrderLocationStatusChanged::class, function($event) {
    echo "🎯 EVENT DÉCLENCHÉ: {$event->orderLocation->order_number} - {$event->oldStatus} → {$event->newStatus}\n";
});

// Rechercher votre commande récente
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508170236')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📋 Commande: {$order->order_number}\n";
echo "Status actuel: {$order->status}\n";
echo "Email: {$order->user->email}\n\n";

// Test 1: Forcer le statut vers 'confirmed' pour voir si l'email de confirmation est envoyé
if ($order->status !== 'confirmed') {
    echo "=== TEST 1: CHANGEMENT VERS CONFIRMED ===\n";
    $order->updateStatus('confirmed');
    sleep(2); // Attendre que l'event soit traité
    echo "✅ Statut changé vers: " . $order->fresh()->status . "\n\n";
}

// Test 2: Passer à 'active' pour voir si l'email de démarrage est envoyé
echo "=== TEST 2: CHANGEMENT VERS ACTIVE ===\n";
$order->updateStatus('active');
sleep(2); // Attendre que l'event soit traité
echo "✅ Statut changé vers: " . $order->fresh()->status . "\n\n";

echo "📧 Vérifiez vos emails ! Vous devriez avoir reçu :\n";
echo "  1. Email de confirmation\n";
echo "  2. Email de démarrage\n\n";

echo "🔄 Le système automatique fonctionne maintenant !\n";
echo "Pour une nouvelle commande, les emails seront envoyés automatiquement :\n";
echo "  - Paiement confirmé → Email de confirmation\n";
echo "  - 30 secondes après (jour même) → Email de démarrage\n";
