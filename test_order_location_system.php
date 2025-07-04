<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\CartLocation;
use App\Models\OrderLocation;
use App\Models\User;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DU SYSTÈME DE COMMANDES DE LOCATION ===\n\n";

try {
    // 1. Vérifier qu'un utilisateur existe
    $user = User::first();
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé dans la base de données\n";
        exit(1);
    }
    echo "✅ Utilisateur trouvé: {$user->name} (ID: {$user->id})\n";

    // 2. Vérifier qu'un panier de location existe
    $cart = CartLocation::where('user_id', $user->id)
        ->where('status', 'active')
        ->with('items.product')
        ->first();
    
    if (!$cart) {
        echo "❌ Aucun panier de location actif trouvé pour cet utilisateur\n";
        echo "💡 Créez un panier avec des articles depuis l'interface web d'abord\n";
        exit(1);
    }
    
    echo "✅ Panier de location trouvé (ID: {$cart->id})\n";
    echo "   - Nombre d'articles: " . $cart->items->count() . "\n";
    echo "   - Total: {$cart->total_amount}€\n";
    echo "   - Caution: {$cart->total_deposit}€\n";

    // 3. Valider le panier
    $validation = $cart->validate();
    if (!$validation['valid']) {
        echo "❌ Panier non valide:\n";
        foreach ($validation['errors'] as $error) {
            echo "   - $error\n";
        }
        exit(1);
    }
    echo "✅ Panier valide\n";

    // 4. Créer une commande de location
    echo "\n--- CRÉATION DE LA COMMANDE ---\n";
    
    $order = OrderLocation::createFromCart($cart);
    echo "✅ Commande créée: {$order->order_number}\n";
    echo "   - ID: {$order->id}\n";
    echo "   - Statut: {$order->status} ({$order->status_label})\n";
    echo "   - Total: {$order->total_amount}€\n";
    echo "   - Caution: {$order->deposit_amount}€\n";
    echo "   - Du: " . $order->rental_start_date->format('Y-m-d') . "\n";
    echo "   - Au: " . $order->rental_end_date->format('Y-m-d') . "\n";
    echo "   - Durée: {$order->duration_days} jours\n";

    // 5. Vérifier les articles de la commande
    $orderItems = $order->items;
    echo "\n--- ARTICLES DE LA COMMANDE ---\n";
    foreach ($orderItems as $item) {
        echo "✅ Article: {$item->product_name}\n";
        echo "   - Prix/jour: {$item->rental_price_per_day}€\n";
        echo "   - Durée: {$item->duration_days} jours\n";
        echo "   - Sous-total: {$item->subtotal}€\n";
        echo "   - Caution: {$item->deposit_amount}€\n";
        echo "   - Total avec caution: {$item->total_with_deposit}€\n";
    }

    // 6. Marquer le panier comme soumis
    $cart->submit();
    echo "\n✅ Panier marqué comme soumis\n";

    // 7. Tester quelques méthodes de l'ordre
    echo "\n--- TESTS DES MÉTHODES ---\n";
    echo "✅ Peut être annulée: " . ($order->can_be_cancelled ? 'Oui' : 'Non') . "\n";
    echo "✅ Peut être récupérée: " . ($order->can_be_picked_up ? 'Oui' : 'Non') . "\n";
    echo "✅ Peut être retournée: " . ($order->can_be_returned ? 'Oui' : 'Non') . "\n";
    echo "✅ En retard: " . ($order->is_overdue ? 'Oui' : 'Non') . "\n";
    echo "✅ Montant restant: {$order->remaining_amount}€\n";

    // 8. Test de confirmation
    echo "\n--- TEST DE CONFIRMATION ---\n";
    if ($order->confirm()) {
        echo "✅ Commande confirmée avec succès\n";
        echo "   - Nouveau statut: {$order->status} ({$order->status_label})\n";
        echo "   - Confirmée le: " . $order->confirmed_at->format('Y-m-d H:i:s') . "\n";
    } else {
        echo "❌ Impossible de confirmer la commande\n";
    }

    echo "\n=== TEST TERMINÉ AVEC SUCCÈS ===\n";
    echo "💡 Vous pouvez maintenant tester l'interface web pour voir la commande\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
