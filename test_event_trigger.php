<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Events\Dispatcher;

// Bootstrap de l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Events\OrderLocationStatusChanged;

echo "🧪 Test déclenchement d'event lors d'un update de statut\n\n";

// Trouver une commande existante
$orderLocation = OrderLocation::where('status', 'confirmed')->first();

if (!$orderLocation) {
    echo "❌ Aucune commande 'confirmed' trouvée\n";
    exit;
}

echo "📋 Commande trouvée: {$orderLocation->id} (statut: {$orderLocation->status})\n";

// Écouter l'événement
\Event::listen(OrderLocationStatusChanged::class, function($event) {
    echo "🎯 EVENT DÉCLENCHÉ! OrderLocationStatusChanged\n";
    echo "   - Commande: {$event->orderLocation->id}\n";
    echo "   - Ancien statut: {$event->oldStatus}\n";
    echo "   - Nouveau statut: {$event->newStatus}\n";
});

// Changer temporairement le statut pour tester
$originalStatus = $orderLocation->status;
echo "\n🔄 Changement statut: $originalStatus → active\n";

$orderLocation->update(['status' => 'active']);

echo "\n🔄 Changement statut: active → $originalStatus\n";
$orderLocation->update(['status' => $originalStatus]);

echo "\n✅ Test terminé\n";
