<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Structure de la table order_item_locations:\n";
echo "==========================================\n";

$columns = DB::select('DESCRIBE order_item_locations');
foreach ($columns as $col) {
    echo "{$col->Field} - {$col->Type}\n";
}
