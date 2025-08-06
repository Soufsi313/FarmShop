<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Structure de la table products:\n";
echo "===================================\n\n";

try {
    $columns = DB::select('DESCRIBE products');
    
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) {$column->Null} {$column->Key} {$column->Default}\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
