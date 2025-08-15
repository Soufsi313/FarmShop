<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil avec des produits aléatoires
     */
    public function index(Request $request)
    {
        // Récupérer 5 produits aléatoires de toutes les catégories (achat ET location)
        $randomProducts = Product::with(['category'])
            ->where('is_active', true)
            ->whereIn('type', ['sale', 'rental', 'both'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('welcome', compact('randomProducts'));
    }
}
