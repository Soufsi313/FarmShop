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
    echo "🔧 Correction des statuts des locations de test\n\n";
    
    // Location 1: Doit être terminée mais PAS inspectée
    $location1 = OrderLocation::where('order_number', 'LOC-TERM-20250903210821')->first();
    if ($location1) {
        $location1->update([
            'status' => 'finished', // Location terminée
            'inspection_completed_at' => null, // PAS d'inspection
            'inspection_notes' => null,
            'inspection_status' => null // Pas de statut d'inspection
        ]);
        echo "✅ Location 1 corrigée: {$location1->order_number}\n";
        echo "   Statut: {$location1->status}\n";
        echo "   Inspection: " . ($location1->inspection_completed_at ? 'TERMINÉE' : 'NON TERMINÉE') . "\n";
        echo "   → Facture INITIALE (à clôturer manuellement)\n";
    }
    
    // Location 2: Doit être terminée ET inspectée
    $location2 = OrderLocation::where('order_number', 'LOC-INSP-20250903210821')->first();
    if ($location2) {
        $location2->update([
            'status' => 'finished', // Location terminée
            'inspection_completed_at' => now()->subHours(2), // Inspection terminée
            'inspection_notes' => 'Matériel retourné avec 1 jour de retard. Rayures mineures sur le boîtier.',
            'inspection_status' => 'completed'
        ]);
        echo "✅ Location 2 corrigée: {$location2->order_number}\n";
        echo "   Statut: {$location2->status}\n";
        echo "   Inspection: " . ($location2->inspection_completed_at ? 'TERMINÉE' : 'NON TERMINÉE') . "\n";
        echo "   → Facture FINALE (inspection complète)\n";
    }
    
    echo "\n🎯 Résultat:\n";
    echo "   Location 1: TERMINÉE mais inspection à faire manuellement\n";
    echo "   Location 2: TERMINÉE avec inspection déjà faite\n";
    
    echo "\n🌐 Test des URLs:\n";
    if ($location1) {
        echo "   Location 1: http://127.0.0.1:8000/rental-orders/{$location1->id}/invoice\n";
    }
    if ($location2) {
        echo "   Location 2: http://127.0.0.1:8000/rental-orders/{$location2->id}/invoice\n";
        echo "   Inspection 2: http://127.0.0.1:8000/rental-orders/{$location2->id}/inspection\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
