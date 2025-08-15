@extends('layouts.app')

@section('title', 'Paiement annulé - Commande #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Message d'annulation -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-800">
                        Paiement annulé
                    </h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        Le paiement de votre commande #{{ $order->order_number }} a été annulé.
                    </p>
                </div>
            </div>
        </div>

        <!-- Informations sur la commande -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Détails de la commande</h2>
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Informations</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Numéro:</strong> #{{ $order->order_number }}</p>
                        <p><strong>Date de création:</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                        <p><strong>Statut:</strong> 
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                En attente de paiement
                            </span>
                        </p>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Total</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($order->total_amount, 2) }}€</p>
                </div>
            </div>

            <!-- Articles de la commande -->
            <div class="border-t pt-6">
                <h3 class="font-medium text-gray-900 mb-4">Articles</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-500">Quantité: {{ $item->quantity }}</p>
                        </div>
                        <span class="font-medium">{{ number_format($item->total_price, 2) }}€</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Que faire maintenant ? -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-blue-900 mb-2">Que pouvez-vous faire ?</h3>
            <div class="text-sm text-blue-800 space-y-2">
                <p>• Votre commande est toujours valide et en attente de paiement</p>
                <p>• Vous pouvez retenter le paiement en cliquant sur le bouton ci-dessous</p>
                <p>• Si vous ne souhaitez plus cette commande, vous pouvez l'annuler</p>
                <p>• En cas de problème, contactez notre service client</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('payment.show', $order) }}" 
               class="flex-1 bg-blue-600 text-white text-center py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                Retenter le paiement
            </a>
            <a href="{{ route('orders.show', $order) }}" 
               class="flex-1 bg-gray-200 text-gray-800 text-center py-3 px-4 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                Voir la commande
            </a>
            <a href="{{ route('cart.index') }}" 
               class="flex-1 bg-gray-200 text-gray-800 text-center py-3 px-4 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                Retour au panier
            </a>
        </div>
    </div>
</div>
@endsection
