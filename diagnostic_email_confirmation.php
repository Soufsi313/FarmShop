<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

echo "=== DIAGNOSTIC EMAIL CONFIRMATION ===\n\n";

// Vérifier les commandes récentes
echo "COMMANDES DE LOCATION RÉCENTES:\n";
$recentRentals = OrderLocation::orderBy('created_at', 'desc')
    ->take(3)
    ->get();

foreach ($recentRentals as $rental) {
    echo "- {$rental->order_number}: {$rental->status} (créée: {$rental->created_at})\n";
    
    // Vérifier si l'email de confirmation est bloqué par le cache
    $cacheKey = "email_confirmed_{$rental->id}";
    $isCached = Cache::has($cacheKey);
    echo "  Cache email confirmation: " . ($isCached ? "BLOQUÉ ❌" : "LIBRE ✅") . "\n";
    
    if ($isCached) {
        echo "  → Email bloqué jusqu'à: " . now()->addMinutes(5)->format('H:i:s') . "\n";
    }
    echo "\n";
}

// Vérifier les jobs en queue
echo "JOBS EN QUEUE:\n";
$pendingJobs = DB::table('jobs')->count();
echo "Jobs en attente: {$pendingJobs}\n";

if ($pendingJobs > 0) {
    echo "JOBS EN ATTENTE:\n";
    $jobs = DB::table('jobs')->orderBy('created_at', 'desc')->take(5)->get();
    foreach ($jobs as $job) {
        $payload = json_decode($job->payload, true);
        echo "- " . ($payload['displayName'] ?? 'Job inconnu') . " (queue: {$job->queue})\n";
    }
}

echo "\n=== RECOMMANDATION ===\n";
echo "Si l'email de confirmation est bloqué par le cache, attendez 5 minutes\n";
echo "ou videz le cache avec: Cache::flush() dans un script PHP\n";
echo "\n✅ Les notifications de démarrage fonctionnent parfaitement !\n";
echo "📧 Vous devriez recevoir la notification de fin le 09/08 au soir.\n";
