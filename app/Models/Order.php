<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        'refund_amount',
        'refund_status',
        'refund_reason',
        'refunded_at',
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
        'refunded_at' => 'datetime',
        'return_deadline' => 'date',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'is_returnable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = self::generateOrderNumber();
            }
            
            // Définir la date limite de retour (14 jours après livraison)
            if (!$order->return_deadline && $order->delivered_at) {
                $order->return_deadline = Carbon::parse($order->delivered_at)->addDays(14);
            }
        });

        static::updating(function ($order) {
            // Mettre à jour automatiquement les timestamps selon le statut
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                $order->updateStatusTimestamp();
                
                // Envoyer notification automatique si changement de statut (sauf lors de la création)
                if ($oldStatus && $oldStatus !== $order->status && $order->user) {
                    try {
                        $order->user->notify(new \App\Notifications\OrderStatusChanged($order, $oldStatus));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Erreur envoi notification commande', [
                            'order_id' => $order->id,
                            'old_status' => $oldStatus,
                            'new_status' => $order->status,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
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
    public static function generateOrderNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        
        // Trouver le prochain numéro séquentiel disponible
        $maxSequence = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('MAX(CAST(SUBSTRING(order_number, -6) AS UNSIGNED)) as max_seq')
            ->value('max_seq') ?? 0;
        
        $sequence = str_pad($maxSequence + 1, 6, '0', STR_PAD_LEFT);
        
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

    /**
     * Vérifier si la commande est éligible au retour
     */
    public function isEligibleForReturn(): bool
    {
        // Les commandes annulées ne sont pas éligibles
        if ($this->isCancelled()) {
            return false;
        }

        // Les commandes livrées sont éligibles selon les règles du produit
        if ($this->isDelivered()) {
            return true;
        }

        // Les commandes non expédiées peuvent être annulées (pas de retour nécessaire)
        return false;
    }

    /**
     * Vérifier si la commande peut être annulée
     */
    public function canBeCancelled(): bool
    {
        // Peut être annulée tant qu'elle n'est pas expédiée, livrée ou déjà annulée
        return !in_array($this->status, [
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
            self::STATUS_RETURNED
        ]);
    }

    /**
     * Vérifier si la commande peut être annulée avec remboursement automatique
     */
    public function canBeCancelledWithRefund(): bool
    {
        // Peut être annulée tant qu'elle n'est pas expédiée
        return !$this->isShipped() && !$this->isDelivered() && !$this->isCancelled();
    }

    /**
     * Annuler la commande avec remboursement automatique si non expédiée
     */
    public function cancelWithRefund(string $reason = null): bool
    {
        if (!$this->canBeCancelledWithRefund()) {
            return false;
        }

        DB::beginTransaction();
        try {
            // Annuler la commande
            $this->cancel($reason);

            // Remettre les produits en stock
            foreach ($this->items as $item) {
                $item->product->increment('quantity', $item->quantity);
            }

            // Initier le remboursement automatique si payée
            if ($this->isPaid()) {
                $this->initiateRefund($this->total_amount, 'Annulation avant expédition');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Initier un remboursement
     */
    public function initiateRefund(float $amount, string $reason = null): bool
    {
        // Ici, intégrer avec le système de paiement pour le remboursement
        // Pour l'instant, on simule le processus
        
        $this->refund_amount = $amount;
        $this->refund_status = 'processing';
        $this->refund_reason = $reason;
        
        // TODO: Intégrer avec Stripe/PayPal pour remboursement réel
        
        return $this->save();
    }

    /**
     * Marquer le remboursement comme terminé
     */
    public function completeRefund(): bool
    {
        $this->refund_status = 'completed';
        $this->refunded_at = Carbon::now();
        
        return $this->save();
    }

    /**
     * Vérifier si tous les articles sont retournables
     */
    public function hasReturnableItems(): bool
    {
        return $this->items->some(function ($item) {
            return $item->product->isReturnableProduct();
        });
    }

    /**
     * Obtenir les articles retournables de la commande
     */
    public function getReturnableItems()
    {
        return $this->items->filter(function ($item) {
            return $item->product->isReturnableProduct() && 
                   $item->product->isWithinReturnPeriod($this->created_at);
        });
    }

    /**
     * Vérifier si la commande est dans la période de retour
     */
    public function isWithinReturnPeriod(): bool
    {
        if (!$this->isDelivered()) {
            return false;
        }

        $returnDeadline = $this->return_deadline ?? Carbon::parse($this->delivered_at)->addDays(14);
        return Carbon::now()->lte($returnDeadline);
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

    /**
     * Vérifier si la commande est en attente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si la commande est confirmée
     */
    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Vérifier si la commande est en cours de préparation
     */
    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PREPARATION;
    }

    /**
     * Vérifier si la commande est expédiée
     */
    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    /**
     * Vérifier si la commande est livrée
     */
    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Vérifier si la commande est annulée
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Vérifier si la commande est retournée
     */
    public function isReturned(): bool
    {
        return $this->status === self::STATUS_RETURNED;
    }

    /**
     * Vérifier si la commande est payée
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Vérifier si le remboursement est en cours
     */
    public function isRefundInProgress(): bool
    {
        return $this->refund_status === 'processing';
    }

    /**
     * Vérifier si le remboursement est terminé
     */
    public function isRefundCompleted(): bool
    {
        return $this->refund_status === 'completed';
    }
}
