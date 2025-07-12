<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;

class CheckProductAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer le produit depuis l'URL ou les paramètres
        $productId = $request->route('product')?->id ?? $request->input('product_id');
        
        if (!$productId) {
            return $next($request);
        }

        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé'
            ], 404);
        }

        // Vérifier si le produit est en rupture de stock
        if ($product->is_out_of_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit est en rupture de stock et ne peut pas être ajouté au panier',
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'stock_status' => $product->stock_status,
                    'quantity' => $product->quantity
                ]
            ], 400);
        }

        // Vérifier si le produit est actif
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce produit n\'est plus disponible',
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'is_active' => $product->is_active
                ]
            ], 400);
        }

        return $next($request);
    }
}
