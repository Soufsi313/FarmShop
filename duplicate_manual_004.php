<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\OrderItemLocation;
use Carbon\Carbon;

echo "=== Duplication LOC-MANUAL-004 ===\n";

// Prendre LOC-MANUAL-001 comme modèle
$model = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if (!$model) {
    echo "❌ Modèle LOC-MANUAL-001 non trouvé\n";
    exit;
}

echo "📋 Modèle trouvé : " . $model->order_number . "\n";

// Dupliquer la commande
$newOrder = $model->replicate();
$newOrder->order_number = 'LOC-MANUAL-004-' . time();
$newOrder->start_date = Carbon::now()->subDays(10);
$newOrder->end_date = Carbon::now()->subDays(3);
$newOrder->actual_return_date = Carbon::now()->subHours(2);
$newOrder->status = 'finished'; // Prête à être clôturée
$newOrder->created_at = Carbon::now()->subDays(10);
$newOrder->updated_at = Carbon::now()->subHours(2);
$newOrder->stripe_payment_intent_id = 'pi_manual_004_' . time();
$newOrder->invoice_number = 'FAC-LOC-004-' . date('Y');

$newOrder->save();

echo "✅ Nouvelle commande créée : " . $newOrder->order_number . "\n";

// Dupliquer les articles
$modelItems = $model->orderItemLocations;
foreach ($modelItems as $item) {
    $newItem = $item->replicate();
    $newItem->order_location_id = $newOrder->id;
    $newItem->save();
    echo "📦 Article ajouté : " . $newItem->product_name . "\n";
}

echo "\n🎯 Informations de la nouvelle commande :\n";
echo "📍 Numéro : " . $newOrder->order_number . "\n";
echo "📅 Période : du " . $newOrder->start_date->format('d/m/Y') . " au " . $newOrder->end_date->format('d/m/Y') . "\n";
echo "💰 Montant : " . $newOrder->total_amount . "€\n";
echo "📍 Statut : " . $newOrder->status . " (prête à clôturer)\n";
echo "🔗 URL : " . config('app.url') . "/rental-orders\n";

echo "\n✅ Vous pouvez maintenant :\n";
echo "1. Aller sur /rental-orders\n";
echo "2. Voir la commande " . $newOrder->order_number . "\n";
echo "3. Cliquer sur 'Clôturer' pour déclencher l'inspection\n";
echo "4. Recevoir l'email d'inspection détaillé avec le nouveau template\n";
