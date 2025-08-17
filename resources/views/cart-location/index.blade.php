@extends('layouts.app')

@section('title', __('app.cart_location.title') . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="cartLocationApp()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 101.414 1.414L9 4.414V17a1 1 0 102 0V4.414l7.293 7.293a1 1 0 001.414-1.414l-9-9z"></path>
                            </svg>
                            {{ __('app.cart_location.home') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('rentals.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                {{ __('app.cart_location.rentals') }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ __('app.cart_location.title') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="mt-4">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('app.cart_location.title') }}</h1>
                <p class="mt-2 text-sm text-gray-600">{{ __('app.cart_location.description') }}</p>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="isLoading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
        </div>

        <!-- Empty Cart -->
        <div x-show="!isLoading && cartItems.length === 0" class="text-center py-12">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l1.5 6m0 0H17M9 19h8"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('app.cart_location.empty_title') }}</h3>
                <p class="mt-2 text-gray-500">{{ __('app.cart_location.empty_description') }}</p>
                <div class="mt-6">
                    <a href="{{ route('rentals.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('app.cart_location.discover_rentals') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Cart Content -->
        <div x-show="!isLoading && cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items (Left Column) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Cart Header -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ __('app.cart_location.rental_items') }} (<span x-text="cartItems.length">0</span>)
                        </h2>
                        <button @click="clearCart()" 
                                x-show="cartItems.length > 0"
                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                            {{ __('app.cart_location.clear_cart') }}
                        </button>
                    </div>
                </div>

                <!-- Cart Items List -->
                <template x-for="item in cartItems" :key="item.id">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden">
                                    <img x-show="item.product.main_image" 
                                         :src="item.product.main_image ? '/storage/' + item.product.main_image : ''"
                                         :alt="getTranslatedProductName(item.product)"
                                         class="w-full h-full object-cover">
                                    <div x-show="!item.product.main_image" 
                                         class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900" x-text="getTranslatedProductName(item.product)"></h3>
                                        <p class="text-sm text-gray-500 mt-1" x-text="item.rental_category_name"></p>
                                        <p class="text-sm text-gray-600 mt-1" x-text="item.product.sku"></p>
                                        
                                        <!-- Rental Period -->
                                        <div class="mt-3 p-3 bg-purple-50 rounded-lg">
                                            <div class="flex items-center text-sm text-purple-800">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span x-text="`{{ __('app.cart_location.from') }} ${formatDate(item.start_date)} {{ __('app.cart_location.to') }} ${formatDate(item.end_date)} (${item.duration_days} {{ __('app.cart_location.day') }}${item.duration_days > 1 ? 's' : ''})`"></span>
                                            </div>
                                        </div>

                                        <!-- Notes if any -->
                                        <div x-show="item.notes" class="mt-2">
                                            <p class="text-sm text-gray-600">
                                                <strong>{{ __('app.cart_location.notes_label') }}:</strong> <span x-text="item.notes"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions & Pricing -->
                                    <div class="mt-4 md:mt-0 md:ml-6 flex-shrink-0">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center space-x-3 mb-4">
                                            <label class="text-sm font-medium text-gray-700">{{ __('app.cart_location.quantity_label') }}:</label>
                                            <div class="flex items-center border border-gray-300 rounded-md">
                                                <button @click="updateQuantity(item.id, item.quantity - 1)"
                                                        :disabled="item.quantity <= 1"
                                                        class="px-2 py-1 text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <span class="px-3 py-1 text-sm font-medium" x-text="item.quantity"></span>
                                                <button @click="updateQuantity(item.id, item.quantity + 1)"
                                                        class="px-2 py-1 text-gray-600 hover:text-gray-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Pricing -->
                                        <div class="text-right space-y-1">
                                            <div class="text-sm text-gray-600">
                                                <span x-text="formatCurrency(item.unit_price_per_day)"></span>{{ __('app.cart_location.per_day') }} × <span x-text="item.quantity"></span> × <span x-text="item.duration_days"></span> {{ __('app.cart_location.days') }}
                                            </div>
                                            <div class="text-lg font-semibold text-gray-900">
                                                <span x-text="formatCurrency(item.total_amount)"></span>
                                            </div>
                                            <div class="text-sm text-orange-600">
                                                {{ __('app.cart_location.deposit_label') }}: <span x-text="formatCurrency(item.subtotal_deposit)"></span>
                                            </div>
                                        </div>

                                        <!-- Item Actions -->
                                        <div class="mt-4 flex space-x-2">
                                            <button @click="editDates(item)" 
                                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                {{ __('app.cart_location.edit_dates') }}
                                            </button>
                                            <button @click="removeItem(item.id)" 
                                                    class="text-sm text-red-600 hover:text-red-800 font-medium">
                                                {{ __('app.cart_location.remove') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Summary Sidebar (Right Column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.cart_location.rental_summary') }}</h3>
                    
                    <!-- Summary Details -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.cart_location.subtotal_ht') }}:</span>
                            <span x-text="formatCurrency(cartSummary.total_amount || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('app.cart_location.vat') }} (21%):</span>
                            <span x-text="formatCurrency(cartSummary.total_tva || 0)"></span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="font-semibold text-gray-900">{{ __('app.cart_location.total_ttc') }}:</span>
                            <span class="font-semibold text-lg" x-text="formatCurrency(cartSummary.total_with_tax || 0)"></span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="font-semibold text-orange-600">{{ __('app.cart_location.total_deposit') }}:</span>
                            <span class="font-semibold text-orange-600" x-text="formatCurrency(cartSummary.total_deposit || 0)"></span>
                        </div>
                    </div>

                    <!-- Cart Stats -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex justify-between">
                                <span>{{ __('app.cart_location.items_count') }}:</span>
                                <span x-text="cartSummary.total_items || 0"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('app.cart_location.total_quantity') }}:</span>
                                <span x-text="cartSummary.total_quantity || 0"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <button @click="proceedToCheckout()" 
                                :disabled="cartItems.length === 0"
                                class="w-full bg-purple-600 text-white py-3 px-4 rounded-md font-semibold hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            {{ __('app.cart_location.proceed_checkout') }}
                        </button>
                        <a href="{{ route('rentals.index') }}" 
                           class="w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-md font-semibold hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors text-center block">
                            {{ __('app.cart_location.continue_shopping') }}
                        </a>
                    </div>

                    <!-- Important Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex">
                            <svg class="flex-shrink-0 w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">{{ __('app.cart_location.important_info') }}</h4>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>{{ __('app.cart_location.deposit_blocked') }}</li>
                                        <li>{{ __('app.cart_location.return_mandatory') }}</li>
                                        <li>{{ __('app.cart_location.inspection_required') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Dates Modal -->
    <div x-show="showDateModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('app.cart_location.edit_dates_modal_title') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.cart_location.start_date_label') }}</label>
                        <input type="date" 
                               x-model="editForm.start_date"
                               :min="minDate"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.cart_location.end_date_label') }}</label>
                        <input type="date" 
                               x-model="editForm.end_date"
                               :min="editForm.start_date"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button @click="saveDateChanges()" 
                            class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        {{ __('app.cart_location.save') }}
                    </button>
                    <button @click="showDateModal = false" 
                            class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{ __('app.cart_location.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
</div>

@push('scripts')
<script>
// Traductions des noms de produits pour JavaScript
window.productTranslations = @json($productNames ?? []);

document.addEventListener('alpine:init', () => {
    Alpine.data('cartLocationApp', () => ({
        // State
        isLoading: true,
        cartItems: [],
        cartSummary: {},
        showDateModal: false,
        editForm: {
            item_id: null,
            start_date: '',
            end_date: ''
        },
        minDate: new Date().toISOString().split('T')[0],

        // Initialize
        async init() {
            console.log('Product translations loaded:', window.productTranslations);
            await this.loadCart();
        },

        // Load cart data
        async loadCart() {
            try {
                this.isLoading = true;
                const response = await this.makeApiCall('/api/cart-location');
                
                if (response.success) {
                    this.cartItems = response.data.cart.items || [];
                    this.cartSummary = response.data.summary || {};
                } else {
                    this.showNotification(response.message || 'Erreur lors du chargement du panier', 'error');
                }
            } catch (error) {
                console.error('Error loading cart:', error);
                if (error.message.includes('401')) {
                    this.showNotification('Vous devez être connecté pour accéder au panier de location', 'error');
                    // Rediriger vers la page de connexion après 2 secondes
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    this.showNotification('Erreur lors du chargement du panier', 'error');
                }
            } finally {
                this.isLoading = false;
            }
        },

        // Update quantity
        async updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;

            try {
                const response = await this.makeApiCall(`/api/cart-location-items/${itemId}/quantity`, {
                    method: 'PUT',
                    body: JSON.stringify({ quantity: newQuantity })
                });

                if (response.success) {
                    await this.loadCart();
                    this.showNotification('Quantité mise à jour', 'success');
                } else {
                    this.showNotification(response.message || 'Erreur lors de la mise à jour', 'error');
                }
            } catch (error) {
                this.showNotification('Erreur lors de la mise à jour de la quantité', 'error');
            }
        },

        // Remove item
        async removeItem(itemId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

            try {
                const response = await this.makeApiCall(`/api/cart-location-items/${itemId}`, {
                    method: 'DELETE'
                });

                if (response.success) {
                    await this.loadCart();
                    this.showNotification('Article supprimé du panier', 'success');
                } else {
                    this.showNotification(response.message || 'Erreur lors de la suppression', 'error');
                }
            } catch (error) {
                this.showNotification('Erreur lors de la suppression', 'error');
            }
        },

        // Clear cart
        async clearCart() {
            if (!confirm('Êtes-vous sûr de vouloir vider complètement votre panier ?')) return;

            try {
                const response = await this.makeApiCall('/api/cart-location/clear', {
                    method: 'DELETE'
                });

                if (response.success) {
                    await this.loadCart();
                    this.showNotification('Panier vidé', 'success');
                } else {
                    this.showNotification(response.message || 'Erreur lors du vidage du panier', 'error');
                }
            } catch (error) {
                this.showNotification('Erreur lors du vidage du panier', 'error');
            }
        },

        // Edit dates
        editDates(item) {
            this.editForm.item_id = item.id;
            this.editForm.start_date = item.start_date;
            this.editForm.end_date = item.end_date;
            this.showDateModal = true;
        },

        // Save date changes
        async saveDateChanges() {
            if (!this.editForm.start_date || !this.editForm.end_date) {
                this.showNotification('Veuillez sélectionner les dates', 'error');
                return;
            }

            try {
                const response = await this.makeApiCall(`/api/cart-location-items/${this.editForm.item_id}/dates`, {
                    method: 'PUT',
                    body: JSON.stringify({
                        start_date: this.editForm.start_date,
                        end_date: this.editForm.end_date
                    })
                });

                if (response.success) {
                    await this.loadCart();
                    this.showDateModal = false;
                    this.showNotification('Dates mises à jour', 'success');
                } else if (response.status === 422) {
                    // Gestion des erreurs de validation
                    let errorMessage = 'Erreur de validation :';
                    
                    if (response.errors) {
                        // Afficher toutes les erreurs de validation
                        for (const [field, messages] of Object.entries(response.errors)) {
                            for (const message of messages) {
                                errorMessage += `\n- ${message}`;
                            }
                        }
                    } else {
                        errorMessage += `\n- ${response.message}`;
                    }
                    
                    this.showNotification(errorMessage, 'error');
                } else {
                    this.showNotification(response.message || 'Erreur lors de la mise à jour des dates', 'error');
                }
            } catch (error) {
                this.showNotification('Erreur lors de la mise à jour des dates', 'error');
            }
        },

        // Proceed to checkout
        proceedToCheckout() {
            if (this.cartItems.length === 0) {
                this.showNotification('Votre panier est vide', 'error');
                return;
            }
            
            // Rediriger vers la page de checkout pour les locations
            window.location.href = '{{ route("checkout-rental.index") }}';
        },

        // Utility functions
        formatCurrency(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount || 0);
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('fr-FR');
        },

        async makeApiCall(url, options = {}) {
            const defaultHeaders = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest' // Important pour Laravel
            };

            const response = await fetch(url, {
                headers: { ...defaultHeaders, ...options.headers },
                credentials: 'same-origin', // Important pour envoyer les cookies de session
                ...options
            });

            const text = await response.text();
            let data;
            
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Response was not JSON:', text);
                throw new Error('La réponse du serveur n\'est pas au format JSON');
            }

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('HTTP error! status: 401 - Non autorisé');
                }
                
                // Pour les erreurs 422 (validation), retournons les données avec le statut
                if (response.status === 422) {
                    return {
                        success: false,
                        status: 422,
                        errors: data.errors || {},
                        message: data.message || 'Erreur de validation'
                    };
                }
                
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return data;
        },

        // Get translated product name
        getTranslatedProductName(product) {
            if (window.productTranslations && window.productTranslations[product.slug]) {
                return window.productTranslations[product.slug];
            }
            // Fallback to database name if translation not found
            return product.name || product.slug;
        },

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-black'
            };

            notification.className = `${colors[type]} px-6 py-3 rounded-md shadow-lg transition-all duration-300 transform translate-x-full whitespace-pre-line`;
            notification.textContent = message;

            const container = document.getElementById('notifications');
            container.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 10);

            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000); // Plus de temps pour lire les messages d'erreur
        }
    }));
});
</script>
@endpush
@endsection
