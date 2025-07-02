<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use App\Models\Product;
use Illuminate\Http\Request;

class SpecialOfferController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Afficher la liste des offres spéciales
     */
    public function index(Request $request)
    {
        $query = SpecialOffer::with('product');

        // Filtres
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->available();
                    break;
                case 'scheduled':
                    $query->where('start_date', '>', now());
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $offers = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Produits pour le filtre
        $products = Product::active()->orderBy('name')->get();

        return view('admin.special-offers.index', compact('offers', 'products'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        return view('admin.special-offers.create', compact('products'));
    }

    /**
     * Enregistrer une nouvelle offre spéciale
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'product_id' => 'required|exists:products,id',
            'min_quantity' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0.01|max:99.99',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        // Vérifier qu'il n'y a pas d'offre active overlapping pour le même produit
        $existingOffer = SpecialOffer::where('product_id', $request->product_id)
            ->where('is_active', true)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->first();

        if ($existingOffer) {
            return back()->withErrors([
                'product_id' => 'Une offre spéciale active existe déjà pour ce produit sur cette période.'
            ])->withInput();
        }

        SpecialOffer::create($request->all());

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Offre spéciale créée avec succès.');
    }

    /**
     * Afficher une offre spéciale
     */
    public function show(SpecialOffer $specialOffer)
    {
        $specialOffer->load('product');
        return view('admin.special-offers.show', compact('specialOffer'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(SpecialOffer $specialOffer)
    {
        $products = Product::active()->orderBy('name')->get();
        return view('admin.special-offers.edit', compact('specialOffer', 'products'));
    }

    /**
     * Mettre à jour l'offre spéciale
     */
    public function update(Request $request, SpecialOffer $specialOffer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'product_id' => 'required|exists:products,id',
            'min_quantity' => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0.01|max:99.99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        // Vérifier les overlaps (sauf pour cette offre)
        $existingOffer = SpecialOffer::where('product_id', $request->product_id)
            ->where('is_active', true)
            ->where('id', '!=', $specialOffer->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->first();

        if ($existingOffer) {
            return back()->withErrors([
                'product_id' => 'Une offre spéciale active existe déjà pour ce produit sur cette période.'
            ])->withInput();
        }

        $specialOffer->update($request->all());

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Offre spéciale mise à jour avec succès.');
    }

    /**
     * Supprimer l'offre spéciale
     */
    public function destroy(SpecialOffer $specialOffer)
    {
        $specialOffer->delete();

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Offre spéciale supprimée avec succès.');
    }

    /**
     * Activer/désactiver une offre
     */
    public function toggle(SpecialOffer $specialOffer)
    {
        $specialOffer->update(['is_active' => !$specialOffer->is_active]);

        $status = $specialOffer->is_active ? 'activée' : 'désactivée';
        
        return redirect()->route('admin.special-offers.index')
            ->with('success', "Offre spéciale {$status} avec succès.");
    }

    /**
     * Activer une offre
     */
    public function activate(SpecialOffer $specialOffer)
    {
        $specialOffer->update(['is_active' => true]);
        
        return redirect()->back()
            ->with('success', 'Offre spéciale activée avec succès.');
    }

    /**
     * Désactiver une offre
     */
    public function deactivate(SpecialOffer $specialOffer)
    {
        $specialOffer->update(['is_active' => false]);
        
        return redirect()->back()
            ->with('success', 'Offre spéciale désactivée avec succès.');
    }
}
