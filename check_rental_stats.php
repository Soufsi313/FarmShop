<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Models\User;

echo "=== Statistiques des commandes de location ===\n\n";

$total = OrderLocation::count();
echo "📊 Total des commandes: {$total}\n\n";

echo "📋 Distribution par statut:\n";
$statuses = ['pending', 'confirmed', 'active', 'completed', 'closed', 'inspecting', 'finished', 'cancelled'];

foreach ($statuses as $status) {
    $count = OrderLocation::where('status', $status)->count();
    echo "- {$status}: {$count} commandes\n";
}

echo "\n👤 Commandes assignées à l'admin:\n";
$adminUser = User::where('email', 's.mef2703@gmail.com')->first();
if ($adminUser) {
    $adminOrders = OrderLocation::where('user_id', $adminUser->id)->count();
    echo "- Admin ({$adminUser->name}): {$adminOrders} commandes\n";
}

echo "\n🔍 Commandes avec inspection:\n";
$inspectingCount = OrderLocation::where('status', 'inspecting')->count();
$finishedCount = OrderLocation::where('status', 'finished')->count();
echo "- En cours d'inspection: {$inspectingCount}\n";
echo "- Inspection terminée: {$finishedCount}\n";

echo "\n✅ Génération réussie !\n";
