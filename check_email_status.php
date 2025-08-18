<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VÉRIFICATION STATUT COMMANDE ===\n\n";

// Rechercher votre commande récente
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508170236')->first();

if ($order) {
    echo "📋 Commande: {$order->order_number}\n";
    echo "Status: {$order->status}\n";
    echo "Créée: {$order->created_at}\n";
    echo "Utilisateur: {$order->user->email}\n";
    echo "Début: {$order->start_date}\n";
    echo "Fin: {$order->end_date}\n";
    echo "Started_at: " . ($order->started_at ? $order->started_at : 'NULL') . "\n\n";
    
    if ($order->status === 'confirmed') {
        echo "🔄 La commande est toujours en 'confirmed'\n";
        echo "⚡ Forçons le démarrage maintenant...\n\n";
        
        // Utiliser updateStatus pour déclencher l'event et l'email
        $order->updateStatus('active');
        
        echo "✅ Statut mis à jour vers: " . $order->fresh()->status . "\n";
        echo "📧 L'email de démarrage devrait avoir été envoyé\n";
        
    } else {
        echo "ℹ️ La commande n'est plus en 'confirmed', statut: {$order->status}\n";
    }
    
} else {
    echo "❌ Commande LOC-202508170236 non trouvée\n";
}

echo "\n=== VÉRIFICATION EMAILS DANS LES LOGS ===\n";

// Vérifier les logs récents
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $recentLines = array_slice($lines, -50); // 50 dernières lignes
    
    $emailLines = array_filter($recentLines, function($line) {
        return stripos($line, 'email') !== false || 
               stripos($line, 'mail') !== false ||
               stripos($line, 'confirmation') !== false ||
               stripos($line, 'RentalOrderConfirmed') !== false ||
               stripos($line, 'RentalStartedMail') !== false;
    });
    
    if (count($emailLines) > 0) {
        echo "📧 Logs d'emails trouvés:\n";
        foreach ($emailLines as $line) {
            echo trim($line) . "\n";
        }
    } else {
        echo "❌ Aucun log d'email trouvé dans les 50 dernières lignes\n";
    }
} else {
    echo "❌ Fichier de log non trouvé\n";
}
