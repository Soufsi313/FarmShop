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
    echo "🔍 Comparaison des deux commandes de test\n\n";
    
    $orders = OrderLocation::whereIn('order_number', [
        'LOC-TERM-20250903210821',
        'LOC-INSP-20250903210821'
    ])->get();
    
    foreach ($orders as $order) {
        echo "📋 {$order->order_number}:\n";
        echo "   Status: {$order->status}\n";
        echo "   Inspection terminée: " . ($order->inspection_completed_at ? 'OUI (' . $order->inspection_completed_at->format('d/m/Y H:i') . ')' : 'NON') . "\n";
        echo "   Notes: " . ($order->inspection_notes ?: 'AUCUNE') . "\n";
        echo "   Pénalités: {$order->late_fees}€ + {$order->damage_cost}€\n";
        echo "   Affichage prévu: ";
        
        if ($order->status === 'finished') {
            if ($order->inspection_completed_at) {
                echo "✅ Inspection terminée\n";
            } else {
                echo "🔔 Location terminée - À inspecter\n";
            }
        } else {
            echo "{$order->status}\n";
        }
        echo "\n";
    }
    
    echo "🎯 Récapitulatif:\n";
    echo "   LOC-TERM: Terminée SANS inspection → À inspecter manuellement\n";
    echo "   LOC-INSP: Terminée AVEC inspection → Inspection complète\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
