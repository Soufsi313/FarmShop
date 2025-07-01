<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Utilisateurs disponibles:\n";
foreach (App\Models\User::take(3)->get() as $user) {
    echo "- {$user->id}: {$user->name} ({$user->email})\n";
}
