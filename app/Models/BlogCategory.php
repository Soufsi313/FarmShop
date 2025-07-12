<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'featured_image',
        'metadata',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'posts_count',
        'views_count',
        'created_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'posts_count' => 'integer',
        'views_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'color' => '#28a745',
        'is_active' => true,
        'sort_order' => 0,
        'posts_count' => 0,
        'views_count' => 0,
    ];

    // Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function publishedPosts()
    {
        return $this->hasMany(BlogPost::class)->where('status', 'published');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeWithPostsCount($query)
    {
        return $query->withCount(['posts', 'publishedPosts']);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('posts_count', 'desc')->orderBy('views_count', 'desc');
    }

    // Mutateurs
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accesseurs
    public function getUrlAttribute()
    {
        return route('blog.category', $this->slug);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return asset('images/blog/default-category.jpg');
    }

    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name;
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit($this->description, 160);
    }

    // Méthodes métier
    public function incrementPostsCount()
    {
        $this->increment('posts_count');
    }

    public function decrementPostsCount()
    {
        $this->decrement('posts_count');
    }

    public function incrementViewsCount($count = 1)
    {
        $this->increment('views_count', $count);
    }

    public function updatePostsCount()
    {
        $this->update([
            'posts_count' => $this->posts()->count()
        ]);
    }

    public function isUsed()
    {
        return $this->posts()->exists();
    }

    public function canBeDeleted()
    {
        return !$this->isUsed();
    }

    // Recherche
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('meta_keywords', 'like', "%{$search}%");
        });
    }

    // Événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (auth()->check()) {
                $category->created_by = auth()->id();
            }
        });

        static::deleting(function ($category) {
            if ($category->isUsed()) {
                throw new \Exception('Cannot delete category with existing posts');
            }
        });
    }
}
