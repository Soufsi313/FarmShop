<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST EMAIL CONFIRMATION LOC-202508132922 ===\n\n";

// Trouver la commande
$order = DB::table('order_locations')
    ->where('order_number', 'LOC-202508132922')
    ->first();

if ($order) {
    echo "📦 Commande trouvée: {$order->order_number}\n";
    echo "   Status: {$order->status}\n";
    echo "   Payment status: {$order->payment_status}\n\n";
    
    // Écouter les events
    Event::listen(\App\Events\OrderLocationStatusChanged::class, function($event) {
        echo "🎯 Event OrderLocationStatusChanged déclenché!\n";
        echo "   Commande: {$event->orderLocation->order_number}\n";
        echo "   Ancien statut: {$event->oldStatus}\n";
        echo "   Nouveau statut: {$event->newStatus}\n\n";
    });
    
    echo "🧪 TEST 1: Déclencher manuellement l'event...\n";
    $orderLocation = \App\Models\OrderLocation::find($order->id);
    
    try {
        event(new \App\Events\OrderLocationStatusChanged($orderLocation, 'pending', 'confirmed'));
        echo "✅ Event déclenché manuellement\n\n";
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n\n";
    }
    
    echo "🧪 TEST 2: Vérifier le cache d'email...\n";
    $cacheKey = "email_confirmed_{$order->id}";
    if (cache()->has($cacheKey)) {
        echo "⚠️  Cache email existe - email déjà envoyé récemment\n";
        echo "   Clé: {$cacheKey}\n";
        echo "   Suppression du cache...\n";
        cache()->forget($cacheKey);
        echo "✅ Cache supprimé\n\n";
    } else {
        echo "✅ Pas de cache email - prêt pour envoi\n\n";
    }
    
    echo "🧪 TEST 3: Redéclencher l'event après suppression du cache...\n";
    try {
        event(new \App\Events\OrderLocationStatusChanged($orderLocation, 'pending', 'confirmed'));
        echo "✅ Event redéclenché\n";
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Commande non trouvée\n";
}
