<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery',
        'status',
        'published_at',
        'scheduled_for',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'metadata',
        'tags',
        'views_count',
        'likes_count',
        'shares_count',
        'comments_count',
        'reading_time',
        'allow_comments',
        'is_featured',
        'is_sticky',
        'author_id',
        'last_edited_by',
        'last_edited_at',
    ];

    protected $casts = [
        'gallery' => 'array',
        'metadata' => 'array',
        'tags' => 'array',
        'published_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'last_edited_at' => 'datetime',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'shares_count' => 'integer',
        'comments_count' => 'integer',
        'reading_time' => 'decimal:2',
        'allow_comments' => 'boolean',
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'draft',
        'views_count' => 0,
        'likes_count' => 0,
        'shares_count' => 0,
        'comments_count' => 0,
        'allow_comments' => true,
        'is_featured' => false,
        'is_sticky' => false,
    ];

    // Relations
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)->where('status', 'approved');
    }

    public function topLevelComments()
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_for', '>', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('blog_category_id', $categoryId);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc')
                    ->orderBy('likes_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeWithTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    // Mutateurs
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (!$this->slug) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $value;
        $this->attributes['reading_time'] = $this->calculateReadingTime($value);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        
        if ($value === 'published' && !$this->published_at) {
            $this->attributes['published_at'] = now();
        }
    }

    // Accesseurs
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return asset('images/blog/default-post.jpg');
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return Str::limit(strip_tags($this->content), 200);
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: $this->excerpt;
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y à H:i') : null;
    }

    // Méthodes métier
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now()
        ]);
        
        $this->category->incrementPostsCount();
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null
        ]);
        
        $this->category->decrementPostsCount();
    }

    public function schedule($date)
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_for' => $date
        ]);
    }

    public function incrementViewsCount($count = 1)
    {
        $this->increment('views_count', $count);
        $this->category->incrementViewsCount($count);
    }

    public function incrementLikesCount()
    {
        $this->increment('likes_count');
    }

    public function incrementSharesCount()
    {
        $this->increment('shares_count');
    }

    public function incrementCommentsCount()
    {
        $this->increment('comments_count');
    }

    public function decrementCommentsCount()
    {
        $this->decrement('comments_count');
    }

    public function updateCommentsCount()
    {
        $this->update([
            'comments_count' => $this->approvedComments()->count()
        ]);
    }

    protected function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $readingSpeed = 200; // mots par minute
        return round($wordCount / $readingSpeed, 2);
    }

    // Recherche
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%")
              ->orWhere('meta_keywords', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (auth()->check()) {
                $post->author_id = auth()->id();
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('content') && auth()->check()) {
                $post->last_edited_by = auth()->id();
                $post->last_edited_at = now();
            }
        });

        static::deleted(function ($post) {
            if ($post->category) {
                $post->category->decrementPostsCount();
            }
        });
    }
}
