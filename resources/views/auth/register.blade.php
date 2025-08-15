@extends('layouts.app')

@section('title', '{{ smart_translate("Inscription") }} - FarmShop')
@section('description', 'Créez votre compte FarmShop pour accéder à nos produits agricoles et services de location.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-farm-green-50 via-farm-orange-50 to-farm-green-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-farm-green-800">FarmShop</h1>
            <p class="mt-2 text-sm text-farm-green-600">Matériel Agricole Belge</p>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-farm-green-800">
            Créez votre compte
        </h2>
        <p class="mt-2 text-center text-sm text-farm-green-600">
            Ou
            <a href="{{ route('login') }}" class="font-medium text-farm-orange-600 hover:text-farm-orange-700 transition-colors">
                connectez-vous à votre compte existant
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white/80 backdrop-blur-sm py-8 px-4 shadow-xl border border-farm-green-200 rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- {{ smart_translate("Nom") }} d'utilisateur -->
                <div>
                    <label for="username" class="block text-sm font-medium text-farm-green-700">
                        {{ smart_translate("Nom") }} d'utilisateur
                    </label>
                    <div class="mt-1">
                        <input id="username" 
                               name="username" 
                               type="text" 
                               autocomplete="username" 
                               required 
                               value="{{ old('username') }}"
                               placeholder="Ex: jdupont"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-1 text-xs text-farm-green-500">
                        Identifiant unique pour votre compte.
                    </p>
                </div>

                <!-- {{ smart_translate("Nom") }} complet -->
                <div>
                    <label for="name" class="block text-sm font-medium text-farm-green-700">
                        {{ smart_translate("Nom") }} complet (optionnel)
                    </label>
                    <div class="mt-1">
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               value="{{ old('name') }}"
                               placeholder="Ex: Jean Dupont"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- {{ smart_translate("Email") }} -->
                <div>
                    <label for="email" class="block text-sm font-medium text-farm-green-700">
                        Adresse email
                    </label>
                    <div class="mt-1">
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="votre@email.com"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- {{ smart_translate("Mot de passe") }} -->
                <div>
                    <label for="password" class="block text-sm font-medium text-farm-green-700">
                        {{ smart_translate("Mot de passe") }}
                    </label>
                    <div class="mt-1">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               placeholder="Minimum 8 caractères"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-1 text-xs text-farm-green-500">
                        Le mot de passe doit contenir au moins 8 caractères.
                    </p>
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-farm-green-700">
                        {{ smart_translate("Confirmer le mot de passe") }}
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               placeholder="Retapez votre mot de passe"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80">
                    </div>
                </div>

                <!-- Conditions d'utilisation -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" 
                               name="terms" 
                               type="checkbox" 
                               required
                               class="h-4 w-4 text-farm-green-600 focus:ring-farm-green-500 border-farm-green-300 rounded">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="terms" class="text-farm-green-800">
                            J'accepte les 
                            <a href="#" class="text-farm-orange-600 hover:text-farm-orange-700 underline transition-colors">
                                conditions d'utilisation
                            </a>
                            et la 
                            <a href="{{ route('privacy') }}" class="text-farm-orange-600 hover:text-farm-orange-700 underline transition-colors">
                                politique de confidentialité
                            </a>
                        </label>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="newsletter" 
                               name="newsletter" 
                               type="checkbox"
                               class="h-4 w-4 text-farm-green-600 focus:ring-farm-green-500 border-farm-green-300 rounded">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="newsletter" class="text-farm-green-800">
                            Je souhaite recevoir les actualités et offres spéciales par email
                        </label>
                    </div>
                </div>

                <!-- Bouton d'inscription -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-farm-green-600 hover:bg-farm-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-green-500 transition-colors shadow-lg">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-farm-green-500 group-hover:text-farm-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                            </svg>
                        </span>
                        Créer mon compte
                    </button>
                </div>
            </form>

            <!-- Séparateur -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-farm-orange-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white/80 text-farm-orange-600">Déjà client ?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-farm-orange-500 rounded-md shadow-sm text-sm font-medium text-farm-orange-600 bg-white/80 hover:bg-farm-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-orange-500 transition-colors">
                        {{ smart_translate("Se connecter") }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Lien retour accueil -->
        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="text-sm text-farm-green-600 hover:text-farm-orange-600 transition-colors">
                ← Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
