<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DIAGNOSTIC INSPECTION LOC-TEST-001\n";
echo "====================================\n\n";

// Récupérer la location LOC-TEST-001
$orderLocation = DB::table('order_locations')
    ->where('order_number', 'LOC-TEST-001-20250805')
    ->first();

if (!$orderLocation) {
    echo "❌ Location LOC-TEST-001-20250805 non trouvée\n";
    exit(1);
}

echo "📋 STATUT ACTUEL :\n";
echo "   Numéro : {$orderLocation->order_number}\n";
echo "   Statut : {$orderLocation->status}\n";
echo "   Statut inspection : " . ($orderLocation->inspection_status ?? 'NULL') . "\n";
echo "   Terminée le : " . ($orderLocation->inspection_finished_at ?? 'NULL') . "\n";
echo "   Pénalités : " . ($orderLocation->penalty_amount ?? '0') . "€\n";
echo "   Remboursement : " . ($orderLocation->deposit_refund ?? '0') . "€\n\n";

// Vérifier les messages Mr Clank
echo "📨 MESSAGES MR CLANK :\n";
$messages = DB::table('messages')
    ->where('user_id', $orderLocation->user_id)
    ->where('subject', 'like', '%' . $orderLocation->order_number . '%')
    ->orderBy('created_at', 'desc')
    ->get();

if ($messages->count() > 0) {
    foreach ($messages as $message) {
        echo "   ✅ Message trouvé (ID: {$message->id})\n";
        echo "      Sujet : {$message->subject}\n";
        echo "      Envoyé le : {$message->sent_at}\n";
        echo "      Système : " . ($message->is_system ? 'OUI' : 'NON') . "\n\n";
    }
} else {
    echo "   ❌ Aucun message Mr Clank trouvé\n\n";
}

// Vérifier les logs récents
echo "📝 LOGS RÉCENTS (dernière heure) :\n";
$logFile = storage_path('logs/laravel.log');

if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $lines = explode("\n", $logs);
    $recentLogs = [];
    
    $oneHourAgo = now()->subHour();
    
    foreach (array_reverse($lines) as $line) {
        if (strpos($line, $orderLocation->order_number) !== false || 
            strpos($line, 'Mr Clank') !== false ||
            strpos($line, 'RentalOrderInspection') !== false ||
            strpos($line, 'inspection') !== false) {
            $recentLogs[] = $line;
        }
        
        if (count($recentLogs) >= 10) break;
    }
    
    if (count($recentLogs) > 0) {
        foreach ($recentLogs as $log) {
            echo "   📄 " . trim($log) . "\n";
        }
    } else {
        echo "   ❌ Aucun log trouvé pour cette location\n";
    }
} else {
    echo "   ❌ Fichier de log non trouvé\n";
}

echo "\n🔧 TESTS DE FONCTIONNEMENT :\n";

// Test 1 : Vérifier la configuration email
echo "1. Configuration email :\n";
echo "   MAIL_MAILER : " . config('mail.default') . "\n";
echo "   MAIL_HOST : " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_FROM : " . config('mail.from.address') . "\n\n";

// Test 2 : Vérifier la queue
echo "2. Jobs en queue :\n";
$queueJobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();
echo "   Jobs en attente : {$queueJobs}\n";
echo "   Jobs échoués : {$failedJobs}\n\n";

// Test 3 : Vérifier si l'événement se déclenche
echo "3. Test manuel d'envoi d'email :\n";
try {
    $orderLocationModel = \App\Models\OrderLocation::find($orderLocation->id);
    
    echo "   Tentative d'envoi email d'inspection...\n";
    Mail::to($orderLocationModel->user->email)->send(new \App\Mail\RentalOrderInspection($orderLocationModel));
    echo "   ✅ Email d'inspection envoyé avec succès\n";
    
} catch (\Exception $e) {
    echo "   ❌ Erreur envoi email : " . $e->getMessage() . "\n";
}

echo "\n4. Test création message Mr Clank :\n";
try {
    $messageId = DB::table('messages')->insertGetId([
        'user_id' => $orderLocation->user_id,
        'sender_id' => 1,
        'type' => 'system',
        'subject' => "🤖 TEST - Location #{$orderLocation->order_number} diagnostic",
        'content' => "Test de diagnostic des notifications",
        'status' => 'unread',
        'priority' => 'normal',
        'is_important' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    echo "   ✅ Message test créé (ID: {$messageId})\n";
} catch (\Exception $e) {
    echo "   ❌ Erreur création message : " . $e->getMessage() . "\n";
}

echo "\n📊 RÉSUMÉ DU DIAGNOSTIC :\n";
echo "========================\n";
if ($orderLocation->status === 'finished') {
    echo "✅ Statut correct : finished\n";
} else {
    echo "❌ Statut incorrect : {$orderLocation->status} (devrait être 'finished')\n";
}

if ($messages->count() > 0) {
    echo "✅ Message(s) Mr Clank trouvé(s)\n";
} else {
    echo "❌ Aucun message Mr Clank\n";
}

echo "\n🔧 SOLUTION RECOMMANDÉE :\n";
echo "==========================\n";
echo "1. Vérifiez que le worker de queue fonctionne : php artisan queue:work\n";
echo "2. Vérifiez les logs d'erreur complets\n";
echo "3. Testez l'envoi d'email manuel ci-dessus\n";
echo "4. Vérifiez votre boîte de réception pour le message test\n";
