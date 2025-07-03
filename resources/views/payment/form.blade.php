@extends('layouts.public')

@section('title', 'Paiement - FarmShop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finaliser votre commande</h1>
            <p class="text-gray-600">Paiement sécurisé avec Stripe</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Résumé de commande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Résumé de votre commande</h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                            @if($item->product->main_image)
                                <img src="{{ $item->product->main_image_url }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">Quantité: {{ $item->quantity }}</p>
                                
                                @if($item->product->hasActiveSpecialOffer())
                                    @php
                                        $offer = $item->product->getActiveSpecialOffer();
                                        $originalPrice = $item->product->price * $item->quantity;
                                        $discountedPrice = $item->total_price;
                                    @endphp
                                    <div class="text-sm">
                                        <span class="text-red-500 line-through">{{ number_format($originalPrice, 2) }}€</span>
                                        <span class="text-green-600 font-medium ml-2">{{ number_format($discountedPrice, 2) }}€</span>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">
                                            -{{ $offer->discount_percentage }}%
                                        </span>
                                    </div>
                                @else
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($item->total_price, 2) }}€</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Sous-total produits:</span>
                            <span>{{ number_format($subtotal, 2) }}€</span>
                        </div>
                        
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Frais de livraison:</span>
                            <span>
                                @if($shippingCost > 0)
                                    {{ number_format($shippingCost, 2) }}€
                                @else
                                    <span class="text-green-600 font-medium">Gratuit</span>
                                @endif
                            </span>
                        </div>
                        
                        @if($shippingCost == 0 && $subtotal < 25)
                            <div class="text-xs text-green-600">
                                🎉 Livraison gratuite car votre commande dépasse 25€
                            </div>
                        @elseif($shippingCost > 0)
                            <div class="text-xs text-gray-500">
                                💡 Livraison gratuite dès 25€ d'achat
                            </div>
                        @endif
                        
                        @if($totalSavings > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Économies totales:</span>
                                <span>-{{ number_format($totalSavings, 2) }}€</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-between text-lg font-bold text-gray-900 mt-3 pt-3 border-t border-gray-200">
                        <span>Total à payer:</span>
                        <span>{{ number_format($total, 2) }}€</span>
                    </div>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de paiement</h2>
                
                <!-- Messages d'erreur/succès -->
                <div id="payment-messages" class="mb-4 hidden">
                    <div id="payment-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden">
                        <span id="error-message"></span>
                    </div>
                    <div id="payment-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden">
                        <span id="success-message">Paiement réussi ! Redirection en cours...</span>
                    </div>
                </div>

                <form id="payment-form">
                    @csrf
                    
                    <!-- Informations de facturation -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Adresse de facturation</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="billing-name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                                <input type="text" id="billing-name" name="billing_name" 
                                       value="{{ auth()->user()->name }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="billing-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="billing-email" name="billing_email" 
                                       value="{{ auth()->user()->email }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="billing-city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                    <input type="text" id="billing-city" name="billing_city" 
                                           placeholder="Bruxelles, Liège, Gand..."
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           required>
                                </div>
                                <div>
                                    <label for="billing-postal-code" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                    <input type="text" id="billing-postal-code" name="billing_postal_code" 
                                           placeholder="1000, 4000, 9000..."
                                           pattern="[0-9]{4}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           required>
                                </div>
                            </div>
                            <div>
                                <label for="billing-address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" id="billing-address" name="billing_address" 
                                       placeholder="Rue, numéro, boîte..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Élément Stripe Card -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Informations de carte</h3>
                        <div id="card-element" class="p-3 border border-gray-300 rounded-md bg-white">
                            <!-- Stripe Elements injectera le formulaire de carte ici -->
                        </div>
                        <div id="card-errors" role="alert" class="text-red-600 text-sm mt-2"></div>
                    </div>

                    <!-- Bouton de paiement -->
                    <button type="submit" id="submit-payment" 
                            class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-medium">
                        <span id="button-text">Payer {{ number_format($total, 2) }}€</span>
                        <span id="spinner" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i> Traitement...
                        </span>
                    </button>
                </form>

                <!-- Cartes de test (en mode développement) -->
                @if(app()->environment('local'))
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                        <h4 class="text-sm font-medium text-yellow-800 mb-2">🧪 Mode Test - Cartes de test disponibles:</h4>
                        <div class="text-xs text-yellow-700 space-y-1">
                            <p><strong>Succès:</strong> 4242 4242 4242 4242</p>
                            <p><strong>Refusée:</strong> 4000 0000 0000 0002</p>
                            <p><strong>Fonds insuffisants:</strong> 4000 0000 0000 9995</p>
                            <p><strong>CVC incorrect:</strong> 4000 0000 0000 0127</p>
                            <p class="text-xs">Utilisez n'importe quelle date future et CVC pour les tests.</p>
                        </div>
                        <a href="{{ route('payment.test-cards') }}" class="text-yellow-800 hover:text-yellow-900 text-sm underline">
                            Voir toutes les cartes de test
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration Stripe
    const stripe = Stripe('{{ config("stripe.publishable_key") }}');
    const elements = stripe.elements();

    // Style pour les éléments Stripe
    const style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Créer l'élément de carte avec configuration belge optimisée
    const cardElement = elements.create('card', {
        style: style,
        hidePostalCode: true, // On cache le code postal Stripe car on utilise notre propre champ
        iconStyle: 'default'
    });
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

    // Validation en temps réel du code postal belge
    const postalCodeInput = document.getElementById('billing-postal-code');
    postalCodeInput.addEventListener('input', function(event) {
        const value = event.target.value;
        const isValid = /^[0-9]{0,4}$/.test(value);
        
        if (!isValid) {
            event.target.value = value.replace(/[^0-9]/g, '').slice(0, 4);
        }
        
        // Changer la couleur de la bordure selon la validité
        if (value.length === 4 && /^[0-9]{4}$/.test(value)) {
            event.target.classList.remove('border-red-300');
            event.target.classList.add('border-green-300');
        } else if (value.length > 0) {
            event.target.classList.remove('border-green-300');
            event.target.classList.add('border-red-300');
        } else {
            event.target.classList.remove('border-red-300', 'border-green-300');
        }
    });

    // Gérer la soumission du formulaire
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        const submitButton = document.getElementById('submit-payment');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        
        // Validation du code postal belge
        const postalCode = document.getElementById('billing-postal-code').value;
        if (!postalCode.match(/^[0-9]{4}$/)) {
            showError('Veuillez entrer un code postal belge valide (4 chiffres).');
            return;
        }
        
        // Validation des autres champs
        const requiredFields = ['billing-name', 'billing-email', 'billing-city', 'billing-address'];
        for (let fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            if (!field.value.trim()) {
                showError('Veuillez remplir tous les champs obligatoires.');
                field.focus();
                return;
            }
        }
        
        // Désactiver le bouton et afficher le spinner
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');

        try {
            // Créer le PaymentIntent
            const response = await fetch('{{ route("payment.create-intent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Erreur lors de la création du paiement');
            }

            // Confirmer le paiement avec Stripe
            const {error, paymentIntent} = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('billing-name').value,
                        email: document.getElementById('billing-email').value,
                        address: {
                            country: 'BE', // Code pays pour la Belgique
                            city: document.getElementById('billing-city').value,
                            line1: document.getElementById('billing-address').value,
                            postal_code: document.getElementById('billing-postal-code').value
                        }
                    }
                }
            });

            if (error) {
                showError(error.message);
            } else if (paymentIntent.status === 'succeeded') {
                // Confirmer le paiement côté serveur
                const confirmResponse = await fetch('{{ route("payment.confirm") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id
                    })
                });

                const confirmData = await confirmResponse.json();
                
                if (confirmData.success) {
                    showSuccess();
                    setTimeout(() => {
                        window.location.href = confirmData.redirect_url;
                    }, 2000);
                } else {
                    throw new Error(confirmData.error || 'Erreur lors de la confirmation');
                }
            }
        } catch (error) {
            console.error('Payment error:', error);
            showError(error.message);
        } finally {
            // Réactiver le bouton
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });

    function showError(message) {
        const messagesDiv = document.getElementById('payment-messages');
        const errorDiv = document.getElementById('payment-error');
        const errorMessage = document.getElementById('error-message');
        const successDiv = document.getElementById('payment-success');
        
        messagesDiv.classList.remove('hidden');
        errorDiv.classList.remove('hidden');
        successDiv.classList.add('hidden');
        errorMessage.textContent = message;
        
        // Scroll vers le message
        messagesDiv.scrollIntoView({ behavior: 'smooth' });
    }

    function showSuccess() {
        const messagesDiv = document.getElementById('payment-messages');
        const errorDiv = document.getElementById('payment-error');
        const successDiv = document.getElementById('payment-success');
        
        messagesDiv.classList.remove('hidden');
        errorDiv.classList.add('hidden');
        successDiv.classList.remove('hidden');
        
        // Scroll vers le message
        messagesDiv.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endsection
