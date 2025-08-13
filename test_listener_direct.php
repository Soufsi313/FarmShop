<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use App\Events\OrderLocationStatusChanged;
use App\Listeners\HandleOrderLocationStatusChange;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test direct du listener...\n\n";

// Récupérer une commande
$orderLocation = OrderLocation::first();

if (!$orderLocation) {
    echo "❌ Aucune commande trouvée\n";
    exit(1);
}

echo "📋 Commande: {$orderLocation->order_number}\n";
echo "📊 Statut: {$orderLocation->status}\n\n";

try {
    echo "🔧 Création de l'event manuellement...\n";
    
    // Créer l'event manuellement
    $event = new OrderLocationStatusChanged($orderLocation, 'pending', 'cancelled');
    
    echo "📋 Event créé: {$event->oldStatus} → {$event->newStatus}\n";
    
    // Appeler le listener directement
    echo "🎯 Appel direct du listener...\n";
    $listener = new HandleOrderLocationStatusChange();
    
    $listener->handle($event);
    
    echo "✅ Listener exécuté avec succès !\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "📍 Fichier: {$e->getFile()}:{$e->getLine()}\n";
    echo "📜 Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🏁 Test terminé.\n";
