<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Cart System ===\n";

try {
    // Test database connection
    $userCount = DB::table('users')->count();
    echo "Users in database: $userCount\n";
    
    if ($userCount > 0) {
        $user = DB::table('users')->first();
        echo "Testing with user: {$user->email}\n";
        
        // Test cart creation
        $cartCount = DB::table('carts')->where('user_id', $user->id)->count();
        echo "Existing carts for user: $cartCount\n";
        
        // Check cart table structure
        $cartColumns = DB::select("PRAGMA table_info(carts)");
        echo "Cart table columns:\n";
        foreach ($cartColumns as $column) {
            echo "  - {$column->name} ({$column->type})\n";
        }
        
    } else {
        echo "No users found in database\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "=== Test Complete ===\n";
