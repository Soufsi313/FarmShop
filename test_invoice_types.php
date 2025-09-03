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
    
    echo "📋 État de la commande:\n";
    echo "   ID: {$order->id}\n";
    echo "   Numéro: {$order->order_number}\n";
    echo "   Facture: {$order->invoice_number}\n";
    echo "   Statut: {$order->status}\n";
    echo "   Inspection terminée: " . ($order->inspection_completed_at ? 'OUI' : 'NON') . "\n";
    echo "   Pénalités retard: {$order->late_fees}€\n";
    echo "   Frais dommages: {$order->damage_cost}€\n";
    
    echo "\n🔄 Test de génération facture INITIALE (sans inspection):\n";
    // Temporairement masquer l'inspection pour tester la facture initiale
    $originalInspection = $order->inspection_completed_at;
    $order->inspection_completed_at = null;
    
    try {
        $filePath = $order->generateInvoicePdf();
        echo "✅ Facture initiale générée: " . basename($filePath) . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur facture initiale: " . $e->getMessage() . "\n";
    }
    
    // Restaurer l'inspection
    $order->inspection_completed_at = $originalInspection;
    
    echo "\n🔄 Test de génération facture FINALE (avec inspection):\n";
    try {
        $filePath = $order->generateInvoicePdf();
        echo "✅ Facture finale générée: " . basename($filePath) . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur facture finale: " . $e->getMessage() . "\n";
    }
    
    echo "\n🌐 URLs de test:\n";
    echo "   Facture: http://127.0.0.1:8000/rental-orders/{$order->id}/invoice\n";
    echo "   Inspection: http://127.0.0.1:8000/rental-orders/{$order->id}/inspection\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
