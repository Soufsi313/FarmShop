<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OrderLocation;

// Créer une route de prévisualisation de l'email
$order = OrderLocation::where('order_number', 'LOC-MANUAL-001-1754417155')->first();

if ($order) {
    // Rendre le template email directement pour prévisualisation
    $view = view('emails.inspection-report', ['orderLocation' => $order]);
    
    // Sauvegarder en HTML pour prévisualisation
    file_put_contents('preview_inspection_email.html', $view->render());
    
    echo "✅ Prévisualisation de l'email sauvegardée dans 'preview_inspection_email.html'\n";
    echo "Vous pouvez ouvrir ce fichier dans votre navigateur pour voir le rendu final.\n";
    
    echo "\n=== Détails de l'email ===\n";
    echo "Destinataire: " . $order->user->email . "\n";
    echo "Expéditeur: s.mef2703@gmail.com (FarmShop)\n";
    echo "Sujet: 📋 Rapport d'inspection - Location #" . $order->order_number . " - FarmShop\n";
    
} else {
    echo "❌ Commande non trouvée\n";
}
