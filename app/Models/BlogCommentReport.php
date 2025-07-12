<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCommentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_comment_id',
        'reported_by',
        'reason',
        'description',
        'additional_info',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'action_taken',
        'priority',
        'reporter_ip',
        'reporter_user_agent',
        'evidence',
    ];

    protected $casts = [
        'additional_info' => 'array',
        'evidence' => 'array',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'priority' => 'medium',
    ];

    // Relations
    public function comment()
    {
        return $this->belongsTo(BlogComment::class, 'blog_comment_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeDismissed($query)
    {
        return $query->where('status', 'dismissed');
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accesseurs
    public function getReasonLabelAttribute()
    {
        $reasons = [
            'spam' => 'Spam',
            'inappropriate_content' => 'Contenu inapproprié',
            'harassment' => 'Harcèlement',
            'hate_speech' => 'Discours de haine',
            'false_information' => 'Fausse information',
            'copyright_violation' => 'Violation de droits d\'auteur',
            'other' => 'Autre'
        ];

        return $reasons[$this->reason] ?? 'Inconnu';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'reviewed' => 'Examiné',
            'resolved' => 'Résolu',
            'dismissed' => 'Rejeté'
        ];

        return $statuses[$this->status] ?? 'Inconnu';
    }

    public function getPriorityLabelAttribute()
    {
        $priorities = [
            'low' => 'Faible',
            'medium' => 'Moyenne',
            'high' => 'Élevée',
            'urgent' => 'Urgente'
        ];

        return $priorities[$this->priority] ?? 'Inconnu';
    }

    public function getActionTakenLabelAttribute()
    {
        if (!$this->action_taken) {
            return 'Aucune action';
        }

        $actions = [
            'none' => 'Aucune action',
            'warning_sent' => 'Avertissement envoyé',
            'comment_hidden' => 'Commentaire masqué',
            'comment_deleted' => 'Commentaire supprimé',
            'user_warned' => 'Utilisateur averti',
            'user_suspended' => 'Utilisateur suspendu',
            'user_banned' => 'Utilisateur banni'
        ];

        return $actions[$this->action_taken] ?? 'Action inconnue';
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    public function getFormattedReviewedDateAttribute()
    {
        return $this->reviewed_at ? $this->reviewed_at->format('d/m/Y à H:i') : null;
    }

    // Méthodes métier
    public function review($reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $reviewerId ?: auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    public function resolve($action = 'none', $reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'action_taken' => $action,
            'reviewed_by' => $reviewerId ?: auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);

        // Appliquer l'action sur le commentaire si nécessaire
        $this->applyAction($action);
    }

    public function dismiss($reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $reviewerId ?: auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    public function updatePriority($priority)
    {
        $this->update(['priority' => $priority]);
    }

    protected function applyAction($action)
    {
        $comment = $this->comment;
        
        switch ($action) {
            case 'comment_hidden':
                $comment->update(['status' => 'rejected']);
                break;
                
            case 'comment_deleted':
                $comment->delete();
                break;
                
            case 'user_warned':
                // Logique pour avertir l'utilisateur
                break;
                
            case 'user_suspended':
                // Logique pour suspendre l'utilisateur
                break;
                
            case 'user_banned':
                // Logique pour bannir l'utilisateur
                break;
        }
    }

    // Recherche
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhere('admin_notes', 'like', "%{$search}%")
              ->orWhereHas('comment', function ($commentQuery) use ($search) {
                  $commentQuery->where('content', 'like', "%{$search}%");
              });
        });
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            // Capturer l'IP et user agent du signaleur
            if (request()) {
                $report->reporter_ip = request()->ip();
                $report->reporter_user_agent = request()->userAgent();
            }
        });

        static::created(function ($report) {
            // Incrémenter le compteur de signalements du commentaire
            $report->comment->incrementReportsCount();
        });
    }
}
