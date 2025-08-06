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

// Trouver l'ordre de test
$order = OrderLocation::where('order_number', 'LIKE', 'LOC-MANUAL-004%')->first();

if (!$order) {
    echo "❌ Ordre LOC-MANUAL-004 non trouvé\n";
    exit(1);
}

echo "📦 Ordre trouvé: {$order->order_number}\n";
echo "📊 Statut actuel: {$order->status}\n";
echo "📧 Email utilisateur: {$order->user->email}\n";
echo "👤 Nom utilisateur: {$order->user->name}\n\n";

// Charger les relations nécessaires
$order->load(['user', 'orderItemLocations.product']);

echo "📝 Préparation de l'email d'inspection...\n";

try {
    // Créer l'email
    $mail = new RentalOrderInspection($order, $order->user);
    
    // Envoyer l'email de test
    echo "📤 Envoi de l'email de test...\n";
    Mail::to($order->user->email)->send($mail);
    
    echo "✅ Email d'inspection envoyé avec succès à {$order->user->email}\n";
    echo "🎨 Nouveau template Tailwind CSS utilisé !\n";
    echo "📬 Vérifiez votre boîte email pour voir le résultat.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi de l'email: " . $e->getMessage() . "\n";
    echo "📋 Stack trace: " . $e->getTraceAsString() . "\n";
}
