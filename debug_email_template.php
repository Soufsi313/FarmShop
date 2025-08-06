<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;
use App\Mail\RentalOrderInspection;

// Test de comparaison des templates
$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    echo "=== Test de template comparaison ===\n";
    
    // Créer l'instance de Mail
    $mail = new RentalOrderInspection($order);
    
    // Vérifier la configuration
    $envelope = $mail->envelope();
    $content = $mail->content();
    
    echo "Sujet: " . $envelope->subject . "\n";
    echo "From: " . json_encode($envelope->from) . "\n";
    echo "Template utilisé: " . $content->view . "\n";
    
    // Rendre le template directement
    try {
        $renderedContent = view($content->view, $content->with)->render();
        
        // Vérifier si c'est l'ancien ou le nouveau template
        if (strpos($renderedContent, '@component') !== false) {
            echo "❌ ANCIEN TEMPLATE DETECTÉ (utilise @component)\n";
        } elseif (strpos($renderedContent, 'FarmShop') !== false && strpos($renderedContent, 'gradient') !== false) {
            echo "✅ NOUVEAU TEMPLATE DETECTÉ (FarmShop personnalisé)\n";
        } else {
            echo "⚠️ Template non identifié\n";
        }
        
        // Sauvegarder pour vérification
        file_put_contents('debug_email_content.html', $renderedContent);
        echo "Contenu sauvegardé dans debug_email_content.html\n";
        
    } catch (Exception $e) {
        echo "Erreur lors du rendu: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Commande non trouvée\n";
}
