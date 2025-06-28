<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class OrderReturn extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'return_number',
        'quantity_returned',
        'refund_amount',
        'return_reason',
        'return_notes',
        'admin_notes',
        'status',
        'refund_status',
        'requested_at',
        'approved_at',
        'received_at',
        'refunded_at',
        'rejected_at',
        'refund_method',
        'refund_transaction_id',
        'is_within_return_period',
        'return_deadline',
        'images',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'refunded_at' => 'datetime',
        'rejected_at' => 'datetime',
        'return_deadline' => 'date',
        'refund_amount' => 'decimal:2',
        'is_within_return_period' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Les statuts possibles pour un retour
     */
    const STATUS_REQUESTED = 'requested';
    const STATUS_APPROVED = 'approved';
    const STATUS_RECEIVED = 'received';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_REJECTED = 'rejected';

    /**
     * Les statuts de remboursement possibles
     */
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_PROCESSING = 'processing';
    const REFUND_STATUS_COMPLETED = 'completed';
    const REFUND_STATUS_FAILED = 'failed';

    /**
     * Les raisons de retour possibles
     */
    const REASON_DEFECTIVE = 'defective';
    const REASON_NOT_AS_DESCRIBED = 'not_as_described';
    const REASON_WRONG_ITEM = 'wrong_item';
    const REASON_CHANGED_MIND = 'changed_mind';
    const REASON_DAMAGED_SHIPPING = 'damaged_shipping';
    const REASON_OTHER = 'other';

    /**
     * Obtenir tous les statuts possibles
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_REQUESTED,
            self::STATUS_APPROVED,
            self::STATUS_RECEIVED,
            self::STATUS_REFUNDED,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Obtenir tous les statuts de remboursement possibles
     */
    public static function getAllRefundStatuses(): array
    {
        return [
            self::REFUND_STATUS_PENDING,
            self::REFUND_STATUS_PROCESSING,
            self::REFUND_STATUS_COMPLETED,
            self::REFUND_STATUS_FAILED,
        ];
    }

    /**
     * Obtenir toutes les raisons de retour possibles
     */
    public static function getAllReasons(): array
    {
        return [
            self::REASON_DEFECTIVE,
            self::REASON_NOT_AS_DESCRIBED,
            self::REASON_WRONG_ITEM,
            self::REASON_CHANGED_MIND,
            self::REASON_DAMAGED_SHIPPING,
            self::REASON_OTHER,
        ];
    }

    /**
     * Relation avec la commande
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relation avec l'article de commande
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour filtrer par statut de remboursement
     */
    public function scopeByRefundStatus(Builder $query, string $refundStatus): Builder
    {
        return $query->where('refund_status', $refundStatus);
    }

    /**
     * Scope pour les retours en attente
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope pour les retours approuvés
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope pour les retours traités
     */
    public function scopeProcessed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    /**
     * Scope pour les retours rejetés
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Vérifier si le retour est en attente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Vérifier si le retour est approuvé
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Vérifier si le retour est rejeté
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Vérifier si le retour est traité
     */
    public function isProcessed(): bool
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    /**
     * Vérifier si le remboursement est terminé
     */
    public function isRefundCompleted(): bool
    {
        return $this->refund_status === self::REFUND_STATUS_COMPLETED;
    }

    /**
     * Approuver le retour
     */
    public function approve(?string $adminNotes = null): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->status = self::STATUS_APPROVED;
        $this->approved_at = Carbon::now();
        if ($adminNotes) {
            $this->admin_notes = $adminNotes;
        }

        return $this->save();
    }

    /**
     * Rejeter le retour
     */
    public function reject(string $adminNotes): bool
    {
        if (!$this->isPending()) {
            return false;
        }

        $this->status = self::STATUS_REJECTED;
        $this->admin_notes = $adminNotes;

        return $this->save();
    }

    /**
     * Traiter le retour (marquer comme traité)
     */
    public function process(?string $adminNotes = null): bool
    {
        if (!$this->isApproved()) {
            return false;
        }

        $this->status = self::STATUS_PROCESSED;
        $this->processed_at = Carbon::now();
        if ($adminNotes) {
            $this->admin_notes = $adminNotes;
        }

        return $this->save();
    }

    /**
     * Initier le remboursement
     */
    public function initiateRefund(float $amount): bool
    {
        if (!$this->isApproved() && !$this->isProcessed()) {
            return false;
        }

        $this->refund_amount = $amount;
        $this->refund_status = self::REFUND_STATUS_PROCESSING;

        return $this->save();
    }

    /**
     * Marquer le remboursement comme terminé
     */
    public function completeRefund(): bool
    {
        if ($this->refund_status !== self::REFUND_STATUS_PROCESSING) {
            return false;
        }

        $this->refund_status = self::REFUND_STATUS_COMPLETED;

        return $this->save();
    }

    /**
     * Marquer le remboursement comme échoué
     */
    public function failRefund(): bool
    {
        if ($this->refund_status !== self::REFUND_STATUS_PROCESSING) {
            return false;
        }

        $this->refund_status = self::REFUND_STATUS_FAILED;

        return $this->save();
    }

    /**
     * Calculer le montant total du retour
     */
    public function calculateRefundAmount(): float
    {
        if (!$this->orderItem) {
            return 0;
        }

        return $this->orderItem->price * $this->quantity_returned;
    }

    /**
     * Obtenir le libellé de la raison
     */
    public function getReasonLabelAttribute(): string
    {
        $reasons = [
            self::REASON_DEFECTIVE => 'Produit défectueux',
            self::REASON_NOT_AS_DESCRIBED => 'Non conforme à la description',
            self::REASON_WRONG_ITEM => 'Mauvais article',
            self::REASON_CHANGED_MIND => 'Changement d\'avis',
            self::REASON_DAMAGED_SHIPPING => 'Endommagé pendant l\'expédition',
            self::REASON_OTHER => 'Autre',
        ];

        return $reasons[$this->reason] ?? 'Inconnu';
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_APPROVED => 'Approuvé',
            self::STATUS_REJECTED => 'Rejeté',
            self::STATUS_PROCESSED => 'Traité',
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    /**
     * Obtenir le libellé du statut de remboursement
     */
    public function getRefundStatusLabelAttribute(): string
    {
        $statuses = [
            self::REFUND_STATUS_PENDING => 'En attente',
            self::REFUND_STATUS_PROCESSING => 'En cours',
            self::REFUND_STATUS_COMPLETED => 'Terminé',
            self::REFUND_STATUS_FAILED => 'Échoué',
        ];

        return $statuses[$this->refund_status] ?? 'Inconnu';
    }

    /**
     * Vérifier si les images peuvent être ajoutées
     */
    public function canAddImages(): bool
    {
        return $this->isPending();
    }

    /**
     * Ajouter des images au retour
     */
    public function addImages(array $imagePaths): bool
    {
        if (!$this->canAddImages()) {
            return false;
        }

        $currentImages = $this->images ?? [];
        $this->images = array_merge($currentImages, $imagePaths);

        return $this->save();
    }

    /**
     * Vérifier si un retour peut être créé pour un article de commande
     */
    public static function canCreateReturn(OrderItem $orderItem): bool
    {
        // Vérifier si le produit est retournable
        if (!$orderItem->product->isReturnableProduct()) {
            return false;
        }

        // Vérifier si la commande est éligible au retour
        if (!$orderItem->order->isEligibleForReturn()) {
            return false;
        }

        // Vérifier la période de retour
        if (!$orderItem->product->isWithinReturnPeriod($orderItem->order->created_at)) {
            return false;
        }

        // Vérifier qu'il n'y a pas déjà un retour en cours pour cet article
        $existingReturn = self::where('order_item_id', $orderItem->id)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->exists();

        return !$existingReturn;
    }

    /**
     * Obtenir les raisons de retour pour un produit périssable (vide car non retournable)
     */
    public static function getPerishableReturnMessage(): string
    {
        return 'Les produits périssables ne peuvent pas être retournés pour des raisons d\'hygiène et de sécurité alimentaire.';
    }

    /**
     * Créer un retour avec validation des règles métier
     */
    public static function createReturn(OrderItem $orderItem, array $data): ?self
    {
        if (!self::canCreateReturn($orderItem)) {
            return null;
        }

        $return = new self([
            'order_id' => $orderItem->order_id,
            'order_item_id' => $orderItem->id,
            'user_id' => $orderItem->order->user_id,
            'quantity_returned' => min($data['quantity_returned'], $orderItem->quantity),
            'reason' => $data['reason'],
            'description' => $data['description'] ?? null,
            'status' => self::STATUS_PENDING,
            'refund_status' => self::REFUND_STATUS_PENDING,
        ]);

        $return->save();
        return $return;
    }
}
