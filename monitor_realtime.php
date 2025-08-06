<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;

echo "=== SURVEILLANCE EN TEMPS RÉEL ===\n\n";

// Surveiller pendant 5 minutes
$startTime = time();
$duration = 300; // 5 minutes

echo "🕐 Surveillance démarrée pour 5 minutes...\n";
echo "⏰ Arrêt prévu à " . date('H:i:s', $startTime + $duration) . "\n\n";

$lastCheck = 0;

while (time() - $startTime < $duration) {
    $currentTime = time();
    
    // Vérifier toutes les 30 secondes
    if ($currentTime - $lastCheck >= 30) {
        $timestamp = date('H:i:s');
        echo "[$timestamp] Vérification...\n";
        
        try {
            // Vérifier votre commande
            $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
            if ($order) {
                echo "  📊 LOC-202508034682: {$order->status}\n";
            }
            
            // Vérifier les commandes de test récentes
            $testOrders = OrderLocation::where('order_number', 'like', 'TEST-%')
                ->where('created_at', '>=', now()->subHours(2))
                ->get();
                
            foreach ($testOrders as $testOrder) {
                $timeUntilStart = $testOrder->start_date->diffInSeconds(now(), false);
                $timeUntilEnd = $testOrder->end_date->diffInSeconds(now(), false);
                
                echo "  🧪 {$testOrder->order_number}: {$testOrder->status}";
                
                if ($timeUntilStart <= 0 && $timeUntilEnd > 0) {
                    echo " (devrait être active)";
                } elseif ($timeUntilEnd <= 0) {
                    echo " (devrait être completed)";
                } else {
                    echo " (en attente: " . abs(round($timeUntilStart / 60)) . "min)";
                }
                echo "\n";
            }
            
            // Vérifier la queue
            $jobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
            echo "  📦 Jobs en queue: {$jobsCount}\n";
            
            // Vérifier les logs récents (dernière minute)
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                $logContent = file_get_contents($logFile);
                $lines = explode("\n", $logContent);
                $recentLines = array_slice($lines, -50); // 50 dernières lignes
                
                $relevantLogs = array_filter($recentLines, function($line) {
                    return strpos($line, date('Y-m-d H:i')) !== false && 
                           (strpos($line, 'location') !== false || 
                            strpos($line, 'rental') !== false ||
                            strpos($line, 'email') !== false ||
                            strpos($line, 'Job') !== false);
                });
                
                if (!empty($relevantLogs)) {
                    echo "  📋 Logs récents:\n";
                    foreach (array_slice($relevantLogs, -3) as $log) {
                        echo "    " . substr($log, 0, 100) . "...\n";
                    }
                }
            }
            
        } catch (\Exception $e) {
            echo "  ❌ Erreur: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
        $lastCheck = $currentTime;
    }
    
    sleep(5);
}

echo "🏁 Surveillance terminée.\n";
echo "📧 Vérifiez maintenant votre email s.mef2703@gmail.com\n";
echo "💡 Si pas d'email reçu, le problème peut être:\n";
echo "   - Configuration SMTP\n";
echo "   - Email en spam\n";
echo "   - Template d'email défaillant\n";
