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
use Illuminate\Support\Facades\DB;
try {
    $prefix = 'FL-' . date('Y') . '-';
    echo "🔍 Vérification des soft deletes pour les factures FL-2025:\n";
    
    // Requête avec withTrashed() pour voir tous les enregistrements
    $allInvoices = OrderLocation::withTrashed()
        ->where('invoice_number', 'like', $prefix . '%')
        ->orderBy('invoice_number', 'desc')
        ->get();
    
    echo "📋 Toutes les factures FL-2025 (y compris supprimées):\n";
    foreach ($allInvoices as $invoice) {
        $deleted = $invoice->deleted_at ? '❌ SUPPRIMÉ' : '✅ ACTIF';
        echo "   ID {$invoice->id}: {$invoice->order_number} -> {$invoice->invoice_number} {$deleted}\n";
    }
    
    // Test de génération en utilisant withTrashed()
    $lastInvoice = OrderLocation::withTrashed()
        ->where('invoice_number', 'like', $prefix . '%')
        ->orderBy('invoice_number', 'desc')
        ->first();
    
    if ($lastInvoice) {
        $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
        $newNumber = $lastNumber + 1;
        $nextInvoiceNumber = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        echo "\n🎯 Prochain numéro de facture à utiliser: {$nextInvoiceNumber}\n";
        
        // Tenter d'attribuer ce numéro à la commande de test
        $order = OrderLocation::where('order_number', 'LOC-202509031206')->first();
        if ($order) {
            $order->update(['invoice_number' => $nextInvoiceNumber]);
            echo "✅ Numéro {$nextInvoiceNumber} attribué à la commande LOC-202509031206\n";
            echo "🌐 Testez maintenant: http://127.0.0.1:8000/rental-orders/{$order->id}/invoice\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
