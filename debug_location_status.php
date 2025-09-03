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
    echo "🔍 Vérification de l'état de la location à clôturer\n\n";
    
    $location = OrderLocation::where('order_number', 'LOC-COMP-20250903215136')->first();
    
    if (!$location) {
        echo "❌ Location non trouvée\n";
        exit(1);
    }
    
    echo "📋 État actuel de la location:\n";
    echo "   ID: {$location->id}\n";
    echo "   Numéro: {$location->order_number}\n";
    echo "   Statut: {$location->status}\n";
    echo "   Inspection status: " . ($location->inspection_status ?: 'NULL') . "\n";
    echo "   Inspection completed: " . ($location->inspection_completed_at ? 'OUI' : 'NON') . "\n";
    echo "   Created: {$location->created_at}\n";
    echo "   Updated: {$location->updated_at}\n";
    
    echo "\n🎯 Statut attendu pour clôture:\n";
    echo "   - status: 'completed' (Terminée)\n";
    echo "   - Doit pouvoir passer à 'closed' (Clôturée)\n";
    
    if ($location->status === 'completed') {
        echo "\n✅ La location est bien en statut 'completed' et peut être clôturée\n";
    } else {
        echo "\n❌ La location n'est pas en statut 'completed' ({$location->status})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
