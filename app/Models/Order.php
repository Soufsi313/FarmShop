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
        'has_non_returnable_items',
        'return_deadline',
        'return_reason',
        'return_requested_at',
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
        'has_non_returnable_items' => 'boolean',
        'return_deadline' => 'datetime',
        'return_requested_at' => 'datetime',
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
        'has_non_returnable_items' => false,
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

        // Envoyer notification email seulement si ce n'est pas automatique
        if ($sendNotification && !($history[count($history) - 1]['automatic'] ?? false)) {
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
        
        // ✅ RÉACTIVÉ : Programmer la progression automatique du statut (maintenant non-bloquant)
        \App\Jobs\ProcessSingleOrderStatusJob::dispatch($this->id, 'preparing')
            ->delay(now()->addSeconds(15));
        
        \Log::info("Commande confirmée {$this->order_number} - Transitions automatiques programmées");
    }

    protected function onPreparing()
    {
        // Mettre à jour le statut des items
        $this->items()->update(['status' => 'preparing']);
        
        // Laisser la possibilité d'annuler pendant la préparation
        // L'annulation sera bloquée seulement après expédition
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
        
        // Vérifier quels items peuvent être retournés
        $hasReturnableItems = $this->checkReturnableItems();
        
        $this->update([
            'delivered_at' => $deliveredAt,
            'can_be_cancelled' => false,
            'can_be_returned' => $hasReturnableItems,
            'return_deadline' => $hasReturnableItems ? $deliveredAt->addDays(14) : null
        ]);

        // Mettre à jour le statut des items
        foreach ($this->items as $item) {
            $item->update([
                'status' => 'delivered',
                'delivered_at' => $deliveredAt,
                'return_deadline' => $item->is_returnable ? $deliveredAt->addDays(14) : null
            ]);
        }
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
            
        $hasNonReturnableItems = $this->items()
            ->where('is_returnable', false)
            ->exists();

        $this->update([
            'has_returnable_items' => $hasReturnableItems,
            'has_non_returnable_items' => $hasNonReturnableItems
        ]);

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
        $maxRetries = 10;
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            // Utiliser une transaction avec verrouillage pour éviter les conditions de course
            $orderNumber = \DB::transaction(function () use ($prefix) {
                // Verrouiller la table pour éviter les lectures concurrentes
                $lastOrder = static::where('order_number', 'like', $prefix . '%')
                    ->lockForUpdate()
                    ->orderBy('order_number', 'desc')
                    ->first();

                if ($lastOrder) {
                    $lastNumber = intval(substr($lastOrder->order_number, -4));
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            });
            
            // Vérifier que ce numéro n'existe pas déjà (double vérification)
            if (!static::where('order_number', $orderNumber)->exists()) {
                return $orderNumber;
            }
            
            // Si le numéro existe déjà, attendre un peu et réessayer
            usleep(100000); // 100ms
        }
        
        // Si toutes les tentatives échouent, utiliser un UUID comme fallback
        return $prefix . '-' . \Str::random(8);
    }

    public static function createFromCart($cart, $billingAddress, $shippingAddress, $paymentMethod)
    {
        // Calculer les totaux depuis le panier (qui a déjà les bons calculs)
        $subtotal = $cart->subtotal;
        $taxAmount = $cart->tax_amount;
        
        // Utiliser la méthode du panier pour calculer les frais de livraison
        $shippingCost = $cart->getShippingCost();
        $totalAmount = $subtotal + $taxAmount + $shippingCost;

        $maxRetries = 5;
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // Créer la commande avec retry logic (order_number sera généré automatiquement par boot())
                $order = static::create([
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
                    $orderItemData = [
                        'product_id' => $cartItem->product_id,
                        'product_name' => $cartItem->product_name,
                        'product_sku' => $cartItem->product->sku ?? null,
                        'product_description' => $cartItem->product->description ?? $cartItem->product->short_description,
                        'product_image' => $cartItem->product->main_image ?? null,
                        'product_category' => [
                            'id' => $cartItem->product->category->id ?? null,
                            'name' => $cartItem->product_category,
                            'food_type' => $cartItem->product->category->food_type ?? 'non_alimentaire',
                            'is_returnable' => $cartItem->product->category->is_returnable ?? false
                        ],
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->unit_price * (1 + ($cartItem->tax_rate / 100)), // Prix TTC unitaire avec réduction
                        'total_price' => $cartItem->total,
                        'is_returnable' => $cartItem->product->category->is_returnable ?? false
                    ];

                    // Ajouter les informations d'offre spéciale si applicable
                    if ($cartItem->special_offer_id) {
                        $orderItemData['special_offer_id'] = $cartItem->special_offer_id;
                        $orderItemData['original_unit_price'] = $cartItem->original_unit_price * (1 + ($cartItem->tax_rate / 100)); // Prix TTC original
                        $orderItemData['discount_percentage'] = $cartItem->discount_percentage;
                        $orderItemData['discount_amount'] = $cartItem->discount_amount * (1 + ($cartItem->tax_rate / 100)); // Réduction TTC
                    }

                    $order->items()->create($orderItemData);
                }

                // Si on arrive ici, la création a réussi
                return $order;

            } catch (\Illuminate\Database\QueryException $e) {
                $lastException = $e;
                
                // Vérifier si c'est une violation de contrainte unique pour order_number
                if (strpos($e->getMessage(), 'orders_order_number_unique') !== false) {
                    // Attendre un peu avant de réessayer
                    usleep(200000); // 200ms
                    continue;
                }
                
                // Si c'est une autre erreur, la relancer immédiatement
                throw $e;
            }
        }

        // Si toutes les tentatives ont échoué, relancer la dernière exception
        throw $lastException;
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
