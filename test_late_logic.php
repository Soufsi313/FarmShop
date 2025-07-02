<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use App\Models\User;
use Carbon\Carbon;

echo "\n🧪 Test de la nouvelle logique de calcul du retard\n";
echo "=" . str_repeat("=", 50) . "\n";

// Créer un utilisateur de test
$user = User::factory()->create([
    'name' => 'Test Retard',
    'email' => 'test-retard@example.com'
]);

// Test 1: Location clôturée le jour J avant 23h59 (pas de retard)
echo "\n📝 Test 1: Clôture le jour J à 20h00 (pas de retard)\n";
$order1 = OrderLocation::create([
    'order_number' => 'TEST-RETARD-1',
    'user_id' => $user->id,
    'status' => 'pending_inspection',
    'rental_start_date' => Carbon::now()->subDays(3),
    'rental_end_date' => Carbon::now()->startOfDay(), // Aujourd'hui
    'client_return_date' => Carbon::now()->setTime(20, 0, 0), // 20h00 aujourd'hui
    'total_amount' => 100.00,
    'deposit_amount' => 50.00,
]);

echo "  - Date de fin: " . $order1->rental_end_date->format('d/m/Y H:i') . "\n";
echo "  - Date de clôture client: " . $order1->client_return_date->format('d/m/Y H:i') . "\n";
echo "  - En retard: " . ($order1->is_overdue ? 'OUI' : 'NON') . "\n";
echo "  - Jours de retard: " . $order1->days_late . "\n";

// Test 2: Location clôturée le lendemain (retard)
echo "\n📝 Test 2: Clôture le lendemain à 10h00 (retard de 1 jour)\n";
$order2 = OrderLocation::create([
    'order_number' => 'TEST-RETARD-2',
    'user_id' => $user->id,
    'status' => 'pending_inspection',
    'rental_start_date' => Carbon::now()->subDays(3),
    'rental_end_date' => Carbon::now()->subDay()->startOfDay(), // Hier
    'client_return_date' => Carbon::now()->setTime(10, 0, 0), // 10h00 aujourd'hui
    'total_amount' => 100.00,
    'deposit_amount' => 50.00,
]);

echo "  - Date de fin: " . $order2->rental_end_date->format('d/m/Y H:i') . "\n";
echo "  - Date de clôture client: " . $order2->client_return_date->format('d/m/Y H:i') . "\n";
echo "  - En retard: " . ($order2->is_overdue ? 'OUI' : 'NON') . "\n";
echo "  - Jours de retard: " . $order2->days_late . "\n";

// Test 3: Location clôturée après minuit le jour de fin (retard)
echo "\n📝 Test 3: Clôture après minuit le jour de fin (retard)\n";
$order3 = OrderLocation::create([
    'order_number' => 'TEST-RETARD-3',
    'user_id' => $user->id,
    'status' => 'pending_inspection',
    'rental_start_date' => Carbon::now()->subDays(3),
    'rental_end_date' => Carbon::now()->startOfDay(), // Aujourd'hui
    'client_return_date' => Carbon::now()->addDay()->setTime(1, 0, 0), // 01h00 demain
    'total_amount' => 100.00,
    'deposit_amount' => 50.00,
]);

echo "  - Date de fin: " . $order3->rental_end_date->format('d/m/Y H:i') . "\n";
echo "  - Date de clôture client: " . $order3->client_return_date->format('d/m/Y H:i') . "\n";
echo "  - En retard: " . ($order3->is_overdue ? 'OUI' : 'NON') . "\n";
echo "  - Jours de retard: " . $order3->days_late . "\n";

// Test 4: Location active qui n'a pas encore dépassé 23h59 le jour de fin
echo "\n📝 Test 4: Location active le jour J avant 23h59 (pas de retard)\n";
$order4 = OrderLocation::create([
    'order_number' => 'TEST-RETARD-4',
    'user_id' => $user->id,
    'status' => 'active',
    'rental_start_date' => Carbon::now()->subDays(3),
    'rental_end_date' => Carbon::now()->startOfDay(), // Aujourd'hui
    'total_amount' => 100.00,
    'deposit_amount' => 50.00,
]);

echo "  - Date de fin: " . $order4->rental_end_date->format('d/m/Y H:i') . "\n";
echo "  - Date actuelle: " . Carbon::now()->format('d/m/Y H:i') . "\n";
echo "  - En retard: " . ($order4->is_overdue ? 'OUI' : 'NON') . "\n";
echo "  - Jours de retard: " . $order4->days_late . "\n";

// Test 5: Location active qui a dépassé 23h59 le jour de fin
echo "\n📝 Test 5: Location active après 23h59 le jour de fin (retard)\n";
$order5 = OrderLocation::create([
    'order_number' => 'TEST-RETARD-5',
    'user_id' => $user->id,
    'status' => 'active',
    'rental_start_date' => Carbon::now()->subDays(4),
    'rental_end_date' => Carbon::now()->subDay()->startOfDay(), // Hier
    'total_amount' => 100.00,
    'deposit_amount' => 50.00,
]);

echo "  - Date de fin: " . $order5->rental_end_date->format('d/m/Y H:i') . "\n";
echo "  - Date actuelle: " . Carbon::now()->format('d/m/Y H:i') . "\n";
echo "  - En retard: " . ($order5->is_overdue ? 'OUI' : 'NON') . "\n";
echo "  - Jours de retard: " . $order5->days_late . "\n";

// Nettoyer les données de test
echo "\n🧹 Nettoyage des données de test...\n";
OrderLocation::whereIn('order_number', [
    'TEST-RETARD-1', 'TEST-RETARD-2', 'TEST-RETARD-3', 'TEST-RETARD-4', 'TEST-RETARD-5'
])->delete();
$user->delete();

echo "✅ Tests terminés !\n\n";

echo "📋 Résumé de la logique:\n";
echo "  - Une location est en retard uniquement si elle est clôturée après 23h59 le jour de fin\n";
echo "  - Pour les locations actives, elles sont en retard si on a dépassé 23h59 le jour de fin\n";
echo "  - Le calcul des jours de retard est basé sur la différence entre la date de clôture et 23h59 le jour de fin\n";
