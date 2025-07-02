<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemLocation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_location_id',
        'product_id',
        'cart_item_location_id',
        'product_name',
        'product_description',
        'rental_price_per_day',
        'deposit_amount',
        'rental_start_date',
        'rental_end_date',
        'duration_days',
        'subtotal',
        'total_with_deposit',
        'condition_at_pickup',
        'condition_at_return',
        'pickup_notes',
        'return_notes',
        'damage_fee',
        'late_fee'
    ];
    
    protected $casts = [
        'rental_price_per_day' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_with_deposit' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'duration_days' => 'integer'
    ];
    
    // Relations
    public function orderLocation(): BelongsTo
    {
        return $this->belongsTo(OrderLocation::class);
    }
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    public function cartItemLocation(): BelongsTo
    {
        return $this->belongsTo(CartItemLocation::class);
    }
    
    // Accesseurs
    public function getTotalFeesAttribute(): float
    {
        return $this->damage_fee + $this->late_fee;
    }
    
    public function getFinalTotalAttribute(): float
    {
        return $this->total_with_deposit + $this->total_fees;
    }
    
    public function getConditionAtPickupLabelAttribute(): ?string
    {
        if (!$this->condition_at_pickup) return null;
        
        $labels = [
            'excellent' => 'Excellent',
            'good' => 'Bon',
            'fair' => 'Correct',
            'poor' => 'Mauvais'
        ];
        
        return $labels[$this->condition_at_pickup] ?? $this->condition_at_pickup;
    }
    
    public function getConditionAtReturnLabelAttribute(): ?string
    {
        if (!$this->condition_at_return) return null;
        
        $labels = [
            'excellent' => 'Excellent',
            'good' => 'Bon',
            'fair' => 'Correct',
            'poor' => 'Mauvais'
        ];
        
        return $labels[$this->condition_at_return] ?? $this->condition_at_return;
    }
    
    // Méthodes de gestion
    public function recordPickupCondition(string $condition, ?string $notes = null): bool
    {
        $validConditions = ['excellent', 'good', 'fair', 'poor'];
        
        if (!in_array($condition, $validConditions)) {
            return false;
        }
        
        $this->update([
            'condition_at_pickup' => $condition,
            'pickup_notes' => $notes
        ]);
        
        return true;
    }
    
    public function recordReturnCondition(string $condition, ?string $notes = null, float $damageFee = 0): bool
    {
        $validConditions = ['excellent', 'good', 'fair', 'poor'];
        
        if (!in_array($condition, $validConditions)) {
            return false;
        }
        
        $this->update([
            'condition_at_return' => $condition,
            'return_notes' => $notes,
            'damage_fee' => $damageFee
        ]);
        
        return true;
    }
    
    public function addLateFee(float $fee): bool
    {
        $this->update([
            'late_fee' => $this->late_fee + $fee
        ]);
        
        return true;
    }
    
    // Méthodes de création
    public static function createFromCartItem(OrderLocation $order, CartItemLocation $cartItem): self
    {
        return self::create([
            'order_location_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'cart_item_location_id' => $cartItem->id,
            'product_name' => $cartItem->product->name,
            'product_description' => $cartItem->product->description,
            'rental_price_per_day' => $cartItem->rental_price_per_day,
            'deposit_amount' => $cartItem->deposit_amount,
            'rental_start_date' => $cartItem->rental_start_date,
            'rental_end_date' => $cartItem->rental_end_date,
            'duration_days' => $cartItem->duration_days,
            'subtotal' => $cartItem->subtotal,
            'total_with_deposit' => $cartItem->total_with_deposit
        ]);
    }
}
