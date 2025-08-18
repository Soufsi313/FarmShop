<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use App\Mail\RentalOrderCompleted;
use Illuminate\Support\Facades\Mail;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST EMAIL LOCATION TERMINÉE ===\n\n";

try {
    // Récupérer une commande de location avec ses relations
    $orderLocation = OrderLocation::with(['user', 'orderItemLocations', 'orderItemLocations.product'])->first();
    
    if (!$orderLocation) {
        echo "❌ Aucune commande de location trouvée.\n";
        exit;
    }
    
    echo "✅ Location trouvée: #{$orderLocation->id}\n";
    echo "👤 Client: {$orderLocation->user->first_name} {$orderLocation->user->last_name}\n";
    echo "📧 Email: {$orderLocation->user->email}\n";
    echo "📅 Commande: #{$orderLocation->order_number}\n";
    
    // Afficher les articles
    echo "📦 Articles (" . $orderLocation->orderItemLocations->count() . "):\n";
    foreach ($orderLocation->orderItemLocations as $item) {
        echo "   - {$item->product->name} (Qty: {$item->quantity})\n";
        echo "     Prix/jour: " . number_format($item->daily_price ?? 50, 2) . "€\n";
    }
    
    // Simuler une location terminée (modifier temporairement les dates)
    $originalStartDate = $orderLocation->start_date;
    $originalEndDate = $orderLocation->end_date;
    $originalStatus = $orderLocation->status;
    
    // Mettre des dates passées pour simulation
    $orderLocation->start_date = now()->subDays(3);
    $orderLocation->end_date = now()->subDays(1);  // Terminée hier
    $orderLocation->status = 'completed';
    
    echo "\n📅 Dates de test:\n";
    echo "   Début: " . $orderLocation->start_date->format('d/m/Y H:i') . "\n";
    echo "   Fin: " . $orderLocation->end_date->format('d/m/Y H:i') . "\n";
    echo "   Statut: {$orderLocation->status}\n";
    
    $plannedDays = $orderLocation->start_date->diffInDays($orderLocation->end_date);
    $actualDays = $orderLocation->start_date->diffInDays(now());
    $lateDays = max(0, $actualDays - $plannedDays);
    
    echo "   Durée prévue: {$plannedDays} jour(s)\n";
    echo "   Durée réelle: {$actualDays} jour(s)\n";
    if ($lateDays > 0) {
        echo "   ⚠️  Retard: {$lateDays} jour(s) (frais: " . ($lateDays * 10) . "€)\n";
    } else {
        echo "   ✅ Pas de retard\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "📧 GÉNÉRATION DE L'EMAIL DE LOCATION TERMINÉE\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Créer l'email de location terminée
    $mail = new RentalOrderCompleted($orderLocation);
    
    // Générer le contenu HTML
    $content = $mail->render();
    
    // Sauvegarder dans un fichier pour visualisation
    $filename = "test_location_terminee_" . date('Y-m-d_H-i-s') . ".html";
    file_put_contents($filename, $content);
    
    echo "✅ Email généré avec succès !\n";
    echo "📄 Fichier créé: {$filename}\n";
    echo "🔍 Taille du contenu: " . strlen($content) . " caractères\n\n";
    
    // Vérifier si les noms de produits apparaissent
    $hasProductNames = false;
    foreach ($orderLocation->orderItemLocations as $item) {
        if (strpos($content, $item->product->name) !== false) {
            $hasProductNames = true;
            echo "✅ Produit '{$item->product->name}' trouvé dans le template\n";
        } else {
            echo "❌ Produit '{$item->product->name}' MANQUANT dans le template\n";
        }
    }
    
    // Afficher un aperçu du contenu
    $preview = strip_tags($content);
    $preview = substr($preview, 0, 300);
    echo "\n👀 Aperçu du contenu:\n";
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
    
    // Restaurer les valeurs originales
    $orderLocation->start_date = $originalStartDate;
    $orderLocation->end_date = $originalEndDate;
    $orderLocation->status = $originalStatus;
    
    echo "\n✅ Test terminé ! L'email de location terminée s'affiche maintenant dans votre navigateur.\n";
    echo "📝 Note: Les dates ont été restaurées à leurs valeurs originales.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}
