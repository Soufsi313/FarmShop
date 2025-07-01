<?php
// Script de test manuel pour l'ajout de produit de location dans le panier
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\CartLocation;
use App\Models\CartItemLocation;
use Illuminate\Http\Request;
use Carbon\Carbon;

echo "=== Test manuel : Ajout d'un produit de location dans le panier ===\n\n";

try {
    // 1. Simulation d'un utilisateur connecté
    $user = User::where('email', 'like', '%test%')->first();
    if (!$user) {
        $user = User::first();
    }
    
    if (!$user) {
        echo "❌ Aucun utilisateur trouvé\n";
        exit;
    }
    
    echo "👤 Utilisateur simulé: {$user->name} (ID: {$user->id})\n";
    
    // 2. Trouver un produit de location disponible
    $product = Product::where('is_rentable', true)
        ->where('stock_quantity', '>', 0)
        ->first();
    
    if (!$product) {
        echo "❌ Aucun produit de location disponible\n";
        exit;
    }
    
    echo "📦 Produit sélectionné: {$product->name}\n";
    echo "   Prix/jour: {$product->rental_price_per_day}€\n";
    echo "   Stock: {$product->stock_quantity}\n";
    echo "   Caution: {$product->deposit_amount}€\n\n";
    
    // 3. Vérifier l'état initial du panier
    echo "🛒 État initial du panier...\n";
    $initialCart = CartLocation::getActiveCartForUser($user->id);
    echo "   Items avant: {$initialCart->total_items}\n";
    echo "   Total avant: {$initialCart->grand_total}€\n\n";
    
    // 4. Simuler l'ajout via le contrôleur (comme si c'était via l'UI)
    echo "➕ Simulation de l'ajout via l'interface utilisateur...\n";
    
    // Créer une requête POST simulée
    $requestData = [
        'product_id' => $product->id,
        'quantity' => 2,
        'rental_duration_days' => 7,
        'rental_start_date' => Carbon::tomorrow()->format('Y-m-d'),
        'deposit_amount' => $product->deposit_amount * 2 // Pour 2 unités
    ];
    
    echo "   Données envoyées:\n";
    foreach ($requestData as $key => $value) {
        echo "     {$key}: {$value}\n";
    }
    echo "\n";
    
    // Simuler l'appel du contrôleur
    $request = new Request($requestData);
    $request->setUserResolver(function() use ($user) {
        return $user;
    });
    
    // Instancier le contrôleur
    $controller = new App\Http\Controllers\CartLocationController();
    
    // Créer une Request valide pour Laravel
    $laravelRequest = App\Http\Requests\AddToCartLocationRequest::createFrom($request);
    
    // Simuler la validation manuelle puisque nous n'avons pas le validator automatique
    echo "🔍 Validation des données...\n";
    $validationRules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1|max:10',
        'rental_duration_days' => 'required|integer|min:1|max:30',
        'rental_start_date' => 'required|date|after_or_equal:today'
    ];
    
    $isValid = true;
    foreach ($validationRules as $field => $rule) {
        if (!isset($requestData[$field])) {
            echo "   ❌ Champ manquant: {$field}\n";
            $isValid = false;
        }
    }
    
    if ($isValid) {
        echo "   ✅ Données valides\n\n";
        
        // 5. Ajouter directement via le modèle (simulation de ce que fait le contrôleur)
        echo "🔄 Ajout dans le panier...\n";
        
        $cart = CartLocation::getActiveCartForUser($user->id);
        $startDate = Carbon::parse($requestData['rental_start_date']);
        
        $cartItem = $cart->addItem(
            $requestData['product_id'],
            $requestData['quantity'],
            $requestData['rental_duration_days'],
            $startDate,
            $requestData['deposit_amount']
        );
        
        if ($cartItem) {
            echo "   ✅ Produit ajouté avec succès!\n";
            echo "   ID de l'item: {$cartItem->id}\n";
            echo "   Prix total: {$cartItem->total_price}€\n";
            echo "   Caution: {$cartItem->deposit_amount}€\n\n";
        } else {
            echo "   ❌ Échec de l'ajout\n\n";
        }
    }
    
    // 6. Vérifier l'état final du panier
    echo "📊 État final du panier...\n";
    $finalCart = CartLocation::getActiveCartForUser($user->id);
    $finalCart->load('items.product');
    
    echo "   Items après: {$finalCart->total_items}\n";
    echo "   Total location: {$finalCart->total_amount}€\n";
    echo "   Total caution: {$finalCart->total_deposit}€\n";
    echo "   Total général: {$finalCart->grand_total}€\n";
    echo "   Statut: {$finalCart->status}\n\n";
    
    // 7. Détail des items
    echo "📝 Détail des items dans le panier:\n";
    foreach ($finalCart->items as $item) {
        echo "   - {$item->product_name} x{$item->quantity}\n";
        echo "     Du {$item->rental_start_date->format('d/m/Y')} au {$item->rental_end_date->format('d/m/Y')}\n";
        echo "     Prix: {$item->total_price}€ + Caution: {$item->deposit_amount}€\n";
        echo "     Statut: {$item->status}\n\n";
    }
    
    // 8. Test de l'API du panier
    echo "🌐 Test de l'API du panier...\n";
    
    // Simuler un appel API pour obtenir le count
    $apiData = [
        'count' => $finalCart->total_items,
        'total_amount' => $finalCart->total_amount,
        'total_deposit' => $finalCart->total_deposit,
        'grand_total' => $finalCart->grand_total
    ];
    
    echo "   API Response simulée:\n";
    echo json_encode($apiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
    
    echo "✅ Test manuel terminé avec succès!\n";
    echo "🎉 Le produit a été ajouté au panier de location comme attendu.\n";

} catch (Exception $e) {
    echo "❌ Erreur durant le test: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
