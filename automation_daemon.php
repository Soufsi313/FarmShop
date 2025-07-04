<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Order;
use Carbon\Carbon;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Automatisation continue des commandes ===\n";
echo "Démarrage à " . now()->format('d/m/Y H:i:s') . "\n";
echo "Appuyez sur Ctrl+C pour arrêter\n\n";

$iteration = 0;

while (true) {
    $iteration++;
    echo "[" . now()->format('H:i:s') . "] Itération #{$iteration}\n";
    
    // Compter les commandes qui peuvent être mises à jour
    $eligibleOrders = 0;
    
    // Commandes confirmed → preparation
    $confirmedOrders = Order::where('status', 'confirmed')
        ->where(function($q) {
            $q->where(function($subQ) {
                $subQ->whereNotNull('confirmed_at')
                     ->where('confirmed_at', '<=', now()->subSeconds(60));
            })->orWhere(function($subQ) {
                $subQ->whereNull('confirmed_at')
                     ->where('updated_at', '<=', now()->subSeconds(60));
            });
        })->count();
    
    // Commandes preparation → shipped
    $preparationOrders = Order::where('status', 'preparation')
        ->where(function($q) {
            $q->where(function($subQ) {
                $subQ->whereNotNull('preparation_at')
                     ->where('preparation_at', '<=', now()->subSeconds(60));
            })->orWhere(function($subQ) {
                $subQ->whereNull('preparation_at')
                     ->where('updated_at', '<=', now()->subSeconds(60));
            });
        })->count();
    
    // Commandes shipped → delivered
    $shippedOrders = Order::where('status', 'shipped')
        ->where(function($q) {
            $q->where(function($subQ) {
                $subQ->whereNotNull('shipped_at')
                     ->where('shipped_at', '<=', now()->subSeconds(60));
            })->orWhere(function($subQ) {
                $subQ->whereNull('shipped_at')
                     ->where('updated_at', '<=', now()->subSeconds(60));
            });
        })->count();
    
    $eligibleOrders = $confirmedOrders + $preparationOrders + $shippedOrders;
    
    if ($eligibleOrders > 0) {
        echo "   📦 {$eligibleOrders} commande(s) éligible(s) pour mise à jour\n";
        echo "   🔧 Exécution de l'automatisation...\n";
        
        // Exécuter l'automatisation
        $exitCode = \Artisan::call('orders:update-status');
        $output = \Artisan::output();
        
        // Afficher seulement les lignes importantes
        $lines = explode("\n", trim($output));
        foreach ($lines as $line) {
            if (strpos($line, '✅') !== false || strpos($line, '📧') !== false) {
                echo "   " . $line . "\n";
            }
        }
    } else {
        echo "   ℹ️  Aucune commande à mettre à jour\n";
    }
    
    echo "   ⏰ Prochaine vérification dans 45 secondes...\n\n";
    
    // Attendre 45 secondes avant la prochaine vérification
    sleep(45);
}
