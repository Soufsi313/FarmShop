<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SpecialOffer extends Model
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
        'product_id',
        'minimum_quantity',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
        'usage_count',
        'usage_limit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
            'discount_percentage' => 'decimal:2',
            'minimum_quantity' => 'integer',
            'usage_count' => 'integer',
            'usage_limit' => 'integer',
        ];
    }

    /**
     * Relations
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les offres valides (dans la période)
     */
    public function scopeValid($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope pour les offres disponibles (actives et sous la limite d'usage)
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereColumn('usage_count', '<', 'usage_limit');
                    });
    }

    /**
     * Méthodes utilitaires
     */
    
    /**
     * Vérifier si l'offre est actuellement valide
     */
    public function isCurrentlyValid(): bool
    {
        $now = Carbon::now();
        return $this->is_active 
            && $this->start_date <= $now 
            && $this->end_date >= $now
            && $this->isAvailable();
    }

    /**
     * Vérifier si l'offre est encore disponible (limite d'usage)
     */
    public function isAvailable(): bool
    {
        return is_null($this->usage_limit) || $this->usage_count < $this->usage_limit;
    }

    /**
     * Calculer la réduction pour une quantité donnée
     */
    public function calculateDiscount($quantity, $unitPrice): array
    {
        if (!$this->isCurrentlyValid() || $quantity < $this->minimum_quantity) {
            return [
                'applicable' => false,
                'discount_amount' => 0,
                'original_total' => $quantity * $unitPrice,
                'discounted_total' => $quantity * $unitPrice,
                'savings' => 0,
                'message' => null
            ];
        }

        $originalTotal = $quantity * $unitPrice;
        $discountAmount = ($originalTotal * $this->discount_percentage) / 100;
        $discountedTotal = $originalTotal - $discountAmount;

        return [
            'applicable' => true,
            'discount_amount' => $discountAmount,
            'original_total' => $originalTotal,
            'discounted_total' => $discountedTotal,
            'savings' => $discountAmount,
            'message' => "Offre appliquée : {$this->name} (-{$this->discount_percentage}%)",
            'offer_name' => $this->name,
            'discount_percentage' => $this->discount_percentage
        ];
    }

    /**
     * Marquer l'offre comme utilisée
     */
    public function markAsUsed(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Obtenir le statut de l'offre
     */
    public function getStatusAttribute(): string
    {
        $now = Carbon::now();
        
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        if ($this->start_date > $now) {
            return 'À venir';
        }
        
        if ($this->end_date < $now) {
            return 'Expirée';
        }
        
        if (!$this->isAvailable()) {
            return 'Limite atteinte';
        }
        
        return 'Active';
    }

    /**
     * Obtenir toutes les offres valides pour un produit
     */
    public static function getValidOffersForProduct($productId, $quantity = 1)
    {
        return self::where('product_id', $productId)
                   ->where('minimum_quantity', '<=', $quantity)
                   ->valid()
                   ->available()
                   ->orderBy('discount_percentage', 'desc')
                   ->get();
    }

    /**
     * Obtenir la meilleure offre applicable pour un produit et une quantité
     */
    public static function getBestOfferForProduct($productId, $quantity)
    {
        return static::getValidOffersForProduct($productId, $quantity)->first();
    }
}
