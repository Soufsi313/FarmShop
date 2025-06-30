<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Méthodes utilitaires
     */
    public function getTotalPrice()
    {
        return $this->items()->sum('total_price');
    }

    public function getTotalItems()
    {
        return $this->items()->sum('quantity');
    }

    public function addItem($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        // Vérifier si l'item existe déjà
        $existingItem = $this->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            // Remplacer la quantité au lieu de l'additionner
            $existingItem->updateQuantity($quantity);
            return $existingItem;
        }

        // Créer un nouvel item
        return $this->items()->create([
            'user_id' => $this->user_id,  // Ajouter le user_id
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'total_price' => $quantity * $product->price,
        ]);
    }

    public function clear()
    {
        return $this->items()->delete();
    }

    /**
     * Méthodes statiques
     */
    public static function getActiveCartForUser($userId)
    {
        return self::active()->forUser($userId)->first() ?: self::create([
            'user_id' => $userId,
            'status' => 'active',
            'expires_at' => now()->addDays(7), // Expire dans 7 jours
        ]);
    }

    /**
     * Valider le contenu du panier
     */
    public function validateItems()
    {
        $issues = [];
        $cartItems = $this->items()->with('product')->get();

        foreach ($cartItems as $item) {
            // Vérifier si le produit existe encore
            if (!$item->product) {
                $issues[] = "Un produit dans votre panier n'existe plus.";
                continue;
            }

            // Vérifier si le produit est toujours actif
            if (!$item->product->is_active) {
                $issues[] = "Le produit '{$item->product->name}' n'est plus disponible.";
            }

            // Vérifier le stock
            if ($item->product->quantity < $item->quantity) {
                $available = $item->product->quantity;
                $issues[] = "Stock insuffisant pour '{$item->product->name}'. Disponible: {$available}, demandé: {$item->quantity}";
            }

            // Vérifier si le prix a changé
            if ($item->unit_price != $item->product->price) {
                $issues[] = "Le prix du produit '{$item->product->name}' a changé depuis son ajout au panier.";
            }
        }

        return $issues;
    }
}
