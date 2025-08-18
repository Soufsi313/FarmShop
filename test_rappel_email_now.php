<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\OrderLocation;
use App\Models\User;
use App\Mail\RentalEndReminderMail;
use Illuminate\Support\Facades\Mail;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST D'AFFICHAGE EMAIL DE RAPPEL FIN DE LOCATION ===\n\n";

try {
    // Récupérer une commande de location active
    $orderLocation = OrderLocation::with(['user', 'items.product'])
        ->whereIn('status', ['started', 'in_progress'])
        ->first();
    
    if (!$orderLocation) {
        echo "❌ Aucune location active trouvée. Création d'une location de test...\n";
        
        // Chercher n'importe quelle commande de location
        $orderLocation = OrderLocation::with(['user', 'items.product'])->first();
        
        if (!$orderLocation) {
            echo "❌ Aucune commande de location trouvée dans la base de données.\n";
            exit;
        }
        
        // Modifier temporairement pour le test
        $orderLocation->status = 'started';
        $orderLocation->start_date = now()->subHours(6);  // Commencée il y a 6h
        $orderLocation->end_date = now()->addHours(8);    // Finit dans 8h
        echo "✅ Location de test configurée (finit dans 8h)\n";
    }
    
    echo "✅ Location trouvée: #{$orderLocation->id}\n";
    echo "👤 Client: {$orderLocation->user->first_name} {$orderLocation->user->last_name}\n";
    echo "📧 Email: {$orderLocation->user->email}\n";
    echo "📅 Début: " . \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y H:i') . "\n";
    echo "📅 Fin: " . \Carbon\Carbon::parse($orderLocation->end_date)->format('d/m/Y H:i') . "\n";
    
    $hoursRemaining = \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($orderLocation->end_date));
    echo "⏰ Temps restant: {$hoursRemaining}h\n";
    
    if ($hoursRemaining <= 3) {
        echo "🚨 Type de rappel: URGENT (≤3h)\n";
    } elseif ($hoursRemaining <= 12) {
        echo "⚠️  Type de rappel: WARNING (3-12h)\n";
    } else {
        echo "📅 Type de rappel: INFO (>12h)\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "📧 GÉNÉRATION DE L'EMAIL DE RAPPEL\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Créer l'email de rappel
    $mail = new RentalEndReminderMail($orderLocation);
    
    // Générer le contenu HTML
    $content = $mail->render();
    
    // Sauvegarder dans un fichier pour visualisation
    $filename = "test_rappel_email_" . date('Y-m-d_H-i-s') . ".html";
    file_put_contents($filename, $content);
    
    echo "✅ Email généré avec succès !\n";
    echo "📄 Fichier créé: {$filename}\n";
    echo "🔍 Taille du contenu: " . strlen($content) . " caractères\n\n";
    
    // Afficher un aperçu du contenu
    $preview = strip_tags($content);
    $preview = substr($preview, 0, 300);
    echo "👀 Aperçu du contenu:\n";
    echo str_repeat("-", 40) . "\n";
    echo trim($preview) . "...\n";
    echo str_repeat("-", 40) . "\n\n";
    
    // Test d'envoi réel (optionnel)
    echo "📮 Test d'envoi d'email...\n";
    
    try {
        // Configurer Mail pour utiliser log driver temporairement
        config(['mail.default' => 'log']);
        
        Mail::to($orderLocation->user->email)->send($mail);
        echo "✅ Email envoyé avec succès (log) !\n";
        
        // Vérifier le fichier de log
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $lastLogEntries = explode("\n", trim($logContent));
            $recentLogs = array_slice($lastLogEntries, -5);
            
            echo "📝 Dernières entrées du log:\n";
            foreach ($recentLogs as $log) {
                if (strpos($log, 'Message-ID') !== false || strpos($log, 'Subject') !== false) {
                    echo "   " . trim($log) . "\n";
                }
            }
        }
        
    } catch (Exception $e) {
        echo "⚠️  Erreur d'envoi: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🌐 OUVERTURE DU FICHIER DANS LE NAVIGATEUR\n";
    echo str_repeat("=", 60) . "\n";
    
    // Ouvrir le fichier dans le navigateur (Windows)
    $fullPath = realpath($filename);
    echo "🔗 Chemin complet: {$fullPath}\n";
    echo "🌐 Ouverture automatique dans le navigateur...\n";
    
    // Commande pour ouvrir dans le navigateur par défaut
    $command = "start \"\" \"$fullPath\"";
    exec($command);
    
    echo "\n✅ Test terminé ! L'email de rappel s'affiche maintenant dans votre navigateur.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}
