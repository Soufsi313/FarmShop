<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Diagnostic du produit 214...\n";

$product = \App\Models\Product::withTrashed()->find(214);

if ($product) {
    echo "✅ Produit trouvé :\n";
    echo "- ID: {$product->id}\n";
    echo "- Nom: {$product->name}\n";
    echo "- Type: {$product->type}\n";
    echo "- Deleted at: " . ($product->deleted_at ?? 'NULL') . "\n";
    echo "- Is rental available: " . ($product->is_rental_available ? 'OUI' : 'NON') . "\n";
    echo "- Rental stock: " . ($product->rental_stock ?? 'NULL') . "\n";
    echo "- Is active: " . ($product->is_active ? 'OUI' : 'NON') . "\n";
    echo "- isRentable(): " . ($product->isRentable() ? 'OUI' : 'NON') . "\n";
    
    // Test du model binding
    echo "\n🔗 Test model binding :\n";
    try {
        $boundProduct = \App\Models\Product::findOrFail(214);
        echo "✅ Model binding réussi\n";
    } catch (\Exception $e) {
        echo "❌ Model binding échoué: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Produit 214 non trouvé\n";
}
