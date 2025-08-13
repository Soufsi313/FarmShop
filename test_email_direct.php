<?php
require_once __DIR__ . '/vendor/autoload.php';

// Charger l'environnement Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RentalOrderConfirmed;
use App\Models\OrderLocation;

echo "🔍 Test d'envoi d'email direct pour LOC-202508132922\n";

try {
    // Récupérer la commande
    $orderLocation = OrderLocation::with(['user', 'orderItemLocations.product'])
        ->where('order_number', 'LOC-202508132922')
        ->first();
    
    if (!$orderLocation) {
        echo "❌ Commande LOC-202508132922 non trouvée\n";
        exit;
    }
    
    echo "✅ Commande trouvée: {$orderLocation->order_number}\n";
    echo "👤 Utilisateur: {$orderLocation->user->email}\n";
    echo "📧 Statut: {$orderLocation->status}\n";
    
    // Test d'envoi direct
    echo "\n📮 Envoi d'email de test...\n";
    
    Mail::to($orderLocation->user->email)->send(new RentalOrderConfirmed($orderLocation));
    
    echo "✅ Email envoyé avec succès !\n";
    
    // Vérifier les logs
    echo "\n📋 Logs récents:\n";
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        $logs = file_get_contents($logPath);
        $lines = explode("\n", $logs);
        $recentLines = array_slice($lines, -5);
        foreach ($recentLines as $line) {
            if (!empty(trim($line))) {
                echo $line . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "🔍 Trace: " . $e->getTraceAsString() . "\n";
}
