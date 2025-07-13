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
                        
                        <button onclick="openContactModal()" 
                                class="block w-full text-left px-4 py-3 text-sm text-green-700 hover:bg-green-50 rounded-md transition-colors border border-green-200">
                            💬 Contacter l'Admin
                        </button>
                        
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

        <!-- Boîte de réception des messages -->
        @if($messages !== null)
        <div class="mt-8">
            <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-xl border border-green-200">
                <div class="px-6 py-4 border-b border-green-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-green-800 flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Boîte de réception
                        </h3>
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $unreadCount }} non lu{{ $unreadCount > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($messages && $messages->count() > 0)
                        <div class="space-y-4">
                            @foreach($messages as $message)
                                <div class="border {{ $message->read_at ? 'border-gray-200 bg-gray-50/50' : 'border-blue-200 bg-blue-50/50' }} rounded-lg p-4 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <!-- En-tête du message -->
                                            <div class="flex items-center space-x-3 mb-2">
                                                @if($message->sender)
                                                    <!-- Utilisateur connecté/inscrit -->
                                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                                        {{ substr($message->sender->name ?: $message->sender->username, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $message->sender->name ?: $message->sender->username }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                                    </div>
                                                @elseif($message->metadata && isset($message->metadata['sender_name']))
                                                    <!-- Visiteur/contact non-inscrit -->
                                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center text-white text-sm font-bold">
                                                        {{ substr($message->metadata['sender_name'], 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $message->metadata['sender_name'] }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                                    </div>
                                                @else
                                                    <!-- Message système -->
                                                    <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center text-white text-sm font-bold">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2m0 0h4"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">Système</p>
                                                        <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                                    </div>
                                                @endif
                                                
                                                @if(!$message->read_at)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Nouveau
                                                    </span>
                                                @endif
                                                
                                                @if($message->is_important)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Important
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Sujet et type -->
                                            <div class="mb-2">
                                                <h4 class="text-sm font-semibold text-gray-900">{{ $message->subject }}</h4>
                                                @if($message->type)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                        {{ $message->type === 'contact_confirmation' ? 'bg-orange-100 text-orange-700' : 
                                                           ($message->type === 'admin_response' ? 'bg-green-100 text-green-700' : 
                                                           'bg-gray-100 text-gray-700') }} mt-1">
                                                        {{ $message->type === 'contact_confirmation' ? 'Message envoyé' : 
                                                           ($message->type === 'admin_response' ? 'Réponse admin' : 
                                                           ucfirst($message->type)) }}
                                                    </span>
                                                @endif
                                                @if($message->priority && $message->priority !== 'medium')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                        {{ $message->priority === 'urgent' ? 'bg-red-100 text-red-700' : 
                                                           ($message->priority === 'high' ? 'bg-orange-100 text-orange-700' : 
                                                           'bg-blue-100 text-blue-700') }} mt-1 ml-1">
                                                        Priorité {{ $message->priority === 'urgent' ? 'urgente' : 
                                                                   ($message->priority === 'high' ? 'haute' : 
                                                                   ($message->priority === 'low' ? 'basse' : $message->priority)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Contenu -->
                                            <div class="text-sm text-gray-700">
                                                {!! nl2br(e(Str::limit($message->content, 300))) !!}
                                            </div>
                                            
                                            <!-- Référence si c'est un message de contact -->
                                            @if($message->metadata && isset($message->metadata['message_reference']))
                                                <div class="mt-2">
                                                    <span class="text-xs text-gray-500">
                                                        Référence: {{ $message->metadata['message_reference'] }}
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <!-- Bouton d'action si disponible -->
                                            @if($message->action_url && $message->action_label)
                                                <div class="mt-3">
                                                    <a href="{{ $message->action_url }}" 
                                                       class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                                        {{ $message->action_label }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="ml-4 flex-shrink-0">
                                            <div class="flex items-center space-x-2">
                                                @if(!$message->read_at)
                                                    <form method="POST" action="{{ route('messages.read', $message) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="text-blue-600 hover:text-blue-800 text-xs font-medium"
                                                                title="Marquer comme lu">
                                                            Marquer lu
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if(!$message->archived_at)
                                                    <form method="POST" action="{{ route('messages.archive', $message) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="text-gray-600 hover:text-gray-800 text-xs font-medium"
                                                                title="Archiver">
                                                            Archiver
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <form method="POST" action="{{ route('messages.delete', $message) }}" class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 text-xs font-medium"
                                                            title="Supprimer">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun message</h3>
                            <p class="mt-1 text-sm text-gray-500">Vous n'avez reçu aucun message pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>

<!-- Modale de contact Admin -->
<div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- En-tête de la modale -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Contacter l'Administration</h3>
                <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Formulaire de contact -->
            <form id="contactForm" class="space-y-4">
                @csrf
                
                <!-- Raison du contact -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                        Raison du contact <span class="text-red-500">*</span>
                    </label>
                    <select id="reason" name="reason" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionnez une raison</option>
                        <option value="mon_profil">Mon Profil</option>
                        <option value="mes_achats">Mes Achats</option>
                        <option value="mes_locations">Mes Locations</option>
                        <option value="mes_donnees">Mes Données</option>
                        <option value="support_technique">Support Technique</option>
                        <option value="partenariat">Partenariat</option>
                        <option value="autre">Autre</option>
                    </select>
                    <div id="reason-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Sujet -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                        Sujet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" required 
                           placeholder="Résumé de votre demande"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <div id="subject-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                
                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea id="message" name="message" rows="4" required 
                              placeholder="Décrivez votre demande en détail..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"></textarea>
                    <div id="message-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    <div class="text-xs text-gray-500 mt-1">
                        Minimum 10 caractères, maximum 2000 caractères
                    </div>
                </div>
                
                <!-- Priorité -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                        Priorité
                    </label>
                    <select id="priority" name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="low">Basse</option>
                        <option value="medium" selected>Moyenne</option>
                        <option value="high">Haute</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeContactModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors">
                        Envoyer
                    </button>
                </div>
            </form>
            
            <!-- Message de succès -->
            <div id="contactSuccess" class="hidden">
                <div class="text-center py-4">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Message envoyé avec succès !</h3>
                    <p class="text-gray-600 mb-4">Votre message a été transmis à l'administration. Vous recevrez une réponse par email dans les plus brefs délais.</p>
                    <button onclick="closeContactModal()" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors">
                        Fermer
                    </button>
                </div>
            </div>
            
            <!-- Message d'erreur générale -->
            <div id="contactError" class="hidden">
                <div class="text-center py-4">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Erreur d'envoi</h3>
                    <p id="contactErrorMessage" class="text-gray-600 mb-4">Une erreur est survenue lors de l'envoi de votre message.</p>
                    <button onclick="showContactForm()" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors">
                        Réessayer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openContactModal() {
    document.getElementById('contactModal').classList.remove('hidden');
    showContactForm();
}

function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
}

function showContactForm() {
    // Pré-remplir les champs avec les données utilisateur
    const form = document.getElementById('contactForm');
    form.reset();
    
    // Réinitialiser les erreurs
    const errorDivs = form.querySelectorAll('[id$="-error"]');
    errorDivs.forEach(div => {
        div.classList.add('hidden');
        div.textContent = '';
    });
    
    // Montrer le formulaire et cacher les messages
    document.getElementById('contactForm').classList.remove('hidden');
    document.getElementById('contactSuccess').classList.add('hidden');
    document.getElementById('contactError').classList.add('hidden');
}

function showContactError(message) {
    document.getElementById('contactForm').classList.add('hidden');
    document.getElementById('contactSuccess').classList.add('hidden');
    document.getElementById('contactError').classList.remove('hidden');
    document.getElementById('contactErrorMessage').textContent = message;
}

// Soumettre le formulaire de contact
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Envoi...';
    submitBtn.disabled = true;
    
    // Réinitialiser les erreurs
    const errorDivs = this.querySelectorAll('[id$="-error"]');
    errorDivs.forEach(div => {
        div.classList.add('hidden');
        div.textContent = '';
    });
    
    try {
        // Récupérer les données du formulaire
        const formData = new FormData(this);
        
        // Préparer les données à envoyer
        const requestData = {
            name: '{{ addslashes($user->name ?: $user->username) }}',
            email: '{{ $user->email }}',
            @if($user->phone)
            phone: '{{ $user->phone }}',
            @endif
            reason: formData.get('reason'),
            subject: formData.get('subject'),
            message: formData.get('message'),
            priority: formData.get('priority') || 'medium'
        };
        
        console.log('Envoi des données:', requestData);
        
        const response = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Réponse statut:', response.status);
        
        const data = await response.json();
        console.log('Données de réponse:', data);
        
        if (response.ok && data.success) {
            // Montrer le message de succès
            document.getElementById('contactForm').classList.add('hidden');
            document.getElementById('contactSuccess').classList.remove('hidden');
        } else {
            // Afficher les erreurs de validation
            if (data.errors) {
                console.log('Erreurs de validation:', data.errors);
                Object.keys(data.errors).forEach(field => {
                    const errorDiv = document.getElementById(field + '-error');
                    if (errorDiv) {
                        errorDiv.textContent = data.errors[field][0];
                        errorDiv.classList.remove('hidden');
                    } else {
                        console.log('Erreur pour le champ', field, ':', data.errors[field][0]);
                    }
                });
            } else {
                console.error('Erreur de réponse:', data);
                showContactError(data.message || 'Une erreur est survenue lors de l\'envoi du message');
            }
        }
    } catch (error) {
        console.error('Erreur complète:', error);
        showContactError('Une erreur de réseau est survenue. Vérifiez votre connexion internet et réessayez.');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});

// Fermer la modale en cliquant à l'extérieur
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactModal();
    }
});
</script>

@endsection
