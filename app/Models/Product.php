<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'rental_price_per_day',
        'deposit_amount',
        'type',
        'quantity',
        'critical_threshold',
        'low_stock_threshold',
        'out_of_stock_threshold',
        'unit_symbol',
        'sku',
        'short_description',
        'weight',
        'dimensions',
        'main_image',
        'gallery_images',
        'images',
        'is_active',
        'is_featured',
        'category_id',
        'rental_category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rental_price_per_day' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'weight' => 'decimal:3',
            'gallery_images' => 'array',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Boot method pour générer automatiquement le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Relation avec la catégorie
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation avec la catégorie de location
     */
    public function rentalCategory()
    {
        return $this->belongsTo(RentalCategory::class);
    }

    /**
     * Relation avec les listes de souhaits
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Relation avec les likes
     */
    public function likes()
    {
        return $this->hasMany(ProductLike::class);
    }

    /**
     * Users qui ont ajouté ce produit à leur wishlist
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    /**
     * Users qui ont liké ce produit
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'product_likes');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeCriticalStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'critical_threshold')
                    ->where('quantity', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhereHas('category', function ($cat) use ($term) {
                  $cat->where('name', 'LIKE', "%{$term}%");
              });
        });
    }

    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Accessors et mutateurs
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' €';
    }

    public function getIsInStockAttribute()
    {
        return $this->quantity > 0;
    }

    public function getIsCriticalStockAttribute()
    {
        return $this->quantity <= $this->critical_threshold && $this->quantity > 0;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->quantity <= 0;
    }

    public function getStockStatusAttribute()
    {
        if ($this->is_out_of_stock) {
            return 'out_of_stock';
        } elseif ($this->is_critical_stock) {
            return 'critical';
        }
        return 'in_stock';
    }

    /**
     * Méthodes utilitaires
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateLikesCount()
    {
        $this->update(['likes_count' => $this->likes()->count()]);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isInWishlistOf(User $user)
    {
        return $this->wishlists()->where('user_id', $user->id)->exists();
    }

    /**
     * Gestion du stock
     */
    public function decreaseStock($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->decrement('quantity', $quantity);
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->increment('quantity', $quantity);
    }

    public function setStock($quantity)
    {
        $this->update(['quantity' => $quantity]);
    }
}
