<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MISE À JOUR DES DATES D'ABONNEMENT NEWSLETTER ===\n\n";

try {
    // Récupérer tous les utilisateurs abonnés sans date d'abonnement
    $subscribedUsersWithoutDate = DB::table('users')
        ->where('newsletter_subscribed', true)
        ->whereNull('newsletter_subscribed_at')
        ->get();
    
    echo "👥 Utilisateurs abonnés sans date: {$subscribedUsersWithoutDate->count()}\n";
    
    if ($subscribedUsersWithoutDate->count() > 0) {
        echo "🔄 Mise à jour en cours...\n";
        
        // Mettre à jour avec la date de création du compte ou maintenant
        foreach ($subscribedUsersWithoutDate as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'newsletter_subscribed_at' => $user->created_at ?? now()
                ]);
        }
        
        echo "✅ {$subscribedUsersWithoutDate->count()} utilisateurs mis à jour avec succès!\n";
    } else {
        echo "ℹ️  Aucune mise à jour nécessaire.\n";
    }
    
    echo "\n📊 NOUVELLES STATISTIQUES:\n";
    echo "=========================\n";
    
    $totalUsers = DB::table('users')->count();
    $subscribedUsers = DB::table('users')->where('newsletter_subscribed', true)->count();
    $subscribedWithDate = DB::table('users')
        ->where('newsletter_subscribed', true)
        ->whereNotNull('newsletter_subscribed_at')
        ->count();
    
    echo "👥 Total utilisateurs: {$totalUsers}\n";
    echo "✅ Abonnés newsletter: {$subscribedUsers}\n";
    echo "📅 Abonnés avec date: {$subscribedWithDate}\n";
    
    if ($subscribedUsers > 0) {
        $subscriptionRate = round(($subscribedUsers / $totalUsers) * 100, 2);
        echo "📊 Taux d'abonnement: {$subscriptionRate}%\n";
    }
    
    // Afficher quelques exemples
    echo "\n📋 EXEMPLES D'ABONNÉS RÉCENTS:\n";
    echo "=============================\n";
    
    $recentSubscribers = DB::table('users')
        ->where('newsletter_subscribed', true)
        ->whereNotNull('newsletter_subscribed_at')
        ->orderBy('newsletter_subscribed_at', 'desc')
        ->limit(5)
        ->get(['name', 'email', 'newsletter_subscribed_at']);
    
    foreach ($recentSubscribers as $subscriber) {
        echo "📧 {$subscriber->name} ({$subscriber->email}) - {$subscriber->newsletter_subscribed_at}\n";
    }
    
    echo "\n✨ Mise à jour terminée avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la mise à jour: " . $e->getMessage() . "\n";
    exit(1);
}
