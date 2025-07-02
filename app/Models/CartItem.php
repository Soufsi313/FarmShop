<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'user_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Relations
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accesseurs
     */
    public function getTotalPriceAttribute()
    {
        // Chargement du produit avec ses offres spéciales si pas déjà chargé
        if (!$this->relationLoaded('product')) {
            $this->load(['product.specialOffers']);
        }

        // Vérifier s'il y a une offre spéciale active
        if ($this->product && $this->product->hasActiveSpecialOffer()) {
            $offer = $this->product->getActiveSpecialOffer();
            $discountResult = $offer->calculateDiscount($this->quantity, $this->unit_price);
            
            if ($discountResult['qualifies']) {
                return $discountResult['final_total'];
            }
        }

        // Prix normal sans offre
        return $this->quantity * $this->unit_price;
    }

    /**
     * Obtenir le prix original (sans remise)
     */
    public function getOriginalTotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Obtenir le montant de la remise appliquée
     */
    public function getDiscountAmountAttribute()
    {
        return $this->original_total - $this->total_price;
    }

    /**
     * Vérifier si cet article a une remise appliquée
     */
    public function hasDiscount(): bool
    {
        return $this->discount_amount > 0;
    }

    /**
     * Obtenir les détails de l'offre spéciale si applicable
     */
    public function getSpecialOfferDetailsAttribute()
    {
        if (!$this->product || !$this->product->hasActiveSpecialOffer()) {
            return null;
        }

        $offer = $this->product->getActiveSpecialOffer();
        return $offer->calculateDiscount($this->quantity, $this->unit_price);
    }

    /**
     * Méthodes utilitaires
     */
    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
        // Le total_price sera recalculé automatiquement via l'accesseur
        $this->touch(); // Mettre à jour updated_at
        return $this;
    }

    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(1, $this->quantity - $amount);
        $this->update(['quantity' => $newQuantity]);
        // Le total_price sera recalculé automatiquement via l'accesseur
        return $this;
    }

    public function updateQuantity($quantity)
    {
        $newQuantity = max(1, $quantity);
        $this->update(['quantity' => $newQuantity]);
        // Le total_price sera recalculé automatiquement via l'accesseur
        return $this;
    }

    /**
     * Scopes
     */
    public function scopeForCart($query, $cartId)
    {
        return $query->where('cart_id', $cartId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
