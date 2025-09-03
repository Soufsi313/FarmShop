<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Bootstrap l'application Laravel
$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        api: __DIR__.'/routes/api.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

try {
    echo "=== ANALYSE DES NUMÉROS DE FACTURE ===\n";
    
    // Voir les commandes avec numéros de facture
    $ordersWithInvoices = OrderLocation::whereNotNull('invoice_number')
        ->orderBy('invoice_number')
        ->get(['id', 'order_number', 'invoice_number', 'status', 'payment_status']);
    
    echo "Commandes avec numéros de facture:\n";
    foreach ($ordersWithInvoices as $order) {
        echo "- {$order->invoice_number} -> {$order->order_number} ({$order->status}/{$order->payment_status})\n";
    }
    
    // Compter les factures FL-2025
    $count = OrderLocation::where('invoice_number', 'like', 'FL-2025-%')->count();
    echo "\nNombre de factures FL-2025: {$count}\n";
    
    // Trouver le prochain numéro disponible
    $prefix = 'FL-' . date('Y') . '-';
    $lastInvoice = OrderLocation::where('invoice_number', 'like', $prefix . '%')
        ->orderBy('invoice_number', 'desc')
        ->first();
    
    if ($lastInvoice) {
        $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
        $nextNumber = $lastNumber + 1;
        echo "Dernier numéro: {$lastInvoice->invoice_number}\n";
        echo "Prochain numéro: {$prefix}" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . "\n";
    } else {
        echo "Aucune facture FL-2025 trouvée, prochain numéro: {$prefix}0001\n";
    }
    
    // Nettoyer les doublons potentiels
    echo "\n=== NETTOYAGE ===\n";
    $duplicates = OrderLocation::whereNotNull('invoice_number')
        ->select('invoice_number')
        ->groupBy('invoice_number')
        ->havingRaw('COUNT(*) > 1')
        ->get();
    
    if ($duplicates->count() > 0) {
        echo "⚠️  Doublons détectés:\n";
        foreach ($duplicates as $dup) {
            $orders = OrderLocation::where('invoice_number', $dup->invoice_number)->get();
            echo "  Numéro {$dup->invoice_number}:\n";
            foreach ($orders as $order) {
                echo "    - ID {$order->id}: {$order->order_number}\n";
            }
        }
    } else {
        echo "✅ Aucun doublon détecté\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
