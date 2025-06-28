<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'reason',
        'message',
        'status',
        'assigned_to',
        'responded_at',
        'admin_notes',
        'ip_address',
        'user_agent',
        'attachments'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'attachments' => 'array'
    ];

    // Statuts possibles
    const STATUS_NEW = 'nouveau';
    const STATUS_IN_PROGRESS = 'en_cours';
    const STATUS_RESOLVED = 'resolu';
    const STATUS_CLOSED = 'ferme';

    // Raisons de contact
    const REASON_GENERAL_INFO = 'information_general';
    const REASON_PRODUCT_QUESTION = 'question_produit';
    const REASON_ORDER_ISSUE = 'probleme_commande';
    const REASON_QUOTE_REQUEST = 'demande_devis';
    const REASON_PARTNERSHIP = 'partenariat';
    const REASON_SUGGESTION = 'suggestion';
    const REASON_COMPLAINT = 'reclamation';
    const REASON_TECHNICAL_SUPPORT = 'support_technique';
    const REASON_OTHER = 'autre';

    /**
     * Relations
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Accesseurs
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_NEW => 'Nouveau',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_RESOLVED => 'Résolu',
            self::STATUS_CLOSED => 'Fermé'
        ];

        return $labels[$this->status] ?? 'Inconnu';
    }

    public function getReasonLabelAttribute(): string
    {
        $labels = [
            self::REASON_GENERAL_INFO => 'Information générale',
            self::REASON_PRODUCT_QUESTION => 'Question sur un produit',
            self::REASON_ORDER_ISSUE => 'Problème de commande',
            self::REASON_QUOTE_REQUEST => 'Demande de devis',
            self::REASON_PARTNERSHIP => 'Partenariat',
            self::REASON_SUGGESTION => 'Suggestion',
            self::REASON_COMPLAINT => 'Réclamation',
            self::REASON_TECHNICAL_SUPPORT => 'Support technique',
            self::REASON_OTHER => 'Autre'
        ];

        return $labels[$this->reason] ?? 'Inconnu';
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            self::STATUS_NEW => 'blue',
            self::STATUS_IN_PROGRESS => 'orange',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_CLOSED => 'gray'
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getResponseTimeAttribute(): ?string
    {
        if (!$this->responded_at) {
            return null;
        }

        $diffInHours = $this->created_at->diffInHours($this->responded_at);
        
        if ($diffInHours < 1) {
            return 'Moins d\'1 heure';
        } elseif ($diffInHours < 24) {
            return $diffInHours . ' heure' . ($diffInHours > 1 ? 's' : '');
        } else {
            $diffInDays = $this->created_at->diffInDays($this->responded_at);
            return $diffInDays . ' jour' . ($diffInDays > 1 ? 's' : '');
        }
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === self::STATUS_CLOSED || $this->status === self::STATUS_RESOLVED) {
            return false;
        }

        // Considéré en retard si pas de réponse après 24h
        return $this->responded_at === null && $this->created_at->diffInHours(now()) > 24;
    }

    public function getIsUrgentAttribute(): bool
    {
        return in_array($this->reason, [
            self::REASON_COMPLAINT,
            self::REASON_ORDER_ISSUE,
            self::REASON_TECHNICAL_SUPPORT
        ]);
    }

    /**
     * Scopes
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_IN_PROGRESS]);
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('reason', [
            self::REASON_COMPLAINT,
            self::REASON_ORDER_ISSUE,
            self::REASON_TECHNICAL_SUPPORT
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNull('responded_at')
                    ->where('created_at', '<', Carbon::now()->subHours(24))
                    ->whereNotIn('status', [self::STATUS_CLOSED, self::STATUS_RESOLVED]);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Marquer comme en cours de traitement
     */
    public function markInProgress(?int $assignedTo = null): bool
    {
        $updates = ['status' => self::STATUS_IN_PROGRESS];
        
        if ($assignedTo) {
            $updates['assigned_to'] = $assignedTo;
        }

        return $this->update($updates);
    }

    /**
     * Marquer comme résolu
     */
    public function markResolved(?string $adminNotes = null): bool
    {
        $updates = [
            'status' => self::STATUS_RESOLVED,
            'responded_at' => $this->responded_at ?? now()
        ];

        if ($adminNotes) {
            $updates['admin_notes'] = $adminNotes;
        }

        return $this->update($updates);
    }

    /**
     * Fermer la demande
     */
    public function close(?string $adminNotes = null): bool
    {
        $updates = [
            'status' => self::STATUS_CLOSED,
            'responded_at' => $this->responded_at ?? now()
        ];

        if ($adminNotes) {
            $updates['admin_notes'] = $adminNotes;
        }

        return $this->update($updates);
    }

    /**
     * Assigner à un administrateur
     */
    public function assignTo(int $userId): bool
    {
        return $this->update(['assigned_to' => $userId]);
    }

    /**
     * Marquer comme ayant reçu une première réponse
     */
    public function markResponded(): bool
    {
        if ($this->responded_at) {
            return false; // Déjà marqué
        }

        return $this->update(['responded_at' => now()]);
    }

    /**
     * Ajouter une note admin
     */
    public function addAdminNote(string $note): bool
    {
        $currentNotes = $this->admin_notes ? $this->admin_notes . "\n\n" : '';
        $newNote = '[' . now()->format('d/m/Y H:i') . '] ' . $note;
        
        return $this->update(['admin_notes' => $currentNotes . $newNote]);
    }

    /**
     * Méthodes statiques utilitaires
     */

    /**
     * Obtenir les statistiques des contacts
     */
    public static function getStatistics(): array
    {
        return [
            'total' => self::count(),
            'new' => self::new()->count(),
            'in_progress' => self::inProgress()->count(),
            'resolved' => self::resolved()->count(),
            'closed' => self::closed()->count(),
            'urgent' => self::urgent()->count(),
            'overdue' => self::overdue()->count(),
            'recent' => self::recent()->count(),
        ];
    }

    /**
     * Obtenir les raisons disponibles avec leurs labels
     */
    public static function getReasons(): array
    {
        return [
            self::REASON_GENERAL_INFO => 'Information générale',
            self::REASON_PRODUCT_QUESTION => 'Question sur un produit',
            self::REASON_ORDER_ISSUE => 'Problème de commande',
            self::REASON_QUOTE_REQUEST => 'Demande de devis',
            self::REASON_PARTNERSHIP => 'Partenariat',
            self::REASON_SUGGESTION => 'Suggestion',
            self::REASON_COMPLAINT => 'Réclamation',
            self::REASON_TECHNICAL_SUPPORT => 'Support technique',
            self::REASON_OTHER => 'Autre'
        ];
    }

    /**
     * Obtenir les statuts disponibles avec leurs labels
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW => 'Nouveau',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_RESOLVED => 'Résolu',
            self::STATUS_CLOSED => 'Fermé'
        ];
    }

    /**
     * Créer une nouvelle demande de contact
     */
    public static function createFromRequest(array $data): self
    {
        $contact = self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'reason' => $data['reason'],
            'message' => $data['message'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'attachments' => $data['attachments'] ?? null
        ]);

        // Envoyer notification aux admins si contact urgent
        if ($contact->is_urgent) {
            // TODO: Envoyer notification email aux admins
        }

        return $contact;
    }

    /**
     * Rechercher dans les contacts
     */
    public static function search(string $query)
    {
        return self::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('subject', 'like', "%{$query}%")
              ->orWhere('message', 'like', "%{$query}%");
        });
    }
}
