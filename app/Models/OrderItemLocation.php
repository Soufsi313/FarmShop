<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_location_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_description',
        'quantity',
        'daily_rate',
        'rental_days',
        'deposit_per_item',
        'subtotal',
        'total_deposit',
        'tax_amount',
        'total_amount',
        'condition_at_pickup',
        'condition_at_return',
        'item_damage_cost',
        'item_inspection_notes',
        'damage_details',
        'item_late_days',
        'item_late_fees',
        'item_deposit_refund'
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'deposit_per_item' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_deposit' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'item_damage_cost' => 'decimal:2',
        'item_late_fees' => 'decimal:2',
        'item_deposit_refund' => 'decimal:2',
        'damage_details' => 'array'
    ];

    /**
     * Relations
     */
    public function orderLocation()
    {
        return $this->belongsTo(OrderLocation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessors
     */
    public function getConditionAtPickupLabelAttribute()
    {
        return match($this->condition_at_pickup) {
            'excellent' => 'Excellent état',
            'good' => 'Bon état',
            'fair' => 'État correct',
            'poor' => 'Mauvais état',
            default => 'Non défini'
        };
    }

    public function getConditionAtReturnLabelAttribute()
    {
        return match($this->condition_at_return) {
            'excellent' => 'Excellent état',
            'good' => 'Bon état',
            'fair' => 'État correct',
            'poor' => 'Mauvais état',
            default => 'Non évalué'
        };
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2, ',', ' ') . ' €';
    }

    public function getFormattedTotalDepositAttribute()
    {
        return number_format($this->total_deposit, 2, ',', ' ') . ' €';
    }

    public function getFormattedDamagesCostAttribute()
    {
        return number_format($this->item_damage_cost, 2, ',', ' ') . ' €';
    }

    public function getFormattedLateFeeAttribute()
    {
        return number_format($this->item_late_fees, 2, ',', ' ') . ' €';
    }

    public function getFormattedDepositRefundAttribute()
    {
        return number_format($this->item_deposit_refund, 2, ',', ' ') . ' €';
    }

    /**
     * Business Logic Methods
     */

    /**
     * Calculer les frais de retard pour cet article
     */
    public function calculateItemLateFees()
    {
        if ($this->item_late_days <= 0) {
            return 0;
        }

        $dailyLateFee = $this->orderLocation->late_fee_per_day;
        return $this->item_late_days * $dailyLateFee * $this->quantity;
    }

    /**
     * Calculer le remboursement de caution pour cet article
     */
    public function calculateItemDepositRefund()
    {
        $totalPenalties = $this->item_damage_cost + $this->item_late_fees;
        return max(0, $this->total_deposit - $totalPenalties);
    }

    /**
     * Mettre à jour l'état de retour et calculer les pénalités
     */
    public function updateReturnCondition($condition, $damageDetails = [], $inspectionNotes = '')
    {
        $this->update([
            'condition_at_return' => $condition,
            'damage_details' => $damageDetails,
            'item_inspection_notes' => $inspectionNotes
        ]);

        // Calculer les coûts de dommages selon l'état
        $this->calculateDamageCost($condition);
        
        return $this;
    }

    /**
     * Calculer les coûts de dommages selon l'état du produit
     */
    private function calculateDamageCost($condition)
    {
        // Logique de calcul des dommages selon l'état
        $damagePercentage = match($condition) {
            'excellent' => 0,      // Aucun dommage
            'good' => 0.05,        // 5% de la caution
            'fair' => 0.15,        // 15% de la caution
            'poor' => 0.30,        // 30% de la caution
            default => 0
        };

        $calculatedDamage = $this->total_deposit * $damagePercentage;
        
        $this->update([
            'item_damage_cost' => $calculatedDamage
        ]);

        return $calculatedDamage;
    }

    /**
     * Finaliser l'inspection de l'article
     */
    public function finalizeInspection($lateDays = 0)
    {
        // Calculer les jours de retard pour cet article
        $this->update([
            'item_late_days' => $lateDays
        ]);

        // Calculer les frais de retard
        $lateFees = $this->calculateItemLateFees();
        $this->update([
            'item_late_fees' => $lateFees
        ]);

        // Calculer le remboursement de caution
        $depositRefund = $this->calculateItemDepositRefund();
        $this->update([
            'item_deposit_refund' => $depositRefund
        ]);

        return $this;
    }

    /**
     * Obtenir un résumé de l'inspection
     */
    public function getInspectionSummary()
    {
        return [
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'condition_pickup' => $this->condition_at_pickup_label,
            'condition_return' => $this->condition_at_return_label,
            'damage_cost' => $this->formatted_damages_cost,
            'late_days' => $this->item_late_days,
            'late_fees' => $this->formatted_late_fee,
            'total_penalties' => number_format($this->item_damage_cost + $this->item_late_fees, 2, ',', ' ') . ' €',
            'deposit_paid' => $this->formatted_total_deposit,
            'deposit_refund' => $this->formatted_deposit_refund,
            'inspection_notes' => $this->item_inspection_notes,
            'damage_details' => $this->damage_details
        ];
    }

    /**
     * Vérifier si l'article nécessite une attention particulière
     */
    public function needsAttention()
    {
        return $this->condition_at_return === 'poor' || 
               $this->item_damage_cost > 0 || 
               $this->item_late_days > 0;
    }

    /**
     * Obtenir la couleur de statut pour l'affichage
     */
    public function getStatusColor()
    {
        if (!$this->condition_at_return) {
            return 'gray'; // Pas encore évalué
        }

        return match($this->condition_at_return) {
            'excellent' => 'green',
            'good' => 'blue',
            'fair' => 'orange',
            'poor' => 'red',
            default => 'gray'
        };
    }

    /**
     * Générer un rapport détaillé pour l'article
     */
    public function generateDetailedReport()
    {
        $report = [
            'basic_info' => [
                'product_name' => $this->product_name,
                'sku' => $this->product_sku,
                'quantity' => $this->quantity,
                'rental_period' => $this->rental_days . ' jours',
                'daily_rate' => $this->formatted_subtotal
            ],
            'financial_summary' => [
                'rental_cost' => number_format($this->subtotal, 2, ',', ' ') . ' €',
                'deposit_paid' => $this->formatted_total_deposit,
                'damage_cost' => $this->formatted_damages_cost,
                'late_fees' => $this->formatted_late_fee,
                'total_penalties' => number_format($this->item_damage_cost + $this->item_late_fees, 2, ',', ' ') . ' €',
                'deposit_refund' => $this->formatted_deposit_refund
            ],
            'condition_report' => [
                'pickup_condition' => $this->condition_at_pickup_label,
                'return_condition' => $this->condition_at_return_label,
                'condition_change' => $this->condition_at_pickup !== $this->condition_at_return,
                'needs_attention' => $this->needsAttention()
            ],
            'timing' => [
                'rental_days' => $this->rental_days,
                'late_days' => $this->item_late_days,
                'on_time_return' => $this->item_late_days === 0
            ],
            'notes' => [
                'inspection_notes' => $this->item_inspection_notes,
                'damage_details' => $this->damage_details
            ]
        ];

        return $report;
    }
}
