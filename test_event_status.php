<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test d'event lors du changement de statut...\n\n";

// Récupérer n'importe quelle commande
$orderLocation = OrderLocation::orderBy('id', 'desc')
    ->with(['user', 'items.product'])
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande trouvée\n";
    exit(1);
}

echo "📋 Commande trouvée: {$orderLocation->order_number}\n";
echo "👤 Utilisateur: {$orderLocation->user->email}\n";
echo "📊 Statut actuel: {$orderLocation->status}\n\n";

// Ajouter un listener temporaire pour voir si l'event est déclenché
\Event::listen(\App\Events\OrderLocationStatusChanged::class, function($event) {
    echo "🎯 EVENT DÉCLENCHÉ: {$event->orderLocation->order_number} - {$event->oldStatus} → {$event->newStatus}\n";
});

echo "🔧 Test de changement de statut...\n";

try {
    // Changer le statut temporairement pour voir si l'event fonctionne
    $originalStatus = $orderLocation->status;
    $testStatus = ($originalStatus === 'confirmed') ? 'active' : 'confirmed';
    
    echo "📝 Changement: {$originalStatus} → {$testStatus}\n";
    
    $orderLocation->update(['status' => $testStatus]);
    
    echo "✅ Statut changé !\n";
    
    // Remettre le statut original
    echo "🔄 Remise du statut original...\n";
    $orderLocation->update(['status' => $originalStatus]);
    
    echo "✅ Statut restauré !\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "📍 Fichier: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n🏁 Test terminé.\n";
