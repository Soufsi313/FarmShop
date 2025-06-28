<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
    ];

    /**
     * Relation avec le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/products/gallery/' . $this->image_path);
    }

    /**
     * Scope pour ordonner par ordre de tri
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
