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
    echo "🔍 Vérification détaillée de la commande LOC-TERM-20250903210821\n\n";
    
    $order = OrderLocation::where('order_number', 'LOC-TERM-20250903210821')->first();
    
    if (!$order) {
        echo "❌ Commande non trouvée\n";
        exit(1);
    }
    
    echo "📋 Détails de la commande:\n";
    echo "   ID: {$order->id}\n";
    echo "   Numéro: {$order->order_number}\n";
    echo "   Status: {$order->status}\n";
    echo "   inspection_status: " . ($order->inspection_status ?: 'NULL') . "\n";
    echo "   inspection_completed_at: " . ($order->inspection_completed_at ? $order->inspection_completed_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "   inspection_notes: " . ($order->inspection_notes ?: 'NULL') . "\n";
    echo "   product_condition: " . ($order->product_condition ?: 'NULL') . "\n";
    echo "   late_fees: {$order->late_fees}€\n";
    echo "   damage_cost: {$order->damage_cost}€\n";
    echo "   created_at: {$order->created_at}\n";
    echo "   updated_at: {$order->updated_at}\n";
    
    echo "\n🔍 Logique d'affichage dans la vue:\n";
    echo "   Condition 1: status === 'finished' → " . ($order->status === 'finished' ? 'TRUE' : 'FALSE') . "\n";
    echo "   Condition 2: inspection_completed_at existe → " . ($order->inspection_completed_at ? 'TRUE' : 'FALSE') . "\n";
    
    if ($order->status === 'finished') {
        if ($order->inspection_completed_at) {
            echo "   → Affichage: ✅ Inspection terminée\n";
        } else {
            echo "   → Affichage: 🔔 Location terminée - À inspecter\n";
        }
    }
    
    echo "\n🎯 Conclusion:\n";
    if ($order->inspection_completed_at) {
        echo "   L'inspection EST TERMINÉE dans la base de données\n";
        echo "   ✅ Le dashboard a raison\n";
        echo "   ❌ La liste des locations pourrait avoir un problème de cache\n";
    } else {
        echo "   L'inspection N'EST PAS TERMINÉE dans la base de données\n";
        echo "   ✅ La liste des locations a raison\n";
        echo "   ❌ Le dashboard pourrait montrer des données incorrectes\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
