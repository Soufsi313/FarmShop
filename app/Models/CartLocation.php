<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class CartLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'total_deposit',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_deposit' => 'decimal:2'
    ];

    // Statuts possibles pour un panier de location
    const STATUS_DRAFT = 'draft';           // Panier en cours de constitution
    const STATUS_PENDING = 'pending';       // Panier validé, en attente de confirmation
    const STATUS_CONFIRMED = 'confirmed';   // Location confirmée
    const STATUS_ACTIVE = 'active';         // Location en cours
    const STATUS_COMPLETED = 'completed';   // Location terminée
    const STATUS_CANCELLED = 'cancelled';   // Location annulée

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItemLocation::class);
    }

    /**
     * Accesseurs
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->items()->sum('quantity');
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->total_amount + $this->total_deposit;
    }

    /**
     * Scopes
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Recalculer les totaux basés sur les items
     */
    public function recalculateTotals(): void
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->total_deposit = $this->items()->sum('deposit_amount');
        $this->save();
    }

    /**
     * Obtenir ou créer le panier de location actif d'un utilisateur
     */
    public static function getActiveCartForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'status' => self::STATUS_DRAFT],
            ['total_amount' => 0, 'total_deposit' => 0]
        );
    }

    /**
     * Ajouter un produit au panier
     */
    public function addItem(
        int $productId, 
        int $quantity, 
        int $durationDays, 
        Carbon $startDate,
        ?float $depositAmount = null
    ): ?CartItemLocation {
        $product = Product::find($productId);
        
        if (!$product || !$product->hasStock($quantity)) {
            return null;
        }

        $endDate = $startDate->copy()->addDays($durationDays);
        $unitPricePerDay = $product->rental_price_per_day ?? $product->price * 0.1;
        $totalPrice = $quantity * $unitPricePerDay * $durationDays;
        $deposit = $depositAmount ?? ($product->deposit_amount ?? $product->price * 0.2);

        // Vérifier si l'item existe déjà
        $existingItem = $this->items()->where('product_id', $productId)->first();
        
        if ($existingItem) {
            // Mettre à jour l'item existant
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
                'rental_duration_days' => $durationDays,
                'rental_start_date' => $startDate,
                'rental_end_date' => $endDate,
                'total_price' => $existingItem->total_price + $totalPrice,
                'deposit_amount' => $existingItem->deposit_amount + ($quantity * $deposit)
            ]);
            $item = $existingItem;
        } else {
            // Créer un nouvel item
            $item = $this->items()->create([
                'product_id' => $productId,
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
                'deposit_amount' => $quantity * $deposit,
                'status' => CartItemLocation::STATUS_PENDING
            ]);
        }

        $this->recalculateTotals();
        return $item;
    }

    /**
     * Supprimer un item du panier
     */
    public function removeItem(int $itemId): bool
    {
        $item = $this->items()->find($itemId);
        if (!$item) {
            return false;
        }

        $item->delete();
        $this->recalculateTotals();
        
        // Si plus d'items, supprimer le panier
        if ($this->items()->count() === 0) {
            $this->delete();
        }
        
        return true;
    }

    /**
     * Vider le panier
     */
    public function clear(): bool
    {
        $this->items()->delete();
        $this->delete();
        return true;
    }

    /**
     * Valider le panier
     */
    public function validate(): array
    {
        $issues = [];

        if ($this->items()->count() === 0) {
            $issues[] = "Le panier de location est vide.";
            return $issues;
        }

        foreach ($this->items as $item) {
            $itemIssues = $item->validate();
            $issues = array_merge($issues, $itemIssues);
        }

        return $issues;
    }

    /**
     * Confirmer le panier (passer de draft à pending)
     */
    public function submit(): bool
    {
        if ($this->status !== self::STATUS_DRAFT) {
            return false;
        }

        $issues = $this->validate();
        if (!empty($issues)) {
            return false;
        }

        $this->status = self::STATUS_PENDING;
        
        // Mettre à jour tous les items
        $this->items()->update(['status' => CartItemLocation::STATUS_PENDING]);
        
        return $this->save();
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
        $this->items()->update(['status' => CartItemLocation::STATUS_CONFIRMED]);
        
        return $this->save();
    }

    /**
     * Démarrer la location
     */
    public function start(): bool
    {
        if ($this->status !== self::STATUS_CONFIRMED) {
            return false;
        }

        $this->status = self::STATUS_ACTIVE;
        $this->items()->update(['status' => CartItemLocation::STATUS_ACTIVE]);
        
        return $this->save();
    }

    /**
     * Terminer la location
     */
    public function complete(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $this->status = self::STATUS_COMPLETED;
        $this->items()->update(['status' => CartItemLocation::STATUS_RETURNED]);
        
        return $this->save();
    }

    /**
     * Annuler la location
     */
    public function cancel(): bool
    {
        if (in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED])) {
            return false;
        }

        $this->status = self::STATUS_CANCELLED;
        $this->items()->update(['status' => CartItemLocation::STATUS_CANCELLED]);
        
        return $this->save();
    }
}
