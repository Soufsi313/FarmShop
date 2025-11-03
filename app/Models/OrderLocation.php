<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        'total_rental_cost',  // Ajout du champ manquant
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
        'stripe_payment_intent_id',
        'stripe_deposit_authorization_id',
        'deposit_status',
        'deposit_captured_amount',
        'deposit_captured_at',
        'deposit_cancelled_at',
        'payment_details',
        'billing_address',
        'delivery_address',
        'late_days',
        'late_fees',
        'actual_return_date',
        'inspection_status',
        'product_condition',
        'damage_cost',
        'penalty_amount',
        'total_penalties',
        'deposit_refund',
        'has_damages',
        'damage_notes',
        'damage_photos',
        'auto_calculate_damages',
        'inspection_notes',
        'inspection_completed_at',
        'inspected_by',
        'notes',
        'cancellation_reason',
        'confirmed_at',
        'started_at',
        'reminder_sent_at',
        'ended_at',
        'overdue_notification_sent_at',
        'completed_at',
        'closed_at',
        'cancelled_at',
        'invoice_number',
        'invoice_generated_at',
        'frontend_confirmed',
        'frontend_confirmed_at'
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
        'has_damages' => 'boolean',
        'damage_photos' => 'array',
        'auto_calculate_damages' => 'boolean',
        'actual_return_date' => 'datetime',
        'inspection_completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'started_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'ended_at' => 'datetime',
        'overdue_notification_sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'closed_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    /**
     * Calculer la date limite d'annulation (24h avant le début de location)
     */
    public function getCancellationDeadline()
    {
        return $this->start_date ? $this->start_date->subDay() : null;
    }

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

    // Alias pour compatibilité avec le code existant
    public function orderItemLocations()
    {
        return $this->items();
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
            'deposit_paid' => 'Caution pré-autorisée',
            'paid' => 'Payé',
            'failed' => 'Échec',
            'refunded' => 'Caution libérée',
            'partial_refund' => 'Caution partiellement capturée',
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

    public function getCalculatedDepositAmountAttribute()
    {
        // Si le dépôt est déjà calculé et stocké, l'utiliser
        if ($this->deposit_amount > 0) {
            return $this->deposit_amount;
        }

        // Sinon, calculer dynamiquement à partir des produits
        return $this->orderItemLocations->sum(function ($item) {
            return $item->quantity * ($item->product->deposit_amount ?? 0);
        });
    }

    public function getDaysUntilStartAttribute()
    {
        return now()->diffInDays($this->start_date, false);
    }

    public function getDaysUntilEndAttribute()
    {
        return now()->diffInDays($this->end_date, false);
    }

    public function getRentalDaysCount()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getFormattedRentalDaysAttribute()
    {
        $days = $this->getRentalDaysCount();
        return $days . ' jour' . ($days > 1 ? 's' : '');
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
            // Si pas encore retourné, calculer depuis maintenant (jours complets uniquement)
            $days = now()->startOfDay()->diffInDays($this->end_date->copy()->startOfDay(), false);
            return max(0, $days * -1);
        }
        
        // Calculer les jours complets de retard (sans fractions d'heures/minutes)
        // Standard dans la location: un retour le 30/10 à 9h pour une fin le 25/10 = 5 jours complets
        // diffInDays(endDate, false) donne un nombre négatif si actual_return_date > end_date
        $days = $this->actual_return_date->copy()->startOfDay()
            ->diffInDays($this->end_date->copy()->startOfDay(), false);
        return max(0, $days * -1);
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
     * Calculer automatiquement le montant des dommages
     * Dommages = caution complète + frais de retard
     */
    public function calculateDamageAmount()
    {
        if (!$this->has_damages || !$this->auto_calculate_damages) {
            return 0;
        }
        
        return $this->deposit_amount + $this->late_fees;
    }

    /**
     * Vérifier si la caution sera capturée (dommages détectés)
     */
    public function getDepositWillBeCapturedAttribute()
    {
        return $this->has_damages && $this->auto_calculate_damages;
    }

    /**
     * Obtenir le montant de la caution qui sera libéré
     */
    public function getDepositReleaseAmountAttribute()
    {
        return $this->has_damages ? 0 : $this->deposit_amount;
    }

    /**
     * Mettre à jour le statut
     */
    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->status;
        
        // Préparer les données à mettre à jour
        $updateData = [
            'status' => $newStatus,
            'notes' => $notes
        ];
        
        // Ajouter les timestamps selon le statut
        switch ($newStatus) {
            case 'confirmed':
                $updateData['confirmed_at'] = now();
                break;
            case 'active':
                $updateData['started_at'] = now();
                break;
            case 'completed':
                $updateData['completed_at'] = now();
                break;
            case 'closed':
                $updateData['closed_at'] = now();
                $updateData['actual_return_date'] = now();
                break;
            case 'cancelled':
                $updateData['cancelled_at'] = now();
                break;
        }
        
        // Un seul update pour éviter les boucles
        $this->update($updateData);
        
        // DÉSACTIVÉ: Ancienne notification email - maintenant gérée par HandleOrderLocationStatusChange Listener
        // $this->sendStatusNotification($oldStatus, $newStatus);
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
        
        // Définir temporairement actual_return_date pour le calcul correct
        $this->actual_return_date = $actualReturnDate;
        
        // Calculer les retards avec la date de retour correcte
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
            'has_damages' => 'boolean',
            'damage_notes' => 'nullable|string|max:2000',
            'damage_photos' => 'nullable|array',
            'inspection_notes' => 'required|string|max:2000'
        ])->validate();
        
        // Calculer automatiquement les coûts de dommages
        $damageCost = 0;
        $hasDamages = $validated['has_damages'] ?? false;
        
        if ($hasDamages && $this->auto_calculate_damages) {
            // Dommages = caution complète + frais de retard
            $damageCost = $this->deposit_amount + $this->late_fees;
        }
        
        // Calculer le total des pénalités
        $totalPenalties = $this->late_fees + $damageCost;
        
        // Important: Avec le système de pré-autorisation, il n'y a pas de "remboursement"
        // La caution est soit libérée (si pas de dommages) soit capturée (si dommages)
        $depositRefund = $hasDamages ? 0 : $this->deposit_amount;
        
        $this->update([
            'status' => 'finished',
            'inspection_status' => 'completed',
            'product_condition' => $validated['product_condition'],
            'has_damages' => $hasDamages,
            'damage_notes' => $validated['damage_notes'],
            'damage_photos' => $validated['damage_photos'] ?? [],
            'damage_cost' => $damageCost,
            'total_penalties' => $totalPenalties,
            'deposit_refund' => $depositRefund,
            'inspection_notes' => $validated['inspection_notes'],
            'inspection_completed_at' => now(),
            'inspected_by' => auth()->id()
        ]);
        
        // Email de rapport d'inspection envoyé automatiquement par le listener
        \Log::info("Inspection complétée: {$this->order_number} (email géré par le listener)");
        
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
        
        $oldStatus = $this->status;
        
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);
        
        // Déclencher manuellement l'event pour être sûr qu'il est traité
        event(new \App\Events\OrderLocationStatusChanged($this, $oldStatus, 'cancelled'));
        
        // Remettre le stock disponible SEULEMENT si la commande était confirmée/payée
        // (c'est-à-dire si le stock avait été décrémenté)
        if (in_array($this->payment_status, ['paid', 'processing']) || in_array($oldStatus, ['confirmed', 'active', 'ended', 'completed'])) {
            foreach ($this->items as $item) {
                $item->product->increaseRentalStock($item->quantity);
                \Log::info("Stock de location restauré après annulation", [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'quantity_restored' => $item->quantity,
                    'order_number' => $this->order_number
                ]);
            }
        } else {
            \Log::info("Annulation sans restauration de stock (commande non confirmée)", [
                'order_number' => $this->order_number,
                'payment_status' => $this->payment_status,
                'status' => $oldStatus
            ]);
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
        
        // Créer les articles de la commande et décrémenter le stock
        foreach ($cart->items as $cartItem) {
            $orderLocation->items()->create([
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'product_sku' => $cartItem->product->sku,
                'product_description' => $cartItem->product->description,
                'quantity' => $cartItem->quantity,
                'daily_rate' => $cartItem->product->daily_rental_price,
                'rental_days' => $rentalDays,
                'deposit_per_item' => $cartItem->product->deposit_amount,
                'subtotal' => $cartItem->product->daily_rental_price * $cartItem->quantity * $rentalDays,
                'total_deposit' => $cartItem->product->deposit_amount * $cartItem->quantity,
                'tax_amount' => ($cartItem->product->daily_rental_price * $cartItem->quantity * $rentalDays) * 0.21,
                'total_amount' => ($cartItem->product->daily_rental_price * $cartItem->quantity * $rentalDays) * 1.21
            ]);
            
            // NOTE: Le stock sera décrémenté lors de la confirmation du paiement via webhook
            // $cartItem->product->decreaseRentalStock($cartItem->quantity);
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
                'confirmed' => 'rental-order-confirmed',
                'active' => 'rental-order-started',
                'completed' => 'rental-order-completed',
                'finished' => 'rental-order-finished',
                'cancelled' => 'rental-order-cancelled',
                default => null
            };
            
            if ($template) {
                Mail::send($template, ['orderLocation' => $this], function ($message) use ($newStatus) {
                    $message->to($this->user->email, $this->user->name)
                            ->subject("Location {$this->order_number} - {$this->status_label}");
                });
                
                \Log::info("Email de notification envoyé pour la location {$this->order_number} - statut: {$newStatus}");
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
            $admins = User::whereIn('role', ['admin', 'Admin'])->get();
            
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
    public function sendInspectionReport()
    {
        try {
            Mail::to($this->user->email)->send(new \App\Mail\RentalOrderInspection($this));
            
            Log::info("Rapport d'inspection envoyé par email pour {$this->order_number}");
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

        // Déterminer le type de facture selon l'état de l'inspection
        $invoiceType = $this->inspection_completed_at ? 'final' : 'initial';
        
        // Charger les items avec les produits ET leurs catégories pour la traduction
        $items = $this->items()->with(['product.category'])->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.rental-invoice', [
            'orderLocation' => $this,
            'items' => $items,
            'user' => $this->user,
            'invoiceType' => $invoiceType, // Nouveau paramètre
            'company' => [
                'name' => config('app.name', 'FarmShop'),
                'address' => 'Avenue de la ferme 123',
                'postal_code' => '1000',
                'city' => 'Bruxelles',
                'country' => 'Belgique',
                'phone' => '+32 2 123 45 67',
                'email' => 's.mef2703@gmail.com',
                'vat_number' => 'BE0123456789'
            ]
        ]);

        $filename = 'facture-location-' . $this->invoice_number . '.pdf';
        $path = storage_path('app/invoices/rentals/');
        
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
        
        return DB::transaction(function () use ($prefix) {
            // Trouver le dernier numéro utilisé (inclus les supprimés pour éviter les conflits)
            $lastInvoice = static::withTrashed()
                ->where('invoice_number', 'like', $prefix . '%')
                ->orderBy('invoice_number', 'desc')
                ->first();

            if ($lastInvoice) {
                $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // Vérifier que le numéro n'existe pas déjà (sécurité)
            do {
                $invoiceNumber = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
                $exists = static::withTrashed()->where('invoice_number', $invoiceNumber)->exists();
                if ($exists) {
                    $newNumber++;
                }
            } while ($exists);
            
            // Mettre à jour directement dans la transaction
            $this->update(['invoice_number' => $invoiceNumber]);
            
            return $invoiceNumber;
        });
    }

    /**
     * Vérifier si la facture peut être générée
     */
    public function canGenerateInvoice()
    {
        return in_array($this->payment_status, ['paid', 'partially_paid', 'deposit_paid']) && 
               in_array($this->status, ['confirmed', 'active', 'completed', 'returned', 'inspecting', 'finished']);
    }

    /**
     * Générer un numéro de commande unique
     */
    public static function generateOrderNumber()
    {
        $prefix = 'LOC-' . date('Y') . date('m') . date('d');
        $maxRetries = 20;
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            // Générer un numéro aléatoire pour éviter les conflits
            $randomSuffix = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . $randomSuffix;
            
            // Vérifier l'unicité
            if (!static::where('order_number', $orderNumber)->exists()) {
                \Log::info("Numéro de commande généré: {$orderNumber} (tentative {$attempt})");
                return $orderNumber;
            }
            
            // Attendre un peu avant la prochaine tentative
            usleep(50000); // 50ms
        }
        
        // Fallback avec timestamp microseconde
        $timestamp = str_replace('.', '', microtime(true));
        $fallbackNumber = $prefix . substr($timestamp, -4);
        
        \Log::warning("Fallback numéro commande utilisé: {$fallbackNumber}");
        return $fallbackNumber;
    }
}
