@extends('layouts.app')

@section('title', 'Nos Produits - FarmShop')

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .price-range-slider {
        -webkit-appearance: none;
        appearance: none;
        height: 5px;
        border-radius: 5px;
        background: #ddd;
        outline: none;
    }
    .price-range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #22c55e;
        cursor: pointer;
    }
    .price-range-slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #22c55e;
        cursor: pointer;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="productPage">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Nos Produits</h1>
                <p class="text-xl text-green-100 max-w-2xl mx-auto">
                    Découvrez notre sélection de produits biologiques et d'équipements de qualité
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filtres -->
            <aside class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h3>
                    
                    <form id="filterForm" method="GET" action="{{ route('products.index') }}">
                        <!-- Recherche -->
                        <div class="mb-6">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                Rechercher
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nom du produit..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>

                        <!-- Catégories -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie
                            </label>
                            <select id="category" 
                                    name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prix -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Prix (€)
                            </label>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <input type="number" 
                                       name="price_min" 
                                       value="{{ request('price_min') }}"
                                       placeholder="Min"
                                       step="0.01"
                                       min="0"
                                       max="{{ $priceRange->max_price ?? 100 }}"
                                       class="px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500">
                                <input type="number" 
                                       name="price_max" 
                                       value="{{ request('price_max') }}"
                                       placeholder="Max"
                                       step="0.01"
                                       min="0"
                                       max="{{ $priceRange->max_price ?? 100 }}"
                                       class="px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-green-500">
                            </div>
                            <div class="text-xs text-gray-500">
                                De {{ number_format($priceRange->min_price ?? 0, 2) }}€ à {{ number_format($priceRange->max_price ?? 100, 2) }}€
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors">
                                Filtrer
                            </button>
                            <a href="{{ route('products.index') }}" 
                               class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-center transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Contenu principal -->
            <main class="lg:w-3/4">
                <!-- Barre de tri et informations -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <p class="text-gray-600">
                                {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} trouvé{{ $products->total() > 1 ? 's' : '' }}
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <label for="sort" class="text-sm font-medium text-gray-700">Trier par :</label>
                            <select id="sort" 
                                    name="sort" 
                                    onchange="updateSort(this.value)"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Plus récents</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>En vedette</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grille des produits -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($products as $product)
                            <div class="product-card bg-white rounded-lg shadow overflow-hidden">
                                <!-- Image du produit - Cliquable -->
                                <a href="{{ route('products.show', $product->slug) }}" class="block relative h-48 bg-gray-200 hover:opacity-95 transition-opacity">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-2 left-2 flex flex-col space-y-1">
                                        @if($product->is_featured)
                                            <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                ⭐ Vedette
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Stock indicator -->
                                    @if($product->quantity <= $product->critical_threshold)
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                Stock faible
                                            </span>
                                        </div>
                                    @endif
                                </a>

                                <!-- Contenu -->
                                <div class="p-4">
                                    <!-- Nom et catégorie - Cliquables -->
                                    <div class="mb-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="block hover:text-green-600 transition-colors">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                        </a>
                                        <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                                    </div>

                                    <!-- Description courte -->
                                    @if($product->short_description)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                            {{ $product->short_description }}
                                        </p>
                                    @endif

                                    <!-- Prix -->
                                    <div class="mb-4">
                                        <div class="text-2xl font-bold text-green-600">
                                            {{ number_format($product->price, 2) }}€
                                            <span class="text-sm font-normal text-gray-500">/ {{ $product->unit_symbol }}</span>
                                        </div>
                                        @if($product->rental_price_per_day && ($product->type === 'rental' || $product->type === 'both'))
                                            <div class="text-sm text-gray-500">
                                                Location : {{ number_format($product->rental_price_per_day, 2) }}€/jour
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions pour les produits d'achat -->
                                    @if($product->type === 'sale' || $product->type === 'both')
                                        <div class="space-y-3">
                                            <!-- Quantité -->
                                            <div class="flex items-center space-x-2">
                                                <label class="text-sm font-medium text-gray-700">Quantité :</label>
                                                <div class="flex items-center">
                                                    <button type="button" 
                                                            onclick="decreaseQuantity({{ $product->id }})"
                                                            class="px-2 py-1 border border-gray-300 rounded-l-md hover:bg-gray-50">
                                                        -
                                                    </button>
                                                    <input type="number" 
                                                           id="quantity_{{ $product->id }}" 
                                                           value="1" 
                                                           min="1" 
                                                           max="{{ $product->quantity }}"
                                                           class="w-16 px-2 py-1 border-t border-b border-gray-300 text-center focus:outline-none">
                                                    <button type="button" 
                                                            onclick="increaseQuantity({{ $product->id }})"
                                                            class="px-2 py-1 border border-gray-300 rounded-r-md hover:bg-gray-50">
                                                        +
                                                    </button>
                                                </div>
                                                <span class="text-sm text-gray-500">({{ $product->quantity }} disponible{{ $product->quantity > 1 ? 's' : '' }})</span>
                                            </div>

                                            <!-- Boutons d'action -->
                                            <div class="flex space-x-2">
                                                <!-- Ajouter au panier -->
                                                @auth
                                                    <button onclick="addToCart({{ $product->id }})" 
                                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                                        Ajouter au panier
                                                    </button>
                                                @else
                                                    <a href="{{ route('login') }}" 
                                                       class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium text-center transition-colors">
                                                        Ajouter au panier
                                                    </a>
                                                @endauth

                                                <!-- Acheter maintenant -->
                                                @auth
                                                    <button onclick="buyNow({{ $product->id }})" 
                                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                                        Acheter
                                                    </button>
                                                @else
                                                    <a href="{{ route('login') }}" 
                                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium text-center transition-colors">
                                                        Acheter
                                                    </a>
                                                @endauth
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Boutons généraux -->
                                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-200">
                                        <!-- Voir le détail -->
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="text-green-600 hover:text-green-700 font-medium text-sm transition-colors">
                                            Voir le détail →
                                        </a>

                                        <!-- Actions utilisateur -->
                                        @auth
                                            <div class="flex space-x-2">
                                                <!-- Like -->
                                                <button onclick="toggleLike({{ $product->id }})" 
                                                        id="like_btn_{{ $product->id }}"
                                                        class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                                                        title="Aimer">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                </button>

                                                <!-- Wishlist -->
                                                <button onclick="toggleWishlist({{ $product->id }})" 
                                                        id="wishlist_btn_{{ $product->id }}"
                                                        class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                                                        title="Ajouter à la wishlist">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white rounded-lg shadow p-4">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Aucun produit -->
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
                        <p class="text-gray-500 mb-4">Essayez de modifier vos critères de recherche.</p>
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Voir tous les produits
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Alpine.js data
document.addEventListener('alpine:init', () => {
    Alpine.data('productPage', () => ({
        // Données réactives si nécessaire
    }))
})

// Fonctions utilitaires
function updateSort(sortValue) {
    const url = new URL(window.location);
    url.searchParams.set('sort', sortValue);
    window.location = url.toString();
}

function increaseQuantity(productId) {
    const input = document.getElementById(`quantity_${productId}`);
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity(productId) {
    const input = document.getElementById(`quantity_${productId}`);
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

// Actions produits
async function addToCart(productId) {
    const quantity = document.getElementById(`quantity_${productId}`).value;
    
    try {
        const response = await fetch(`/api/products/${productId}/add-to-cart`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: parseInt(quantity) })
        });

        const data = await response.json();
        
        if (data.success) {
            // Mettre à jour le compteur du panier dans l'en-tête
            updateCartCount(data.cart_count);
            
            // Afficher message de succès
            showNotification('Produit ajouté au panier !', 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
    }
}

function buyNow(productId) {
    const quantity = document.getElementById(`quantity_${productId}`).value;
    
    // Créer un formulaire pour envoyer les données
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/products/${productId}/buy-now`;
    
    // Token CSRF
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    // Quantité
    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = quantity;
    form.appendChild(quantityInput);
    
    document.body.appendChild(form);
    form.submit();
}

async function toggleLike(productId) {
    try {
        const response = await fetch(`/api/products/${productId}/toggle-like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            const btn = document.getElementById(`like_btn_${productId}`);
            const icon = btn.querySelector('svg');
            
            if (data.is_liked) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
                icon.setAttribute('fill', 'currentColor');
            } else {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
            }
        } else {
            showNotification(data.message || 'Erreur', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'action', 'error');
    }
}

async function toggleWishlist(productId) {
    try {
        const response = await fetch(`/api/products/${productId}/toggle-wishlist`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();
        
        if (data.success) {
            const btn = document.getElementById(`wishlist_btn_${productId}`);
            const icon = btn.querySelector('svg');
            
            if (data.is_in_wishlist) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-blue-500');
                icon.setAttribute('fill', 'currentColor');
            } else {
                icon.classList.remove('text-blue-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'action', 'error');
    }
}

// Fonctions utilitaires
function updateCartCount(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'inline' : 'none';
    }
}

function showNotification(message, type = 'info') {
    // Créer une notification toast
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection
