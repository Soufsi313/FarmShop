@extends('layouts.app')

@section('title', 'Finaliser ma commande - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="checkoutData()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser ma commande</h1>
            <p class="text-gray-600">Vérifiez vos informations avant de confirmer votre commande</p>
        </div>

        <form method="POST" action="{{ route('checkout.create-order') }}" class="grid lg:grid-cols-3 gap-8">
            @csrf
            
            <!-- Colonnes de gauche: Formulaires -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Adresses de livraison -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __("app.forms.delivery_address") }}<//h2>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" 
                                   name="use_profile_addresses" 
                                   value="1"
                                   x-model="useProfileAddresses"
                                   @change="toggleProfileAddresses()"
                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-600">Utiliser l'adresse de mon profil</span>
                        </label>
                    </div>

                    <!-- Adresse du profil (affichée si l'option est cochée) -->
                    <div x-show="useProfileAddresses" class="mb-4 p-4 bg-green-50 rounded-lg">
                        <h3 class="font-medium text-green-900 mb-2">Adresse depuis votre profil :</h3>
                        <div class="text-sm text-green-800">
                            <p class="font-medium">{{ $user->name }}</p>
                            <p>{{ $user->address }}</p>
                            <p>{{ $user->postal_code }} {{ $user->city }}</p>
                            <p>{{ $user->country ?? 'France' }}</p>
                        </div>
                        <a href="{{ route('users.profile') }}" class="text-green-600 hover:text-green-800 text-sm underline mt-2 inline-block">
                            Modifier mon profil
                        </a>
                    </div>

                    <!-- Formulaire d'adresse (masqué si profil utilisé) -->
                    <div x-show="!useProfileAddresses">
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Adresse de facturation -->
                            <div>
                                <h3 class="font-medium text-gray-900 mb-3">Adresse de facturation</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.full_name") }}<//label>
                                        <input type="text" id="billing_name" name="billing_address[name]" 
                                               value="{{ old('billing_address.name', $user->name) }}"
                                               required 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.phone") }}<//label>
                                        <input type="tel" id="billing_phone" name="billing_address[phone]" 
                                               value="{{ old('billing_address.phone', $user->phone) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.address") }}<//label>
                                    <input type="text" id="billing_address" name="billing_address[address]" 
                                           value="{{ old('billing_address.address', $user->address) }}"
                                           required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.postal_code") }}<//label>
                                        <input type="text" id="billing_postal_code" name="billing_address[postal_code]" 
                                               value="{{ old('billing_address.postal_code', $user->postal_code) }}"
                                               required 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.city") }}<//label>
                                        <input type="text" id="billing_city" name="billing_address[city]" 
                                               value="{{ old('billing_address.city', $user->city) }}"
                                               required 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.country") }}<//label>
                                    <select id="billing_country" name="billing_address[country]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="France" {{ old('billing_address.country', $user->country ?? 'France') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Belgique" {{ old('billing_address.country', $user->country) == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                        <option value="Suisse" {{ old('billing_address.country', $user->country) == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Option: même adresse pour livraison -->
                            <div class="flex items-center space-x-2 mt-4">
                                <input type="checkbox" 
                                       id="same_shipping_address" 
                                       x-model="sameShippingAddress"
                                       @change="copybillingToShipping()"
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <label for="same_shipping_address" class="text-sm text-gray-600">
                                    Utiliser la même adresse pour la livraison
                                </label>
                            </div>

                            <!-- Adresse de livraison (si différente) -->
                            <div x-show="!sameShippingAddress">
                                <h3 class="font-medium text-gray-900 mb-3 mt-6">{{ __("app.forms.delivery_address") }}<//h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.full_name") }}<//label>
                                        <input type="text" id="shipping_name" name="shipping_address[name]" 
                                               x-model="shippingAddress.name"
                                               required 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.phone") }}<//label>
                                        <input type="tel" id="shipping_phone" name="shipping_address[phone]" 
                                               x-model="shippingAddress.phone"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.address") }}<//label>
                                    <input type="text" id="shipping_address" name="shipping_address[address]" 
                                           x-model="shippingAddress.address"
                                           required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.postal_code") }}<//label>
                                        <input type="text" id="shipping_postal_code" name="shipping_address[postal_code]" 
                                               x-model="shippingAddress.postal_code"
                                               required 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.city") }}<//label>
                                        <input type="text" id="shipping_city" name="shipping_address[city]" 
                                               x-model="shippingAddress.city"
                                               required 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">{{ __("app.forms.country") }}<//label>
                                    <select id="shipping_country" name="shipping_address[country]" 
                                            x-model="shippingAddress.country"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="France">France</option>
                                        <option value="Belgique">Belgique</option>
                                        <option value="Suisse">Suisse</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Méthode de paiement -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Méthode de paiement</h2>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="card" checked 
                                   class="text-green-600 focus:ring-green-500">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                    💳
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Carte bancaire</p>
                                    <p class="text-sm text-gray-600">Paiement sécurisé par Stripe</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="paypal" 
                                   class="text-green-600 focus:ring-green-500">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded flex items-center justify-center text-xs font-bold">
                                    PP
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">PayPal</p>
                                    <p class="text-sm text-gray-600">Paiement via votre compte PayPal</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" 
                                   class="text-green-600 focus:ring-green-500">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-100 rounded flex items-center justify-center">
                                    🏦
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Virement bancaire</p>
                                    <p class="text-sm text-gray-600">Paiement après réception de la facture</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Colonne de droite: Résumé de commande -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Résumé de votre commande</h2>
                    
                    <!-- Articles -->
                    <div class="space-y-3 mb-4">
                        @foreach($cart->items as $item)
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-lg">{{ $item->product_metadata['emoji'] ?? '📦' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-600">{{ $item->quantity }} × {{ number_format($item->product_metadata['price_ttc_unitaire'] ?? 0, 2) }} €</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($item->total, 2) }} €
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totaux -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total HT</span>
                            <span>{{ $cartSummary['formatted']['subtotal_ht'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __("app.ecommerce.tax") }}<//span>
                            <span>{{ $cartSummary['formatted']['tax_amount'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total produits</span>
                            <span>{{ $cartSummary['formatted']['total_ttc'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Frais de livraison</span>
                            <span class="{{ $cartSummary['is_free_shipping'] ? 'text-green-600' : '' }}">
                                {{ $cartSummary['is_free_shipping'] ? 'GRATUITE' : $cartSummary['formatted']['shipping_cost'] }}
                            </span>
                        </div>
                        
                        @if(!$cartSummary['is_free_shipping'])
                        <div class="text-xs text-orange-600 bg-orange-50 p-2 rounded">
                            Ajoutez encore {{ $cartSummary['formatted']['remaining_for_free_shipping'] }} pour bénéficier de la livraison gratuite
                        </div>
                        @endif

                        <div class="border-t pt-2">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total à payer</span>
                                <span>{{ $cartSummary['formatted']['total_with_shipping'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de validation -->
                    <button type="submit" 
                            class="w-full mt-6 bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors">
                        Confirmer la commande
                    </button>

                    <!-- Retour au panier -->
                    <a href="{{ route('cart.index') }}" 
                       class="w-full mt-3 block text-center text-gray-600 hover:text-gray-800 text-sm">
                        ← Retour au panier
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function checkoutData() {
    return {
        useProfileAddresses: false,
        sameShippingAddress: true,
        shippingAddress: {
            name: '{{ old('shipping_address.name', $user->name) }}',
            phone: '{{ old('shipping_address.phone', $user->phone) }}',
            address: '{{ old('shipping_address.address', $user->address) }}',
            postal_code: '{{ old('shipping_address.postal_code', $user->postal_code) }}',
            city: '{{ old('shipping_address.city', $user->city) }}',
            country: '{{ old('shipping_address.country', $user->country ?? 'France') }}'
        },

        toggleProfileAddresses() {
            // Cette fonction est appelée quand l'utilisateur coche/décoche l'utilisation du profil
        },

        copybillingToShipping() {
            if (this.sameShippingAddress) {
                // Copier les valeurs de facturation vers livraison
                this.shippingAddress.name = document.getElementById('billing_name').value;
                this.shippingAddress.phone = document.getElementById('billing_phone').value;
                this.shippingAddress.address = document.getElementById('billing_address').value;
                this.shippingAddress.postal_code = document.getElementById('billing_postal_code').value;
                this.shippingAddress.city = document.getElementById('billing_city').value;
                this.shippingAddress.country = document.getElementById('billing_country').value;
            }
        }
    }
}
</script>

@endsection
