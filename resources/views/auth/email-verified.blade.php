@extends('layouts.app')

@section('title', 'Email vérifié avec succès')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-farm-green-50 to-farm-orange-50">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Icône de succès -->
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-4">
                🎉 Email vérifié avec succès !
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Votre adresse email a été confirmée. Votre compte FarmShop est maintenant actif !
            </p>
        </div>

        <div class="bg-white/80 backdrop-blur-sm p-8 rounded-lg shadow-xl border border-farm-green-200">
            <div class="text-center space-y-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-center space-x-2 text-green-700">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Compte activé</span>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-2 text-green-700">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <span class="font-medium">Email confirmé</span>
                    </div>
                    
                    <div class="flex items-center justify-center space-x-2 text-green-700">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">Accès complet débloqué</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <p class="text-sm text-gray-600 mb-6">
                        Vous pouvez maintenant profiter de toutes les fonctionnalités de FarmShop :
                    </p>
                    
                    <div class="grid grid-cols-2 gap-3 text-xs text-gray-600 mb-6">
                        <div class="flex items-center space-x-1">
                            <span>🛒</span>
                            <span>Commander des produits</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span>📦</span>
                            <span>Louer du matériel</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span>❤️</span>
                            <span>Liste de souhaits</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span>📧</span>
                            <span>Newsletter</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('home') }}" 
                           class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-farm-green-600 hover:bg-farm-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-green-500 transition-colors">
                            🏠 Découvrir FarmShop
                        </a>
                        
                        <a href="{{ route('categories.index') }}" 
                           class="w-full flex justify-center py-2 px-4 border border-farm-green-300 rounded-md text-sm font-medium text-farm-green-700 bg-white hover:bg-farm-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-green-500 transition-colors">
                            🛍️ Voir nos produits
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-500">
                Merci de faire confiance à FarmShop pour vos achats agricoles !
            </p>
        </div>
    </div>
</div>
@endsection
