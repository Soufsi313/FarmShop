<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil avec les vraies catégories
     */
    public function index()
    {
        // Récupérer les catégories actives pour l'affichage public
        // Ordonnées par display_order (notre champ SEO) puis par nom
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->take(8) // Limiter à 8 catégories pour l'affichage landing
            ->get();

        return view('welcome', compact('categories'));
    }
}
