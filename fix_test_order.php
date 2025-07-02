<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Finaliser la commande test pour l'inspection ===\n\n";

// Récupérer la commande test
$order = OrderLocation::where('order_number', 'LOC-AUTO-TEST-1751441424')->first();

if (!$order) {
    echo "❌ Commande non trouvée.\n";
    exit(1);
}

echo "📦 Commande trouvée: {$order->order_number}\n";
echo "   - Statut actuel: {$order->status}\n";
echo "   - Date clôture client: " . ($order->client_return_date ? $order->client_return_date : 'Non définie') . "\n";

// Simuler la clôture par le client si pas encore fait
if (!$order->client_return_date) {
    $order->update([
        'client_return_date' => now(),
        'client_notes' => 'Matériel retourné en bon état. Tout s\'est bien passé. Merci pour le service !'
    ]);
    
    echo "✅ Date de clôture client ajoutée: " . now()->format('d/m/Y H:i') . "\n";
    echo "✅ Notes client ajoutées\n";
} else {
    echo "✅ Commande déjà clôturée par le client\n";
}

echo "\n=== Test des URLs ===\n";
echo "URL détail: http://127.0.0.1:8000/admin/locations/{$order->id}\n";
echo "URL inspection: http://127.0.0.1:8000/admin/locations/{$order->id}/return\n";
echo "Dashboard admin: http://127.0.0.1:8000/admin/locations/dashboard\n\n";

echo "✅ Commande prête pour l'inspection !\n";
echo "   - Le bouton 'Procéder à l'inspection' devrait maintenant fonctionner\n";
echo "   - La page d'inspection devrait afficher les notes du client\n";
echo "   - L'admin peut finaliser le retour\n";
