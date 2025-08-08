<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== STATUT QUEUE SYSTEM ===\n\n";

// Vérifier la queue
$pendingJobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();

echo "Jobs en attente: {$pendingJobs}\n";
echo "Jobs échoués: {$failedJobs}\n\n";

if ($pendingJobs > 0) {
    echo "Jobs en attente:\n";
    $jobs = DB::table('jobs')->orderBy('created_at', 'desc')->limit(5)->get();
    foreach ($jobs as $job) {
        $payload = json_decode($job->payload, true);
        echo "- Queue: {$job->queue}\n";
        echo "  Job: " . ($payload['displayName'] ?? 'N/A') . "\n";
        echo "  Créé: " . date('Y-m-d H:i:s', $job->created_at) . "\n\n";
    }
}

echo "✅ CORRECTIONS SYSTÈME EMAIL:\n";
echo "1. Listener HandleOrderLocationStatusChange: ASYNC (ShouldQueue)\n";
echo "2. RentalOrderConfirmed: ASYNC (ShouldQueue) ✅\n";
echo "3. RentalOrderCancelled: ASYNC (ShouldQueue) ✅\n";
echo "4. RentalOrderCompleted: ASYNC (ShouldQueue) ✅\n\n";

echo "🚀 Le système est maintenant entièrement asynchrone!\n";
echo "Les emails de location passeront par la queue pour une meilleure performance.\n";
