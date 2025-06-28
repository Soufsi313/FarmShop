<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // Constantes pour les types de catégories
    const TYPE_PURCHASE = 'purchase';
    const TYPE_RENTAL = 'rental';
    const TYPE_BOTH = 'both';

    // Constantes pour les types alimentaires
    const FOOD_TYPE_PERISHABLE = 'perishable';
    const FOOD_TYPE_NON_PERISHABLE = 'non_perishable';
    const FOOD_TYPE_NON_FOOD = 'non_food';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'food_type',
        'allows_returns',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allows_returns' => 'boolean',
    ];

    /**
     * Boot du modèle pour générer automatiquement le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });
    }

    /**
     * Relation avec les produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner par ordre de tri
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope pour les catégories d'achat (purchase ou both)
     */
    public function scopeForPurchase($query)
    {
        return $query->whereIn('type', [self::TYPE_PURCHASE, self::TYPE_BOTH]);
    }

    /**
     * Scope pour les catégories de location (rental ou both)
     */
    public function scopeForRental($query)
    {
        return $query->whereIn('type', [self::TYPE_RENTAL, self::TYPE_BOTH]);
    }

    /**
     * Scope pour filtrer par type exact
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Obtenir le nombre de produits actifs dans cette catégorie
     */
    public function getActiveProductsCountAttribute()
    {
        return $this->products()->active()->count();
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
     * Obtenir l'URL de l'image de la catégorie
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/categories/' . $this->image);
        }
        return asset('images/default-category.png');
    }

    /**
     * Obtenir la route de la catégorie
     */
    public function getRouteAttribute()
    {
        return route('categories.show', $this->slug);
    }

    /**
     * Vérifie si la catégorie est pour les achats
     */
    public function isPurchaseCategory()
    {
        return in_array($this->type, [self::TYPE_PURCHASE, self::TYPE_BOTH]);
    }

    /**
     * Vérifie si la catégorie est pour les locations
     */
    public function isRentalCategory()
    {
        return in_array($this->type, [self::TYPE_RENTAL, self::TYPE_BOTH]);
    }

    /**
     * Obtenir le libellé du type de catégorie
     */
    public function getTypeLabel()
    {
        switch($this->type) {
            case self::TYPE_PURCHASE:
                return 'Achat uniquement';
            case self::TYPE_RENTAL:
                return 'Location uniquement';
            case self::TYPE_BOTH:
                return 'Achat et Location';
            default:
                return 'Non défini';
        }
    }

    /**
     * Obtenir tous les types disponibles avec leurs libellés
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_PURCHASE => 'Achat uniquement',
            self::TYPE_RENTAL => 'Location uniquement',
            self::TYPE_BOTH => 'Achat et Location'
        ];
    }

    /**
     * Vérifier si la catégorie permet les retours
     */
    public function allowsReturns(): bool
    {
        return $this->allows_returns && $this->food_type !== self::FOOD_TYPE_PERISHABLE;
    }

    /**
     * Vérifier si la catégorie est alimentaire périssable
     */
    public function isPerishable(): bool
    {
        return $this->food_type === self::FOOD_TYPE_PERISHABLE;
    }

    /**
     * Vérifier si la catégorie est alimentaire non périssable
     */
    public function isNonPerishableFood(): bool
    {
        return $this->food_type === self::FOOD_TYPE_NON_PERISHABLE;
    }

    /**
     * Vérifier si la catégorie est non alimentaire
     */
    public function isNonFood(): bool
    {
        return $this->food_type === self::FOOD_TYPE_NON_FOOD;
    }

    /**
     * Obtenir le libellé du type alimentaire
     */
    public function getFoodTypeLabel(): string
    {
        switch($this->food_type) {
            case self::FOOD_TYPE_PERISHABLE:
                return 'Alimentaire périssable';
            case self::FOOD_TYPE_NON_PERISHABLE:
                return 'Alimentaire non périssable';
            case self::FOOD_TYPE_NON_FOOD:
                return 'Non alimentaire';
            default:
                return 'Non défini';
        }
    }

    /**
     * Scope pour les catégories retournables
     */
    public function scopeReturnable($query)
    {
        return $query->where('allows_returns', true)
                    ->where('food_type', '!=', self::FOOD_TYPE_PERISHABLE);
    }

    /**
     * Scope pour les catégories alimentaires
     */
    public function scopeFood($query)
    {
        return $query->whereIn('food_type', [self::FOOD_TYPE_PERISHABLE, self::FOOD_TYPE_NON_PERISHABLE]);
    }

    /**
     * Scope pour les catégories non alimentaires
     */
    public function scopeNonFood($query)
    {
        return $query->where('food_type', self::FOOD_TYPE_NON_FOOD);
    }

    /**
     * Obtenir tous les types alimentaires disponibles
     */
    public static function getFoodTypes(): array
    {
        return [
            self::FOOD_TYPE_PERISHABLE => 'Alimentaire périssable',
            self::FOOD_TYPE_NON_PERISHABLE => 'Alimentaire non périssable',
            self::FOOD_TYPE_NON_FOOD => 'Non alimentaire',
        ];
    }
}
