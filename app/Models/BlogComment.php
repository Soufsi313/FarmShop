<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_post_id',
        'user_id',
        'parent_id',
        'content',
        'original_content',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'guest_name',
        'guest_email',
        'guest_website',
        'ip_address',
        'user_agent',
        'likes_count',
        'replies_count',
        'is_pinned',
        'reports_count',
        'is_reported',
        'metadata',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
        'edited_at' => 'datetime',
        'likes_count' => 'integer',
        'replies_count' => 'integer',
        'reports_count' => 'integer',
        'is_pinned' => 'boolean',
        'is_reported' => 'boolean',
        'is_edited' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'likes_count' => 0,
        'replies_count' => 0,
        'reports_count' => 0,
        'is_pinned' => false,
        'is_reported' => false,
        'is_edited' => false,
    ];

    // Relations
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    public function approvedReplies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->where('status', 'approved');
    }

    public function reports()
    {
        return $this->hasMany(BlogCommentReport::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeReported($query)
    {
        return $query->where('is_reported', true);
    }

    public function scopeByPost($query, $postId)
    {
        return $query->where('blog_post_id', $postId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc')
                    ->orderBy('replies_count', 'desc');
    }

    // Accesseurs
    public function getAuthorNameAttribute()
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    public function getAuthorEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    public function getIsGuestAttribute()
    {
        return !$this->user_id;
    }

    public function getCanEditAttribute()
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();
        
        // Admin peut toujours modifier
        if ($user->role === 'admin') {
            return true;
        }

        // Utilisateur peut modifier son propre commentaire dans les 15 minutes
        return $this->user_id === $user->id && 
               $this->created_at->diffInMinutes(now()) <= 15;
    }

    public function getCanDeleteAttribute()
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();
        
        // Admin peut toujours supprimer
        if ($user->role === 'admin') {
            return true;
        }

        // Utilisateur peut supprimer son propre commentaire
        return $this->user_id === $user->id;
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }

    // Méthodes métier
    public function approve($moderatorId = null)
    {
        $this->update([
            'status' => 'approved',
            'moderated_by' => $moderatorId ?: auth()->id(),
            'moderated_at' => now()
        ]);

        $this->post->incrementCommentsCount();
        
        if ($this->parent) {
            $this->parent->incrementRepliesCount();
        }
    }

    public function reject($reason = null, $moderatorId = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'moderated_by' => $moderatorId ?: auth()->id(),
            'moderated_at' => now()
        ]);
    }

    public function markAsSpam($moderatorId = null)
    {
        $this->update([
            'status' => 'spam',
            'moderated_by' => $moderatorId ?: auth()->id(),
            'moderated_at' => now()
        ]);
    }

    public function pin()
    {
        $this->update(['is_pinned' => true]);
    }

    public function unpin()
    {
        $this->update(['is_pinned' => false]);
    }

    public function incrementLikesCount()
    {
        $this->increment('likes_count');
    }

    public function incrementRepliesCount()
    {
        $this->increment('replies_count');
    }

    public function decrementRepliesCount()
    {
        $this->decrement('replies_count');
    }

    public function incrementReportsCount()
    {
        $this->increment('reports_count');
        $this->update(['is_reported' => true]);
    }

    public function updateRepliesCount()
    {
        $this->update([
            'replies_count' => $this->approvedReplies()->count()
        ]);
    }

    public function editContent($newContent)
    {
        $this->update([
            'original_content' => $this->original_content ?: $this->content,
            'content' => $newContent,
            'is_edited' => true,
            'edited_at' => now()
        ]);
    }

    // Recherche
    public function scopeSearch($query, $search)
    {
        return $query->where('content', 'like', "%{$search}%");
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            // Capturer l'IP et user agent
            if (request()) {
                $comment->ip_address = request()->ip();
                $comment->user_agent = request()->userAgent();
            }
        });

        static::deleted(function ($comment) {
            // Décrémenter le compteur de commentaires du post
            if ($comment->status === 'approved') {
                $comment->post->decrementCommentsCount();
                
                if ($comment->parent) {
                    $comment->parent->decrementRepliesCount();
                }
            }
        });
    }
}
