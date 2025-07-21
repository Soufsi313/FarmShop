@extends('layouts.app')

@section('title', 'Message envoyé - FarmShop')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-100 via-orange-50 to-green-100 py-12">
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Icône de succès -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Message principal -->
            <h1 class="text-3xl font-bold text-green-800 mb-4">
                ✅ Message envoyé avec succès !
            </h1>
            
            <p class="text-lg text-gray-600 mb-6">
                Merci pour votre message. Nous avons bien reçu votre demande et nous vous répondrons dans les plus brefs délais.
            </p>

            <!-- Informations sur la demande -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Résumé de votre demande</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Référence :</span>
                        <span class="font-mono font-medium" id="reference">MSG-XXXXXX</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date d'envoi :</span>
                        <span class="font-medium">{{ now()->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Temps de réponse estimé :</span>
                        <span class="font-medium text-green-600">24-48 heures</span>
                    </div>
                </div>
            </div>

            <!-- Informations importantes -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8 text-left">
                <h4 class="font-medium text-blue-800 mb-2">📧 Confirmation par email</h4>
                <p class="text-blue-700 text-sm">
                    Un email de confirmation a été envoyé à votre adresse email. 
                    Vérifiez également votre dossier spam si vous ne le recevez pas dans quelques minutes.
                </p>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        🏠 Retour à l'accueil
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center justify-center border border-green-600 text-green-600 hover:bg-green-50 px-6 py-3 rounded-lg font-medium transition-colors">
                        🛒 Voir nos produits
                    </a>
                </div>
                
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm transition-colors">
                    ← Envoyer un autre message
                </a>
            </div>

            <!-- Informations de contact urgentes -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h4 class="font-medium text-gray-800 mb-3">🚨 Besoin d'une réponse urgente ?</h4>
                <p class="text-gray-600 text-sm mb-3">
                    Pour les demandes urgentes, n'hésitez pas à nous appeler directement :
                </p>
                <p class="font-medium text-green-600">
                    📞 +32 2 123 45 67
                </p>
                <p class="text-gray-500 text-xs mt-1">
                    Lun-Ven : 8h-18h | Sam : 9h-12h
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer la référence depuis le localStorage si disponible
    const reference = localStorage.getItem('contact_reference');
    if (reference) {
        document.getElementById('reference').textContent = reference;
        // Nettoyer le localStorage
        localStorage.removeItem('contact_reference');
    }
});
</script>
@endsection
