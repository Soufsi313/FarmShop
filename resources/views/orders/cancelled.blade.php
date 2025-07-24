@extends('layouts.app')

@section('title', 'Commande Annulée - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête avec icône de succès -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Commande Annulée</h1>
            <p class="text-lg text-gray-600">Votre commande #{{ $order->order_number }} a été annulée avec succès</p>
        </div>

        <!-- Carte principale -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            
            <!-- Header de la commande -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Commande #{{ $order->order_number }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Annulée le {{ $order->cancelled_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            🔴 Annulée
                        </span>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            {{ number_format($order->total_amount, 2) }} €
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informations sur l'annulation -->
            <div class="px-6 py-4 bg-red-50 border-b border-gray-200">
                <h4 class="text-md font-medium text-red-900 mb-2">ℹ️ Informations sur l'annulation</h4>
                <div class="text-sm text-red-800">
                    <p>• Votre commande a été annulée et le stock des produits a été restauré</p>
                    @if($order->payment_status === 'paid')
                        <p>• Le remboursement sera traité sous 3-5 jours ouvrés</p>
                    @endif
                    @if($order->cancellation_reason)
                        <p>• Raison : {{ $order->cancellation_reason }}</p>
                    @endif
                    <p>• Un email de confirmation vous a été envoyé</p>
                </div>
            </div>

            <!-- Articles de la commande annulée -->
            <div class="px-6 py-4">
                <h4 class="text-md font-medium text-gray-900 mb-4">Articles de la commande annulée</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                        @if($item->product_image)
                        <img src="{{ Storage::url($item->product_image) }}" 
                             alt="{{ $item->product_name }}" 
                             class="w-16 h-16 object-cover rounded-md">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <h5 class="font-medium text-gray-900">{{ $item->product_name }}</h5>
                            <p class="text-sm text-gray-600">Quantité : {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-600">Prix unitaire : {{ number_format($item->unit_price, 2) }} €</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-medium text-gray-900">
                                {{ number_format($item->total_price, 2) }} €
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Récapitulatif financier -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Sous-total :</span>
                        <span>{{ number_format($order->subtotal, 2) }} €</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between text-sm">
                        <span>TVA :</span>
                        <span>{{ number_format($order->tax_amount, 2) }} €</span>
                    </div>
                    @endif
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between text-sm">
                        <span>Frais de livraison :</span>
                        <span>{{ number_format($order->shipping_cost, 2) }} €</span>
                    </div>
                    @endif
                    <div class="border-t pt-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total annulé :</span>
                            <span>{{ number_format($order->total_amount, 2) }} €</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 text-center space-y-4">
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('orders.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                    📋 Voir mes autres commandes
                </a>
                
                <a href="{{ route('products.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                    🛒 Continuer mes achats
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                    💬 Nous contacter
                </a>
            </div>
        </div>

        <!-- Support -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-lg font-medium text-blue-900 mb-2">Besoin d'aide ?</h4>
                    <p class="text-blue-800 mb-3">
                        Si vous avez des questions concernant cette annulation ou si vous souhaitez repasser commande, 
                        notre équipe est là pour vous aider.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            📧 Nous écrire
                        </a>
                        <span class="text-blue-600">📞 01 23 45 67 89</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
