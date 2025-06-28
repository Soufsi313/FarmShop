<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalPenalty extends Model
{
    use HasFactory;

    // Types d'amendes
    const TYPE_LATE_RETURN = 'late_return';
    const TYPE_DAMAGE = 'damage';
    const TYPE_LOSS = 'loss';
    const TYPE_CLEANING = 'cleaning';
    const TYPE_OTHER = 'other';

    // Statuts de paiement
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_WAIVED = 'waived';
    const PAYMENT_DISPUTED = 'disputed';

    protected $fillable = [
        'rental_id',
        'rental_item_id',
        'type',
        'reason',
        'description',
        'amount',
        'days_late',
        'daily_penalty_rate',
        'payment_status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_penalty_rate' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function rentalItem()
    {
        return $this->belongsTo(RentalItem::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    public function scopeDisputed($query)
    {
        return $query->where('payment_status', self::PAYMENT_DISPUTED);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accessors
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function getIsWaivedAttribute()
    {
        return $this->payment_status === self::PAYMENT_WAIVED;
    }

    public function getIsDisputedAttribute()
    {
        return $this->payment_status === self::PAYMENT_DISPUTED;
    }

    public function getFormattedTypeAttribute()
    {
        return self::getTypeLabels()[$this->type] ?? $this->type;
    }

    public function getFormattedPaymentStatusAttribute()
    {
        return self::getPaymentStatusLabels()[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Méthodes métier
     */
    public function markAsPaid($notes = null)
    {
        $this->update([
            'payment_status' => self::PAYMENT_PAID,
            'paid_at' => now(),
            'notes' => $notes
        ]);
        
        // Recalculer les totaux de la location
        $this->rental->calculateTotals();
    }

    public function waive($reason = null)
    {
        $this->update([
            'payment_status' => self::PAYMENT_WAIVED,
            'notes' => $reason ? "Remise: {$reason}" : 'Amende annulée'
        ]);
        
        // Recalculer les totaux de la location
        $this->rental->calculateTotals();
    }

    public function dispute($reason)
    {
        $this->update([
            'payment_status' => self::PAYMENT_DISPUTED,
            'notes' => "Contestation: {$reason}"
        ]);
    }

    public function updateAmount($newAmount, $reason = null)
    {
        $oldAmount = $this->amount;
        
        $this->update([
            'amount' => $newAmount,
            'notes' => $reason ? 
                "Montant modifié de {$oldAmount}€ à {$newAmount}€. Raison: {$reason}" : 
                "Montant modifié de {$oldAmount}€ à {$newAmount}€"
        ]);
        
        // Recalculer les totaux de la location
        $this->rental->calculateTotals();
    }

    /**
     * Méthodes statiques
     */
    public static function getTypeLabels()
    {
        return [
            self::TYPE_LATE_RETURN => 'Retard de retour',
            self::TYPE_DAMAGE => 'Dommage',
            self::TYPE_LOSS => 'Perte',
            self::TYPE_CLEANING => 'Nettoyage',
            self::TYPE_OTHER => 'Autre',
        ];
    }

    public static function getPaymentStatusLabels()
    {
        return [
            self::PAYMENT_PENDING => 'En attente',
            self::PAYMENT_PAID => 'Payée',
            self::PAYMENT_WAIVED => 'Annulée',
            self::PAYMENT_DISPUTED => 'Contestée',
        ];
    }

    public static function getTotalByType($type, $dateFrom = null, $dateTo = null)
    {
        $query = self::where('type', $type)
                    ->where('payment_status', '!=', self::PAYMENT_WAIVED);
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        return $query->sum('amount');
    }
}
