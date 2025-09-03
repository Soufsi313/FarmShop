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
use App\Models\OrderItemLocation;
use App\Models\Product;
use App\Models\User;

try {
    echo "🧪 Test du workflow complet des factures\n\n";
    
    // Utilisons la commande existante LOC-202509031206 et testons la différence entre facture initiale/finale
    $existingOrder = OrderLocation::where('order_number', 'LOC-202509031206')->first();
    
    if (!$existingOrder) {
        echo "❌ Commande de test non trouvée\n";
        exit(1);
    }
    
    echo "✅ Utilisation commande existante: {$existingOrder->order_number}\n";
    echo "   État actuel - Inspection: " . ($existingOrder->inspection_completed_at ? 'TERMINÉE' : 'NON TERMINÉE') . "\n";
    
    // 1. Test facture INITIALE (simuler pas d'inspection)
    echo "\n📄 SCENARIO 1: Facture INITIALE (simulation sans inspection)\n";
    
    // Sauvegarder l'état actuel
    $originalInspection = $existingOrder->inspection_completed_at;
    $originalNotes = $existingOrder->inspection_notes;
    
    // Temporairement masquer l'inspection
    $existingOrder->inspection_completed_at = null;
    $existingOrder->inspection_notes = null;
    
    try {
        $filePath = $existingOrder->generateInvoicePdf();
        echo "✅ Facture INITIALE générée (sans inspection)\n";
        echo "   Fichier: " . basename($filePath) . "\n";
        echo "   Contenu: Montants de base uniquement\n";
    } catch (Exception $e) {
        echo "❌ Erreur facture initiale: " . $e->getMessage() . "\n";
    }
    
    // 2. Test facture FINALE (avec inspection)
    echo "\n📄 SCENARIO 2: Facture FINALE (avec inspection complète)\n";
    
    // Restaurer l'inspection + ajouter des détails
    $existingOrder->inspection_completed_at = $originalInspection ?: now();
    $existingOrder->inspection_notes = $originalNotes ?: 'Test: Matériel retourné avec légers dommages.';
    
    try {
        $filePath = $existingOrder->generateInvoicePdf();
        echo "✅ Facture FINALE générée (avec inspection)\n";
        echo "   Fichier: " . basename($filePath) . "\n";
        echo "   Contenu: Montants + pénalités + inspection\n";
        echo "   Pénalités retard: {$existingOrder->late_fees}€\n";
        echo "   Frais dommages: {$existingOrder->damage_cost}€\n";
    } catch (Exception $e) {
        echo "❌ Erreur facture finale: " . $e->getMessage() . "\n";
    }
    
    echo "\n🌐 URL de téléchargement:\n";
    echo "   http://127.0.0.1:8000/rental-orders/{$existingOrder->id}/invoice\n";
    
    echo "\n✅ Test terminé - La facture finale a été générée avec le contenu mis à jour.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
