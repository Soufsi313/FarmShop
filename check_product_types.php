<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TYPES DE PRODUITS DANS LA BASE ===\n";
$types = DB::table('products')->distinct()->pluck('type');
foreach($types as $type) {
    $count = DB::table('products')->where('type', $type)->count();
    echo "- $type ($count produits)\n";
}

?>
