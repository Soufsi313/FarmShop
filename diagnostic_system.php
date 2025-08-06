<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "=== DIAGNOSTIC ET RÉPARATION DU SYSTÈME AUTOMATIQUE ===\n\n";

try {
    // 1. Vérifier la configuration de queue
    echo "📋 Configuration actuelle :\n";
    echo "   Queue Driver: " . config('queue.default') . "\n";
    echo "   Database: " . config('database.default') . "\n\n";

    // 2. Vérifier si la table jobs existe et est accessible
    echo "🔍 Vérification de la table jobs...\n";
    $jobsCount = DB::table('jobs')->count();
    echo "   ✅ Table jobs accessible, {$jobsCount} jobs en attente\n\n";

    // 3. Nettoyer les anciens jobs si nécessaire
    if ($jobsCount > 100) {
        echo "🧹 Nettoyage des anciens jobs...\n";
        DB::table('jobs')->where('available_at', '<', now()->subHours(24)->timestamp)->delete();
        echo "   ✅ Anciens jobs supprimés\n\n";
    }

    // 4. Tester la création d'un job simple
    echo "🧪 Test de création d'un job...\n";
    
    // Créer un job de test simple
    \App\Jobs\AutoUpdateRentalStatusJob::dispatch();
    
    $newJobsCount = DB::table('jobs')->count();
    echo "   ✅ Job créé avec succès ({$newJobsCount} jobs en queue)\n\n";

    // 5. Vérifier les locations qui ont besoin de mise à jour
    echo "🏠 Vérification des locations...\n";
    
    $confirmedExpired = OrderLocation::where('status', 'confirmed')
        ->where('start_date', '<=', now())
        ->count();
        
    $activeExpired = OrderLocation::where('status', 'active')
        ->where('end_date', '<=', now())
        ->count();
    
    echo "   📊 Locations confirmées à activer: {$confirmedExpired}\n";
    echo "   📊 Locations actives à terminer: {$activeExpired}\n\n";

    // 6. Afficher les commandes à traiter
    if ($confirmedExpired > 0) {
        echo "🔍 Locations à activer :\n";
        $toActivate = OrderLocation::where('status', 'confirmed')
            ->where('start_date', '<=', now())
            ->select('order_number', 'start_date', 'end_date')
            ->get();
            
        foreach ($toActivate as $order) {
            echo "   • {$order->order_number} (début: {$order->start_date})\n";
        }
        echo "\n";
    }

    // 7. Instructions pour démarrer le worker
    echo "🚀 POUR DÉMARRER LE SYSTÈME AUTOMATIQUE :\n\n";
    echo "1. Dans un nouveau terminal (cmd ou PowerShell), exécutez :\n";
    echo "   cd \"C:\\Users\\Master\\Desktop\\FarmShop\"\n";
    echo "   php artisan queue:work --daemon --sleep=3 --tries=3 --timeout=60\n\n";
    
    echo "2. Pour traiter immédiatement les jobs en attente :\n";
    echo "   php artisan queue:work --once\n\n";
    
    echo "3. Pour surveiller la queue :\n";
    echo "   php artisan queue:monitor\n\n";

    echo "✅ DIAGNOSTIC TERMINÉ - Le système est prêt à fonctionner!\n";
    echo "💡 Il suffit maintenant de démarrer le queue worker.\n\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📝 Stack trace: " . $e->getTraceAsString() . "\n";
}
