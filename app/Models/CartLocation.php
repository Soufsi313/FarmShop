<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class CartLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'total_deposit',
        'total_tva',
        'total_with_tax',
        'total_items',
        'total_quantity',
        'default_start_date',
        'default_end_date',
        'default_duration_days',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_deposit' => 'decimal:2',
        'total_tva' => 'decimal:2',
        'total_with_tax' => 'decimal:2',
        'default_start_date' => 'date',
        'default_end_date' => 'date',
        'metadata' => 'array'
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItemLocation::class);
    }

    // Méthodes de gestion du panier

    /**
     * Ajouter un produit au panier de location
     */
    public function addProduct(Product $product, int $quantity, Carbon $startDate, Carbon $endDate, ?string $notes = null): CartItemLocation
    {
        // Vérifier si le produit peut être loué
        if (!in_array($product->type, ['rental', 'both'])) {
            throw new \Exception("Ce produit n'est pas disponible à la location");
        }

        // Vérifier que le produit n'est pas en rupture de stock
        if ($product->is_out_of_stock) {
            throw new \Exception("Ce produit est en rupture de stock et ne peut pas être loué");
        }

        // Vérifier que le produit est actif
        if (!$product->is_active) {
            throw new \Exception("Ce produit n'est plus disponible");
        }

        // Vérifier si le produit n'est pas déjà dans le panier
        $existingItem = $this->items()->where('product_id', $product->id)->first();
        if ($existingItem) {
            throw new \Exception("Ce produit est déjà dans votre panier de location");
        }

        // Vérifier la disponibilité du produit pour cette période
        $this->checkProductAvailability($product, $quantity, $startDate, $endDate);

        // Calculer la durée en excluant les dimanches (jours de fermeture)
        // Utilise la méthode du produit qui compte uniquement les jours ouvrés
        $durationDays = $product->calculateRentalDuration($startDate, $endDate);

        // Créer l'élément de panier
        $cartItem = $this->items()->create([
            'product_id' => $product->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_days' => $durationDays,
            'quantity' => $quantity,
            'unit_price_per_day' => $product->rental_price_per_day ?? 0,
            'unit_deposit' => $product->deposit_amount ?? 0,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'rental_category_name' => $product->rentalCategory?->translated_name ?? $product->rentalCategory?->name,
            'notes' => $notes
        ]);

        // Calculer les montants
        $cartItem->calculateAmounts();
        
        // Recalculer les totaux du panier
        $this->recalculateTotal();

        return $cartItem->fresh();
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeProduct(Product $product): bool
    {
        $item = $this->items()->where('product_id', $product->id)->first();
        
        if (!$item) {
            return false;
        }

        $item->delete();
        $this->recalculateTotal();
        
        return true;
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function updateProductQuantity(Product $product, int $quantity): CartItemLocation
    {
        $item = $this->items()->where('product_id', $product->id)->firstOrFail();
        
        // Vérifier la disponibilité pour la nouvelle quantité
        $this->checkProductAvailability($product, $quantity, Carbon::parse($item->start_date), Carbon::parse($item->end_date), $item->id);
        
        $item->updateQuantity($quantity);
        $this->recalculateTotal();
        
        return $item->fresh();
    }

    /**
     * Mettre à jour les dates de location d'un produit
     */
    public function updateProductDates(Product $product, Carbon $startDate, Carbon $endDate): CartItemLocation
    {
        $item = $this->items()->where('product_id', $product->id)->firstOrFail();
        
        // Vérifier la disponibilité pour les nouvelles dates
        $this->checkProductAvailability($product, $item->quantity, $startDate, $endDate, $item->id);
        
        $item->updateDates($startDate, $endDate);
        $this->recalculateTotal();
        
        return $item->fresh();
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
     * Vérifier la disponibilité d'un produit pour une période donnée
     */
    public function checkProductAvailability(Product $product, int $quantity, Carbon $startDate, Carbon $endDate, ?int $excludeItemId = null): void
    {
        // Vérifier que le produit n'est pas en rupture de stock
        if ($product->is_out_of_stock) {
            throw new \Exception("Ce produit est en rupture de stock et ne peut pas être loué");
        }

        // Vérifier le stock global
        if ($quantity > $product->quantity) {
            throw new \Exception("Stock insuffisant. Stock disponible: {$product->quantity}");
        }

        // Vérifier qu'aucun autre utilisateur n'a ce produit dans son panier pour une période qui se chevauche
        $query = CartItemLocation::where('product_id', $product->id)
            ->whereHas('cartLocation', function ($q) {
                $q->where('user_id', '!=', $this->user_id);
            })
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeItemId) {
            $query->where('id', '!=', $excludeItemId);
        }

        $conflictingReservations = $query->sum('quantity');
        $availableQuantity = $product->quantity - $conflictingReservations;

        if ($quantity > $availableQuantity) {
            throw new \Exception("Produit non disponible pour cette période. Quantité disponible: {$availableQuantity}");
        }
    }

    /**
     * Vérifier la disponibilité de tous les produits du panier
     */
    public function checkAvailability(): array
    {
        $availability = [];
        
        foreach ($this->items as $item) {
            try {
                $this->checkProductAvailability(
                    $item->product, 
                    $item->quantity, 
                    Carbon::parse($item->start_date), 
                    Carbon::parse($item->end_date),
                    $item->id
                );
                $availability[$item->id] = [
                    'available' => true,
                    'message' => 'Disponible'
                ];
            } catch (\Exception $e) {
                $availability[$item->id] = [
                    'available' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        return $availability;
    }

    /**
     * Recalculer les totaux du panier
     */
    public function recalculateTotal(): void
    {
        $items = $this->items;
        
        $totalAmount = $items->sum('subtotal_amount');
        $totalDeposit = $items->sum('subtotal_deposit');
        $totalTva = $items->sum('tva_amount');
        $totalWithTax = $totalAmount + $totalTva;
        $totalItems = $items->count();
        $totalQuantity = $items->sum('quantity');

        $this->update([
            'total_amount' => $totalAmount,
            'total_deposit' => $totalDeposit,
            'total_tva' => $totalTva,
            'total_with_tax' => $totalWithTax,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity
        ]);
    }

    /**
     * Préparer le panier pour la commande
     */
    public function prepareForCheckout(): array
    {
        if ($this->items->isEmpty()) {
            throw new \Exception('Le panier de location est vide');
        }

        $availability = $this->checkAvailability();
        $unavailableItems = collect($availability)->where('available', false);

        if ($unavailableItems->isNotEmpty()) {
            throw new \Exception('Certains produits ne sont plus disponibles pour les dates sélectionnées');
        }

        return [
            'cart' => $this->load('items.product'),
            'summary' => [
                'total_amount' => $this->total_amount,
                'total_deposit' => $this->total_deposit,
                'total_tva' => $this->total_tva,
                'total_with_tax' => $this->total_with_tax,
                'total_items' => $this->total_items,
                'total_quantity' => $this->total_quantity
            ],
            'availability' => $availability
        ];
    }

    /**
     * Obtenir un résumé du panier
     */
    public function getSummary(): array
    {
        // Mettre à jour les totaux avant de les retourner
        $this->updateTotals();
        
        return [
            'total_amount' => $this->total_amount,
            'total_deposit' => $this->total_deposit,
            'total_tva' => $this->total_tva,
            'total_with_tax' => $this->total_with_tax,
            'total_items' => $this->total_items,
            'total_quantity' => $this->total_quantity,
            'items_count' => $this->items()->count(),
            'is_empty' => $this->items()->count() === 0
        ];
    }

    /**
     * Mettre à jour les totaux du panier basés sur les items
     */
    public function updateTotals(): void
    {
        $items = $this->items;
        
        $totalAmount = $items->sum('subtotal_amount');
        $totalDeposit = $items->sum('subtotal_deposit');
        $totalTva = $items->sum('tva_amount');
        $totalWithTax = $items->sum('total_amount');
        $totalItems = $items->count();
        $totalQuantity = $items->sum('quantity');
        
        $this->update([
            'total_amount' => $totalAmount,
            'total_deposit' => $totalDeposit,
            'total_tva' => $totalTva,
            'total_with_tax' => $totalWithTax,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity
        ]);
    }

    /**
     * Mettre à jour les dates par défaut du panier
     */
    public function updateDefaultDates(Carbon $startDate, Carbon $endDate): void
    {
        $durationDays = $startDate->diffInDays($endDate) + 1;
        
        $this->update([
            'default_start_date' => $startDate,
            'default_end_date' => $endDate,
            'default_duration_days' => $durationDays
        ]);
    }
}
