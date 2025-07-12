<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
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
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot method pour générer automatiquement le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relation avec les produits (sera définie quand le modèle Product sera créé)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope pour récupérer seulement les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour récupérer les catégories avec leurs produits
     */
    public function scopeWithProducts($query)
    {
        return $query->with('products');
    }

    /**
     * Accessor pour obtenir l'URL de la catégorie
     */
    public function getUrlAttribute(): string
    {
        return route('categories.show', $this->slug);
    }

    /**
     * Vérifier si la catégorie a des produits
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Compter le nombre de produits dans cette catégorie
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Activer la catégorie
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver la catégorie
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }
}
