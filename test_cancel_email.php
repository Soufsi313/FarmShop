<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test de l'annulation et envoi d'email...\n\n";

// Récupérer une commande en attente (pending)
$orderLocation = OrderLocation::where('status', 'pending')
    ->orderBy('id', 'desc')
    ->with(['user', 'items.product'])
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande en attente trouvée\n";
    echo "🔍 Commandes disponibles:\n";
    
    $orders = OrderLocation::orderBy('id', 'desc')->limit(5)->get(['id', 'order_number', 'status']);
    foreach($orders as $order) {
        echo "   - {$order->order_number}: {$order->status}\n";
    }
    exit(1);
}

echo "📋 Commande trouvée: {$orderLocation->order_number}\n";
echo "👤 Utilisateur: {$orderLocation->user->email}\n";
echo "📊 Statut actuel: {$orderLocation->status}\n";
echo "💰 Montant: {$orderLocation->total_amount}€\n\n";

try {
    echo "❌ Annulation de la commande...\n";
    
    // Ajouter un listener temporaire pour voir si l'event est déclenché
    \Event::listen(\App\Events\OrderLocationStatusChanged::class, function($event) {
        echo "🎯 EVENT DÉCLENCHÉ: {$event->orderLocation->order_number} - {$event->oldStatus} → {$event->newStatus}\n";
    });
    
    // Annuler la commande
    $orderLocation->cancel("Test d'annulation automatique");
    
    echo "✅ Commande annulée avec succès !\n";
    echo "📊 Nouveau statut: {$orderLocation->fresh()->status}\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'annulation: {$e->getMessage()}\n";
    echo "📍 Fichier: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n🏁 Test terminé.\n";
