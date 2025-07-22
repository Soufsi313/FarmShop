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
        'product_metadata',
        'is_available'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
        'product_metadata' => 'array',
        'is_available' => 'boolean'
    ];

    /**
     * Get the cart that owns the cart item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product that is in the cart.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate and update totals when quantity or price changes.
     */
    public function recalculate(): void
    {
        // Le unit_price est HT, nous devons calculer les totaux
        $this->subtotal = $this->unit_price * $this->quantity; // Sous-total HT
        $this->tax_amount = round($this->subtotal * ($this->tax_rate / 100), 2); // TVA
        $this->total = $this->subtotal + $this->tax_amount; // Total TTC
        $this->save();
    }

    /**
     * Update quantity and recalculate totals.
     */
    public function updateQuantity(int $quantity): void
    {
        $this->quantity = max(1, $quantity); // Minimum 1
        $this->recalculate();
        
        // Recalculer le total du panier
        $this->cart->calculateTotal();
    }

    /**
     * Increase quantity by a specified amount.
     */
    public function increaseQuantity(int $amount = 1): void
    {
        $this->updateQuantity($this->quantity + $amount);
    }

    /**
     * Decrease quantity by a specified amount.
     */
    public function decreaseQuantity(int $amount = 1): void
    {
        $newQuantity = $this->quantity - $amount;
        if ($newQuantity <= 0) {
            $this->delete();
            $this->cart->calculateTotal();
        } else {
            $this->updateQuantity($newQuantity);
        }
    }

    /**
     * Get formatted unit price (HT).
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 2, ',', ' ') . ' €';
    }

    /**
     * Get formatted subtotal (HT).
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 2, ',', ' ') . ' €';
    }

    /**
     * Get formatted tax amount.
     */
    public function getFormattedTaxAmountAttribute(): string
    {
        return number_format($this->tax_amount, 2, ',', ' ') . ' €';
    }

    /**
     * Get formatted total (TTC).
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 2, ',', ' ') . ' €';
    }

    /**
     * Get the product image URL from metadata.
     */
    public function getProductImageAttribute(): string
    {
        // Toujours récupérer depuis le produit s'il existe pour avoir l'URL la plus à jour
        if ($this->product) {
            return $this->product->image_url;
        }
        
        // Sinon, essayer depuis les métadonnées
        $metadata = $this->product_metadata ?? [];
        if (!empty($metadata['image'])) {
            return $metadata['image'];
        }
        
        // Image par défaut
        return '/images/placeholder-product.jpg';
    }

    /**
     * Get the product slug from metadata.
     */
    public function getProductSlugAttribute(): string
    {
        $metadata = $this->product_metadata ?? [];
        return $metadata['slug'] ?? '';
    }

    /**
     * Check if the product is still available.
     */
    public function checkAvailability(): bool
    {
        if (!$this->product) {
            $this->is_available = false;
            $this->save();
            return false;
        }

        // Vérifier si le produit existe toujours et est disponible
        $product = $this->product;
        $isAvailable = $product && 
                      !$product->trashed() && 
                      $product->is_active && 
                      $product->stock >= $this->quantity;

        $this->is_available = $isAvailable;
        $this->save();

        return $isAvailable;
    }

    /**
     * Synchronize with current product data.
     */
    public function syncWithProduct(): void
    {
        if (!$this->product) {
            return;
        }

        $product = $this->product;
        
        // Mettre à jour les informations si le produit a changé
        $this->product_name = $product->name;
        $this->product_category = $product->category->name ?? 'Non classé';
        
        // Mettre à jour les métadonnées
        $this->product_metadata = [
            'slug' => $product->slug,
            'image' => $product->image_url, // Utiliser la nouvelle méthode
            'description' => $product->short_description,
            'category_id' => $product->category_id,
            'is_active' => $product->is_active,
            'stock' => $product->stock
        ];

        // Note: On ne met pas à jour le prix automatiquement pour préserver
        // le prix au moment de l'ajout au panier
        
        $this->save();
    }

    /**
     * Create a cart item from a product.
     */
    public static function createFromProduct(Cart $cart, Product $product, int $quantity = 1): self
    {
        // Le prix en base est TTC
        $priceTTC = $product->price;
        $taxRate = $product->getTaxRate();
        
        // Calculer le prix HT à partir du prix TTC
        $priceHT = round($priceTTC / (1 + ($taxRate / 100)), 2);
        
        // Calculs pour la quantité
        $subtotal = $priceHT * $quantity; // Sous-total HT
        $taxAmount = round($subtotal * ($taxRate / 100), 2); // Montant TVA
        $total = $priceTTC * $quantity; // Total TTC (prix TTC × quantité)

        return self::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_category' => $product->category->name ?? 'Non classé',
            'unit_price' => $priceHT, // Prix unitaire HT
            'quantity' => $quantity,
            'subtotal' => $subtotal, // Sous-total HT
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount, // Montant TVA
            'total' => $total, // Total TTC
            'product_metadata' => [
                'slug' => $product->slug,
                'image' => $product->image_url, // Utiliser la nouvelle méthode
                'description' => $product->short_description,
                'category_id' => $product->category_id,
                'food_type' => $product->category->food_type ?? 'non_alimentaire',
                'price_ttc_unitaire' => $priceTTC,
                'is_active' => $product->is_active,
                'stock' => $product->stock,
                'unit_symbol' => $product->unit_symbol
            ],
            'is_available' => true
        ]);
    }

    /**
     * Get the cart item data for display purposes.
     */
    public function toDisplayArray(): array
    {
        $metadata = $this->product_metadata ?? [];
        $priceTTCUnitaire = $metadata['price_ttc_unitaire'] ?? ($this->unit_price * (1 + ($this->tax_rate / 100)));
        $unitSymbol = $metadata['unit_symbol'] ?? 'unité';
        
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_category' => $this->product_category,
            'product_image' => $this->product_image,
            'product_slug' => $this->product_slug,
            'quantity' => $this->quantity,
            'unit_label' => $unitSymbol,
            
            // Prix et calculs
            'price_per_unit_ht' => $this->unit_price, // Prix unitaire HT
            'price_per_unit_ttc' => $priceTTCUnitaire, // Prix unitaire TTC
            'subtotal_ht' => $this->subtotal, // Sous-total HT
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $this->tax_amount, // Montant TVA
            'total_ttc' => $this->total, // Total TTC
            
            // Calculs pour l'affichage
            'subtotal_ht_per_quantity' => $this->subtotal,
            'tax_amount_per_quantity' => $this->tax_amount,
            'total_ttc_per_quantity' => $this->total,
            
            'is_available' => $this->is_available,
            
            // Versions formatées
            'price_per_unit_ht_formatted' => number_format($this->unit_price, 2) . ' €',
            'price_per_unit_ttc_formatted' => number_format($priceTTCUnitaire, 2) . ' €',
            'subtotal_formatted' => number_format($this->subtotal, 2) . ' €',
            'tax_amount_formatted' => number_format($this->tax_amount, 2) . ' €',
            'total_formatted' => number_format($this->total, 2) . ' €',
            'tax_rate_formatted' => number_format($this->tax_rate, 1) . '%',
            
            'product' => $this->product ? [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'slug' => $this->product->slug,
                'stock' => $this->product->stock,
                'is_active' => $this->product->is_active,
                'image_url' => $this->product->image_url
            ] : null
        ];
    }

    /**
     * Scope to get only available items.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to get only unavailable items.
     */
    public function scopeUnavailable($query)
    {
        return $query->where('is_available', false);
    }
}
