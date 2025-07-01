<?php
// Créer une commande de test avec délai de retour dépassé (plus de 14 jours)
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION D'UNE COMMANDE AVEC DÉLAI DE RETOUR DÉPASSÉ ===\n\n";

// Récupérer un utilisateur
$user = \App\Models\User::first();
if (!$user) {
    echo "Erreur: Aucun utilisateur trouvé.\n";
    exit;
}

// Récupérer un produit non périssable (le broyeur qu'on vient de corriger)
$product = \App\Models\Product::where('name', 'LIKE', '%broyeur%')->first();
if (!$product) {
    // Sinon prendre n'importe quel produit non périssable
    $product = \App\Models\Product::where('is_perishable', false)->first();
}

if (!$product) {
    echo "Erreur: Aucun produit non périssable trouvé.\n";
    exit;
}

echo "Utilisateur: {$user->name}\n";
echo "Produit sélectionné: {$product->name}\n";
echo "  - Prix: {$product->price}€\n";
echo "  - Périssable: " . ($product->isPerishable() ? 'OUI' : 'NON') . "\n";
echo "  - Catégorie: " . ($product->category ? $product->category->name : 'Non définie') . "\n";

// Calculer les dates pour que le délai soit dépassé
$createdDate = now()->subDays(20); // Commande créée il y a 20 jours
$deliveredDate = now()->subDays(18); // Livrée il y a 18 jours (> 14 jours)
$returnDeadline = $deliveredDate->copy()->addDays(14); // Deadline il y a 4 jours

echo "\nDates de test:\n";
echo "  - Commande créée le: " . $createdDate->format('d/m/Y H:i') . "\n";
echo "  - Livrée le: " . $deliveredDate->format('d/m/Y H:i') . "\n";
echo "  - Deadline de retour: " . $returnDeadline->format('d/m/Y H:i') . "\n";
echo "  - Aujourd'hui: " . now()->format('d/m/Y H:i') . "\n";
echo "  - Délai dépassé de: " . $returnDeadline->diffInDays(now()) . " jour(s)\n";

// Créer la commande
$orderNumber = 'FS' . $createdDate->format('Ymd') . str_pad(\App\Models\Order::count() + 1, 6, '0', STR_PAD_LEFT);

$subtotal = $product->price * 2; // Quantité de 2
$taxAmount = round($subtotal * 0.21, 2);
$shippingCost = 4.99;
$totalAmount = $subtotal + $taxAmount + $shippingCost;

$order = \App\Models\Order::create([
    'user_id' => $user->id,
    'order_number' => $orderNumber,
    'status' => 'delivered',
    'payment_status' => 'paid',
    'subtotal' => $subtotal,
    'tax_amount' => $taxAmount,
    'shipping_cost' => $shippingCost,
    'total_amount' => $totalAmount,
    'payment_method' => 'card',
    'billing_address' => json_encode([
        'name' => $user->name,
        'address' => '456 Rue du Test Délai',
        'city' => 'Bruxelles',
        'postal_code' => '1000',
        'country' => 'Belgique'
    ]),
    'shipping_address' => json_encode([
        'name' => $user->name,
        'address' => '456 Rue du Test Délai',
        'city' => 'Bruxelles',
        'postal_code' => '1000',
        'country' => 'Belgique'
    ]),
    'delivered_at' => $deliveredDate,
    'return_deadline' => $returnDeadline,
    'created_at' => $createdDate,
    'updated_at' => $deliveredDate,
]);

// Ajouter l'item
\App\Models\OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'product_name' => $product->name,
    'quantity' => 2,
    'unit_price' => $product->price,
    'total_price' => $product->price * 2,
    'is_perishable' => false,
    'is_returnable' => true,
]);

echo "\n✅ COMMANDE DE TEST CRÉÉE !\n";
echo "Numéro: {$order->order_number} (ID: {$order->id})\n";
echo "Statut: {$order->status}\n";
echo "Montant total: {$order->total_amount}€\n";

// Vérifier l'éligibilité au retour
$isWithinDeadline = now()->lte($returnDeadline);
echo "\n=== TEST D'ÉLIGIBILITÉ AU RETOUR ===\n";
echo "Deadline de retour: " . $returnDeadline->format('d/m/Y H:i') . "\n";
echo "Date actuelle: " . now()->format('d/m/Y H:i') . "\n";
echo "Dans les délais: " . ($isWithinDeadline ? 'OUI' : 'NON') . "\n";
echo "Jours de retard: " . ($isWithinDeadline ? '0' : now()->diffInDays($returnDeadline)) . "\n";

// Tester l'API d'éligibilité comme le ferait l'interface admin
echo "\n=== TEST DE L'API D'ÉLIGIBILITÉ ===\n";
$controller = new \App\Http\Controllers\Admin\AdminController();
$eligibilityResponse = $controller->checkReturnEligibility($order);
$eligibilityData = $eligibilityResponse->getData(true);

echo "Peut être retournée: " . ($eligibilityData['can_return'] ? 'OUI' : 'NON') . "\n";
if (!$eligibilityData['can_return']) {
    echo "Raison du refus: " . $eligibilityData['reason'] . "\n";
    if (isset($eligibilityData['deadline'])) {
        echo "Deadline qui était: " . $eligibilityData['deadline'] . "\n";
    }
}

echo "\n🧪 COMMANDE DE TEST PRÊTE !\n";
echo "Vous pouvez maintenant tester dans l'interface admin:\n";
echo "  - URL: http://127.0.0.1:8000/admin/orders/cancellation\n";
echo "  - Chercher: {$order->order_number}\n";
echo "  - Le bouton de retour ne devrait PAS apparaître\n";
echo "  - Un message d'erreur devrait s'afficher si vous tentez un retour\n";

echo "\nTest créé avec succès !\n";
