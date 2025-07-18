@extends('layouts.app')

@section('title', $product->name . ' - Location - FarmShop')

@push('styles')
<style>
    .product-image {
        transition: transform 0.3s ease;
    }
    .product-image:hover {
        transform: scale(1.05);
    }
    .rental-calculator {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .constraint-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="rentalDetailPage">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-3">
            <nav class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-purple-600 transition-colors">🏠 Accueil</a>
                <span>›</span>
                <a href="{{ route('rentals.index') }}" class="hover:text-purple-600 transition-colors">🚜 Locations</a>
                @if($product->rentalCategory)
                    <span>›</span>
                    <a href="{{ route('rentals.index', ['rental_category' => $product->rentalCategory->id]) }}" 
                       class="hover:text-purple-600 transition-colors">{{ $product->rentalCategory->name }}</a>
                @endif
                <span>›</span>
                <span class="text-gray-900 font-medium">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <!-- Images du produit -->
            <div class="space-y-4">
                <!-- Image principale -->
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                             alt="{{ $product->image_alt ?? $product->name }}"
                             class="w-full h-full object-cover product-image">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <span class="text-6xl">📦</span>
                        </div>
                    @endif
                </div>

                <!-- Images secondaires -->
                @if($product->images && count($product->images) > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <div class="aspect-square bg-gray-200 rounded-md overflow-hidden cursor-pointer hover:opacity-80 transition-opacity">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Retour -->
                <div class="flex space-x-4">
                    <a href="{{ route('rentals.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                        ← Retour aux locations
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors">
                        🛒 Voir les achats
                    </a>
                </div>
            </div>

            <!-- Informations du produit -->
            <div class="space-y-6">
                
                <!-- En-tête -->
                <div>
                    @if($product->rentalCategory)
                        <div class="mb-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                {{ $product->rentalCategory->name }}
                            </span>
                        </div>
                    @endif
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <!-- Compteur de likes (visible par tous) -->
                    <div class="flex items-center space-x-2 text-lg text-gray-600 mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span id="likes_count_{{ $product->slug }}" class="font-semibold">{{ $product->getLikesCount() }}</span>
                        <span>{{ $product->getLikesCount() === 1 ? 'personne aime ce produit' : 'personnes aiment ce produit' }}</span>
                    </div>
                    
                    <!-- Actions rapides (Like et Wishlist) -->
                    @auth
                        <div class="flex items-center space-x-3 mb-4">
                            <button id="like_btn_{{ $product->slug }}" 
                                    onclick="toggleLike('{{ $product->slug }}')"
                                    class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 {{ $product->isLikedByUser() ? 'text-red-500' : 'text-gray-400' }}" 
                                     fill="{{ $product->isLikedByUser() ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ $product->isLikedByUser() ? 'J\'aime' : 'Aimer' }}</span>
                            </button>

                            <button id="wishlist_btn_{{ $product->slug }}" 
                                    onclick="toggleWishlist('{{ $product->slug }}')"
                                    class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 {{ $product->isInUserWishlist() ? 'text-blue-500' : 'text-gray-400' }}" 
                                     fill="{{ $product->isInUserWishlist() ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ $product->isInUserWishlist() ? 'Dans ma wishlist' : 'Ajouter à ma wishlist' }}</span>
                            </button>
                        </div>
                    @endauth
                    
                    @if($product->short_description)
                        <p class="text-lg text-gray-600">{{ $product->short_description }}</p>
                    @endif

                    <!-- État du stock -->
                    <div class="mt-4">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-medium text-gray-700">Disponibilité :</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                       {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : 
                                          ($product->quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->quantity > 0 ? $product->quantity . ' en stock' : 'Rupture de stock' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Prix et contraintes -->
                <div class="bg-white p-6 rounded-lg shadow-md space-y-4">
                    <!-- Prix -->
                    <div class="text-center border-b pb-4">
                        <div class="text-4xl font-bold text-purple-600 mb-1">
                            {{ number_format($product->rental_price_per_day, 2) }}€
                        </div>
                        <div class="text-lg text-gray-600">par jour</div>
                        
                        @if($product->deposit_amount > 0)
                            <div class="mt-2 text-amber-600">
                                <span class="text-sm">Caution requise :</span>
                                <span class="font-semibold">{{ number_format($product->deposit_amount, 2) }}€</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contraintes de location -->
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900">📋 Conditions de location</h3>
                        
                        <div class="space-y-2">
                            @if($product->min_rental_days || $product->max_rental_days)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Durée de location :</span>
                                    <span class="constraint-badge">
                                        @if($product->min_rental_days && $product->max_rental_days)
                                            {{ $product->min_rental_days }} - {{ $product->max_rental_days }} jours
                                        @elseif($product->min_rental_days)
                                            Minimum {{ $product->min_rental_days }} jour(s)
                                        @elseif($product->max_rental_days)
                                            Maximum {{ $product->max_rental_days }} jour(s)
                                        @endif
                                    </span>
                                </div>
                            @endif
                            
                            @if($product->advance_notice_days)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Préavis requis :</span>
                                    <span class="constraint-badge">{{ $product->advance_notice_days }} jour(s)</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @auth
                        @if($product->quantity > 0)
                            <!-- Sélecteur de quantité -->
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700">Quantité :</label>
                                <div class="flex items-center space-x-3">
                                    <button type="button" 
                                            x-on:click="decreaseQuantity()" 
                                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                                        -
                                    </button>
                                    <input type="number" 
                                           x-model="quantity" 
                                           min="1" 
                                           max="{{ $product->quantity }}" 
                                           class="w-16 text-center border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <button type="button" 
                                            x-on:click="increaseQuantity()" 
                                            class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="space-y-3">
                                <button x-on:click="addToCartLocation()" 
                                        class="w-full px-6 py-3 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors font-medium">
                                    ➕ Ajouter au panier de location
                                </button>
                                
                                <button x-on:click="showRentalModal = true" 
                                        class="w-full px-6 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors font-medium">
                                    🏠 Louer maintenant
                                </button>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-red-600 font-medium">❌ Produit indisponible</p>
                                <p class="text-sm text-gray-500 mt-1">Ce produit n'est actuellement pas en stock</p>
                            </div>
                        @endif
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" 
                               class="block w-full px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium text-center">
                                Se connecter pour louer
                            </a>
                            <p class="text-sm text-gray-500 text-center">
                                Vous devez être connecté pour pouvoir louer ce produit
                            </p>
                        </div>
                    @endauth
                </div>

                @auth
                <!-- Calculateur de location -->
                <div class="rental-calculator text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">💰 Calculateur de location</h3>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-purple-100 mb-1">Date de début</label>
                            <input type="date" 
                                   x-model="startDate"
                                   :min="minDate"
                                   x-on:change="calculateCost"
                                   class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-md text-white placeholder-purple-200 focus:ring-2 focus:ring-white/50 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-purple-100 mb-1">Date de fin</label>
                            <input type="date" 
                                   x-model="endDate"
                                   :min="startDate"
                                   x-on:change="calculateCost"
                                   class="w-full px-3 py-2 bg-white/20 border border-white/30 rounded-md text-white placeholder-purple-200 focus:ring-2 focus:ring-white/50 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Résultats -->
                    <div x-show="calculation.show" class="bg-white/20 rounded-md p-4">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Durée :</span>
                                <span x-text="calculation.days + ' jour(s)'" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Sous-total :</span>
                                <span x-text="calculation.subtotal.toFixed(2) + '€'" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>TVA (20%) :</span>
                                <span x-text="calculation.tax.toFixed(2) + '€'" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Caution :</span>
                                <span x-text="calculation.deposit.toFixed(2) + '€'" class="font-medium text-amber-200"></span>
                            </div>
                            <div class="flex justify-between border-t border-white/30 pt-2 mt-2">
                                <span class="font-semibold">Total à payer :</span>
                                <span x-text="calculation.total.toFixed(2) + '€'" class="font-bold text-xl"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Messages d'erreur -->
                    <div x-show="calculationError" class="bg-red-100 border border-red-300 text-red-800 rounded-md p-3 mt-4">
                        <p x-text="calculationError" class="text-sm"></p>
                    </div>
                </div>
                @endauth
            </div>
        </div>

        <!-- Description détaillée -->
        @if($product->description)
            <div class="mt-12 bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Description détaillée</h2>
                <div class="prose max-w-none text-gray-600">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        @endif

        <!-- Produits similaires -->
        @if($similarProducts && $similarProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">🔄 Produits similaires</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($similarProducts as $similar)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="aspect-square bg-gray-200">
                                @if($similar->main_image)
                                    <img src="{{ asset('storage/' . $similar->main_image) }}" 
                                         alt="{{ $similar->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <span class="text-3xl">📦</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $similar->name }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-purple-600 font-bold">{{ number_format($similar->rental_price_per_day, 2) }}€/j</span>
                                    <a href="{{ route('rentals.show', $similar) }}" 
                                       class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                        Voir →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
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
                <h3 class="text-lg font-semibold text-gray-900">📅 Confirmer la location</h3>
                <button x-on:click="showRentalModal = false" class="text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Fermer</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Récapitulatif produit -->
            <div class="bg-gray-50 p-4 rounded-md mb-4">
                <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                <p class="text-sm text-gray-600">
                    Prix : <span class="font-semibold text-purple-600">{{ number_format($product->rental_price_per_day, 2) }}€/jour</span>
                </p>
                @if($product->deposit_amount > 0)
                    <p class="text-sm text-gray-600">
                        Caution : <span class="font-semibold text-amber-600">{{ number_format($product->deposit_amount, 2) }}€</span>
                    </p>
                @endif
                <p class="text-sm text-gray-600">
                    Quantité : <span x-text="quantity" class="font-semibold"></span>
                </p>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" 
                           x-model="startDate"
                           :min="minDate"
                           x-on:change="calculateCost"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" 
                           x-model="endDate"
                           :min="startDate"
                           x-on:change="calculateCost"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>

            <!-- Calcul -->
            <div x-show="calculation.show" class="bg-blue-50 p-4 rounded-md mb-4">
                <h5 class="font-medium text-blue-900 mb-2">💰 Récapitulatif</h5>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span>Durée :</span>
                        <span x-text="calculation.days + ' jour(s)'" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sous-total :</span>
                        <span x-text="calculation.subtotal.toFixed(2) + '€'" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>TVA (20%) :</span>
                        <span x-text="calculation.tax.toFixed(2) + '€'" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Caution :</span>
                        <span x-text="calculation.deposit.toFixed(2) + '€'" class="font-medium text-amber-600"></span>
                    </div>
                    <div class="flex justify-between border-t pt-1 mt-2">
                        <span class="font-semibold">Total à payer :</span>
                        <span x-text="calculation.total.toFixed(2) + '€'" class="font-bold text-purple-600"></span>
                    </div>
                </div>
            </div>

            <!-- Messages d'erreur -->
            <div x-show="rentalError" class="bg-red-50 border border-red-200 rounded-md p-3 mb-4">
                <div class="flex">
                    <div class="text-red-400">⚠️</div>
                    <div class="ml-3">
                        <p x-text="rentalError" class="text-sm text-red-800"></p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3">
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
    @endauth
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rentalDetailPage', () => ({
        // État
        quantity: 1,
        showRentalModal: false,
        startDate: '',
        endDate: '',
        
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
        
        calculationError: '',
        rentalError: '',
        minDate: new Date().toISOString().split('T')[0],
        
        // Initialisation
        init() {
            // Définir dates par défaut
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            this.startDate = tomorrow.toISOString().split('T')[0];
            
            const weekLater = new Date();
            weekLater.setDate(weekLater.getDate() + 8);
            this.endDate = weekLater.toISOString().split('T')[0];
            
            // Calculer le coût initial
            this.calculateCost();
        },
        
        // Gestion des quantités
        increaseQuantity() {
            if (this.quantity < {{ $product->quantity }}) {
                this.quantity++;
                this.calculateCost();
            }
        },
        
        decreaseQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
                this.calculateCost();
            }
        },
        
        // Ajouter au panier de location
        addToCartLocation() {
            this.makeApiCall(`/api/cart-location/products/{{ $product->id }}`, {
                method: 'POST',
                body: JSON.stringify({
                    quantity: this.quantity,
                    start_date: this.startDate,
                    end_date: this.endDate
                })
            }).then(data => {
                if (data.success) {
                    this.showNotification('✅ Produit ajouté au panier de location !', 'success');
                } else {
                    this.showNotification('❌ ' + data.message, 'error');
                }
            });
        },
        
        // Calculer le coût
        calculateCost() {
            if (!this.startDate || !this.endDate) {
                this.calculation.show = false;
                this.calculationError = '';
                return;
            }
            
            this.makeApiCall(`/api/rentals/{{ $product->id }}/calculate-cost`, {
                method: 'POST',
                body: JSON.stringify({
                    start_date: this.startDate,
                    end_date: this.endDate,
                    quantity: this.quantity
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
                    this.calculationError = '';
                    this.rentalError = '';
                } else {
                    this.calculation.show = false;
                    this.calculation.valid = false;
                    this.calculationError = data.message;
                }
            }).catch(() => {
                this.calculation.show = false;
                this.calculation.valid = false;
                this.calculationError = 'Erreur lors du calcul';
            });
        },
        
        // Confirmer la location
        confirmRental() {
            this.makeApiCall(`/api/cart-location/products/{{ $product->id }}`, {
                method: 'POST',
                body: JSON.stringify({
                    quantity: this.quantity,
                    start_date: this.startDate,
                    end_date: this.endDate
                })
            }).then(data => {
                if (data.success) {
                    this.showNotification('✅ Produit ajouté au panier de location !', 'success');
                    this.showRentalModal = false;
                    // Rediriger vers le panier ou la page de commande
                    setTimeout(() => {
                        window.location.href = '/cart-location';
                    }, 1500);
                } else {
                    this.rentalError = data.message;
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
async function toggleLike(productSlug) {
    try {
        const response = await fetch(`/web/likes/products/${productSlug}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        // Vérifier si la réponse est ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            const btn = document.getElementById(`like_btn_${productSlug}`);
            const icon = btn.querySelector('svg');
            const text = btn.querySelector('span');
            const likesCountElement = document.getElementById(`likes_count_${productSlug}`);
            
            // Compatibilité avec les deux formats de réponse
            const isLiked = data.data.is_liked || data.data.liked;
            const likesCount = data.data.likes_count || 0;
            
            if (isLiked) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
                icon.setAttribute('fill', 'currentColor');
                text.textContent = 'J\'aime';
            } else {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
                text.textContent = 'Aimer';
            }
            
            // Mettre à jour le compteur
            if (likesCountElement) {
                likesCountElement.textContent = likesCount;
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'action', 'error');
        }
    } catch (error) {
        console.error('Erreur détaillée:', error);
        
        // Afficher une erreur plus spécifique
        if (error.message.includes('404')) {
            showNotification('Produit non trouvé', 'error');
        } else if (error.message.includes('401')) {
            showNotification('Vous devez être connecté pour effectuer cette action', 'error');
        } else if (error.message.includes('500')) {
            showNotification('Erreur serveur. Veuillez réessayer plus tard.', 'error');
        } else {
            showNotification('Erreur lors de l\'action. Vérifiez votre connexion.', 'error');
        }
    }
}

async function toggleWishlist(productSlug) {
    try {
        const response = await fetch(`/web/wishlist/products/${productSlug}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        // Vérifier si la réponse est ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success) {
            const btn = document.getElementById(`wishlist_btn_${productSlug}`);
            const icon = btn.querySelector('svg');
            const text = btn.querySelector('span');
            
            // Compatibilité avec les deux formats de réponse
            const isWishlisted = data.data.is_wishlisted || data.data.in_wishlist;
            
            if (isWishlisted) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-blue-500');
                icon.setAttribute('fill', 'currentColor');
                text.textContent = 'Dans ma wishlist';
            } else {
                icon.classList.remove('text-blue-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
                text.textContent = 'Ajouter à ma wishlist';
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'action', 'error');
        }
    } catch (error) {
        console.error('Erreur détaillée:', error);
        
        // Afficher une erreur plus spécifique
        if (error.message.includes('404')) {
            showNotification('Produit non trouvé', 'error');
        } else if (error.message.includes('401')) {
            showNotification('Vous devez être connecté pour effectuer cette action', 'error');
        } else if (error.message.includes('500')) {
            showNotification('Erreur serveur. Veuillez réessayer plus tard.', 'error');
        } else {
            showNotification('Erreur lors de l\'action. Vérifiez votre connexion.', 'error');
        }
    }
}

function showNotification(message, type = 'info') {
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
</script>
@endpush
