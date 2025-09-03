<?php
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        api: __DIR__.'/routes/api.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\OrderLocation;
try {
    $order = OrderLocation::where('order_number', 'LOC-202509031206')->first();
    if (!$order) {
        echo "❌ Commande non trouvée\n";
        exit(1);
    }
    echo "📋 État de la commande LOC-202509031206:\n";
    echo "   ID: {$order->id}\n";
    echo "   Numéro de facture: " . ($order->invoice_number ?: 'NON ATTRIBUÉ') . "\n";
    echo "   Statut: {$order->status}\n";
    echo "   Statut paiement: {$order->payment_status}\n";
    echo "   Total: {$order->total_amount}€\n";
    echo "   canGenerateInvoice(): " . ($order->canGenerateInvoice() ? 'OUI' : 'NON') . "\n";
    echo "   URL de téléchargement: http://127.0.0.1:8000/rental-orders/{$order->id}/invoice\n";
    
    // Test direct de génération de facture
    if (!$order->invoice_number) {
        echo "\n🔧 Tentative de génération du numéro de facture...\n";
        try {
            $invoiceNumber = $order->generateInvoiceNumber();
            $order->save();
            echo "✅ Numéro généré: {$invoiceNumber}\n";
        } catch (Exception $e) {
            echo "❌ Erreur génération: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
