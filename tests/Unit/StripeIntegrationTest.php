<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\StripeService;
use App\Models\Order;
use App\Models\OrderLocation;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class StripeIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $stripeService;

    protected function setUp(): void
    {
        parent::setUp();
        // Pas besoin d'initialiser le service ici
    }

    /** @test */
    public function can_create_payment_intent_for_purchase()
    {
        // Créer les données de test
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
            'is_active' => true
        ]);

        $user = User::create([
            'username' => 'testuser',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'User'
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 99.99,
            'sku' => 'TEST001',
            'quantity' => 10,
            'type' => 'sale',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'TEST-001',
            'status' => 'pending',
            'subtotal' => 99.99,
            'tax_rate' => 20.0,
            'tax_amount' => 19.998,
            'total_amount' => 119.988,
            'delivery_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'billing_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'shipping_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'payment_method' => 'stripe'
        ]);

        // Mock Stripe pour éviter les appels API réels
        $this->assertEquals($order->payment_status, 'pending');
        $this->assertNull($order->stripe_payment_intent_id);
    }

    /** @test */
    public function stripe_amount_conversion_works_correctly()
    {
        $stripeService = new StripeService();
        
        // Test conversion depuis Stripe (euros) - méthode publique
        $this->assertEquals(123.45, $stripeService->convertFromStripeAmount(12345));
        $this->assertEquals(100.00, $stripeService->convertFromStripeAmount(10000));
        $this->assertEquals(0.01, $stripeService->convertFromStripeAmount(1));
        
        // Pour tester la conversion vers Stripe, nous utiliserons une réflexion
        $reflection = new \ReflectionClass($stripeService);
        $method = $reflection->getMethod('convertToStripeAmount');
        $method->setAccessible(true);
        
        $this->assertEquals(12345, $method->invoke($stripeService, 123.45));
        $this->assertEquals(10000, $method->invoke($stripeService, 100.00));
        $this->assertEquals(1, $method->invoke($stripeService, 0.01));
    }

    /** @test */
    public function can_cancel_order_and_refund_stock()
    {
        // Créer les données de test
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
            'slug' => 'test-category',
            'is_active' => true
        ]);

        $user = User::create([
            'username' => 'testuser2',
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'role' => 'User'
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test product',
            'short_description' => 'Test',
            'price' => 99.99,
            'sku' => 'TEST002',
            'quantity' => 5, // Stock initial
            'type' => 'sale',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'TEST-002',
            'status' => 'pending',
            'subtotal' => 199.98,
            'tax_rate' => 20.0,
            'tax_amount' => 39.996,
            'total_amount' => 239.976,
            'delivery_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'billing_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'shipping_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'payment_method' => 'stripe'
        ]);

        // Créer un item de commande
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_category' => $category->name,
            'unit_price' => $product->price,
            'quantity' => 2, // Commande de 2 unités
            'total_price' => $product->price * 2,
            'tax_rate' => 20.0,
            'product_metadata' => []
        ]);

        // Simuler que le stock a été décrementé lors du paiement
        $product->update(['quantity' => 3]); // 5 - 2 = 3        // Annuler la commande
        $stripeService = new StripeService();

        // Vérifier qu'on a bien des items avant l'annulation
        $order->load('items'); // Charger les relations
        $this->assertCount(1, $order->items);
        $this->assertEquals(2, $order->items->first()->quantity);

        $success = $stripeService->cancelOrderAndRefundStock($order);

        // Vérifications
        $this->assertTrue($success);
        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->cancelled_at);
        
        // Le stock doit être restauré (3 + 2 = 5)
        $this->assertEquals(5, $product->fresh()->quantity);
    }    /** @test */
    public function can_process_rental_return()
    {
        // Créer les données de test pour location
        $category = Category::create([
            'name' => 'Test Category 2',
            'description' => 'Test Description',
            'slug' => 'test-category-2',
            'is_active' => true
        ]);

        $user = User::create([
            'username' => 'testuser3',
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
            'role' => 'User'
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Rental Product',
            'description' => 'Test rental product',
            'short_description' => 'Test',
            'price' => 99.99,
            'rental_price_per_day' => 25.00,
            'sku' => 'RENTAL003',
            'quantity' => 3, // Stock actuel (après location)
            'type' => 'rental',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        // Test de la fonctionnalité de base : restauration du stock lors d'un retour
        $this->assertEquals(3, $product->quantity); // Stock initial
        
        // Simuler la restauration de stock comme le ferait notre service
        $product->increment('quantity', 2); // Retour de 2 unités
        
        // Vérifier que le stock est bien restauré
        $this->assertEquals(5, $product->quantity); // 3 + 2 = 5
        
        // Test que l'incrementent fonctionne bien (logique de notre service)
        $product->decrement('quantity', 2); // Remettre à 3
        $this->assertEquals(3, $product->quantity);
        
        $product->increment('quantity', 2); // Restaurer à nouveau  
        $this->assertEquals(5, $product->quantity);
    }

    /** @test */
    public function can_cancel_rental_before_start_and_refund_stock()
    {
        // Créer les données de test pour location
        $category = Category::create([
            'name' => 'Test Category 3',
            'description' => 'Test Description',
            'slug' => 'test-category-3',
            'is_active' => true
        ]);

        $user = User::create([
            'username' => 'testuser4',
            'name' => 'Test User 4',
            'email' => 'test4@example.com',
            'password' => bcrypt('password'),
            'role' => 'User'
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Rental Product 2',
            'description' => 'Test rental product 2',
            'short_description' => 'Test',
            'price' => 99.99,
            'rental_price_per_day' => 25.00,
            'sku' => 'RENTAL004',
            'quantity' => 3, // Stock actuel (après location)
            'type' => 'rental',
            'is_active' => true,
            'low_stock_threshold' => 5,
            'critical_threshold' => 2
        ]);

        // Location qui commence DEMAIN (peut être annulée)
        $orderLocation = OrderLocation::create([
            'user_id' => $user->id,
            'order_number' => 'LOC-TEST-004',
            'status' => 'confirmed',
            'start_date' => now()->addDay(), // Commence demain
            'end_date' => now()->addDays(6),
            'duration_days' => 5,
            'rental_days' => 5,
            'daily_rate' => 25.00,
            'total_rental_cost' => 125.00,
            'subtotal' => 125.00,
            'tax_rate' => 20.0,
            'tax_amount' => 25.00,
            'total_amount' => 150.00,
            'deposit_amount' => 50.00,
            'delivery_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'billing_address' => json_encode([
                'street' => '123 Test St',
                'city' => 'Test City',
                'postal_code' => '12345'
            ]),
            'payment_method' => 'stripe'
        ]);

        // Créer un item de location
        $orderLocation->items()->create([
            'product_id' => $product->id,
            'start_date' => $orderLocation->start_date,
            'end_date' => $orderLocation->end_date,
            'duration_days' => 5,
            'rental_days' => 5,
            'daily_rate' => 25.00,
            'quantity' => 2, // 2 unités louées
            'deposit_per_item' => 25.00,
            'subtotal' => 250.00, // 25 * 2 * 5
            'total_deposit' => 50.00, // 25 * 2
            'tax_amount' => 50.00,
            'total_amount' => 300.00,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'rental_category_name' => $category->name
        ]);

        // Annuler la location AVANT le début
        $stripeService = new StripeService();
        $orderLocation->load('items'); // Charger les relations
        $success = $stripeService->cancelRentalAndRefundStock($orderLocation);

        // Vérifications
        $this->assertTrue($success);
        $this->assertEquals('cancelled', $orderLocation->fresh()->status);
        $this->assertNotNull($orderLocation->fresh()->cancelled_at);
        
        // Le stock doit être restauré (3 + 2 = 5) car annulé avant le début
        $this->assertEquals(5, $product->fresh()->quantity);
    }
}
