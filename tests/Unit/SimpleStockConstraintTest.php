<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleStockConstraintTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_out_of_stock_attribute_works()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
            'is_active' => true
        ]);
        
        $outOfStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Out of Stock Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 29.99,
            'sku' => 'TEST001',
            'quantity' => 0,
            'type' => 'sale',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        $inStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'In Stock Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 29.99,
            'sku' => 'TEST002',
            'quantity' => 10,
            'type' => 'sale',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        $this->assertTrue($outOfStockProduct->is_out_of_stock);
        $this->assertFalse($inStockProduct->is_out_of_stock);
    }

    /** @test */
    public function cart_model_prevents_out_of_stock_addition()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
            'is_active' => true
        ]);
        
        $outOfStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Out of Stock Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 29.99,
            'sku' => 'TEST001',
            'quantity' => 0,
            'type' => 'sale',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
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
