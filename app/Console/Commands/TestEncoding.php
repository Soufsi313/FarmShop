<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestEncoding extends Command
{
    protected $signature = 'app:test-encoding';
    protected $description = 'Teste l\'affichage de l\'encodage';

    public function handle()
    {
        $this->info('=== TEST ENCODAGE ===');

        // Test catégories
        $this->info('📂 Catégories:');
        $categories = DB::table('categories')->orderBy('name')->get(['name']);
        foreach ($categories as $category) {
            $this->info("  • {$category->name}");
        }

        // Test produits avec accents
        $this->info('📦 Produits avec accents:');
        $products = DB::table('products')
            ->where('name', 'like', '%é%')
            ->orWhere('name', 'like', '%è%')
            ->orWhere('name', 'like', '%à%')
            ->orWhere('name', 'like', '%ç%')
            ->take(5)
            ->get(['name']);
            
        foreach ($products as $product) {
            $this->info("  • {$product->name}");
        }

        $this->info('✅ Test terminé');
    }
}
