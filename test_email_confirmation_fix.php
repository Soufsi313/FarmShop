<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Cache;

echo "=== TEST EMAIL CONFIRMATION CORRECTED ===\n\n";

// Nettoyer le cache pour tester
Cache::flush();
echo "✅ Cache vidé pour permettre un nouveau test\n\n";

$recentRental = OrderLocation::orderBy('created_at', 'desc')->first();

if ($recentRental) {
    echo "Dernière commande de location:\n";
    echo "- Numéro: {$recentRental->order_number}\n";
    echo "- Statut: {$recentRental->status}\n";
    echo "- Email: {$recentRental->user->email}\n";
    echo "- Confirmée à: " . ($recentRental->confirmed_at ?? 'Non confirmée') . "\n\n";
}

echo "✅ CORRECTIONS APPLIQUÉES:\n";
echo "1. Suppression de la vérification 'confirmed_at' qui bloquait l'envoi\n";
echo "2. Ajout d'une protection par cache contre les doublons (5 min)\n";
echo "3. Système d'email entièrement revu\n\n";

echo "🧪 TESTEZ MAINTENANT:\n";
echo "1. Créez une nouvelle location\n";
echo "2. Payez avec Stripe\n";
echo "3. L'email de confirmation devrait être envoyé automatiquement\n";
echo "4. Vérifiez les logs avec: Get-Content storage/logs/laravel.log -Tail 10\n";

echo "\n=== SYSTÈME PRÊT ===\n";
