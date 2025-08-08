<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Vérification des statuts possibles:\n";
echo "=====================================\n\n";

$columns = DB::select('SHOW COLUMNS FROM order_locations WHERE Field = "status"');
echo "Colonne status: " . $columns[0]->Type . "\n";

$columns2 = DB::select('SHOW COLUMNS FROM order_locations WHERE Field = "inspection_status"');
echo "Colonne inspection_status: " . $columns2[0]->Type . "\n";

$columns3 = DB::select('SHOW COLUMNS FROM order_locations WHERE Field = "payment_status"');
echo "Colonne payment_status: " . $columns3[0]->Type . "\n";

$columns4 = DB::select('SHOW COLUMNS FROM order_locations WHERE Field = "deposit_status"');
echo "Colonne deposit_status: " . $columns4[0]->Type . "\n";
?>
