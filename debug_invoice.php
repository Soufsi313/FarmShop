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
    echo "=== DIAGNOSTIC FACTURE ===\n";
    
    // Chercher la dernière commande créée
    $order = OrderLocation::orderBy('created_at', 'desc')->first();
    
    if (!$order) {
        echo "❌ Aucune commande trouvée\n";
        exit(1);
    }
    
    echo "📋 Commande: {$order->order_number}\n";
    echo "📊 Statut: {$order->status}\n";
    echo "💳 Paiement: {$order->payment_status}\n";
    echo "📄 Numéro facture: " . ($order->invoice_number ?? 'Non généré') . "\n";
    echo "✅ Peut générer facture: " . ($order->canGenerateInvoice() ? 'OUI' : 'NON') . "\n";
    
    if ($order->canGenerateInvoice()) {
        echo "\n=== TEST GÉNÉRATION FACTURE ===\n";
        
        try {
            // Tester la génération de numéro de facture
            if (!$order->invoice_number) {
                echo "🔢 Génération du numéro de facture...\n";
                $invoiceNumber = $order->generateInvoiceNumber();
                echo "✅ Numéro généré: {$invoiceNumber}\n";
                $order->save();
            }
            
            echo "✅ Facture prête à être générée\n";
            echo "🌐 URL: http://127.0.0.1:8000/rental-orders/{$order->id}/invoice\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur lors de la génération: " . $e->getMessage() . "\n";
            echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
        }
    } else {
        echo "❌ Cette commande ne peut pas générer de facture\n";
        echo "   Statut requis: paid, partially_paid, deposit_paid\n";
        echo "   Statut actuel: {$order->payment_status}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
