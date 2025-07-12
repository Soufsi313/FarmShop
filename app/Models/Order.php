<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'status_history',
        'status_updated_at',
        'billing_address',
        'shipping_address',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'paid_at',
        'shipping_method',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'estimated_delivery',
        'can_be_cancelled',
        'can_be_returned',
        'cancelled_at',
        'cancellation_reason',
        'has_returnable_items',
        'return_deadline',
        'invoice_number',
        'invoice_generated_at',
        'email_notifications_sent',
        'last_notification_sent_at',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'status_history' => 'array',
        'status_updated_at' => 'datetime',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery' => 'datetime',
        'cancelled_at' => 'datetime',
        'can_be_cancelled' => 'boolean',
        'can_be_returned' => 'boolean',
        'has_returnable_items' => 'boolean',
        'return_deadline' => 'datetime',
        'invoice_generated_at' => 'datetime',
        'email_notifications_sent' => 'array',
        'last_notification_sent_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending',
        'can_be_cancelled' => true,
        'can_be_returned' => false,
        'has_returnable_items' => false,
        'tax_amount' => 0,
        'shipping_cost' => 0,
        'discount_amount' => 0,
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    public function returnableItems()
    {
        return $this->hasMany(OrderItem::class)->where('is_returnable', true);
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

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCanBeCancelled($query)
    {
        return $query->where('can_be_cancelled', true)
                    ->whereNotIn('status', ['shipped', 'delivered', 'cancelled', 'returned']);
    }

    public function scopeCanBeReturned($query)
    {
        return $query->where('can_be_returned', true)
                    ->where('status', 'delivered')
                    ->where('return_deadline', '>', now());
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'preparing' => 'En préparation',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            'returned' => 'Retournée'
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'paid' => 'Payée',
            'failed' => 'Échec',
            'refunded' => 'Remboursée'
        ];

        return $statuses[$this->payment_status] ?? 'Inconnu';
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' €';
    }

    public function getCanBeCancelledNowAttribute()
    {
        return $this->can_be_cancelled && 
               !in_array($this->status, ['shipped', 'delivered', 'cancelled', 'returned']);
    }

    public function getCanBeReturnedNowAttribute()
    {
        return $this->can_be_returned && 
               $this->status === 'delivered' && 
               $this->return_deadline && 
               $this->return_deadline > now() &&
               $this->has_returnable_items;
    }

    public function getDaysUntilReturnDeadlineAttribute()
    {
        if (!$this->return_deadline) {
            return null;
        }
        
        return max(0, now()->diffInDays($this->return_deadline, false));
    }

    // Méthodes métier
    public function updateStatus($newStatus, $sendNotification = true)
    {
        $oldStatus = $this->status;
        
        // Mettre à jour l'historique
        $history = $this->status_history ?: [];
        $history[] = [
            'from' => $oldStatus,
            'to' => $newStatus,
            'timestamp' => now()->toISOString(),
            'automatic' => true
        ];

        $this->update([
            'status' => $newStatus,
            'status_history' => $history,
            'status_updated_at' => now()
        ]);

        // Actions spécifiques selon le statut
        switch ($newStatus) {
            case 'confirmed':
                $this->onConfirmed();
                break;
            case 'preparing':
                $this->onPreparing();
                break;
            case 'shipped':
                $this->onShipped();
                break;
            case 'delivered':
                $this->onDelivered();
                break;
        }

        // Envoyer notification email
        if ($sendNotification) {
            $this->sendStatusNotification($oldStatus, $newStatus);
        }

        return $this;
    }

    protected function onConfirmed()
    {
        // Générer numéro de facture
        if (!$this->invoice_number) {
            $this->update([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT),
                'invoice_generated_at' => now()
            ]);
        }
    }

    protected function onPreparing()
    {
        // Mettre à jour le statut des items
        $this->items()->update(['status' => 'preparing']);
        
        // Plus possible d'annuler facilement
        $this->update(['can_be_cancelled' => false]);
    }

    protected function onShipped()
    {
        $this->update([
            'shipped_at' => now(),
            'can_be_cancelled' => false,
            'estimated_delivery' => now()->addDays(2) // 2 jours par défaut
        ]);

        // Mettre à jour le statut des items
        $this->items()->update([
            'status' => 'shipped',
            'shipped_at' => now()
        ]);
    }

    protected function onDelivered()
    {
        $deliveredAt = now();
        
        $this->update([
            'delivered_at' => $deliveredAt,
            'can_be_cancelled' => false,
            'can_be_returned' => $this->has_returnable_items,
            'return_deadline' => $this->has_returnable_items ? $deliveredAt->addDays(14) : null
        ]);

        // Mettre à jour le statut des items
        $this->items()->update([
            'status' => 'delivered',
            'delivered_at' => $deliveredAt,
            'return_deadline' => function($item) use ($deliveredAt) {
                return $item->is_returnable ? $deliveredAt->addDays(14) : null;
            }
        ]);
    }

    public function cancel($reason = null)
    {
        if (!$this->can_be_cancelled_now) {
            throw new \Exception('Cette commande ne peut plus être annulée');
        }

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'can_be_cancelled' => false
        ]);

        // Annuler tous les items
        $this->items()->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);

        // Traiter le remboursement automatique
        $this->processAutomaticRefund();

        return $this;
    }

    protected function processAutomaticRefund()
    {
        if ($this->payment_status === 'paid') {
            // Logique de remboursement automatique
            // Ceci devrait être intégré avec votre système de paiement
            
            $this->update([
                'payment_status' => 'refunded'
            ]);
        }
    }

    public function checkReturnableItems()
    {
        $hasReturnableItems = $this->items()
            ->where('is_returnable', true)
            ->exists();

        $this->update(['has_returnable_items' => $hasReturnableItems]);

        return $hasReturnableItems;
    }

    protected function sendStatusNotification($oldStatus, $newStatus)
    {
        $notifications = $this->email_notifications_sent ?: [];
        $notifications[] = [
            'type' => 'status_change',
            'from' => $oldStatus,
            'to' => $newStatus,
            'sent_at' => now()->toISOString()
        ];

        $this->update([
            'email_notifications_sent' => $notifications,
            'last_notification_sent_at' => now()
        ]);

        // Envoyer l'email (implémenter avec Mail/Queue)
        // Mail::to($this->user->email)->send(new OrderStatusChanged($this, $oldStatus, $newStatus));
    }

    public function generateInvoicePdf()
    {
        // Logique pour générer le PDF de facture
        // Retourner le chemin du fichier PDF généré
        
        $pdfPath = "invoices/invoice-{$this->invoice_number}.pdf";
        
        // Implémenter la génération PDF avec DomPDF ou similaire
        
        return $pdfPath;
    }

    /**
     * 🚚 Calculer les frais de livraison pour une commande
     * Règle : < 25€ = 2.50€ de frais | ≥ 25€ = gratuit
     */
    public static function calculateShippingCost(float $subtotal): float
    {
        $freeShippingThreshold = 25.00;
        $shippingFee = 2.50;
        
        return $subtotal >= $freeShippingThreshold ? 0.00 : $shippingFee;
    }

    /**
     * 🎁 Vérifier si une commande est éligible à la livraison gratuite
     */
    public static function isFreeShippingEligible(float $subtotal): bool
    {
        return $subtotal >= 25.00;
    }

    /**
     * 📊 Récapitulatif des frais de livraison
     */
    public function getShippingSummary(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'free_shipping_eligible' => static::isFreeShippingEligible($this->subtotal),
            'shipping_message' => $this->shipping_cost > 0 
                ? "Frais de livraison: {$this->shipping_cost}€" 
                : '🎉 Livraison gratuite'
        ];
    }

    // Méthodes statiques
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Y') . date('m');
        $lastOrder = static::where('order_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function createFromCart($cart, $billingAddress, $shippingAddress, $paymentMethod)
    {
        // Calculer les totaux depuis le panier (qui a déjà les bons calculs)
        $subtotal = $cart->subtotal;
        $taxAmount = $cart->tax_amount;
        
        // 🚚 FRAIS DE LIVRAISON AUTOMATIQUES : < 25€ = 2.50€ | ≥ 25€ = gratuit
        $shippingCost = $cart->calculateShippingCost($subtotal);
        $totalAmount = $subtotal + $taxAmount + $shippingCost;

        // Créer la commande
        $order = static::create([
            'order_number' => static::generateOrderNumber(),
            'user_id' => $cart->user_id,
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
        ]);

        // Créer les items de commande
        foreach ($cart->items as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'product_sku' => $cartItem->product->sku,
                'product_description' => $cartItem->product->description,
                'product_image' => $cartItem->product->featured_image,
                'product_category' => [
                    'id' => $cartItem->product->category->id,
                    'name' => $cartItem->product->category->name,
                    'food_type' => $cartItem->product->category->food_type,
                    'is_returnable' => $cartItem->product->category->is_returnable
                ],
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price,
                'total_price' => $cartItem->quantity * $cartItem->product->price,
                'is_returnable' => $cartItem->product->category->is_returnable
            ]);
        }

        // Vérifier s'il y a des items retournables
        $order->checkReturnableItems();

        return $order;
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }
}
