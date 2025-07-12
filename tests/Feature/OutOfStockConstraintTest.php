<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class OutOfStockConstraintTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'username' => 'testuser',
            'role' => 'customer'
        ]);
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function cannot_add_out_of_stock_product_to_cart()
    {
        // Créer un produit en rupture de stock
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 0,
            'type' => 'sale',
            'is_active' => true
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/cart/products/{$product->id}", [
                'quantity' => 1
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Ce produit est en rupture de stock et ne peut pas être ajouté au panier'
        ]);
    }

    /** @test */
    public function cannot_add_out_of_stock_product_to_cart_location()
    {
        // Créer un produit en rupture de stock pour location
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 0,
            'type' => 'rental',
            'is_active' => true,
            'rental_price_per_day' => 25.00
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/cart-location/products/{$product->id}", [
                'quantity' => 1,
                'start_date' => now()->addDay()->format('Y-m-d'),
                'end_date' => now()->addDays(3)->format('Y-m-d'),
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Ce produit est en rupture de stock et ne peut pas être ajouté au panier'
        ]);
    }

    /** @test */
    public function cannot_update_quantity_when_product_becomes_out_of_stock()
    {
        // Créer un produit avec stock
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 5,
            'type' => 'sale',
            'is_active' => true
        ]);

        // Ajouter au panier
        $this->actingAs($this->user)
            ->postJson("/api/cart/products/{$product->id}", [
                'quantity' => 2
            ])
            ->assertStatus(201);

        // Simuler rupture de stock
        $product->update(['quantity' => 0]);

        // Tenter de mettre à jour la quantité
        $response = $this->actingAs($this->user)
            ->putJson("/api/cart/products/{$product->id}", [
                'quantity' => 3
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Ce produit est en rupture de stock et ne peut pas être ajouté au panier'
        ]);
    }

    /** @test */
    public function can_add_product_with_sufficient_stock()
    {
        // Créer un produit avec stock suffisant
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 10,
            'type' => 'sale',
            'is_active' => true
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/cart/products/{$product->id}", [
                'quantity' => 3
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Produit ajouté au panier avec succès'
        ]);
    }

    /** @test */
    public function check_availability_endpoint_returns_correct_status()
    {
        // Créer un produit en rupture de stock
        $outOfStockProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 0,
            'type' => 'sale',
            'is_active' => true
        ]);

        // Créer un produit avec stock
        $inStockProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 10,
            'type' => 'sale',
            'is_active' => true
        ]);

        // Test produit en rupture de stock
        $response = $this->postJson("/api/products/{$outOfStockProduct->id}/check-availability", [
            'type' => 'sale',
            'quantity' => 1
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'available' => false,
                'reasons' => ['Produit en rupture de stock']
            ]
        ]);

        // Test produit avec stock
        $response = $this->postJson("/api/products/{$inStockProduct->id}/check-availability", [
            'type' => 'sale',
            'quantity' => 5
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'available' => true,
                'reasons' => []
            ]
        ]);
    }

    /** @test */
    public function cannot_add_inactive_product_to_cart()
    {
        // Créer un produit inactif
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'quantity' => 10,
            'type' => 'sale',
            'is_active' => false
        ]);

        $response = $this->actingAs($this->user)
            ->postJson("/api/cart/products/{$product->id}", [
                'quantity' => 1
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Ce produit n\'est plus disponible'
        ]);
    }
}
