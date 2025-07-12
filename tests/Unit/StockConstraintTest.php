<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockConstraintTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_out_of_stock_attribute_works()
    {
        $category = Category::factory()->create();
        
        $outOfStockProduct = Product::factory()->create([
            'category_id' => $category->id,
            'quantity' => 0
        ]);

        $inStockProduct = Product::factory()->create([
            'category_id' => $category->id,
            'quantity' => 10
        ]);

        $this->assertTrue($outOfStockProduct->is_out_of_stock);
        $this->assertFalse($inStockProduct->is_out_of_stock);
    }

    /** @test */
    public function cart_model_prevents_out_of_stock_addition()
    {
        $category = Category::factory()->create();
        
        $outOfStockProduct = Product::factory()->create([
            'category_id' => $category->id,
            'quantity' => 0,
            'type' => 'sale',
            'is_active' => true
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ce produit est en rupture de stock et ne peut pas être acheté');

        // Simuler l'appel de la méthode addProduct sur un panier
        $cart = new \App\Models\Cart([
            'user_id' => 1,
            'status' => 'active',
            'tax_rate' => 20.0
        ]);

        $cart->addProduct($outOfStockProduct, 1);
    }
}
