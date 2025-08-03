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
    
    <!-- TinyMCE Editor -->
        <!-- Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
    
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
        <!-- Sidebar Moderne -->
        <div class="hidden md:flex md:w-72 md:flex-col">
            <div class="flex flex-col flex-grow bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 shadow-2xl">
                <!-- Logo Section -->
                <div class="flex items-center flex-shrink-0 px-6 py-6 border-b border-slate-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">FarmShop</h2>
                            <p class="text-xs text-slate-400 font-medium">Administration</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    <div class="space-y-2">
                        <!-- Dashboard principal -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="@if(request()->routeIs('admin.dashboard*')) bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                            <div class="@if(request()->routeIs('admin.dashboard*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span>Tableau de bord</span>
                        </a>
                    </div>

                    <!-- Section Gestion des Utilisateurs -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Utilisateurs</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.users.index') }}" 
                               class="@if(request()->routeIs('admin.users*')) bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.users*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <span>Gestion Utilisateurs</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section Gestion du Catalogue -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Catalogue</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.products.index') }}" 
                               class="@if(request()->routeIs('admin.products*')) bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.products*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <span>Produits</span>
                            </a>
                            
                            <a href="{{ route('admin.categories.index') }}" 
                               class="@if(request()->routeIs('admin.categories*')) bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.categories*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <span>Catégories</span>
                            </a>

                            <a href="{{ route('admin.special-offers.index') }}" 
                               class="@if(request()->routeIs('admin.special-offers*')) bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.special-offers*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <span>Offres Spéciales</span>
                            </a>

                            <a href="{{ route('admin.rental-categories.index') }}" 
                               class="@if(request()->routeIs('admin.rental-categories*')) bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.rental-categories*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <span>Locations</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section Gestion de Stock -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-red-400 to-red-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Gestion de Stock</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.stock.index') }}" 
                               class="@if(request()->routeIs('admin.stock*')) bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.stock*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <span>Vue d'ensemble</span>
                                @if(isset($stockStats) && ($stockStats['out_of_stock'] > 0 || $stockStats['critical_stock'] > 0))
                                    <span class="ml-auto px-2 py-1 text-xs bg-red-500/20 text-red-400 rounded-full">
                                        {{ $stockStats['out_of_stock'] + $stockStats['critical_stock'] }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('admin.stock.alerts') }}" 
                               class="@if(request()->routeIs('admin.stock.alerts*')) bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.stock.alerts*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <span>Alertes & Seuils</span>
                            </a>

                            <a href="{{ route('admin.stock.restock') }}" 
                               class="@if(request()->routeIs('admin.stock.restock*')) bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.stock.restock*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                </div>
                                <span>Réapprovisionnement</span>
                            </a>

                            <a href="{{ route('admin.stock.reports') }}" 
                               class="@if(request()->routeIs('admin.stock.reports*')) bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.stock.reports*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <span>Rapports & Analyses</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section Gestion des Commandes -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-orange-400 to-orange-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Commandes</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.orders.index') }}" 
                               class="@if(request()->routeIs('admin.orders*')) bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.orders*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <span>Commandes Achat</span>
                            </a>

                            <a href="{{ route('admin.order-locations.index') }}" 
                               class="@if(request()->routeIs('admin.order-locations*')) bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.order-locations*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span>Commandes Location</span>
                            </a>

                            <a href="{{ route('admin.rental-returns.index') }}" 
                               class="@if(request()->routeIs('admin.rental-returns*')) bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.rental-returns*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <span>Retours</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section Gestion du Contenu -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Contenu</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.blog.index') }}" 
                               class="@if(request()->routeIs('admin.blog*')) bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.blog*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"/>
                                    </svg>
                                </div>
                                <span>Articles Blog</span>
                            </a>

                            <a href="{{ route('admin.blog-categories.index') }}" 
                               class="@if(request()->routeIs('admin.blog-categories*')) bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.blog-categories*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <span>Catégories Blog</span>
                            </a>

                            <a href="{{ route('admin.blog-comments.index') }}" 
                               class="@if(request()->routeIs('admin.blog-comments*')) bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.blog-comments*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <span>Commentaires Blog</span>
                            </a>

                            <a href="{{ route('admin.blog-comment-reports.index') }}" 
                               class="@if(request()->routeIs('admin.blog-comment-reports*')) bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.blog-comment-reports*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <span>Signalements</span>
                            </a>

                            <a href="#" onclick="alert('Module newsletter en développement')" 
                               class="text-slate-300 hover:bg-slate-700/50 hover:text-white group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="bg-slate-600 group-hover:bg-slate-500 p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span>Newsletter</span>
                                <span class="ml-auto px-2 py-1 text-xs bg-yellow-500/20 text-yellow-400 rounded-full">Bientôt</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section Messages Contact -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-teal-400 to-teal-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Communication</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.messages.index') }}" 
                               class="@if(request()->routeIs('admin.messages*')) bg-gradient-to-r from-teal-500 to-teal-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.messages*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span>Messages Contact</span>
                            </a>
                        </div>
                    </div>

                    <!-- Section Outils et Statistiques -->
                    <div class="mt-8">
                        <div class="flex items-center px-4 mb-4">
                            <div class="w-8 h-0.5 bg-gradient-to-r from-rose-400 to-rose-600 rounded-full"></div>
                            <h3 class="ml-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Outils & Analytics</h3>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ route('admin.statistics') }}" 
                               class="@if(request()->routeIs('admin.statistics*')) bg-gradient-to-r from-rose-500 to-rose-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.statistics*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <span>Statistiques</span>
                                <span class="ml-auto w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            </a>

                            <a href="{{ route('admin.cookies.index') }}" 
                               class="@if(request()->routeIs('admin.cookies*')) bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.cookies*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span>Gestion Cookies</span>
                            </a>

                            <a href="{{ route('admin.settings.index') }}" 
                               class="@if(request()->routeIs('admin.settings*')) bg-gradient-to-r from-slate-500 to-slate-600 text-white shadow-lg @else text-slate-300 hover:bg-slate-700/50 hover:text-white @endif group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 ease-in-out transform hover:scale-105">
                                <div class="@if(request()->routeIs('admin.settings*')) bg-white/20 @else bg-slate-600 group-hover:bg-slate-500 @endif p-2 rounded-lg mr-4 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span>Paramètres</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="flex-shrink-0 border-t border-slate-700/50 p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">Administrateur</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-lg transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" class="md:hidden fixed inset-0 z-40 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900">
                <!-- Contenu mobile simplifié -->
                <div class="p-4">
                    <h2 class="text-white font-bold">FarmShop Admin</h2>
                </div>
                <nav class="flex-1 px-4">
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-white">Dashboard</a>
                    <a href="{{ route('admin.statistics') }}" class="block py-2 text-white">Statistiques</a>
                    <!-- Autres liens... -->
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="ml-3 text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ url('/') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Retour au site
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
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div x-data="toastManager()" class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Toast Template -->
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                x-show="true"
                x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                :class="getToastClass(toast.type)"
                class="max-w-sm w-full border rounded-lg shadow-lg pointer-events-auto">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <!-- Success Icon -->
                            <svg x-show="toast.type === 'success'" :class="getIconClass(toast.type)" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <!-- Error Icon -->
                            <svg x-show="toast.type === 'error'" :class="getIconClass(toast.type)" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <!-- Warning Icon -->
                            <svg x-show="toast.type === 'warning'" :class="getIconClass(toast.type)" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <!-- Info Icon -->
                            <svg x-show="toast.type === 'info'" :class="getIconClass(toast.type)" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium" x-text="toast.message"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="remove(toast.id)" class="inline-flex text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        // Global Toast Manager
        function toastManager() {
            return {
                toasts: [],
                
                add(message, type = 'info', duration = 5000) {
                    const id = Date.now();
                    const toast = { id, message, type, duration };
                    this.toasts.push(toast);
                    
                    if (duration > 0) {
                        setTimeout(() => this.remove(id), duration);
                    }
                },
                
                remove(id) {
                    this.toasts = this.toasts.filter(toast => toast.id !== id);
                },
                
                getToastClass(type) {
                    const classes = {
                        'success': 'bg-green-50 border-green-200 text-green-800',
                        'error': 'bg-red-50 border-red-200 text-red-800',
                        'warning': 'bg-yellow-50 border-yellow-200 text-yellow-800',
                        'info': 'bg-blue-50 border-blue-200 text-blue-800'
                    };
                    return classes[type] || classes['info'];
                },
                
                getIconClass(type) {
                    const classes = {
                        'success': 'text-green-400',
                        'error': 'text-red-400',
                        'warning': 'text-yellow-400',
                        'info': 'text-blue-400'
                    };
                    return classes[type] || classes['info'];
                }
            }
        }
        
        // Global function to show toasts
        window.showToast = function(message, type = 'info', duration = 5000) {
            const container = document.querySelector('[x-data*="toastManager"]');
            if (container && container.__x) {
                container.__x.$data.add(message, type, duration);
            } else {
                console.log(`${type}: ${message}`);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
