<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderConfirmed;
use Illuminate\Support\Facades\Mail;

echo "🔧 Test manuel d'envoi d'email de confirmation\n";
echo "============================================\n\n";

// Récupérer la dernière commande confirmée
$orderLocation = OrderLocation::where('status', 'confirmed')
    ->orWhere('payment_status', 'paid')
    ->orderBy('created_at', 'desc')
    ->first();

if (!$orderLocation) {
    echo "❌ Aucune commande confirmée trouvée\n";
    exit;
}

echo "📦 Commande trouvée: {$orderLocation->order_number}\n";
echo "👤 Client: {$orderLocation->user->email}\n";
echo "📊 Statut: {$orderLocation->status}\n";
echo "💰 Montant: {$orderLocation->total_amount}€\n\n";

echo "📧 Envoi de l'email de confirmation...\n";

try {
    Mail::to($orderLocation->user->email)->send(new RentalOrderConfirmed($orderLocation));
    echo "✅ Email envoyé avec succès !\n";
    echo "📬 Vérifiez votre boîte email: {$orderLocation->user->email}\n\n";
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n\n";
}

echo "✅ Test terminé.\n";

?>
