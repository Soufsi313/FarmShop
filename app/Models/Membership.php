<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Membership extends Model
{
    use HasFactory, SoftDeletes;

    // Types d'adhésion
    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_FAMILY = 'family';
    const TYPE_PROFESSIONAL = 'professional';
    const TYPE_STUDENT = 'student';
    const TYPE_ASSOCIATION = 'association';

    // Statuts d'adhésion
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    // Statuts de paiement
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_OVERDUE = 'overdue';

    protected $fillable = [
        'user_id',
        'membership_number',
        'type',
        'status',
        'start_date',
        'end_date',
        'renewal_date',
        'activated_at',
        'suspended_at',
        'cancelled_at',
        'annual_fee',
        'paid_amount',
        'payment_status',
        'last_payment_date',
        'has_delivery_discount',
        'delivery_discount_percent',
        'has_product_discount',
        'product_discount_percent',
        'can_reserve_products',
        'has_priority_access',
        'can_participate_events',
        'notes',
        'preferences',
        'referral_code',
        'referred_by',
        'billing_address',
        'billing_city',
        'billing_postal_code',
        'billing_country',
        'approved_by',
        'approved_at',
        'cancellation_reason',
        'auto_renewal',
        'newsletter_subscription',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'last_payment_date' => 'date',
        'activated_at' => 'datetime',
        'suspended_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'approved_at' => 'datetime',
        'annual_fee' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'delivery_discount_percent' => 'decimal:2',
        'product_discount_percent' => 'decimal:2',
        'has_delivery_discount' => 'boolean',
        'has_product_discount' => 'boolean',
        'can_reserve_products' => 'boolean',
        'has_priority_access' => 'boolean',
        'can_participate_events' => 'boolean',
        'auto_renewal' => 'boolean',
        'newsletter_subscription' => 'boolean',
        'preferences' => 'array',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'renewal_date',
        'last_payment_date',
        'activated_at',
        'suspended_at',
        'cancelled_at',
        'approved_at',
        'deleted_at',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('end_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now())
                    ->where('status', '!=', self::STATUS_CANCELLED);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->whereBetween('end_date', [now(), now()->addDays($days)]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePaidUp($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', self::PAYMENT_OVERDUE)
                    ->orWhere(function($q) {
                        $q->where('payment_status', '!=', self::PAYMENT_PAID)
                          ->where('end_date', '<', now());
                    });
    }

    /**
     * Accessors & Mutators
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->end_date >= now();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date < now() && $this->status !== self::STATUS_CANCELLED;
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               $this->end_date->diffInDays(now()) <= 30 &&
               $this->end_date >= now();
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return $this->end_date >= now() ? $this->end_date->diffInDays(now()) : 0;
    }

    public function getOutstandingAmountAttribute(): float
    {
        return max(0, $this->annual_fee - $this->paid_amount);
    }

    public function getPaymentProgressAttribute(): float
    {
        return $this->annual_fee > 0 ? ($this->paid_amount / $this->annual_fee) * 100 : 0;
    }

    /**
     * Méthodes utilitaires
     */
    public function activate(): bool
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'activated_at' => now(),
        ]);

        return true;
    }

    public function suspend(string $reason = null): bool
    {
        $this->update([
            'status' => self::STATUS_SUSPENDED,
            'suspended_at' => now(),
            'notes' => $this->notes . "\nSuspendu le " . now()->format('d/m/Y') . 
                      ($reason ? " - Raison: $reason" : ''),
        ]);

        return true;
    }

    public function cancel(string $reason = null): bool
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        return true;
    }

    public function renew(int $months = 12): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $newEndDate = $this->end_date->addMonths($months);
        
        $this->update([
            'end_date' => $newEndDate,
            'renewal_date' => now(),
            'payment_status' => self::PAYMENT_PENDING,
        ]);

        return true;
    }

    public function addPayment(float $amount): bool
    {
        $newPaidAmount = $this->paid_amount + $amount;
        
        $paymentStatus = $newPaidAmount >= $this->annual_fee ? 
            self::PAYMENT_PAID : 
            ($newPaidAmount > 0 ? self::PAYMENT_PARTIAL : self::PAYMENT_PENDING);

        $this->update([
            'paid_amount' => $newPaidAmount,
            'payment_status' => $paymentStatus,
            'last_payment_date' => now(),
        ]);

        return true;
    }

    public function generateMembershipNumber(): string
    {
        $year = now()->format('Y');
        $typeCode = strtoupper(substr($this->type, 0, 2));
        $sequence = str_pad(self::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return "MBR{$year}{$typeCode}{$sequence}";
    }

    public function hasDiscount(string $type): bool
    {
        return $type === 'delivery' ? $this->has_delivery_discount : $this->has_product_discount;
    }

    public function getDiscountPercent(string $type): float
    {
        return $type === 'delivery' ? $this->delivery_discount_percent : $this->product_discount_percent;
    }

    /**
     * Types et statuts disponibles
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_INDIVIDUAL => 'Individuel',
            self::TYPE_FAMILY => 'Familial',
            self::TYPE_PROFESSIONAL => 'Professionnel',
            self::TYPE_STUDENT => 'Étudiant',
            self::TYPE_ASSOCIATION => 'Association',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_SUSPENDED => 'Suspendu',
            self::STATUS_EXPIRED => 'Expiré',
            self::STATUS_CANCELLED => 'Annulé',
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return [
            self::PAYMENT_PENDING => 'En attente',
            self::PAYMENT_PARTIAL => 'Partiel',
            self::PAYMENT_PAID => 'Payé',
            self::PAYMENT_OVERDUE => 'En retard',
        ];
    }
}
