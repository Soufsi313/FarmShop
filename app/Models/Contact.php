<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email', 
        'phone',
        'subject',
        'reason',
        'message',
        'status',
        'is_read',
        'read_at',
        'admin_response',
        'admin_id',
        'responded_at',
        'email_sent',
        'email_sent_at',
        'priority',
        'metadata'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'metadata' => 'array'
    ];

    // Constantes pour les raisons
    const REASONS = [
        'mon_profil' => 'Mon Profil',
        'mes_achats' => 'Mes Achats', 
        'mes_locations' => 'Mes Locations',
        'mes_donnees' => 'Mes Données',
        'support_technique' => 'Support Technique',
        'partenariat' => 'Partenariat',
        'autre' => 'Autre'
    ];

    // Constantes pour les statuts
    const STATUSES = [
        'pending' => 'En Attente',
        'in_progress' => 'En Cours',
        'resolved' => 'Résolu',
        'closed' => 'Fermé'
    ];

    // Constantes pour les priorités
    const PRIORITIES = [
        'low' => 'Basse',
        'medium' => 'Moyenne', 
        'high' => 'Haute',
        'urgent' => 'Urgente'
    ];

    /**
     * Relation avec l'admin qui a répondu
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead($adminId = null): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'admin_id' => $adminId ?? auth()->id()
        ]);
    }

    /**
     * Ajouter une réponse d'admin
     */
    public function addAdminResponse(string $response, int $adminId): void
    {
        $this->update([
            'admin_response' => $response,
            'admin_id' => $adminId,
            'responded_at' => now(),
            'status' => 'resolved',
            'is_read' => true,
            'read_at' => $this->read_at ?? now()
        ]);
    }

    /**
     * Marquer l'email comme envoyé
     */
    public function markEmailSent(): void
    {
        $this->update([
            'email_sent' => true,
            'email_sent_at' => now()
        ]);
    }

    /**
     * Changer le statut
     */
    public function changeStatus(string $status): void
    {
        $this->update(['status' => $status]);
    }

    /**
     * Changer la priorité
     */
    public function changePriority(string $priority): void
    {
        $this->update(['priority' => $priority]);
    }

    /**
     * Vérifier si une réponse est nécessaire
     */
    public function needsResponse(): bool
    {
        return in_array($this->status, ['pending', 'in_progress']) && empty($this->admin_response);
    }

    /**
     * Obtenir le libellé de la raison
     */
    public function getReasonLabelAttribute(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Obtenir le libellé de la priorité
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    /**
     * Obtenir la couleur du statut (pour l'interface)
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'primary'
        };
    }

    /**
     * Obtenir la couleur de la priorité
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Scopes pour filtrer les contacts
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByReason($query, string $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeNeedsResponse($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress'])
                    ->whereNull('admin_response');
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Statistiques pour le dashboard admin
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'pending' => self::where('status', 'pending')->count(),
            'in_progress' => self::where('status', 'in_progress')->count(),
            'resolved' => self::where('status', 'resolved')->count(),
            'unread' => self::where('is_read', false)->count(),
            'needs_response' => self::needsResponse()->count(),
            'recent' => self::recent()->count(),
            'by_reason' => self::selectRaw('reason, COUNT(*) as count')
                              ->groupBy('reason')
                              ->pluck('count', 'reason')
                              ->toArray(),
            'by_priority' => self::selectRaw('priority, COUNT(*) as count')
                                ->groupBy('priority')
                                ->pluck('count', 'priority')
                                ->toArray()
        ];
    }
}
