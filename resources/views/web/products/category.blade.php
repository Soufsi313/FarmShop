@extends('layouts.app')

@section('title', '{{ __("app.pages.category") }} : ' . $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header de la catégorie -->
    <div class="mb-8">
        <nav class="text-sm breadcrumbs mb-4">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="{{ url('/') }}" class="hover:text-green-600">{{ __("app.nav.home") }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-green-600">Nos produits</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-green-600 font-semibold">{{ trans_category($category, "name") }}</li>
            </ol>
        </nav>
        
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ trans_category($category, "name") }}</h1>
            @if($category->description)
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">{{ trans_category($category, "description") }}</p>
            @endif
            <div class="mt-4">
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">
                    {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} disponible{{ $products->total() > 1 ? 's' : '' }}
                </span>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar des filtres -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtres</h3>
                
                <form method="GET" action="{{ route('products.category', $category) }}" id="filter-form">
                    <!-- Recherche -->
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">{{ __("app.buttons.search") }}<//label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Nom du produit..."
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Type de produit -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }} class="text-green-600">
                                <span class="ml-2 text-sm">{{ __("app.common.all") }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="sale" {{ request('type') === 'sale' ? 'checked' : '' }} class="text-green-600">
                                <span class="ml-2 text-sm">Vente</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="rental" {{ request('type') === 'rental' ? 'checked' : '' }} class="text-green-600">
                                <span class="ml-2 text-sm">{{ __("app.ecommerce.rental") }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Prix -->
                    @if($priceRange)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
                        <div class="flex space-x-2">
                            <input type="number" 
                                   name="price_min" 
                                   value="{{ request('price_min') }}"
                                   placeholder="Min"
                                   min="{{ $priceRange->min_price }}"
                                   max="{{ $priceRange->max_price }}"
                                   step="0.01"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <input type="number" 
                                   name="price_max" 
                                   value="{{ request('price_max') }}"
                                   placeholder="Max"
                                   min="{{ $priceRange->min_price }}"
                                   max="{{ $priceRange->max_price }}"
                                   step="0.01"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            De {{ number_format($priceRange->min_price, 2) }}€ à {{ number_format($priceRange->max_price, 2) }}€
                        </div>
                    </div>
                    @endif

                    <!-- Tri -->
                    <div class="mb-6">
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">{{ __("app.buttons.sort_by") }}<//label>
                        <select name="sort" id="sort" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Plus récent</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom A-Z</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Produits mis en avant</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                        Appliquer les filtres
                    </button>
                </form>

                <a href="{{ route('products.category', $category) }}" class="block w-full text-center mt-3 text-gray-600 hover:text-green-600 text-sm">
                    Réinitialiser
                </a>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="lg:w-3/4">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer"
                             onclick="window.location.href='{{ route('products.show', $product) }}'">
                            <div class="relative">
                                <!-- Image du produit -->
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badge type -->
                                <div class="absolute top-2 left-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->type === 'sale' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $product->type === 'sale' ? 'Vente' : {{ __("app.ecommerce.rental") }} }}
                                    </span>
                                </div>

                                <!-- Badge featured -->
                                @if($product->is_featured)
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                            Vedette
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <!-- Nom du produit (cliquable) -->
                                <h3 class="text-lg font-semibold text-gray-800 mb-2 hover:text-green-600 transition-colors">
                                    {{ $product->name }}
                                </h3>

                                <!-- Description courte -->
                                @if($product->short_description)
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->short_description }}</p>
                                @endif

                                <!-- Prix -->
                                <div class="mb-4">
                                    <span class="text-2xl font-bold text-green-600">
                                        {{ number_format($product->price, 2) }}€
                                    </span>
                                    @if($product->type === 'rental')
                                        <span class="text-sm text-gray-500">/jour</span>
                                    @else
                                        <span class="text-sm text-gray-500">/{{ $product->unit ?? 'pièce' }}</span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                @if($product->type === 'sale')
                                    <div class="flex items-center space-x-2" onclick="event.stopPropagation()">
                                        @guest
                                            <button onclick="alert('Veuillez vous connecter pour ajouter au panier')" 
                                                    class="flex-1 bg-gray-300 text-gray-500 py-2 px-4 rounded-md text-sm cursor-not-allowed">
                                                Ajouter au panier
                                            </button>
                                        @else
                                            <div class="flex items-center space-x-1">
                                                <button type="button" onclick="updateQuantity({{ $product->id }}, -1)" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-md hover:bg-gray-300">-</button>
                                                <input type="number" id="quantity-{{ $product->id }}" value="1" min="1" max="{{ $product->stock ?? 99 }}" class="w-12 text-center border border-gray-300 rounded-md">
                                                <button type="button" onclick="updateQuantity({{ $product->id }}, 1)" class="bg-gray-200 text-gray-700 w-8 h-8 rounded-md hover:bg-gray-300">+</button>
                                            </div>
                                            <button onclick="addToCart({{ $product->id }})" 
                                                    class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 text-sm transition-colors">
                                                Panier
                                            </button>
                                            <button onclick="buyNow({{ $product->id }})" 
                                                    class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 text-sm transition-colors">
                                                Acheter
                                            </button>
                                        @endguest
                                    </div>
                                @else
                                    <div onclick="event.stopPropagation()">
                                        <button onclick="window.location.href='{{ route('products.show', $product) }}'" 
                                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 text-sm transition-colors">
                                            Voir les détails
                                        </button>
                                    </div>
                                @endif

                                <!-- Actions utilisateur -->
                                @auth
                                    <div class="flex justify-center space-x-4 mt-3 pt-3 border-t border-gray-100" onclick="event.stopPropagation()">
                                        <button onclick="toggleLike({{ $product->id }})" 
                                                class="like-btn flex items-center space-x-1 text-gray-500 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="{{ auth()->user()->hasLiked($product) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <span class="like-count text-xs">{{ $product->likes_count ?? 0 }}</span>
                                        </button>
                                        
                                        <button onclick="toggleWishlist({{ $product->id }})" 
                                                class="wishlist-btn flex items-center space-x-1 text-gray-500 hover:text-yellow-500 transition-colors">
                                            <svg class="w-4 h-4" fill="{{ auth()->user()->hasInWishlist($product) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                            <span class="text-xs">Liste</span>
                                        </button>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2m-2 0H8m8 0V9a2 2 0 00-2-2H8a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
                    <p class="text-gray-500 mb-4">Aucun produit ne correspond à vos critères dans cette catégorie.</p>
                    <a href="{{ route('products.category', $category) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                        Voir tous les produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Fonctions JavaScript réutilisées depuis la page principale
function updateQuantity(productId, change) {
    const input = document.getElementById('quantity-' + productId);
    const currentValue = parseInt(input.value);
    const newValue = currentValue + change;
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    
    if (newValue >= min && newValue <= max) {
        input.value = newValue;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity-' + productId)?.value || 1;
    
    fetch(`/api/products/${productId}/add-to-cart`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: parseInt(quantity)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert(data.message || 'Erreur lors de l\'ajout au panier');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout au panier');
    });
}

function buyNow(productId) {
    const quantity = document.getElementById('quantity-' + productId)?.value || 1;
    window.location.href = `/products/${productId}/buy-now?quantity=${quantity}`;
}

function toggleLike(productId) {
    fetch(`/api/products/${productId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function toggleWishlist(productId) {
    fetch(`/api/products/${productId}/wishlist`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Auto-submit du formulaire de tri
document.getElementById('sort')?.addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});
</script>
@endsection
