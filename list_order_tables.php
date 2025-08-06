<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Tables contenant 'order' ou 'item':\n";
echo "====================================\n";

$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values((array) $table)[0];
    if (strpos($tableName, 'item') !== false || strpos($tableName, 'order') !== false) {
        echo $tableName . "\n";
    }
}
