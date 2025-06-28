<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    // Statuts des articles
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARATION = 'preparation';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_description',
        'product_sku',
        'is_perishable',
        'is_returnable',
        'quantity',
        'unit_price',
        'total_price',
        'returned_quantity',
        'refunded_amount',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'is_perishable' => 'boolean',
        'is_returnable' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            // Calculer automatiquement le prix total
            $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
        });

        static::updating(function ($orderItem) {
            // Recalculer le prix total si la quantité ou le prix unitaire change
            if ($orderItem->isDirty(['quantity', 'unit_price'])) {
                $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
            }
        });
    }

    /**
     * Relations
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * Scopes
     */
    public function scopeReturnable($query)
    {
        return $query->where('is_returnable', true)
                    ->where('is_perishable', false)
                    ->where('status', self::STATUS_DELIVERED);
    }

    public function scopePerishable($query)
    {
        return $query->where('is_perishable', true);
    }

    public function scopeNonPerishable($query)
    {
        return $query->where('is_perishable', false);
    }

    /**
     * Accessors
     */
    public function getIsReturnableAttribute(): bool
    {
        return $this->is_returnable && 
               !$this->is_perishable && 
               $this->status === self::STATUS_DELIVERED &&
               $this->order->is_returnable &&
               $this->order->return_deadline >= now();
    }

    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity - $this->returned_quantity;
    }

    public function getRemainingValueAttribute(): float
    {
        return $this->remaining_quantity * $this->unit_price;
    }

    public function getStatusLabelAttribute(): string
    {
        switch($this->status) {
            case self::STATUS_PENDING:
                return 'En attente';
            case self::STATUS_CONFIRMED:
                return 'Confirmé';
            case self::STATUS_PREPARATION:
                return 'En préparation';
            case self::STATUS_SHIPPED:
                return 'Expédié';
            case self::STATUS_DELIVERED:
                return 'Livré';
            case self::STATUS_RETURNED:
                return 'Retourné';
            case self::STATUS_CANCELLED:
                return 'Annulé';
            default:
                return 'Inconnu';
        }
    }

    /**
     * Méthodes utilitaires
     */
    public function canBeReturned(): bool
    {
        return $this->is_returnable && 
               !$this->is_perishable && 
               $this->status === self::STATUS_DELIVERED &&
               $this->remaining_quantity > 0 &&
               $this->order->return_deadline >= now();
    }

    public function processReturn(int $quantity, string $reason): ?OrderReturn
    {
        if (!$this->canBeReturned() || $quantity > $this->remaining_quantity) {
            return null;
        }

        $refundAmount = $quantity * $this->unit_price;
        
        $orderReturn = OrderReturn::create([
            'order_id' => $this->order_id,
            'order_item_id' => $this->id,
            'user_id' => $this->order->user_id,
            'return_number' => $this->generateReturnNumber(),
            'quantity_returned' => $quantity,
            'refund_amount' => $refundAmount,
            'return_reason' => $reason,
            'requested_at' => now(),
            'return_deadline' => $this->order->return_deadline,
        ]);

        // Mettre à jour la quantité retournée
        $this->increment('returned_quantity', $quantity);
        $this->increment('refunded_amount', $refundAmount);

        // Si tout l'article est retourné, changer le statut
        if ($this->remaining_quantity <= 0) {
            $this->update(['status' => self::STATUS_RETURNED]);
        }

        return $orderReturn;
    }

    public function generateReturnNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $sequence = str_pad(OrderReturn::whereYear('created_at', $year)->count() + 1, 4, '0', STR_PAD_LEFT);
        
        return "RET{$year}{$month}{$sequence}";
    }

    /**
     * Méthodes statiques
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmé',
            self::STATUS_PREPARATION => 'En préparation',
            self::STATUS_SHIPPED => 'Expédié',
            self::STATUS_DELIVERED => 'Livré',
            self::STATUS_RETURNED => 'Retourné',
            self::STATUS_CANCELLED => 'Annulé',
        ];
    }
}
