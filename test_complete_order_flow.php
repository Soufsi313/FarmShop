<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;

echo "=== Création d'une commande de test ===\n\n";

// Trouver un utilisateur
$user = User::first();
if (!$user) {
    echo "Aucun utilisateur trouvé.\n";
    exit;
}

// Trouver un produit
$product = Product::where('stock_quantite', '>', 0)->first();
if (!$product) {
    echo "Aucun produit en stock trouvé.\n";
    exit;
}

echo "Utilisateur: {$user->nom} {$user->prenom}\n";
echo "Produit: {$product->nom} (Stock: {$product->stock_quantite})\n\n";

// Créer une nouvelle commande
$order = Order::create([
    'user_id' => $user->id,
    'status' => 'pending',
    'payment_status' => 'pending',
    'subtotal' => $product->prix,
    'tax_amount' => $product->prix * ($product->tva_rate / 100),
    'total_amount' => $product->prix * (1 + $product->tva_rate / 100),
    'billing_address' => json_encode([
        'name' => $user->nom . ' ' . $user->prenom,
        'email' => $user->email,
        'address' => 'Test Address'
    ]),
    'shipping_address' => json_encode([
        'name' => $user->nom . ' ' . $user->prenom,
        'email' => $user->email,
        'address' => 'Test Address'
    ])
]);

// Ajouter un item à la commande
OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'quantity' => 1,
    'unit_price' => $product->prix,
    'total_price' => $product->prix,
    'tva_rate' => $product->tva_rate,
    'tva_amount' => $product->prix * ($product->tva_rate / 100)
]);

echo "Commande créée: #{$order->order_number} (ID: {$order->id})\n";
echo "Statut initial: {$order->status}\n\n";

echo "=== Simulation du processus de paiement webhook ===\n";

// Simuler ce qui se passe dans le webhook après paiement réussi
echo "1. Mise à jour du paiement...\n";
$order->update([
    'payment_status' => 'paid',
    'paid_at' => now(),
    'payment_method' => 'stripe'
]);

echo "2. Progression: pending -> confirmed\n";
$order->updateStatus('confirmed');

echo "3. Progression: confirmed -> preparing\n";  
$order->updateStatus('preparing');

echo "4. Progression: preparing -> shipped\n";
$order->updateStatus('shipped');

echo "5. Progression: shipped -> delivered\n";
$order->updateStatus('delivered');

// Rafraîchir la commande
$order = $order->fresh();

echo "\n=== Résultat final ===\n";
echo "Statut final: {$order->status}\n";
echo "Statut paiement: {$order->payment_status}\n";
echo "Livré le: " . ($order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : 'Non défini') . "\n";
echo "Expédié le: " . ($order->shipped_at ? $order->shipped_at->format('Y-m-d H:i:s') : 'Non défini') . "\n";
echo "Numéro de facture: " . ($order->invoice_number ?: 'Non généré') . "\n\n";

echo "Historique des statuts:\n";
if ($order->status_history && is_array($order->status_history)) {
    foreach ($order->status_history as $status) {
        echo "  {$status['from']} -> {$status['to']} ({$status['timestamp']})\n";
    }
} else {
    echo "  Aucun historique trouvé\n";
}

echo "\n=== Test terminé ===\n";
