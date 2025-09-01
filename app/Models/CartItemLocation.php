<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CartItemLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_location_id',
        'product_id',
        'start_date',
        'end_date',
        'duration_days',
        'quantity',
        'unit_price_per_day',
        'unit_deposit',
        'subtotal_amount',
        'subtotal_deposit',
        'tva_amount',
        'total_amount',
        'product_name',
        'product_sku',
        'rental_category_name',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'unit_price_per_day' => 'decimal:2',
        'unit_deposit' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
        'subtotal_deposit' => 'decimal:2',
        'tva_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'metadata' => 'array'
    ];

    protected $appends = [
        'translated_category_name'
    ];

    // Taux de TVA par défaut
    const TVA_RATE = 0.20; // 20%

    // Relations
    public function cartLocation(): BelongsTo
    {
        return $this->belongsTo(CartLocation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Events model
    protected static function booted()
    {
        static::creating(function ($cartItem) {
            $cartItem->calculateAmounts();
        });

        static::updating(function ($cartItem) {
            if ($cartItem->isDirty(['quantity', 'unit_price_per_day', 'unit_deposit', 'duration_days'])) {
                $cartItem->calculateAmounts();
            }
        });
    }

    // Méthodes de calcul

    /**
     * Calculer tous les montants de la ligne
     */
    public function calculateAmounts(): void
    {
        // Calcul du sous-total HT (prix par jour * quantité * durée)
        $this->subtotal_amount = $this->unit_price_per_day * $this->quantity * $this->duration_days;
        
        // Calcul du sous-total caution (caution unitaire * quantité)
        $this->subtotal_deposit = $this->unit_deposit * $this->quantity;
        
        // Calcul de la TVA (sur le montant de location seulement)
        $this->tva_amount = $this->subtotal_amount * self::TVA_RATE;
        
        // Calcul du total TTC (sous-total + TVA)
        $this->total_amount = $this->subtotal_amount + $this->tva_amount;
    }

    /**
     * Mettre à jour la quantité
     */
    public function updateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \Exception('La quantité doit être supérieure à 0');
        }

        $this->update(['quantity' => $quantity]);
    }

    /**
     * Mettre à jour les dates de location
     */
    public function updateDates(Carbon $startDate, Carbon $endDate): void
    {
        if ($startDate->gt($endDate)) {
            throw new \Exception('La date de début doit être antérieure à la date de fin');
        }

        if ($startDate->lt(Carbon::today())) {
            throw new \Exception('La date de début ne peut pas être dans le passé');
        }

        $durationDays = $startDate->diffInDays($endDate) + 1;

        $this->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_days' => $durationDays
        ]);
    }

    /**
     * Obtenir le nom de catégorie traduit
     */
    public function getTranslatedCategoryName(): string
    {
        // Mapping des noms de catégories français vers les clés de traduction
        $translationKeys = [
            'Outils agricoles' => 'outils-agricoles',
            'Machines' => 'machines',
            'Équipements' => 'equipements',
        ];
        
        $translationKey = $translationKeys[$this->rental_category_name] ?? null;
        
        if ($translationKey) {
            return __('app.rental_categories.' . $translationKey);
        }
        
        // Fallback vers le nom original si pas de traduction trouvée
        return $this->rental_category_name;
    }

    /**
     * Accesseur pour le nom de catégorie traduit (attribut calculé)
     */
    public function getTranslatedCategoryNameAttribute(): string
    {
        return $this->getTranslatedCategoryName();
    }

    /**
     * Obtenir les informations de disponibilité du produit
     */
    public function getAvailabilityInfo(): array
    {
        $product = $this->product;
        
        if (!$product) {
            return [
                'available' => false,
                'message' => 'Produit non trouvé',
                'current_stock' => 0,
                'requested_quantity' => $this->quantity
            ];
        }

        try {
            $this->cartLocation->checkProductAvailability(
                $product,
                $this->quantity,
                Carbon::parse($this->start_date),
                Carbon::parse($this->end_date),
                $this->id
            );

            return [
                'available' => true,
                'message' => 'Disponible pour la période sélectionnée',
                'current_stock' => $product->quantity,
                'requested_quantity' => $this->quantity,
                'period' => [
                    'start_date' => $this->start_date->format('d/m/Y'),
                    'end_date' => $this->end_date->format('d/m/Y'),
                    'duration_days' => $this->duration_days
                ]
            ];
        } catch (\Exception $e) {
            return [
                'available' => false,
                'message' => $e->getMessage(),
                'current_stock' => $product->quantity,
                'requested_quantity' => $this->quantity,
                'period' => [
                    'start_date' => $this->start_date->format('d/m/Y'),
                    'end_date' => $this->end_date->format('d/m/Y'),
                    'duration_days' => $this->duration_days
                ]
            ];
        }
    }

    /**
     * Obtenir les détails formatés pour l'affichage
     */
    public function toDisplayArray(): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product_name,
                'sku' => $this->product_sku,
                'rental_category' => $this->getTranslatedCategoryName(),
                'current_stock' => $this->product?->quantity ?? 0
            ],
            'rental_period' => [
                'start_date' => $this->start_date->format('d/m/Y'),
                'end_date' => $this->end_date->format('d/m/Y'),
                'duration_days' => $this->duration_days
            ],
            'quantity' => $this->quantity,
            'pricing' => [
                'unit_price_per_day' => $this->unit_price_per_day,
                'unit_deposit' => $this->unit_deposit,
                'subtotal_amount' => $this->subtotal_amount,
                'subtotal_deposit' => $this->subtotal_deposit,
                'tva_amount' => $this->tva_amount,
                'total_amount' => $this->total_amount
            ],
            'calculations' => [
                'total_rental_days' => $this->duration_days * $this->quantity,
                'daily_cost_all_items' => $this->unit_price_per_day * $this->quantity,
                'total_deposit_required' => $this->subtotal_deposit
            ],
            'notes' => $this->notes,
            'availability' => $this->getAvailabilityInfo(),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i')
        ];
    }

    /**
     * Vérifier si l'élément peut être modifié
     */
    public function canBeModified(): bool
    {
        // L'élément peut être modifié si la date de début n'est pas dépassée
        return Carbon::parse($this->start_date)->gte(Carbon::today());
    }

    /**
     * Obtenir le coût total par jour pour cet élément
     */
    public function getDailyCost(): float
    {
        return $this->unit_price_per_day * $this->quantity;
    }

    /**
     * Obtenir le coût total pour toute la période
     */
    public function getTotalCost(): float
    {
        return $this->total_amount;
    }

    /**
     * Obtenir la caution totale requise
     */
    public function getTotalDeposit(): float
    {
        return $this->subtotal_deposit;
    }

    /**
     * Obtenir le prix moyen par jour (incluant TVA)
     */
    public function getAverageDailyPriceWithTax(): float
    {
        return $this->total_amount / $this->duration_days;
    }

    /**
     * Vérifier si la période de location se chevauche avec une autre période
     */
    public function overlapsWith(Carbon $startDate, Carbon $endDate): bool
    {
        $itemStart = Carbon::parse($this->start_date);
        $itemEnd = Carbon::parse($this->end_date);

        return $itemStart->lte($endDate) && $itemEnd->gte($startDate);
    }

    /**
     * Obtenir les jours restants avant le début de la location
     */
    public function getDaysUntilStart(): int
    {
        return Carbon::today()->diffInDays(Carbon::parse($this->start_date), false);
    }

    /**
     * Obtenir le statut de la période de location
     */
    public function getRentalStatus(): string
    {
        $today = Carbon::today();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        if ($today->lt($startDate)) {
            return 'upcoming'; // À venir
        } elseif ($today->between($startDate, $endDate)) {
            return 'active'; // En cours
        } else {
            return 'completed'; // Terminée
        }
    }
}
