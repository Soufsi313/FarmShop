<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sender_id',
        'type',
        'subject',
        'content',
        'metadata',
        'status',
        'priority',
        'read_at',
        'archived_at',
        'is_important',
        'action_url',
        'action_label'
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_important' => 'boolean'
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Méthodes utilitaires
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update([
            'status' => 'unread',
            'read_at' => null
        ]);
    }

    public function archive(): void
    {
        $this->update([
            'status' => 'archived',
            'archived_at' => now()
        ]);
    }

    public function unarchive(): void
    {
        $this->update([
            'status' => 'unread',
            'archived_at' => null
        ]);
    }

    public function toggleImportant(): bool
    {
        $newState = !$this->is_important;
        $this->update(['is_important' => $newState]);
        return $newState;
    }

    /**
     * Créer un message système
     */
    public static function createSystemMessage(
        int $userId,
        string $subject,
        string $content,
        array $metadata = [],
        string $priority = 'normal',
        bool $isImportant = false,
        string $actionUrl = null,
        string $actionLabel = null
    ): Message {
        return static::create([
            'user_id' => $userId,
            'type' => 'system',
            'subject' => $subject,
            'content' => $content,
            'metadata' => $metadata,
            'priority' => $priority,
            'is_important' => $isImportant,
            'action_url' => $actionUrl,
            'action_label' => $actionLabel
        ]);
    }

    /**
     * Créer un message de notification de commande
     */
    public static function createOrderNotification(
        int $userId,
        string $subject,
        string $content,
        int $orderId,
        string $orderNumber,
        string $priority = 'normal'
    ): Message {
        return static::create([
            'user_id' => $userId,
            'type' => 'order',
            'subject' => $subject,
            'content' => $content,
            'metadata' => [
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ],
            'priority' => $priority,
            'action_url' => "/orders/{$orderId}",
            'action_label' => 'Voir la commande'
        ]);
    }

    /**
     * Créer un message administrateur
     */
    public static function createAdminMessage(
        int $userId,
        int $senderId,
        string $subject,
        string $content,
        array $metadata = [],
        string $priority = 'normal'
    ): Message {
        return static::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => 'admin',
            'subject' => $subject,
            'content' => $content,
            'metadata' => $metadata,
            'priority' => $priority
        ]);
    }

    /**
     * Obtenir le libellé du type
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'system' => 'Système',
            'admin' => 'Administration',
            'order' => 'Commande',
            'notification' => 'Notification',
            default => 'Autre'
        };
    }

    /**
     * Obtenir le libellé de priorité
     */
    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            'low' => 'Faible',
            'normal' => 'Normale',
            'high' => 'Élevée',
            'urgent' => 'Urgente',
            default => 'Normale'
        };
    }

    /**
     * Vérifier si le message est non lu
     */
    public function isUnread(): bool
    {
        return $this->status === 'unread';
    }

    /**
     * Vérifier si le message est lu
     */
    public function isRead(): bool
    {
        return $this->status === 'read';
    }

    /**
     * Vérifier si le message est archivé
     */
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }
}
