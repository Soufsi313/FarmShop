@extends('layouts.app')

@section('title', __('app.wishlist.title') . ' - FarmShop')

@push('styles')
<style>
    .wishlist-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .wishlist-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .remove-button {
        transition: all 0.2s ease;
    }
    .remove-button:hover {
        transform: scale(1.1);
    }
    .add-to-cart-button {
        transition: all 0.3s ease;
    }
    .add-to-cart-button:hover {
        transform: translateY(-1px);
    }
    .heart-icon {
        transition: all 0.2s ease;
    }
    .heart-icon:hover {
        transform: scale(1.2);
    }
    .product-image {
        transition: transform 0.3s ease;
    }
    .product-image:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="wishlistPage">
    
    <!-- En-tête de la page -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        {{ __('app.wishlist.title') }}
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $wishlist->total() }} {{ $wishlist->total() > 1 ? __('app.wishlist.products_plural') : __('app.wishlist.product_singular') }}</p>
                </div>
                
                @if($wishlist->count() > 0)
                <div class="flex space-x-3">
                    <button @click="clearWishlist" 
                            class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg transition-colors font-medium">
                        {{ __('app.wishlist.clear_list') }}
                    </button>
                    <button @click="addAllToCart" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                        {{ __('app.wishlist.add_all_to_cart') }}
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container mx-auto px-4 py-8">
        @if($wishlist->count() > 0)
            <!-- Grille des produits -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlist as $wishlistItem)
                    @php $product = $wishlistItem->product @endphp
                    @if($product)
                        <div class="wishlist-item bg-white rounded-lg shadow-md overflow-hidden relative">
                            <!-- Bouton de suppression -->
                            <button @click="removeFromWishlist('{{ $product->slug }}')"
                                    class="remove-button absolute top-2 right-2 z-10 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <!-- Image du produit -->
                            <div class="aspect-square bg-gray-200 overflow-hidden">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="product-image w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <!-- Informations du produit -->
                            <div class="p-4">
                                <!-- Catégorie -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $product->category->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ __('app.wishlist.added_on') }} {{ $wishlistItem->created_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                <!-- Nom du produit -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-green-600 transition-colors">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ __('app.product_names.' . $product->slug) ?: $product->name }}
                                    </a>
                                </h3>

                                <!-- Description courte -->
                                @if($product->short_description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                        {{ __('app.product_descriptions.' . $product->slug) ?: $product->short_description }}
                                    </p>
                                @endif

                                <!-- Prix et stock -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex flex-col">
                                        @if($product->type === 'rental' || $product->type === 'both')
                                            <span class="text-lg font-bold text-green-600">
                                                {{ number_format($product->rental_price_per_day, 2) }} €/jour
                                            </span>
                                            @if($product->type === 'both')
                                                <span class="text-sm text-gray-500">
                                                    ou {{ number_format($product->price, 2) }} € à l'achat
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-lg font-bold text-green-600">
                                                {{ number_format($product->price, 2) }} €
                                            </span>
                                        @endif
                                        <span class="text-sm text-gray-500 mt-1">
                                            Stock: {{ $product->quantity }} {{ $product->unit_symbol ?? 'unité' }}{{ $product->quantity > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Statut stock -->
                                    @if($product->quantity > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            En stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rupture
                                        </span>
                                    @endif
                                </div>

                                @if($product->quantity > 0)
                                    <!-- Contrôles de quantité -->
                                    <div class="flex items-center space-x-2 mb-3">
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
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex flex-col space-y-2">
                                    @if($product->quantity > 0)
                                        @if($product->type === 'rental')
                                            <button @click="addToRentalCart({{ $product->id }})"
                                                    class="add-to-cart-button w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                                {{ __('app.wishlist.add_to_cart') }}
                                            </button>
                                        @elseif($product->type === 'sale')
                                            <div class="flex space-x-2">
                                                <button onclick="addToCartFromWishlist({{ $product->id }})" 
                                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                                    {{ __('app.wishlist.add_to_cart') }}
                                                </button>
                                                <button onclick="buyNowFromWishlist({{ $product->id }})" 
                                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                                    {{ __('app.wishlist.buy_now') }}
                                                </button>
                                            </div>
                                        @elseif($product->type === 'both')
                                            <div class="flex space-x-2">
                                                <button onclick="addToCartFromWishlist({{ $product->id }})" 
                                                        class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                                    {{ __('app.wishlist.add_to_cart') }}
                                                </button>
                                                <button onclick="buyNowFromWishlist({{ $product->id }})" 
                                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                                    {{ __('app.wishlist.buy_now') }}
                                                </button>
                                            </div>
                                            <button @click="addToRentalCart({{ $product->id }})"
                                                    class="add-to-cart-button w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-all">
                                                {{ __('app.wishlist.rent_now') }}
                                            </button>
                                        @endif
                                    @else
                                        <button disabled 
                                                class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-sm font-medium cursor-not-allowed">
                                            {{ __('app.wishlist.product_unavailable') }}
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-medium text-center transition-colors">
                                        {{ __("app.content.view_details") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            @if($wishlist->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $wishlist->links() }}
                </div>
            @endif

        @else
            <!-- État vide -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        {{ __('app.wishlist.empty_title') }}
                    </h3>
                    <p class="text-gray-600 mb-6">
                        {{ __('app.wishlist.empty_description') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            {{ __('app.products.view_all_products') }}
                        </a>
                        <a href="{{ route('rentals.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('app.rentals.available_rentals') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast pour les notifications -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 transform translate-y-2" 
         x-transition:enter-end="opacity-100 transform translate-y-0" 
         x-transition:leave="transition ease-in duration-300" 
         x-transition:leave-start="opacity-100 transform translate-y-0" 
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 z-50 max-w-sm">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg x-show="toast.type === 'success'" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900" x-text="toast.message"></p>
                </div>
                <div class="ml-auto">
                    <button @click="toast.show = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('wishlistPage', () => ({
        toast: {
            show: false,
            message: '',
            type: 'success'
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            
            setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },

        async removeFromWishlist(productSlug) {
            if (!confirm('Êtes-vous sûr de vouloir retirer ce produit de votre liste de souhaits ?')) {
                return;
            }

            try {
                const response = await fetch(`/web/wishlist/products/${productSlug}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    // Recharger la page pour mettre à jour la liste
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showToast(data.message, 'error');
                }
            } catch (error) {
                this.showToast('Erreur lors de la suppression', 'error');
            }
        },

        async clearWishlist() {
            if (!confirm('Êtes-vous sûr de vouloir vider complètement votre liste de souhaits ?')) {
                return;
            }

            try {
                const response = await fetch('/web/wishlist/clear', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showToast(data.message, 'error');
                }
            } catch (error) {
                this.showToast('Erreur lors du vidage de la liste', 'error');
            }
        },

        async addToCart(productId) {
            try {
                const response = await fetch(`/cart/add-product/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: 1
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast('Produit ajouté au panier', 'success');
                } else {
                    this.showToast(data.message || 'Erreur lors de l\'ajout au panier', 'error');
                }
            } catch (error) {
                console.error('Erreur addToCart:', error);
                this.showToast('Erreur lors de l\'ajout au panier', 'error');
            }
        },

        async addToRentalCart(productId) {
            try {
                const response = await fetch(`/api/cart-location/products/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: 1,
                        start_date: new Date().toISOString().split('T')[0],
                        end_date: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.showToast('Produit ajouté au panier de location', 'success');
                } else {
                    this.showToast(data.message, 'error');
                }
            } catch (error) {
                this.showToast('Erreur lors de l\'ajout au panier de location', 'error');
            }
        },

        async addAllToCart() {
            if (!confirm('Ajouter tous les produits disponibles au panier ?')) {
                return;
            }

            // Cette fonctionnalité nécessiterait une route API spécifique
            this.showToast('Fonctionnalité en développement', 'error');
        }
    }))
})

// Fonctions utilitaires pour les contrôles de quantité
window.increaseQuantity = function(productId) {
    const input = document.getElementById(`quantity_${productId}`);
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        input.value = currentValue + 1;
    }
}

window.decreaseQuantity = function(productId) {
    const input = document.getElementById(`quantity_${productId}`);
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

// Fonctions d'ajout au panier avec quantité depuis la wishlist
window.addToCartFromWishlist = async function(productId) {
    const quantity = document.getElementById(`quantity_${productId}`).value;
    
    try {
        const response = await fetch(`/cart/add-product/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ quantity: parseInt(quantity) })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            // Mettre à jour le compteur du panier dans l'en-tête
            updateCartCount(data.cart_count);
            
            // Afficher message de succès
            showNotification('Produit ajouté au panier avec succès', 'success');
        } else {
            showNotification(data.message || '{{ __('app.wishlist.add_to_cart_error') }}', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('{{ __('app.wishlist.add_to_cart_error') }}', 'error');
    }
}

window.buyNowFromWishlist = async function(productId) {
    try {
        // Ajouter le produit au panier avec quantité 1
        const response = await fetch(`/cart/add-product/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ quantity: 1 })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        
        if (data.success) {
            // Mettre à jour le compteur du panier dans l'en-tête
            updateCartCount(data.cart_count);
            
            // Rediriger vers le panier
            window.location.href = '/cart';
        } else {
            showNotification(data.message || '{{ __('app.wishlist.add_to_cart_error') }}', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showNotification('{{ __('app.wishlist.add_to_cart_error') }}', 'error');
    }
}

// Fonctions utilitaires
window.updateCartCount = function(count) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'inline' : 'none';
    }
}

window.showNotification = function(message, type = 'info') {
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
@endsection
