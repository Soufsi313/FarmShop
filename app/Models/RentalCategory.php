<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RentalCategory extends Model
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

        static::creating(function ($rentalCategory) {
            if (empty($rentalCategory->slug)) {
                $rentalCategory->slug = Str::slug($rentalCategory->name);
            }
        });

        static::updating(function ($rentalCategory) {
            if ($rentalCategory->isDirty('name')) {
                $rentalCategory->slug = Str::slug($rentalCategory->name);
            }
        });
    }

    /**
     * Relation avec les produits de location (sera définie quand le modèle RentalProduct sera créé)
     */
    // public function rentalProducts()
    // {
    //     return $this->hasMany(RentalProduct::class);
    // }

    /**
     * Scope pour récupérer seulement les catégories de location actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor pour obtenir l'URL de la catégorie de location
     */
    public function getUrlAttribute(): string
    {
        return route('rental-categories.show', $this->slug);
    }

    /**
     * Vérifier si la catégorie de location a des produits
     */
    public function hasRentalProducts(): bool
    {
        // Temporaire : retourne false jusqu'à ce que le modèle RentalProduct soit créé
        return false;
        // return $this->rentalProducts()->exists();
    }

    /**
     * Compter le nombre de produits de location dans cette catégorie
     */
    public function getRentalProductsCountAttribute(): int
    {
        // Temporaire : retourne 0 jusqu'à ce que le modèle RentalProduct soit créé
        return 0;
        // return $this->rentalProducts()->count();
    }

    /**
     * Activer la catégorie de location
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Désactiver la catégorie de location
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }
}
