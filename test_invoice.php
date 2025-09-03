<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

// Tester la génération de facture
$orderNumber = 'LOC-20250903-E08180'; // La commande problématique

$order = OrderLocation::where('order_number', $orderNumber)->first();

if ($order) {
    echo "🧾 Test de génération de facture pour: " . $order->order_number . "\n";
    echo "📋 Statut: " . $order->status . "\n";
    echo "💳 Paiement: " . $order->payment_status . "\n";
    
    // Vérifier si la facture peut être générée
    $canGenerate = $order->canGenerateInvoice();
    echo "✅ Peut générer facture: " . ($canGenerate ? 'OUI' : 'NON') . "\n";
    
    if ($canGenerate) {
        try {
            echo "🔄 Tentative de génération...\n";
            $filePath = $order->generateInvoicePdf();
            echo "✅ Facture générée avec succès !\n";
            echo "📁 Chemin: " . $filePath . "\n";
            echo "📄 Existe: " . (file_exists($filePath) ? 'OUI' : 'NON') . "\n";
        } catch (\Exception $e) {
            echo "❌ Erreur lors de la génération: " . $e->getMessage() . "\n";
            echo "📍 Ligne: " . $e->getLine() . "\n";
            echo "📂 Fichier: " . $e->getFile() . "\n";
        }
    } else {
        echo "❌ Conditions non remplies pour générer la facture\n";
        echo "   - Statut requis: confirmed, active, completed, returned, inspecting, finished\n";
        echo "   - Paiement requis: paid, partially_paid\n";
    }
    
} else {
    echo "❌ Commande non trouvée: " . $orderNumber . "\n";
    echo "📋 Commandes disponibles:\n";
    $orders = OrderLocation::orderBy('created_at', 'desc')->take(5)->get(['order_number', 'status', 'payment_status']);
    foreach ($orders as $o) {
        echo "   - " . $o->order_number . " (statut: " . $o->status . ", paiement: " . $o->payment_status . ")\n";
    }
}
