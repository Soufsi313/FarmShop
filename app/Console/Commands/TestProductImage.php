<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestProductImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:product-image {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the ProductImage model and controller functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action') ?? 'all';

        $this->info('🎯 Test de la gestion des images de produits');
        $this->info('=' . str_repeat('=', 50));

        switch ($action) {
            case 'create':
                $this->testCreateImages();
                break;
            case 'list':
                $this->testListImages();
                break;
            case 'update':
                $this->testUpdateImage();
                break;
            case 'reorder':
                $this->testReorderImages();
                break;
            case 'stats':
                $this->testStatistics();
                break;
            case 'cleanup':
                $this->testCleanup();
                break;
            case 'all':
            default:
                $this->testListImages();
                $this->testStatistics();
                break;
        }

        return 0;
    }

    private function testCreateImages()
    {
        $this->info('📸 Test de création d\'images de produit...');
        
        $product = Product::first();
        if (!$product) {
            $this->error('Aucun produit trouvé. Veuillez d\'abord créer des produits.');
            return;
        }

        // Simulation de création d'images de test
        $testImages = [
            [
                'product_id' => $product->id,
                'image_path' => 'test_image_1.jpg',
                'alt_text' => $product->name . ' - Vue principale',
                'sort_order' => 0,
            ],
            [
                'product_id' => $product->id,
                'image_path' => 'test_image_2.jpg',
                'alt_text' => $product->name . ' - Détail',
                'sort_order' => 1,
            ],
        ];

        foreach ($testImages as $imageData) {
            $image = ProductImage::create($imageData);
            $this->info("✅ Image créée: {$image->alt_text} (ID: {$image->id})");
        }

        $this->info("✨ {$product->name} a maintenant " . $product->images()->count() . " image(s)");
    }

    private function testListImages()
    {
        $this->info('📋 Liste des images de produits...');
        
        $images = ProductImage::with('product')->ordered()->get();
        
        if ($images->isEmpty()) {
            $this->warn('Aucune image de produit trouvée.');
            return;
        }

        $this->table(
            ['ID', 'Produit', 'Chemin', 'Alt Text', 'Ordre'],
            $images->map(function ($image) {
                return [
                    $image->id,
                    $image->product ? $image->product->name : 'N/A',
                    $image->image_path,
                    $image->alt_text ?: 'N/A',
                    $image->sort_order,
                ];
            })->toArray()
        );

        $this->info("📊 Total: {$images->count()} image(s)");
    }

    private function testUpdateImage()
    {
        $this->info('✏️ Test de mise à jour d\'image...');
        
        $image = ProductImage::first();
        if (!$image) {
            $this->warn('Aucune image trouvée pour le test de mise à jour.');
            return;
        }

        $oldAltText = $image->alt_text;
        $newAltText = 'Alt text mis à jour - ' . now()->format('Y-m-d H:i:s');
        
        $image->update(['alt_text' => $newAltText]);
        
        $this->info("✅ Image ID {$image->id} mise à jour:");
        $this->info("   Ancien alt text: {$oldAltText}");
        $this->info("   Nouveau alt text: {$newAltText}");
    }

    private function testReorderImages()
    {
        $this->info('🔄 Test de réorganisation des images...');
        
        $product = Product::whereHas('images')->first();
        if (!$product) {
            $this->warn('Aucun produit avec images trouvé.');
            return;
        }

        $images = $product->images()->ordered()->get();
        if ($images->count() < 2) {
            $this->warn('Au moins 2 images sont nécessaires pour tester la réorganisation.');
            return;
        }

        $this->info("📦 Produit: {$product->name}");
        $this->info('Ordre actuel:');
        foreach ($images as $image) {
            $this->info("  - {$image->alt_text} (ordre: {$image->sort_order})");
        }

        // Inverser l'ordre
        foreach ($images as $index => $image) {
            $image->update(['sort_order' => $images->count() - 1 - $index]);
        }

        $this->info('Nouvel ordre:');
        $reorderedImages = $product->images()->ordered()->get();
        foreach ($reorderedImages as $image) {
            $this->info("  - {$image->alt_text} (ordre: {$image->sort_order})");
        }
    }

    private function testStatistics()
    {
        $this->info('📊 Statistiques des images de produits...');
        
        $totalImages = ProductImage::count();
        $productsWithImages = ProductImage::distinct('product_id')->count();
        $productsWithoutImages = Product::whereDoesntHave('images')->count();
        $avgImagesPerProduct = $totalImages > 0 ? round($totalImages / max(Product::count(), 1), 2) : 0;

        $stats = [
            ['Métrique', 'Valeur'],
            ['Total images', $totalImages],
            ['Produits avec images', $productsWithImages],
            ['Produits sans images', $productsWithoutImages],
            ['Moyenne images/produit', $avgImagesPerProduct],
        ];

        $this->table(['Métrique', 'Valeur'], array_slice($stats, 1));

        // Top 5 des produits avec le plus d'images
        $topProducts = ProductImage::select('product_id', \DB::raw('count(*) as image_count'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('image_count')
            ->limit(5)
            ->get();

        if ($topProducts->isNotEmpty()) {
            $this->info('🏆 Top 5 produits avec le plus d\'images:');
            $topProductsData = $topProducts->map(function ($item) {
                return [
                    $item->product ? $item->product->name : 'Produit supprimé',
                    $item->image_count . ' image(s)'
                ];
            })->toArray();
            
            $this->table(['Produit', 'Nombre d\'images'], $topProductsData);
        }
    }

    private function testCleanup()
    {
        $this->info('🧹 Nettoyage des images de test...');
        
        $testImages = ProductImage::where('image_path', 'like', 'test_image_%')->get();
        
        if ($testImages->isEmpty()) {
            $this->info('Aucune image de test à supprimer.');
            return;
        }

        $count = $testImages->count();
        $testImages->each(function ($image) {
            $image->delete();
        });

        $this->info("✅ {$count} image(s) de test supprimée(s)");
    }
}
