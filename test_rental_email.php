<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use App\Mail\RentalOrderConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Test d'envoi d'email de confirmation de location...\n\n";

// Récupérer la dernière commande confirmée
$orderLocation = OrderLocation::where('status', 'confirmed')
    ->orderBy('id', 'desc')
    ->with(['user', 'items.product'])
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande confirmée trouvée\n";
    exit(1);
}

echo "📋 Commande trouvée: {$orderLocation->order_number}\n";
echo "👤 Utilisateur: {$orderLocation->user->email}\n";
echo "💰 Montant: {$orderLocation->total_amount}€\n";
echo "📅 Période: {$orderLocation->start_date->format('d/m/Y')} - {$orderLocation->end_date->format('d/m/Y')}\n\n";

try {
    echo "📧 Tentative d'envoi d'email...\n";
    
    // Tester l'envoi direct (sans queue)
    $mailable = new RentalOrderConfirmed($orderLocation);
    
    // Vérifier la configuration mail
    echo "🔧 Configuration mail: " . config('mail.default') . "\n";
    echo "📬 Mail host: " . config('mail.mailers.smtp.host') . "\n";
    
    // Envoyer directement (pas de queue)
    Mail::to($orderLocation->user->email)->send($mailable);
    
    echo "✅ Email envoyé avec succès !\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'envoi: {$e->getMessage()}\n";
    echo "📍 Fichier: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n🏁 Test terminé.\n";
