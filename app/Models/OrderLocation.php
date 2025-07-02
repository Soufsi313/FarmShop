<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class OrderLocation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_number',
        'user_id',
        'cart_location_id',
        'status',
        'total_amount',
        'deposit_amount',
        'paid_amount',
        'rental_start_date',
        'rental_end_date',
        'actual_return_date',
        'confirmed_at',
        'picked_up_at',
        'returned_at',
        'pickup_notes',
        'return_notes',
        'admin_notes',
        'late_fee',
        'damage_fee',
        'client_return_date',
        'client_notes',
        'cancelled_at',
        'total_penalties',
        'deposit_refund_amount',
        'deposit_refunded_at',
        'refund_notes'
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'total_penalties' => 'decimal:2',
        'deposit_refund_amount' => 'decimal:2',
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
        'actual_return_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'returned_at' => 'datetime',
        'client_return_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'deposit_refunded_at' => 'datetime',
    ];
    
    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function cartLocation(): BelongsTo
    {
        return $this->belongsTo(CartLocation::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(OrderItemLocation::class);
    }
    
    // Accesseurs et méthodes utilitaires
    public function getDurationDaysAttribute(): int
    {
        return $this->rental_start_date->diffInDays($this->rental_end_date) + 1;
    }
    
    public function getIsOverdueAttribute(): bool
    {
        // Une location n'est en retard que si elle a été clôturée après 23h59 le jour de fin
        if ($this->client_return_date) {
            // Si le client a clôturé la location après 23h59 le jour de fin
            $endOfRentalDay = $this->rental_end_date->copy()->endOfDay();
            return $this->client_return_date->isAfter($endOfRentalDay);
        }
        
        // Pour les locations encore actives, vérifier si on a dépassé 23h59 le jour de fin
        return $this->status === 'active' && 
               now()->isAfter($this->rental_end_date->copy()->endOfDay());
    }
    
    public function getDaysLateAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        $endOfRentalDay = $this->rental_end_date->copy()->endOfDay();
        $comparisonDate = $this->client_return_date ?? now();
        
        // Calculer la différence en jours en commençant par le lendemain de la fin de location
        $startOfNextDay = $this->rental_end_date->copy()->addDay()->startOfDay();
        
        if ($comparisonDate->isAfter($endOfRentalDay)) {
            return $startOfNextDay->diffInDays($comparisonDate->startOfDay()) + 1;
        }
        
        return 0;
    }
    
    public function getCanBeCancelledAttribute(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
    
    public function getCanBePickedUpAttribute(): bool
    {
        return $this->status === 'confirmed' && 
               $this->rental_start_date->isToday();
    }
    
    public function getCanBeReturnedAttribute(): bool
    {
        return in_array($this->status, ['active', 'pending_inspection']);
    }
    
    public function getCanBeClosedByClientAttribute(): bool
    {
        return $this->status === 'active' && 
               $this->rental_end_date->isToday();
    }
    
    public function getCanBeCancelledByClientAttribute(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
               $this->rental_start_date->isFuture();
    }
    
    public function getNeedsClientActionAttribute(): bool
    {
        // La location nécessite une action du client (clôture le jour J)
        return $this->status === 'active' && 
               $this->rental_end_date->isToday();
    }
    
    public function getIsReadyForAdminInspectionAttribute(): bool
    {
        // La location a été clôturée par le client et attend l'inspection admin
        return $this->status === 'pending_inspection';
    }
    
    public function getRemainingAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }
    
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'active' => 'En cours',
            'pending_inspection' => 'En attente d\'inspection',
            'returned' => 'Retournée',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée',
            'overdue' => 'En retard'
        ];
        
        return $labels[$this->status] ?? $this->status;
    }
    
    // Méthodes de gestion des statuts
    public function confirm(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
        
        return true;
    }
    
    public function markAsPickedUp(): bool
    {
        if ($this->status !== 'confirmed') {
            return false;
        }
        
        $this->update([
            'status' => 'active',
            'picked_up_at' => now()
        ]);
        
        return true;
    }
    
    public function markAsReturned(?string $notes = null): bool
    {
        if (!in_array($this->status, ['active', 'pending_inspection'])) {
            return false;
        }
        
        $this->update([
            'status' => 'returned',
            'actual_return_date' => now(),
            'returned_at' => now(),
            'return_notes' => $notes
        ]);
        
        return true;
    }
    
    public function cancel(?string $reason = null): bool
    {
        if (!$this->can_be_cancelled) {
            return false;
        }
        
        $this->update([
            'status' => 'cancelled',
            'admin_notes' => $reason
        ]);
        
        return true;
    }
    
    public function markAsOverdue(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        $this->update(['status' => 'overdue']);
        
        return true;
    }
    
    /**
     * Clôturer la location par le client (marquer comme prête pour inspection)
     */
    public function closeByClient(?string $clientNotes = null): bool
    {
        if (!$this->can_be_closed_by_client) {
            return false;
        }
        
        $this->update([
            'status' => 'pending_inspection',
            'client_return_date' => now(),
            'client_notes' => $clientNotes
        ]);
        
        return true;
    }
    
    /**
     * Annuler la location par le client (avant démarrage)
     */
    public function cancelByClient(?string $reason = null): bool
    {
        if (!$this->can_be_cancelled_by_client) {
            return false;
        }
        
        $this->update([
            'status' => 'cancelled',
            'client_notes' => $reason,
            'cancelled_at' => now()
        ]);
        
        return true;
    }
    
    // Méthodes de création
    public static function createFromCart(CartLocation $cart): self
    {
        $order = self::create([
            'order_number' => self::generateOrderNumber(),
            'user_id' => $cart->user_id,
            'cart_location_id' => $cart->id,
            'total_amount' => $cart->total_amount,
            'deposit_amount' => $cart->total_deposit,
            'rental_start_date' => $cart->rental_start_date,
            'rental_end_date' => $cart->rental_end_date
        ]);
        
        // Créer les items de la commande
        foreach ($cart->items as $cartItem) {
            OrderItemLocation::createFromCartItem($order, $cartItem);
        }
        
        return $order;
    }
    
    protected static function generateOrderNumber(): string
    {
        do {
            $number = 'LOC-' . now()->format('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $number)->exists());
        
        return $number;
    }
    
    /**
     * Calculer le total des pénalités (retard + dégâts)
     */
    public function calculateTotalPenalties(): float
    {
        $totalDamageFee = $this->items->sum('damage_fee');
        return $this->late_fee + $totalDamageFee;
    }
    
    /**
     * Calculer le montant de caution à rembourser
     */
    public function calculateDepositRefund(): float
    {
        $totalPenalties = $this->calculateTotalPenalties();
        $refundAmount = $this->deposit_amount - $totalPenalties;
        
        // Le remboursement ne peut pas être négatif
        return max(0, $refundAmount);
    }
    
    /**
     * Vérifier si la caution peut être remboursée
     */
    public function getCanRefundDepositAttribute(): bool
    {
        return $this->status === 'returned' && 
               is_null($this->deposit_refunded_at);
    }
    
    /**
     * Vérifier si la caution a été remboursée
     */
    public function getIsDepositRefundedAttribute(): bool
    {
        return !is_null($this->deposit_refunded_at);
    }
    
    /**
     * Obtenir le statut du remboursement de caution
     */
    public function getDepositRefundStatusAttribute(): string
    {
        if ($this->status !== 'returned') {
            return 'En attente de retour';
        }
        
        if ($this->is_deposit_refunded) {
            return 'Remboursée';
        }
        
        return 'À rembourser';
    }
    
    /**
     * Effectuer le remboursement de la caution
     */
    public function processDepositRefund(?string $notes = null): bool
    {
        if (!$this->can_refund_deposit) {
            return false;
        }
        
        $totalPenalties = $this->calculateTotalPenalties();
        $refundAmount = $this->calculateDepositRefund();
        
        $this->update([
            'total_penalties' => $totalPenalties,
            'deposit_refund_amount' => $refundAmount,
            'deposit_refunded_at' => now(),
            'refund_notes' => $notes,
            'status' => 'completed'
        ]);
        
        return true;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'active')
                          ->where('rental_end_date', '<', now()->startOfDay());
                    });
    }
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
