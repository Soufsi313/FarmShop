<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configuration de la base de données Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Vérification du filtrage par type 'location'...\n\n";

try {
    // 1. Vérifier tous les produits de type 'rental' et 'both'
    echo "📊 Produits de type 'rental' :\n";
    echo "==============================\n";
    
    $rentalProducts = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->join('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->where('p.type', 'rental')
        ->where('p.is_active', true)
        ->select([
            'p.id',
            'p.name',
            'p.type',
            'c.name as category_name',
            'rc.name as rental_category_name',
            'p.rental_price_per_day',
            'p.deposit_amount'
        ])
        ->orderBy('rc.name')
        ->orderBy('p.name')
        ->get();

    $totalRentalProducts = $rentalProducts->count();
    echo "Total produits de location : $totalRentalProducts\n\n";

    // Grouper par catégorie de location
    $groupedByRentalCategory = $rentalProducts->groupBy('rental_category_name');
    
    foreach ($groupedByRentalCategory as $rentalCategory => $products) {
        echo "🏷️  Catégorie : $rentalCategory (" . $products->count() . " produits)\n";
        echo str_repeat("-", 50) . "\n";
        
        foreach ($products as $product) {
            echo sprintf(
                "  ID: %d | %s | Cat.: %s | Prix/jour: %.2f€ | Caution: %.2f€\n",
                $product->id,
                $product->name,
                $product->category_name,
                $product->rental_price_per_day,
                $product->deposit_amount
            );
        }
        echo "\n";
    }

    // 2. Vérifier s'il y a des produits de type 'both'
    echo "📊 Produits de type 'both' (vente ET location) :\n";
    echo "===============================================\n";
    
    $bothProducts = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->leftJoin('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->where('p.type', 'both')
        ->where('p.is_active', true)
        ->select([
            'p.id',
            'p.name',
            'p.type',
            'c.name as category_name',
            'rc.name as rental_category_name',
            'p.price',
            'p.rental_price_per_day',
            'p.deposit_amount'
        ])
        ->get();

    $totalBothProducts = $bothProducts->count();
    echo "Total produits vente ET location : $totalBothProducts\n\n";

    if ($totalBothProducts > 0) {
        foreach ($bothProducts as $product) {
            echo sprintf(
                "  ID: %d | %s | Cat.: %s | Prix vente: %.2f€ | Prix location/jour: %.2f€ | Caution: %.2f€\n",
                $product->id,
                $product->name,
                $product->category_name,
                $product->price,
                $product->rental_price_per_day ?? 0,
                $product->deposit_amount ?? 0
            );
        }
    } else {
        echo "Aucun produit de type 'both' trouvé.\n";
    }

    echo "\n";

    // 3. Statistiques par catégorie
    echo "📈 Statistiques par catégorie de location :\n";
    echo "==========================================\n";
    
    $stats = DB::table('products as p')
        ->join('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->whereIn('p.type', ['rental', 'both'])
        ->where('p.is_active', true)
        ->groupBy('rc.id', 'rc.name')
        ->select([
            'rc.name as rental_category_name',
            DB::raw('COUNT(*) as product_count'),
            DB::raw('AVG(p.rental_price_per_day) as avg_daily_rate'),
            DB::raw('AVG(p.deposit_amount) as avg_deposit'),
            DB::raw('MIN(p.rental_price_per_day) as min_daily_rate'),
            DB::raw('MAX(p.rental_price_per_day) as max_daily_rate')
        ])
        ->get();

    foreach ($stats as $stat) {
        echo sprintf(
            "🏷️  %s :\n" .
            "   Produits : %d\n" .
            "   Prix moyen/jour : %.2f€\n" .
            "   Caution moyenne : %.2f€\n" .
            "   Prix min/max : %.2f€ - %.2f€\n\n",
            $stat->rental_category_name,
            $stat->product_count,
            $stat->avg_daily_rate,
            $stat->avg_deposit,
            $stat->min_daily_rate,
            $stat->max_daily_rate
        );
    }

    // 4. Vérifier la cohérence des catégories
    echo "🔍 Vérification de la cohérence des catégories :\n";
    echo "===============================================\n";
    
    $inconsistencies = DB::table('products as p')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->join('rental_categories as rc', 'p.rental_category_id', '=', 'rc.id')
        ->whereIn('p.type', ['rental', 'both'])
        ->where(function($query) {
            $query->where(function($q) {
                $q->where('c.name', 'LIKE', '%fruit%')
                  ->orWhere('c.name', 'LIKE', '%légume%')
                  ->orWhere('c.name', 'LIKE', '%céréale%')
                  ->orWhere('c.name', 'LIKE', '%féculent%')
                  ->orWhere('c.name', 'LIKE', '%laitier%');
            });
        })
        ->select([
            'p.id',
            'p.name',
            'c.name as category_name',
            'rc.name as rental_category_name'
        ])
        ->get();

    if ($inconsistencies->isEmpty()) {
        echo "✅ Aucune incohérence détectée - tous les produits de location sont dans des catégories appropriées.\n";
    } else {
        echo "⚠️  Incohérences détectées :\n";
        foreach ($inconsistencies as $inc) {
            echo sprintf(
                "   ID: %d | %s | Cat.: %s | Cat. Location: %s\n",
                $inc->id,
                $inc->name,
                $inc->category_name,
                $inc->rental_category_name
            );
        }
    }

    echo "\n📊 Résumé final :\n";
    echo "================\n";
    echo "Total produits de location uniquement : $totalRentalProducts\n";
    echo "Total produits vente ET location : $totalBothProducts\n";
    echo "Total produits disponibles en location : " . ($totalRentalProducts + $totalBothProducts) . "\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n✅ Vérification terminée.\n";
