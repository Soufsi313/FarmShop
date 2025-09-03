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
    echo "🔍 Recherche avec préfixe: {$prefix}\n";
    
    // Test de la requête exacte utilisée dans generateInvoiceNumber
    $lastInvoice = OrderLocation::where('invoice_number', 'like', $prefix . '%')
        ->orderBy('invoice_number', 'desc')
        ->first();
    
    if ($lastInvoice) {
        echo "✅ Trouvé via Eloquent: {$lastInvoice->invoice_number}\n";
        $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
        echo "   Dernier numéro: {$lastNumber}\n";
        echo "   Prochain numéro: " . ($lastNumber + 1) . "\n";
    } else {
        echo "❌ Aucun résultat via Eloquent\n";
    }
    
    // Test avec une requête SQL directe
    $result = DB::select("SELECT invoice_number FROM order_locations WHERE invoice_number LIKE ? ORDER BY invoice_number DESC LIMIT 1", [$prefix . '%']);
    if ($result) {
        echo "✅ Trouvé via SQL: {$result[0]->invoice_number}\n";
    } else {
        echo "❌ Aucun résultat via SQL\n";
    }
    
    // Lister tous les numéros FL-2025
    $allInvoices = DB::select("SELECT id, order_number, invoice_number FROM order_locations WHERE invoice_number LIKE 'FL-2025-%' ORDER BY invoice_number");
    echo "\n📋 Toutes les factures FL-2025:\n";
    foreach ($allInvoices as $invoice) {
        echo "   ID {$invoice->id}: {$invoice->order_number} -> {$invoice->invoice_number}\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
