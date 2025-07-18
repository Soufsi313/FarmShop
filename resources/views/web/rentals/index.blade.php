@extends('layouts.app')

@section('title', 'Nos Locations - FarmShop')

@push('styles')
<style>
    .rental-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .rental-card:hover {
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
        background: #7c3aed;
        cursor: pointer;
    }
    .price-range-slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #7c3aed;
        cursor: pointer;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="rentalPage">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">🚜 Nos Locations</h1>
                <p class="text-xl text-purple-100 max-w-2xl mx-auto">
                    Découvrez notre gamme complète d'équipements agricoles à louer
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
                    
                    <form id="filterForm" method="GET" action="{{ route('rentals.index') }}">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>

                        <!-- Catégories de location -->
                        <div class="mb-6">
                            <label for="rental_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie
                            </label>
                            <select id="rental_category" 
                                    name="rental_category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="">Toutes les catégories</option>
                                @foreach($rentalCategories as $category)
                                    <option value="{{ $category->id }}" {{ request('rental_category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prix par jour -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Prix par jour
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}"
                                       placeholder="Min €"
                                       step="0.01"
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}"
                                       placeholder="Max €"
                                       step="0.01"
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <!-- Tri -->
                        <div class="mb-6">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                Trier par
                            </label>
                            <select id="sort" 
                                    name="sort" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom A-Z</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularité</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                                🔍 Filtrer
                            </button>
                            <a href="{{ route('rentals.index') }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                ↻
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Contenu principal -->
            <main class="lg:w-3/4">
                <!-- Barre d'info et panier -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-gray-600">
                            <strong>{{ $products->total() }}</strong> produit(s) trouvé(s)
                            @if(request()->hasAny(['search', 'rental_category', 'min_price', 'max_price']))
                                <span class="text-sm">(filtrés)</span>
                            @endif
                        </p>
                    </div>

                    @auth
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-800 transition-colors">
                                🛒 Produits d'achat
                            </a>
                            <button id="cart-location-btn" 
                                    class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors"
                                    x-on:click="showCartLocation = true">
                                📋 Panier location (<span x-text="cartLocationCount">0</span>)
                            </button>
                        </div>
                    @endauth
                </div>

                <!-- Grille des produits -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="rental-card bg-white rounded-lg shadow-md overflow-hidden">
                                <!-- Image cliquable -->
                                <a href="{{ route('rentals.show', $product) }}" class="block">
                                    <div class="relative h-48 bg-gray-200">
                                        @if($product->main_image)
                                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                                                 alt="{{ $product->image_alt ?? $product->name }}"
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <span class="text-4xl">📦</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Badge catégorie -->
                                        @if($product->rentalCategory)
                                            <div class="absolute top-2 left-2 bg-purple-600 text-white px-2 py-1 rounded-md text-xs font-medium">
                                                {{ $product->rentalCategory->name }}
                                            </div>
                                        @endif

                                        <!-- Badge stock -->
                                        <div class="absolute top-2 right-2 px-2 py-1 rounded-md text-xs font-medium
                                                   {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : 
                                                      ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            Stock: {{ $product->quantity }}
                                        </div>

                                        <!-- Overlay "Voir détails" -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                                            <span class="text-white font-semibold opacity-0 hover:opacity-100 transition-opacity duration-200">
                                                👁️ Voir détails
                                            </span>
                                        </div>
                                    </div>
                                </a>

                                <!-- Contenu -->
                                <div class="p-4">
                                    <a href="{{ route('rentals.show', $product) }}" class="block">
                                        <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2 hover:text-purple-600 transition-colors">{{ $product->name }}</h3>
                                    </a>
                                    
                                    @if($product->short_description)
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->short_description }}</p>
                                    @endif

                                    <!-- Prix -->
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl font-bold text-purple-600">
                                                {{ number_format($product->rental_price_per_day, 2) }}€
                                            </span>
                                            <span class="text-sm text-gray-500">/ jour</span>
                                        </div>
                                        @if($product->deposit_amount > 0)
                                            <p class="text-sm text-amber-600">
                                                Caution : {{ number_format($product->deposit_amount, 2) }}€
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Contraintes -->
                                    <div class="text-xs text-gray-500 mb-3 space-y-1">
                                        @if($product->min_rental_days && $product->max_rental_days)
                                            <p>📅 {{ $product->min_rental_days }}-{{ $product->max_rental_days }} jours</p>
                                        @endif
                                    </div>

                                    <!-- Compteur de likes (visible par tous) -->
                                    <div class="flex items-center space-x-1 text-sm text-gray-500 mb-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span id="likes_count_{{ $product->slug }}">{{ $product->getLikesCount() }}</span>
                                        <span>{{ $product->getLikesCount() === 1 ? 'like' : 'likes' }}</span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="space-y-3">
                                        @auth
                                            <!-- Boutons Like et Wishlist -->
                                            <div class="flex justify-between">
                                                <button id="like_btn_{{ $product->slug }}" 
                                                        onclick="toggleLike('{{ $product->slug }}')" 
                                                        class="flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition-colors {{ $product->isLikedByUser() ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-500' }}">
                                                    <span class="text-lg">{{ $product->isLikedByUser() ? '❤️' : '🤍' }}</span>
                                                    <span>{{ $product->isLikedByUser() ? 'Aimé' : 'Aimer' }}</span>
                                                </button>
                                                
                                                <button id="wishlist_btn_{{ $product->slug }}" 
                                                        onclick="toggleWishlist('{{ $product->slug }}')" 
                                                        class="flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition-colors {{ $product->isInUserWishlist() ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600 hover:bg-yellow-50 hover:text-yellow-500' }}">
                                                    <span class="text-lg">{{ $product->isInUserWishlist() ? '⭐' : '☆' }}</span>
                                                    <span>{{ $product->isInUserWishlist() ? 'En favoris' : 'Favoris' }}</span>
                                                </button>
                                            </div>
                                        @endauth

                                        <!-- Boutons de location -->
                                        <div class="flex space-x-2">
                                            @auth
                                                <!-- Sélecteur de quantité -->
                                            <div class="flex items-center space-x-1 border rounded-md">
                                                <button type="button" 
                                                        class="quantity-btn minus px-2 py-1 text-gray-500 hover:text-gray-700" 
                                                        x-on:click="decreaseQuantity({{ $product->id }})">-</button>
                                                <input type="number" 
                                                       x-model="quantities[{{ $product->id }}]" 
                                                       min="1" 
                                                       max="{{ $product->quantity }}" 
                                                       class="w-12 text-center border-0 focus:ring-0">
                                                <button type="button" 
                                                        class="quantity-btn plus px-2 py-1 text-gray-500 hover:text-gray-700" 
                                                        x-on:click="increaseQuantity({{ $product->id }}, {{ $product->quantity }})">+</button>
                                            </div>

                                            <button class="flex-1 add-to-cart-location px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors text-sm"
                                                    x-on:click="addToCartLocation({{ $product->id }})">
                                                ➕ Panier
                                            </button>

                                            <button class="rent-now-btn px-3 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors text-sm font-medium"
                                                    x-on:click="rentNow({{ json_encode($product) }})">
                                                🏠 Louer
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" 
                                               class="w-full px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center text-sm">
                                                Se connecter pour louer
                                            </a>
                                        @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <!-- Aucun résultat -->
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun produit trouvé</h3>
                        <p class="text-gray-600 mb-4">Essayez de modifier vos critères de recherche</p>
                        <a href="{{ route('rentals.index') }}" 
                           class="inline-block px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            Voir tous les produits
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>

    @auth
    <!-- Modal de location -->
    <div x-show="showRentalModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
         style="display: none;">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto"
             x-on:click.away="showRentalModal = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">📅 Choisir les dates de location</h3>
                <button x-on:click="showRentalModal = false" class="text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Informations produit -->
                <div class="bg-gray-50 p-3 rounded-md">
                    <h4 x-text="selectedProduct.name" class="font-medium text-gray-900"></h4>
                    <p class="text-sm text-gray-600">
                        Prix: <span x-text="selectedProduct.rental_price_per_day" class="font-semibold text-purple-600"></span>€/jour
                    </p>
                    <p class="text-sm text-gray-600">
                        Caution: <span x-text="selectedProduct.deposit_amount" class="font-semibold text-amber-600"></span>€
                    </p>
                    <p class="text-sm text-gray-600">
                        Durée: <span x-text="selectedProduct.min_rental_days + '-' + selectedProduct.max_rental_days"></span> jours
                    </p>
                </div>

                <!-- Quantité -->
                <div>
                    <label for="modal-quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                    <input type="number" 
                           x-model="rentalForm.quantity" 
                           min="1" 
                           :max="selectedProduct.quantity"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="modal-start-date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                        <input type="date" 
                               x-model="rentalForm.startDate"
                               :min="minDate"
                               x-on:change="calculateCost"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="modal-end-date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                        <input type="date" 
                               x-model="rentalForm.endDate"
                               :min="rentalForm.startDate"
                               x-on:change="calculateCost"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Calcul automatique -->
                <div x-show="calculation.show" class="bg-blue-50 p-4 rounded-md">
                    <h5 class="font-medium text-blue-900 mb-2">💰 Récapitulatif</h5>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Durée:</span>
                            <span x-text="calculation.days + ' jour(s)'" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sous-total:</span>
                            <span x-text="calculation.subtotal.toFixed(2) + '€'" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>TVA (20%):</span>
                            <span x-text="calculation.tax.toFixed(2) + '€'" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Caution:</span>
                            <span x-text="calculation.deposit.toFixed(2) + '€'" class="font-medium text-amber-600"></span>
                        </div>
                        <div class="flex justify-between border-t pt-1 mt-2">
                            <span class="font-semibold">Total à payer:</span>
                            <span x-text="calculation.total.toFixed(2) + '€'" class="font-bold text-purple-600"></span>
                        </div>
                    </div>
                </div>

                <!-- Messages d'erreur -->
                <div x-show="rentalError" class="bg-red-50 border border-red-200 rounded-md p-3">
                    <div class="flex">
                        <div class="text-red-400">⚠️</div>
                        <div class="ml-3">
                            <p x-text="rentalError" class="text-sm text-red-800"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3 mt-6">
                <button x-on:click="confirmRental" 
                        :disabled="!calculation.valid"
                        class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    🏠 Confirmer la location
                </button>
                <button x-on:click="showRentalModal = false" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <!-- Modal du panier de location -->
    <div x-show="showCartLocation" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
         style="display: none;">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
             x-on:click.away="showCartLocation = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">📋 Panier de location</h3>
                <button x-on:click="showCartLocation = false" class="text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div x-html="cartLocationContent">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
        </div>
    </div>
    @endauth
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rentalPage', () => ({
        // État
        showRentalModal: false,
        showCartLocation: false,
        selectedProduct: {},
        quantities: @json($products->pluck('id')->mapWithKeys(fn($id) => [$id => 1])),
        cartLocationCount: 0,
        cartLocationContent: '',
        
        // Formulaire de location
        rentalForm: {
            quantity: 1,
            startDate: '',
            endDate: ''
        },
        
        // Calcul
        calculation: {
            show: false,
            valid: false,
            days: 0,
            subtotal: 0,
            tax: 0,
            deposit: 0,
            total: 0
        },
        
        rentalError: '',
        minDate: new Date().toISOString().split('T')[0],
        
        // Initialisation
        init() {
            this.updateCartLocationCount();
            // Définir dates par défaut
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            this.rentalForm.startDate = tomorrow.toISOString().split('T')[0];
            
            const weekLater = new Date();
            weekLater.setDate(weekLater.getDate() + 8);
            this.rentalForm.endDate = weekLater.toISOString().split('T')[0];
        },
        
        // Gestion des quantités
        increaseQuantity(productId, maxStock) {
            if (this.quantities[productId] < maxStock) {
                this.quantities[productId]++;
            }
        },
        
        decreaseQuantity(productId) {
            if (this.quantities[productId] > 1) {
                this.quantities[productId]--;
            }
        },
        
        // Ajouter au panier de location
        addToCartLocation(productId) {
            const quantity = this.quantities[productId];
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const weekLater = new Date();
            weekLater.setDate(weekLater.getDate() + 8);
            
            this.makeApiCall(`/api/cart-location/products/${productId}`, {
                method: 'POST',
                body: JSON.stringify({
                    quantity: quantity,
                    start_date: tomorrow.toISOString().split('T')[0],
                    end_date: weekLater.toISOString().split('T')[0]
                })
            }).then(data => {
                if (data.success) {
                    this.showNotification('✅ Produit ajouté au panier de location !', 'success');
                    this.updateCartLocationCount();
                } else {
                    this.showNotification('❌ ' + data.message, 'error');
                }
            });
        },
        
        // Louer maintenant
        rentNow(product) {
            this.selectedProduct = product;
            this.rentalForm.quantity = this.quantities[product.id];
            this.showRentalModal = true;
            this.calculateCost();
        },
        
        // Calculer le coût
        calculateCost() {
            if (!this.rentalForm.startDate || !this.rentalForm.endDate || !this.selectedProduct.id) {
                this.calculation.show = false;
                return;
            }
            
            this.makeApiCall(`/api/rentals/${this.selectedProduct.id}/calculate-cost`, {
                method: 'POST',
                body: JSON.stringify({
                    start_date: this.rentalForm.startDate,
                    end_date: this.rentalForm.endDate,
                    quantity: this.rentalForm.quantity
                })
            }).then(data => {
                if (data.success) {
                    this.calculation = {
                        show: true,
                        valid: true,
                        days: data.data.rental_period.days,
                        subtotal: data.data.pricing.subtotal,
                        tax: data.data.pricing.tax_amount,
                        deposit: data.data.pricing.deposit_amount,
                        total: data.data.pricing.total
                    };
                    this.rentalError = '';
                } else {
                    this.calculation.show = false;
                    this.calculation.valid = false;
                    this.rentalError = data.message;
                }
            }).catch(() => {
                this.calculation.show = false;
                this.calculation.valid = false;
                this.rentalError = 'Erreur lors du calcul';
            });
        },
        
        // Confirmer la location
        confirmRental() {
            this.makeApiCall(`/api/cart-location/products/${this.selectedProduct.id}`, {
                method: 'POST',
                body: JSON.stringify({
                    quantity: this.rentalForm.quantity,
                    start_date: this.rentalForm.startDate,
                    end_date: this.rentalForm.endDate
                })
            }).then(data => {
                if (data.success) {
                    this.showNotification('✅ Produit ajouté au panier de location !', 'success');
                    this.updateCartLocationCount();
                    this.showRentalModal = false;
                } else {
                    this.rentalError = data.message;
                }
            });
        },
        
        // Mettre à jour le compteur du panier
        updateCartLocationCount() {
            this.makeApiCall('/api/cart-location/summary')
                .then(data => {
                    if (data.success) {
                        this.cartLocationCount = data.data.total_items || 0;
                    }
                });
        },
        
        // Utilitaires
        makeApiCall(url, options = {}) {
            return fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    ...options.headers
                },
                ...options
            }).then(response => response.json());
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
                'bg-blue-100 text-blue-800 border border-blue-200'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }));
});

// Fonctions Like et Wishlist
function toggleLike(productSlug) {
    fetch(`/web/likes/products/${productSlug}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.getElementById(`like_btn_${productSlug}`);
            const icon = button.querySelector('span:first-child');
            const text = button.querySelector('span:last-child');
            const likesCountElement = document.getElementById(`likes_count_${productSlug}`);
            
            const isLiked = data.is_liked || data.liked;
            const likesCount = data.data ? data.data.likes_count || 0 : 0;
            
            if (isLiked) {
                button.className = 'flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition-colors bg-red-100 text-red-600';
                icon.textContent = '❤️';
                text.textContent = 'Aimé';
            } else {
                button.className = 'flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition-colors bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-500';
                icon.textContent = '🤍';
                text.textContent = 'Aimer';
            }
            
            // Mettre à jour le compteur
            if (likesCountElement) {
                likesCountElement.textContent = likesCount;
            }
        } else {
            alert(data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

function toggleWishlist(productSlug) {
    fetch(`/web/wishlist/products/${productSlug}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.getElementById(`wishlist_btn_${productSlug}`);
            const icon = button.querySelector('span:first-child');
            const text = button.querySelector('span:last-child');
            
            if (data.in_wishlist || data.is_wishlisted) {
                button.className = 'flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition-colors bg-yellow-100 text-yellow-600';
                icon.textContent = '⭐';
                text.textContent = 'En favoris';
            } else {
                button.className = 'flex items-center space-x-1 px-3 py-1 rounded-full text-sm transition-colors bg-gray-100 text-gray-600 hover:bg-yellow-50 hover:text-yellow-500';
                icon.textContent = '☆';
                text.textContent = 'Favoris';
            }
        } else {
            alert(data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}
</script>
@endpush
