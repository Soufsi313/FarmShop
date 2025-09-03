@extends('layouts.app')

@section('title', 'Confirmation de retour - Commande #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Confirmation de retour
            </h1>
            <p class="text-gray-600">
                Commande #{{ $order->order_number }} - Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
            </p>
            <div class="mt-4">
                <a href="{{ route('orders.show', $order) }}" 
                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                    ← Retour aux détails de la commande
                </a>
            </div>
        </div>

        <!-- Alerte importante -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Information importante</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Seuls les <strong>produits non-alimentaires</strong> peuvent être retournés pour des raisons d'hygiène et de sécurité alimentaire.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            
            <!-- Articles retournables -->
            @if($returnableItems->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Articles retournables
                </h2>
                
                <div class="space-y-3 mb-4">
                    @foreach($returnableItems as $item)
                    <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($item->product_image)
                                    <img src="{{ asset('storage/' . $item->product_image) }}" 
                                         alt="{{ $item->product_name }}"
                                         class="w-full h-full object-cover rounded-lg">
                                @else
                                    <span class="text-lg">📦</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">
                                Quantité: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }}€
                            </p>
                            <p class="text-sm font-medium text-green-600">
                                {{ number_format($item->unit_price * $item->quantity, 2) }}€
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="border-t pt-3">
                    <div class="flex justify-between text-lg font-semibold text-green-800">
                        <span>Montant remboursable:</span>
                        <span>{{ number_format($returnableAmount, 2) }}€</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Articles non-retournables -->
            @if($nonReturnableItems->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Articles non-retournables
                </h2>
                
                <div class="space-y-3 mb-4">
                    @foreach($nonReturnableItems as $item)
                    <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($item->product_image)
                                    <img src="{{ asset('storage/' . $item->product_image) }}" 
                                         alt="{{ $item->product_name }}"
                                         class="w-full h-full object-cover rounded-lg">
                                @else
                                    <span class="text-lg">🍎</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-600">
                                Quantité: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }}€
                            </p>
                            <p class="text-sm text-red-600">
                                Produit alimentaire - Non retournable
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="border-t pt-3">
                    <div class="flex justify-between text-lg font-medium text-red-800">
                        <span>Montant non remboursable:</span>
                        <span>{{ number_format($nonReturnableAmount, 2) }}€</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Résumé et formulaire -->
        @if($returnableItems->count() > 0)
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Confirmer le retour</h2>
            
            <!-- Résumé financier -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Montant total de la commande:</span>
                        <span class="font-medium">{{ number_format($order->total_amount, 2) }}€</span>
                    </div>
                    @if($nonReturnableAmount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-600">Produits non-retournables:</span>
                        <span class="text-red-600">- {{ number_format($nonReturnableAmount, 2) }}€</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-semibold text-green-600 border-t pt-2">
                        <span>Montant du remboursement:</span>
                        <span>{{ number_format($returnableAmount, 2) }}€</span>
                    </div>
                </div>
            </div>

            <!-- Formulaire de retour -->
            <form method="POST" action="{{ route('orders.return', $order) }}">
                @csrf
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison du retour <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Expliquez pourquoi vous souhaitez retourner ces articles...">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conditions -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            Je comprends que seuls les articles non-alimentaires seront remboursés et que le remboursement sera effectué sous 3-5 jours ouvrés.
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-green-700 transition-all duration-200 hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Confirmer le retour ({{ number_format($returnableAmount, 2) }}€)
                    </button>
                    <a href="{{ route('orders.show', $order) }}" 
                       class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-400 transition-colors text-center">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
        @else
        <!-- Aucun article retournable -->
        <div class="bg-white rounded-lg shadow p-6 mt-6 text-center">
            <div class="text-6xl mb-4">🚫</div>
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Aucun article retournable</h2>
            <p class="text-gray-600 mb-6">
                Cette commande ne contient que des produits alimentaires qui ne peuvent pas être retournés pour des raisons d'hygiène.
            </p>
            <a href="{{ route('orders.show', $order) }}" 
               class="bg-green-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-green-700 transition-colors">
                Retour aux détails
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
