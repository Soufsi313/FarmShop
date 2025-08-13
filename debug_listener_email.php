<?php

require_once 'vendor/autoload.php';

use App\Events\OrderLocationStatusChanged;
use App\Listeners\HandleOrderLocationStatusChange;
use Illuminate\Support\Facades\Log;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DIAGNOSTIC LISTENER EMAIL ===\n\n";

// Trouver la commande
$orderLocation = \App\Models\OrderLocation::where('order_number', 'LOC-202508132922')->first();

if ($orderLocation) {
    echo "📦 Commande: {$orderLocation->order_number}\n";
    echo "   Email utilisateur: {$orderLocation->user->email}\n\n";
    
    echo "🧪 TEST 1: Vérifier les listeners enregistrés...\n";
    $eventDispatcher = app('events');
    $listeners = $eventDispatcher->getListeners(OrderLocationStatusChanged::class);
    echo "   Listeners trouvés: " . count($listeners) . "\n";
    foreach ($listeners as $listener) {
        echo "   - " . get_class($listener) . "\n";
    }
    echo "\n";
    
    echo "🧪 TEST 2: Appel direct du listener...\n";
    $event = new OrderLocationStatusChanged($orderLocation, 'pending', 'confirmed');
    $listener = new HandleOrderLocationStatusChange();
    
    try {
        echo "   Avant appel listener...\n";
        $listener->handle($event);
        echo "   ✅ Listener exécuté sans erreur\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur dans le listener: " . $e->getMessage() . "\n";
        echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
    echo "\n🧪 TEST 3: Event via dispatcher Laravel...\n";
    try {
        event(new OrderLocationStatusChanged($orderLocation, 'pending', 'confirmed'));
        echo "   ✅ Event dispatché via Laravel\n";
    } catch (\Exception $e) {
        echo "   ❌ Erreur dispatch: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Commande non trouvée\n";
}
