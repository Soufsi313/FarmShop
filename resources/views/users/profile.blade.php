@extends('layouts.app')

@section('title', 'Mon Profil - FarmShop')
@section('description', 'Gérez vos informations personnelles et paramètres de compte FarmShop.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-green-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête du profil -->
        <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-green-200 mb-8">
            <div class="px-6 py-8">
                <div class="flex items-center space-x-6">
                    <!-- Avatar -->
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($user->name ?: $user->username, 0, 1) }}
                    </div>
                    
                    <!-- Informations principales -->
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-green-800">
                            {{ $user->name ?: $user->username }}
                        </h1>
                        <p class="text-blue-600 text-lg">{{ $user->email }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $user->role }}
                            </span>
                            @if($user->newsletter_subscribed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Newsletter
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Formulaire d'édition du profil -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-green-200">
                    <div class="px-6 py-4 border-b border-green-200">
                        <h2 class="text-xl font-semibold text-green-800 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier mes informations
                        </h2>
                    </div>
                    
                    <form method="POST" action="{{ route('users.update') }}" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nom d'utilisateur -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-green-700 mb-2">
                                Nom d'utilisateur
                            </label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', $user->username) }}"
                                   class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom complet -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-green-700 mb-2">
                                Nom complet
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="Votre nom complet"
                                   class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-green-700 mb-2">
                                Adresse email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-green-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+32 123 456 789"
                                   class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-green-700 mb-2">
                                    Adresse
                                </label>
                                <input type="text" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address', $user->address) }}"
                                       placeholder="Rue, numéro"
                                       class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="address_line_2" class="block text-sm font-medium text-green-700 mb-2">
                                    Complément d'adresse
                                </label>
                                <input type="text" 
                                       id="address_line_2" 
                                       name="address_line_2" 
                                       value="{{ old('address_line_2', $user->address_line_2) }}"
                                       placeholder="Appartement, étage, etc."
                                       class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address_line_2') border-red-500 @enderror">
                                @error('address_line_2')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-green-700 mb-2">
                                    Ville
                                </label>
                                <input type="text" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', $user->city) }}"
                                       placeholder="Bruxelles"
                                       class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-green-700 mb-2">
                                    Code postal
                                </label>
                                <input type="text" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code', $user->postal_code) }}"
                                       placeholder="1000"
                                       class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('postal_code') border-red-500 @enderror">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pays -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-green-700 mb-2">
                                Pays
                            </label>
                            <select id="country" 
                                    name="country" 
                                    class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm bg-white/80 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-500 @enderror">
                                <option value="">Sélectionnez un pays</option>
                                <option value="BE" {{ old('country', $user->country) === 'BE' ? 'selected' : '' }}>Belgique</option>
                                <option value="FR" {{ old('country', $user->country) === 'FR' ? 'selected' : '' }}>France</option>
                                <option value="NL" {{ old('country', $user->country) === 'NL' ? 'selected' : '' }}>Pays-Bas</option>
                                <option value="DE" {{ old('country', $user->country) === 'DE' ? 'selected' : '' }}>Allemagne</option>
                                <option value="LU" {{ old('country', $user->country) === 'LU' ? 'selected' : '' }}>Luxembourg</option>
                            </select>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Newsletter -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="newsletter_subscribed" 
                                       name="newsletter_subscribed" 
                                       type="checkbox" 
                                       value="1"
                                       {{ old('newsletter_subscribed', $user->newsletter_subscribed) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-blue-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="newsletter_subscribed" class="text-green-700">
                                    Je souhaite recevoir la newsletter et les offres spéciales
                                </label>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-green-200">
                            <a href="{{ url('/') }}" 
                               class="px-4 py-2 border border-blue-300 rounded-md text-blue-700 hover:bg-blue-50 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition-colors shadow-lg">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panneau latéral -->
            <div class="space-y-6">
                
                <!-- Sécurité -->
                <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-green-200">
                    <div class="px-6 py-4 border-b border-green-200">
                        <h3 class="text-lg font-semibold text-green-800 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Sécurité
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="#" 
                           class="block w-full text-left px-4 py-3 text-sm text-blue-700 hover:bg-blue-50 rounded-md transition-colors border border-blue-200">
                            🔑 Modifier mon mot de passe
                        </a>
                        <a href="{{ route('users.download-data') }}" 
                           class="block w-full text-left px-4 py-3 text-sm text-blue-700 hover:bg-blue-50 rounded-md transition-colors border border-blue-200">
                            📥 Télécharger mes données
                        </a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-blue-200">
                    <div class="px-6 py-4 border-b border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($user->newsletter_subscribed)
                            <form method="POST" action="{{ route('newsletter.unsubscribe') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-3 text-sm text-blue-700 hover:bg-blue-50 rounded-md transition-colors border border-blue-200">
                                    📧 Se désabonner de la newsletter
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('newsletter.subscribe') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-3 text-sm text-green-700 hover:bg-green-50 rounded-md transition-colors border border-green-200">
                                    📧 S'abonner à la newsletter
                                </button>
                            </form>
                        @endif
                        
                        <button onclick="if(confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.')) { document.getElementById('delete-form').submit(); }" 
                                class="block w-full text-left px-4 py-3 text-sm text-red-700 hover:bg-red-50 rounded-md transition-colors border border-red-200">
                            🗑️ Supprimer mon compte
                        </button>
                        
                        <form id="delete-form" method="POST" action="{{ route('users.self-delete') }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
