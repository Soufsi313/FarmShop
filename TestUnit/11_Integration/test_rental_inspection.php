<?php

/**
 * Test d'Integration: Inspection Materiel Location
 * 
 * Teste le processus complet d'inspection apres fin de location:
 * 1. Calcul des jours de retard
 * 2. Calcul des frais de retard
 * 3. Detection de dommages
 * 4. Calcul automatique des penalites
 * 5. Gestion de la caution (liberation ou capture)
 * 6. Cloture et inspection de location
 */

// Bootstrap Laravel si necessaire
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
    require_once __DIR__ . '/../../vendor/autoload.php';
    $app = require_once __DIR__ . '/../../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "\n========================================\n";
echo "TEST INTEGRATION: INSPECTION LOCATION\n";
echo "========================================\n\n";

$startTime = microtime(true);
$errors = [];

try {
    // 1. Preparation utilisateur et produit de location
    echo "1. Preparation utilisateur et produit de location...\n";
    
    $user = \App\Models\User::where('email', 'test_rental_inspection@example.com')->first();
    
    if (!$user) {
        $user = new \App\Models\User();
        $user->username = 'test_rental_inspection_' . time();
        $user->email = 'test_rental_inspection@example.com';
        $user->password = bcrypt('password');
        $user->email_verified_at = now();
        $user->save();
        echo "   - Utilisateur cree: {$user->email}\n";
    } else {
        echo "   - Utilisateur existant: {$user->email}\n";
    }
    
    // Trouver un produit de location
    $product = \App\Models\Product::where('type', 'rental')
        ->orWhere('type', 'both')
        ->where('rental_stock', '>', 0)
        ->first();
    
    if (!$product) {
        $errors[] = "Aucun produit de location disponible pour le test";
    } else {
        echo "   - Produit: {$product->name}\n";
        echo "   - Stock location: {$product->rental_stock}\n";
    }

    // 2. Test calcul jours de retard
    echo "\n2. Test calcul jours de retard...\n";
    
    // Cas 1: Retour a temps (0 jours de retard)
    $orderOnTime = new \App\Models\OrderLocation();
    $orderOnTime->user_id = $user->id;
    $orderOnTime->order_number = 'TEST-ONTIME-' . time();
    $orderOnTime->start_date = \Carbon\Carbon::now()->subDays(7)->startOfDay();
    $orderOnTime->end_date = \Carbon\Carbon::now()->subDays(1)->startOfDay(); // Hier
    $orderOnTime->actual_return_date = \Carbon\Carbon::now()->subDays(1)->startOfDay(); // Retourne hier
    $orderOnTime->rental_days = 7;
    $orderOnTime->daily_rate = 25.00;
    $orderOnTime->total_rental_cost = 175.00;
    $orderOnTime->deposit_amount = 100.00;
    $orderOnTime->late_fee_per_day = 10.00;
    $orderOnTime->subtotal = 175.00;
    $orderOnTime->tax_rate = 21.00;
    $orderOnTime->tax_amount = 36.75;
    $orderOnTime->total_amount = 211.75;
    $orderOnTime->status = 'completed';
    $orderOnTime->payment_status = 'paid';
    $orderOnTime->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $orderOnTime->delivery_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $orderOnTime->auto_calculate_damages = true;
    
    \App\Models\OrderLocation::withoutEvents(function() use ($orderOnTime) {
        $orderOnTime->save();
    });
    
    $lateDaysOnTime = $orderOnTime->calculateLateDays();
    echo "   - Commande: {$orderOnTime->order_number}\n";
    echo "   - Date fin prevue: " . $orderOnTime->end_date->format('d/m/Y') . "\n";
    echo "   - Date retour reel: " . $orderOnTime->actual_return_date->format('d/m/Y') . "\n";
    echo "   - Jours de retard: $lateDaysOnTime\n";
    
    if ($lateDaysOnTime != 0) {
        $errors[] = "Retour a temps devrait avoir 0 jours de retard, obtenu: $lateDaysOnTime";
    } else {
        echo "   - Resultat: CORRECT (retour a temps)\n";
    }
    
    // Cas 2: Retour avec 3 jours de retard
    $orderLate = new \App\Models\OrderLocation();
    $orderLate->user_id = $user->id;
    $orderLate->order_number = 'TEST-LATE-' . time();
    $orderLate->start_date = \Carbon\Carbon::now()->subDays(10)->startOfDay();
    $orderLate->end_date = \Carbon\Carbon::now()->subDays(3)->startOfDay(); // Devait finir il y a 3 jours
    $orderLate->actual_return_date = \Carbon\Carbon::now()->startOfDay(); // Retourne aujourd'hui
    $orderLate->rental_days = 7;
    $orderLate->daily_rate = 25.00;
    $orderLate->total_rental_cost = 175.00;
    $orderLate->deposit_amount = 100.00;
    $orderLate->late_fee_per_day = 10.00;
    $orderLate->subtotal = 175.00;
    $orderLate->tax_rate = 21.00;
    $orderLate->tax_amount = 36.75;
    $orderLate->total_amount = 211.75;
    $orderLate->status = 'completed';
    $orderLate->payment_status = 'paid';
    $orderLate->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $orderLate->delivery_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $orderLate->auto_calculate_damages = true;
    
    \App\Models\OrderLocation::withoutEvents(function() use ($orderLate) {
        $orderLate->save();
    });
    
    $lateDaysLate = $orderLate->calculateLateDays();
    echo "\n   - Commande: {$orderLate->order_number}\n";
    echo "   - Date fin prevue: " . $orderLate->end_date->format('d/m/Y') . "\n";
    echo "   - Date retour reel: " . $orderLate->actual_return_date->format('d/m/Y') . "\n";
    echo "   - Jours de retard: $lateDaysLate\n";
    
    if ($lateDaysLate != 3) {
        $errors[] = "Retour avec 3 jours de retard devrait calculer 3, obtenu: $lateDaysLate";
    } else {
        echo "   - Resultat: CORRECT (3 jours de retard)\n";
    }

    // 3. Test calcul frais de retard
    echo "\n3. Test calcul frais de retard...\n";
    
    $lateFeesOnTime = $orderOnTime->calculateLateFees();
    echo "   - Commande a temps: $lateFeesOnTime EUR (attendu: 0)\n";
    
    if ($lateFeesOnTime != 0) {
        $errors[] = "Frais de retard pour retour a temps devrait etre 0, obtenu: $lateFeesOnTime";
    }
    
    $lateFeesLate = $orderLate->calculateLateFees();
    $expectedLateFees = 3 * 10.00; // 3 jours * 10 EUR/jour
    echo "   - Commande en retard: $lateFeesLate EUR (attendu: $expectedLateFees)\n";
    
    if ($lateFeesLate != $expectedLateFees) {
        $errors[] = "Frais de retard incorrects: attendu $expectedLateFees, obtenu: $lateFeesLate";
    } else {
        echo "   - Resultat: CORRECT (10 EUR/jour * 3 jours = 30 EUR)\n";
    }

    // 4. Test inspection sans dommages
    echo "\n4. Test inspection sans dommages (liberation caution)...\n";
    
    // Mettre le statut en 'inspecting'
    $orderOnTime->status = 'inspecting';
    $orderOnTime->inspection_status = 'pending';
    $orderOnTime->save();
    
    $inspectionDataNoDamage = [
        'product_condition' => 'excellent',
        'has_damages' => false,
        'damage_notes' => null,
        'inspection_notes' => 'Materiel retourne en excellent etat, aucun dommage constate.'
    ];
    
    // Simuler authentification admin
    $admin = \App\Models\User::where('email', 'admin@farmshop.com')->first();
    if (!$admin) {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@farmshop.com',
            'username' => 'admin_test',
            'password' => bcrypt('password')
        ]);
    }
    \Illuminate\Support\Facades\Auth::login($admin);
    
    $orderOnTime->completeInspection($inspectionDataNoDamage);
    $orderOnTime->refresh();
    
    echo "   - Commande: {$orderOnTime->order_number}\n";
    echo "   - Statut: {$orderOnTime->status}\n";
    echo "   - Inspection: {$orderOnTime->inspection_status}\n";
    echo "   - Etat produit: {$orderOnTime->product_condition}\n";
    echo "   - Dommages: " . ($orderOnTime->has_damages ? 'OUI' : 'NON') . "\n";
    echo "   - Cout dommages: " . number_format($orderOnTime->damage_cost, 2) . " EUR\n";
    echo "   - Frais retard: " . number_format($orderOnTime->late_fees, 2) . " EUR\n";
    echo "   - Total penalites: " . number_format($orderOnTime->total_penalties, 2) . " EUR\n";
    echo "   - Caution: " . number_format($orderOnTime->deposit_amount, 2) . " EUR\n";
    echo "   - Caution liberee: " . number_format($orderOnTime->deposit_refund, 2) . " EUR\n";
    
    if ($orderOnTime->status !== 'finished') {
        $errors[] = "Statut devrait etre 'finished' apres inspection";
    }
    
    if ($orderOnTime->has_damages) {
        $errors[] = "Inspection sans dommages ne devrait pas marquer has_damages = true";
    }
    
    if ($orderOnTime->damage_cost != 0) {
        $errors[] = "Cout dommages devrait etre 0 sans dommages";
    }
    
    if ($orderOnTime->deposit_refund != $orderOnTime->deposit_amount) {
        $errors[] = "Caution devrait etre entierement liberee sans dommages";
    } else {
        echo "   - Resultat: Caution LIBEREE (100 EUR rendus au client)\n";
    }

    // 5. Test inspection avec dommages (capture caution)
    echo "\n5. Test inspection avec dommages (capture caution)...\n";
    
    $orderLate->status = 'inspecting';
    $orderLate->inspection_status = 'pending';
    $orderLate->late_days = $lateDaysLate;
    $orderLate->late_fees = $lateFeesLate;
    $orderLate->save();
    
    $inspectionDataWithDamage = [
        'product_condition' => 'poor',
        'has_damages' => true,
        'damage_notes' => 'Rayures importantes sur le boitier, poignee cassee.',
        'damage_photos' => ['photo1.jpg', 'photo2.jpg'],
        'inspection_notes' => 'Materiel retourne avec dommages importants necessitant reparation.'
    ];
    
    $orderLate->completeInspection($inspectionDataWithDamage);
    $orderLate->refresh();
    
    echo "   - Commande: {$orderLate->order_number}\n";
    echo "   - Statut: {$orderLate->status}\n";
    echo "   - Inspection: {$orderLate->inspection_status}\n";
    echo "   - Etat produit: {$orderLate->product_condition}\n";
    echo "   - Dommages: " . ($orderLate->has_damages ? 'OUI' : 'NON') . "\n";
    echo "   - Photos dommages: " . count($orderLate->damage_photos) . "\n";
    echo "   - Cout dommages: " . number_format($orderLate->damage_cost, 2) . " EUR\n";
    echo "   - Frais retard: " . number_format($orderLate->late_fees, 2) . " EUR\n";
    echo "   - Total penalites: " . number_format($orderLate->total_penalties, 2) . " EUR\n";
    echo "   - Caution: " . number_format($orderLate->deposit_amount, 2) . " EUR\n";
    echo "   - Caution liberee: " . number_format($orderLate->deposit_refund, 2) . " EUR\n";
    
    // Avec auto_calculate_damages = true, damage_cost = caution + late_fees
    $expectedDamageCost = $orderLate->deposit_amount + $orderLate->late_fees;
    $expectedTotalPenalties = $orderLate->late_fees + $expectedDamageCost;
    
    if (!$orderLate->has_damages) {
        $errors[] = "Inspection avec dommages devrait marquer has_damages = true";
    }
    
    if ($orderLate->damage_cost != $expectedDamageCost) {
        $errors[] = "Cout dommages incorrect: attendu $expectedDamageCost, obtenu: {$orderLate->damage_cost}";
    } else {
        echo "   - Calcul dommages: CORRECT (caution 100 + retard 30 = 130 EUR)\n";
    }
    
    if ($orderLate->total_penalties != $expectedTotalPenalties) {
        $errors[] = "Total penalites incorrect: attendu $expectedTotalPenalties, obtenu: {$orderLate->total_penalties}";
    }
    
    if ($orderLate->deposit_refund != 0) {
        $errors[] = "Caution devrait etre entierement capturee avec dommages";
    } else {
        echo "   - Resultat: Caution CAPTUREE (0 EUR rendus, 100 EUR gardes)\n";
    }

    // 6. Test calcul automatique dommages
    echo "\n6. Test calcul automatique dommages...\n";
    
    $testCases = [
        [
            'has_damages' => false,
            'auto_calculate' => true,
            'deposit' => 150.00,
            'late_fees' => 0,
            'expected_damage' => 0,
            'description' => 'Pas de dommages'
        ],
        [
            'has_damages' => true,
            'auto_calculate' => false,
            'deposit' => 150.00,
            'late_fees' => 20.00,
            'expected_damage' => 0,
            'description' => 'Dommages mais calcul manuel'
        ],
        [
            'has_damages' => true,
            'auto_calculate' => true,
            'deposit' => 150.00,
            'late_fees' => 20.00,
            'expected_damage' => 170.00,
            'description' => 'Dommages avec calcul auto'
        ]
    ];
    
    foreach ($testCases as $index => $case) {
        $testOrder = new \App\Models\OrderLocation();
        $testOrder->has_damages = $case['has_damages'];
        $testOrder->auto_calculate_damages = $case['auto_calculate'];
        $testOrder->deposit_amount = $case['deposit'];
        $testOrder->late_fees = $case['late_fees'];
        
        $calculatedDamage = $testOrder->calculateDamageAmount();
        
        echo "   - Cas " . ($index + 1) . ": {$case['description']}\n";
        echo "     * Caution: {$case['deposit']} EUR\n";
        echo "     * Retard: {$case['late_fees']} EUR\n";
        echo "     * Dommages calcules: $calculatedDamage EUR\n";
        echo "     * Attendu: {$case['expected_damage']} EUR\n";
        
        if ($calculatedDamage != $case['expected_damage']) {
            $errors[] = "Cas {$case['description']}: attendu {$case['expected_damage']}, obtenu $calculatedDamage";
        } else {
            echo "     * Resultat: CORRECT\n";
        }
    }

    // 7. Test attributs caution (capture vs liberation)
    echo "\n7. Test attributs caution (capture vs liberation)...\n";
    
    echo "   - Commande sans dommages:\n";
    echo "     * Caution capturee: " . ($orderOnTime->deposit_will_be_captured ? 'OUI' : 'NON') . "\n";
    echo "     * Montant libere: " . number_format($orderOnTime->deposit_release_amount, 2) . " EUR\n";
    
    if ($orderOnTime->deposit_will_be_captured) {
        $errors[] = "Commande sans dommages ne devrait pas capturer la caution";
    }
    
    if ($orderOnTime->deposit_release_amount != $orderOnTime->deposit_amount) {
        $errors[] = "Montant libere devrait etre egal a la caution sans dommages";
    }
    
    echo "   - Commande avec dommages:\n";
    echo "     * Caution capturee: " . ($orderLate->deposit_will_be_captured ? 'OUI' : 'NON') . "\n";
    echo "     * Montant libere: " . number_format($orderLate->deposit_release_amount, 2) . " EUR\n";
    
    if (!$orderLate->deposit_will_be_captured) {
        $errors[] = "Commande avec dommages devrait capturer la caution";
    }
    
    if ($orderLate->deposit_release_amount != 0) {
        $errors[] = "Montant libere devrait etre 0 avec dommages";
    }

    // 8. Test methode closeRental
    echo "\n8. Test methode closeRental (cloture par utilisateur)...\n";
    
    $orderToClose = new \App\Models\OrderLocation();
    $orderToClose->user_id = $user->id;
    $orderToClose->order_number = 'TEST-CLOSE-' . time();
    $orderToClose->start_date = \Carbon\Carbon::now()->subDays(5);
    $orderToClose->end_date = \Carbon\Carbon::now()->subDays(1);
    $orderToClose->rental_days = 4;
    $orderToClose->daily_rate = 30.00;
    $orderToClose->total_rental_cost = 120.00;
    $orderToClose->deposit_amount = 80.00;
    $orderToClose->late_fee_per_day = 12.00;
    $orderToClose->subtotal = 120.00;
    $orderToClose->tax_rate = 21.00;
    $orderToClose->tax_amount = 25.20;
    $orderToClose->total_amount = 145.20;
    $orderToClose->status = 'completed'; // Doit etre completed pour pouvoir cloturer
    $orderToClose->payment_status = 'paid';
    $orderToClose->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $orderToClose->delivery_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $orderToClose->auto_calculate_damages = true;
    
    \App\Models\OrderLocation::withoutEvents(function() use ($orderToClose) {
        $orderToClose->save();
    });
    
    echo "   - Commande: {$orderToClose->order_number}\n";
    echo "   - Statut avant: {$orderToClose->status}\n";
    echo "   - Peut etre cloturee: " . ($orderToClose->can_be_closed ? 'OUI' : 'NON') . "\n";
    
    if ($orderToClose->can_be_closed) {
        $orderToClose->closeRental(\Carbon\Carbon::now());
        $orderToClose->refresh();
        
        echo "   - Statut apres: {$orderToClose->status}\n";
        echo "   - Statut inspection: {$orderToClose->inspection_status}\n";
        echo "   - Date retour: " . $orderToClose->actual_return_date->format('d/m/Y H:i') . "\n";
        echo "   - Jours retard: {$orderToClose->late_days}\n";
        echo "   - Frais retard: " . number_format($orderToClose->late_fees, 2) . " EUR\n";
        
        if ($orderToClose->status !== 'inspecting') {
            $errors[] = "Statut devrait etre 'inspecting' apres closeRental";
        }
        
        if ($orderToClose->inspection_status !== 'pending') {
            $errors[] = "Inspection devrait etre 'pending' apres closeRental";
        }
        
        if ($orderToClose->late_days === null) {
            $errors[] = "late_days devrait etre calcule apres closeRental";
        } else {
            echo "   - Resultat: CORRECT (statut 'inspecting', retard calcule)\n";
        }
    } else {
        $errors[] = "Commande completed apres end_date devrait pouvoir etre cloturee";
    }

    // 9. Test validation donnees inspection
    echo "\n9. Test validation donnees inspection...\n";
    
    $invalidCases = [
        [
            'data' => [
                'product_condition' => 'invalid',
                'has_damages' => false,
                'damage_notes' => null,
                'inspection_notes' => 'Test notes'
            ],
            'should_fail' => true,
            'description' => 'Condition invalide'
        ],
        [
            'data' => [
                'product_condition' => 'good',
                'has_damages' => false,
                'damage_notes' => null,
                'inspection_notes' => ''
            ],
            'should_fail' => true,
            'description' => 'Notes manquantes'
        ],
        [
            'data' => [
                'product_condition' => 'excellent',
                'has_damages' => false,
                'damage_notes' => null,
                'inspection_notes' => 'Test valide'
            ],
            'should_fail' => false,
            'description' => 'Donnees valides'
        ]
    ];
    
    foreach ($invalidCases as $index => $case) {
        echo "   - Test " . ($index + 1) . ": {$case['description']}\n";
        
        $testOrderInspection = new \App\Models\OrderLocation();
        $testOrderInspection->user_id = $user->id;
        $testOrderInspection->order_number = 'TEST-VALID-' . time() . '-' . $index;
        $testOrderInspection->start_date = \Carbon\Carbon::now()->subDays(3);
        $testOrderInspection->end_date = \Carbon\Carbon::now()->subDays(1);
        $testOrderInspection->rental_days = 2;
        $testOrderInspection->daily_rate = 20.00;
        $testOrderInspection->total_rental_cost = 40.00;
        $testOrderInspection->deposit_amount = 50.00;
        $testOrderInspection->late_fee_per_day = 8.00;
        $testOrderInspection->subtotal = 40.00;
        $testOrderInspection->tax_rate = 21.00;
        $testOrderInspection->tax_amount = 8.40;
        $testOrderInspection->total_amount = 48.40;
        $testOrderInspection->status = 'inspecting';
        $testOrderInspection->inspection_status = 'pending';
        $testOrderInspection->payment_status = 'paid';
        $testOrderInspection->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
        $testOrderInspection->delivery_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
        $testOrderInspection->auto_calculate_damages = true;
        
        \App\Models\OrderLocation::withoutEvents(function() use ($testOrderInspection) {
            $testOrderInspection->save();
        });
        
        try {
            $testOrderInspection->completeInspection($case['data']);
            
            if ($case['should_fail']) {
                $errors[] = "{$case['description']} devrait echouer la validation";
                echo "     * Resultat: ERREUR (validation devrait echouer)\n";
            } else {
                echo "     * Resultat: CORRECT (validation reussie)\n";
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (!$case['should_fail']) {
                $errors[] = "{$case['description']} ne devrait pas echouer: " . $e->getMessage();
                echo "     * Resultat: ERREUR (validation ne devrait pas echouer)\n";
            } else {
                echo "     * Resultat: CORRECT (validation echouee comme attendu)\n";
                echo "     * Erreur: " . implode(', ', array_keys($e->errors())) . "\n";
            }
        }
    }

    // 10. Test scenario complet
    echo "\n10. Test scenario complet (location -> retard -> dommages -> inspection)...\n";
    
    $fullScenario = new \App\Models\OrderLocation();
    $fullScenario->user_id = $user->id;
    $fullScenario->order_number = 'TEST-FULL-' . time();
    $fullScenario->start_date = \Carbon\Carbon::now()->subDays(12)->startOfDay();
    $fullScenario->end_date = \Carbon\Carbon::now()->subDays(5)->startOfDay(); // Devait finir il y a 5 jours
    $fullScenario->rental_days = 7;
    $fullScenario->daily_rate = 35.00;
    $fullScenario->total_rental_cost = 245.00;
    $fullScenario->deposit_amount = 200.00;
    $fullScenario->late_fee_per_day = 15.00;
    $fullScenario->subtotal = 245.00;
    $fullScenario->tax_rate = 21.00;
    $fullScenario->tax_amount = 51.45;
    $fullScenario->total_amount = 296.45;
    $fullScenario->status = 'completed';
    $fullScenario->payment_status = 'paid';
    $fullScenario->billing_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $fullScenario->delivery_address = json_encode(['street' => 'Test', 'city' => 'Brussels', 'zip' => '1000']);
    $fullScenario->auto_calculate_damages = true;
    
    \App\Models\OrderLocation::withoutEvents(function() use ($fullScenario) {
        $fullScenario->save();
    });
    
    echo "   - Commande: {$fullScenario->order_number}\n";
    echo "   - Location: " . $fullScenario->start_date->format('d/m/Y') . " au " . $fullScenario->end_date->format('d/m/Y') . "\n";
    echo "   - Cout location: " . number_format($fullScenario->total_rental_cost, 2) . " EUR\n";
    echo "   - Caution: " . number_format($fullScenario->deposit_amount, 2) . " EUR\n";
    
    // Etape 1: Cloture par le client
    echo "\n   Etape 1: Client retourne le materiel (avec retard)\n";
    $fullScenario->closeRental(\Carbon\Carbon::now()->startOfDay());
    $fullScenario->refresh();
    
    echo "   - Statut: {$fullScenario->status}\n";
    echo "   - Jours retard: {$fullScenario->late_days}\n";
    echo "   - Frais retard: " . number_format($fullScenario->late_fees, 2) . " EUR\n";
    
    // Etape 2: Inspection par l'admin (dommages detectes)
    echo "\n   Etape 2: Admin inspecte et detecte des dommages\n";
    $fullInspection = [
        'product_condition' => 'poor',
        'has_damages' => true,
        'damage_notes' => 'Choc important sur le carter moteur, lame tordue, poignee cassee.',
        'damage_photos' => ['damage1.jpg', 'damage2.jpg', 'damage3.jpg'],
        'inspection_notes' => 'Materiel necessitant reparations importantes. Retour avec 5 jours de retard.'
    ];
    
    $fullScenario->completeInspection($fullInspection);
    $fullScenario->refresh();
    
    echo "   - Statut final: {$fullScenario->status}\n";
    echo "   - Etat produit: {$fullScenario->product_condition}\n";
    echo "   - Dommages: " . ($fullScenario->has_damages ? 'OUI' : 'NON') . "\n";
    echo "   - Notes: {$fullScenario->damage_notes}\n";
    echo "   - Photos: " . count($fullScenario->damage_photos) . "\n";
    
    echo "\n   Calcul final:\n";
    echo "   - Frais retard (5j x 15 EUR): " . number_format($fullScenario->late_fees, 2) . " EUR\n";
    echo "   - Cout dommages (caution + retard): " . number_format($fullScenario->damage_cost, 2) . " EUR\n";
    echo "   - Total penalites: " . number_format($fullScenario->total_penalties, 2) . " EUR\n";
    echo "   - Caution initiale: " . number_format($fullScenario->deposit_amount, 2) . " EUR\n";
    echo "   - Caution liberee: " . number_format($fullScenario->deposit_refund, 2) . " EUR\n";
    
    $expectedLateDays = 5;
    $expectedLateFees = 5 * 15.00; // 75 EUR (5 jours complets * 15 EUR/jour)
    $expectedDamageCost = 200.00 + 75.00; // 275 EUR (caution + late_fees)
    $expectedTotalPenalties = 75.00 + 275.00; // 350 EUR (late_fees + damage_cost)
    
    if ($fullScenario->late_days != $expectedLateDays) {
        $errors[] = "Scenario complet: jours retard incorrects (attendu $expectedLateDays, obtenu {$fullScenario->late_days})";
    }
    
    if ($fullScenario->late_fees != $expectedLateFees) {
        $errors[] = "Scenario complet: frais retard incorrects (attendu $expectedLateFees, obtenu {$fullScenario->late_fees})";
    }
    
    if ($fullScenario->damage_cost != $expectedDamageCost) {
        $errors[] = "Scenario complet: cout dommages incorrect (attendu $expectedDamageCost, obtenu {$fullScenario->damage_cost})";
    }
    
    if ($fullScenario->total_penalties != $expectedTotalPenalties) {
        $errors[] = "Scenario complet: total penalites incorrect (attendu $expectedTotalPenalties, obtenu {$fullScenario->total_penalties})";
    }
    
    if ($fullScenario->deposit_refund != 0) {
        $errors[] = "Scenario complet: caution devrait etre capturee";
    }
    
    if (count($errors) === 0) {
        echo "   - Resultat: SCENARIO COMPLET REUSSI\n";
    }

    // 11. Nettoyage
    echo "\n11. Nettoyage...\n";
    
    $testOrders = \App\Models\OrderLocation::where('order_number', 'LIKE', 'TEST-%')->get();
    foreach ($testOrders as $order) {
        $order->items()->delete();
        $order->delete();
    }
    echo "   - Commandes de test supprimees: " . $testOrders->count() . "\n";
    
    \Illuminate\Support\Facades\Auth::logout();
    echo "   - Session admin deconnectee\n";
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
    echo "Toutes les fonctionnalites d'inspection fonctionnent correctement:\n";
    echo "  - Calcul jours de retard\n";
    echo "  - Calcul frais de retard\n";
    echo "  - Detection de dommages\n";
    echo "  - Calcul automatique penalites\n";
    echo "  - Gestion caution (liberation/capture)\n";
    echo "  - Cloture et inspection de location\n";
    echo "Duree: {$duration}ms\n";
    exit(0);
}
