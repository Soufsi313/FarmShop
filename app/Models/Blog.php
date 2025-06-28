<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug', 
        'content',
        'excerpt',
        'featured_image',
        'status',
        'published_at',
        'author_id',
        'views_count',
        'comments_enabled',
        'meta_data'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'comments_enabled' => 'boolean',
        'meta_data' => 'array',
        'views_count' => 'integer'
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // ==================== RELATIONS ====================

    /**
     * L'auteur de l'article (admin)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Les commentaires de l'article
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Les commentaires approuvés et visibles
     */
    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)->where('status', 'approved')->orderBy('created_at', 'desc');
    }

    // ==================== SCOPES ====================

    /**
     * Articles publiés seulement
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Articles par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Articles récents
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()->latest('published_at')->limit($limit);
    }

    /**
     * Articles populaires (par nombre de vues)
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->published()->orderBy('views_count', 'desc')->limit($limit);
    }

    // ==================== MUTATORS ====================

    /**
     * Génère automatiquement le slug à partir du titre
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateUniqueSlug($value);
        }
    }

    /**
     * Génère automatiquement l'extrait s'il n'est pas fourni
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $value;
        
        if (empty($this->attributes['excerpt'])) {
            $this->attributes['excerpt'] = Str::limit(strip_tags($value), 150);
        }
    }

    // ==================== ACCESSORS ====================

    /**
     * URL complète de l'article
     */
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Temps de lecture estimé
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // 200 mots par minute
        return $readingTime . ' min de lecture';
    }

    /**
     * Vérifier si l'article est publié
     */
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    /**
     * Nombre total de commentaires approuvés
     */
    public function getCommentsCountAttribute()
    {
        return $this->approvedComments()->count();
    }

    // ==================== MÉTHODES MÉTIER ====================

    /**
     * Publier l'article
     */
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now()
        ]);
    }

    /**
     * Mettre en brouillon
     */
    public function draft()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null
        ]);
    }

    /**
     * Archiver l'article
     */
    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    /**
     * Incrémenter le nombre de vues
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Activer/désactiver les commentaires
     */
    public function toggleComments()
    {
        $this->update(['comments_enabled' => !$this->comments_enabled]);
    }

    /**
     * Générer un slug unique
     */
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Vérifier si un utilisateur peut modifier cet article
     */
    public function canBeEditedBy(User $user)
    {
        return $user->hasPermissionTo('manage blogs') || $user->id === $this->author_id;
    }

    /**
     * Obtenir les articles similaires
     */
    public function getSimilarArticles($limit = 3)
    {
        return static::published()
                    ->where('id', '!=', $this->id)
                    ->latest('published_at')
                    ->limit($limit)
                    ->get();
    }
}
