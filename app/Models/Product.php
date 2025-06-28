<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Constantes pour les unités
    const UNIT_KG = 'kg';
    const UNIT_PIECE = 'piece';
    const UNIT_LITER = 'liter';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'price',
        'quantity',
        'unit_symbol',
        'main_image',
        'critical_stock_threshold',
        'is_active',
        'is_featured',
        'bulk_pricing',
        'views_count',
        'likes_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'bulk_pricing' => 'array',
        'views_count' => 'integer',
        'likes_count' => 'integer',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });
    }

    /**
     * Relations
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function likes()
    {
        return $this->hasMany(ProductLike::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'product_likes')->withTimestamps();
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
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

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= critical_stock_threshold');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Méthodes de gestion du stock
     */
    public function decreaseStock($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->decrement('quantity', $quantity);
            
            // Vérifier si le seuil critique est atteint
            $this->checkCriticalStock();
            
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $this->increment('quantity', $quantity);
        return true;
    }

    public function updateStock($newQuantity)
    {
        $this->update(['quantity' => $newQuantity]);
        $this->checkCriticalStock();
    }

    /**
     * Vérification du stock critique
     */
    public function checkCriticalStock()
    {
        if ($this->isLowStock() || $this->isOutOfStock()) {
            $this->notifyAdminStockAlert();
        }
    }

    /**
     * Vérifications d'état du stock
     */
    public function isInStock()
    {
        return $this->quantity > 0;
    }

    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->critical_stock_threshold && $this->quantity > 0;
    }

    /**
     * Gestion des vues
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Gestion des likes
     */
    public function toggleLike(User $user)
    {
        $like = ProductLike::where('user_id', $user->id)
                          ->where('product_id', $this->id)
                          ->first();

        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
            return false; // Unlike
        } else {
            ProductLike::create([
                'user_id' => $user->id,
                'product_id' => $this->id,
            ]);
            $this->increment('likes_count');
            return true; // Like
        }
    }

    public function isLikedBy(User $user)
    {
        return ProductLike::where('user_id', $user->id)
                         ->where('product_id', $this->id)
                         ->exists();
    }

    /**
     * Gestion de la wishlist
     */
    public function toggleWishlist(User $user)
    {
        $wishlist = Wishlist::where('user_id', $user->id)
                           ->where('product_id', $this->id)
                           ->first();

        if ($wishlist) {
            $wishlist->delete();
            return false; // Retiré de la wishlist
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $this->id,
            ]);
            return true; // Ajouté à la wishlist
        }
    }

    public function isInWishlistOf(User $user)
    {
        return Wishlist::where('user_id', $user->id)
                      ->where('product_id', $this->id)
                      ->exists();
    }

    /**
     * Gestion des prix en vrac
     */
    public function getBulkPrice($quantity)
    {
        if (!$this->bulk_pricing || empty($this->bulk_pricing)) {
            return $this->price * $quantity;
        }

        $bulkPrices = collect($this->bulk_pricing)->sortByDesc('min_quantity');

        foreach ($bulkPrices as $bulk) {
            if ($quantity >= $bulk['min_quantity']) {
                return $bulk['price_per_unit'] * $quantity;
            }
        }

        return $this->price * $quantity;
    }

    public function hasBulkPricing()
    {
        return !empty($this->bulk_pricing);
    }

    /**
     * URLs et chemins
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('storage/products/' . $this->main_image);
        }
        return asset('images/default-product.png');
    }

    public function getRouteAttribute()
    {
        return route('products.show', $this->slug);
    }

    /**
     * Générer un slug unique
     */
    public static function generateUniqueSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Obtenir les unités disponibles
     */
    public static function getUnits()
    {
        return [
            self::UNIT_KG => 'Au kg',
            self::UNIT_PIECE => 'À la pièce',
            self::UNIT_LITER => 'Au litre',
        ];
    }

    public function getUnitLabelAttribute()
    {
        return self::getUnits()[$this->unit_symbol] ?? $this->unit_symbol;
    }

    /**
     * Notification aux admins pour le stock critique
     */
    protected function notifyAdminStockAlert()
    {
        // Récupérer tous les admins et superusers
        $admins = User::role(['admin', 'superuser'])->get();
        
        foreach ($admins as $admin) {
            if ($this->isOutOfStock()) {
                $admin->notify(new \App\Notifications\ProductOutOfStock($this));
            } elseif ($this->isLowStock()) {
                $admin->notify(new \App\Notifications\ProductLowStock($this));
            }
        }
        
        // Déclencher un événement pour d'autres listeners potentiels
        event('product.stock.alert', [
            'product' => $this,
            'type' => $this->isOutOfStock() ? 'stock_out' : 'stock_low'
        ]);
    }

    /**
     * Méthodes utilitaires
     */
    public function getStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'Rupture de stock';
        } elseif ($this->isLowStock()) {
            return 'Stock faible';
        } else {
            return 'En stock';
        }
    }

    public function getStockStatusColorAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'red';
        } elseif ($this->isLowStock()) {
            return 'orange';
        } else {
            return 'green';
        }
    }
}
