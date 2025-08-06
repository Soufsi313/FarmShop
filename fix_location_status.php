<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "=== CORRECTION MANUELLE DU STATUT DE LOCATION ===\n\n";

// Trouver votre commande spécifique
$order = OrderLocation::where('order_number', 'LOC-202508034682')->first();

if (!$order) {
    echo "❌ Commande LOC-202508034682 non trouvée\n";
    exit(1);
}

echo "✅ Commande trouvée: {$order->order_number}\n";
echo "📊 Statut actuel: {$order->status}\n";
echo "📅 Date de début: {$order->start_date}\n";
echo "📅 Date de fin: {$order->end_date}\n";
echo "👤 Client: {$order->user->email}\n\n";

// Vérifier si elle devrait être active
$now = now();
$startDate = $order->start_date;
$endDate = $order->end_date;

if ($now->gte($startDate) && $now->lt($endDate)) {
    echo "🔄 La location devrait être ACTIVE maintenant.\n";
    echo "⚡ Correction du statut...\n";
    
    try {
        // Mettre à jour le statut vers active
        $order->update([
            'status' => 'active',
            'started_at' => $startDate
        ]);
        
        echo "✅ Statut mis à jour vers: active\n";
        echo "📧 Tentative d'envoi de l'email de notification...\n";
        
        // Envoyer l'email de démarrage (si disponible)
        try {
            Mail::to($order->user->email)->send(
                new \App\Mail\RentalStartedMail($order)
            );
            echo "✅ Email de notification envoyé avec succès!\n";
        } catch (\Exception $e) {
            echo "⚠️ Email non envoyé (classe Mail peut-être manquante): " . $e->getMessage() . "\n";
            echo "ℹ️ Mais le statut a été corrigé avec succès!\n";
        }
        
        echo "\n=== RÉSUMÉ ===\n";
        echo "✅ Commande {$order->order_number} corrigée\n";
        echo "📊 Nouveau statut: active\n";
        echo "📅 Démarrée le: {$order->started_at}\n";
        echo "⏰ Se terminera automatiquement le: {$endDate}\n";
        
    } catch (\Exception $e) {
        echo "❌ Erreur lors de la correction: " . $e->getMessage() . "\n";
    }
    
} elseif ($now->lt($startDate)) {
    echo "⏳ La location n'a pas encore commencé (début prévu: {$startDate})\n";
} else {
    echo "🔴 La location est terminée (fin: {$endDate})\n";
    echo "🔄 Devrait être au statut 'completed'\n";
    
    try {
        $order->update(['status' => 'completed']);
        echo "✅ Statut corrigé vers: completed\n";
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
}

echo "\n=== FIN DE LA CORRECTION ===\n";
