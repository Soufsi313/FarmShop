@extends('layouts.app')

@section('title', 'Compte supprimé - FarmShop')
@section('page-title', 'Suppression confirmée')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-50 to-orange-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Animation de suppression -->
            <div class="mx-auto h-32 w-32 bg-red-100 rounded-full flex items-center justify-center mb-8 animate-pulse">
                <svg class="h-16 w-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                🗑️ Compte supprimé avec succès
            </h1>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">✅ Suppression confirmée</h2>
                
                <div class="text-left space-y-3">
                    <p class="text-gray-600">
                        <span class="font-semibold">📧 Email de confirmation :</span> 
                        Un email récapitulatif vous a été envoyé
                    </p>
                    
                    <p class="text-gray-600">
                        <span class="font-semibold">📦 Données GDPR :</span> 
                        Téléchargement automatique en cours...
                    </p>
                    
                    <p class="text-gray-600">
                        <span class="font-semibold">🕐 Date de suppression :</span> 
                        {{ now()->format('d/m/Y à H:i:s') }}
                    </p>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">📋 Récapitulatif</h3>
                <ul class="text-sm text-yellow-700 text-left space-y-1">
                    <li>✅ Données personnelles supprimées</li>
                    <li>✅ Historique des commandes effacé</li>
                    <li>✅ Messages et communications supprimés</li>
                    <li>✅ Abonnement newsletter annulé</li>
                    <li>✅ Archive GDPR générée et envoyée</li>
                </ul>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">💾 Sauvegarde GDPR</h3>
                <p class="text-sm text-blue-700">
                    Conformément au RGPD, toutes vos données ont été compilées dans un fichier ZIP qui sera téléchargé automatiquement. 
                    Ce fichier contient vos informations personnelles, historique de navigation et données de commandes au format PDF.
                </p>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route('home') }}" 
                   class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-block">
                    🏠 Retourner à l'accueil
                </a>
                
                <a href="{{ route('register') }}" 
                   class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-block">
                    👤 Créer un nouveau compte
                </a>
            </div>
            
            <div class="mt-8 text-sm text-gray-500">
                <p>Merci d'avoir utilisé FarmShop</p>
                <p>Nous espérons vous revoir bientôt ! 🌱</p>
            </div>
        </div>
    </div>
</div>

<script>
// Déclencher le téléchargement automatique après 2 secondes
setTimeout(function() {
    window.location.href = "{{ route('users.download-data') }}";
}, 2000);
</script>
@endsection