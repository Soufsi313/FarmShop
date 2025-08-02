@extends('layouts.app')

@section('title', 'Paiement Location - Commande #' . $orderLocation->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Paiement Location - Commande #{{ $orderLocation->order_number }}</h1>
                    <p class="text-gray-600 mt-1">
                        Période: {{ $orderLocation->start_date->format('d/m/Y') }} - {{ $orderLocation->end_date->format('d/m/Y') }}
                        ({{ $orderLocation->rental_days }} jours)
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($orderLocation->total_amount, 2) }} €</p>
                    <p class="text-sm text-gray-500">dont {{ number_format($orderLocation->deposit_amount, 2) }}€ de caution</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Résumé -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Résumé de la location</h2>
                
                <!-- Produits loués -->
                <div class="space-y-4 mb-6">
                    @foreach($orderLocation->items as $item)
                    <div class="flex space-x-3 py-3 border-b border-gray-200 last:border-b-0">
                        @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product_name }}</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                <p>{{ $item->quantity }} × {{ number_format($item->daily_price, 2) }}€/jour × {{ $item->total_days }} jours</p>
                                <p class="text-purple-600 font-medium">Caution: {{ number_format($item->deposit_amount, 2) }}€</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">{{ number_format($item->subtotal, 2) }}€</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Détails du total -->
                <div class="space-y-2 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sous-total location</span>
                        <span>{{ number_format($orderLocation->subtotal, 2) }}€</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">TVA ({{ $orderLocation->tax_rate }}%)</span>
                        <span>{{ number_format($orderLocation->tax_amount, 2) }}€</span>
                    </div>
                    <div class="flex justify-between text-sm text-purple-600">
                        <span>Caution (bloquée temporairement)</span>
                        <span>{{ number_format($orderLocation->deposit_amount, 2) }}€</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                        <span>Total à payer</span>
                        <span>{{ number_format($orderLocation->total_amount, 2) }}€</span>
                    </div>
                </div>
                
                <!-- Information sur la caution -->
                <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                    <p class="text-sm text-purple-800">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <strong>Important :</strong> La caution sera bloquée sur votre carte et libérée au retour du matériel en bon état.
                    </p>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Informations de paiement</h2>
                
                <div x-data="rentalPaymentForm()">
                    <form @submit.prevent="processPayment()">
                        
                        <!-- Informations de carte -->
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Numéro de carte</label>
                                <input type="text" 
                                       x-model="cardNumber"
                                       @input="formatCard()"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="19"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Expiration</label>
                                    <input type="text" 
                                           x-model="expiry"
                                           @input="formatExpiry()"
                                           placeholder="MM/AA"
                                           maxlength="5"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">CVC</label>
                                    <input type="text" 
                                           x-model="cvc"
                                           placeholder="123"
                                           maxlength="4"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Nom sur la carte</label>
                                <input type="text" 
                                       x-model="cardName"
                                       placeholder="Jean Dupont"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>
                        
                        <!-- Conditions -->
                        <div class="mb-6">
                            <label class="flex items-start space-x-3">
                                <input type="checkbox" 
                                       x-model="acceptTerms"
                                       class="mt-1 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <span class="text-sm text-gray-700">
                                    J'accepte les <a href="#" class="text-purple-600 hover:underline">conditions générales de location</a> 
                                    et je comprends que la caution sera bloquée sur ma carte jusqu'au retour du matériel.
                                </span>
                            </label>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="space-y-3">
                            <button type="submit" 
                                    :disabled="!acceptTerms || isProcessing"
                                    :class="{ 'opacity-50 cursor-not-allowed': !acceptTerms || isProcessing }"
                                    class="w-full bg-purple-600 text-white py-3 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 font-medium">
                                <span x-show="!isProcessing">Payer {{ number_format($orderLocation->total_amount, 2) }}€</span>
                                <span x-show="isProcessing">Traitement en cours...</span>
                            </button>
                            
                            <a href="{{ route('rental-orders.show', $orderLocation) }}" 
                               class="block w-full text-center py-3 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Annuler et retourner à la commande
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rentalPaymentForm', () => ({
        // État du formulaire
        cardNumber: '',
        expiry: '',
        cvc: '',
        cardName: '{{ auth()->user()->name }}',
        acceptTerms: false,
        isProcessing: false,
        
        // Formatage du numéro de carte
        formatCard() {
            this.cardNumber = this.cardNumber.replace(/\D/g, '').replace(/(\d{4})/g, '$1 ').trim();
        },
        
        // Formatage de l'expiration
        formatExpiry() {
            this.expiry = this.expiry.replace(/\D/g, '').replace(/(\d{2})(\d)/, '$1/$2');
        },
        
        // Traitement du paiement
        async processPayment() {
            if (!this.acceptTerms) {
                alert('Veuillez accepter les conditions générales');
                return;
            }
            
            this.isProcessing = true;
            
            try {
                const response = await fetch('{{ route("payment.stripe-rental.process", $orderLocation) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        card_number: this.cardNumber.replace(/\s/g, ''),
                        expiry: this.expiry,
                        cvc: this.cvc,
                        card_name: this.cardName
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect_url || '{{ route("rental-orders.show", $orderLocation) }}';
                } else {
                    alert(data.message || 'Erreur lors du paiement');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors du traitement du paiement');
            } finally {
                this.isProcessing = false;
            }
        }
    }));
});
</script>
@endpush
@endsection
