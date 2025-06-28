<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCommentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_comment_id',
        'reporter_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'ip_address'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime'
    ];

    protected $dates = [
        'reviewed_at',
        'created_at',
        'updated_at'
    ];

    // ==================== RELATIONS ====================

    /**
     * Le commentaire signalé
     */
    public function comment()
    {
        return $this->belongsTo(BlogComment::class, 'blog_comment_id');
    }

    /**
     * L'utilisateur qui a signalé
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * L'admin qui a traité le signalement
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ==================== SCOPES ====================

    /**
     * Signalements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Signalements traités
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Signalements rejetés
     */
    public function scopeDismissed($query)
    {
        return $query->where('status', 'dismissed');
    }

    /**
     * Par raison de signalement
     */
    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    // ==================== ACCESSORS ====================

    /**
     * Libellé de la raison
     */
    public function getReasonLabelAttribute()
    {
        $reasons = [
            'spam' => 'Spam',
            'inappropriate_content' => 'Contenu inapproprié',
            'harassment' => 'Harcèlement',
            'hate_speech' => 'Discours de haine',
            'violence' => 'Violence',
            'personal_information' => 'Informations personnelles',
            'copyright' => 'Violation de droits d\'auteur',
            'other' => 'Autre'
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    /**
     * Vérifier si le signalement est en attente
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifier si le signalement a été traité
     */
    public function getIsReviewedAttribute()
    {
        return $this->status === 'reviewed';
    }

    // ==================== MÉTHODES MÉTIER ====================

    /**
     * Marquer comme traité
     */
    public function markAsReviewed(User $admin, $notes = null)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    /**
     * Rejeter le signalement
     */
    public function dismiss(User $admin, $notes = null)
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    /**
     * Remettre en attente
     */
    public function markAsPending()
    {
        $this->update([
            'status' => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'admin_notes' => null
        ]);
    }

    /**
     * Obtenir toutes les raisons disponibles
     */
    public static function getReasons()
    {
        return [
            'spam' => 'Spam',
            'inappropriate_content' => 'Contenu inapproprié',
            'harassment' => 'Harcèlement',
            'hate_speech' => 'Discours de haine',
            'violence' => 'Violence',
            'personal_information' => 'Informations personnelles',
            'copyright' => 'Violation de droits d\'auteur',
            'other' => 'Autre'
        ];
    }
}
