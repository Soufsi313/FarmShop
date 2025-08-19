@extends('layouts.admin')

@section('title', 'Détails du Produit - FarmShop Admin')
@section('page-title', 'Détails du Produit')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
            <p class="text-gray-600">SKU: {{ $product->sku }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                Modifier
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                        <dd class="text-sm text-gray-900">{{ $product->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                        <dd class="text-sm text-gray-900">{{ $product->category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="text-sm text-gray-900">
                            @if($product->type === 'sale')
                                Vente uniquement
                            @elseif($product->type === 'rental')
                                Location uniquement
                            @elseif($product->type === 'both')
                                Vente et Location
                            @else
                                Non défini
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unité</dt>
                        <dd class="text-sm text-gray-900">{{ $product->unit_symbol }}</dd>
                    </div>
                </dl>

                @if($product->description)
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                    <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $product->description }}</dd>
                </div>
                @endif
            </div>

            <!-- Prix et disponibilité -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Prix et disponibilité</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($product->type === 'sale' || $product->type === 'both')
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Prix de vente</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ number_format($product->price, 2) }} €</dd>
                    </div>
                    @endif

                    @if($product->type === 'rental' || $product->type === 'both')
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Prix location/jour</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ number_format($product->rental_price_per_day, 2) }} €</dd>
                    </div>
                    @endif

                    @if($product->deposit_amount)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Caution</dt>
                        <dd class="text-sm text-gray-900">{{ number_format($product->deposit_amount, 2) }} €</dd>
                    </div>
                    @endif

                    @if($product->min_rental_days)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Durée minimum</dt>
                        <dd class="text-sm text-gray-900">{{ $product->min_rental_days }} jour(s)</dd>
                    </div>
                    @endif

                    @if($product->max_rental_days)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Durée maximum</dt>
                        <dd class="text-sm text-gray-900">{{ $product->max_rental_days }} jour(s)</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Stock -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gestion du stock</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quantité actuelle</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ $product->quantity }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Seuil critique</dt>
                        <dd class="text-sm text-gray-900">{{ $product->critical_threshold }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Seuil faible</dt>
                        <dd class="text-sm text-gray-900">{{ $product->low_stock_threshold ?? 'Non défini' }}</dd>
                    </div>
                </dl>

                <!-- Statut du stock -->
                <div class="mt-4">
                    @if($product->is_out_of_stock)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            ⚠️ Rupture de stock
                        </span>
                    @elseif($product->is_critical_stock)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                            ⚠️ Stock critique
                        </span>
                    @elseif($product->is_low_stock)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            ⚠️ Stock faible
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ✅ Stock OK
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Image principale -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Image principale</h3>
                
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                         alt="{{ $product->image_alt ?: $product->name }}" 
                         class="w-full h-48 object-cover rounded-lg">
                    @if($product->image_alt)
                        <p class="mt-2 text-xs text-gray-500">{{ $product->image_alt }}</p>
                    @endif
                @else
                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 text-center">Aucune image</p>
                @endif
            </div>

            <!-- Galerie d'images -->
            @if($product->gallery_images && count($product->gallery_images) > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Galerie d'images</h3>
                
                <div class="grid grid-cols-2 gap-2">
                    @foreach($product->gallery_images as $image)
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-20 object-cover rounded">
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Statut et visibilité -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Actif</span>
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Non
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">En vedette</span>
                        @if($product->is_featured)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                ⭐ Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Non
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Caractéristiques -->
            @if($product->weight || $product->dimensions)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Caractéristiques</h3>
                
                <dl class="space-y-3">
                    @if($product->weight)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Poids</dt>
                        <dd class="text-sm text-gray-900">{{ $product->weight }} kg</dd>
                    </div>
                    @endif
                    
                    @if($product->dimensions)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dimensions</dt>
                        <dd class="text-sm text-gray-900">{{ $product->dimensions }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Informations système -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations système</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                        <dd class="text-sm text-gray-900">{{ $product->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Modifié le</dt>
                        <dd class="text-sm text-gray-900">{{ $product->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="text-sm text-gray-900">{{ $product->slug }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
