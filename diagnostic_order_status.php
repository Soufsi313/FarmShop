<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use Illuminate\Support\Facades\DB;

// Trouver la dernière commande
$lastOrder = Order::orderBy('created_at', 'desc')->first();

if ($lastOrder) {
    echo "=== DIAGNOSTIC DERNIÈRE COMMANDE ===\n";
    echo "ID: " . $lastOrder->id . "\n";
    echo "Numéro: " . $lastOrder->order_number . "\n";
    echo "Statut actuel: " . $lastOrder->status . "\n";
    echo "Payment status: " . ($lastOrder->payment_status ?? 'non défini') . "\n";
    echo "Paid at: " . ($lastOrder->paid_at ?? 'non défini') . "\n";
    echo "Created at: " . $lastOrder->created_at . "\n";
    echo "Updated at: " . $lastOrder->updated_at . "\n";
    
    // Vérifier les jobs en queue
    echo "\n=== JOBS EN QUEUE ===\n";
    $jobsCount = DB::table('jobs')->count();
    echo "Jobs en attente: " . $jobsCount . "\n";
    
    if ($jobsCount > 0) {
        $jobs = DB::table('jobs')->orderBy('created_at', 'desc')->take(5)->get();
        foreach ($jobs as $job) {
            $payload = json_decode($job->payload, true);
            echo "- Job: " . ($payload['displayName'] ?? 'Unknown') . " (Queue: {$job->queue})\n";
        }
    }
    
    // Vérifier les jobs failed
    echo "\n=== JOBS ÉCHOUÉS ===\n";
    $failedJobsCount = DB::table('failed_jobs')->count();
    echo "Jobs échoués: " . $failedJobsCount . "\n";
    
    if ($failedJobsCount > 0) {
        $failedJobs = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->take(3)->get();
        foreach ($failedJobs as $job) {
            echo "- Job échoué: " . $job->payload . "\n";
            echo "  Erreur: " . substr($job->exception, 0, 200) . "...\n";
        }
    }
    
    // Vérifier si le modèle a la méthode updateStatus
    echo "\n=== VÉRIFICATION DU MODÈLE ===\n";
    if (method_exists($lastOrder, 'updateStatus')) {
        echo "✅ Méthode updateStatus disponible\n";
    } else {
        echo "❌ Méthode updateStatus manquante\n";
    }
    
} else {
    echo "Aucune commande trouvée\n";
}
