<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_description',
        'product_image',
        'product_category',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'status_updated_at',
        'is_returnable',
        'is_returned',
        'returned_quantity',
        'return_deadline',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'can_be_cancelled',
        'cancelled_at',
        'cancellation_reason',
        'metadata',
    ];

    protected $casts = [
        'product_category' => 'array',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'status_updated_at' => 'datetime',
        'is_returnable' => 'boolean',
        'is_returned' => 'boolean',
        'returned_quantity' => 'integer',
        'return_deadline' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'can_be_cancelled' => 'boolean',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'is_returnable' => false,
        'is_returned' => false,
        'returned_quantity' => 0,
        'can_be_cancelled' => true,
    ];

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeReturnable($query)
    {
        return $query->where('is_returnable', true);
    }

    public function scopeNotReturned($query)
    {
        return $query->where('is_returned', false);
    }

    public function scopeCanBeReturned($query)
    {
        return $query->where('is_returnable', true)
                    ->where('is_returned', false)
                    ->where('status', 'delivered')
                    ->where('return_deadline', '>', now());
    }

    public function scopeCanBeCancelled($query)
    {
        return $query->where('can_be_cancelled', true)
                    ->whereNotIn('status', ['shipped', 'delivered', 'cancelled', 'returned']);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'preparing' => 'En préparation',
            'shipped' => 'Expédié',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé',
            'returned' => 'Retourné'
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2) . ' €';
    }

    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 2) . ' €';
    }

    public function getProductImageUrlAttribute()
    {
        if ($this->product_image) {
            return asset('storage/' . $this->product_image);
        }
        return asset('images/products/default.jpg');
    }

    public function getCanBeCancelledNowAttribute()
    {
        return $this->can_be_cancelled && 
               !in_array($this->status, ['shipped', 'delivered', 'cancelled', 'returned']);
    }

    public function getCanBeReturnedNowAttribute()
    {
        return $this->is_returnable && 
               !$this->is_returned &&
               $this->status === 'delivered' && 
               $this->return_deadline && 
               $this->return_deadline > now();
    }

    public function getRemainingReturnableQuantityAttribute()
    {
        return $this->quantity - $this->returned_quantity;
    }

    public function getDaysUntilReturnDeadlineAttribute()
    {
        if (!$this->return_deadline) {
            return null;
        }
        
        return max(0, now()->diffInDays($this->return_deadline, false));
    }

    public function getCategoryTypeAttribute()
    {
        return $this->product_category['food_type'] ?? 'alimentaire';
    }

    public function getIsNonFoodItemAttribute()
    {
        return $this->category_type === 'non_alimentaire';
    }

    // Méthodes métier
    public function updateStatus($newStatus)
    {
        $this->update([
            'status' => $newStatus,
            'status_updated_at' => now()
        ]);

        // Actions spécifiques selon le statut
        switch ($newStatus) {
            case 'shipped':
                $this->update([
                    'shipped_at' => now(),
                    'can_be_cancelled' => false
                ]);
                break;
                
            case 'delivered':
                $deliveredAt = now();
                $this->update([
                    'delivered_at' => $deliveredAt,
                    'can_be_cancelled' => false,
                    'return_deadline' => $this->is_returnable ? $deliveredAt->addDays(14) : null
                ]);
                break;
        }

        return $this;
    }

    public function cancel($reason = null)
    {
        if (!$this->can_be_cancelled_now) {
            throw new \Exception('Cet article ne peut plus être annulé');
        }

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'can_be_cancelled' => false
        ]);

        return $this;
    }

    public function canBeReturnedQuantity($quantity)
    {
        return $quantity <= $this->remaining_returnable_quantity;
    }

    public function processReturn($quantity, $reason, $description = null)
    {
        if (!$this->can_be_returned_now) {
            throw new \Exception('Cet article ne peut plus être retourné');
        }

        if (!$this->canBeReturnedQuantity($quantity)) {
            throw new \Exception('Quantité de retour invalide');
        }

        // Créer la demande de retour
        $return = $this->returns()->create([
            'order_id' => $this->order_id,
            'user_id' => $this->order->user_id,
            'return_number' => OrderReturn::generateReturnNumber(),
            'quantity_returned' => $quantity,
            'reason' => $reason,
            'description' => $description,
            'requested_at' => now(),
            'refund_amount' => $quantity * $this->unit_price,
            'status' => 'requested'
        ]);

        // Mettre à jour les quantités
        $this->increment('returned_quantity', $quantity);

        // Si tout est retourné, marquer comme retourné
        if ($this->returned_quantity >= $this->quantity) {
            $this->update([
                'is_returned' => true,
                'status' => 'returned'
            ]);
        }

        return $return;
    }

    public function calculateRefundAmount($quantity = null)
    {
        $quantityToRefund = $quantity ?: $this->quantity;
        return $quantityToRefund * $this->unit_price;
    }

    // Vérifications
    public function isEligibleForReturn()
    {
        return $this->is_returnable && 
               $this->status === 'delivered' && 
               $this->return_deadline && 
               $this->return_deadline > now() &&
               $this->remaining_returnable_quantity > 0;
    }

    public function getReturnEligibilityReason()
    {
        if (!$this->is_returnable) {
            return 'Article non retournable (produit alimentaire)';
        }

        if ($this->status !== 'delivered') {
            return 'Article non encore livré';
        }

        if (!$this->return_deadline || $this->return_deadline <= now()) {
            return 'Délai de retour dépassé (14 jours)';
        }

        if ($this->remaining_returnable_quantity <= 0) {
            return 'Article déjà entièrement retourné';
        }

        return 'Article éligible au retour';
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Définir si l'article est retournable selon sa catégorie
            if (isset($item->product_category['is_returnable'])) {
                $item->is_returnable = $item->product_category['is_returnable'];
            }
        });

        static::updated(function ($item) {
            // Mettre à jour le statut de la commande si nécessaire
            if ($item->isDirty('status')) {
                $item->order->load('items');
                
                // Si tous les items sont livrés, marquer la commande comme livrée
                if ($item->order->items->every(fn($i) => $i->status === 'delivered')) {
                    $item->order->updateStatus('delivered');
                }
            }
        });
    }
}
