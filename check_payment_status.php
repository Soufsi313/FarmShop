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
    echo "🔍 Vérification des statuts de paiement des locations de test\n\n";
    
    $locations = OrderLocation::whereIn('order_number', [
        'LOC-TERM-20250903210821',
        'LOC-INSP-20250903210821'
    ])->get();
    
    foreach ($locations as $location) {
        echo "📋 Location: {$location->order_number}\n";
        echo "   payment_status: '{$location->payment_status}'\n";
        echo "   deposit_amount: {$location->deposit_amount}€\n";
        echo "   total_amount: {$location->total_amount}€\n";
        echo "   created_at: {$location->created_at}\n";
        echo "   updated_at: {$location->updated_at}\n";
        echo "\n";
    }
    
    echo "🔍 Valeurs possibles de payment_status:\n";
    echo "   - 'pending': Paiement en attente\n";
    echo "   - 'paid': Paiement effectué\n";
    echo "   - 'deposit_paid': Caution payée\n";
    echo "   - 'failed': Paiement échoué\n";
    echo "   - 'refunded': Remboursé\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
