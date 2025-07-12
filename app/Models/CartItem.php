<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_name',
        'product_category',
        'unit_price',
        'quantity',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'product_metadata'
    ];

    protected $casts = [
        'product_metadata' => 'array',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Relations
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Boot method pour les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cartItem) {
            $cartItem->calculateAmounts();
        });

        static::updating(function ($cartItem) {
            if ($cartItem->isDirty(['quantity', 'unit_price', 'tax_rate'])) {
                $cartItem->calculateAmounts();
            }
        });
    }

    /**
     * Calculer les montants (sous-total, TVA, total)
     */
    public function calculateAmounts(): void
    {
        $this->subtotal = $this->unit_price * $this->quantity;
        $this->tax_amount = ($this->subtotal * $this->tax_rate) / 100;
        $this->total = $this->subtotal + $this->tax_amount;
    }

    /**
     * Mettre à jour la quantité et recalculer
     */
    public function updateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \Exception("La quantité doit être supérieure à 0");
        }

        // Vérifier le stock du produit
        $product = $this->product;
        if ($product && $product->quantity < $quantity) {
            throw new \Exception("Stock insuffisant. Disponible: {$product->quantity}");
        }

        $this->quantity = $quantity;
        $this->calculateAmounts();
        $this->save();
    }

    /**
     * Obtenir le prix unitaire TTC
     */
    public function getUnitPriceWithTaxAttribute(): float
    {
        return $this->unit_price + (($this->unit_price * $this->tax_rate) / 100);
    }

    /**
     * Obtenir le prix total HT pour cette ligne
     */
    public function getLineTotalAttribute(): float
    {
        return $this->subtotal;
    }

    /**
     * Obtenir le prix total TTC pour cette ligne
     */
    public function getLineTotalWithTaxAttribute(): float
    {
        return $this->total;
    }

    /**
     * Vérifier si le produit est toujours disponible
     */
    public function isAvailable(): bool
    {
        $product = $this->product;
        
        if (!$product) {
            return false;
        }

        return $product->is_active 
            && in_array($product->type, ['sale', 'both'])
            && $product->quantity >= $this->quantity;
    }

    /**
     * Obtenir les informations de disponibilité
     */
    public function getAvailabilityInfo(): array
    {
        $product = $this->product;
        
        if (!$product) {
            return [
                'available' => false,
                'reason' => 'Produit introuvable'
            ];
        }

        if (!$product->is_active) {
            return [
                'available' => false,
                'reason' => 'Produit non disponible'
            ];
        }

        if (!in_array($product->type, ['sale', 'both'])) {
            return [
                'available' => false,
                'reason' => 'Produit non disponible à la vente'
            ];
        }

        if ($product->quantity < $this->quantity) {
            return [
                'available' => false,
                'reason' => "Stock insuffisant (disponible: {$product->quantity})",
                'available_quantity' => $product->quantity
            ];
        }

        return [
            'available' => true,
            'available_quantity' => $product->quantity
        ];
    }

    /**
     * Synchroniser les informations du produit
     */
    public function syncProductInfo(): void
    {
        $product = $this->product;
        
        if ($product) {
            $this->update([
                'product_name' => $product->name,
                'product_category' => $product->category->name,
                'product_metadata' => [
                    'images' => $product->images,
                    'description' => $product->short_description,
                    'sku' => $product->sku
                ]
            ]);
        }
    }

    /**
     * Formater les informations pour l'affichage
     */
    public function toDisplayArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_category' => $this->product_category,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'unit_price_with_tax' => $this->unit_price_with_tax,
            'subtotal' => $this->subtotal,
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'product_metadata' => $this->product_metadata,
            'availability' => $this->getAvailabilityInfo(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
