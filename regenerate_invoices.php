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
    echo "🔄 Régénération des factures avec statut de paiement corrigé\n\n";
    
    $locations = OrderLocation::whereIn('order_number', [
        'LOC-TERM-20250903210821',
        'LOC-INSP-20250903210821'
    ])->get();
    
    foreach ($locations as $location) {
        echo "📄 {$location->order_number} (payment_status: {$location->payment_status})\n";
        
        try {
            $filePath = $location->generateInvoicePdf();
            echo "✅ Facture régénérée: " . basename($filePath) . "\n";
            echo "   Type: " . ($location->inspection_completed_at ? 'FINALE' : 'INITIALE') . "\n";
            echo "   URL: http://127.0.0.1:8000/rental-orders/{$location->id}/invoice\n";
        } catch (Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    echo "🎯 Les factures devraient maintenant afficher 'Paiement effectué' au lieu de 'Paiement en attente'\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
