<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'tax_amount',
        'total',
        'tax_rate',
        'total_items',
        'metadata',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate the total amount of the cart.
     */
    public function calculateTotal()
    {
        $this->subtotal = $this->items()->sum('subtotal');
        $this->tax_amount = $this->items()->sum('tax_amount');
        $this->total = $this->items()->sum('total');
        $this->total_items = $this->items()->sum('quantity');
        $this->save();
        
        return $this->total;
    }

    /**
     * Get the total number of items in the cart.
     */
    public function getTotalItemsAttribute()
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Get the subtotal (HT) of all items.
     */
    public function getSubtotalAttribute()
    {
        return $this->attributes['subtotal'] ?? 0;
    }

    /**
     * Get the total tax amount.
     */
    public function getTotalTaxAttribute()
    {
        return $this->attributes['tax_amount'] ?? 0;
    }

    /**
     * Get the total amount (TTC).
     */
    public function getTotalAmountAttribute()
    {
        return $this->attributes['total'] ?? 0;
    }

    /**
     * Add a product to the cart.
     */
    public function addProduct(Product $product, int $quantity = 1)
    {
        $existingItem = $this->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->increaseQuantity($quantity);
            return $existingItem;
        } else {
            $item = CartItem::createFromProduct($this, $product, $quantity);
            $this->calculateTotal();
            return $item;
        }
    }

    /**
     * Remove a product from the cart.
     */
    public function removeProduct(Product $product)
    {
        $item = $this->items()->where('product_id', $product->id)->first();
        
        if ($item) {
            $item->delete();
            $this->calculateTotal();
            return true;
        }
        
        return false;
    }

    /**
     * Clear all items from the cart.
     */
    public function clear()
    {
        $this->items()->delete();
        $this->subtotal = 0;
        $this->tax_amount = 0;
        $this->total = 0;
        $this->total_items = 0;
        $this->save();
    }

    /**
     * Check if the cart is empty.
     */
    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }

    /**
     * Get formatted total amount.
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2, ',', ' ') . ' €';
    }

    /**
     * Get formatted subtotal.
     */
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', ' ') . ' €';
    }

    /**
     * Get formatted total tax.
     */
    public function getFormattedTotalTaxAttribute()
    {
        return number_format($this->tax_amount, 2, ',', ' ') . ' €';
    }

    /**
     * Check availability of all products in the cart.
     */
    public function checkAvailability()
    {
        $unavailableItems = [];
        
        foreach ($this->items as $item) {
            if ($item->product->stock < $item->quantity) {
                $unavailableItems[] = [
                    'item_id' => $item->id,
                    'product_name' => $item->product->name,
                    'requested_quantity' => $item->quantity,
                    'available_stock' => $item->product->stock
                ];
            }
        }
        
        return $unavailableItems;
    }

    /**
     * Get cost summary of the cart.
     */
    public function getCostSummary()
    {
        return [
            'subtotal' => $this->subtotal,
            'total_tax' => $this->tax_amount,
            'total' => $this->total,
            'total_items' => $this->total_items,
            'formatted' => [
                'subtotal' => $this->formatted_subtotal,
                'total_tax' => $this->formatted_total_tax,
                'total' => $this->formatted_total
            ]
        ];
    }

    /**
     * Get shipping cost based on cart total
     */
    public function getShippingCost(): float
    {
        // Livraison gratuite si le total dépasse 25€
        if ($this->total >= 25.00) {
            return 0.00;
        }
        
        // Sinon, frais de livraison de 5€
        return 5.00;
    }

    /**
     * Get the cart total including shipping
     */
    public function getTotalWithShipping(): float
    {
        return $this->total + $this->getShippingCost();
    }

    /**
     * Check if shipping is free
     */
    public function isFreeShipping(): bool
    {
        return $this->total >= 25.00;
    }

    /**
     * Get remaining amount for free shipping
     */
    public function getRemainingForFreeShipping(): float
    {
        if ($this->isFreeShipping()) {
            return 0.00;
        }
        
        return 25.00 - $this->total;
    }

    /**
     * Get complete cart summary with shipping details
     */
    public function getCompleteCartSummary(): array
    {
        $shippingCost = $this->getShippingCost();
        $totalWithShipping = $this->getTotalWithShipping();
        
        return [
            'subtotal_ht' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'total_ttc' => $this->total,
            'shipping_cost' => $shippingCost,
            'total_with_shipping' => $totalWithShipping,
            'is_free_shipping' => $this->isFreeShipping(),
            'remaining_for_free_shipping' => $this->getRemainingForFreeShipping(),
            'total_items' => $this->total_items,
            'formatted' => [
                'subtotal_ht' => number_format($this->subtotal, 2) . ' €',
                'tax_amount' => number_format($this->tax_amount, 2) . ' €',
                'total_ttc' => number_format($this->total, 2) . ' €',
                'shipping_cost' => number_format($shippingCost, 2) . ' €',
                'total_with_shipping' => number_format($totalWithShipping, 2) . ' €',
                'remaining_for_free_shipping' => number_format($this->getRemainingForFreeShipping(), 2) . ' €'
            ]
        ];
    }

    /**
     * Scope a query to only include non-expired carts.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Get or create a cart for a user.
     */
    public static function getOrCreateForUser(User $user)
    {
        return self::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            [
                'subtotal' => 0,
                'tax_amount' => 0,
                'total' => 0,
                'tax_rate' => 20.00,
                'total_items' => 0
            ]
        );
    }
}