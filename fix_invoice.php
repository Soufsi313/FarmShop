<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

echo "🔍 Vérification des numéros de facture...\n";

// Vérifier qui a le numéro FL-2025-0001
$existingInvoice = OrderLocation::where('invoice_number', 'FL-2025-0001')->first();
if ($existingInvoice) {
    echo "📋 Numéro FL-2025-0001 déjà utilisé par: " . $existingInvoice->order_number . "\n";
}

// Vérifier le statut de la commande problématique
$order = OrderLocation::where('order_number', 'LOC-20250903-E08180')->first();
if ($order) {
    echo "📋 Commande: " . $order->order_number . "\n";
    echo "📄 Numéro facture actuel: " . ($order->invoice_number ?: 'AUCUN') . "\n";
    
    // Réinitialiser le numéro de facture pour forcer une nouvelle génération
    if ($order->invoice_number) {
        echo "🔄 Réinitialisation du numéro de facture...\n";
        $order->update(['invoice_number' => null]);
        echo "✅ Numéro de facture réinitialisé\n";
    }
    
    // Essayer de générer un nouveau numéro
    try {
        echo "🔄 Génération d'un nouveau numéro...\n";
        $newNumber = $order->generateInvoiceNumber();
        echo "✅ Nouveau numéro généré: " . $newNumber . "\n";
    } catch (\Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
}

echo "\n📋 Derniers numéros de factures:\n";
$invoices = OrderLocation::whereNotNull('invoice_number')
    ->orderBy('invoice_number', 'desc')
    ->take(5)
    ->get(['order_number', 'invoice_number']);
    
foreach ($invoices as $inv) {
    echo "   - " . $inv->order_number . " → " . $inv->invoice_number . "\n";
}
