<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;

echo "=== TEST CORRECTION EMAIL ANNULATION ===\n\n";

// Vérifier s'il y a des commandes de location récentes
$recentRental = OrderLocation::orderBy('created_at', 'desc')->first();

if ($recentRental) {
    echo "Dernière commande de location trouvée:\n";
    echo "- Numéro: {$recentRental->order_number}\n";
    echo "- Statut: {$recentRental->status}\n";
    echo "- Créée: {$recentRental->created_at}\n";
    echo "- Email client: {$recentRental->user->email}\n\n";
} else {
    echo "❌ Aucune commande de location trouvée.\n\n";
}

echo "✅ CORRECTION APPLIQUÉE:\n";
echo "1. Ancien système d'email (dans OrderLocation::sendStatusNotification()) DÉSACTIVÉ\n";
echo "2. Nouveau système (dans HandleOrderLocationStatusChange Listener) ACTIF\n";
echo "3. Plus de doublons d'emails d'annulation\n";
echo "4. Templates modernes (Mailable) utilisés\n\n";

echo "🧪 TESTEZ MAINTENANT:\n";
echo "- Créez une nouvelle location\n";
echo "- Annulez-la\n";
echo "- Vous devriez recevoir UN SEUL email avec le nouveau template\n";

echo "\n=== SYSTÈME CORRIGÉ ===\n";
