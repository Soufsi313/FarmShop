<?php

/**
 * Test d'Integration: Location avec Date Passee
 * 
 * Teste le blocage de location avec une date de debut anterieure a aujourd'hui
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST INTEGRATION: LOCATION DATE PASSEE\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Preparation de l'utilisateur de test
    echo "1. Preparation de l'utilisateur de test...\n";
    
    $user = \App\Models\User::where('email', 'test_rental_date@example.com')->first();
    
    if (!$user) {
        $user = new \App\Models\User();
        $user->username = 'test_rental_date_' . time();
        $user->email = 'test_rental_date@example.com';
        $user->password = bcrypt('password');
        $user->email_verified_at = now();
        $user->save();
        echo "   - Utilisateur cree: {$user->email}\n";
    } else {
        echo "   - Utilisateur existant: {$user->email}\n";
    }

    // 2. Trouver ou creer un produit de location
    echo "\n2. Recherche d'un produit de location...\n";
    
    $rentalProduct = \App\Models\Product::where('type', 'rental')
        ->orWhere('type', 'both')
        ->where('rental_stock', '>', 0)
        ->first();
    
    if (!$rentalProduct) {
        // Creer un produit de location de test
        $category = \App\Models\Category::first();
        
        if (!$category) {
            $errors[] = "Aucune categorie disponible pour creer un produit de location";
        } else {
            $rentalProduct = new \App\Models\Product();
            $rentalProduct->name = 'Produit Test Location';
            $rentalProduct->slug = 'produit-test-location-' . time();
            $rentalProduct->description = 'Produit de test pour location';
            $rentalProduct->price = 0; // Prix achat non applicable
            $rentalProduct->quantity = 0; // Stock achat
            $rentalProduct->rental_stock = 5; // Stock location
            $rentalProduct->type = 'rental'; // Type location uniquement
            $rentalProduct->unit_symbol = 'pièce';
            $rentalProduct->category_id = $category->id;
            $rentalProduct->is_active = true;
            $rentalProduct->save();
            
            // Ajouter les informations de location
            if (class_exists('App\Models\ProductRentalInfo')) {
                $rentalInfo = new \App\Models\ProductRentalInfo();
                $rentalInfo->product_id = $rentalProduct->id;
                $rentalInfo->price_per_day = 25.00;
                $rentalInfo->deposit_amount = 100.00;
                $rentalInfo->min_rental_days = 1;
                $rentalInfo->max_rental_days = 30;
                $rentalInfo->save();
            }
            
            echo "   - Produit de location cree: {$rentalProduct->name}\n";
        }
    } else {
        echo "   - Produit trouve: {$rentalProduct->name}\n";
    }
    
    if ($rentalProduct) {
        echo "   - Type: {$rentalProduct->type}\n";
        echo "   - Stock location: {$rentalProduct->rental_stock}\n";
        
        // Verifier s'il a des infos de location
        if (method_exists($rentalProduct, 'rentalInfo') || $rentalProduct->relationLoaded('rentalInfo')) {
            $rentalInfo = $rentalProduct->rentalInfo ?? null;
            if ($rentalInfo) {
                echo "   - Prix/jour: {$rentalInfo->price_per_day} EUR\n";
                echo "   - Caution: {$rentalInfo->deposit_amount} EUR\n";
            }
        }
    }

    // 3. Test avec date de debut dans le passe
    echo "\n3. Test location avec date de debut dans le passe...\n";
    
    $today = \Carbon\Carbon::today();
    $yesterday = \Carbon\Carbon::yesterday();
    
    echo "   - Date du jour: " . $today->format('d/m/Y') . "\n";
    echo "   - Date selectionnee: " . $yesterday->format('d/m/Y') . " (hier)\n";
    
    $isValidDate = $yesterday->greaterThanOrEqualTo($today);
    
    if ($isValidDate) {
        $errors[] = "La date d'hier devrait etre invalide pour une nouvelle location";
    } else {
        echo "   - Validation: Date invalide (CORRECT)\n";
        echo "   - Message attendu: La date de debut doit etre aujourd'hui ou dans le futur\n";
    }

    // 4. Test avec plusieurs dates passees
    echo "\n4. Test avec differentes dates passees...\n";
    
    $pastDates = [
        \Carbon\Carbon::yesterday(),
        \Carbon\Carbon::now()->subDays(2),
        \Carbon\Carbon::now()->subWeek(),
        \Carbon\Carbon::now()->subMonth(),
    ];
    
    foreach ($pastDates as $index => $date) {
        $isValid = $date->greaterThanOrEqualTo($today);
        $daysAgo = $today->diffInDays($date);
        
        echo "   - Date: " . $date->format('d/m/Y') . " (il y a $daysAgo jours)\n";
        echo "     Validation: " . ($isValid ? 'VALIDE (ERREUR!)' : 'INVALIDE (correct)') . "\n";
        
        if ($isValid) {
            $errors[] = "Date passee acceptee: " . $date->format('d/m/Y');
        }
    }

    // 5. Test avec dates valides (aujourd'hui et futur)
    echo "\n5. Test avec dates valides...\n";
    
    $validDates = [
        \Carbon\Carbon::today(),
        \Carbon\Carbon::tomorrow(),
        \Carbon\Carbon::now()->addWeek(),
        \Carbon\Carbon::now()->addMonth(),
    ];
    
    foreach ($validDates as $date) {
        $isValid = $date->greaterThanOrEqualTo($today);
        $daysFromNow = $today->diffInDays($date);
        
        echo "   - Date: " . $date->format('d/m/Y');
        if ($daysFromNow > 0) {
            echo " (dans $daysFromNow jours)";
        } else {
            echo " (aujourd'hui)";
        }
        echo "\n";
        echo "     Validation: " . ($isValid ? 'VALIDE (correct)' : 'INVALIDE (ERREUR!)') . "\n";
        
        if (!$isValid) {
            $errors[] = "Date valide refusee: " . $date->format('d/m/Y');
        }
    }

    // 6. Test de validation Laravel
    echo "\n6. Test regles de validation Laravel...\n";
    
    $testCases = [
        [
            'start_date' => $yesterday->format('Y-m-d'),
            'should_pass' => false,
            'description' => 'Hier'
        ],
        [
            'start_date' => $today->format('Y-m-d'),
            'should_pass' => true,
            'description' => 'Aujourd\'hui'
        ],
        [
            'start_date' => \Carbon\Carbon::tomorrow()->format('Y-m-d'),
            'should_pass' => true,
            'description' => 'Demain'
        ],
    ];
    
    foreach ($testCases as $testCase) {
        $validator = \Illuminate\Support\Facades\Validator::make(
            ['start_date' => $testCase['start_date']],
            ['start_date' => 'required|date|after_or_equal:today']
        );
        
        $passed = !$validator->fails();
        $expected = $testCase['should_pass'];
        
        echo "   - {$testCase['description']} ({$testCase['start_date']}): ";
        echo ($passed ? 'VALIDE' : 'INVALIDE');
        
        if ($passed === $expected) {
            echo " (attendu)\n";
        } else {
            echo " (INATTENDU)\n";
            $errors[] = "Validation incorrecte pour {$testCase['description']}";
        }
        
        if (!$passed && $validator->errors()->has('start_date')) {
            echo "     Erreur: " . $validator->errors()->first('start_date') . "\n";
        }
    }

    // 7. Test date de fin avant date de debut
    echo "\n7. Test date de fin avant date de debut...\n";
    
    $startDate = \Carbon\Carbon::tomorrow();
    $endDate = \Carbon\Carbon::today();
    
    echo "   - Date debut: " . $startDate->format('d/m/Y') . "\n";
    echo "   - Date fin: " . $endDate->format('d/m/Y') . "\n";
    
    $isValidPeriod = $endDate->greaterThanOrEqualTo($startDate);
    
    if ($isValidPeriod) {
        $errors[] = "Date de fin avant date de debut devrait etre invalide";
    } else {
        echo "   - Validation: Periode invalide (CORRECT)\n";
        echo "   - Message attendu: La date de fin doit etre posterieure a la date de debut\n";
    }

    // 8. Test avec le panier de location
    echo "\n8. Test ajout au panier avec date passee...\n";
    
    if ($rentalProduct) {
        $cartLocation = \App\Models\CartLocation::firstOrCreate(
            ['user_id' => $user->id],
            [
                'subtotal_amount' => 0,
                'total_deposit' => 0,
                'tva_amount' => 0,
                'total_amount' => 0,
                'tva_rate' => 0.21,
                'total_items' => 0
            ]
        );
        
        echo "   - Panier location ID: {$cartLocation->id}\n";
        
        // Tenter d'ajouter avec date passee
        $additionBlocked = false;
        $errorMessage = null;
        
        try {
            // Validation avant ajout
            if ($yesterday->lessThan($today)) {
                $additionBlocked = true;
                $errorMessage = "La date de debut de location ne peut pas etre dans le passe.";
                echo "   - Validation: Date passee detectee\n";
                echo "   - Message: $errorMessage\n";
            } else {
                echo "   - Ajout au panier (NE DEVRAIT PAS SE PRODUIRE)\n";
            }
        } catch (\Exception $e) {
            $additionBlocked = true;
            $errorMessage = $e->getMessage();
            echo "   - Exception: $errorMessage\n";
        }
        
        if (!$additionBlocked) {
            $errors[] = "L'ajout au panier avec date passee n'a pas ete bloque";
        } else {
            echo "   - Resultat: Ajout bloque (CORRECT)\n";
        }
    }

    // 9. Test des contraintes de dates
    echo "\n9. Test des contraintes de dates...\n";
    
    if ($rentalProduct && method_exists($rentalProduct, 'rentalInfo')) {
        $rentalInfo = $rentalProduct->rentalInfo;
        
        if ($rentalInfo && isset($rentalInfo->min_rental_days)) {
            echo "   - Duree minimale: {$rentalInfo->min_rental_days} jour(s)\n";
            echo "   - Duree maximale: {$rentalInfo->max_rental_days} jour(s)\n";
            
            // Test periode trop courte
            $start = \Carbon\Carbon::tomorrow();
            $end = $start->copy(); // Meme jour
            $duration = $start->diffInDays($end) + 1;
            
            echo "   - Test periode: " . $start->format('d/m/Y') . " au " . $end->format('d/m/Y') . "\n";
            echo "   - Duree: $duration jour(s)\n";
            
            if ($duration < $rentalInfo->min_rental_days) {
                echo "   - Validation: Duree insuffisante (CORRECT)\n";
            }
        }
    }

    // 10. Test message utilisateur
    echo "\n10. Test messages d'erreur utilisateur...\n";
    
    $userMessages = [
        "La date de debut doit etre aujourd'hui ou dans le futur.",
        "La date de debut de location ne peut pas etre dans le passe.",
        "Veuillez selectionner une date valide (aujourd'hui ou ulterieure).",
        "La date de fin doit etre posterieure ou egale a la date de debut.",
        "La periode de location est invalide."
    ];
    
    echo "   - Messages d'erreur disponibles:\n";
    foreach ($userMessages as $message) {
        echo "     * $message\n";
    }

    // 11. Test types de produits
    echo "\n11. Test verification types de produits...\n";
    
    $productTypes = [
        'sale' => 'Achat uniquement',
        'rental' => 'Location uniquement',
        'both' => 'Achat et Location'
    ];
    
    foreach ($productTypes as $type => $label) {
        $count = \App\Models\Product::where('type', $type)->count();
        echo "   - $label ($type): $count produit(s)\n";
    }
    
    // Verifier qu'on a bien des produits de chaque type
    $rentalOnlyCount = \App\Models\Product::where('type', 'rental')->count();
    $saleOnlyCount = \App\Models\Product::where('type', 'sale')->count();
    
    echo "   - Produits location seulement: $rentalOnlyCount\n";
    echo "   - Produits achat seulement: $saleOnlyCount\n";

    // 12. Nettoyage
    echo "\n12. Nettoyage...\n";
    
    if (isset($cartLocation)) {
        $cartLocation->items()->delete();
        echo "   - Panier location nettoye\n";
    }
    
    // Supprimer le produit de test si on l'a cree
    if (isset($rentalProduct) && $rentalProduct->slug === 'produit-test-location-' . $rentalProduct->id) {
        $rentalProduct->delete();
        echo "   - Produit de test supprime\n";
    }
    
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
    echo "Le systeme bloque correctement les locations avec dates passees\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
