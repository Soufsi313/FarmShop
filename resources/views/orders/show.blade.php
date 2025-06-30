@extends('layouts.app')

@section('title', 'Commande #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête de la commande -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        Commande #{{ $order->order_number }}
                    </h1>
                    <p class="text-gray-600 mb-1">
                        <span class="font-medium">Passée le :</span> 
                        {{ $order->created_at->format('d/m/Y à H:i') }}
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">Status :</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @switch($order->status)
                                @case('pending')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('confirmed')
                                    bg-blue-100 text-blue-800
                                    @break
                                @case('shipped')
                                    bg-purple-100 text-purple-800
                                    @break
                                @case('delivered')
                                    bg-green-100 text-green-800
                                    @break
                                @case('cancelled')
                                    bg-red-100 text-red-800
                                    @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endswitch
                        ">
                            @switch($order->status)
                                @case('pending')
                                    En attente
                                    @break
                                @case('confirmed')
                                    Confirmée
                                    @break
                                @case('shipped')
                                    Expédiée
                                    @break
                                @case('delivered')
                                    Livrée
                                    @break
                                @case('cancelled')
                                    Annulée
                                    @break
                                @default
                                    {{ ucfirst($order->status) }}
                            @endswitch
                        </span>
                    </p>
                </div>
                
                <div class="text-right">
                    <p class="text-sm text-gray-600 mb-1">Total de la commande</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($order->total_amount, 2) }} €</p>
                </div>
            </div>
        </div>

        <!-- Articles de la commande -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Articles commandés</h2>
            
            <div class="divide-y divide-gray-200">
                @foreach($order->items as $item)
                <div class="py-4 first:pt-0 last:pb-0">
                    <div class="flex items-start space-x-4">
                        <!-- Image du produit -->
                        <div class="flex-shrink-0">
                            @if($item->product && $item->product->main_image)
                                <img src="{{ $item->product->main_image_url }}" 
                                     alt="{{ $item->product_name }}"
                                     class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-200">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Détails de l'article -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 mb-1">
                                {{ $item->product_name }}
                            </h3>
                            
                            @if($item->product_description)
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                {{ Str::limit($item->product_description, 100) }}
                            </p>
                            @endif
                            
                            <div class="flex items-center text-sm text-gray-600">
                                <span>Quantité : {{ $item->quantity }}</span>
                                <span class="mx-2">•</span>
                                <span>Prix unitaire : {{ number_format($item->unit_price, 2) }} €</span>
                            </div>
                            
                            <!-- Status de l'article -->
                            <div class="mt-2">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                    @switch($item->status)
                                        @case('pending')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @case('confirmed')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('shipped')
                                            bg-purple-100 text-purple-800
                                            @break
                                        @case('delivered')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('cancelled')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($item->status)
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('confirmed')
                                            Confirmé
                                            @break
                                        @case('shipped')
                                            Expédié
                                            @break
                                        @case('delivered')
                                            Livré
                                            @break
                                        @case('cancelled')
                                            Annulé
                                            @break
                                        @default
                                            {{ ucfirst($item->status) }}
                                    @endswitch
                                </span>
                            </div>
                        </div>
                        
                        <!-- Prix total de l'article -->
                        <div class="text-right">
                            <p class="text-lg font-semibold text-gray-900">
                                {{ number_format($item->total_price, 2) }} €
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Informations de livraison et facturation -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Adresse de livraison -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <svg class="w-5 h-5 inline-block mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Adresse de livraison
                </h3>
                
                @php
                    $shippingAddress = $order->shipping_address ? json_decode($order->shipping_address, true) : null;
                @endphp
                
                @if($shippingAddress)
                <div class="text-gray-700 space-y-1">
                    <p>{{ $shippingAddress['street'] ?? '' }}</p>
                    @if(isset($shippingAddress['additional_info']) && $shippingAddress['additional_info'])
                        <p>{{ $shippingAddress['additional_info'] }}</p>
                    @endif
                    <p>{{ $shippingAddress['postal_code'] ?? '' }} {{ $shippingAddress['city'] ?? '' }}</p>
                    <p>{{ $shippingAddress['country'] ?? 'France' }}</p>
                </div>
                @else
                <p class="text-gray-500">Adresse non disponible</p>
                @endif
            </div>

            <!-- Récapitulatif des coûts -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <svg class="w-5 h-5 inline-block mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Récapitulatif
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total</span>
                        <span class="font-medium">{{ number_format($order->subtotal, 2) }} €</span>
                    </div>
                    
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Frais de livraison</span>
                        <span class="font-medium">{{ number_format($order->shipping_cost, 2) }} €</span>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-600">Frais de livraison</span>
                        <span class="font-medium text-green-600">Gratuit</span>
                    </div>
                    @endif
                    
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">TVA</span>
                        <span class="font-medium">{{ number_format($order->tax_amount, 2) }} €</span>
                    </div>
                    @endif
                    
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span class="text-green-600">{{ number_format($order->total_amount, 2) }} €</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes de la commande -->
        @if($order->notes)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
            <p class="text-gray-700">{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('orders.user.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à mes commandes
                </a>
                
                @if(in_array($order->status, ['pending', 'confirmed']) && $order->created_at->diffInHours(now()) < 24)
                <button type="button"
                        onclick="cancelOrder()"
                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Annuler la commande
                </button>
                @endif
                
                @if(in_array($order->status, ['delivered']))
                <a href="#" 
                   class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-lg text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Télécharger la facture
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if(in_array($order->status, ['pending', 'confirmed']) && $order->created_at->diffInHours(now()) < 24)
<!-- Modal d'annulation -->
<div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Annuler la commande</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form method="POST" action="{{ route('orders.user.cancel', $order->id) }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Annuler
                    </button>
                </form>
                <button onclick="closeCancelModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Retour
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cancelOrder() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}
</script>
@endif
@endsection
