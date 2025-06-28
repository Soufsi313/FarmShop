<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    // Statuts de location
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    // Statuts de paiement
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'rental_number',
        'user_id',
        'start_date',
        'end_date',
        'actual_return_date',
        'status',
        'total_rental_amount',
        'total_deposit_amount',
        'penalty_amount',
        'refund_amount',
        'billing_address',
        'payment_status',
        'deposit_status',
        'notes',
        'return_notes',
        'rental_conditions',
        'reminder_sent',
        'reminder_sent_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
        'total_rental_amount' => 'decimal:2',
        'total_deposit_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'billing_address' => 'array',
        'rental_conditions' => 'array',
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($rental) {
            if (empty($rental->rental_number)) {
                $rental->rental_number = 'LOC-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(RentalItem::class);
    }

    public function penalties()
    {
        return $this->hasMany(RentalPenalty::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->orWhere(function ($q) {
                        $q->where('status', self::STATUS_ACTIVE)
                          ->where('end_date', '<', now()->toDateString());
                    });
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeNeedingReminder($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('reminder_sent', false)
                    ->where('end_date', '<=', now()->addWeek()->toDateString());
    }

    /**
     * Accessors
     */
    public function getDurationInDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status !== self::STATUS_OVERDUE && $this->status !== self::STATUS_ACTIVE) {
            return 0;
        }
        
        $endDate = $this->actual_return_date ?? $this->end_date;
        return max(0, now()->toDateString() > $endDate ? 
            Carbon::parse($endDate)->diffInDays(now(), false) : 0);
    }

    public function getTotalAmountAttribute()
    {
        return $this->total_rental_amount + $this->penalty_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === self::STATUS_OVERDUE || 
               ($this->status === self::STATUS_ACTIVE && now()->toDateString() > $this->end_date->toDateString());
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function getCanBeReturnedAttribute()
    {
        return in_array($this->status, [self::STATUS_ACTIVE, self::STATUS_OVERDUE]);
    }

    /**
     * Méthodes métier
     */
    public function calculateTotals()
    {
        $this->total_rental_amount = $this->items->sum('total_rental_amount');
        $this->total_deposit_amount = $this->items->sum('total_deposit_amount');
        $this->penalty_amount = $this->penalties->where('payment_status', '!=', 'waived')->sum('amount');
        $this->save();
    }

    public function canBeModified()
    {
        return in_array($this->status, [self::STATUS_PENDING]);
    }

    public function markAsActive()
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'actual_return_date' => now()->toDateString()
        ]);
    }

    public function markAsOverdue()
    {
        if ($this->status === self::STATUS_ACTIVE) {
            $this->update(['status' => self::STATUS_OVERDUE]);
            
            // Calculer automatiquement l'amende de retard
            $this->calculateLatePenalty();
        }
    }

    public function calculateLatePenalty()
    {
        $daysLate = $this->days_overdue;
        
        if ($daysLate > 0) {
            foreach ($this->items as $item) {
                $penaltyAmount = $item->rental_price_per_day * 0.1 * $daysLate; // 10% par jour de retard
                
                // Vérifier si une amende n'existe pas déjà
                $existingPenalty = $this->penalties()
                    ->where('type', 'late_return')
                    ->where('rental_item_id', $item->id)
                    ->first();
                
                if (!$existingPenalty) {
                    $this->penalties()->create([
                        'rental_item_id' => $item->id,
                        'type' => 'late_return',
                        'reason' => "Retard de {$daysLate} jour(s)",
                        'amount' => $penaltyAmount,
                        'days_late' => $daysLate,
                        'daily_penalty_rate' => $item->rental_price_per_day * 0.1,
                    ]);
                }
            }
            
            $this->calculateTotals();
        }
    }

    public function cancel($reason = null)
    {
        if ($this->can_be_cancelled) {
            $this->update([
                'status' => self::STATUS_CANCELLED,
                'notes' => $reason ? "Annulée: {$reason}" : 'Annulée'
            ]);
            
            // Remettre en stock les produits
            foreach ($this->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
            
            return true;
        }
        
        return false;
    }

    public function processReturn($returnData)
    {
        foreach ($returnData as $itemId => $data) {
            $item = $this->items()->find($itemId);
            if ($item) {
                $item->processReturn($data);
            }
        }
        
        // Vérifier si tous les articles sont retournés
        $allReturned = $this->items->every(function ($item) {
            return $item->return_status === 'fully_returned';
        });
        
        if ($allReturned) {
            $this->markAsCompleted();
        }
        
        $this->calculateTotals();
    }

    /**
     * Méthodes statiques
     */
    public static function getAllStatuses()
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmée',
            self::STATUS_ACTIVE => 'En cours',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_OVERDUE => 'En retard',
            self::STATUS_CANCELLED => 'Annulée',
        ];
    }

    public static function getPaymentStatuses()
    {
        return [
            self::PAYMENT_PENDING => 'En attente',
            self::PAYMENT_PAID => 'Payé',
            self::PAYMENT_REFUNDED => 'Remboursé',
        ];
    }
}
