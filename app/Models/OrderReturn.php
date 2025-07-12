<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'return_number',
        'quantity_returned',
        'reason',
        'description',
        'images',
        'status',
        'status_history',
        'status_updated_at',
        'requested_at',
        'approved_at',
        'item_received_at',
        'inspected_at',
        'refunded_at',
        'refund_amount',
        'refund_method',
        'refund_transaction_id',
        'refund_processed',
        'return_shipping_address',
        'return_tracking_number',
        'return_shipping_cost',
        'inspection_result',
        'inspection_notes',
        'inspection_images',
        'inspected_by',
        'approved_by',
        'admin_notes',
        'rejection_reason',
        'email_notifications_sent',
        'last_notification_sent_at',
        'metadata',
    ];

    protected $casts = [
        'images' => 'array',
        'status_history' => 'array',
        'status_updated_at' => 'datetime',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'item_received_at' => 'datetime',
        'inspected_at' => 'datetime',
        'refunded_at' => 'datetime',
        'quantity_returned' => 'integer',
        'refund_amount' => 'decimal:2',
        'refund_processed' => 'boolean',
        'return_shipping_address' => 'array',
        'return_shipping_cost' => 'decimal:2',
        'inspection_images' => 'array',
        'email_notifications_sent' => 'array',
        'last_notification_sent_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'requested',
        'refund_processed' => false,
        'return_shipping_cost' => 0,
    ];

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeItemReceived($query)
    {
        return $query->where('status', 'item_received');
    }

    public function scopeInspected($query)
    {
        return $query->where('status', 'inspected');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePendingInspection($query)
    {
        return $query->where('status', 'item_received');
    }

    public function scopePendingRefund($query)
    {
        return $query->where('status', 'inspected')
                    ->where('inspection_result', 'approved')
                    ->where('refund_processed', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accesseurs
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'requested' => 'Demandé',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'item_received' => 'Article reçu',
            'inspected' => 'Inspecté',
            'refunded' => 'Remboursé',
            'cancelled' => 'Annulé'
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    public function getReasonLabelAttribute()
    {
        $reasons = [
            'defective' => 'Produit défectueux',
            'wrong_item' => 'Mauvais article',
            'not_as_described' => 'Non conforme à la description',
            'changed_mind' => 'Changement d\'avis',
            'damaged_shipping' => 'Endommagé pendant le transport',
            'other' => 'Autre'
        ];

        return $reasons[$this->reason] ?? 'Inconnu';
    }

    public function getInspectionResultLabelAttribute()
    {
        if (!$this->inspection_result) {
            return 'Non inspecté';
        }

        $results = [
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'partial' => 'Partiel'
        ];

        return $results[$this->inspection_result] ?? 'Inconnu';
    }

    public function getFormattedRefundAmountAttribute()
    {
        return number_format($this->refund_amount, 2) . ' €';
    }

    public function getCanBeApprovedAttribute()
    {
        return $this->status === 'requested';
    }

    public function getCanBeRejectedAttribute()
    {
        return in_array($this->status, ['requested', 'approved']);
    }

    public function getCanBeInspectedAttribute()
    {
        return $this->status === 'item_received';
    }

    public function getCanBeRefundedAttribute()
    {
        return $this->status === 'inspected' && 
               $this->inspection_result === 'approved' && 
               !$this->refund_processed;
    }

    // Méthodes métier
    public function updateStatus($newStatus, $adminId = null, $notes = null)
    {
        $oldStatus = $this->status;
        
        // Mettre à jour l'historique
        $history = $this->status_history ?: [];
        $history[] = [
            'from' => $oldStatus,
            'to' => $newStatus,
            'timestamp' => now()->toISOString(),
            'admin_id' => $adminId,
            'notes' => $notes
        ];

        $updateData = [
            'status' => $newStatus,
            'status_history' => $history,
            'status_updated_at' => now()
        ];

        // Actions spécifiques selon le statut
        switch ($newStatus) {
            case 'approved':
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = $adminId;
                break;
                
            case 'item_received':
                $updateData['item_received_at'] = now();
                break;
                
            case 'inspected':
                $updateData['inspected_at'] = now();
                $updateData['inspected_by'] = $adminId;
                break;
                
            case 'refunded':
                $updateData['refunded_at'] = now();
                $updateData['refund_processed'] = true;
                break;
        }

        if ($notes) {
            $updateData['admin_notes'] = $notes;
        }

        $this->update($updateData);

        // Envoyer notification
        $this->sendStatusNotification($oldStatus, $newStatus);

        return $this;
    }

    public function approve($adminId = null, $notes = null)
    {
        if (!$this->can_be_approved) {
            throw new \Exception('Ce retour ne peut pas être approuvé');
        }

        $this->updateStatus('approved', $adminId, $notes);
        
        return $this;
    }

    public function reject($reason, $adminId = null, $notes = null)
    {
        if (!$this->can_be_rejected) {
            throw new \Exception('Ce retour ne peut pas être rejeté');
        }

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $adminId,
            'admin_notes' => $notes,
            'status_updated_at' => now()
        ]);

        $this->sendStatusNotification($this->status, 'rejected');

        return $this;
    }

    public function markAsReceived($trackingNumber = null, $adminId = null)
    {
        $this->update([
            'status' => 'item_received',
            'item_received_at' => now(),
            'return_tracking_number' => $trackingNumber,
            'status_updated_at' => now()
        ]);

        $this->sendStatusNotification('approved', 'item_received');

        return $this;
    }

    public function inspect($result, $notes = null, $images = null, $adminId = null)
    {
        if (!$this->can_be_inspected) {
            throw new \Exception('Ce retour ne peut pas être inspecté');
        }

        $this->update([
            'status' => 'inspected',
            'inspection_result' => $result,
            'inspection_notes' => $notes,
            'inspection_images' => $images,
            'inspected_by' => $adminId,
            'inspected_at' => now(),
            'status_updated_at' => now()
        ]);

        // Si approuvé, programmer le remboursement automatique
        if ($result === 'approved') {
            $this->processAutomaticRefund();
        }

        $this->sendStatusNotification('item_received', 'inspected');

        return $this;
    }

    public function processAutomaticRefund()
    {
        if (!$this->can_be_refunded) {
            throw new \Exception('Ce retour ne peut pas être remboursé');
        }

        // Logique de remboursement automatique
        // Intégrer avec le système de paiement
        
        $this->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_processed' => true,
            'refund_method' => $this->order->payment_method,
            'refund_transaction_id' => 'REF-' . time(), // Générer un ID réel
            'status_updated_at' => now()
        ]);

        $this->sendStatusNotification('inspected', 'refunded');

        return $this;
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

        // Envoyer l'email
        // Mail::to($this->user->email)->send(new ReturnStatusChanged($this, $oldStatus, $newStatus));
    }

    // Méthodes statiques
    public static function generateReturnNumber()
    {
        $prefix = 'RET-' . date('Y') . date('m');
        $lastReturn = static::where('return_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReturn) {
            $lastNumber = intval(substr($lastReturn->return_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            if (!$return->return_number) {
                $return->return_number = static::generateReturnNumber();
            }
            
            if (!$return->requested_at) {
                $return->requested_at = now();
            }
        });
    }
}
