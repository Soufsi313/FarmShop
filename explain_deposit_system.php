<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🏦 ANALYSE DU SYSTÈME DE PAIEMENT ACTUEL\n";
echo "=======================================\n\n";

echo "📝 CE QUI SE PASSE AVEC VOTRE CARTE :\n";
echo "------------------------------------\n";

// Analyser une commande réelle
$order = DB::table('order_locations')->where('payment_status', 'paid')->first();
if ($order) {
    echo "Exemple avec commande #{$order->order_number}:\n\n";
    
    echo "💰 MONTANT TOTAL DÉBITÉ: " . number_format($order->total_amount, 2) . "€\n";
    echo "├─ Prix location: " . number_format($order->subtotal ?? 0, 2) . "€\n";
    echo "├─ TVA: " . number_format($order->tax_amount ?? 0, 2) . "€\n";
    echo "└─ Caution: " . number_format($order->deposit_amount ?? 0, 2) . "€\n\n";
    
    echo "❓ PROBLÈME IDENTIFIÉ:\n";
    if ($order->deposit_amount == 0) {
        echo "⚠️ AUCUNE CAUTION n'est définie/payée!\n";
        echo "→ Seul le prix de location est débité\n";
        echo "→ Il n'y a PAS de blocage/préautorisation de caution\n\n";
    } else {
        echo "✅ Caution définie: " . number_format($order->deposit_amount, 2) . "€\n";
        echo "→ Cette somme EST DÉBITÉE (pas bloquée)\n";
        echo "→ Elle sera remboursée après inspection\n\n";
    }
}

echo "🔧 SYSTÈME ACTUEL VS SYSTÈME THÉORIQUE:\n";
echo "---------------------------------------\n";
echo "📊 SYSTÈME ACTUEL (ce qui se passe):\n";
echo "1. Vous payez: Prix location + TVA + Caution\n";
echo "2. TOUT est débité de votre carte\n";
echo "3. La caution sera remboursée après inspection\n\n";

echo "💡 SYSTÈME THÉORIQUE (ce qui devrait se passer):\n";
echo "1. Vous payez: Prix location + TVA\n";
echo "2. La caution est PRÉAUTORISÉE (bloquée, pas débitée)\n";
echo "3. Après inspection: préautorisation libérée OU convertie en débit\n\n";

echo "🎯 CONCLUSION POUR VOTRE QUESTION:\n";
echo "----------------------------------\n";
echo "❌ ACTUELLEMENT: Vous PAYEZ la caution (elle est débitée)\n";
echo "✅ NORMALEMENT: Elle devrait être BLOQUÉE (préautorisée)\n\n";

echo "📋 VÉRIFICATION PRODUITS:\n";
echo "------------------------\n";
$products = DB::table('products')
    ->where('rental_stock', '>', 0)
    ->get(['name', 'rental_price_per_day', 'rental_deposit'])
    ->take(3);

foreach ($products as $product) {
    $deposit = $product->rental_deposit ?? 0;
    echo "• {$product->name}\n";
    echo "  Prix/jour: " . number_format($product->rental_price_per_day, 2) . "€\n";
    echo "  Caution: " . ($deposit > 0 ? number_format($deposit, 2) . "€" : "NON DÉFINIE") . "\n\n";
}
