@extends('layouts.app')

@section('title', 'Paiement - Commande #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser le paiement</h1>
            <p class="text-gray-600">Commande #{{ $order->order_number }}</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            
            <!-- Détail de la commande -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Détail de votre commande</h2>
                
                <!-- Articles -->
                <div class="space-y-4 mb-6">
                    @foreach($orderDetails['items'] as $item)
                    <div class="flex justify-between items-start border-b pb-4">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item['product_name'] }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $item['quantity'] }} × {{ number_format($item['unit_price'], 2) }}€
                            </p>
                            
                            @if($item['discount'] > 0)
                                <div class="mt-2">
                                    @foreach($item['applied_offers'] as $offer)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                        {{ $offer['name'] }}: -{{ number_format($offer['discount'], 2) }}€
                                    </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($item['discount'] > 0)
                                <p class="text-sm text-gray-500 line-through">{{ number_format($item['line_total'], 2) }}€</p>
                                <p class="font-medium text-green-600">{{ number_format($item['line_total_after_discount'], 2) }}€</p>
                            @else
                                <p class="font-medium">{{ number_format($item['line_total'], 2) }}€</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Récapitulatif des totaux -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Sous-total</span>
                        <span>{{ number_format($orderDetails['subtotal'], 2) }}€</span>
                    </div>
                    
                    @if($orderDetails['total_discount'] > 0)
                    <div class="flex justify-between text-sm text-green-600">
                        <span>Remises</span>
                        <span>-{{ number_format($orderDetails['total_discount'], 2) }}€</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between text-sm">
                        <span>Total HTC</span>
                        <span>{{ number_format($orderDetails['total_htc'], 2) }}€</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span>TVA ({{ number_format($orderDetails['tax_rate'] * 100, 0) }}%)</span>
                        <span>{{ number_format($orderDetails['tax_amount'], 2) }}€</span>
                    </div>
                    
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total TTC</span>
                        <span>{{ number_format($orderDetails['total_ttc'], 2) }}€</span>
                    </div>
                </div>

                <!-- Adresses -->
                <div class="mt-6 pt-6 border-t">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Adresse de facturation</h3>
                            <div class="text-sm text-gray-600">
                                <p>{{ $order->billing_address['name'] }}</p>
                                <p>{{ $order->billing_address['address'] }}</p>
                                <p>{{ $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</p>
                                <p>{{ $order->billing_address['country'] }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Adresse de livraison</h3>
                            <div class="text-sm text-gray-600">
                                <p>{{ $order->shipping_address['name'] }}</p>
                                <p>{{ $order->shipping_address['address'] }}</p>
                                <p>{{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</p>
                                <p>{{ $order->shipping_address['country'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement Stripe -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de paiement</h2>
                
                <form id="payment-form">
                    @csrf
                    
                    <!-- Élément Stripe Card -->
                    <div id="card-element" class="p-3 border border-gray-300 rounded-md mb-4">
                        <!-- Stripe Elements s'insérera ici -->
                    </div>
                    
                    <!-- Affichage des erreurs -->
                    <div id="card-errors" role="alert" class="text-red-600 text-sm mb-4"></div>
                    
                    <!-- Bouton de paiement -->
                    <button type="submit" id="submit-payment" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <span id="button-text">Payer {{ number_format($orderDetails['total_ttc'], 2) }}€</span>
                        <span id="spinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement...
                        </span>
                    </button>
                </form>

                <!-- Retour -->
                <a href="{{ route('cart.index') }}" 
                   class="w-full mt-3 block text-center text-gray-600 hover:text-gray-800 text-sm">
                    ← Retour au panier
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts Stripe -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();

    // Créer l'élément carte
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
        },
    });

    // Monter l'élément carte
    cardElement.mount('#card-element');

    // Gérer les erreurs en temps réel
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Gérer la soumission du formulaire
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const submitButton = document.getElementById('submit-payment');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        // Désactiver le bouton et afficher le spinner
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');

        try {
            // Créer l'intention de paiement
            const response = await fetch('{{ route("payment.process", ["order" => $order->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Erreur lors du traitement du paiement');
            }

            // Confirmer le paiement avec Stripe
            const result = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ $order->billing_address["name"] }}',
                        email: '{{ auth()->user()->email }}',
                        address: {
                            line1: '{{ $order->billing_address["address"] }}',
                            city: '{{ $order->billing_address["city"] }}',
                            postal_code: '{{ $order->billing_address["postal_code"] }}',
                            country: '{{ $order->billing_address["country"] === "France" ? "FR" : $order->billing_address["country"] }}',
                        }
                    }
                }
            });

            if (result.error) {
                // Afficher l'erreur à l'utilisateur
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                
                // Réactiver le bouton
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else {
                // Le paiement a réussi
                window.location.href = '{{ route("payment.success", ["order" => $order->id]) }}';
            }

        } catch (error) {
            console.error('Erreur:', error);
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message || 'Une erreur est survenue lors du paiement';
            
            // Réactiver le bouton
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });
});
</script>
@endsection
