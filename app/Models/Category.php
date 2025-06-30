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
     * Obtenir le taux de TVA pour cette catégorie
     * 
     * @return float Le taux de TVA (ex: 0.06 pour 6%, 0.21 pour 21%)
     */
    public function getTaxRate()
    {
        // Produits alimentaires (périssables et non-périssables) : 6% TVA
        if (in_array($this->food_type, [self::FOOD_TYPE_PERISHABLE, self::FOOD_TYPE_NON_PERISHABLE])) {
            return 0.06;
        }
        
        // Produits non-alimentaires : 21% TVA
        return 0.21;
    }

    /**
     * Vérifier si la catégorie contient des produits alimentaires
     * 
     * @return bool
     */
    public function isFood()
    {
        return in_array($this->food_type, [self::FOOD_TYPE_PERISHABLE, self::FOOD_TYPE_NON_PERISHABLE]);
    }

    /**
     * Vérifier si la catégorie contient des produits périssables
     * 
     * @return bool
     */
    public function isPerishable()
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
