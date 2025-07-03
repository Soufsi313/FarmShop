<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Order;
use Carbon\Carbon;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test de votre commande FS202507015883 ===\n\n";

$order = Order::where('order_number', 'FS202507015883')->first();

if (!$order) {
    echo "❌ Commande FS202507015883 non trouvée\n";
    exit;
}

echo "📦 Votre commande: {$order->order_number}\n";
echo "   - Statut actuel: {$order->status}\n";
echo "   - Total: {$order->total_amount}€\n";
echo "   - Paiement: {$order->payment_status}\n";
echo "   - Confirmée: " . ($order->confirmed_at ? $order->confirmed_at->format('d/m/Y H:i:s') : 'Non') . "\n";
echo "   - Préparation: " . ($order->preparation_at ? $order->preparation_at->format('d/m/Y H:i:s') : 'Non') . "\n";
echo "   - Expédiée: " . ($order->shipped_at ? $order->shipped_at->format('d/m/Y H:i:s') : 'Non') . "\n";
echo "   - Livrée: " . ($order->delivered_at ? $order->delivered_at->format('d/m/Y H:i:s') : 'Non') . "\n";
echo "   - Créée: " . $order->created_at->format('d/m/Y H:i:s') . "\n";
echo "   - Mise à jour: " . $order->updated_at->format('d/m/Y H:i:s') . "\n";

// Calculer le temps écoulé
$now = Carbon::now();
$timeField = null;
$timeValue = null;

switch ($order->status) {
    case 'confirmed':
        $timeField = 'confirmed_at';
        $timeValue = $order->confirmed_at ?: $order->updated_at;
        break;
    case 'preparation':
        $timeField = 'preparation_at';
        $timeValue = $order->preparation_at ?: $order->updated_at;
        break;
    case 'shipped':
        $timeField = 'shipped_at';
        $timeValue = $order->shipped_at ?: $order->updated_at;
        break;
}

if ($timeValue) {
    $secondsElapsed = $now->diffInSeconds($timeValue);
    $minutesElapsed = round($secondsElapsed / 60, 1);
    echo "\n⏰ Temps écoulé depuis {$timeField}: {$secondsElapsed} secondes ({$minutesElapsed} minutes)\n";
    echo "   - Délai requis: 60 secondes (1 minute)\n";
    echo "   - Prêt pour mise à jour: " . ($secondsElapsed >= 60 ? '✅ OUI' : '❌ NON (encore ' . (60 - $secondsElapsed) . ' secondes)') . "\n";
}

echo "\n🔧 Test de l'automatisation...\n";
echo "Exécution de la commande orders:update-status...\n\n";

// Exécuter l'automatisation
$exitCode = \Artisan::call('orders:update-status');
$output = \Artisan::output();

echo $output;

// Vérifier l'état après automatisation
$order->refresh();
echo "\n📊 État après automatisation:\n";
echo "   - Nouveau statut: {$order->status}\n";
echo "   - Préparation: " . ($order->preparation_at ? $order->preparation_at->format('d/m/Y H:i:s') : 'Non') . "\n";
echo "   - Expédiée: " . ($order->shipped_at ? $order->shipped_at->format('d/m/Y H:i:s') : 'Non') . "\n";
echo "   - Livrée: " . ($order->delivered_at ? $order->delivered_at->format('d/m/Y H:i:s') : 'Non') . "\n";

echo "\n💡 INSTRUCTIONS:\n";
echo "   1. Attendez 1 minute entre chaque exécution\n";
echo "   2. Relancez ce script: php test_my_order.php\n";
echo "   3. Ou exécutez manuellement: php artisan orders:update-status\n";
echo "   4. Vérifiez vos emails pour les notifications\n\n";

echo "🎯 Script terminé !\n";
