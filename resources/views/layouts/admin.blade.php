<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Admin - FarmShop')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuration Tailwind CSS personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        html {
            font-family: 'Inter', ui-sans-serif, system-ui;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 overflow-y-auto bg-blue-800">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4">
                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <h2 class="ml-3 text-lg font-semibold text-white">FarmShop Admin</h2>
                </div>
                
                <!-- Navigation -->
                <nav class="mt-8 flex-1">
                    <div class="px-2 space-y-1">
                        <!-- Dashboard principal -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="@if(request()->routeIs('admin.dashboard*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-3 text-sm font-medium rounded-md transition-colors">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Tableau de bord
                        </a>

                        <!-- Section Gestion des Utilisateurs -->
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">CRUD Utilisateurs</h3>
                            <div class="mt-1 space-y-1">
                                <a href="{{ route('admin.users.index') }}" 
                                   class="@if(request()->routeIs('admin.users*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                    Utilisateurs
                                </a>
                            </div>
                        </div>

                        <!-- Section Gestion du Catalogue -->
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">CRUD Catalogue</h3>
                            <div class="mt-1 space-y-1">
                                <a href="{{ route('admin.products.index') }}" 
                                   class="@if(request()->routeIs('admin.products*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Produits
                                </a>
                                
                                <a href="{{ route('admin.categories.index') }}" 
                                   class="@if(request()->routeIs('admin.categories*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Catégories Produits
                                </a>

                                <a href="{{ route('admin.special-offers.index') }}" 
                                   class="@if(request()->routeIs('admin.special-offers*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Offres Spéciales
                                </a>

                                <a href="{{ route('admin.rental-categories.index') }}" 
                                   class="@if(request()->routeIs('admin.rental-categories*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Catégories Location
                                </a>
                            </div>
                        </div>

                        <!-- Section Gestion des Commandes -->
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">CRUD Commandes</h3>
                            <div class="mt-1 space-y-1">
                                <a href="{{ route('admin.orders.index') }}" 
                                   class="@if(request()->routeIs('admin.orders*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    Commandes Achat
                                </a>

                                <a href="{{ route('admin.order-locations.index') }}" 
                                   class="@if(request()->routeIs('admin.order-locations*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Commandes Location
                                </a>

                                <a href="#" onclick="alert('Module retours en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Retours
                                </a>
                            </div>
                        </div>

                        <!-- Section Gestion du Contenu -->
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">CRUD Contenu</h3>
                            <div class="mt-1 space-y-1">
                                <a href="#" onclick="alert('Module blog en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"/>
                                    </svg>
                                    Articles Blog
                                </a>

                                <a href="#" onclick="alert('Module catégories blog en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Catégories Blog
                                </a>

                                <a href="#" onclick="alert('Module newsletter en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Newsletter
                                </a>
                            </div>
                        </div>

                        <!-- Section Messages Contact -->
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">Communication</h3>
                            <div class="mt-1 space-y-1">
                                <a href="{{ route('admin.messages.index') }}" 
                                   class="@if(request()->routeIs('admin.messages*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Messages Contact
                                </a>
                            </div>
                        </div>

                        <!-- Section Outils et Statistiques -->
                        <div class="mt-6">
                            <h3 class="px-3 text-xs font-semibold text-blue-200 uppercase tracking-wider">Outils Admin</h3>
                            <div class="mt-1 space-y-1">
                                <a href="#" onclick="alert('Module contraintes location en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Contraintes Location
                                </a>

                                <a href="#" onclick="alert('Module gestion stock en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    Gestion Stock
                                </a>

                                <a href="#" onclick="alert('Module statistiques en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Statistiques
                                </a>

                                <a href="#" onclick="alert('Module cookies/RGPD en développement')" 
                                   class="text-blue-100 hover:bg-blue-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Cookies & RGPD
                                </a>

                                <!-- Paramètres -->
                                <a href="{{ route('admin.settings.index') }}" 
                                   class="@if(request()->routeIs('admin.settings*')) bg-blue-900 text-white @else text-blue-100 hover:bg-blue-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Paramètres
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="flex-shrink-0 flex border-t border-blue-700 p-4">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-medium">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-blue-200">Administrateur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" class="md:hidden fixed inset-0 z-40 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-blue-800">
                <!-- Contenu identique à la sidebar desktop -->
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="ml-3 text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            ← Retour au site
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
