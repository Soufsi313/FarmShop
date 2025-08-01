@extends('layouts.app')

@section('title', 'Confirmation de commande - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête de confirmation -->
        <div class="text-center mb-8">
            <div class="mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Paiement confirmé !</h1>
            <p class="text-lg text-gray-600">Votre commande a été validée avec succès</p>
        </div>

        <!-- Détails de la commande -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="border-b pb-4 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Commande #{{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Payée
                        </span>
                    </div>
                </div>
            </div>

            <!-- Articles commandés -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Articles commandés</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        @if($item->product_image)
                        <div class="flex-shrink-0">
                            <img src="{{ Storage::url($item->product_image) }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $item->quantity }} × {{ number_format($item->unit_price / 1.06, 2) }} € HT
                                @if($item->product_category['food_type'] === 'alimentaire')
                                    (TVA 6%)
                                @else
                                    (TVA 21%)
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ number_format($item->total_price, 2) }} €</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Résumé des prix -->
            <div class="border-t pt-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sous-total HT</span>
                        <span>{{ number_format($order->subtotal, 2) }} €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">TVA</span>
                        <span>{{ number_format($order->tax_amount, 2) }} €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Frais de livraison</span>
                        <span>
                            @if($order->shipping_cost > 0)
                                {{ number_format($order->shipping_cost, 2) }} €
                            @else
                                GRATUITE
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t pt-2">
                        <span>Total payé</span>
                        <span>{{ number_format($order->total_amount, 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adresses -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Adresse de facturation -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Adresse de facturation</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">{{ $order->billing_address['name'] ?? $order->billing_address['firstName'] . ' ' . $order->billing_address['lastName'] }}</p>
                    @if(!empty($order->billing_address['company']))
                        <p>{{ $order->billing_address['company'] }}</p>
                    @endif
                    <p>{{ $order->billing_address['address'] }}</p>
                    @if(!empty($order->billing_address['addressComplement']))
                        <p>{{ $order->billing_address['addressComplement'] }}</p>
                    @endif
                    <p>{{ $order->billing_address['postalCode'] ?? $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</p>
                    <p>{{ $order->billing_address['country'] }}</p>
                </div>
            </div>

            <!-- Adresse de livraison -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Adresse de livraison</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-medium text-gray-900">{{ $order->shipping_address['name'] ?? $order->shipping_address['firstName'] . ' ' . $order->shipping_address['lastName'] }}</p>
                    @if(!empty($order->shipping_address['company']))
                        <p>{{ $order->shipping_address['company'] }}</p>
                    @endif
                    <p>{{ $order->shipping_address['address'] }}</p>
                    @if(!empty($order->shipping_address['addressComplement']))
                        <p>{{ $order->shipping_address['addressComplement'] }}</p>
                    @endif
                    <p>{{ $order->shipping_address['postalCode'] ?? $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</p>
                    <p>{{ $order->shipping_address['country'] }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="text-center space-y-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center justify-center space-x-2 text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium">Un email de confirmation a été envoyé à {{ $order->user->email }}</span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('orders.show', $order) }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Voir ma commande
                </a>
                
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Continuer mes achats
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
