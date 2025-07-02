<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\OrderLocation;
use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test des redirections pour la gestion des locations ===\n\n";

// Vérifier qu'il y a des commandes de test
$orders = OrderLocation::with(['user', 'items.product'])->take(3)->get();

if ($orders->isEmpty()) {
    echo "❌ Aucune commande de location trouvée. Créez d'abord des données de test.\n";
    exit(1);
}

echo "✅ " . $orders->count() . " commandes de location trouvées.\n\n";

foreach ($orders as $order) {
    echo "📦 Commande #{$order->order_number}\n";
    echo "   - Client: {$order->user->name}\n";
    echo "   - Statut: {$order->status}\n";
    echo "   - Articles: " . $order->items->count() . "\n";
    echo "   - Période: du " . $order->rental_start_date->format('d/m/Y') . " au " . $order->rental_end_date->format('d/m/Y') . "\n";
    
    // Actions possibles selon le statut
    echo "   - Actions possibles: ";
    switch ($order->status) {
        case 'pending':
            echo "Confirmer, Annuler";
            break;
        case 'confirmed':
            echo "Inspecter et activer (récupération)";
            break;
        case 'active':
            echo "Inspecter et marquer comme terminé (retour), Marquer en retard";
            break;
        case 'returned':
            echo "Aucune (terminée)";
            break;
        case 'cancelled':
            echo "Aucune (annulée)";
            break;
        default:
            echo "Statut inconnu";
    }
    echo "\n\n";
}

// Vérifier la structure des contrôleurs
echo "=== Vérification des contrôleurs ===\n";

$controllerPath = __DIR__ . '/app/Http/Controllers/Admin/OrderLocationAdminController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Vérifier que les méthodes utilisent redirect() au lieu de response()->json()
    $methods = ['markAsReturned', 'confirm', 'cancel', 'markAsOverdue'];
    
    foreach ($methods as $method) {
        if (strpos($content, "public function $method") !== false) {
            $hasJson = strpos($content, 'response()->json') !== false;
            $hasRedirect = strpos($content, 'redirect()') !== false;
            
            if ($hasRedirect && !$hasJson) {
                echo "✅ Méthode $method utilise bien redirect()\n";
            } elseif ($hasJson) {
                echo "⚠️  Méthode $method utilise encore response()->json\n";
            } else {
                echo "❓ Méthode $method: statut incertain\n";
            }
        }
    }
} else {
    echo "❌ Contrôleur admin non trouvé\n";
}

echo "\n=== Conseils ===\n";
echo "1. Testez les workflows en vous connectant comme admin\n";
echo "2. Naviguez vers /admin/locations pour voir la liste des commandes\n";
echo "3. Cliquez sur une commande pour voir les détails et tester les actions\n";
echo "4. Vérifiez que les redirections se font bien vers les bonnes pages\n";
echo "5. Assurez-vous que les messages de succès/erreur s'affichent\n\n";

echo "✅ Test terminé !\n";
