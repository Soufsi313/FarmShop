<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

echo "=== Vérification de la commande LOC-202509012460 ===\n\n";

$order = OrderLocation::where('order_number', 'LOC-202509012460')->first();

if (!$order) {
    echo "❌ Commande non trouvée!\n";
    exit(1);
}

echo "✅ Commande trouvée:\n";
echo "   - ID: {$order->id}\n";
echo "   - Numéro: {$order->order_number}\n";
echo "   - Statut: {$order->status}\n";
echo "   - Statut paiement: {$order->payment_status}\n";
echo "   - Peut générer facture: " . ($order->canGenerateInvoice() ? 'OUI' : 'NON') . "\n";
echo "   - Total: {$order->total_amount}€\n";
echo "   - Utilisateur: {$order->user->name} ({$order->user->email})\n";

echo "\n=== Résultat ===\n";
if ($order->canGenerateInvoice()) {
    echo "✅ La facture devrait maintenant être accessible!\n";
} else {
    echo "❌ La facture n'est toujours pas accessible. Statut paiement: {$order->payment_status}\n";
}
