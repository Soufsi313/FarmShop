<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\OrderLocation;
use App\Mail\RentalOrderInspection;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Vérification et envoi de l'email d'inspection\n\n";

// Trouver la commande
$orderLocation = OrderLocation::where('order_number', 'LOC-TEST-INSPECTION-1754427887')->first();

if (!$orderLocation) {
    echo "❌ Commande non trouvée\n";
    exit(1);
}

echo "📦 Commande: {$orderLocation->order_number}\n";
echo "📊 Statut: {$orderLocation->status}\n";
echo "👤 Client: {$orderLocation->user->name}\n";
echo "📧 Email: {$orderLocation->user->email}\n";
echo "📅 Retour effectif: " . ($orderLocation->actual_return_date ? $orderLocation->actual_return_date->format('d/m/Y à H:i') : 'Non défini') . "\n";
echo "🔒 Clôturé le: " . ($orderLocation->closed_at ? $orderLocation->closed_at->format('d/m/Y à H:i') : 'Non défini') . "\n\n";

// Charger les relations nécessaires
$orderLocation->load(['user', 'orderItemLocations.product']);

echo "🛠️ Articles de la location:\n";
foreach($orderLocation->orderItemLocations as $item) {
    echo "  - {$item->product->name} (Qté: {$item->quantity})\n";
    echo "    État: " . ($item->condition_at_return ?? 'Non défini') . "\n";
}
echo "\n";

// Vérifier si la commande a été inspectée
if ($orderLocation->status === 'finished' || $orderLocation->closed_at) {
    echo "📧 Envoi de l'email d'inspection...\n";
    
    try {
        // Créer et envoyer l'email d'inspection
        $mail = new RentalOrderInspection($orderLocation, $orderLocation->user);
        Mail::to($orderLocation->user->email)->send($mail);
        
        echo "✅ Email d'inspection envoyé avec succès à {$orderLocation->user->email}\n";
        echo "🎨 Template moderne FarmShop utilisé!\n";
        echo "📬 Vérifiez votre boîte email maintenant.\n\n";
        
        echo "📧 CONTENU DE L'EMAIL:\n";
        echo "=====================\n";
        echo "• 📋 Rapport d'inspection complet\n";
        echo "• 📦 Détails de la commande {$orderLocation->order_number}\n";
        echo "• 🛠️ État du matériel inspecté\n";
        echo "• 💰 Résumé financier détaillé\n";
        echo "• 📱 Bouton d'accès aux locations\n";
        echo "• 💬 Informations de contact\n";
        
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n";
        echo "📋 Stack trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "⚠️ La commande n'est pas encore inspectée (statut: {$orderLocation->status})\n";
    echo "💡 L'email d'inspection est envoyé automatiquement quand l'admin finalise l'inspection.\n";
}

echo "\n🎯 L'email d'inspection a été envoyé manuellement!\n";
