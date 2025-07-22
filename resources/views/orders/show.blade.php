@extends('layouts.app')

@section('title', 'Commande #' . $order->order_number . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Commande #{{ $order->order_number }}
                    </h1>
                    <p class="text-gray-600">
                        Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        @switch($order->status)
                            @case('pending')
                                🟡 En attente de confirmation
                                @break
                            @case('confirmed')
                                🔵 Confirmée
                                @break
                            @case('shipped')
                                🟣 Expédiée
                                @break
                            @case('delivered')
                                🟢 Livrée
                                @break
                            @case('cancelled')
                                🔴 Annulée
                                @break
                            @default
                                ⚪ {{ ucfirst($order->status) }}
                        @endswitch
                    </span>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" 
                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                    ← Retour à mes commandes
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Colonnes de gauche: Détails de la commande -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Articles commandés -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Articles commandés</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-start space-x-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <!-- Image produit -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" 
                                             alt="{{ $item->product_name }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-2xl">📦</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Détails produit -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-gray-900">{{ $item->product_name }}</h3>
                                @if($item->product_sku)
                                <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">{{ $item->product_description }}</p>
                                
                                <!-- Statut de l'article -->
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $item->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $item->status == 'preparing' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $item->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $item->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $item->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Prix et quantité -->
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm text-gray-600">{{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} €</p>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($item->total_price, 2) }} €</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Informations de livraison -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresse de livraison</h2>
                    <div class="text-sm">
                        <p class="font-medium">{{ $order->shipping_address['name'] }}</p>
                        <p>{{ $order->shipping_address['address'] }}</p>
                        <p>{{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</p>
                        <p>{{ $order->shipping_address['country'] }}</p>
                        @if(isset($order->shipping_address['phone']))
                        <p class="mt-2">📞 {{ $order->shipping_address['phone'] }}</p>
                        @endif
                    </div>
                    
                    @if($order->tracking_number)
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-900">Numéro de suivi</p>
                        <p class="text-sm text-blue-800">{{ $order->tracking_number }}</p>
                    </div>
                    @endif
                </div>

                <!-- Informations de facturation -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresse de facturation</h2>
                    <div class="text-sm">
                        <p class="font-medium">{{ $order->billing_address['name'] }}</p>
                        <p>{{ $order->billing_address['address'] }}</p>
                        <p>{{ $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</p>
                        <p>{{ $order->billing_address['country'] }}</p>
                    </div>
                </div>

            </div>

            <!-- Colonne de droite: Résumé et actions -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Résumé financier -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Résumé de la commande</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total</span>
                            <span>{{ number_format($order->subtotal, 2) }} €</span>
                        </div>
                        
                        @if($order->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">TVA</span>
                            <span>{{ number_format($order->tax_amount, 2) }} €</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Frais de livraison</span>
                            <span class="{{ $order->shipping_cost == 0 ? 'text-green-600' : '' }}">
                                {{ $order->shipping_cost == 0 ? 'GRATUITE' : number_format($order->shipping_cost, 2) . ' €' }}
                            </span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Remise</span>
                            <span class="text-green-600">-{{ number_format($order->discount_amount, 2) }} €</span>
                        </div>
                        @endif
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>{{ number_format($order->total_amount, 2) }} €</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statut du paiement -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Paiement</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Méthode</span>
                            <span class="capitalize">
                                @switch($order->payment_method)
                                    @case('card')
                                        💳 Carte bancaire
                                        @break
                                    @case('paypal')
                                        💰 PayPal
                                        @break
                                    @case('bank_transfer')
                                        🏦 Virement bancaire
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Statut</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->payment_status == 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                @switch($order->payment_status)
                                    @case('paid')
                                        ✅ Payé
                                        @break
                                    @case('pending')
                                        ⏳ En attente
                                        @break
                                    @case('failed')
                                        ❌ Échec
                                        @break
                                    @default
                                        {{ ucfirst($order->payment_status) }}
                                @endswitch
                            </span>
                        </div>
                        
                        @if($order->paid_at)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payé le</span>
                            <span>{{ $order->paid_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        @if($order->can_be_cancelled && in_array($order->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 transition-colors">
                                Annuler la commande
                            </button>
                        </form>
                        @endif
                        
                        <!-- Bouton de téléchargement de facture (si payée) -->
                        @if($order->payment_status == 'paid')
                        <a href="#" onclick="alert('Fonctionnalité bientôt disponible')"
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors text-center block">
                            📄 Télécharger la facture
                        </a>
                        @endif
                        
                        <!-- Renouveler la commande -->
                        <a href="#" onclick="alert('Fonctionnalité bientôt disponible')"
                           class="w-full bg-green-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors text-center block">
                            🔄 Renouveler cette commande
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
