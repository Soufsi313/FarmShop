@extends('layouts.app')

@section('title', 'Compte créé avec succès - FarmShop')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-farm-green-50 via-white to-farm-orange-50">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Icône de succès animée -->
        <div class="text-center">
            <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-gradient-to-br from-green-100 to-green-200 mb-6 animate-bounce">
                <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                🎉 Bienvenue sur FarmShop !
            </h1>
            
            <p class="text-xl text-gray-600 mb-2">
                Votre compte a été créé avec succès
            </p>
            <p class="text-lg text-green-600 font-semibold">
                {{ session('user_email') ?? 'Utilisateur' }}
            </p>
        </div>

        <!-- Carte d'information principale -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl border-2 border-green-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h2 class="text-white text-xl font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Prochaines étapes
                </h2>
            </div>
            
            <div class="p-8 space-y-6">
                <!-- Étape 1 : Emails envoyés -->
                <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">📧 Vérifiez vos emails</h3>
                        <p class="text-gray-700 text-sm mb-2">
                            Nous vous avons envoyé <strong>2 emails importants</strong> :
                        </p>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 ml-2">
                            <li><strong>Email de bienvenue</strong> : Récapitulatif de votre compte et fonctionnalités disponibles</li>
                            <li><strong>Email de vérification</strong> : Lien pour activer votre compte (expire dans 60 minutes)</li>
                        </ul>
                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800 flex items-center">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Si vous ne voyez pas les emails, vérifiez votre dossier <strong>spam/courrier indésirable</strong></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Étape 2 : Vérification en mode local -->
                <div class="flex items-start space-x-4 p-4 bg-orange-50 rounded-xl border border-orange-200">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.98-1.333-2.732 0L3.268 16c-.768 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">⚠️ Mode développement local</h3>
                        <p class="text-gray-700 text-sm mb-2">
                            Vous êtes en environnement local. Le lien de vérification d'email pourrait ne pas fonctionner correctement.
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Pas d'inquiétude !</strong> Vous pouvez quand même vous connecter et utiliser FarmShop. La vérification d'email est optionnelle en développement.
                        </p>
                    </div>
                </div>

                <!-- Étape 3 : Connexion -->
                <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-xl border border-green-200">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">🔐 Connectez-vous maintenant</h3>
                        <p class="text-gray-700 text-sm mb-3">
                            Votre compte est prêt ! Vous pouvez vous connecter dès maintenant et commencer à utiliser FarmShop.
                        </p>
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-xl text-base font-medium text-white hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Se connecter à mon compte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ce que vous pouvez faire -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                Découvrez ce que vous pouvez faire avec FarmShop
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🛒</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 text-sm">Acheter des produits</h4>
                        <p class="text-xs text-gray-600">Parcourez notre catalogue fermier</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🚜</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 text-sm">Louer du matériel</h4>
                        <p class="text-xs text-gray-600">Équipement agricole professionnel</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">📦</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 text-sm">Suivre vos commandes</h4>
                        <p class="text-xs text-gray-600">Historique et statuts en temps réel</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">⭐</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 text-sm">Gérer vos favoris</h4>
                        <p class="text-xs text-gray-600">Sauvegardez vos produits préférés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aide -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">💡 Besoin d'aide ?</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Si vous avez des questions ou rencontrez des difficultés, notre équipe est là pour vous aider.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-blue-300 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Nous contacter
                        </a>
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-blue-300 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
