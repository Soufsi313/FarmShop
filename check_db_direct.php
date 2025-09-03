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

use Illuminate\Support\Facades\DB;

try {
    echo "=== VÉRIFICATION DIRECTE BASE DE DONNÉES ===\n";
    
    // Recherche directe
    $result = DB::select("SELECT id, order_number, invoice_number FROM order_locations WHERE invoice_number = 'FL-2025-0001'");
    
    if (count($result) > 0) {
        echo "⚠️  Trouvé FL-2025-0001 dans la base:\n";
        foreach ($result as $row) {
            echo "  - ID {$row->id}: {$row->order_number} -> {$row->invoice_number}\n";
        }
    } else {
        echo "✅ FL-2025-0001 n'existe pas dans la base\n";
    }
    
    // Lister tous les FL-2025
    $allFL = DB::select("SELECT id, order_number, invoice_number FROM order_locations WHERE invoice_number LIKE 'FL-2025-%'");
    
    echo "\nTous les numéros FL-2025 dans la base:\n";
    if (count($allFL) > 0) {
        foreach ($allFL as $row) {
            echo "  - {$row->invoice_number} (ID {$row->id}: {$row->order_number})\n";
        }
    } else {
        echo "  Aucun numéro FL-2025 trouvé\n";
    }
    
    // Vérifier la contrainte unique
    echo "\nVérification de la contrainte unique:\n";
    $constraints = DB::select("SHOW INDEX FROM order_locations WHERE Key_name = 'order_locations_invoice_number_unique'");
    
    if (count($constraints) > 0) {
        echo "✅ Contrainte unique existe sur invoice_number\n";
    } else {
        echo "❌ Contrainte unique manquante sur invoice_number\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
