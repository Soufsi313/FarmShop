<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION DU SCHÉMA DE LA TABLE USERS ===\n\n";

try {
    // Vérifier si la table users existe
    if (!Schema::hasTable('users')) {
        echo "❌ La table 'users' n'existe pas.\n";
        exit(1);
    }
    
    echo "✅ La table 'users' existe.\n\n";
    
    // Obtenir toutes les colonnes de la table users
    $columns = Schema::getColumnListing('users');
    
    echo "📋 COLONNES DE LA TABLE USERS:\n";
    echo "==============================\n";
    foreach ($columns as $column) {
        echo "- {$column}\n";
    }
    
    echo "\n🔍 VÉRIFICATION DES COLONNES NEWSLETTER:\n";
    echo "=======================================\n";
    
    // Vérifier les colonnes spécifiques liées à la newsletter
    $newsletterColumns = [
        'newsletter_subscribed',
        'newsletter_subscription', 
        'newsletter_subscribed_at',
        'newsletter_subscription_date'
    ];
    
    foreach ($newsletterColumns as $column) {
        if (Schema::hasColumn('users', $column)) {
            echo "✅ Colonne '{$column}' existe\n";
        } else {
            echo "❌ Colonne '{$column}' n'existe pas\n";
        }
    }
    
    echo "\n📊 DÉTAILS DES COLONNES NEWSLETTER EXISTANTES:\n";
    echo "=============================================\n";
    
    // Obtenir les détails des colonnes
    $tableInfo = DB::select("DESCRIBE users");
    
    foreach ($tableInfo as $column) {
        if (str_contains($column->Field, 'newsletter')) {
            echo "🏷️  Colonne: {$column->Field}\n";
            echo "   Type: {$column->Type}\n";
            echo "   Null: {$column->Null}\n";
            echo "   Default: " . ($column->Default ?? 'NULL') . "\n";
            echo "   Extra: {$column->Extra}\n\n";
        }
    }
    
    echo "\n📈 STATISTIQUES ACTUELLES:\n";
    echo "=========================\n";
    
    // Statistiques sur les abonnés (utiliser la bonne colonne)
    $totalUsers = DB::table('users')->count();
    echo "👥 Total utilisateurs: {$totalUsers}\n";
    
    if (Schema::hasColumn('users', 'newsletter_subscribed')) {
        $subscribedUsers = DB::table('users')->where('newsletter_subscribed', true)->count();
        $unsubscribedUsers = DB::table('users')->where('newsletter_subscribed', false)->count();
        echo "✅ Abonnés newsletter: {$subscribedUsers}\n";
        echo "❌ Non abonnés: {$unsubscribedUsers}\n";
        
        if ($totalUsers > 0) {
            $subscriptionRate = round(($subscribedUsers / $totalUsers) * 100, 2);
            echo "📊 Taux d'abonnement: {$subscriptionRate}%\n";
        }
    } elseif (Schema::hasColumn('users', 'newsletter_subscription')) {
        $subscribedUsers = DB::table('users')->where('newsletter_subscription', true)->count();
        $unsubscribedUsers = DB::table('users')->where('newsletter_subscription', false)->count();
        echo "✅ Abonnés newsletter: {$subscribedUsers}\n";
        echo "❌ Non abonnés: {$unsubscribedUsers}\n";
        
        if ($totalUsers > 0) {
            $subscriptionRate = round(($subscribedUsers / $totalUsers) * 100, 2);
            echo "📊 Taux d'abonnement: {$subscriptionRate}%\n";
        }
    }
    
    echo "\n✨ Vérification terminée avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la vérification: " . $e->getMessage() . "\n";
    exit(1);
}
