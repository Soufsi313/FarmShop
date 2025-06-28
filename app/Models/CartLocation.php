<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CartLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    // Statuts possibles pour une location
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ACTIVE = 'active';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
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
        $endDate = $startDate->copy()->addDays($newDurationDays);

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
     * Confirmer la location
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
     * Démarrer la location (marquer comme active)
     */
    public function start(): bool
    {
        if ($this->status !== self::STATUS_CONFIRMED) {
            return false;
        }

        $this->status = self::STATUS_ACTIVE;
        $this->rental_start_date = Carbon::now();
        $this->rental_end_date = Carbon::now()->addDays($this->rental_duration_days);
        
        return $this->save();
    }

    /**
     * Retourner la location
     */
    public function returnRental(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $this->status = self::STATUS_RETURNED;
        return $this->save();
    }

    /**
     * Annuler la location
     */
    public function cancel(): bool
    {
        if (in_array($this->status, [self::STATUS_RETURNED, self::STATUS_CANCELLED])) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }

    /**
     * Méthodes statiques utilitaires
     */

    /**
     * Créer une nouvelle location dans le panier
     */
    public static function addToCart(
        int $userId, 
        int $productId, 
        int $quantity, 
        int $durationDays, 
        Carbon $startDate,
        ?float $depositAmount = null
    ): ?self {
        $product = Product::find($productId);
        
        if (!$product || !$product->hasStock($quantity)) {
            return null;
        }

        $endDate = $startDate->copy()->addDays($durationDays);
        $unitPricePerDay = $product->rental_price_per_day ?? $product->price * 0.1; // 10% du prix de vente par défaut
        $totalPrice = $quantity * $unitPricePerDay * $durationDays;
        $deposit = $depositAmount ?? ($product->deposit_amount ?? $product->price * 0.2); // 20% du prix comme caution par défaut

        return self::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            [
                'product_name' => $product->name,
                'product_category' => $product->category->name ?? 'Non catégorisé',
                'product_description' => $product->description,
                'product_unit' => $product->unit,
                'quantity' => $quantity,
                'rental_duration_days' => $durationDays,
                'rental_start_date' => $startDate,
                'rental_end_date' => $endDate,
                'unit_price_per_day' => $unitPricePerDay,
                'total_price' => $totalPrice,
                'deposit_amount' => $deposit,
                'status' => self::STATUS_PENDING
            ]
        );
    }

    /**
     * Obtenir le panier de location d'un utilisateur
     */
    public static function getCartForUser(int $userId)
    {
        return self::forUser($userId)
                  ->pending()
                  ->with('product')
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    /**
     * Calculer le total du panier de location
     */
    public static function getCartTotal(int $userId): array
    {
        $cartItems = self::getCartForUser($userId);
        
        $totalPrice = $cartItems->sum('total_price');
        $totalDeposit = $cartItems->sum('deposit_amount');
        $totalAmount = $totalPrice + $totalDeposit;
        $itemCount = $cartItems->count();

        return [
            'total_price' => $totalPrice,
            'total_deposit' => $totalDeposit,
            'total_amount' => $totalAmount,
            'item_count' => $itemCount,
            'items' => $cartItems
        ];
    }

    /**
     * Vider le panier de location d'un utilisateur
     */
    public static function clearCartForUser(int $userId): int
    {
        return self::forUser($userId)->pending()->delete();
    }

    /**
     * Valider le panier de location
     */
    public static function validateCart(int $userId): array
    {
        $issues = [];
        $cartItems = self::getCartForUser($userId);

        foreach ($cartItems as $item) {
            $product = $item->product;
            
            if (!$product) {
                $issues[] = "Le produit '{$item->product_name}' n'est plus disponible.";
                continue;
            }

            if (!$product->hasStock($item->quantity)) {
                $issues[] = "Stock insuffisant pour '{$product->name}'. Stock disponible: {$product->stock_quantity}";
            }

            if ($item->rental_start_date->isPast()) {
                $issues[] = "La date de début de location pour '{$product->name}' est dans le passé.";
            }

            if ($item->rental_duration_days <= 0) {
                $issues[] = "La durée de location pour '{$product->name}' doit être supérieure à 0.";
            }
        }

        return $issues;
    }
}
