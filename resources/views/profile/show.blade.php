@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-green-100 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6">
                <h1 class="text-3xl font-bold text-white">Mon Profil</h1>
                <p class="text-green-100 mt-2">Gérez vos informations personnelles</p>
            </div>
        </div>

        <!-- Notifications -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium mb-2">Des erreurs ont été détectées :</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Section principale -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Photo de profil -->
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Photo de profil
                    </h2>
                    
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            @if($user->profile_photo_path)
                                <img class="h-24 w-24 rounded-full object-cover border-4 border-green-100 shadow-lg" 
                                     src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                     alt="Photo de profil">
                            @else
                                <div class="h-24 w-24 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center border-4 border-green-100 shadow-lg">
                                    <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <form action="{{ route('profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Changer la photo de profil
                                    </label>
                                    <input type="file" name="photo" id="photo" accept="image/*" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors">
                                </div>
                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Télécharger
                                    </button>
                                    
                                    @if($user->profile_photo_path)
                                        <form action="{{ route('profile.photo.delete') }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')"
                                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Informations personnelles -->
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Informations personnelles
                    </h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('name') border-red-300 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="biography" class="block text-sm font-medium text-gray-700 mb-2">
                                Biographie
                            </label>
                            <textarea name="biography" id="biography" rows="4" 
                                      placeholder="Parlez-nous un peu de vous..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none @error('biography') border-red-300 @enderror">{{ old('biography', $user->biography) }}</textarea>
                            @error('biography')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum 500 caractères</p>
                        </div>
                        
                        <div class="flex justify-end pt-4">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Contact Admin -->
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"></path>
                        </svg>
                        Besoin d'aide ?
                    </h3>
                    <p class="text-gray-600 mb-4 text-sm">
                        Une question ? Un problème ? Contactez notre équipe d'administration.
                    </p>
                    <button type="button" onclick="openContactModal()" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Envoyer un message
                    </button>
                </div>

                <!-- Statistiques rapides -->
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Votre activité
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Membre depuis</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">Dernière connexion</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Statut</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Actif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26c.34.18.74.18 1.08 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Newsletter
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                @if($user->is_newsletter_subscribed)
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700">
                                    @if($user->is_newsletter_subscribed)
                                        Vous êtes abonné(e) à notre newsletter depuis le {{ $user->newsletter_subscribed_at ? $user->newsletter_subscribed_at->format('d/m/Y') : 'N/A' }}.
                                    @else
                                        Recevez nos dernières actualités, produits et offres spéciales directement dans votre boîte mail.
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <form action="{{ route('profile.newsletter.toggle') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 {{ $user->is_newsletter_subscribed ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-600 hover:bg-green-700 text-white' }} rounded-lg transition-colors text-sm font-medium">
                                @if($user->is_newsletter_subscribed)
                                    Se désabonner de la newsletter
                                @else
                                    S'abonner à la newsletter
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Zone de danger -->
                <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Gestion des données
                    </h3>
                    
                    <!-- Téléchargement des données -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">📦 Télécharger mes données</h4>
                        <p class="text-sm text-blue-700 mb-3">
                            Conformément au RGPD, vous pouvez télécharger toutes vos données personnelles au format ZIP.
                        </p>
                        <a href="{{ route('profile.data.download') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger mes données
                        </a>
                    </div>
                    
                    <!-- Suppression de compte -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-medium text-red-800 mb-2">⚠️ Suppression de compte</h4>
                        <p class="text-sm text-red-700">
                            <strong>Attention :</strong> La suppression de votre compte est irréversible. Toutes vos données seront définitivement effacées.
                        </p>
                    </div>
                    
                    <button type="button" onclick="openDeleteAccountModal()" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium">
                        Supprimer mon compte
                    </button>
                </div>

                <!-- Liens rapides -->
                <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        Liens utiles
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('user.messages.index') }}" 
                           class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                            💬 Mes messages
                        </a>
                        <a href="{{ route('products.index') }}" 
                           class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                            🛒 Boutique
                        </a>
                        <a href="{{ url('/') }}" 
                           class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                            🏠 Accueil
                        </a>
                        <a href="{{ route('cookies.policy') }}" 
                           class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                            🍪 Politique de cookies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal suppression de compte -->
<div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Supprimer mon compte</h3>
                <button type="button" onclick="closeDeleteAccountModal()" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="deleteAccountForm" action="{{ route('profile.account.delete') }}" method="POST" class="p-6">
            @csrf
            @method('DELETE')
            <div class="space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-red-800">Cette action est irréversible</h4>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Votre compte sera définitivement supprimé</li>
                                    <li>Toutes vos données personnelles seront effacées</li>
                                    <li>Vos messages et historique seront perdus</li>
                                    <li>Vous ne pourrez plus vous connecter</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">💡 Recommandation</h4>
                            <p class="mt-1 text-sm text-blue-700">
                                Nous vous recommandons de <a href="{{ route('profile.data.download') }}" class="underline font-medium">télécharger vos données</a> avant de supprimer votre compte.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmez avec votre mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="delete_password" required
                           placeholder="Entrez votre mot de passe actuel"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="confirmation" id="delete_confirmation" required
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="delete_confirmation" class="ml-2 block text-sm text-gray-700">
                        Je comprends que cette action est irréversible et je souhaite supprimer définitivement mon compte
                    </label>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeDeleteAccountModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Supprimer mon compte
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Contact Admin -->
<div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Contacter l'administration</h3>
                <button type="button" onclick="closeContactModal()" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="contactForm" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Sujet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" required
                           placeholder="Résumez votre demande en quelques mots"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="5" required
                              placeholder="Décrivez votre demande ou problème..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeContactModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        Envoyer le message
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openContactModal() {
    document.getElementById('contactModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('contactForm').reset();
}

function openDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('deleteAccountForm').reset();
}

// Gérer la soumission du formulaire de contact
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Envoi en cours...';
    
    fetch('{{ route("contact.admin") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeContactModal();
            // Afficher une notification de succès
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl shadow-lg z-50';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Votre message a été envoyé avec succès !
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        } else {
            alert('Une erreur est survenue lors de l\'envoi du message.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de l\'envoi du message.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});

// Fermer les modals en cliquant à l'extérieur
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactModal();
    }
});

document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteAccountModal();
    }
});

// Fermer les modals avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeContactModal();
        closeDeleteAccountModal();
    }
});

// Validation supplémentaire pour le formulaire de suppression
document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
    const password = document.getElementById('delete_password').value;
    const confirmation = document.getElementById('delete_confirmation').checked;
    
    if (!password || !confirmation) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs et cocher la case de confirmation.');
        return false;
    }
    
    // Confirmation finale
    if (!confirm('Êtes-vous absolument certain(e) de vouloir supprimer votre compte ? Cette action est irréversible.')) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection
