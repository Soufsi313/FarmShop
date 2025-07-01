<?php
// Diagnostic avancé des retours pour la commande FS202507015879
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC AVANCÉ DES RETOURS ===\n\n";

// Trouver la commande par son numéro
$order = \App\Models\Order::where('order_number', 'FS202507015879')->first();

if (!$order) {
    echo "Commande FS202507015879 non trouvée.\n";
    exit;
}

echo "Commande trouvée :\n";
echo "  - ID: {$order->id}\n";
echo "  - Numéro: {$order->order_number}\n";
echo "  - Statut: {$order->status}\n";
echo "  - Client: {$order->user->name}\n";
echo "  - Date livraison: " . ($order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : 'Non livrée') . "\n";

// Vérifier l'éligibilité au retour
if ($order->status === 'delivered' && $order->delivered_at) {
    $returnDeadline = \Carbon\Carbon::parse($order->delivered_at)->addDays(14);
    $isEligible = now()->lte($returnDeadline);
    echo "  - Deadline retour: {$returnDeadline->format('d/m/Y H:i')}\n";
    echo "  - Éligible au retour: " . ($isEligible ? 'OUI' : 'NON') . "\n";
} else {
    echo "  - Pas éligible (statut ou pas de date de livraison)\n";
}

// Vérifier s'il y a déjà des retours pour cette commande
$existingReturns = \App\Models\OrderReturn::where('order_id', $order->id)->get();
echo "\nRetours existants pour cette commande: {$existingReturns->count()}\n";

foreach ($existingReturns as $return) {
    echo "  - ID: {$return->id}\n";
    echo "  - Numéro: {$return->return_number}\n";
    echo "  - Statut: {$return->status}\n";
    echo "  - Montant: {$return->refund_amount}€\n";
    echo "  - Créé le: {$return->created_at->format('d/m/Y H:i')}\n";
    echo "  ---\n";
}

// Vérifier les produits retournables
echo "\nProduits de la commande :\n";
foreach ($order->items as $item) {
    $product = $item->product;
    $isPerishable = $product ? $product->isPerishable() : ($item->is_perishable ?? false);
    
    echo "  - Produit: " . ($product ? $product->name : "Produit supprimé (ID: {$item->product_id})") . "\n";
    echo "    Quantité: {$item->quantity}\n";
    echo "    Prix unitaire: {$item->unit_price}€\n";
    echo "    Total: " . ($item->quantity * $item->unit_price) . "€\n";
    echo "    Périssable: " . ($isPerishable ? 'OUI' : 'NON') . "\n";
    echo "    Retournable: " . (!$isPerishable ? 'OUI' : 'NON') . "\n";
    echo "    ---\n";
}

// Test de génération de numéros de retour
echo "\nTest de génération de numéros de retour uniques :\n";
for ($i = 0; $i < 5; $i++) {
    $microtime = str_replace('.', '', microtime(true));
    $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $testReturnNumber = 'RET' . substr($microtime, 0, 14) . $random;
    
    $exists = \App\Models\OrderReturn::where('return_number', $testReturnNumber)->exists();
    echo "  - {$testReturnNumber} : " . ($exists ? 'EXISTE DÉJÀ' : 'UNIQUE') . "\n";
    
    usleep(10000); // 0.01 seconde
}

// Vérifier tous les numéros de retour récents
echo "\nTous les retours créés aujourd'hui :\n";
$todayReturns = \App\Models\OrderReturn::whereDate('created_at', today())->get();
foreach ($todayReturns as $return) {
    echo "  - {$return->return_number} (Commande: {$return->order_id}, Créé: {$return->created_at->format('H:i:s')})\n";
}

echo "\nDiagnostic terminé !\n";
