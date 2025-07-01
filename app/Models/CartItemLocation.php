<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CartItemLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_location_id',
        'product_id',
        'product_name',
        'product_category', 
        'product_description',
        'product_unit',
        'quantity',
        'rental_duration_days',
        'rental_start_date',
        'rental_end_date',
        'unit_price_per_day',
        'total_price',
        'deposit_amount',
        'status'
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'unit_price_per_day' => 'decimal:2',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'quantity' => 'integer',
        'rental_duration_days' => 'integer'
    ];

    // Statuts possibles pour un item de location
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ACTIVE = 'active';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Relations
     */
    public function cartLocation(): BelongsTo
    {
        return $this->belongsTo(CartLocation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accesseurs et Mutateurs
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->total_price + $this->deposit_amount;
    }

    public function getDailyPriceAttribute(): float
    {
        return $this->quantity * $this->unit_price_per_day;
    }

    public function getRemainingDaysAttribute(): int
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return 0;
        }
        
        return max(0, Carbon::now()->diffInDays($this->rental_end_date, false));
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               Carbon::now()->isAfter($this->rental_end_date);
    }

    /**
     * Accesseurs pour compatibilité avec les noms de champs du frontend
     */
    public function getStartDateAttribute()
    {
        return $this->rental_start_date;
    }

    public function getEndDateAttribute()
    {
        return $this->rental_end_date;
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->rental_duration_days;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('rental_end_date', '<', Carbon::now());
    }

    public function scopeExpiringSoon($query, $days = 3)
    {
        $endDate = Carbon::now()->addDays($days);
        return $query->where('status', self::STATUS_ACTIVE)
                    ->whereBetween('rental_end_date', [Carbon::now(), $endDate]);
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Recalculer le prix total basé sur la quantité et la durée
     */
    public function recalculatePrice(): void
    {
        $this->total_price = $this->quantity * $this->unit_price_per_day * $this->rental_duration_days;
        $this->save();
        
        // Recalculer les totaux du panier parent
        $this->cartLocation->recalculateTotals();
    }

    /**
     * Mettre à jour la quantité et recalculer le prix
     */
    public function updateQuantity(int $newQuantity): bool
    {
        if ($newQuantity <= 0) {
            return false;
        }

        // Vérifier la disponibilité du produit pour cette quantité
        if ($this->product && !$this->product->hasStock($newQuantity)) {
            return false;
        }

        $this->quantity = $newQuantity;
        $this->recalculatePrice();
        
        return true;
    }

    /**
     * Mettre à jour la durée de location et recalculer le prix
     */
    public function updateDuration(int $newDurationDays, ?Carbon $newStartDate = null): bool
    {
        if ($newDurationDays <= 0) {
            return false;
        }

        $startDate = $newStartDate ?? $this->rental_start_date;
        if (!$startDate) {
            // Si pas de date de début, utiliser demain par défaut
            $startDate = Carbon::tomorrow();
        }
        
        $endDate = $startDate->copy()->addDays($newDurationDays - 1); // -1 car on inclut le jour de début

        $this->rental_duration_days = $newDurationDays;
        $this->rental_start_date = $startDate;
        $this->rental_end_date = $endDate;
        
        $this->recalculatePrice();

        return true;
    }

    /**
     * Prolonger la location
     */
    public function extendRental(int $additionalDays): bool
    {
        if ($additionalDays <= 0) {
            return false;
        }

        $newDuration = $this->rental_duration_days + $additionalDays;
        return $this->updateDuration($newDuration, $this->rental_start_date);
    }

    /**
     * Valider cet item
     */
    public function validate(): array
    {
        $issues = [];
        $product = $this->product;
        
        if (!$product) {
            $issues[] = "Le produit '{$this->product_name}' n'est plus disponible.";
            return $issues;
        }

        if (!$product->hasStock($this->quantity)) {
            $issues[] = "Stock insuffisant pour '{$product->name}'. Stock disponible: {$product->stock_quantity}, demandé: {$this->quantity}";
        }

        if ($this->rental_start_date->isPast()) {
            $issues[] = "La date de début de location pour '{$product->name}' est dans le passé.";
        }

        if ($this->rental_duration_days <= 0) {
            $issues[] = "La durée de location pour '{$product->name}' doit être supérieure à 0.";
        }

        if ($this->quantity <= 0) {
            $issues[] = "La quantité pour '{$product->name}' doit être supérieure à 0.";
        }

        return $issues;
    }

    /**
     * Confirmer cet item de location
     */
    public function confirm(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->status = self::STATUS_CONFIRMED;
        return $this->save();
    }

    /**
     * Démarrer la location de cet item
     */
    public function start(): bool
    {
        if ($this->status !== self::STATUS_CONFIRMED) {
            return false;
        }

        $this->status = self::STATUS_ACTIVE;
        return $this->save();
    }

    /**
     * Retourner cet item de location
     */
    public function returnItem(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $this->status = self::STATUS_RETURNED;
        return $this->save();
    }

    /**
     * Annuler cet item de location
     */
    public function cancel(): bool
    {
        if (in_array($this->status, [self::STATUS_RETURNED, self::STATUS_CANCELLED])) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }
}
