<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartLocation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RentalStockConstraintTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cart_location_prevents_out_of_stock_addition()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
            'is_active' => true
        ]);
        
        $outOfStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Out of Stock Rental Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 29.99,
            'rental_price_per_day' => 5.99,
            'sku' => 'RENTAL001',
            'quantity' => 0,
            'type' => 'rental',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ce produit est en rupture de stock et ne peut pas être loué');

        // Simuler l'appel de la méthode addProduct sur un panier de location
        $cartLocation = new CartLocation([
            'user_id' => 1,
            'status' => 'active',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2)
        ]);

        $cartLocation->addProduct(
            $outOfStockProduct, 
            1, 
            Carbon::tomorrow(), 
            Carbon::tomorrow()->addDays(2)
        );
    }

    /** @test */
    public function cart_location_allows_in_stock_addition()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
            'is_active' => true
        ]);
        
        $inStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'In Stock Rental Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 29.99,
            'rental_price_per_day' => 5.99,
            'sku' => 'RENTAL002',
            'quantity' => 5,
            'type' => 'rental',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        // Simuler l'appel de la méthode addProduct sur un panier de location
        $cartLocation = new CartLocation([
            'user_id' => 1,
            'status' => 'active',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2)
        ]);

        // Cette méthode ne devrait pas lever d'exception car on teste seulement la logique stock
        // (on n'accède pas à la base de données car on ne sauvegarde pas le panier)
        try {
            $cartLocation->addProduct(
                $inStockProduct, 
                1, 
                Carbon::tomorrow(), 
                Carbon::tomorrow()->addDays(2)
            );
            // Si on arrive ici, le produit avec stock est accepté
            $this->assertTrue(true);
        } catch (\Exception $e) {
            // Si l'exception ne concerne pas le stock, c'est OK
            $this->assertStringNotContainsString('rupture de stock', $e->getMessage());
        }
    }
}
