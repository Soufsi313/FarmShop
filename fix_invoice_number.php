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
use Illuminate\Support\Facades\DB;

try {
    echo "=== TEST MANUEL GÉNÉRATION FACTURE ===\n";
    
    // Prendre la commande de test
    $order = OrderLocation::where('order_number', 'LOC-202509031206')->first();
    
    if (!$order) {
        echo "❌ Commande non trouvée\n";
        exit(1);
    }
    
    echo "📋 Commande: {$order->order_number}\n";
    echo "📄 Numéro facture actuel: " . ($order->invoice_number ?? 'Aucun') . "\n";
    
    // Nettoyer le numéro de facture s'il existe
    if ($order->invoice_number) {
        echo "🧹 Nettoyage du numéro existant...\n";
        $order->update(['invoice_number' => null]);
    }
    
    // Génération manuelle du numéro
    $prefix = 'FL-' . date('Y') . '-';
    echo "🔢 Préfixe: {$prefix}\n";
    
    // Chercher le dernier numéro
    $lastInvoice = OrderLocation::where('invoice_number', 'like', $prefix . '%')
        ->orderBy('invoice_number', 'desc')
        ->first();
    
    if ($lastInvoice) {
        $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
        $newNumber = $lastNumber + 1;
        echo "📈 Dernier numéro: {$lastInvoice->invoice_number} -> Nouveau: {$newNumber}\n";
    } else {
        $newNumber = 1;
        echo "🆕 Premier numéro: {$newNumber}\n";
    }
    
    $invoiceNumber = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    echo "🎯 Numéro proposé: {$invoiceNumber}\n";
    
    // Vérifier qu'il n'existe pas
    $exists = OrderLocation::where('invoice_number', $invoiceNumber)->exists();
    echo "🔍 Existe déjà: " . ($exists ? 'OUI ❌' : 'NON ✅') . "\n";
    
    if (!$exists) {
        echo "💾 Attribution du numéro...\n";
        $order->update(['invoice_number' => $invoiceNumber]);
        echo "✅ Numéro attribué avec succès!\n";
        
        echo "\n🌐 Testez maintenant: http://127.0.0.1:8000/rental-orders/{$order->id}/invoice\n";
    } else {
        echo "❌ Le numéro existe déjà, impossible de l'attribuer\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
