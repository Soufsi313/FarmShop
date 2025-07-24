<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Jobs\ProcessOrderStatusProgression;

echo "=== Test du système de progression automatique des statuts ===\n\n";

// Récupérer une commande en attente ou créer une commande de test
$order = Order::where('status', 'pending')->first();

if (!$order) {
    echo "Aucune commande en attente trouvée.\n";
    echo "Récupération de la dernière commande pour test...\n";
    $order = Order::latest()->first();
    
    if ($order) {
        // Remettre en pending pour test
        $order->update(['status' => 'pending']);
        echo "Commande {$order->order_number} remise en statut 'pending' pour test\n";
    } else {
        echo "Aucune commande trouvée.\n";
        exit;
    }
}

echo "Commande de test: {$order->order_number} (ID: {$order->id})\n";
echo "Statut actuel: {$order->status}\n\n";

echo "=== Simulation du processus après paiement Stripe ===\n";

// 1. Paiement réussi - passage à confirmed
echo "1. Paiement réussi - Mise à jour vers 'confirmed'\n";
$order->updateStatus('confirmed');

// 2. Programmer la progression automatique
echo "2. Programmation de la progression automatique...\n";

// Pour le test, on va utiliser des délais plus courts (5 secondes au lieu de 45)
echo "   - Preparation programmée dans 5 secondes...\n";
ProcessOrderStatusProgression::dispatch($order->id, 'preparing')->delay(now()->addSeconds(5));

echo "\n=== Progression programmée ===\n";
echo "Statut actuel: {$order->fresh()->status}\n";
echo "La progression suivra automatiquement:\n";
echo "  - Dans 5 sec: confirmed → preparing\n";
echo "  - Dans 50 sec: preparing → shipped\n";
echo "  - Dans 95 sec: shipped → delivered\n\n";

echo "Pour voir la progression en temps réel, lancez dans un autre terminal:\n";
echo "php artisan queue:work --timeout=120\n\n";

echo "Pour vérifier les jobs en attente:\n";
echo "php artisan queue:monitor\n\n";

echo "=== Règles d'annulation ===\n";
echo "La commande peut être annulée tant qu'elle n'est pas expédiée (shipped)\n";
echo "Une fois expédiée, l'annulation n'est plus possible\n\n";

echo "Pour annuler cette commande avant expédition:\n";
echo "php artisan tinker --execute=\"\n";
echo "  \$order = App\\Models\\Order::find({$order->id});\n";
echo "  if (\$order->status !== 'shipped' && \$order->status !== 'delivered') {\n";
echo "    \$order->cancel('Test d annulation');\n";
echo "    echo 'Commande annulée';\n";
echo "  } else {\n";
echo "    echo 'Impossible d annuler, commande déjà expédiée';\n";
echo "  }\n";
echo "\"\n\n";

echo "=== Test prêt ===\n";
echo "Lancez le worker de queue pour voir la progression automatique.\n";
