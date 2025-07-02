@extends('layouts.public')

@section('title', 'Cartes de Test Stripe - FarmShop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">🧪 Cartes de Test Stripe</h1>
            <p class="text-gray-600">Utilisez ces cartes pour tester les paiements en mode développement</p>
            <div class="mt-4">
                <a href="{{ route('payment.form') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au paiement
                </a>
            </div>
        </div>

        <!-- Informations générales -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-blue-900 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Informations importantes
            </h2>
            <ul class="text-blue-800 space-y-2">
                <li>• Utilisez <strong>n'importe quelle date d'expiration future</strong> (ex: 12/25, 01/30)</li>
                <li>• Utilisez <strong>n'importe quel CVC à 3 chiffres</strong> (ex: 123, 456)</li>
                <li>• Utilisez <strong>n'importe quel code postal valide</strong></li>
                <li>• Ces cartes ne fonctionnent qu'en <strong>mode test</strong></li>
            </ul>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Cartes de base -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-credit-card mr-2 text-green-600"></i>
                    Cartes de Base
                </h2>
                
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-green-600">✅ Paiement Réussi</h3>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">VISA</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['visa_success'] ?? '4242424242424242' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Carte pour tester les paiements réussis</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-red-600">❌ Carte Refusée</h3>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">VISA</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['visa_declined'] ?? '4000000000000002' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Déclinée avec un code de refus générique</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-orange-600">💳 Fonds Insuffisants</h3>
                            <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">VISA</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['visa_insufficient_funds'] ?? '4000000000009995' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Refusée pour fonds insuffisants</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-blue-600">✅ Mastercard</h3>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">MASTERCARD</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['mastercard_success'] ?? '5555555555554444' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Mastercard pour tests réussis</p>
                    </div>
                </div>
            </div>

            <!-- Cartes d'erreur spécifiques -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2 text-orange-600"></i>
                    Tests d'Erreurs Spécifiques
                </h2>
                
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-red-600">🕐 Carte Expirée</h3>
                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">VISA</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['visa_expired'] ?? '4000000000000069' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Refusée car expirée</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-purple-600">🔢 CVC Incorrect</h3>
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">VISA</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['visa_incorrect_cvc'] ?? '4000000000000127' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Refusée pour CVC incorrect</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-green-600">💳 American Express</h3>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">AMEX</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['american_express'] ?? '378282246310005' }}</code>
                        <p class="text-sm text-gray-600 mt-2">American Express pour tests</p>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-medium text-blue-600">💳 Carte de Débit</h3>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">DEBIT</span>
                        </div>
                        <code class="text-lg font-mono bg-gray-100 px-3 py-2 rounded block">{{ $testCards['mastercard_debit'] ?? '5200828282828210' }}</code>
                        <p class="text-sm text-gray-600 mt-2">Carte de débit Mastercard</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions détaillées -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-book mr-2 text-indigo-600"></i>
                Instructions d'utilisation
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-3">🔧 Comment tester :</h3>
                    <ol class="text-gray-700 space-y-2 list-decimal list-inside">
                        <li>Copiez le numéro de carte souhaité</li>
                        <li>Collez-le dans le champ "Numéro de carte"</li>
                        <li>Utilisez une date d'expiration future (ex: 12/25)</li>
                        <li>Utilisez n'importe quel CVC (ex: 123)</li>
                        <li>Cliquez sur "Payer" pour voir le résultat</li>
                    </ol>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-900 mb-3">📊 Résultats attendus :</h3>
                    <ul class="text-gray-700 space-y-2">
                        <li><span class="text-green-600">✅</span> <strong>Succès :</strong> Commande créée et confirmée</li>
                        <li><span class="text-red-600">❌</span> <strong>Refus :</strong> Message d'erreur affiché</li>
                        <li><span class="text-orange-600">⚠️</span> <strong>Erreur :</strong> Détails spécifiques du problème</li>
                        <li><span class="text-blue-600">ℹ️</span> <strong>3D Secure :</strong> Authentification supplémentaire</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Liens utiles -->
        <div class="mt-8 text-center">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-medium text-gray-900 mb-3">📖 Documentation</h3>
                <div class="space-y-2">
                    <a href="https://stripe.com/docs/testing" target="_blank" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 mx-2">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Documentation officielle Stripe
                    </a>
                    <a href="https://stripe.com/docs/testing#cards" target="_blank" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 mx-2">
                        <i class="fas fa-credit-card mr-1"></i>
                        Cartes de test complètes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
