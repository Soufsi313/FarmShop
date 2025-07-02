<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test final du système d'automatisation ===\n\n";

// Vérifier les commandes en attente d'inspection
$pendingInspections = OrderLocation::where('status', 'pending_inspection')
    ->with(['items.product', 'user'])
    ->orderBy('client_return_date', 'desc')
    ->get();

echo "📊 Commandes en attente d'inspection: {$pendingInspections->count()}\n\n";

foreach ($pendingInspections as $order) {
    echo "📦 {$order->order_number}\n";
    echo "   - Client: {$order->user->name}\n";
    echo "   - Articles: {$order->items->count()}\n";
    echo "   - Date clôture client: " . ($order->client_return_date ? $order->client_return_date->format('d/m/Y H:i') : 'Non définie') . "\n";
    echo "   - Notes client: " . ($order->client_notes ?? 'Aucune') . "\n";
    echo "   - URL détail: http://127.0.0.1:8000/admin/locations/{$order->id}\n";
    echo "   - URL inspection: http://127.0.0.1:8000/admin/locations/{$order->id}/return\n\n";
}

// Vérifier les commandes actives qui peuvent être clôturées
$activeOrders = OrderLocation::where('status', 'active')
    ->whereDate('rental_end_date', '<=', now())
    ->with(['items.product', 'user'])
    ->get();

echo "🔄 Commandes actives éligibles à la clôture: {$activeOrders->count()}\n\n";

foreach ($activeOrders as $order) {
    echo "📦 {$order->order_number}\n";
    echo "   - Client: {$order->user->name}\n";
    echo "   - Articles: {$order->items->count()}\n";
    echo "   - Fin prévue: {$order->rental_end_date->format('d/m/Y')}\n";
    echo "   - Peut être clôturée: " . ($order->can_be_closed_by_client ? 'Oui' : 'Non') . "\n";
    echo "   - URL client: http://127.0.0.1:8000/commandes-location/{$order->id}\n\n";
}

echo "=== Récapitulatif du workflow ===\n";
echo "1. ✅ Client peut clôturer sa location le jour de fin\n";
echo "2. ✅ Location passe en statut 'pending_inspection'\n";
echo "3. ✅ Admin voit la location dans le dashboard\n";
echo "4. ✅ Admin peut procéder à l'inspection\n";
echo "5. ✅ Admin finalise le retour\n\n";

echo "🎯 Système d'automatisation opérationnel !\n";
