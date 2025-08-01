<?php

namespace App\Models;

use App\Events\StockUpdated;
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
     * Relation avec les offres spéciales
     */
    public function specialOffers()
    {
        return $this->hasMany(SpecialOffer::class);
    }

    /**
     * Offres spéciales actives pour ce produit
     */
    public function activeSpecialOffers()
    {
        return $this->hasMany(SpecialOffer::class)->valid()->available();
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
     * Vérifier si le produit est disponible à la location
     */
    public function isRentable(): bool
    {
        return in_array($this->type, ['rental', 'both']) 
               && $this->is_active 
               && $this->quantity > 0 
               && !$this->is_out_of_stock;
    }

    /**
     * Obtenir les contraintes de location
     */
    public function getRentalConstraints(): array
    {
        return [
            'min_rental_days' => $this->min_rental_days ?? 1,
            'max_rental_days' => $this->max_rental_days ?? 30,
            'available_days' => $this->available_days ?? [1, 2, 3, 4, 5, 6, 7],
            'daily_price' => $this->rental_price_per_day,
            'deposit_amount' => $this->deposit_amount,
        ];
    }

    /**
     * Valider une période de location
     */
    public function validateRentalPeriod(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): array
    {
        $errors = [];
        $days = $startDate->diffInDays($endDate) + 1;

        // Vérifier les contraintes de durée
        if ($days < ($this->min_rental_days ?? 1)) {
            $errors[] = "Durée minimale de location : " . ($this->min_rental_days ?? 1) . " jour(s)";
        }

        if ($days > ($this->max_rental_days ?? 30)) {
            $errors[] = "Durée maximale de location : " . ($this->max_rental_days ?? 30) . " jour(s)";
        }

        // Vérifier que la date de début n'est pas dans le passé
        if ($startDate->lte(now()->startOfDay())) {
            $errors[] = "La date de début doit être dans le futur";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'duration_days' => $days
        ];
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
     * Vérifier si le produit est aimé par l'utilisateur connecté
     */
    public function isLikedByUser()
    {
        return auth()->check() && $this->isLikedBy(auth()->user());
    }

    /**
     * Obtenir le nombre total de likes
     */
    public function getLikesCount(): int
    {
        return $this->likes_count ?? 0;
    }

    /**
     * Obtenir le texte formaté pour le compteur de likes
     */
    public function getLikesCountText(): string
    {
        $count = $this->getLikesCount();
        if ($count === 0) {
            return '';
        } elseif ($count === 1) {
            return '1 like';
        } else {
            return $count . ' likes';
        }
    }

    /**
     * Vérifier si le produit est dans la wishlist de l'utilisateur connecté
     */
    public function isInUserWishlist()
    {
        return auth()->check() && $this->isInWishlistOf(auth()->user());
    }

    /**
     * Gestion du stock
     */
    public function decreaseStock($quantity)
    {
        $oldQuantity = $this->quantity;
        
        if ($this->quantity >= $quantity) {
            $this->decrement('quantity', $quantity);
            
            // Déclencher l'événement de mise à jour de stock
            event(new StockUpdated($this, $oldQuantity, $this->quantity, 'decrease'));
            
            // Vérifier et créer des alertes de stock après la diminution
            $this->checkStockAlerts($oldQuantity);
            
            return true;
        }
        return false;
    }

    public function increaseStock($quantity)
    {
        $oldQuantity = $this->quantity;
        $this->increment('quantity', $quantity);
        
        // Déclencher l'événement de mise à jour de stock
        event(new StockUpdated($this, $oldQuantity, $this->quantity, 'increase'));
        
        // Vérifier si on sort d'une alerte de stock critique
        $this->checkStockRecovery($oldQuantity);
    }

    public function setStock($quantity)
    {
        $oldQuantity = $this->quantity;
        $this->update(['quantity' => $quantity]);
        
        // Déclencher l'événement de mise à jour de stock
        event(new StockUpdated($this, $oldQuantity, $this->quantity, 'set'));
        
        // Vérifier les alertes après modification manuelle
        $this->checkStockAlerts($oldQuantity);
    }

    /**
     * Vérifier et créer des alertes de stock si nécessaire
     */
    protected function checkStockAlerts($previousQuantity = null)
    {
        // Éviter les alertes multiples pour le même état
        if ($previousQuantity !== null) {
            $previousStatus = $this->getStockStatusFromQuantity($previousQuantity);
            $currentStatus = $this->stock_status;
            
            // Si le statut n'a pas changé, pas besoin de nouvelle alerte
            if ($previousStatus === $currentStatus) {
                return;
            }
        }

        // Alerte rupture de stock
        if ($this->is_out_of_stock) {
            $this->createStockAlert('out_of_stock');
        }
        // Alerte stock critique
        elseif ($this->is_critical_stock) {
            $this->createStockAlert('critical_stock');
        }
        // Alerte stock faible
        elseif ($this->is_low_stock) {
            $this->createStockAlert('low_stock');
        }
    }

    /**
     * Vérifier si le produit sort d'une alerte critique
     */
    protected function checkStockRecovery($previousQuantity)
    {
        $previousStatus = $this->getStockStatusFromQuantity($previousQuantity);
        $currentStatus = $this->stock_status;
        
        // Si on passe d'un état critique à normal, notifier la récupération
        if (in_array($previousStatus, ['out_of_stock', 'critical']) && $currentStatus === 'in_stock') {
            $this->createStockAlert('stock_recovered');
        }
    }

    /**
     * Créer une alerte de stock dans la table messages
     */
    protected function createStockAlert($alertType)
    {
        // Éviter les doublons récents (moins de 1 heure)
        $recentAlert = Message::where('type', 'stock_alert')
            ->where('metadata->product_id', $this->id)
            ->where('metadata->alert_type', $alertType)
            ->where('created_at', '>', now()->subHour())
            ->exists();

        if ($recentAlert) {
            return;
        }

        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        $alertMessages = [
            'out_of_stock' => [
                'subject' => '🚨 RUPTURE DE STOCK - ' . $this->name,
                'content' => "Le produit \"{$this->name}\" (SKU: {$this->sku}) est en rupture de stock.\n\nStock actuel: {$this->quantity}\nSeuil critique: {$this->critical_threshold}\n\nAction immédiate requise pour reconstituer le stock.",
                'priority' => 'high'
            ],
            'critical_stock' => [
                'subject' => '⚠️ STOCK CRITIQUE - ' . $this->name,
                'content' => "Le produit \"{$this->name}\" (SKU: {$this->sku}) a atteint le seuil critique.\n\nStock actuel: {$this->quantity}\nSeuil critique: {$this->critical_threshold}\n\nRecommandation: Réapprovisionner rapidement.",
                'priority' => 'high'
            ],
            'low_stock' => [
                'subject' => '📉 STOCK FAIBLE - ' . $this->name,
                'content' => "Le produit \"{$this->name}\" (SKU: {$this->sku}) a un stock faible.\n\nStock actuel: {$this->quantity}\nSeuil de stock faible: {$this->low_stock_threshold}\n\nPensez à commander du stock supplémentaire.",
                'priority' => 'medium'
            ],
            'stock_recovered' => [
                'subject' => '✅ STOCK RECONSTITUÉ - ' . $this->name,
                'content' => "Le produit \"{$this->name}\" (SKU: {$this->sku}) a un stock reconstitué.\n\nStock actuel: {$this->quantity}\n\nLe produit est de nouveau disponible normalement.",
                'priority' => 'low'
            ]
        ];

        $alertData = $alertMessages[$alertType] ?? null;
        
        if (!$alertData) {
            return;
        }

        foreach ($admins as $admin) {
            Message::create([
                'user_id' => $admin->id,
                'sender_id' => null, // Message système
                'type' => 'stock_alert',
                'subject' => $alertData['subject'],
                'content' => $alertData['content'],
                'metadata' => [
                    'product_id' => $this->id,
                    'product_name' => $this->name,
                    'product_sku' => $this->sku,
                    'alert_type' => $alertType,
                    'current_stock' => $this->quantity,
                    'critical_threshold' => $this->critical_threshold,
                    'low_stock_threshold' => $this->low_stock_threshold,
                    'category' => $this->category?->name
                ],
                'status' => 'unread',
                'priority' => $alertData['priority'],
                'is_important' => in_array($alertType, ['out_of_stock', 'critical_stock']),
                'action_url' => "/admin/products/{$this->id}",
                'action_label' => 'Voir le produit'
            ]);
        }
    }

    /**
     * Obtenir le statut de stock basé sur une quantité donnée
     */
    protected function getStockStatusFromQuantity($quantity)
    {
        if ($quantity <= 0) {
            return 'out_of_stock';
        } elseif ($quantity <= $this->critical_threshold) {
            return 'critical';
        } elseif ($this->low_stock_threshold && $quantity <= $this->low_stock_threshold) {
            return 'low';
        }
        return 'in_stock';
    }

    /**
     * Vérifier si le produit a un stock faible
     */
    public function getIsLowStockAttribute()
    {
        return $this->low_stock_threshold && 
               $this->quantity <= $this->low_stock_threshold && 
               $this->quantity > $this->critical_threshold;
    }

    /**
     * Scope pour les produits avec stock faible
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->whereColumn('quantity', '>', 'critical_threshold')
                    ->whereNotNull('low_stock_threshold');
    }

    /**
     * Obtenir tous les produits nécessitant une attention (stock critique ou faible)
     */
    public static function getStockAlerts()
    {
        return static::where(function($query) {
            $query->outOfStock()
                  ->orWhere(function($q) {
                      $q->criticalStock();
                  })
                  ->orWhere(function($q) {
                      $q->lowStock();
                  });
        })->with('category')->get();
    }

    /**
     * Obtenir le nombre de produits par statut de stock
     */
    public static function getStockStatistics()
    {
        return [
            'total_products' => static::count(),
            'out_of_stock' => static::outOfStock()->count(),
            'critical_stock' => static::criticalStock()->count(),
            'low_stock' => static::lowStock()->count(),
            'in_stock' => static::inStock()->count(),
            'alerts' => static::getStockAlerts()->count()
        ];
    }

    /**
     * Get the tax rate for this product based on its category
     */
    public function getTaxRate(): float
    {
        // Si la catégorie est alimentaire, TVA réduite de 6%
        if ($this->category && $this->category->food_type === 'alimentaire') {
            return 6.00;
        }
        
        // Sinon, TVA normale de 21%
        return 21.00;
    }

    /**
     * Get the price excluding tax (HT)
     */
    public function getPriceExcludingTax(): float
    {
        $taxRate = $this->getTaxRate();
        return round($this->price / (1 + ($taxRate / 100)), 2);
    }

    /**
     * Get the tax amount for this product
     */
    public function getTaxAmount(): float
    {
        $priceHT = $this->getPriceExcludingTax();
        $taxRate = $this->getTaxRate();
        return round($priceHT * ($taxRate / 100), 2);
    }

    /**
     * Get formatted prices with tax details
     */
    public function getPriceDetails(): array
    {
        $priceHT = $this->getPriceExcludingTax();
        $taxAmount = $this->getTaxAmount();
        $taxRate = $this->getTaxRate();
        
        return [
            'price_ht' => $priceHT,
            'tax_amount' => $taxAmount,
            'price_ttc' => $this->price,
            'tax_rate' => $taxRate,
            'formatted' => [
                'price_ht' => number_format($priceHT, 2) . ' €',
                'tax_amount' => number_format($taxAmount, 2) . ' €',
                'price_ttc' => number_format($this->price, 2) . ' €',
                'tax_rate' => number_format($taxRate, 1) . '%'
            ]
        ];
    }

    /**
     * Get the main image URL for the product.
     */
    public function getImageUrlAttribute(): string
    {
        // Si une image principale est définie
        if (!empty($this->main_image)) {
            // Si c'est un chemin relatif, ajouter le préfixe storage avec URL relative
            if (!str_starts_with($this->main_image, 'http') && !str_starts_with($this->main_image, '/')) {
                return '/storage/' . $this->main_image;
            }
            return $this->main_image;
        }

        // Sinon, essayer les images de galerie
        if (!empty($this->gallery_images) && is_array($this->gallery_images) && count($this->gallery_images) > 0) {
            $firstImage = $this->gallery_images[0];
            if (!str_starts_with($firstImage, 'http') && !str_starts_with($firstImage, '/')) {
                return '/storage/' . $firstImage;
            }
            return $firstImage;
        }

        // Sinon, essayer le champ images
        if (!empty($this->images) && is_array($this->images) && count($this->images) > 0) {
            $firstImage = $this->images[0];
            if (!str_starts_with($firstImage, 'http') && !str_starts_with($firstImage, '/')) {
                return '/storage/' . $firstImage;
            }
            return $firstImage;
        }

        // Image par défaut basée sur la catégorie
        return $this->getDefaultImageUrl();
    }

    /**
     * Get default image URL based on category.
     */
    public function getDefaultImageUrl(): string
    {
        $categoryName = $this->category?->name ?? 'default';
        
        // Images par défaut selon la catégorie
        $defaultImages = [
            'Légumes' => '/images/default-vegetable.jpg',
            'Fruits' => '/images/default-fruit.jpg',
            'Herbes aromatiques' => '/images/default-herbs.jpg',
            'Légumineuses' => '/images/default-legumes.jpg',
            'Céréales' => '/images/default-cereals.jpg',
            'Plantes médicinales' => '/images/default-medicinal.jpg',
            'Équipements' => '/images/default-equipment.jpg',
            'Outils' => '/images/default-tools.jpg',
        ];

        return $defaultImages[$categoryName] ?? '/images/placeholder-product.jpg';
    }

    /**
     * Get all available images for the product.
     */
    public function getAllImagesAttribute(): array
    {
        $images = [];

        // Ajouter l'image principale
        if (!empty($this->main_image)) {
            $images[] = $this->image_url;
        }

        // Ajouter les images de galerie
        if (!empty($this->gallery_images) && is_array($this->gallery_images)) {
            foreach ($this->gallery_images as $image) {
                if (!str_starts_with($image, 'http') && !str_starts_with($image, '/')) {
                    $images[] = '/storage/' . $image;
                } else {
                    $images[] = $image;
                }
            }
        }

        // Ajouter les autres images
        if (!empty($this->images) && is_array($this->images)) {
            foreach ($this->images as $image) {
                if (!str_starts_with($image, 'http') && !str_starts_with($image, '/')) {
                    $images[] = '/storage/' . $image;
                } else {
                    $images[] = $image;
                }
            }
        }

        // Supprimer les doublons
        $images = array_unique($images);

        // Si aucune image, utiliser l'image par défaut
        if (empty($images)) {
            $images[] = $this->getDefaultImageUrl();
        }

        return $images;
    }

    /**
     * Récupère l'offre spéciale active valide pour une quantité donnée
     */
    public function getActiveSpecialOffer($quantity = 1)
    {
        return $this->specialOffers()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('minimum_quantity', '<=', $quantity)
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereColumn('usage_count', '<', 'usage_limit');
            })
            ->orderBy('discount_percentage', 'desc')
            ->first();
    }

    /**
     * Calcule le prix avec réduction pour une quantité donnée
     */
    public function getPriceForQuantity($quantity = 1)
    {
        $basePrice = $this->price * $quantity;
        $specialOffer = $this->getActiveSpecialOffer($quantity);
        
        if ($specialOffer) {
            $discount = ($basePrice * $specialOffer->discount_percentage) / 100;
            return $basePrice - $discount;
        }
        
        return $basePrice;
    }

    /**
     * Calcule le montant de la réduction pour une quantité donnée
     */
    public function getDiscountAmount($quantity = 1)
    {
        $basePrice = $this->price * $quantity;
        $discountedPrice = $this->getPriceForQuantity($quantity);
        
        return $basePrice - $discountedPrice;
    }

    /**
     * Récupère le pourcentage de réduction pour une quantité donnée
     */
    public function getDiscountPercentage($quantity = 1)
    {
        $specialOffer = $this->getActiveSpecialOffer($quantity);
        return $specialOffer ? $specialOffer->discount_percentage : 0;
    }

    /**
     * Vérifie si le produit a une offre spéciale active pour une quantité donnée
     */
    public function hasActiveSpecialOffer($quantity = 1)
    {
        return $this->getActiveSpecialOffer($quantity) !== null;
    }

    /**
     * Utiliser le slug comme clé de route
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
