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
        return $this->quantity * $this->unit_price;
    }

    /**
     * Méthodes utilitaires
     */
    public function incrementQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
        $this->update(['total_price' => $this->quantity * $this->unit_price]);
        $this->touch(); // Mettre à jour updated_at
        return $this;
    }

    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(1, $this->quantity - $amount);
        $this->update([
            'quantity' => $newQuantity,
            'total_price' => $newQuantity * $this->unit_price
        ]);
        return $this;
    }

    public function updateQuantity($quantity)
    {
        $newQuantity = max(1, $quantity);
        $this->update([
            'quantity' => $newQuantity,
            'total_price' => $newQuantity * $this->unit_price
        ]);
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
