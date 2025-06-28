<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_id',
        'user_id',
        'author_name',
        'content',
        'status',
        'parent_id',
        'ip_address',
        'user_agent',
        'reports_count',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'reports_count' => 'integer'
    ];

    protected $dates = [
        'approved_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // ==================== RELATIONS ====================

    /**
     * L'article commenté
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * L'auteur du commentaire
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le modérateur qui a approuvé le commentaire
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Commentaire parent (pour les réponses)
     */
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Réponses à ce commentaire
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('status', 'approved');
    }

    /**
     * Tous les commentaires enfants (récursif)
     */
    public function allReplies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    /**
     * Signalements de ce commentaire
     */
    public function reports()
    {
        return $this->hasMany(BlogCommentReport::class);
    }

    // ==================== SCOPES ====================

    /**
     * Commentaires approuvés seulement
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Commentaires en attente de modération
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Commentaires principaux (pas de réponses)
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Commentaires avec signalements
     */
    public function scopeReported($query)
    {
        return $query->where('reports_count', '>', 0);
    }

    // ==================== ACCESSORS ====================

    /**
     * Nom d'affichage de l'auteur
     */
    public function getDisplayNameAttribute()
    {
        if ($this->user_id && $this->user) {
            return $this->user->name;
        }
        
        // Si le compte a été supprimé, afficher "Compte supprimé"
        if ($this->author_name && !$this->user) {
            return 'Compte supprimé';
        }
        
        return $this->author_name ?? 'Utilisateur anonyme';
    }

    /**
     * Vérifier si le commentaire est approuvé
     */
    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    /**
     * Vérifier si le commentaire est signalé
     */
    public function getIsReportedAttribute()
    {
        return $this->reports_count > 0;
    }

    /**
     * Vérifier si c'est une réponse
     */
    public function getIsReplyAttribute()
    {
        return !is_null($this->parent_id);
    }

    // ==================== MÉTHODES MÉTIER ====================

    /**
     * Approuver le commentaire
     */
    public function approve(User $moderator = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $moderator ? $moderator->id : null
        ]);
    }

    /**
     * Rejeter le commentaire
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Masquer le commentaire
     */
    public function hide()
    {
        $this->update(['status' => 'hidden']);
    }

    /**
     * Remettre en attente
     */
    public function pending()
    {
        $this->update([
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null
        ]);
    }

    /**
     * Incrémenter le nombre de signalements
     */
    public function incrementReports()
    {
        $this->increment('reports_count');
    }

    /**
     * Réinitialiser les signalements
     */
    public function resetReports()
    {
        $this->update(['reports_count' => 0]);
    }

    /**
     * Vérifier si un utilisateur peut modifier ce commentaire
     */
    public function canBeEditedBy(User $user)
    {
        return $user->hasPermissionTo('manage blogs') || $user->id === $this->user_id;
    }

    /**
     * Vérifier si un utilisateur peut signaler ce commentaire
     */
    public function canBeReportedBy(User $user)
    {
        // Un utilisateur ne peut pas signaler son propre commentaire
        if ($user->id === $this->user_id) {
            return false;
        }

        // Vérifier si l'utilisateur a déjà signalé ce commentaire
        return !$this->reports()->where('reporter_id', $user->id)->exists();
    }

    /**
     * Sauvegarder le nom de l'auteur avant suppression du compte
     */
    public function preserveAuthorName()
    {
        if ($this->user && !$this->author_name) {
            $this->update(['author_name' => $this->user->name]);
        }
    }
}
