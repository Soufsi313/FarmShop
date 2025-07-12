<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartLocation;
use App\Models\CartItem;
use App\Models\CartItemLocation;
use Illuminate\Support\Facades\Log;

class ProductStockObserver
{
    /**
     * Handle the Product "updated" event.
     * Vérifie automatiquement les paniers quand le stock d'un produit change
     */
    public function updated(Product $product): void
    {
        // Vérifier si la quantité a changé
        if ($product->isDirty('quantity')) {
            $oldQuantity = $product->getOriginal('quantity');
            $newQuantity = $product->quantity;

            Log::info("Stock du produit {$product->id} modifié: {$oldQuantity} → {$newQuantity}");

            // Si le produit devient en rupture de stock
            if ($newQuantity <= 0 && $oldQuantity > 0) {
                $this->handleOutOfStockProduct($product);
            }
            // Si le stock diminue, vérifier les paniers
            elseif ($newQuantity < $oldQuantity) {
                $this->validateCartItemsStock($product, $newQuantity);
            }
        }

        // Vérifier si le produit devient inactif
        if ($product->isDirty('is_active') && !$product->is_active) {
            $this->handleInactiveProduct($product);
        }
    }

    /**
     * Gérer les produits en rupture de stock
     */
    private function handleOutOfStockProduct(Product $product): void
    {
        Log::warning("Produit {$product->id} ({$product->name}) est maintenant en rupture de stock");

        // Marquer tous les éléments de panier de ce produit comme indisponibles
        CartItem::where('product_id', $product->id)
            ->update(['is_available' => false]);

        CartItemLocation::where('product_id', $product->id)
            ->update(['is_available' => false]);

        // Optionnellement, notifier les utilisateurs concernés
        $this->notifyUsersOfOutOfStock($product);
    }

    /**
     * Gérer les produits devenus inactifs
     */
    private function handleInactiveProduct(Product $product): void
    {
        Log::warning("Produit {$product->id} ({$product->name}) est maintenant inactif");

        // Marquer tous les éléments de panier de ce produit comme indisponibles
        CartItem::where('product_id', $product->id)
            ->update(['is_available' => false]);

        CartItemLocation::where('product_id', $product->id)
            ->update(['is_available' => false]);
    }

    /**
     * Valider le stock des éléments de panier
     */
    private function validateCartItemsStock(Product $product, int $newQuantity): void
    {
        // Vérifier les paniers d'achat
        $cartItems = CartItem::where('product_id', $product->id)
            ->where('quantity', '>', $newQuantity)
            ->get();

        foreach ($cartItems as $item) {
            $item->update(['is_available' => false]);
            Log::info("Élément de panier {$item->id} marqué comme indisponible (quantité demandée: {$item->quantity}, stock: {$newQuantity})");
        }

        // Vérifier les paniers de location
        $cartLocationItems = CartItemLocation::where('product_id', $product->id)
            ->where('quantity', '>', $newQuantity)
            ->get();

        foreach ($cartLocationItems as $item) {
            $item->update(['is_available' => false]);
            Log::info("Élément de panier location {$item->id} marqué comme indisponible (quantité demandée: {$item->quantity}, stock: {$newQuantity})");
        }
    }

    /**
     * Notifier les utilisateurs concernés par la rupture de stock
     */
    private function notifyUsersOfOutOfStock(Product $product): void
    {
        // Récupérer tous les utilisateurs ayant ce produit dans leur panier
        $affectedUsers = collect();
        
        // Paniers d'achat
        $cartUsers = Cart::whereHas('items', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->with('user')->get()->pluck('user');

        // Paniers de location
        $cartLocationUsers = CartLocation::whereHas('items', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->with('user')->get()->pluck('user');

        $affectedUsers = $cartUsers->merge($cartLocationUsers)->unique('id');

        foreach ($affectedUsers as $user) {
            // Ici, vous pouvez implémenter la logique de notification
            // Par exemple, envoyer un email, créer une notification push, etc.
            Log::info("Utilisateur {$user->id} ({$user->email}) notifié de la rupture de stock du produit {$product->name}");
            
            // Exemple : créer une notification dans la base de données
            // $user->notifications()->create([
            //     'type' => 'stock_alert',
            //     'data' => [
            //         'product_id' => $product->id,
            //         'product_name' => $product->name,
            //         'message' => "Le produit '{$product->name}' dans votre panier est maintenant en rupture de stock."
            //     ]
            // ]);
        }
    }
}
