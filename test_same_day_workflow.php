<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DU WORKFLOW LOCATION JOUR MÊME ===\n\n";

// Rechercher une location confirmed récente
$recentOrder = App\Models\OrderLocation::where('status', 'confirmed')
    ->where('created_at', '>', now()->subHour())
    ->orderBy('created_at', 'desc')
    ->first();

if ($recentOrder) {
    echo "📋 Location trouvée: {$recentOrder->order_number}\n";
    echo "Status actuel: {$recentOrder->status}\n";
    echo "Créée le: {$recentOrder->created_at}\n";
    echo "Date début: {$recentOrder->start_date}\n";
    echo "Date fin: {$recentOrder->end_date}\n";
    echo "Email utilisateur: {$recentOrder->user->email}\n\n";
    
    // Vérifier si c'est une location jour même
    if ($recentOrder->start_date->isToday()) {
        echo "✅ C'est bien une location jour même!\n";
        echo "🕒 Dans 30 secondes, le statut devrait passer à 'active'\n";
        echo "📧 L'email de confirmation a dû être envoyé\n";
        echo "📧 L'email de démarrage sera envoyé dans 30 secondes\n\n";
        
        // Vérifier les jobs en attente
        $jobs = \Illuminate\Support\Facades\DB::table('jobs')->get();
        echo "📋 Jobs en attente: " . $jobs->count() . "\n";
        
        foreach ($jobs as $job) {
            $payload = json_decode($job->payload, true);
            if (isset($payload['displayName']) && $payload['displayName'] === 'App\\Jobs\\StartRentalJob') {
                echo "   🚀 Job StartRentalJob trouvé (ID: {$job->id})\n";
                echo "   📅 Disponible après: " . date('Y-m-d H:i:s', $job->available_at) . "\n";
            }
        }
    } else {
        echo "❌ Ce n'est pas une location jour même\n";
    }
} else {
    echo "❌ Aucune location 'confirmed' récente trouvée\n";
    echo "💡 Créez d'abord une nouvelle location pour tester\n";
}

echo "\n=== WORKFLOW ATTENDU POUR LOCATION JOUR MÊME ===\n";
echo "1. ✅ Paiement Stripe → Status 'confirmed' → Email de confirmation envoyé\n";
echo "2. 🕒 Après 30 secondes → Status 'active' → Email de démarrage envoyé\n";
echo "3. 📅 A la date de fin → Status 'completed' → Email de fin envoyé\n\n";

echo "Pour tester, créez une nouvelle location pour aujourd'hui (17/08 → 17/08)\n";
