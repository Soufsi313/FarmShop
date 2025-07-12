<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total',
        'tax_rate',
        'total_items',
        'free_shipping_eligible',
        'metadata',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'free_shipping_eligible' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Ajouter un produit au panier
     */
    public function addProduct(Product $product, int $quantity = 1): CartItem
    {
        // Vérifier la disponibilité du stock
        if ($product->quantity < $quantity) {
            throw new \Exception("Stock insuffisant. Disponible: {$product->quantity}");
        }

        // Vérifier que le produit est disponible à la vente
        if (!in_array($product->type, ['sale', 'both'])) {
            throw new \Exception("Ce produit n'est pas disponible à la vente");
        }

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            throw new \Exception("Ce produit n'est plus disponible");
        }

        // Chercher si le produit existe déjà dans le panier
        $existingItem = $this->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            // Mettre à jour la quantité
            $newQuantity = $existingItem->quantity + $quantity;
            
            // Vérifier le stock pour la nouvelle quantité
            if ($product->quantity < $newQuantity) {
                throw new \Exception("Stock insuffisant pour cette quantité. Disponible: {$product->quantity}");
            }

            $existingItem->updateQuantity($newQuantity);
            return $existingItem;
        }

        // Créer un nouvel élément
        $cartItem = $this->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_category' => $product->category->name,
            'unit_price' => $product->price,
            'quantity' => $quantity,
            'tax_rate' => $this->tax_rate,
            'product_metadata' => [
                'images' => $product->images,
                'description' => $product->short_description,
                'sku' => $product->sku
            ]
        ]);

        $cartItem->calculateAmounts();
        $this->recalculateTotal();

        return $cartItem;
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeProduct(Product $product): bool
    {
        $item = $this->items()->where('product_id', $product->id)->first();
        
        if ($item) {
            $item->delete();
            $this->recalculateTotal();
            return true;
        }

        return false;
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function updateProductQuantity(Product $product, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            throw new \Exception("La quantité doit être supérieure à 0");
        }

        // Vérifier le stock
        if ($product->quantity < $quantity) {
            throw new \Exception("Stock insuffisant. Disponible: {$product->quantity}");
        }

        $item = $this->items()->where('product_id', $product->id)->firstOrFail();
        $item->updateQuantity($quantity);
        $this->recalculateTotal();

        return $item;
    }

    /**
     * Vider le panier
     */
    public function clear(): void
    {
        $this->items()->delete();
        $this->recalculateTotal();
    }

    /**
     * Recalculer les totaux du panier
     */
    public function recalculateTotal(): void
    {
        $items = $this->items()->get();
        
        $subtotal = $items->sum('subtotal');
        $taxAmount = $items->sum('tax_amount');
        $totalItems = $items->sum('quantity');

        // 🚚 CALCUL AUTOMATIQUE DES FRAIS DE LIVRAISON
        $shippingCost = $this->calculateShippingCost($subtotal);
        $freeShippingEligible = $this->isFreeShippingEligible($subtotal);
        
        // Total final avec frais de livraison
        $total = $subtotal + $taxAmount + $shippingCost;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'free_shipping_eligible' => $freeShippingEligible,
            'total' => $total,
            'total_items' => $totalItems
        ]);
    }

    /**
     * 🚚 Calculer les frais de livraison automatiquement
     * Règle : < 25€ = 2.50€ de frais | ≥ 25€ = gratuit
     */
    public function calculateShippingCost(float $subtotal = null): float
    {
        $subtotal = $subtotal ?? $this->subtotal ?? 0;
        
        // Seuil pour la livraison gratuite
        $freeShippingThreshold = 25.00;
        $shippingFee = 2.50;
        
        return $subtotal >= $freeShippingThreshold ? 0.00 : $shippingFee;
    }

    /**
     * 🎁 Vérifier si éligible à la livraison gratuite
     */
    public function isFreeShippingEligible(float $subtotal = null): bool
    {
        $subtotal = $subtotal ?? $this->subtotal ?? 0;
        return $subtotal >= 25.00;
    }

    /**
     * 💰 Montant restant pour la livraison gratuite
     */
    public function getAmountForFreeShipping(): float
    {
        if ($this->free_shipping_eligible) {
            return 0.00;
        }
        
        $remaining = 25.00 - ($this->subtotal ?? 0);
        return round(max(0, $remaining), 2);
    }

    /**
     * 📊 Obtenir un récapitulatif des coûts
     */
    public function getCostSummary(): array
    {
        return [
            'subtotal' => $this->subtotal ?? 0,
            'tax_amount' => $this->tax_amount ?? 0,
            'shipping_cost' => $this->shipping_cost ?? 0,
            'total' => $this->total ?? 0,
            'free_shipping_eligible' => $this->free_shipping_eligible ?? false,
            'amount_for_free_shipping' => $this->getAmountForFreeShipping(),
            'shipping_message' => $this->getShippingMessage()
        ];
    }

    /**
     * 💬 Message informatif sur la livraison
     */
    public function getShippingMessage(): string
    {
        if ($this->free_shipping_eligible) {
            return '🎉 Livraison gratuite !';
        }
        
        $remaining = $this->getAmountForFreeShipping();
        return "Ajoutez {$remaining}€ pour la livraison gratuite";
    }

    /**
     * Vérifier si le panier est vide
     */
    public function isEmpty(): bool
    {
        return $this->items()->count() === 0;
    }

    /**
     * Vérifier la disponibilité de tous les produits du panier
     */
    public function checkAvailability(): array
    {
        $unavailableItems = [];

        foreach ($this->items as $item) {
            $product = $item->product;
            
            if (!$product || !$product->is_active || !in_array($product->type, ['sale', 'both'])) {
                $unavailableItems[] = [
                    'item' => $item,
                    'reason' => 'Produit non disponible'
                ];
                continue;
            }

            if ($product->quantity < $item->quantity) {
                $unavailableItems[] = [
                    'item' => $item,
                    'reason' => "Stock insuffisant (disponible: {$product->quantity})"
                ];
            }
        }

        return $unavailableItems;
    }

    /**
     * Marquer le panier comme converti (commandé)
     */
    public function markAsConverted(): void
    {
        $this->update(['status' => 'converted']);
    }

    /**
     * Marquer le panier comme abandonné
     */
    public function markAsAbandoned(): void
    {
        $this->update(['status' => 'abandoned']);
    }

    /**
     * Définir une date d'expiration
     */
    public function setExpiration(\DateTime $date): void
    {
        $this->update(['expires_at' => $date]);
    }
}
