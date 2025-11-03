@extends('layouts.app')

@section('title', __('app.auth.register') . ' - FarmShop')
@section('description', 'Créez votre compte FarmShop pour accéder à nos produits agricoles et services de location.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-farm-green-50 via-farm-orange-50 to-farm-green-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-farm-green-800">FarmShop</h1>
            <p class="mt-2 text-sm text-farm-green-600">{{ __('app.auth.site_description') }}</p>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-farm-green-800">
            {{ __('app.auth.register_title') }}
        </h2>
        <p class="mt-2 text-center text-sm text-farm-green-600">
            {{ __('app.auth.register_subtitle') }}
            <a href="{{ route('login') }}" class="font-medium text-farm-orange-600 hover:text-farm-orange-700 transition-colors">
                {{ __('app.auth.login_existing_account') }}
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white/80 backdrop-blur-sm py-8 px-4 shadow-xl border border-farm-green-200 rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Nom d'utilisateur -->
                <div>
                    <label for="username" class="block text-sm font-medium text-farm-green-700">
                        {{ __('app.auth.username') }}
                    </label>
                    <div class="mt-1">
                        <input id="username" 
                               name="username" 
                               type="text" 
                               autocomplete="username" 
                               required 
                               value="{{ old('username') }}"
                               placeholder="{{ __('app.auth.username_placeholder') }}"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-1 text-xs text-farm-green-500">
                        {{ __('app.auth.username_help') }}
                    </p>
                </div>

                <!-- Nom complet -->
                <div>
                    <label for="name" class="block text-sm font-medium text-farm-green-700">
                        {{ __('app.auth.full_name') }}
                    </label>
                    <div class="mt-1">
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               value="{{ old('name') }}"
                               placeholder="{{ __('app.auth.full_name_placeholder') }}"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

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
                               placeholder="{{ __('app.auth.email_placeholder_register') }}"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mot de passe -->
                <div x-data="passwordStrength()">
                    <label for="password" class="block text-sm font-medium text-farm-green-700">
                        {{ __('app.auth.password') }}
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               x-model="password"
                               @input="checkStrength()"
                               placeholder="{{ __('app.auth.password_placeholder') }}"
                               class="appearance-none block w-full px-3 py-2 border border-farm-green-300 rounded-md placeholder-farm-green-400 focus:outline-none focus:ring-farm-green-500 focus:border-farm-green-500 sm:text-sm bg-white/80 @error('password') border-red-500 @enderror">
                        
                        <!-- Bouton pour afficher/masquer le mot de passe -->
                        <button type="button" 
                                @click="showPassword = !showPassword; $el.previousElementSibling.type = showPassword ? 'text' : 'password'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <svg x-show="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                        
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Barre de progression et indicateur de force -->
                    <div x-show="password.length > 0" class="mt-2">
                        <!-- Barre de progression -->
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-full transition-all duration-300 ease-out rounded-full"
                                 :class="{
                                     'bg-red-500': strength === 'weak',
                                     'bg-yellow-500': strength === 'medium',
                                     'bg-green-500': strength === 'strong'
                                 }"
                                 :style="'width: ' + strengthPercentage + '%'">
                            </div>
                        </div>
                        
                        <!-- Texte indicateur -->
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs font-medium"
                               :class="{
                                   'text-red-600': strength === 'weak',
                                   'text-yellow-600': strength === 'medium',
                                   'text-green-600': strength === 'strong'
                               }">
                                <span x-show="strength === 'weak'">🔴 Mot de passe faible (<span x-text="password.length"></span> caractères)</span>
                                <span x-show="strength === 'medium'">🟡 Mot de passe moyen (<span x-text="password.length"></span> caractères)</span>
                                <span x-show="strength === 'strong'">🟢 Mot de passe élevé (<span x-text="password.length"></span> caractères)</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                <span x-show="strength === 'weak'">Minimum 6 caractères</span>
                                <span x-show="strength === 'medium'">Recommandé 8+ caractères</span>
                                <span x-show="strength === 'strong'">Excellent ✓</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-farm-green-700">
                        {{ __('app.auth.confirm_password') }}
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               placeholder="{{ __('app.auth.confirm_password_placeholder') }}"
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
                            {{ __('app.auth.terms_accept') }} 
                            <a href="#" class="text-farm-orange-600 hover:text-farm-orange-700 underline transition-colors">
                                {{ __('app.auth.terms_of_use') }}
                            </a>
                            {{ __('app.auth.and') }} 
                            <a href="{{ route('privacy') }}" class="text-farm-orange-600 hover:text-farm-orange-700 underline transition-colors">
                                {{ __('app.auth.privacy_policy') }}
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
                            {{ __('app.auth.newsletter_subscribe') }}
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
                        {{ __('app.auth.register_button') }}
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
                        <span class="px-2 bg-white/80 text-farm-orange-600">{{ __('app.auth.already_customer') }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-farm-orange-500 rounded-md shadow-sm text-sm font-medium text-farm-orange-600 bg-white/80 hover:bg-farm-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-orange-500 transition-colors">
                        {{ __('app.auth.sign_in') }}
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

@push('scripts')
<script>
    function passwordStrength() {
        return {
            password: '',
            showPassword: false,
            strength: 'weak',
            strengthPercentage: 0,
            
            checkStrength() {
                const length = this.password.length;
                
                if (length === 0) {
                    this.strength = 'weak';
                    this.strengthPercentage = 0;
                } else if (length < 6) {
                    // Faible : moins de 6 caractères
                    this.strength = 'weak';
                    this.strengthPercentage = (length / 6) * 33; // Max 33% pour faible
                } else if (length < 8) {
                    // Moyen : entre 6 et 7 caractères
                    this.strength = 'medium';
                    this.strengthPercentage = 33 + ((length - 6) / 2) * 33; // 33% à 66%
                } else {
                    // Élevé : 8 caractères ou plus
                    this.strength = 'strong';
                    // Progression jusqu'à 100% pour les mots de passe longs
                    this.strengthPercentage = Math.min(100, 66 + ((length - 8) / 8) * 34);
                }
            }
        }
    }
</script>
@endpush

@endsection
