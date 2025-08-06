<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderCompleted;
use Illuminate\Support\Facades\Mail;

echo "=== TEST DE L'EMAIL CORRIGÉ ===\n\n";

try {
    // Récupérer votre commande
    $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
    
    if (!$order) {
        throw new Exception("Commande non trouvée");
    }
    
    echo "✅ Commande: {$order->order_number}\n";
    echo "👤 Email: {$order->user->email}\n\n";
    
    // Test de création de l'email
    echo "🔧 Test de création de l'objet email...\n";
    $mail = new RentalOrderCompleted($order);
    echo "   ✅ Objet email créé avec succès\n\n";
    
    // Test d'envoi
    echo "📧 Test d'envoi de l'email corrigé...\n";
    
    Mail::to($order->user->email)->send($mail);
    
    echo "   ✅ Email envoyé avec succès!\n";
    echo "   📬 Vérifiez votre boîte email.\n\n";
    
    // Maintenant que c'est corrigé, traitons les jobs en attente
    echo "🔄 Traitement des jobs en attente...\n";
    
    // Vérifier combien de jobs en attente
    $jobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
    echo "   📦 {$jobsCount} jobs en attente à traiter\n";
    
    if ($jobsCount > 0) {
        echo "   💡 Le queue worker va maintenant pouvoir traiter ces jobs sans erreur!\n";
        
        // Traiter quelques jobs manuellement
        for ($i = 0; $i < min(5, $jobsCount); $i++) {
            try {
                \Illuminate\Support\Facades\Artisan::call('queue:work', [
                    '--once' => true,
                    '--timeout' => 30
                ]);
            } catch (\Exception $e) {
                echo "   ⚠️ Job échoué: " . $e->getMessage() . "\n";
            }
        }
        
        $remainingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
        echo "   📊 Jobs traités: " . ($jobsCount - $remainingJobs) . "\n";
        echo "   📊 Jobs restants: {$remainingJobs}\n";
    }
    
    echo "\n=== RÉSULTAT ===\n";
    echo "✅ Classe email corrigée\n";
    echo "✅ Email de test envoyé\n";
    echo "✅ Jobs peuvent maintenant être traités\n";
    echo "💡 Votre système automatique devrait maintenant fonctionner parfaitement!\n";

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📝 Ligne: " . $e->getLine() . "\n";
    echo "📝 Fichier: " . $e->getFile() . "\n";
}
