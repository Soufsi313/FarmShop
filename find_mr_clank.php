<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 RECHERCHE MR CLANK\n";
echo "=====================\n";

$mrClank = DB::table('users')->where('username', 'mr_clank')->orWhere('email', 'like', '%clank%')->first();

if ($mrClank) {
    echo "✅ Mr Clank trouvé :\n";
    echo "   ID: {$mrClank->id}\n";
    echo "   Name: {$mrClank->name}\n";
    echo "   Email: {$mrClank->email}\n";
    echo "   Username: " . ($mrClank->username ?? 'N/A') . "\n";
} else {
    echo "❌ Mr Clank non trouvé\n";
    echo "Recherche d'utilisateurs système...\n";
    
    $systemUsers = DB::table('users')->where('role', 'Admin')->get(['id', 'name', 'email', 'username']);
    foreach ($systemUsers as $user) {
        echo "- ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    }
}
