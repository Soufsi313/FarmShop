<?php

require_once 'vendor/autoload.php';

use App\Models\OrderLocation;
use App\Mail\RentalStartedMail;
use App\Mail\RentalEndReminderMail;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST FINAL DES TEMPLATES RÉCUPÉRÉS ===\n\n";

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
    
    // Vérifier les articles
    echo "📦 Articles (" . $orderLocation->orderItemLocations->count() . "):\n";
    foreach ($orderLocation->orderItemLocations as $item) {
        echo "   - {$item->product->name} (Qty: {$item->quantity})\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "📧 TEST TEMPLATE DÉMARRAGE DE LOCATION\n";
    echo str_repeat("=", 60) . "\n";
    
    // Test template démarrage
    $mail = new RentalStartedMail($orderLocation);
    $content = $mail->render();
    
    $filename = "test_final_rental_started.html";
    file_put_contents($filename, $content);
    
    echo "✅ Template démarrage généré: {$filename}\n";
    echo "🔍 Taille: " . strlen($content) . " caractères\n";
    
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
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "📧 TEST TEMPLATE RAPPEL FIN DE LOCATION\n";
    echo str_repeat("=", 60) . "\n";
    
    // Test template rappel (simulation avec variables manquantes)
    $data = [
        'orderLocation' => $orderLocation,
        'user' => $orderLocation->user,
        'endTomorrow' => 'Demain 18h00',
        'hoursRemaining' => 24,
        'startDate' => $orderLocation->start_date,
        'endDate' => $orderLocation->end_date,
        'totalDays' => 2,
        'items' => $orderLocation->orderItemLocations->map(function($item) {
            return (object)[
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'daily_rate' => $item->daily_price ?? 50
            ];
        })
    ];
    
    $view = view('emails.rental-end-reminder', $data);
    $reminderContent = $view->render();
    
    $filename2 = "test_final_rental_reminder.html";
    file_put_contents($filename2, $reminderContent);
    
    echo "✅ Template rappel généré: {$filename2}\n";
    echo "🔍 Taille: " . strlen($reminderContent) . " caractères\n";
    
    // Vérifier si les noms de produits apparaissent
    foreach ($orderLocation->orderItemLocations as $item) {
        if (strpos($reminderContent, $item->product->name) !== false) {
            echo "✅ Produit '{$item->product->name}' trouvé dans le template rappel\n";
        } else {
            echo "❌ Produit '{$item->product->name}' MANQUANT dans le template rappel\n";
        }
    }
    
    echo "\n🌐 Ouverture des fichiers dans le navigateur...\n";
    exec("start \"\" \"$filename\"");
    sleep(2);
    exec("start \"\" \"$filename2\"");
    
    echo "✅ Test terminé ! Les templates s'affichent maintenant dans votre navigateur.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Trace: " . $e->getTraceAsString() . "\n";
}
