<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            // Calculer automatiquement le prix total lors de la création
            $cart->calculateTotalPrice();
        });

        static::updating(function ($cart) {
            // Calculer automatiquement le prix total lors de la mise à jour
            $cart->calculateTotalPrice();
        });
    }

    /**
     * Calcul automatique du prix total
     */
    public function calculateTotalPrice()
    {
        // Si c'est un prix en vrac, utiliser la méthode du produit
        if ($this->product && $this->product->hasBulkPricing()) {
            $this->total_price = $this->product->getBulkPrice($this->quantity);
        } else {
            $this->total_price = $this->unit_price * $this->quantity;
        }
    }

    /**
     * Vérifier si le produit est disponible en stock suffisant
     */
    public function hasStockAvailable()
    {
        return $this->product->quantity >= $this->quantity;
    }

    /**
     * Vérifier si le produit est encore actif
     */
    public function isProductActive()
    {
        return $this->product->is_active;
    }

    /**
     * Obtenir les informations du produit pour l'affichage
     */
    public function getProductInfoAttribute()
    {
        return [
            'name' => $this->product->name,
            'category' => $this->product->category->name,
            'unit_symbol' => $this->product->unit_symbol,
            'image' => $this->product->main_image_url,
            'available_stock' => $this->product->quantity,
            'is_active' => $this->product->is_active,
        ];
    }

    /**
     * Scopes
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithValidProducts($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('is_active', true);
        });
    }

    public function scopeWithAvailableStock($query)
    {
        return $query->whereRaw('quantity <= (SELECT quantity FROM products WHERE products.id = carts.product_id)');
    }

    /**
     * Méthodes utilitaires
     */
    public function updateQuantity($newQuantity)
    {
        // Vérifier si le stock est suffisant
        if ($this->product->quantity < $newQuantity) {
            throw new \Exception("Stock insuffisant. Stock disponible: {$this->product->quantity}");
        }

        $this->quantity = $newQuantity;
        $this->calculateTotalPrice();
        $this->save();

        return $this;
    }

    public function incrementQuantity($amount = 1)
    {
        return $this->updateQuantity($this->quantity + $amount);
    }

    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(1, $this->quantity - $amount);
        return $this->updateQuantity($newQuantity);
    }

    /**
     * Méthodes statiques pour la gestion du panier
     */
    public static function addToCart($userId, $productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);

        // Vérifier si le produit est actif
        if (!$product->is_active) {
            throw new \Exception('Ce produit n\'est plus disponible.');
        }

        // Vérifier le stock disponible
        if ($product->quantity < $quantity) {
            throw new \Exception("Stock insuffisant. Stock disponible: {$product->quantity}");
        }

        // Vérifier si le produit n'est pas déjà dans un autre panier (contrainte unique)
        $existingCart = self::where('product_id', $productId)->first();
        if ($existingCart && $existingCart->user_id != $userId) {
            throw new \Exception('Ce produit est déjà réservé dans un autre panier.');
        }

        // Si le produit est déjà dans le panier de cet utilisateur, augmenter la quantité
        if ($existingCart && $existingCart->user_id == $userId) {
            return $existingCart->incrementQuantity($quantity);
        }

        // Créer un nouvel élément de panier
        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $product->price,
        ]);
    }

    public static function getCartTotal($userId)
    {
        return self::forUser($userId)->sum('total_price');
    }

    public static function getCartItemCount($userId)
    {
        return self::forUser($userId)->sum('quantity');
    }

    public static function clearCart($userId)
    {
        return self::forUser($userId)->delete();
    }

    public static function validateCart($userId)
    {
        $cartItems = self::forUser($userId)->with('product')->get();
        $issues = [];

        foreach ($cartItems as $item) {
            if (!$item->isProductActive()) {
                $issues[] = "Le produit '{$item->product->name}' n'est plus disponible.";
            }
            
            if (!$item->hasStockAvailable()) {
                $issues[] = "Stock insuffisant pour '{$item->product->name}'. Stock disponible: {$item->product->quantity}";
            }
        }

        return $issues;
    }
}
