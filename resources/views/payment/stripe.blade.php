@extends('layouts.app')

@section('title', {{ __("app.ecommerce.payment") }})

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h1 class="text-2xl font-bold">Paiement - Commande #{{ $order->order_number }}</h1>
            <p class="text-gray-600">Total: {{ number_format($order->total_amount, 2) }} €</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Résumé -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Résumé de la commande</h2>
                
                @foreach($order->items as $item)
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <h3 class="font-medium">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} €</p>
                    </div>
                    <span class="font-medium">{{ number_format($item->unit_price * $item->quantity, 2) }} €</span>
                </div>
                @endforeach
                
                <div class="pt-4 border-t mt-4">
                    <div class="flex justify-between font-bold text-lg">
                        <span>{{ __("app.ecommerce.total") }}</span>
                        <span>{{ number_format($order->total_amount, 2) }} €</span>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __("app.ecommerce.payment") }}</h2>
                
                <div x-data="paymentForm()">
                    <form @submit.prevent="processPayment()">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Numéro de carte</label>
                            <input type="text" 
                                   x-model="cardNumber"
                                   @input="formatCard()"
                                   placeholder="1234 5678 9012 3456"
                                   class="w-full border rounded px-3 py-2">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Expiration</label>
                                <input type="text" 
                                       x-model="expiry"
                                       @input="formatExpiry()"
                                       placeholder="MM/YY"
                                       class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">CVC</label>
                                <input type="text" 
                                       x-model="cvc"
                                       @input="formatCvc()"
                                       placeholder="123"
                                       class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="acceptTerms" class="mr-2">
                                <span class="text-sm">J'accepte les conditions générales</span>
                            </label>
                        </div>
                        
                        <div x-show="error" x-text="error" class="text-red-600 mb-4"></div>
                        
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium disabled:bg-gray-400">
                            <span x-show="!loading">Payer {{ number_format($order->total_amount, 2) }} €</span>
                            <span x-show="loading">Traitement...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function paymentForm() {
    return {
        cardNumber: '',
        expiry: '',
        cvc: '',
        acceptTerms: false,
        loading: false,
        error: '',
        
        formatCard() {
            let value = this.cardNumber.replace(/\s/g, '').replace(/[^0-9]/g, '');
            this.cardNumber = value.match(/.{1,4}/g)?.join(' ') || value;
            if (this.cardNumber.length > 19) this.cardNumber = this.cardNumber.slice(0, 19);
        },
        
        formatExpiry() {
            let value = this.expiry.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.expiry = value;
            if (this.expiry.length > 5) this.expiry = this.expiry.slice(0, 5);
        },
        
        formatCvc() {
            this.cvc = this.cvc.replace(/[^0-9]/g, '');
            if (this.cvc.length > 4) this.cvc = this.cvc.slice(0, 4);
        },
        
        async processPayment() {
            this.error = '';
            
            if (!this.acceptTerms) {
                this.error = 'Veuillez accepter les conditions générales';
                return;
            }
            
            if (!this.cardNumber || !this.expiry || !this.cvc) {
                this.error = 'Veuillez remplir tous les champs';
                return;
            }
            
            this.loading = true;
            
            try {
                console.log('🚀 Début du paiement');
                
                // Appel API PaymentIntent
                const response = await fetch('/payment/{{ $order->id }}/stripe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                console.log('📄 Résultat:', result);
                
                if (result.success) {
                    console.log('✅ Paiement réussi');
                    // Redirection vers confirmation
                    window.location.href = '/orders/{{ $order->id }}/confirmation';
                } else {
                    throw new Error(result.message || 'Erreur de paiement');
                }
                
            } catch (error) {
                console.error('❌ Erreur:', error);
                this.error = error.message || 'Erreur lors du paiement';
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
