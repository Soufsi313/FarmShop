<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\OrderLocationStatusChanged;

class OrderLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'start_date',
        'end_date',
        'rental_days',
        'daily_rate',
        'total_rental_cost',
        'deposit_amount',
        'late_fee_per_day',
        'tax_rate',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'billing_address',
        'delivery_address',
        'late_days',
        'late_fees',
        'actual_return_date',
        'inspection_status',
        'product_condition',
        'damage_cost',
        'total_penalties',
        'deposit_refund',
        'inspection_notes',
        'inspection_completed_at',
        'inspected_by',
        'notes',
        'cancellation_reason',
        'confirmed_at',
        'started_at',
        'completed_at',
        'closed_at',
        'cancelled_at',
        'invoice_number',
        'invoice_generated_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'billing_address' => 'array',
        'delivery_address' => 'array',
        'daily_rate' => 'decimal:2',
        'total_rental_cost' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'late_fee_per_day' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'late_fees' => 'decimal:2',
        'damage_cost' => 'decimal:2',
        'total_penalties' => 'decimal:2',
        'deposit_refund' => 'decimal:2',
        'actual_return_date' => 'datetime',
        'inspection_completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'closed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    /**
     * Boot du modèle - Événements automatiques
     */
    protected static function boot()
    {
        parent::boot();

        // Observer les changements de statut
        static::updating(function ($orderLocation) {
            // Vérifier si le statut a changé
            if ($orderLocation->isDirty('status')) {
                $oldStatus = $orderLocation->getOriginal('status');
                $newStatus = $orderLocation->getAttribute('status');
                
                // Déclencher l'événement de changement de statut
                event(new OrderLocationStatusChanged($orderLocation, $oldStatus, $newStatus));
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
        return $this->hasMany(OrderItemLocation::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeRequiringInspection($query)
    {
        return $query->where('status', 'inspecting');
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'active' => 'En cours',
            'completed' => 'Terminée',
            'closed' => 'Clôturée',
            'inspecting' => 'En inspection',
            'finished' => 'Terminée',
            'cancelled' => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'En attente',
            'deposit_paid' => 'Caution payée',
            'paid' => 'Payé',
            'failed' => 'Échec',
            'refunded' => 'Remboursé',
            'partial_refund' => 'Remboursement partiel',
            default => 'Inconnu'
        };
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2, ',', ' ') . ' €';
    }

    public function getFormattedDepositAttribute()
    {
        return number_format($this->deposit_amount, 2, ',', ' ') . ' €';
    }

    public function getDaysUntilStartAttribute()
    {
        return now()->diffInDays($this->start_date, false);
    }

    public function getDaysUntilEndAttribute()
    {
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Business Logic Methods
     */
    
    /**
     * Vérifier si la location peut être annulée
     */
    public function getCanBeCancelledAttribute()
    {
        if ($this->status !== 'pending' && $this->status !== 'confirmed') {
            return false;
        }
        
        // Peut être annulée jusqu'à 23h59 la veille du début
        $cancellationDeadline = $this->start_date->copy()->subDay()->endOfDay();
        return now()->lte($cancellationDeadline);
    }

    /**
     * Vérifier si la location peut être clôturée
     */
    public function getCanBeClosedAttribute()
    {
        return $this->status === 'completed' && now()->gte($this->end_date);
    }

    /**
     * Calculer les jours de retard
     */
    public function calculateLateDays()
    {
        if (!$this->actual_return_date) {
            // Si pas encore retourné, calculer depuis maintenant
            return max(0, now()->diffInDays($this->end_date, false) * -1);
        }
        
        return max(0, $this->actual_return_date->diffInDays($this->end_date, false) * -1);
    }

    /**
     * Calculer les frais de retard
     */
    public function calculateLateFees()
    {
        $lateDays = $this->calculateLateDays();
        return $lateDays * $this->late_fee_per_day;
    }

    /**
     * Mettre à jour le statut
     */
    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->status;
        
        $this->update([
            'status' => $newStatus,
            'notes' => $notes
        ]);
        
        // Mettre à jour les timestamps selon le statut
        switch ($newStatus) {
            case 'confirmed':
                $this->update(['confirmed_at' => now()]);
                break;
            case 'active':
                $this->update(['started_at' => now()]);
                break;
            case 'completed':
                $this->update(['completed_at' => now()]);
                break;
            case 'closed':
                $this->update([
                    'closed_at' => now(),
                    'actual_return_date' => now(),
                    'status' => 'inspecting' // Passe automatiquement en inspection
                ]);
                break;
            case 'cancelled':
                $this->update(['cancelled_at' => now()]);
                break;
        }
        
        // Envoyer notification email selon le nouveau statut
        $this->sendStatusNotification($oldStatus, $newStatus);
    }

    /**
     * Clôturer la location (action utilisateur)
     */
    public function closeRental($returnDate = null)
    {
        if (!$this->can_be_closed) {
            throw new \Exception('Cette location ne peut pas être clôturée maintenant.');
        }

        $actualReturnDate = $returnDate ? Carbon::parse($returnDate) : now();
        
        // Calculer les retards
        $lateDays = $this->calculateLateDays();
        $lateFees = $this->calculateLateFees();
        
        $this->update([
            'status' => 'inspecting',
            'closed_at' => now(),
            'actual_return_date' => $actualReturnDate,
            'late_days' => $lateDays,
            'late_fees' => $lateFees,
            'inspection_status' => 'pending'
        ]);
        
        // Notifier l'admin pour l'inspection
        $this->notifyAdminForInspection();
        
        return $this;
    }

    /**
     * Terminer l'inspection (action admin)
     */
    public function completeInspection($inspectionData)
    {
        $validated = validator($inspectionData, [
            'product_condition' => 'required|in:excellent,good,poor',
            'damage_cost' => 'required|numeric|min:0',
            'inspection_notes' => 'required|string|max:2000'
        ])->validate();
        
        // Calculer le total des pénalités
        $totalPenalties = $this->late_fees + $validated['damage_cost'];
        
        // Calculer le remboursement de caution
        $depositRefund = max(0, $this->deposit_amount - $totalPenalties);
        
        $this->update([
            'status' => 'finished',
            'inspection_status' => 'completed',
            'product_condition' => $validated['product_condition'],
            'damage_cost' => $validated['damage_cost'],
            'total_penalties' => $totalPenalties,
            'deposit_refund' => $depositRefund,
            'inspection_notes' => $validated['inspection_notes'],
            'inspection_completed_at' => now(),
            'inspected_by' => auth()->id()
        ]);
        
        // Envoyer le rapport d'inspection au client
        $this->sendInspectionReport();
        
        return $this;
    }

    /**
     * Annuler la location
     */
    public function cancel($reason = null)
    {
        if (!$this->can_be_cancelled) {
            throw new \Exception('Cette location ne peut plus être annulée.');
        }
        
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);
        
        // Remettre le stock disponible
        foreach ($this->items as $item) {
            $item->product->increment('rental_stock', $item->quantity);
        }
        
        return $this;
    }

    /**
     * Créer une commande de location depuis le panier
     */
    public static function createFromCart(CartLocation $cart, $rentalData, $addresses, $paymentMethod)
    {
        $startDate = Carbon::parse($rentalData['start_date']);
        $endDate = Carbon::parse($rentalData['end_date']);
        $rentalDays = $startDate->diffInDays($endDate) + 1;
        
        // Calculer les totaux
        $subtotal = 0;
        $totalDeposit = 0;
        
        foreach ($cart->items as $item) {
            $itemSubtotal = $item->product->daily_rental_price * $item->quantity * $rentalDays;
            $itemDeposit = $item->product->rental_deposit * $item->quantity;
            
            $subtotal += $itemSubtotal;
            $totalDeposit += $itemDeposit;
        }
        
        $taxAmount = $subtotal * 0.21; // TVA 21%
        $totalAmount = $subtotal + $taxAmount;
        
        $orderLocation = static::create([
            'user_id' => $cart->user_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'rental_days' => $rentalDays,
            'daily_rate' => $subtotal / $rentalDays,
            'total_rental_cost' => $subtotal,
            'deposit_amount' => $totalDeposit,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'billing_address' => $addresses['billing'],
            'delivery_address' => $addresses['delivery'],
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);
        
        // Créer les articles de la commande
        foreach ($cart->items as $cartItem) {
            $orderLocation->items()->create([
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'product_sku' => $cartItem->product->sku,
                'product_description' => $cartItem->product->description,
                'quantity' => $cartItem->quantity,
                'daily_rate' => $cartItem->product->daily_rental_price,
                'rental_days' => $rentalDays,
                'deposit_per_item' => $cartItem->product->rental_deposit,
                'subtotal' => $cartItem->product->daily_rental_price * $cartItem->quantity * $rentalDays,
                'total_deposit' => $cartItem->product->rental_deposit * $cartItem->quantity,
                'tax_amount' => ($cartItem->product->daily_rental_price * $cartItem->quantity * $rentalDays) * 0.21,
                'total_amount' => ($cartItem->product->daily_rental_price * $cartItem->quantity * $rentalDays) * 1.21
            ]);
        }
        
        return $orderLocation;
    }

    /**
     * Vérifier automatiquement les statuts (à exécuter via un job)
     */
    public static function checkStatusUpdates()
    {
        // Démarrer les locations dont la date de début est arrivée
        static::where('status', 'confirmed')
              ->whereDate('start_date', '<=', now())
              ->each(function ($order) {
                  $order->updateStatus('active');
              });
              
        // Terminer les locations dont la date de fin est passée
        static::where('status', 'active')
              ->whereDate('end_date', '<', now())
              ->each(function ($order) {
                  $order->updateStatus('completed');
              });
    }

    /**
     * Envoyer notification de changement de statut
     */
    private function sendStatusNotification($oldStatus, $newStatus)
    {
        // Implémentation des emails selon le statut
        try {
            $template = match($newStatus) {
                'confirmed' => 'emails.rental.confirmed',
                'active' => 'emails.rental.started',
                'completed' => 'emails.rental.completed',
                'finished' => 'emails.rental.finished',
                'cancelled' => 'emails.rental.cancelled',
                default => null
            };
            
            if ($template) {
                Mail::send($template, ['orderLocation' => $this], function ($message) use ($newStatus) {
                    $message->to($this->user->email, $this->user->name)
                            ->subject("Location {$this->order_number} - {$this->status_label}");
                });
            }
        } catch (\Exception $e) {
            \Log::error("Erreur envoi email location {$this->order_number}: " . $e->getMessage());
        }
    }

    /**
     * Notifier l'admin pour l'inspection
     */
    private function notifyAdminForInspection()
    {
        try {
            // Récupérer tous les admins
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Mail::send('emails.rental.inspection-needed', [
                    'orderLocation' => $this,
                    'admin' => $admin
                ], function ($message) use ($admin) {
                    $message->to($admin->email, $admin->name)
                            ->subject("Inspection requise - Location {$this->order_number}");
                });
            }
        } catch (\Exception $e) {
            \Log::error("Erreur notification inspection {$this->order_number}: " . $e->getMessage());
        }
    }

    /**
     * Envoyer le rapport d'inspection
     */
    private function sendInspectionReport()
    {
        try {
            Mail::send('emails.rental.inspection-report', ['orderLocation' => $this], function ($message) {
                $message->to($this->user->email, $this->user->name)
                        ->subject("Rapport d'inspection - Location {$this->order_number}");
            });
        } catch (\Exception $e) {
            \Log::error("Erreur envoi rapport inspection {$this->order_number}: " . $e->getMessage());
        }
    }

        /**
     * Générer et sauvegarder la facture PDF
     */
    public function generateInvoicePdf()
    {
        if (!$this->invoice_number) {
            $this->generateInvoiceNumber();
        }

        $pdf = \PDF::loadView('invoices.rental-invoice', [
            'orderLocation' => $this,
            'items' => $this->items()->with('product')->get(),
            'user' => $this->user,
            'company' => [
                'name' => config('app.name', 'FarmShop'),
                'address' => 'Rue de la Ferme, 123',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
                'country' => 'Belgique',
                'phone' => '+32 2 123 45 67',
                'email' => 'contact@farmshop.be',
                'vat_number' => 'BE0123456789'
            ]
        ]);

        $filename = 'facture-location-' . $this->invoice_number . '.pdf';
        $path = storage_path('app/public/invoices/rentals/');
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        $pdf->save($path . $filename);
        
        // Marquer la facture comme générée
        $this->update([
            'invoice_generated_at' => now()
        ]);
        
        return $path . $filename;
    }

    /**
     * Générer un numéro de facture unique
     */
    public function generateInvoiceNumber()
    {
        if ($this->invoice_number) {
            return $this->invoice_number;
        }

        $prefix = 'FL-' . date('Y') . '-';
        
        for ($attempt = 0; $attempt < 10; $attempt++) {
            DB::transaction(function () use ($prefix, &$invoiceNumber) {
                $lastInvoice = static::where('invoice_number', 'like', $prefix . '%')
                    ->orderBy('invoice_number', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastInvoice) {
                    $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                $invoiceNumber = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            });
            
            // Vérifier que ce numéro n'existe pas déjà
            if (!static::where('invoice_number', $invoiceNumber)->exists()) {
                $this->update(['invoice_number' => $invoiceNumber]);
                return $invoiceNumber;
            }
            
            usleep(100000); // 100ms
        }
        
        // Fallback avec timestamp
        $invoiceNumber = $prefix . time();
        $this->update(['invoice_number' => $invoiceNumber]);
        return $invoiceNumber;
    }

    /**
     * Vérifier si la facture peut être générée
     */
    public function canGenerateInvoice()
    {
        return in_array($this->payment_status, ['paid', 'partially_paid']) && 
               in_array($this->status, ['confirmed', 'in_progress', 'returned', 'inspecting', 'finished']);
    }

    /**
     * Générer un numéro de commande unique
     */
    public static function generateOrderNumber()
    {
        $prefix = 'LOC-' . date('Y') . date('m');
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
}
