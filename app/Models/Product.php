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
        'min_rental_days',
        'max_rental_days',
        'available_days',
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
            'min_rental_days' => 'integer',
            'max_rental_days' => 'integer',
            'available_days' => 'array',
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

    /**
     * Vérifier si le produit est disponible pour la location
     */
    public function isRentable(): bool
    {
        return in_array($this->type, ['rental', 'both']) && 
               $this->is_active && 
               $this->rental_price_per_day > 0;
    }

    /**
     * Obtenir les jours de la semaine disponibles pour la location
     * Par défaut : Lundi à Samedi (1-6)
     */
    public function getAvailableDays(): array
    {
        return $this->available_days ?? [1, 2, 3, 4, 5, 6]; // Lundi à Samedi
    }

    /**
     * Vérifier si un jour de la semaine est disponible pour la location
     */
    public function isDayAvailable(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->getAvailableDays());
    }

    /**
     * Valider une période de location pour ce produit
     */
    public function validateRentalPeriod(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): array
    {
        $errors = [];
        
        // Vérifier que le produit est louable
        if (!$this->isRentable()) {
            $errors[] = "Ce produit n'est pas disponible à la location";
            return ['valid' => false, 'errors' => $errors];
        }
        
        // Vérifier que la date de début n'est pas aujourd'hui ou dans le passé
        if ($startDate->lte(now()->startOfDay())) {
            $errors[] = "La location ne peut pas commencer aujourd'hui. Date minimum : " . now()->addDay()->format('d/m/Y');
        }
        
        // Vérifier que la date de fin est après la date de début
        if ($endDate->lte($startDate)) {
            $errors[] = "La date de fin doit être après la date de début";
        }
        
        // Calculer la durée en jours
        $duration = $startDate->diffInDays($endDate) + 1; // +1 pour inclure le jour de début
        
        // Vérifier la durée minimale
        if ($duration < $this->min_rental_days) {
            $errors[] = "Durée minimale de location : {$this->min_rental_days} jour(s)";
        }
        
        // Vérifier la durée maximale
        if ($duration > $this->max_rental_days) {
            $errors[] = "Durée maximale de location : {$this->max_rental_days} jour(s)";
        }
        
        // Vérifier que tous les jours de la période sont disponibles
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dayOfWeek = $current->dayOfWeek === 0 ? 7 : $current->dayOfWeek; // Dimanche = 7, Lundi = 1
            
            if (!$this->isDayAvailable($dayOfWeek)) {
                $dayName = $this->getDayName($dayOfWeek);
                $errors[] = "Location non disponible le {$dayName} ({$current->format('d/m/Y')})";
            }
            
            $current->addDay();
        }
        
        $costDetails = empty($errors) ? $this->calculateRentalCost($startDate, $endDate) : null;
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'duration' => $duration,
            'cost_details' => $costDetails,
            'total_cost' => $costDetails ? $costDetails['total_cost'] : null,
            'deposit_required' => $this->deposit_amount
        ];
    }

    /**
     * Calculer le coût total d'une location
     */
    public function calculateRentalCost(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): array
    {
        $durationDays = $startDate->diffInDays($endDate) + 1;
        $dailyPrice = $this->rental_price_per_day;
        $subtotal = $dailyPrice * $durationDays;
        
        // Application d'éventuelles remises pour location longue
        $discount = 0;
        $discountPercentage = 0;
        
        if ($durationDays >= 5) {
            $discountPercentage = 5; // 5% de remise à partir de 5 jours
        }
        if ($durationDays >= 7) {
            $discountPercentage = 10; // 10% de remise pour la durée maximale
        }
        
        if ($discountPercentage > 0) {
            $discount = $subtotal * ($discountPercentage / 100);
        }
        
        $total = $subtotal - $discount;
        
        return [
            'duration_days' => $durationDays,
            'daily_price' => $dailyPrice,
            'subtotal' => round($subtotal, 2),
            'discount_percentage' => $discountPercentage,
            'discount_amount' => round($discount, 2),
            'total_cost' => round($total, 2),
            'deposit_required' => $this->deposit_amount,
            'currency' => 'EUR'
        ];
    }

    /**
     * Obtenir la prochaine date de début disponible
     */
    public function getNextAvailableStartDate(): \Carbon\Carbon
    {
        $date = now()->addDay()->startOfDay();
        
        // Trouver le prochain jour disponible
        while (!$this->isDayAvailable($date->dayOfWeek === 0 ? 7 : $date->dayOfWeek)) {
            $date->addDay();
        }
        
        return $date;
    }

    /**
     * Obtenir la prochaine date de fin disponible pour une date de début donnée
     */
    public function getNextAvailableEndDate(\Carbon\Carbon $startDate): \Carbon\Carbon
    {
        $endDate = $startDate->copy()->addDays($this->min_rental_days - 1);
        
        // S'assurer que la date de fin est un jour disponible
        while (!$this->isDayAvailable($endDate->dayOfWeek === 0 ? 7 : $endDate->dayOfWeek)) {
            $endDate->addDay();
        }
        
        return $endDate;
    }

    /**
     * Obtenir le nom du jour en français
     */
    private function getDayName(int $dayOfWeek): string
    {
        $days = [
            1 => 'Lundi',
            2 => 'Mardi', 
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        
        return $days[$dayOfWeek] ?? 'Jour inconnu';
    }

    /**
     * Obtenir les informations de contraintes de location
     */
    public function getRentalConstraints(): array
    {
        return [
            'min_days' => $this->min_rental_days,
            'max_days' => $this->max_rental_days,
            'available_days' => $this->getAvailableDays(),
            'available_days_names' => array_map(
                fn($day) => $this->getDayName($day), 
                $this->getAvailableDays()
            ),
            'daily_price' => $this->rental_price_per_day,
            'deposit_amount' => $this->deposit_amount,
            'next_available_start' => $this->getNextAvailableStartDate()->format('Y-m-d'),
            'business_hours' => 'Lundi - Samedi'
        ];
    }
}
