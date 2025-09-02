<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

echo "=== Analyse détaillée de la commande LOC-202509012460 ===\n\n";

$order = OrderLocation::where('order_number', 'LOC-202509012460')->first();

if (!$order) {
    echo "❌ Commande non trouvée!\n";
    exit(1);
}

echo "📋 Informations complètes:\n";
echo "   - ID: {$order->id}\n";
echo "   - Numéro: {$order->order_number}\n";
echo "   - Statut: {$order->status}\n";
echo "   - Statut paiement: {$order->payment_status}\n";
echo "   - Méthode paiement: {$order->payment_method}\n";
echo "   - Total: {$order->total_amount}€\n";
echo "   - Créée le: {$order->created_at}\n";
echo "   - Mise à jour le: {$order->updated_at}\n";

if ($order->payment_id) {
    echo "   - ID paiement: {$order->payment_id}\n";
}

if ($order->stripe_session_id) {
    echo "   - Session Stripe: {$order->stripe_session_id}\n";
}

echo "\n🔍 Analyse du problème:\n";
if ($order->payment_status === 'pending') {
    echo "   ⚠️  Le statut 'pending' indique que le paiement n'est pas encore confirmé\n";
    echo "   💡 Solutions possibles:\n";
    echo "      1. Le client doit compléter le paiement\n";
    echo "      2. Le webhook Stripe n'a pas encore mis à jour le statut\n";
    echo "      3. Il y a eu un problème lors du traitement du paiement\n";
} else {
    echo "   ✅ Statut de paiement valide pour la facture\n";
}

echo "\n🛠️  Pour forcer l'accès à la facture même en 'pending':\n";
echo "     Voulez-vous modifier la logique pour permettre les factures même en 'pending'?\n";
