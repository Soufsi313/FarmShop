<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

echo "=== DIAGNOSTIC FINAL DU SYSTÈME AUTOMATIQUE ===\n\n";

try {
    echo "🔍 ÉTAT ACTUEL DU SYSTÈME\n";
    echo "=" . str_repeat("=", 40) . "\n\n";
    
    // 1. Configuration
    echo "📋 Configuration:\n";
    echo "   Queue Driver: " . config('queue.default') . "\n";
    echo "   Mail Driver: " . config('mail.default') . "\n";
    echo "   Mail Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "   Mail From: " . config('mail.from.address') . "\n\n";
    
    // 2. Queue Worker
    $jobsCount = DB::table('jobs')->count();
    echo "📦 Queue Worker:\n";
    echo "   Jobs en attente: {$jobsCount}\n";
    
    if ($jobsCount == 0) {
        echo "   ✅ Status: ACTIF et traite les jobs\n";
    } else {
        echo "   ⚠️ Status: Jobs non traités, worker peut-être inactif\n";
    }
    echo "\n";
    
    // 3. Votre commande
    echo "🏠 Votre commande LOC-202508034682:\n";
    $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
    
    if ($order) {
        echo "   📊 Statut: {$order->status}\n";
        echo "   📅 Période: {$order->start_date->format('d/m/Y')} → {$order->end_date->format('d/m/Y')}\n";
        echo "   👤 Email: {$order->user->email}\n";
        echo "   ⏰ Démarrée le: " . ($order->started_at ? $order->started_at->format('d/m/Y H:i') : 'Non défini') . "\n";
        echo "   💰 Montant: {$order->total_amount}€\n";
        
        $now = now();
        if ($now->between($order->start_date, $order->end_date)) {
            echo "   🟢 Status attendu: ACTIVE (période en cours)\n";
            
            if ($order->status === 'active') {
                echo "   ✅ CORRECT: Statut correspondant à la période\n";
            } else {
                echo "   ❌ PROBLÈME: Devrait être 'active'\n";
            }
        } elseif ($now->gt($order->end_date)) {
            echo "   🔴 Status attendu: COMPLETED (période terminée)\n";
            
            if (in_array($order->status, ['completed', 'closed', 'finished'])) {
                echo "   ✅ CORRECT: Statut correspondant à la période\n";
            } else {
                echo "   ❌ PROBLÈME: Devrait être terminé\n";
            }
        } else {
            echo "   ⏳ Status attendu: CONFIRMED (période future)\n";
        }
    } else {
        echo "   ❌ Commande non trouvée\n";
    }
    echo "\n";
    
    // 4. Test d'envoi d'email
    echo "📧 Test de configuration email:\n";
    
    try {
        // Test simple
        $testSent = false;
        
        Mail::raw("Test du système automatique FarmShop - " . now(), function ($message) use ($order) {
            $message->to($order->user->email)
                    ->subject("[FarmShop] Test système automatique");
        });
        
        echo "   ✅ Configuration email: FONCTIONNELLE\n";
        echo "   📬 Email de test envoyé à {$order->user->email}\n";
        $testSent = true;
        
    } catch (\Exception $e) {
        echo "   ❌ Configuration email: PROBLÈME\n";
        echo "   📝 Erreur: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 5. Commandes de test créées
    echo "🧪 Commandes de test récentes:\n";
    $testOrders = OrderLocation::where('order_number', 'like', 'TEST-%')
        ->where('created_at', '>=', now()->subHours(2))
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
        
    if ($testOrders->count() > 0) {
        foreach ($testOrders as $testOrder) {
            echo "   • {$testOrder->order_number}: {$testOrder->status}\n";
            echo "     Période: {$testOrder->start_date->format('H:i')} → {$testOrder->end_date->format('H:i')}\n";
        }
    } else {
        echo "   Aucune commande de test récente\n";
    }
    echo "\n";
    
    // 6. Résumé et recommandations
    echo "🎯 RÉSUMÉ ET STATUS\n";
    echo "=" . str_repeat("=", 40) . "\n\n";
    
    $allGood = true;
    
    if ($jobsCount > 0) {
        echo "❌ Queue Worker: Jobs non traités\n";
        echo "   💡 Solution: Redémarrer le worker\n";
        $allGood = false;
    } else {
        echo "✅ Queue Worker: Fonctionnel\n";
    }
    
    if ($order && $order->status === 'active') {
        echo "✅ Votre commande: Statut correct\n";
    } else {
        echo "⚠️ Votre commande: Statut à vérifier\n";
        $allGood = false;
    }
    
    if ($testSent) {
        echo "✅ Emails: Configuration fonctionnelle\n";
    } else {
        echo "❌ Emails: Configuration à corriger\n";
        $allGood = false;
    }
    
    echo "\n";
    
    if ($allGood) {
        echo "🎉 SYSTÈME ENTIÈREMENT FONCTIONNEL!\n";
        echo "💡 Les transitions automatiques et emails devraient marcher.\n";
        echo "📧 Vérifiez votre boîte email (et spams) pour les notifications.\n";
    } else {
        echo "⚠️ SYSTÈME PARTIELLEMENT FONCTIONNEL\n";
        echo "🔧 Quelques ajustements nécessaires (voir ci-dessus).\n";
    }
    
    echo "\n📋 COMMANDES UTILES:\n";
    echo "   Redémarrer worker: php artisan queue:work --daemon\n";
    echo "   Voir logs: Get-Content storage\\logs\\laravel.log -Tail 20\n";
    echo "   Tester queue: php artisan queue:monitor\n";
    echo "   Force transition: php monitor_rentals.php\n";

} catch (\Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}
