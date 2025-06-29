<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // Produits alimentaires de fruits et légumes avec données réalistes
        $products = [
            [
                'name' => 'Pommes Golden',
                'description' => 'Pommes Golden délicieuses, croquantes et sucrées. Parfaites pour croquer ou cuisiner. Origine France.',
                'price' => 2.80,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Tomates cerises',
                'description' => 'Tomates cerises fraîches et juteuses, idéales pour les salades et apéritifs. Cultivées localement.',
                'price' => 4.50,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Carottes bio',
                'description' => 'Carottes biologiques fraîches, croquantes et sucrées. Parfaites pour tous vos plats.',
                'price' => 1.95,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Bananes',
                'description' => 'Bananes mûres et savoureuses, riches en potassium. Parfaites pour le petit-déjeuner.',
                'price' => 2.20,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Salade verte',
                'description' => 'Salade verte fraîche et croquante, parfaite pour vos salades composées.',
                'price' => 1.50,
                'unit_symbol' => 'piece'
            ],
            [
                'name' => 'Courgettes',
                'description' => 'Courgettes fraîches de saison, tendres et savoureuses. Idéales pour gratins et ratatouilles.',
                'price' => 2.30,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Fraises',
                'description' => 'Fraises de saison, juteuses et parfumées. Un délice pour petits et grands.',
                'price' => 5.80,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Poivrons rouges',
                'description' => 'Poivrons rouges frais et croquants, parfaits pour cuisiner ou en salade.',
                'price' => 3.90,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Oranges',
                'description' => 'Oranges juteuses et vitaminées, parfaites pour les jus ou à croquer.',
                'price' => 2.60,
                'unit_symbol' => 'kg'
            ],
            [
                'name' => 'Brocolis',
                'description' => 'Brocolis frais et nutritifs, riches en vitamines. Excellents vapeur ou en gratin.',
                'price' => 3.20,
                'unit_symbol' => 'kg'
            ]
        ];

        static $index = 0;
        $product = $products[$index % count($products)];
        $index++;

        return [
            'name' => $product['name'],
            'slug' => \Illuminate\Support\Str::slug($product['name']) . '-' . $this->faker->unique()->numberBetween(1, 1000),
            'description' => $product['description'],
            'price' => $product['price'],
            'quantity' => $this->faker->numberBetween(250, 500), // Stock conséquent
            'unit_symbol' => $product['unit_symbol'],
            'critical_stock_threshold' => $this->faker->numberBetween(15, 30), // Seuil critique cohérent
            'is_active' => true,
            'is_featured' => $this->faker->boolean(30), // 30% de chance d'être en vedette
            'is_rentable' => false, // Pas de location pour l'alimentaire
            'is_perishable' => true, // Alimentaire = périssable
            'is_returnable' => false, // Pas de retour pour l'alimentaire
            'views_count' => $this->faker->numberBetween(0, 100),
            'likes_count' => $this->faker->numberBetween(0, 20),
        ];
    }
}
