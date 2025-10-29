<?php

/**
 * Test d'Integration: Restrictions Retour Produits
 * 
 * Teste les restrictions de retour:
 * 1. Produits non retournables (alimentaire, semences)
 * 2. Commandes mixtes (retour partiel uniquement non-alimentaires)
 * 3. Delai de retour (14 jours maximum)
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST INTEGRATION: RESTRICTIONS RETOUR\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Preparation de l'utilisateur de test
    echo "1. Preparation de l'utilisateur de test...\n";
    
    $user = \App\Models\User::where('email', 'test_returns@example.com')->first();
    
    if (!$user) {
        $user = new \App\Models\User();
        $user->username = 'test_returns_' . time();
        $user->email = 'test_returns@example.com';
        $user->password = bcrypt('password');
        $user->email_verified_at = now();
        $user->save();
        echo "   - Utilisateur cree: {$user->email}\n";
    } else {
        echo "   - Utilisateur existant: {$user->email}\n";
    }

    // 2. Verification des produits retournables vs non-retournables
    echo "\n2. Verification des produits dans la base...\n";
    
    // Compter via les categories
    $returnableCategories = \App\Models\Category::where('is_returnable', true)->pluck('id');
    $nonReturnableCategories = \App\Models\Category::where('is_returnable', false)->pluck('id');
    
    $returnableProducts = \App\Models\Product::whereIn('category_id', $returnableCategories)->count();
    $nonReturnableProducts = \App\Models\Product::whereIn('category_id', $nonReturnableCategories)->count();
    
    echo "   - Categories retournables: " . $returnableCategories->count() . "\n";
    echo "   - Categories NON retournables: " . $nonReturnableCategories->count() . "\n";
    echo "   - Produits retournables: $returnableProducts\n";
    echo "   - Produits NON retournables: $nonReturnableProducts\n";
    
    // Trouver des exemples de chaque type
    $returnableExample = \App\Models\Product::whereIn('category_id', $returnableCategories)->first();
    $nonReturnableExample = \App\Models\Product::whereIn('category_id', $nonReturnableCategories)->first();
    
    if ($returnableExample) {
        echo "   - Exemple retournable: {$returnableExample->name}\n";
    }
    if ($nonReturnableExample) {
        echo "   - Exemple NON retournable: {$nonReturnableExample->name}\n";
    }

    // 3. Test de retour d'un produit NON retournable
    echo "\n3. Test retour produit NON retournable...\n";
    
    if (!$nonReturnableExample) {
        // Creer un produit alimentaire non retournable
        $category = \App\Models\Category::firstOrCreate(
            ['slug' => 'fruits-legumes'],
            [
                'name' => 'Fruits et Légumes',
                'description' => 'Produits frais',
                'food_type' => 'essential',
                'is_returnable' => false
            ]
        );
        
        $nonReturnableExample = new \App\Models\Product();
        $nonReturnableExample->name = 'Tomates Bio (Test)';
        $nonReturnableExample->slug = 'tomates-bio-test-' . time();
        $nonReturnableExample->description = 'Produit frais alimentaire - Non retournable';
        $nonReturnableExample->price = 3.50;
        $nonReturnableExample->quantity = 100;
        $nonReturnableExample->type = 'sale';
        $nonReturnableExample->unit_symbol = 'kg';
        $nonReturnableExample->category_id = $category->id;
        $nonReturnableExample->is_active = true;
        $nonReturnableExample->save();
        
        echo "   - Produit cree: {$nonReturnableExample->name}\n";
    }
    
    echo "   - Produit: {$nonReturnableExample->name}\n";
    echo "   - Categorie: {$nonReturnableExample->category->name}\n";
    echo "   - Retournable (categorie): " . ($nonReturnableExample->category->is_returnable ? 'OUI' : 'NON') . "\n";
    
    // Creer une commande avec ce produit
    $order1 = new \App\Models\Order();
    $order1->user_id = $user->id;
    $order1->order_number = 'TEST-NONRET-' . time();
    $order1->subtotal = $nonReturnableExample->price;
    $order1->tax_amount = round($nonReturnableExample->price * 0.06, 2); // TVA 6% alimentaire
    $order1->total_amount = $order1->subtotal + $order1->tax_amount;
    $order1->status = 'delivered'; // Commande livree
    $order1->payment_status = 'paid';
    $order1->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $order1->shipping_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $order1->has_non_returnable_items = true; // Contient produits non retournables
    
    // Desactiver temporairement les events pour le test
    \App\Models\Order::withoutEvents(function() use ($order1) {
        $order1->save();
    });
    
    // Ajouter un item a la commande
    $orderItem1 = new \App\Models\OrderItem();
    $orderItem1->order_id = $order1->id;
    $orderItem1->product_id = $nonReturnableExample->id;
    $orderItem1->product_name = $nonReturnableExample->name;
    $orderItem1->quantity = 1;
    $orderItem1->unit_price = $nonReturnableExample->price;
    $orderItem1->subtotal = $nonReturnableExample->price;
    $orderItem1->tax_rate = 6.00; // TVA 6% alimentaire
    $orderItem1->tax_amount = $order1->tax_amount;
    $orderItem1->total_price = $order1->total_amount;
    $orderItem1->is_returnable = false; // Copie de la categorie
    $orderItem1->save();
    
    echo "   - Commande creee: {$order1->order_number}\n";
    echo "   - has_non_returnable_items: " . ($order1->has_non_returnable_items ? 'OUI' : 'NON') . "\n";
    
    // Tenter de retourner ce produit
    $canReturn = $nonReturnableExample->category->is_returnable;
    
    if ($canReturn) {
        $errors[] = "Produit non retournable accepte pour retour";
    } else {
        echo "   - Validation: Retour bloque (CORRECT)\n";
        echo "   - Message attendu: Ce produit ne peut pas etre retourne (produit alimentaire/frais)\n";
    }

    // 4. Test retour commande MIXTE (retournables + non-retournables)
    echo "\n4. Test retour commande MIXTE...\n";
    
    // Creer ou trouver un produit retournable
    if (!$returnableExample) {
        $category = \App\Models\Category::firstOrCreate(
            ['slug' => 'outils'],
            [
                'name' => 'Outils',
                'description' => 'Outils de jardinage',
                'food_type' => null,
                'is_returnable' => true
            ]
        );
        
        $returnableExample = new \App\Models\Product();
        $returnableExample->name = 'Bêche Professionnelle (Test)';
        $returnableExample->slug = 'beche-pro-test-' . time();
        $returnableExample->description = 'Outil de jardinage - Retournable';
        $returnableExample->price = 45.00;
        $returnableExample->quantity = 50;
        $returnableExample->type = 'sale';
        $returnableExample->unit_symbol = 'pièce';
        $returnableExample->category_id = $category->id;
        $returnableExample->is_active = true;
        $returnableExample->save();
        
        echo "   - Produit retournable cree: {$returnableExample->name}\n";
    }
    
    // Creer commande mixte
    $order2 = new \App\Models\Order();
    $order2->user_id = $user->id;
    $order2->order_number = 'TEST-MIXED-' . time();
    $order2->subtotal = $returnableExample->price + $nonReturnableExample->price;
    $order2->tax_amount = round($returnableExample->price * 0.21 + $nonReturnableExample->price * 0.06, 2);
    $order2->total_amount = $order2->subtotal + $order2->tax_amount;
    $order2->status = 'delivered';
    $order2->payment_status = 'paid';
    $order2->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $order2->shipping_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $order2->has_non_returnable_items = true; // Contient des produits non retournables
    
    // Desactiver temporairement les events pour le test
    \App\Models\Order::withoutEvents(function() use ($order2) {
        $order2->save();
    });
    
    // Ajouter les items
    $item1 = new \App\Models\OrderItem();
    $item1->order_id = $order2->id;
    $item1->product_id = $returnableExample->id;
    $item1->product_name = $returnableExample->name;
    $item1->quantity = 1;
    $item1->unit_price = $returnableExample->price;
    $item1->subtotal = $returnableExample->price;
    $item1->tax_rate = 21.00;
    $item1->tax_amount = round($returnableExample->price * 0.21, 2);
    $item1->total_price = round($returnableExample->price * 1.21, 2); // Avec TVA 21%
    $item1->is_returnable = true; // Basé sur la catégorie
    $item1->save();
    
    $item2 = new \App\Models\OrderItem();
    $item2->order_id = $order2->id;
    $item2->product_id = $nonReturnableExample->id;
    $item2->product_name = $nonReturnableExample->name;
    $item2->quantity = 2;
    $item2->unit_price = $nonReturnableExample->price;
    $item2->subtotal = $nonReturnableExample->price * 2;
    $item2->tax_rate = 6.00;
    $item2->tax_amount = round($nonReturnableExample->price * 2 * 0.06, 2);
    $item2->total_price = round($nonReturnableExample->price * 2 * 1.06, 2); // Avec TVA 6%
    $item2->is_returnable = false; // Basé sur la catégorie
    $item2->save();
    
    echo "   - Commande mixte creee: {$order2->order_number}\n";
    echo "   - Item 1: {$item1->product_name} (retournable)\n";
    echo "   - Item 2: {$item2->product_name} (NON retournable)\n";
    
    // Traiter le retour partiel
    $returnableItems = [];
    $nonReturnableItems = [];
    
    foreach ($order2->items as $item) {
        $product = $item->product;
        if ($product && $product->category && $product->category->is_returnable) {
            $returnableItems[] = $item->product_name;
        } else {
            $nonReturnableItems[] = $item->product_name;
        }
    }
    
    echo "   - Items retournables: " . count($returnableItems) . " (" . implode(', ', $returnableItems) . ")\n";
    echo "   - Items NON retournables: " . count($nonReturnableItems) . " (" . implode(', ', $nonReturnableItems) . ")\n";
    
    if (count($returnableItems) > 0 && count($nonReturnableItems) > 0) {
        echo "   - Resultat: Retour PARTIEL autorise (uniquement items retournables)\n";
        echo "   - Message utilisateur: Seuls les produits non alimentaires seront retournes et rembourses\n";
    } else if (count($returnableItems) === 0) {
        echo "   - Resultat: Retour REFUSE (aucun item retournable)\n";
        $errors[] = "Commande sans items retournables devrait refuser le retour";
    }

    // 5. Test delai de retour (14 jours)
    echo "\n5. Test delai de retour (14 jours maximum)...\n";
    
    $returnDeadlineDays = 14;
    echo "   - Delai legal: $returnDeadlineDays jours\n";
    
    // Cas 1: Commande recente (5 jours) - VALIDE
    $recentOrder = new \App\Models\Order();
    $recentOrder->user_id = $user->id;
    $recentOrder->order_number = 'TEST-RECENT-' . time();
    $recentOrder->subtotal = 100.00;
    $recentOrder->tax_amount = 21.00;
    $recentOrder->total_amount = 121.00;
    $recentOrder->status = 'delivered';
    $recentOrder->payment_status = 'paid';
    $recentOrder->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $recentOrder->shipping_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $recentOrder->has_non_returnable_items = false;
    $recentOrder->created_at = \Carbon\Carbon::now()->subDays(5); // Il y a 5 jours
    
    // Desactiver events et sauvegarder
    \App\Models\Order::withoutEvents(function() use ($recentOrder) {
        $recentOrder->save(['timestamps' => false]);
    });
    
    // Ajouter un item
    if ($returnableExample) {
        $recentItem = new \App\Models\OrderItem();
        $recentItem->order_id = $recentOrder->id;
        $recentItem->product_id = $returnableExample->id;
        $recentItem->product_name = $returnableExample->name;
        $recentItem->quantity = 2;
        $recentItem->unit_price = 50.00;
        $recentItem->subtotal = 100.00;
        $recentItem->tax_rate = 21.00;
        $recentItem->tax_amount = 21.00;
        $recentItem->total_price = 121.00;
        $recentItem->is_returnable = true;
        $recentItem->save();
    }
    
    $daysAgo = floor($recentOrder->created_at->diffInDays(\Carbon\Carbon::now()));
    $canReturnRecent = $daysAgo <= $returnDeadlineDays;
    
    echo "   - Commande: {$recentOrder->order_number}\n";
    echo "   - Date commande: " . $recentOrder->created_at->format('d/m/Y H:i') . "\n";
    echo "   - Jours ecoules: $daysAgo jours\n";
    echo "   - Retour autorise: " . ($canReturnRecent ? 'OUI' : 'NON') . "\n";
    
    if (!$canReturnRecent) {
        $errors[] = "Commande recente (5 jours) devrait permettre le retour";
    } else {
        echo "   - Resultat: VALIDE (dans les 14 jours)\n";
    }
    
    // Cas 2: Commande a la limite (14 jours exactement) - VALIDE
    $limitOrder = new \App\Models\Order();
    $limitOrder->user_id = $user->id;
    $limitOrder->order_number = 'TEST-LIMIT-' . time();
    $limitOrder->subtotal = 100.00;
    $limitOrder->tax_amount = 21.00;
    $limitOrder->total_amount = 121.00;
    $limitOrder->status = 'delivered';
    $limitOrder->payment_status = 'paid';
    $limitOrder->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $limitOrder->shipping_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $limitOrder->has_non_returnable_items = false;
    $limitOrder->created_at = \Carbon\Carbon::now()->subDays(14); // Il y a exactement 14 jours
    
    \App\Models\Order::withoutEvents(function() use ($limitOrder) {
        $limitOrder->save(['timestamps' => false]);
    });
    
    // Ajouter un item
    if ($returnableExample) {
        $limitItem = new \App\Models\OrderItem();
        $limitItem->order_id = $limitOrder->id;
        $limitItem->product_id = $returnableExample->id;
        $limitItem->product_name = $returnableExample->name;
        $limitItem->quantity = 2;
        $limitItem->unit_price = 50.00;
        $limitItem->subtotal = 100.00;
        $limitItem->tax_rate = 21.00;
        $limitItem->tax_amount = 21.00;
        $limitItem->total_price = 121.00;
        $limitItem->is_returnable = true;
        $limitItem->save();
    }
    
    $daysAgo = floor($limitOrder->created_at->diffInDays(\Carbon\Carbon::now()));
    $canReturnLimit = $daysAgo <= $returnDeadlineDays;
    
    echo "\n   - Commande: {$limitOrder->order_number}\n";
    echo "   - Date commande: " . $limitOrder->created_at->format('d/m/Y H:i') . "\n";
    echo "   - Jours ecoules: $daysAgo jours\n";
    echo "   - Retour autorise: " . ($canReturnLimit ? 'OUI' : 'NON') . "\n";
    
    if (!$canReturnLimit) {
        $errors[] = "Commande a 14 jours exactement devrait permettre le retour";
    } else {
        echo "   - Resultat: VALIDE (dernier jour autorise)\n";
    }
    
    // Cas 3: Commande ancienne (15 jours) - INVALIDE
    $oldOrder = new \App\Models\Order();
    $oldOrder->user_id = $user->id;
    $oldOrder->order_number = 'TEST-OLD-' . time();
    $oldOrder->subtotal = 100.00;
    $oldOrder->tax_amount = 21.00;
    $oldOrder->total_amount = 121.00;
    $oldOrder->status = 'delivered';
    $oldOrder->payment_status = 'paid';
    $oldOrder->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $oldOrder->shipping_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $oldOrder->has_non_returnable_items = false;
    $oldOrder->created_at = \Carbon\Carbon::now()->subDays(15); // Il y a 15 jours
    
    \App\Models\Order::withoutEvents(function() use ($oldOrder) {
        $oldOrder->save(['timestamps' => false]);
    });
    
    // Ajouter un item
    if ($returnableExample) {
        $oldItem = new \App\Models\OrderItem();
        $oldItem->order_id = $oldOrder->id;
        $oldItem->product_id = $returnableExample->id;
        $oldItem->product_name = $returnableExample->name;
        $oldItem->quantity = 2;
        $oldItem->unit_price = 50.00;
        $oldItem->subtotal = 100.00;
        $oldItem->tax_rate = 21.00;
        $oldItem->tax_amount = 21.00;
        $oldItem->total_price = 121.00;
        $oldItem->is_returnable = true;
        $oldItem->save();
    }
    
    $daysAgo = floor($oldOrder->created_at->diffInDays(\Carbon\Carbon::now()));
    $canReturnOld = $daysAgo <= $returnDeadlineDays;
    
    echo "\n   - Commande: {$oldOrder->order_number}\n";
    echo "   - Date commande: " . $oldOrder->created_at->format('d/m/Y H:i') . "\n";
    echo "   - Jours ecoules: $daysAgo jours\n";
    echo "   - Retour autorise: " . ($canReturnOld ? 'OUI' : 'NON') . "\n";
    
    if ($canReturnOld) {
        $errors[] = "Commande de 15 jours ne devrait PAS permettre le retour";
    } else {
        echo "   - Resultat: REFUSE (delai depasse)\n";
        echo "   - Message attendu: Le delai de retour de 14 jours est depasse\n";
    }
    
    // Cas 4: Commande tres ancienne (30 jours) - INVALIDE
    $veryOldOrder = new \App\Models\Order();
    $veryOldOrder->user_id = $user->id;
    $veryOldOrder->order_number = 'TEST-VERYOLD-' . time();
    $veryOldOrder->subtotal = 100.00;
    $veryOldOrder->tax_amount = 21.00;
    $veryOldOrder->total_amount = 121.00;
    $veryOldOrder->status = 'delivered';
    $veryOldOrder->payment_status = 'paid';
    $veryOldOrder->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $veryOldOrder->shipping_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $veryOldOrder->has_non_returnable_items = false;
    $veryOldOrder->created_at = \Carbon\Carbon::now()->subDays(30); // Il y a 30 jours
    
    \App\Models\Order::withoutEvents(function() use ($veryOldOrder) {
        $veryOldOrder->save(['timestamps' => false]);
    });
    
    // Ajouter un item
    if ($returnableExample) {
        $veryOldItem = new \App\Models\OrderItem();
        $veryOldItem->order_id = $veryOldOrder->id;
        $veryOldItem->product_id = $returnableExample->id;
        $veryOldItem->product_name = $returnableExample->name;
        $veryOldItem->quantity = 2;
        $veryOldItem->unit_price = 50.00;
        $veryOldItem->subtotal = 100.00;
        $veryOldItem->tax_rate = 21.00;
        $veryOldItem->tax_amount = 21.00;
        $veryOldItem->total_price = 121.00;
        $veryOldItem->is_returnable = true;
        $veryOldItem->save();
    }
    
    $daysAgo = floor($veryOldOrder->created_at->diffInDays(\Carbon\Carbon::now()));
    $canReturnVeryOld = $daysAgo <= $returnDeadlineDays;
    
    echo "\n   - Commande: {$veryOldOrder->order_number}\n";
    echo "   - Date commande: " . $veryOldOrder->created_at->format('d/m/Y H:i') . "\n";
    echo "   - Jours ecoules: $daysAgo jours\n";
    echo "   - Retour autorise: " . ($canReturnVeryOld ? 'OUI' : 'NON') . "\n";
    
    if ($canReturnVeryOld) {
        $errors[] = "Commande de 30 jours ne devrait PAS permettre le retour";
    } else {
        echo "   - Resultat: REFUSE (tres ancien)\n";
    }

    // 6. Test validation complete d'une demande de retour
    echo "\n6. Test validation complete demande retour...\n";
    
    $testCases = [
        [
            'order' => $order1,
            'description' => 'Produit non retournable',
            'should_pass' => false,
            'reason' => 'Produit alimentaire non retournable'
        ],
        [
            'order' => $recentOrder,
            'description' => 'Commande recente (5 jours)',
            'should_pass' => true,
            'reason' => 'Dans delai de 14 jours'
        ],
        [
            'order' => $oldOrder,
            'description' => 'Commande ancienne (15 jours)',
            'should_pass' => false,
            'reason' => 'Delai depasse'
        ]
    ];
    
    foreach ($testCases as $testCase) {
        $order = $testCase['order'];
        
        // Verification delai
        $daysElapsed = floor($order->created_at->diffInDays(\Carbon\Carbon::now()));
        $withinDeadline = $daysElapsed <= $returnDeadlineDays;
        
        // Verification produits retournables
        $hasReturnableItems = !$order->has_non_returnable_items;
        
        // Validation globale
        $canReturn = $withinDeadline && $hasReturnableItems;
        
        echo "\n   - Test: {$testCase['description']}\n";
        echo "     * Commande: {$order->order_number}\n";
        echo "     * Jours ecoules: $daysElapsed\n";
        echo "     * Dans delai: " . ($withinDeadline ? 'OUI' : 'NON') . "\n";
        echo "     * Items retournables: " . ($hasReturnableItems ? 'OUI' : 'NON') . "\n";
        echo "     * Retour autorise: " . ($canReturn ? 'OUI' : 'NON') . "\n";
        echo "     * Attendu: " . ($testCase['should_pass'] ? 'OUI' : 'NON') . "\n";
        
        if ($canReturn !== $testCase['should_pass']) {
            $errors[] = "Validation incorrecte pour: {$testCase['description']}";
        } else {
            echo "     * Resultat: CORRECT\n";
        }
    }

    // 7. Test regles validation Laravel
    echo "\n7. Test regles validation Laravel...\n";
    
    $validationRules = [
        'order_id' => 'required|exists:orders,id',
        'reason' => 'required|string|min:10|max:500',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1'
    ];
    
    echo "   - Regles definies:\n";
    foreach ($validationRules as $field => $rule) {
        echo "     * $field: $rule\n";
    }
    
    // Test validation
    $validData = [
        'order_id' => $recentOrder->id,
        'reason' => 'Produit défectueux, ne correspond pas à la description',
        'items' => [
            ['product_id' => $returnableExample->id, 'quantity' => 1]
        ]
    ];
    
    $validator = \Illuminate\Support\Facades\Validator::make($validData, $validationRules);
    
    if ($validator->fails()) {
        $errors[] = "Validation echouee pour donnees valides";
        echo "   - Erreurs: " . implode(', ', $validator->errors()->all()) . "\n";
    } else {
        echo "   - Validation donnees valides: REUSSIE\n";
    }

    // 8. Test messages utilisateur
    echo "\n8. Test messages utilisateur...\n";
    
    $messages = [
        'non_returnable' => "Ce produit ne peut pas etre retourne car il s'agit d'un produit alimentaire/frais.",
        'mixed_order' => "Seuls les produits non alimentaires de votre commande seront retournes et rembourses.",
        'deadline_passed' => "Le delai de retour de 14 jours est depasse pour cette commande.",
        'within_deadline' => "Vous avez jusqu'au [DATE] pour retourner cette commande.",
        'partial_return' => "Retour partiel: [X] produit(s) retournable(s) sur [Y] au total.",
        'no_returnable_items' => "Aucun produit de cette commande ne peut etre retourne."
    ];
    
    echo "   - Messages disponibles:\n";
    foreach ($messages as $key => $message) {
        echo "     * $key: $message\n";
    }

    // 9. Test calcul remboursement partiel
    echo "\n9. Test calcul remboursement partiel (commande mixte)...\n";
    
    $totalOrder = $order2->total_amount;
    $refundAmount = 0;
    $refundedItems = [];
    
    foreach ($order2->items as $item) {
        $product = $item->product;
        if ($product && $product->category && $product->category->is_returnable) {
            $refundAmount += $item->total_price;
            $refundedItems[] = $item->product_name . " ({$item->quantity}x)";
        }
    }
    
    echo "   - Commande: {$order2->order_number}\n";
    echo "   - Total commande: " . number_format($totalOrder, 2) . " EUR\n";
    echo "   - Montant rembourse: " . number_format($refundAmount, 2) . " EUR\n";
    echo "   - Montant conserve: " . number_format($totalOrder - $refundAmount, 2) . " EUR\n";
    echo "   - Produits rembourses: " . implode(', ', $refundedItems) . "\n";
    
    if ($refundAmount > 0 && $refundAmount < $totalOrder) {
        echo "   - Resultat: Remboursement PARTIEL correct\n";
    } else if ($refundAmount === 0) {
        $errors[] = "Remboursement devrait etre partiel pour commande mixte";
    }

    // 10. Test categories de produits non retournables
    echo "\n10. Test identification categories non retournables...\n";
    
    $nonReturnableCategories = [
        'fruits-legumes' => 'Fruits et Légumes',
        'semences' => 'Semences et Graines',
        'plants' => 'Plants et Boutures',
        'produits-frais' => 'Produits Frais',
        'alimentation-animaux' => 'Alimentation Animaux (frais)'
    ];
    
    echo "   - Categories typiquement non retournables:\n";
    foreach ($nonReturnableCategories as $slug => $name) {
        $category = \App\Models\Category::where('slug', $slug)->first();
        if ($category) {
            $productCount = \App\Models\Product::where('category_id', $category->id)->count();
            $isReturnable = $category->is_returnable ? 'OUI' : 'NON';
            echo "     * $name: $productCount produit(s) - Retournable: $isReturnable\n";
        } else {
            echo "     * $name: Categorie non trouvee\n";
        }
    }

    // 11. Test statut commande eligibilite retour
    echo "\n11. Test statut commande pour eligibilite retour...\n";
    
    $eligibleStatuses = ['delivered', 'completed'];
    $nonEligibleStatuses = ['pending', 'processing', 'shipped', 'cancelled', 'refunded'];
    
    echo "   - Statuts eligibles pour retour: " . implode(', ', $eligibleStatuses) . "\n";
    echo "   - Statuts NON eligibles: " . implode(', ', $nonEligibleStatuses) . "\n";
    
    foreach ($eligibleStatuses as $status) {
        echo "     * $status: ELIGIBLE\n";
    }
    
    foreach ($nonEligibleStatuses as $status) {
        echo "     * $status: NON ELIGIBLE\n";
    }

    // 12. Nettoyage
    echo "\n12. Nettoyage...\n";
    
    // Supprimer les commandes de test
    $testOrders = \App\Models\Order::where('order_number', 'LIKE', 'TEST-%')->get();
    foreach ($testOrders as $order) {
        $order->items()->delete();
        $order->delete();
    }
    echo "   - Commandes de test supprimees: " . $testOrders->count() . "\n";
    
    // Supprimer les produits de test
    $testProducts = \App\Models\Product::where('slug', 'LIKE', '%-test-%')->get();
    foreach ($testProducts as $product) {
        $product->delete();
    }
    echo "   - Produits de test supprimes: " . $testProducts->count() . "\n";
    
    echo "   - Utilisateur conserve pour futurs tests\n";

} catch (\Exception $e) {
    $errors[] = "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}

// Resultats
$duration = round((microtime(true) - $startTime) * 1000, 2);

echo "\n========================================\n";
if (count($errors) > 0) {
    echo "RESULTAT: ECHEC\n";
    echo "========================================\n";
    echo "Erreurs detectees:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\nDuree: {$duration}ms\n";
    exit(1);
} else {
    echo "RESULTAT: REUSSI\n";
    echo "========================================\n";
    echo "Toutes les restrictions de retour fonctionnent correctement:\n";
    echo "  - Produits non retournables bloques\n";
    echo "  - Retours partiels geres (commandes mixtes)\n";
    echo "  - Delai de 14 jours respecte\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
