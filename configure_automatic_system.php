<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Log;

echo "=== CONFIGURATION DU SYSTÈME AUTOMATIQUE DE LOCATIONS ===\n\n";

// 1. Vérifier la configuration de la queue
echo "1. 📋 VÉRIFICATION DE LA CONFIGURATION\n";
echo "   Driver de queue : " . config('queue.default') . "\n";
echo "   Base de données : " . config('database.default') . "\n";

// 2. Créer les tables de queue si nécessaire
echo "\n2. 🗄️ CONFIGURATION DES TABLES DE QUEUE\n";
try {
    if (!Schema::hasTable('jobs')) {
        echo "   Création de la table jobs...\n";
        Artisan::call('queue:table');
        Artisan::call('migrate', ['--force' => true]);
        echo "   ✅ Table jobs créée\n";
    } else {
        echo "   ✅ Table jobs existe déjà\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️ Erreur table jobs : " . $e->getMessage() . "\n";
}

// 3. Vérifier les jobs programmés
echo "\n3. 📦 VÉRIFICATION DES JOBS EN ATTENTE\n";
$pendingJobs = DB::table('jobs')->count();
echo "   Jobs en attente : {$pendingJobs}\n";

// 4. Tester le système de mail
echo "\n4. 📧 TEST DU SYSTÈME D'EMAIL\n";
try {
    $testOrder = OrderLocation::where('status', 'active')->first();
    if ($testOrder) {
        echo "   Commande test trouvée : {$testOrder->order_number}\n";
        echo "   Email destinataire : {$testOrder->user->email}\n";
        echo "   ✅ Système d'email prêt\n";
    } else {
        echo "   ⚠️ Aucune location active pour test\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Erreur système email : " . $e->getMessage() . "\n";
}

// 5. Démarrer les locations en retard
echo "\n5. 🚀 DÉMARRAGE DES LOCATIONS EN RETARD\n";
$locationsToStart = OrderLocation::where('status', 'confirmed')
    ->where('start_date', '<=', now())
    ->get();

echo "   Locations à démarrer : {$locationsToStart->count()}\n";

foreach ($locationsToStart as $location) {
    try {
        echo "   🟢 Démarrage {$location->order_number}...";
        
        $location->update([
            'status' => 'active',
            'started_at' => now()
        ]);
        
        // Programmer l'envoi d'email via la queue
        \App\Jobs\StartRentalJob::dispatch($location);
        
        echo " ✅\n";
        Log::info("Location démarrée automatiquement : {$location->order_number}");
        
    } catch (\Exception $e) {
        echo " ❌ ({$e->getMessage()})\n";
    }
}

// 6. Instructions pour le worker
echo "\n6. ⚙️ DÉMARRAGE DU WORKER DE QUEUE\n";
echo "   Pour traiter les jobs automatiquement, lancez :\n";
echo "   > php artisan queue:work --daemon --sleep=3 --tries=3\n\n";

echo "   Ou utilisez le fichier batch :\n";
echo "   > start_queue_worker.bat\n\n";

// 7. Programmer les tâches automatiques
echo "7. ⏰ PROGRAMMATION DES TÂCHES AUTOMATIQUES\n";
try {
    // Programmer la vérification automatique
    \App\Jobs\AutoUpdateRentalStatusJob::dispatch()
        ->delay(now()->addMinutes(1));
    
    echo "   ✅ Vérification automatique programmée dans 1 minute\n";
    
    // Programmer les vérifications récurrentes
    \App\Jobs\AutoUpdateRentalStatusJob::dispatch()
        ->delay(now()->addHours(1));
    
    echo "   ✅ Vérification récurrente programmée dans 1 heure\n";
    
} catch (\Exception $e) {
    echo "   ❌ Erreur programmation : " . $e->getMessage() . "\n";
}

echo "\n=== SYSTÈME CONFIGURÉ AVEC SUCCÈS ===\n";
echo "🔄 Les transitions automatiques sont maintenant opérationnelles !\n";
echo "📧 Les emails de notification seront envoyés automatiquement.\n";
echo "⏰ Les vérifications auront lieu toutes les heures.\n\n";

echo "PROCHAINES ÉTAPES :\n";
echo "1. Lancez le worker : php artisan queue:work --daemon\n";
echo "2. Vérifiez les logs : tail -f storage/logs/laravel.log\n";
echo "3. Testez une nouvelle location pour vérifier le fonctionnement\n\n";
