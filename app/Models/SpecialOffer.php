<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SpecialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'product_id',
        'min_quantity',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Relation avec le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope pour les offres actives
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
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Scope pour les offres actives et valides
     */
    public function scopeAvailable($query)
    {
        return $query->active()->valid();
    }

    /**
     * Vérifier si l'offre est actuellement disponible
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Vérifier si la quantité donnée qualifie pour cette offre
     */
    public function qualifiesForOffer(int $quantity): bool
    {
        return $this->isAvailable() && $quantity >= $this->min_quantity;
    }

    /**
     * Calculer la remise pour une quantité donnée
     */
    public function calculateDiscount(int $quantity, float $unitPrice): array
    {
        if (!$this->qualifiesForOffer($quantity)) {
            return [
                'qualifies' => false,
                'original_total' => $quantity * $unitPrice,
                'discount_amount' => 0,
                'final_total' => $quantity * $unitPrice,
                'savings' => 0
            ];
        }

        $originalTotal = $quantity * $unitPrice;
        $discountAmount = $originalTotal * ($this->discount_percentage / 100);
        $finalTotal = $originalTotal - $discountAmount;

        return [
            'qualifies' => true,
            'original_total' => $originalTotal,
            'discount_amount' => $discountAmount,
            'final_total' => $finalTotal,
            'savings' => $discountAmount,
            'discount_percentage' => $this->discount_percentage
        ];
    }

    /**
     * Obtenir le statut de l'offre
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = now();
        
        if ($now->lt($this->start_date)) {
            return 'scheduled';
        }

        if ($now->gt($this->end_date)) {
            return 'expired';
        }

        return 'active';
    }

    /**
     * Vérifier si l'offre est active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Vérifier si l'offre est programmée (pas encore commencée)
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Vérifier si l'offre est expirée
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Vérifier si l'offre est inactive
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Obtenir les jours restants avant expiration
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if ($this->status !== 'active') {
            return null;
        }

        return $this->end_date->diffInDays(now());
    }
}
