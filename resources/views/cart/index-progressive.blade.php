@extends('layouts.app')

@section('title', 'Mon Panier - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🛒 Mon Panier</h1>
        
        <div x-data="cartData()" x-init="init()" class="mt-8">
            <!-- Loading State -->
            <div x-show="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <p class="mt-2 text-gray-600">Chargement du panier...</p>
            </div>

            <!-- Error State -->
            <div x-show="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erreur</h3>
                        <p class="mt-1 text-sm text-red-700" x-text="error"></p>
                    </div>
                </div>
            </div>

            <!-- Cart Content -->
            <div x-show="!loading && !error">
                <!-- Empty Cart -->
                <div x-show="!cart.items || cart.items.length === 0" class="text-center py-12">
                    <div class="text-6xl mb-4">🛒</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Votre panier est vide</h3>
                    <p class="text-gray-600 mb-6">Ajoutez des produits pour commencer vos achats</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        Découvrir nos produits
                    </a>
                </div>

                <!-- Cart Items -->
                <div x-show="cart.items && cart.items.length > 0">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">
                                Articles dans votre panier (<span x-text="cart.items ? cart.items.length : 0"></span>)
                            </h2>
                            <button @click="clearCart()" class="text-sm text-red-600 hover:text-red-800">
                                Vider le panier
                            </button>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            <template x-for="item in cart.items" :key="item.id">
                                <div class="p-6">
                                    <div class="flex items-start space-x-4">
                                        <!-- Image produit -->
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-2xl" x-text="item.product_emoji || '📦'"></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Détails produit -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-medium text-gray-900" x-text="item.product_name"></h3>
                                                    <p class="text-sm text-gray-600" x-text="item.product_category"></p>
                                                    
                                                    <!-- Contrôles de quantité -->
                                                    <div class="mt-3 flex items-center space-x-3">
                                                        <label class="text-sm text-gray-600">Quantité:</label>
                                                        <div class="flex items-center space-x-2">
                                                            <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                                                    :disabled="item.quantity <= 1 || updating"
                                                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm font-medium">
                                                                -
                                                            </button>
                                                            <span class="w-8 text-center font-medium" x-text="item.quantity"></span>
                                                            <button @click="updateQuantity(item.id, item.quantity + 1)"
                                                                    :disabled="updating"
                                                                    class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm font-medium">
                                                                +
                                                            </button>
                                                        </div>
                                                        <span class="text-sm text-gray-600" x-text="item.unit_label"></span>
                                                        
                                                        <!-- Bouton supprimer -->
                                                        <button @click="removeItem(item.id)" 
                                                                :disabled="updating"
                                                                class="ml-4 text-red-600 hover:text-red-800 disabled:opacity-50 disabled:cursor-not-allowed text-sm font-medium">
                                                            🗑️ Supprimer
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Détails prix -->
                                                <div class="flex-shrink-0 text-right space-y-1 ml-4">
                                                    <!-- Prix unitaire -->
                                                    <div class="text-sm text-gray-600">
                                                        Prix unitaire: <span x-text="item.price_per_unit_ttc_formatted"></span> TTC
                                                    </div>
                                                    
                                                    <!-- Sous-total HT -->
                                                    <div class="text-sm text-gray-600">
                                                        Sous-total HT: <span x-text="item.subtotal_formatted"></span>
                                                    </div>
                                                    
                                                    <!-- TVA -->
                                                    <div class="text-sm text-gray-600">
                                                        TVA (<span x-text="item.tax_rate_formatted"></span>): <span x-text="item.tax_amount_formatted"></span>
                                                    </div>
                                                    
                                                    <!-- Total TTC -->
                                                    <div class="text-lg font-semibold text-gray-900">
                                                        Total: <span x-text="item.total_formatted"></span> TTC
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Cart Total -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="space-y-3">
                                <!-- Sous-total HT -->
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Sous-total HT</span>
                                    <span x-text="cart.subtotal_ht_formatted"></span>
                                </div>
                                
                                <!-- TVA -->
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>TVA</span>
                                    <span x-text="cart.tax_amount_formatted"></span>
                                </div>
                                
                                <!-- Total produits TTC -->
                                <div class="flex justify-between text-base font-medium text-gray-900 pt-2 border-t border-gray-200">
                                    <span>Total produits TTC</span>
                                    <span x-text="cart.total_ttc_formatted"></span>
                                </div>
                                
                                <!-- Frais de livraison -->
                                <div class="flex justify-between text-sm" :class="cart.is_free_shipping ? 'text-green-600' : 'text-gray-600'">
                                    <span>Frais de livraison</span>
                                    <span x-text="cart.is_free_shipping ? 'GRATUITE' : cart.shipping_cost_formatted"></span>
                                </div>
                                
                                <!-- Message livraison gratuite -->
                                <div x-show="!cart.is_free_shipping && cart.remaining_for_free_shipping > 0" class="text-sm text-orange-600 bg-orange-50 p-2 rounded">
                                    <span>🚚 Ajoutez encore <strong x-text="cart.remaining_for_free_shipping_formatted"></strong> pour bénéficier de la livraison gratuite !</span>
                                </div>
                                
                                <div x-show="cart.is_free_shipping" class="text-sm text-green-600 bg-green-50 p-2 rounded">
                                    <span>🎉 Félicitations ! Vous bénéficiez de la livraison gratuite</span>
                                </div>
                                
                                <!-- Total final -->
                                <div class="flex justify-between text-lg font-bold text-gray-900 pt-3 border-t border-gray-300">
                                    <span>Total à payer</span>
                                    <span x-text="cart.total_with_shipping_formatted"></span>
                                </div>
                                
                                <!-- Bouton de commande -->
                                <div class="pt-4">
                                    <button class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                        Passer la commande
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cartData() {
    return {
        cart: {},
        loading: false,
        updating: false, // Pour les mises à jour individuelles
        error: null,
        
        init() {
            console.log('Initialisation du panier');
            this.loadCart();
        },
        
        async loadCart() {
            this.loading = true;
            this.error = null;
            
            try {
                console.log('Chargement du panier via route web...');
                const response = await fetch('/cart/data', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                console.log('Cart response status:', response.status);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.log('Error response:', errorText);
                    throw new Error(`Erreur HTTP: ${response.status} - ${errorText}`);
                }
                
                const data = await response.json();
                console.log('Données reçues:', data);
                
                this.cart = data.cart || {};
                
            } catch (error) {
                console.error('Erreur lors du chargement:', error);
                this.error = error.message;
            } finally {
                this.loading = false;
            }
        },
        
        async clearCart() {
            if (!confirm('Êtes-vous sûr de vouloir vider le panier ?')) {
                return;
            }
            
            try {
                const response = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    this.loadCart(); // Recharger le panier
                }
            } catch (error) {
                console.error('Erreur lors du vidage:', error);
            }
        },
        
        async updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;
            
            this.updating = true;
            try {
                const response = await fetch(`/cart/items/${itemId}/quantity`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ quantity: newQuantity })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    // Mettre à jour l'article dans le panier
                    const itemIndex = this.cart.items.findIndex(item => item.id === itemId);
                    if (itemIndex !== -1) {
                        this.cart.items[itemIndex] = data.item;
                    }
                    
                    // Mettre à jour les totaux du panier
                    const summary = data.cart_summary;
                    this.cart.subtotal_ht = summary.subtotal_ht;
                    this.cart.tax_amount = summary.tax_amount;
                    this.cart.total_ttc = summary.total_ttc;
                    this.cart.shipping_cost = summary.shipping_cost;
                    this.cart.total_with_shipping = summary.total_with_shipping;
                    this.cart.is_free_shipping = summary.is_free_shipping;
                    this.cart.remaining_for_free_shipping = summary.remaining_for_free_shipping;
                    this.cart.total_items = summary.total_items;
                    
                    // Mettre à jour les versions formatées
                    this.cart.subtotal_ht_formatted = summary.formatted.subtotal_ht;
                    this.cart.tax_amount_formatted = summary.formatted.tax_amount;
                    this.cart.total_ttc_formatted = summary.formatted.total_ttc;
                    this.cart.shipping_cost_formatted = summary.formatted.shipping_cost;
                    this.cart.total_with_shipping_formatted = summary.formatted.total_with_shipping;
                    this.cart.remaining_for_free_shipping_formatted = summary.formatted.remaining_for_free_shipping;
                }
            } catch (error) {
                console.error('Erreur lors de la mise à jour:', error);
            } finally {
                this.updating = false;
            }
        },
        
        async removeItem(itemId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                return;
            }
            
            this.updating = true;
            try {
                const response = await fetch(`/cart/items/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    this.loadCart(); // Recharger le panier complet
                }
            } catch (error) {
                console.error('Erreur lors de la suppression:', error);
            } finally {
                this.updating = false;
            }
        }
    }
}
</script>
@endsection
