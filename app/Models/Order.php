<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Statuts de commande
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARATION = 'preparation';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    // Statuts de paiement
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'confirmed_at',
        'preparation_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'shipping_method',
        'tracking_number',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'paid_at',
        'coupon_code',
        'coupon_discount',
        'is_returnable',
        'return_deadline',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'preparation_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'paid_at' => 'datetime',
        'return_deadline' => 'date',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'is_returnable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = $order->generateOrderNumber();
            }
            
            // Définir la date limite de retour (14 jours après livraison)
            if (!$order->return_deadline && $order->delivered_at) {
                $order->return_deadline = Carbon::parse($order->delivered_at)->addDays(14);
            }
        });

        static::updating(function ($order) {
            // Mettre à jour automatiquement les timestamps selon le statut
            if ($order->isDirty('status')) {
                $order->updateStatusTimestamp();
            }
        });
    }

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancellable($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_PREPARATION]);
    }

    public function scopeReturnable($query)
    {
        return $query->where('status', self::STATUS_DELIVERED)
                    ->where('is_returnable', true)
                    ->where('return_deadline', '>=', now());
    }

    /**
     * Accessors
     */
    public function getIsCancellableAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_PREPARATION]);
    }

    public function getIsReturnableAttribute(): bool
    {
        return $this->status === self::STATUS_DELIVERED && 
               $this->is_returnable && 
               $this->return_deadline >= now();
    }

    public function getCanDownloadInvoiceAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_PREPARATION, self::STATUS_SHIPPED, self::STATUS_DELIVERED]);
    }

    public function getStatusLabelAttribute(): string
    {
        switch($this->status) {
            case self::STATUS_PENDING:
                return 'En attente';
            case self::STATUS_CONFIRMED:
                return 'Confirmée';
            case self::STATUS_PREPARATION:
                return 'En préparation';
            case self::STATUS_SHIPPED:
                return 'Expédiée';
            case self::STATUS_DELIVERED:
                return 'Livrée';
            case self::STATUS_CANCELLED:
                return 'Annulée';
            case self::STATUS_RETURNED:
                return 'Retournée';
            default:
                return 'Inconnu';
        }
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        switch($this->payment_status) {
            case self::PAYMENT_PENDING:
                return 'En attente';
            case self::PAYMENT_PAID:
                return 'Payé';
            case self::PAYMENT_FAILED:
                return 'Échec';
            case self::PAYMENT_REFUNDED:
                return 'Remboursé';
            default:
                return 'Inconnu';
        }
    }

    /**
     * Méthodes utilitaires
     */
    public function generateOrderNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $sequence = str_pad(self::whereYear('created_at', $year)->count() + 1, 6, '0', STR_PAD_LEFT);
        
        return "FS{$year}{$month}{$sequence}";
    }

    public function updateStatusTimestamp(): void
    {
        $field = null;
        
        switch($this->status) {
            case self::STATUS_CONFIRMED:
                $field = 'confirmed_at';
                break;
            case self::STATUS_PREPARATION:
                $field = 'preparation_at';
                break;
            case self::STATUS_SHIPPED:
                $field = 'shipped_at';
                break;
            case self::STATUS_DELIVERED:
                $field = 'delivered_at';
                break;
            case self::STATUS_CANCELLED:
                $field = 'cancelled_at';
                break;
        }

        if ($field) {
            $this->setAttribute($field, now());
        }

        // Définir la date limite de retour quand la commande est livrée
        if ($this->status === self::STATUS_DELIVERED && !$this->return_deadline) {
            $this->return_deadline = now()->addDays(14);
        }
    }

    public function cancel(string $reason = null): bool
    {
        if (!$this->is_cancellable) {
            return false;
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        // Traiter le remboursement automatique si payé
        if ($this->payment_status === self::PAYMENT_PAID) {
            $this->processAutomaticRefund();
        }

        return true;
    }

    public function confirm(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    public function markAsShipped(string $trackingNumber = null): bool
    {
        if (!in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_PREPARATION])) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_SHIPPED,
            'shipped_at' => now(),
            'tracking_number' => $trackingNumber,
        ]);
    }

    public function markAsDelivered(): bool
    {
        if ($this->status !== self::STATUS_SHIPPED) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
            'return_deadline' => now()->addDays(14),
        ]);
    }

    public function markAsPaid(string $transactionId = null): bool
    {
        return $this->update([
            'payment_status' => self::PAYMENT_PAID,
            'payment_transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);
    }

    public function processAutomaticRefund(): bool
    {
        // TODO: Implémenter la logique de remboursement automatique
        // selon le moyen de paiement utilisé
        
        return $this->update([
            'payment_status' => self::PAYMENT_REFUNDED,
        ]);
    }

    public function hasNonPerishableItems(): bool
    {
        return $this->items()->where('is_perishable', false)->exists();
    }

    public function hasReturnableItems(): bool
    {
        return $this->items()->where('is_returnable', true)->exists();
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('total_price');
        $this->tax_amount = $this->subtotal * 0.20; // TVA 20%
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_cost - $this->discount_amount - $this->coupon_discount;
        $this->save();
    }

    /**
     * Recalculer les totaux de la commande
     */
    public function recalculateTotals(): void
    {
        $this->calculateTotals();
    }

    /**
     * Méthodes pour les statuts disponibles
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmée',
            self::STATUS_PREPARATION => 'En préparation',
            self::STATUS_SHIPPED => 'Expédiée',
            self::STATUS_DELIVERED => 'Livrée',
            self::STATUS_CANCELLED => 'Annulée',
            self::STATUS_RETURNED => 'Retournée',
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return [
            self::PAYMENT_PENDING => 'En attente',
            self::PAYMENT_PAID => 'Payé',
            self::PAYMENT_FAILED => 'Échec',
            self::PAYMENT_REFUNDED => 'Remboursé',
        ];
    }
}
