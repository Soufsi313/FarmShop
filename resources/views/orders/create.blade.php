@extends('layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser ma commande</h1>
            <p class="text-gray-600">Vérifiez vos informations et validez votre commande</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale - Adresse de livraison -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Adresse de livraison
                    </h2>
                    
                    @if(auth()->user()->hasDefaultShippingAddress())
                        <!-- Adresse par défaut -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h6 class="font-semibold text-blue-900 mb-1">Adresse par défaut</h6>
                                    <p class="text-blue-800 font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-blue-700">{{ auth()->user()->default_shipping_address['street'] ?? '' }}</p>
                                    @if(!empty(auth()->user()->default_shipping_address['additional_info']))
                                        <p class="text-blue-700">{{ auth()->user()->default_shipping_address['additional_info'] }}</p>
                                    @endif
                                    <p class="text-blue-700">{{ auth()->user()->default_shipping_address['postal_code'] ?? '' }} {{ auth()->user()->default_shipping_address['city'] ?? '' }}</p>
                                    <p class="text-blue-700">{{ auth()->user()->default_shipping_address['country'] ?? 'France' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="use_different_address" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Utiliser une adresse différente</span>
                            </label>
                        </div>
                        
                        <!-- Formulaire d'adresse (caché par défaut) -->
                        <div id="address-form" class="hidden">
                    @else
                        <!-- Pas d'adresse par défaut -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div>
                                    <h6 class="font-semibold text-yellow-900">Aucune adresse de livraison enregistrée</h6>
                                    <p class="text-yellow-800">Veuillez renseigner votre adresse de livraison.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div id="address-form">
                    @endif
                    
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                                <input type="text" id="shipping_address" name="shipping_address" 
                                       value="{{ old('shipping_address', auth()->user()->default_shipping_address['street'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('shipping_address') border-red-500 @enderror" 
                                       placeholder="Ex: 123 Rue de la Paix" required>
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="shipping_additional" class="block text-sm font-medium text-gray-700 mb-1">Complément d'adresse</label>
                                <input type="text" id="shipping_additional" name="shipping_additional" 
                                       value="{{ old('shipping_additional', auth()->user()->default_shipping_address['additional_info'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" 
                                       placeholder="Ex: Appartement 4B, Étage 2">
                            </div>
                            
                            <div>
                                <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                                <input type="text" id="shipping_city" name="shipping_city" 
                                       value="{{ old('shipping_city', auth()->user()->default_shipping_address['city'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('shipping_city') border-red-500 @enderror" 
                                       placeholder="Ex: Paris" required>
                                @error('shipping_city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Code postal *</label>
                                <input type="text" id="shipping_postal_code" name="shipping_postal_code" 
                                       value="{{ old('shipping_postal_code', auth()->user()->default_shipping_address['postal_code'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('shipping_postal_code') border-red-500 @enderror" 
                                       placeholder="Ex: 75001" required>
                                @error('shipping_postal_code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                <select id="shipping_country" name="shipping_country" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    <option value="France" {{ old('shipping_country', auth()->user()->default_shipping_address['country'] ?? 'France') == 'France' ? 'selected' : '' }}>🇫🇷 France</option>
                                    <option value="Belgique" {{ old('shipping_country', auth()->user()->default_shipping_address['country'] ?? '') == 'Belgique' ? 'selected' : '' }}>🇧🇪 Belgique</option>
                                    <option value="Suisse" {{ old('shipping_country', auth()->user()->default_shipping_address['country'] ?? '') == 'Suisse' ? 'selected' : '' }}>🇨🇭 Suisse</option>
                                    <option value="Luxembourg" {{ old('shipping_country', auth()->user()->default_shipping_address['country'] ?? '') == 'Luxembourg' ? 'selected' : '' }}>🇱🇺 Luxembourg</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles commandés -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Articles commandés
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg @if($item->hasDiscount()) bg-green-50 border-green-200 @endif">
                                <!-- Image du produit -->
                                <div class="flex-shrink-0">
                                    @if($item->product && $item->product->main_image)
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Informations du produit -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="font-semibold text-gray-900">{{ $item->product ? $item->product->name : 'Produit supprimé' }}</h3>
                                        @if($item->hasDiscount())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                PROMO
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">Quantité: {{ $item->quantity }}</p>
                                    @if($item->hasDiscount())
                                        @php
                                            $offerDetails = $item->special_offer_details;
                                            $discountedUnitPrice = $item->unit_price * (1 - ($offerDetails['discount_percentage'] ?? 0) / 100);
                                        @endphp
                                        <p class="text-sm text-gray-500">Prix unitaire: <span class="line-through">{{ number_format($item->unit_price, 2) }} €</span></p>
                                        <p class="text-sm text-green-600 font-medium">Prix avec remise: {{ number_format($discountedUnitPrice, 2) }} €</p>
                                        <p class="text-xs text-green-600">Économie: {{ number_format($item->discount_amount, 2) }} €</p>
                                    @else
                                        <p class="text-sm text-gray-600">Prix unitaire: {{ number_format($item->unit_price, 2) }} €</p>
                                    @endif
                                </div>
                                
                                <!-- Prix total -->
                                <div class="text-right">
                                    @if($item->hasDiscount())
                                        <p class="text-sm text-gray-400 line-through">{{ number_format($item->original_total, 2) }} €</p>
                                        <p class="text-lg font-semibold text-green-600">{{ number_format($item->total_price, 2) }} €</p>
                                        <p class="text-xs text-green-600">-{{ number_format($item->discount_amount, 2) }} €</p>
                                    @else
                                        <p class="text-lg font-semibold text-green-600">{{ number_format($item->total_price, 2) }} €</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Colonne de droite - Récapitulatif -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Récapitulatif
                    </h2>
                    
                    @php $subtotal = 0; @endphp
                    @foreach($cartItems as $item)
                        @php $subtotal += $item->total_price; @endphp
                    @endforeach
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total</span>
                            <span class="font-semibold">{{ number_format($subtotal, 2) }} €</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais de livraison</span>
                            <span class="font-semibold">
                                @if($subtotal >= 25)
                                    <span class="text-green-600">Gratuit</span>
                                @else
                                    <span class="text-gray-900">2,50 €</span>
                                @endif
                            </span>
                        </div>
                        
                        @if($subtotal < 25)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-sm text-blue-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Plus que <strong>{{ number_format(25 - $subtotal, 2) }} €</strong> pour la livraison gratuite !
                                </p>
                            </div>
                        @endif
                        
                        <hr class="border-gray-200">
                        
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-green-600">{{ number_format($subtotal + ($subtotal >= 25 ? 0 : 2.50), 2) }} €</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Valider ma commande
                        </button>
                        
                        <a href="{{ route('cart.index') }}" 
                           class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Retour au panier
                        </a>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Paiement sécurisé SSL
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('use_different_address');
    const addressForm = document.getElementById('address-form');
    
    if (checkbox && addressForm) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                addressForm.classList.remove('hidden');
            } else {
                addressForm.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
