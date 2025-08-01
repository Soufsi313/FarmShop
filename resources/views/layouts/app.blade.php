<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FarmShop - Matériel Agricole Belge de Qualité')</title>
    <meta name="description" content="@yield('description', 'FarmShop propose du matériel agricole de qualité en Belgique. Achat et location d\'équipements pour professionnels et particuliers.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'farm-green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        'farm-brown': {
                            50: '#fdf8f6',
                            100: '#f2e8e5',
                            200: '#eaddd7',
                            300: '#e0cfc5',
                            400: '#d2bab0',
                            500: '#bfa094',
                            600: '#a18072',
                            700: '#977669',
                            800: '#846358',
                            900: '#43302b',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Styles CSS personnalisés -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .hero-bg {
            background-image: linear-gradient(rgba(22, 163, 74, 0.8), rgba(22, 163, 74, 0.6));
        }
        
        html {
            font-family: 'Inter', ui-sans-serif, system-ui;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-50" x-data="{ mobileMenuOpen: false }">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="flex items-center">
                        <svg class="h-8 w-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">FarmShop</span>
                    </a>
                </div>

                <!-- Navigation principale (desktop) -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/" class="text-gray-900 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Accueil</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Nos produits</span>
                        </a>
                        <a href="{{ route('rentals.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Nos locations</span>
                        </a>
                        <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <span>Blog</span>
                        </a>
                        
                        <!-- Sections visibles seulement si connecté -->
                        @auth
                            <!-- Dropdown Panier -->
                            <div class="relative" x-data="{ cartDropdownOpen: false }">
                                <button @click="cartDropdownOpen = !cartDropdownOpen" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 0L7 13m0 0l-1.5 8h13M7 13v8a2 2 0 002 2h6a2 2 0 002-2v-8m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4.01"/>
                                    </svg>
                                    <span>Panier</span>
                                    <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-show="cartDropdownOpen" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.away="cartDropdownOpen = false"
                                     class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                    <a href="{{ route('cart.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="h-4 w-4 mr-3 text-farm-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        <span>Panier Achat</span>
                                        <span id="cart-count" class="ml-2 bg-green-600 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="h-4 w-4 mr-3 text-farm-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Panier Location</span>
                                        <span class="ml-2 bg-orange-500 text-white text-xs rounded-full px-2 py-1 opacity-50">Bientôt</span>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Mes achats</span>
                            </a>
                            <a href="#" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8h0m0-8h0m-4.5 8h9a2 2 0 002-2V9a2 2 0 00-2-2h-9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                                <span>Mes locations</span>
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                                <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span>Wishlist</span>
                            </a>
                        @endauth
                        
                        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center space-x-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Contact</span>
                        </a>
                    </div>
                </div>

                <!-- Actions utilisateur -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 text-sm font-medium transition-colors">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Inscription
                        </a>
                    @else
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-green-500">
                                <span class="sr-only">Ouvrir menu utilisateur</span>
                                <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white font-medium">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </button>
                            
                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 @click.away="userMenuOpen = false"
                                 class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                <a href="{{ route('users.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 font-medium">
                                        🛠️ Dashboard Admin
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                @endif
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes achats</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes locations</a>
                                <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        Ma Wishlist
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
                </div>

                <!-- Bouton menu mobile -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="text-gray-700 hover:text-green-600 focus:outline-none focus:text-green-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu mobile -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-down duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-up duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                    <a href="/" class="text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Accueil</a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Nos produits</a>
                    <a href="{{ route('rentals.index') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Nos locations</a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Blog</a>
                    
                    <!-- Sections visibles seulement si connecté -->
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            Wishlist
                        </a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Mes achats</a>
                        <a href="#" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Mes locations</a>
                        <a href="{{ route('users.profile') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Mon profil</a>
                    @endauth
                    
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                    
                    @guest
                        <div class="border-t pt-4 mt-4">
                            <a href="#" class="text-gray-700 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Connexion</a>
                            <a href="#" class="bg-green-600 text-white block px-3 py-2 rounded-md text-base font-medium mt-2">Inscription</a>
                        </div>
                    @else
                        <div class="border-t pt-4 mt-4">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 block px-3 py-2 rounded-md text-base font-medium w-full text-left">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>
    </header>

    <!-- Messages Flash -->
    @if(session('message'))
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                <!-- Informations principales -->
                <div class="space-y-8 xl:col-span-1">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-white">FarmShop</span>
                    </div>
                    <p class="text-gray-300 text-base">
                        Votre partenaire de confiance pour le matériel agricole en Belgique. 
                        Achat et location d'équipements de qualité pour professionnels et particuliers.
                    </p>
                    <div class="text-gray-300">
                        <p class="font-medium text-white mb-2">Siège social:</p>
                        <p>Avenue de la ferme 123</p>
                        <p>1000 Bruxelles, Belgique</p>
                        <p>Tel: +32 2 123 45 67</p>
                        <p>Email: s.mef2703@gmail.com</p>
                    </div>
                </div>

                <!-- Liens rapides -->
                <div class="mt-12 xl:mt-0 xl:col-span-2">
                    <div class="md:grid md:grid-cols-3 md:gap-8">
                        <!-- Mentions légales -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Mentions légales</h3>
                            <ul class="mt-4 space-y-3">
                                <li><a href="{{ route('legal.mentions') }}" class="text-base text-gray-300 hover:text-white transition-colors">Mentions légales</a></li>
                                <li><a href="{{ route('legal.cgv') }}" class="text-base text-gray-300 hover:text-white transition-colors">CGV</a></li>
                                <li><a href="{{ route('legal.cgu') }}" class="text-base text-gray-300 hover:text-white transition-colors">CGU</a></li>
                                <li><a href="{{ route('legal.cgl') }}" class="text-base text-gray-300 hover:text-white transition-colors">Conditions Location</a></li>
                            </ul>
                        </div>

                        <!-- Protection des données -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Données personnelles</h3>
                            <ul class="mt-4 space-y-3">
                                <li><a href="{{ route('legal.privacy') }}" class="text-base text-gray-300 hover:text-white transition-colors">Politique de confidentialité</a></li>
                                <li><a href="{{ route('legal.gdpr-rights') }}" class="text-base text-gray-300 hover:text-white transition-colors">Droits RGPD</a></li>
                                <li><a href="{{ route('legal.cookies') }}" class="text-base text-gray-300 hover:text-white transition-colors">Politique des cookies</a></li>
                                <li><a href="{{ route('legal.data-request') }}" class="text-base text-gray-300 hover:text-white transition-colors">Demande de données</a></li>
                            </ul>
                        </div>

                        <!-- Conformité secteur -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Conformité</h3>
                            <ul class="mt-4 space-y-3">
                                <li><a href="https://www.afsca.be" target="_blank" rel="noopener noreferrer" class="text-base text-gray-300 hover:text-white transition-colors">AFSCA <svg class="inline w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg></a></li>
                                <li><a href="{{ route('legal.returns') }}" class="text-base text-gray-300 hover:text-white transition-colors">Droit de rétractation</a></li>
                                <li><a href="{{ route('legal.warranties') }}" class="text-base text-gray-300 hover:text-white transition-colors">Garanties légales</a></li>
                                <li><a href="{{ route('legal.mediation') }}" class="text-base text-gray-300 hover:text-white transition-colors">Médiation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright et informations légales -->
            <div class="mt-12 border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div class="text-sm text-gray-400 space-y-1">
                        <p>&copy; {{ date('Y') }} FarmShop SPRL. Tous droits réservés.</p>
                        <p>BCE: 0XXX.XXX.XXX | TVA: BE 0XXX.XXX.XXX</p>
                        <p>Siège social: Avenue de la ferme 123, 1000 Bruxelles</p>
                        <p class="text-xs">
                            Assurance RC Professionnelle: Police n° XXX - 
                            <a href="{{ route('legal.insurance') }}" class="hover:text-white transition-colors">Détails couverture</a>
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-gray-300" aria-label="Facebook">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300" aria-label="Instagram">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.326-1.297s-1.297-1.888-1.297-3.185c0-1.297.49-2.448 1.297-3.326s1.888-1.297 3.185-1.297c1.297 0 2.448.49 3.326 1.297s1.297 1.888 1.297 3.185c0 1.297-.49 2.448-1.297 3.326s-1.888 1.297-3.185 1.297z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300" aria-label="LinkedIn">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Banner de consentement des cookies -->
    <div id="cookie-banner" class="hidden fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-50">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm">
                <p>🍪 Ce site utilise des cookies pour améliorer votre expérience. En continuant à naviguer, vous acceptez notre politique de cookies.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="FarmShop.cookieConsent.decline()" class="px-4 py-2 text-sm border border-gray-600 rounded hover:bg-gray-800 transition-colors">
                    Refuser
                </button>
                <button onclick="FarmShop.cookieConsent.accept()" class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 rounded transition-colors">
                    Accepter
                </button>
            </div>
        </div>
    </div>

    <!-- Bouton retour en haut -->
    <button onclick="FarmShop.ui.scrollToTop()" 
            class="fixed bottom-8 right-8 bg-green-600 hover:bg-green-700 text-white p-3 rounded-full shadow-lg transition-colors z-40">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    @stack('scripts')

    <!-- Scripts globaux -->
    <script>
        // Objet global FarmShop
        window.FarmShop = {
            // Système de notifications
            notification: {
                show: function(message, type = 'info', duration = 5000) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
                    
                    // Classes selon le type
                    switch(type) {
                        case 'success':
                            notification.classList.add('bg-green-100', 'border-l-4', 'border-green-500', 'text-green-700');
                            break;
                        case 'error':
                            notification.classList.add('bg-red-100', 'border-l-4', 'border-red-500', 'text-red-700');
                            break;
                        case 'warning':
                            notification.classList.add('bg-yellow-100', 'border-l-4', 'border-yellow-500', 'text-yellow-700');
                            break;
                        default:
                            notification.classList.add('bg-blue-100', 'border-l-4', 'border-blue-500', 'text-blue-700');
                    }
                    
                    notification.innerHTML = `
                        <div class="flex">
                            <div class="flex-1">
                                <p class="font-medium">${message}</p>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    // Animation d'entrée
                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);
                    
                    // Suppression automatique
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (notification.parentElement) {
                                notification.remove();
                            }
                        }, 300);
                    }, duration);
                }
            },

            // Interface utilisateur
            ui: {
                scrollToTop: function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            },

            // Gestion des cookies
            cookieConsent: {
                accept: function() {
                    localStorage.setItem('cookieConsent', 'accepted');
                    document.getElementById('cookie-banner').style.display = 'none';
                },
                decline: function() {
                    localStorage.setItem('cookieConsent', 'declined');
                    document.getElementById('cookie-banner').style.display = 'none';
                },
                check: function() {
                    const consent = localStorage.getItem('cookieConsent');
                    if (!consent) {
                        document.getElementById('cookie-banner').classList.remove('hidden');
                    }
                }
            }
        };

        // Fonction globale pour les notifications (alias)
        function showNotification(message, type = 'info', duration = 5000) {
            FarmShop.notification.show(message, type, duration);
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier le consentement des cookies
            FarmShop.cookieConsent.check();
        });
    </script>
</body>
</html>
