<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use HasFactory;

    // Statuts de retour
    const RETURN_NOT_RETURNED = 'not_returned';
    const RETURN_PARTIAL = 'partial_returned';
    const RETURN_FULL = 'fully_returned';
    const RETURN_DAMAGED = 'damaged_returned';
    const RETURN_LOST = 'lost';

    // États des produits
    const CONDITION_EXCELLENT = 'excellent';
    const CONDITION_GOOD = 'good';
    const CONDITION_FAIR = 'fair';
    const CONDITION_POOR = 'poor';

    protected $fillable = [
        'rental_id',
        'product_id',
        'quantity',
        'rental_price_per_day',
        'deposit_amount_per_item',
        'total_rental_amount',
        'total_deposit_amount',
        'condition_at_pickup',
        'condition_at_return',
        'damage_notes',
        'return_status',
        'returned_quantity',
        'returned_at',
    ];

    protected $casts = [
        'rental_price_per_day' => 'decimal:2',
        'deposit_amount_per_item' => 'decimal:2',
        'total_rental_amount' => 'decimal:2',
        'total_deposit_amount' => 'decimal:2',
        'returned_at' => 'datetime',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            // Calculer les totaux automatiquement
            $item->calculateTotals();
        });
        
        static::updating(function ($item) {
            // Recalculer les totaux si nécessaire
            if ($item->isDirty(['quantity', 'rental_price_per_day', 'deposit_amount_per_item'])) {
                $item->calculateTotals();
            }
        });
    }

    /**
     * Relations
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function penalties()
    {
        return $this->hasMany(RentalPenalty::class);
    }

    /**
     * Accessors
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->returned_quantity;
    }

    public function getIsFullyReturnedAttribute()
    {
        return $this->return_status === self::RETURN_FULL;
    }

    public function getIsPartiallyReturnedAttribute()
    {
        return $this->return_status === self::RETURN_PARTIAL;
    }

    public function getHasDamageAttribute()
    {
        return $this->return_status === self::RETURN_DAMAGED || 
               !empty($this->damage_notes);
    }

    public function getReturnPercentageAttribute()
    {
        return $this->quantity > 0 ? 
            round(($this->returned_quantity / $this->quantity) * 100, 2) : 0;
    }

    /**
     * Méthodes métier
     */
    public function calculateTotals()
    {
        if ($this->rental && $this->quantity && $this->rental_price_per_day) {
            $days = $this->rental->duration_in_days;
            $this->total_rental_amount = $this->quantity * $this->rental_price_per_day * $days;
            $this->total_deposit_amount = $this->quantity * $this->deposit_amount_per_item;
        }
    }

    public function processReturn($data)
    {
        $returnedQty = (int) $data['returned_quantity'];
        $condition = $data['condition_at_return'] ?? null;
        $damageNotes = $data['damage_notes'] ?? null;
        
        // Valider la quantité retournée
        $returnedQty = min($returnedQty, $this->remaining_quantity);
        
        $this->returned_quantity += $returnedQty;
        $this->condition_at_return = $condition;
        $this->damage_notes = $damageNotes;
        $this->returned_at = now();
        
        // Déterminer le statut de retour
        if ($this->returned_quantity >= $this->quantity) {
            $this->return_status = $damageNotes ? self::RETURN_DAMAGED : self::RETURN_FULL;
        } elseif ($this->returned_quantity > 0) {
            $this->return_status = self::RETURN_PARTIAL;
        }
        
        // Remettre en stock
        $this->product->increment('quantity', $returnedQty);
        
        // Calculer les amendes pour dommages si nécessaire
        if ($condition === self::CONDITION_POOR || $damageNotes) {
            $this->calculateDamagePenalty($damageNotes);
        }
        
        $this->save();
    }

    public function markAsLost($quantity = null)
    {
        $lostQty = $quantity ?? $this->remaining_quantity;
        
        $this->return_status = self::RETURN_LOST;
        $this->damage_notes = "Produit perdu - {$lostQty} unité(s)";
        
        // Amende pour perte (valeur de remplacement)
        $this->penalties()->create([
            'rental_id' => $this->rental_id,
            'type' => 'loss',
            'reason' => "Perte de {$lostQty} unité(s)",
            'amount' => $lostQty * $this->product->price,
            'description' => "Coût de remplacement du produit perdu"
        ]);
        
        $this->save();
    }

    protected function calculateDamagePenalty($damageDescription)
    {
        // Calculer une amende basée sur la gravité des dommages
        $penaltyAmount = 0;
        
        switch ($this->condition_at_return) {
            case self::CONDITION_POOR:
                $penaltyAmount = $this->deposit_amount_per_item * 0.8; // 80% de la caution
                break;
            case self::CONDITION_FAIR:
                $penaltyAmount = $this->deposit_amount_per_item * 0.5; // 50% de la caution
                break;
            case self::CONDITION_GOOD:
                $penaltyAmount = $this->deposit_amount_per_item * 0.2; // 20% de la caution
                break;
        }
        
        if ($penaltyAmount > 0) {
            $this->penalties()->create([
                'rental_id' => $this->rental_id,
                'type' => 'damage',
                'reason' => 'Dommages constatés au retour',
                'description' => $damageDescription,
                'amount' => $penaltyAmount * $this->returned_quantity
            ]);
        }
    }

    public function canBeReturned()
    {
        return $this->remaining_quantity > 0 && 
               in_array($this->rental->status, [Rental::STATUS_ACTIVE, Rental::STATUS_OVERDUE]);
    }

    /**
     * Méthodes statiques
     */
    public static function getReturnStatuses()
    {
        return [
            self::RETURN_NOT_RETURNED => 'Non retourné',
            self::RETURN_PARTIAL => 'Partiellement retourné',
            self::RETURN_FULL => 'Complètement retourné',
            self::RETURN_DAMAGED => 'Retourné avec dommages',
            self::RETURN_LOST => 'Perdu',
        ];
    }

    public static function getConditions()
    {
        return [
            self::CONDITION_EXCELLENT => 'Excellent',
            self::CONDITION_GOOD => 'Bon',
            self::CONDITION_FAIR => 'Correct',
            self::CONDITION_POOR => 'Mauvais',
        ];
    }
}
