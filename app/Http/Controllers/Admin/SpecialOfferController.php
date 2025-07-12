<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpecialOfferController extends Controller
{
    public function index()
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }

        $specialOffers = SpecialOffer::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.special-offers.index', compact('specialOffers'));
    }

    public function create()
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }

        $products = Product::where('is_active', true)->get();
        return view('admin.special-offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean'
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('special-offers', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        SpecialOffer::create($data);

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Offre spéciale créée avec succès.');
    }

    public function show(SpecialOffer $specialOffer)
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }

        $specialOffer->load('product');
        return view('admin.special-offers.show', compact('specialOffer'));
    }

    public function edit(SpecialOffer $specialOffer)
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }

        $products = Product::where('is_active', true)->get();
        return view('admin.special-offers.edit', compact('specialOffer', 'products'));
    }

    public function update(Request $request, SpecialOffer $specialOffer)
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean'
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($specialOffer->image && Storage::disk('public')->exists($specialOffer->image)) {
                Storage::disk('public')->delete($specialOffer->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('special-offers', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $specialOffer->update($data);

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Offre spéciale mise à jour avec succès.');
    }

    public function destroy(SpecialOffer $specialOffer)
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }

        // Delete image if exists
        if ($specialOffer->image && Storage::disk('public')->exists($specialOffer->image)) {
            Storage::disk('public')->delete($specialOffer->image);
        }

        $specialOffer->delete();

        return redirect()->route('admin.special-offers.index')
            ->with('success', 'Offre spéciale supprimée avec succès.');
    }

    public function toggle(SpecialOffer $specialOffer)
    {
        // Vérification des permissions
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les offres spéciales.');
        }

        $specialOffer->update([
            'is_active' => !$specialOffer->is_active
        ]);

        return back()->with('success', 'Statut de l\'offre mis à jour avec succès.');
    }
}
