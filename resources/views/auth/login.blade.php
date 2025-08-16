@extends('layouts.app')

@section('title', __('app.auth.login') . ' - FarmShop')
@section('description', 'Connectez-vous à votre compte FarmShop pour accéder à vos achats, locations et wishlist.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-farm-green-50 via-farm-orange-50 to-farm-green-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-farm-green-800">FarmShop</h1>
            <p class="mt-2 text-sm text-farm-orange-700">{{ __('app.auth.site_description') }}</p>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-farm-green-800">
            {{ __('app.auth.login_title') }}
        </h2>
        <p class="mt-2 text-center text-sm text-farm-orange-600">
            {{ __('app.auth.login_subtitle') }}
            <a href="{{ route('register') }}" class="font-medium text-farm-green-600 hover:text-farm-green-500">
                {{ __('app.auth.create_new_account') }}
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white/80 backdrop-blur-sm py-8 px-4 shadow-xl border border-farm-green-200 sm:rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-farm-green-700">
                        {{ __('app.auth.email_address') }}
                    </label>
                    <div class="mt-1">
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-orange-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 bg-white/90 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-farm-green-700">
                        {{ __('app.auth.password') }}
                    </label>
                    <div class="mt-1">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-orange-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 bg-white/90 sm:text-sm @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Se souvenir de moi & Mot de passe oublié -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-farm-green-600 focus:ring-farm-green-500 border-farm-green-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-farm-green-700">
                            {{ __('app.auth.remember_me') }}
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-farm-orange-600 hover:text-farm-orange-500">
                            {{ __('app.auth.forgot_password') }}
                        </a>
                    </div>
                </div>

                <!-- Messages d'erreur globaux -->
                @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                    <div class="bg-red-50 border border-red-300 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ __('app.auth.login_error') }}
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    {{ __('app.auth.check_credentials') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Message de succès -->
                @if (session('success'))
                    <div class="bg-farm-green-50 border border-farm-green-300 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-farm-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-farm-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Bouton de connexion -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-farm-green-600 hover:bg-farm-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-green-500 transition-colors shadow-lg">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-farm-green-500 group-hover:text-farm-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        {{ __('app.auth.login_button') }}
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
                        <span class="px-2 bg-white/80 text-farm-orange-600">{{ __('app.auth.first_visit') }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-farm-orange-500 rounded-md shadow-sm text-sm font-medium text-farm-orange-600 bg-white/80 hover:bg-farm-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-orange-500 transition-colors">
                        {{ __('app.auth.create_account') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Lien retour accueil -->
        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="text-sm text-farm-green-600 hover:text-farm-orange-600 transition-colors">
                {{ __('app.auth.back_to_home') }}
            </a>
        </div>
    </div>
</div>

<script>
// Nettoyer le localStorage des cookies quand on arrive sur la page de connexion
localStorage.removeItem('cookie_consent_given');
localStorage.removeItem('cookie_consent_date');
console.log('🍪 localStorage des cookies nettoyé pour la connexion');
</script>
@endsection
