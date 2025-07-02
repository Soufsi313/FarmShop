<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Remise de la commande en attente d'inspection ===\n\n";

$order = OrderLocation::find(26);
if (!$order) {
    echo "❌ Commande non trouvée.\n";
    exit(1);
}

echo "📦 Commande: {$order->order_number}\n";
echo "   - Statut actuel: {$order->status}\n";

// Remettre au statut pending_inspection
$order->update([
    'status' => 'pending_inspection',
    'client_return_date' => now(),
    'client_notes' => 'Location clôturée par le client automatiquement pour test',
    'actual_return_date' => null,
    'returned_at' => null
]);

echo "   - Nouveau statut: {$order->fresh()->status}\n";
echo "   - Date de clôture client: {$order->fresh()->client_return_date}\n";
echo "   - Notes client: {$order->fresh()->client_notes}\n\n";

echo "✅ Prêt pour le test d'inspection !\n";
echo "URL: http://127.0.0.1:8000/admin/locations/26/return\n";
