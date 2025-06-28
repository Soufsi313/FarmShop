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

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
}
