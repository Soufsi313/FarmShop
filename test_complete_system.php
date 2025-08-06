<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Jobs\AutoUpdateRentalStatusJob;
use Illuminate\Support\Facades\DB;

echo "=== TEST COMPLET DU SYSTÈME AUTOMATIQUE ===\n\n";

try {
    // 1. Vérifier que le queue worker fonctionne
    echo "🔍 Test du queue worker...\n";
    
    // Créer un job de test
    AutoUpdateRentalStatusJob::dispatch();
    
    // Attendre un peu et vérifier si le job a été traité
    sleep(2);
    
    $jobsCount = DB::table('jobs')->count();
    echo "   Jobs en attente: {$jobsCount}\n";
    
    if ($jobsCount == 0) {
        echo "   ✅ Queue worker fonctionne - job traité!\n\n";
    } else {
        echo "   ⚠️ Queue worker peut-être non démarré - job non traité\n\n";
    }

    // 2. Tester votre commande spécifique
    echo "🏠 Test avec votre commande LOC-202508034682...\n";
    
    $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
    
    if ($order) {
        echo "   Statut actuel: {$order->status}\n";
        echo "   Date début: {$order->start_date}\n";
        echo "   Date fin: {$order->end_date}\n";
        
        // Simuler un changement de statut pour tester les événements
        if ($order->status === 'active') {
            echo "\n🧪 Test de l'événement de changement de statut...\n";
            
            // Déclencher l'événement en changeant le statut (même valeur)
            $order->update(['status' => 'active']);
            
            echo "   ✅ Événement déclenché\n";
            
            // Vérifier si des jobs ont été créés
            sleep(1);
            $newJobsCount = DB::table('jobs')->count();
            echo "   Jobs générés: {$newJobsCount}\n";
        }
    }

    // 3. Créer une commande de test pour validation complète
    echo "\n🎯 Création d'une commande de test...\n";
    
    // Récupérer un utilisateur et un produit pour le test
    $user = \App\Models\User::first();
    $product = \App\Models\Product::where('rental_stock', '>', 0)->first();
    
    if ($user && $product) {
        // Créer une commande de test avec des dates dans le futur proche
        $testOrder = OrderLocation::create([
            'user_id' => $user->id,
            'order_number' => 'TEST-' . time(),
            'status' => 'confirmed',
            'start_date' => now()->addMinutes(2), // Dans 2 minutes
            'end_date' => now()->addMinutes(5),   // Dans 5 minutes
            'rental_days' => 1,
            'total_amount' => 50.00,
            'total_deposit' => 20.00,
            'tax_amount' => 10.50,
            'payment_status' => 'paid',
            'stripe_payment_intent_id' => 'test_' . time(),
            'confirmed_at' => now()
        ]);
        
        echo "   ✅ Commande de test créée: {$testOrder->order_number}\n";
        echo "   📅 Démarrage programmé: {$testOrder->start_date}\n";
        echo "   📅 Fin programmée: {$testOrder->end_date}\n";
        
        // Programmer la vérification automatique
        AutoUpdateRentalStatusJob::dispatch();
        
        echo "\n⏰ Cette commande devrait passer automatiquement à 'active' dans 2 minutes\n";
        echo "   Surveillez les logs avec: tail -f storage/logs/laravel.log\n";
        
    } else {
        echo "   ⚠️ Impossible de créer une commande de test (utilisateur ou produit manquant)\n";
    }

    echo "\n=== INSTRUCTIONS DE SURVEILLANCE ===\n";
    echo "1. Vérifiez que le queue worker tourne :\n";
    echo "   php artisan queue:work --daemon\n\n";
    
    echo "2. Surveillez les logs en temps réel :\n";
    echo "   Get-Content storage\\logs\\laravel.log -Wait -Tail 10\n\n";
    
    echo "3. Vérifiez les jobs en cours :\n";
    echo "   php artisan queue:monitor\n\n";
    
    echo "4. Dans 3 minutes, vérifiez si la commande de test est passée à 'active'\n\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
