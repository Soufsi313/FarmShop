@extends('layouts.admin')

@section('title', 'Dashboard - FarmShop Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-6">
        <!-- Utilisateurs -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.users') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['users'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.products') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['products'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catégories -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.categories') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['categories'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.orders') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['orders'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.orders') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['orders'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles de blog -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.blog_posts') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['blog_posts'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catégories Blog -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-pink-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.blog_categories') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['blog_categories'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commentaires Blog -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.comments') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['blog_comments'] }}</dd>
                            @if($stats['pending_comments'] > 0)
                                <dd class="text-xs text-orange-600">{{ $stats['pending_comments'] }} {{ __('app.pages.dashboard.stats.pending_comments') }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signalements -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ __('app.pages.dashboard.stats.reports') }}</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['comment_reports'] }}</dd>
                            @if($stats['pending_reports'] > 0)
                                <dd class="text-xs text-red-600">{{ $stats['pending_reports'] }} {{ __('app.pages.dashboard.stats.pending_reports') }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Gestion de Stock -->
    <div class="bg-white shadow rounded-lg relative" x-data="stockManagement">
        <!-- Indicateurs de notification -->
        @if(($stats['stock']['out_of_stock'] ?? 0) > 0)
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full border-2 border-white flex items-center justify-center animate-pulse">
                <span class="text-white text-xs font-bold">!</span>
            </div>
        @elseif(($stats['stock']['critical_stock'] ?? 0) > 0)
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-orange-500 rounded-full border-2 border-white flex items-center justify-center animate-pulse">
                <span class="text-white text-xs font-bold">{{ $stats['stock']['critical_stock'] }}</span>
            </div>
        @elseif(($stats['stock']['low_stock'] ?? 0) > 0)
            <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-500 rounded-full border-2 border-white flex items-center justify-center animate-pulse">
                <span class="text-white text-xs font-bold">{{ $stats['stock']['low_stock'] }}</span>
            </div>
        @endif
        
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">📦 {{ __('app.pages.dashboard.stock.title') }}</h3>
                            <!-- Indicateurs d'état inline -->
                            @if(($stats['stock']['out_of_stock'] ?? 0) > 0)
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1"></span>
                                    {{ $stats['stock']['out_of_stock'] }} {{ __('app.pages.dashboard.stock.out_of_stock') }}
                                </span>
                            @elseif(($stats['stock']['critical_stock'] ?? 0) > 0)
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 animate-pulse">
                                    <span class="w-2 h-2 bg-orange-400 rounded-full mr-1"></span>
                                    {{ $stats['stock']['critical_stock'] }} {{ __('app.pages.dashboard.stock.critical_stock') }}
                                </span>
                            @elseif(($stats['stock']['low_stock'] ?? 0) > 0)
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></span>
                                    {{ $stats['stock']['low_stock'] }} {{ __('app.pages.dashboard.stock.low_stock') }}
                                </span>
                            @else
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                    {{ __('app.pages.dashboard.stock.all_good') }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ __('app.pages.dashboard.stock.description') }}</p>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <div class="flex space-x-2">
                        <button @click="refreshStockData()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('app.pages.dashboard.stock.refresh') }}
                        </button>
                        <a href="{{ route('admin.products.index') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            {{ __('app.pages.dashboard.stock.manage_products') }}
                        </a>
                    </div>
                    
                    <!-- Contrôles de notifications -->
                    <div class="flex space-x-2 text-xs">
                        <button @click="toggleSoundNotifications()" 
                                :class="notifications.sound ? 'bg-purple-100 text-purple-800 border-purple-300' : 'bg-gray-100 text-gray-600 border-gray-300'"
                                class="border px-3 py-1 rounded-full transition-colors flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728m-9.9-2.828a5 5 0 007.072 0m2.828-9.9a9 9 0 000 12.728M6.343 6.343L4.93 4.93m12.728 12.728L16.24 16.24"/>
                            </svg>
                            <span x-text="notifications.sound ? '{{ __('app.pages.dashboard.stock.sound_on') }}' : '{{ __('app.pages.dashboard.stock.sound_off') }}'"></span>
                        </button>
                        
                        <button @click="toggleDesktopNotifications()" 
                                :class="notifications.desktop ? 'bg-indigo-100 text-indigo-800 border-indigo-300' : 'bg-gray-100 text-gray-600 border-gray-300'"
                                class="border px-3 py-1 rounded-full transition-colors flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 4.828A4 4 0 015.858 4H9a4 4 0 014 4v1a1.97 1.97 0 00.971 1.691l2.026 1.18a2 2 0 011.003 1.73V17h-5"/>
                            </svg>
                            <span x-text="notifications.desktop ? '{{ __('app.pages.dashboard.stock.desktop_on') }}' : '{{ __('app.pages.dashboard.stock.desktop_off') }}'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistiques de Stock -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Rupture de Stock -->
                <div class="@if(($stats['stock']['out_of_stock'] ?? 0) > 0) bg-red-50 border-red-300 shadow-lg shadow-red-200 card-alert-critical stock-critical @else bg-red-50 @endif border border-red-200 rounded-lg p-4 transition-all-smooth">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center @if(($stats['stock']['out_of_stock'] ?? 0) > 0) animate-bounce notification-bubble @endif">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-bold text-red-900">{{ $stats['stock']['out_of_stock'] }}</p>
                            <p class="text-sm text-red-700">{{ __('app.pages.dashboard.stock.out_of_stock_title') }}</p>
                            @if(($stats['stock']['out_of_stock'] ?? 0) > 0)
                                <p class="text-xs text-red-600 font-medium mt-1 animate-pulse-fast">⚠️ Action requise</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock Critique -->
                <div class="@if(($stats['stock']['critical_stock'] ?? 0) > 0) bg-orange-50 border-orange-300 shadow-md shadow-orange-200 card-alert-warning stock-warning @else bg-orange-50 @endif border border-orange-200 rounded-lg p-4 transition-all-smooth">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center @if(($stats['stock']['critical_stock'] ?? 0) > 0) animate-pulse @endif">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-bold text-orange-900">{{ $stats['stock']['critical_stock'] }}</p>
                            <p class="text-sm text-orange-700">{{ __('app.pages.dashboard.stock.critical_stock_title') }}</p>
                            @if(($stats['stock']['critical_stock'] ?? 0) > 0)
                                <p class="text-xs text-orange-600 font-medium mt-1 animate-pulse">⚡ Réapprovisionnement recommandé</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock Bas -->
                <div class="@if(($stats['stock']['low_stock'] ?? 0) > 0) bg-yellow-50 border-yellow-300 shadow-sm shadow-yellow-200 card-alert-info @else bg-yellow-50 @endif border border-yellow-200 rounded-lg p-4 transition-all-smooth">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center @if(($stats['stock']['low_stock'] ?? 0) > 0) stock-notification-dot @endif">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-bold text-yellow-900">{{ $stats['stock']['low_stock'] }}</p>
                            <p class="text-sm text-yellow-700">{{ __('app.pages.dashboard.stock.low_stock_title') }}</p>
                            @if(($stats['stock']['low_stock'] ?? 0) > 0)
                                <p class="text-xs text-yellow-600 font-medium mt-1">{{ __('app.pages.dashboard.stock.low_stock_monitoring') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock Normal -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-2xl font-bold text-green-900">{{ $stats['stock']['normal_stock'] }}</p>
                            <p class="text-sm text-green-700">{{ __('app.pages.dashboard.stock.normal_stock') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de Valeur du Stock -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-lg font-bold text-blue-900">{{ number_format($stats['stock']['total_stock_value'], 2) }}€</p>
                        <p class="text-sm text-blue-700">{{ __('app.pages.dashboard.stock.total_stock_value') }}</p>
                    </div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-lg font-bold text-red-900">{{ number_format($stats['stock']['critical_stock_value'], 2) }}€</p>
                        <p class="text-sm text-red-700">{{ __('app.pages.dashboard.stock.critical_stock_value') }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="text-center">
                        <p class="text-lg font-bold text-gray-900">{{ $stats['stock']['needs_attention'] }}</p>
                        <p class="text-sm text-gray-700">{{ __('app.pages.dashboard.stock.monitoring_required') }}</p>
                    </div>
                </div>
            </div>

            <!-- Alertes et Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Produits en Stock Critique -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-medium text-gray-900">{{ __('app.pages.dashboard.stock.critical_products_title') }}</h4>
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                            {{ $stats['stock']['critical_products']->count() }} {{ __('app.pages.dashboard.stock.products_count') }}
                        </span>
                    </div>
                    
                    @if($stats['stock']['critical_products']->count() > 0)
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($stats['stock']['critical_products'] as $product)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->category->name ?? __('app.pages.dashboard.stock.no_category') }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">
                                                {{ __('app.pages.dashboard.stock.stock_label') }}: {{ $product->quantity }}
                                            </span>
                                            <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                {{ __('app.pages.dashboard.stock.threshold_label') }}: {{ $product->critical_threshold }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-900">{{ number_format($product->price * $product->quantity, 2) }}€</p>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-xs text-blue-600 hover:text-blue-800">
                                            {{ __('app.pages.dashboard.stock.edit_action') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Actions de Réapprovisionnement -->
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-900">{{ __('app.pages.dashboard.stock.restock_suggestions') }}</p>
                                    <p class="text-xs text-yellow-700">{{ __('app.pages.dashboard.stock.restock_description') }}</p>
                                </div>
                                <button onclick="generateRestockSuggestions()" 
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                    {{ __('app.pages.dashboard.stock.generate_action') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">{{ __('app.pages.dashboard.stock.no_critical_stock') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Alertes Récentes -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-medium text-gray-900">{{ __('app.pages.dashboard.stock.recent_alerts_title') }}</h4>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                            {{ __('app.pages.dashboard.stock.last_days') }}
                        </span>
                    </div>
                    
                    @if($stats['stock']['recent_alerts']->count() > 0)
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($stats['stock']['recent_alerts'] as $alert)
                                <div class="p-3 bg-gray-50 rounded-lg border">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $alert->subject }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($alert->content, 100) }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                                        </div>
                                        @switch($alert->priority)
                                            @case('urgent')
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ __('app.pages.dashboard.stock.urgent_priority') }}</span>
                                                @break
                                            @case('high')
                                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">{{ __('app.pages.dashboard.stock.high_priority') }}</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">{{ __('app.pages.dashboard.stock.normal_priority') }}</span>
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">{{ __('app.pages.dashboard.stock.no_recent_alerts') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Statistiques Avancées -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Fréquentation du site -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg p-6 border border-blue-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4"/>
                        </svg>
                        {{ __('app.pages.dashboard.analytics.title') }}
                    </h3>
                    <p class="text-sm text-gray-600">{{ __('app.pages.dashboard.analytics.description') }}</p>
                </div>
                <a href="{{ route('admin.statistics') }}" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-blue-100">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['analytics']['unique_visitors'] ?? 1247) }}</div>
                        <div class="text-xs text-gray-600">{{ __('app.pages.dashboard.analytics.unique_visitors') }}</div>
                        <div class="text-xs text-green-600">+12.5% {{ __('app.pages.dashboard.analytics.monthly_growth') }}</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-blue-100">
                        <div class="text-2xl font-bold text-indigo-600">{{ number_format($stats['analytics']['page_views'] ?? 3842) }}</div>
                        <div class="text-xs text-gray-600">{{ __('app.pages.dashboard.analytics.page_views') }}</div>
                        <div class="text-xs text-green-600">+8.3% {{ __('app.pages.dashboard.analytics.monthly_growth') }}</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 border border-blue-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.analytics.bounce_rate') }}</span>
                        <span class="text-sm font-medium text-orange-600">{{ $stats['analytics']['bounce_rate'] ?? '42.3%' }}</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 border border-blue-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.analytics.avg_session_duration') }}</span>
                        <span class="text-sm font-medium text-blue-600">{{ $stats['analytics']['avg_session_duration'] ?? '2m 34s' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-lg p-6 border border-purple-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a1 1 0 001.42 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('app.pages.dashboard.newsletter.title') }}
                    </h3>
                    <p class="text-sm text-gray-600">{{ __('app.pages.dashboard.newsletter.description') }}</p>
                </div>
                <a href="{{ route('admin.newsletters.index') }}" class="text-purple-600 hover:text-purple-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['newsletter']['subscribers'] ?? 0) }}</div>
                        <div class="text-xs text-gray-600">{{ __('app.pages.dashboard.newsletter.active_subscribers') }}</div>
                        <div class="text-xs {{ ($stats['newsletter']['growth_rate'] ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($stats['newsletter']['growth_rate'] ?? 0) > 0 ? '+' : '' }}{{ $stats['newsletter']['growth_rate'] ?? '+5.2' }}% {{ __('app.pages.dashboard.newsletter.this_month') }}
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                        <div class="text-2xl font-bold text-pink-600">{{ $stats['newsletter']['sent_count'] ?? 0 }}</div>
                        <div class="text-xs text-gray-600">{{ __('app.pages.dashboard.newsletter.emails_sent') }}</div>
                        <div class="text-xs text-blue-600">{{ __('app.pages.dashboard.newsletter.this_month') }}</div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 border border-purple-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.newsletter.open_rate') }}</span>
                        <span class="text-sm font-medium text-green-600">{{ $stats['newsletter']['open_rate'] ?? '0.0' }}%</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-3 border border-purple-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.newsletter.click_rate') }}</span>
                        <span class="text-sm font-medium text-blue-600">{{ $stats['newsletter']['click_rate'] ?? '0.0' }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Locations -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-lg p-6 border border-green-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        {{ __('app.pages.dashboard.rentals.title') }}
                    </h3>
                    <p class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.description') }}</p>
                </div>
                <a href="{{ route('admin.rental-returns.index') }}" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg p-3 border border-green-100">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($stats['rentals']['total_orders'] ?? 0) }}</div>
                        <div class="text-xs text-gray-600">{{ __('app.pages.dashboard.rentals.total_orders') }}</div>
                        <div class="text-xs text-blue-600">{{ __('app.pages.dashboard.rentals.total') }}</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-green-100">
                        <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['rentals']['monthly_revenue'] ?? 0, 2) }}€</div>
                        <div class="text-xs text-gray-600">{{ __('app.pages.dashboard.rentals.monthly_revenue') }}</div>
                        <div class="text-xs text-green-600">{{ __('app.pages.dashboard.newsletter.this_month') }}</div>
                    </div>
                </div>
                
                <!-- Détails des statuts de location -->
                <div class="space-y-2">
                    <div class="bg-white rounded-lg p-2 border border-green-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.active_orders') }}</span>
                            <span class="text-sm font-medium text-green-600">{{ $stats['rentals']['active_orders'] ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-2 border border-green-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.closed_orders') }}</span>
                            <span class="text-sm font-medium text-orange-600">{{ $stats['rentals']['closed_orders'] ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-2 border border-green-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.inspecting_orders') }}</span>
                            <span class="text-sm font-medium text-indigo-600">{{ $stats['rentals']['inspecting_orders'] ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-2 border border-green-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.finished_orders') }}</span>
                            <span class="text-sm font-medium text-emerald-600">{{ $stats['rentals']['finished_orders'] ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg p-2 border border-green-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.pending_returns') }}</span>
                            <span class="text-sm font-medium text-purple-600">{{ $stats['rentals']['pending_returns'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Graphiques de Performance -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">📊 {{ __('app.pages.dashboard.performance.title') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('app.pages.dashboard.performance.description') }}</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="refreshCharts()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('app.pages.dashboard.stock.refresh') }}
                    </button>
                    <a href="{{ route('admin.statistics') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        {{ __('app.pages.dashboard.performance.detailed_view') }}
                    </a>
                </div>
            </div>

            <!-- Mini graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Graphique visiteurs -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('app.pages.dashboard.performance.visitor_evolution') }}</h4>
                    <div class="flex items-end space-x-1 h-20">
                        @php
                            $visitorData = [45, 52, 48, 61, 55, 67, 73]; // Données de démonstration
                        @endphp
                        @foreach($visitorData as $day => $value)
                            <div class="flex-1 bg-blue-500 rounded-t" 
                                 style="height: {{ $value }}%;" 
                                 title="Jour {{ $day + 1 }}: {{ $value }}% des visiteurs max">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>{{ __('app.pages.dashboard.days.mon') }}</span>
                        <span>{{ __('app.pages.dashboard.days.tue') }}</span>
                        <span>{{ __('app.pages.dashboard.days.wed') }}</span>
                        <span>{{ __('app.pages.dashboard.days.thu') }}</span>
                        <span>{{ __('app.pages.dashboard.days.fri') }}</span>
                        <span>{{ __('app.pages.dashboard.days.sat') }}</span>
                        <span>{{ __('app.pages.dashboard.days.sun') }}</span>
                    </div>
                </div>

                <!-- Graphique commandes -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('app.pages.dashboard.performance.recent_orders') }}</h4>
                    <div class="flex items-end space-x-1 h-20">
                        @php
                            $orderData = [12, 18, 15, 22, 19, 25, 21]; // Données de démonstration
                        @endphp
                        @foreach($orderData as $day => $orders)
                            <div class="flex-1 bg-green-500 rounded-t" 
                                 style="height: {{ ($orders / 25) * 100 }}%;" 
                                 title="Jour {{ $day + 1 }}: {{ $orders }} commandes">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>{{ __('app.pages.dashboard.days.mon') }}</span>
                        <span>{{ __('app.pages.dashboard.days.tue') }}</span>
                        <span>{{ __('app.pages.dashboard.days.wed') }}</span>
                        <span>{{ __('app.pages.dashboard.days.thu') }}</span>
                        <span>{{ __('app.pages.dashboard.days.fri') }}</span>
                        <span>{{ __('app.pages.dashboard.days.sat') }}</span>
                        <span>{{ __('app.pages.dashboard.days.sun') }}</span>
                    </div>
                </div>

                <!-- Graphique newsletter -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('app.pages.dashboard.performance.newsletter_performance') }}</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ __('app.pages.dashboard.newsletter.open_rate') }}</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $stats['newsletter']['open_rate'] ?? 65 }}%;"></div>
                                </div>
                                <span class="text-xs font-medium">{{ $stats['newsletter']['open_rate'] ?? 65 }}%</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ __('app.pages.dashboard.newsletter.click_rate') }}</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-pink-500 h-2 rounded-full" style="width: {{ $stats['newsletter']['click_rate'] ?? 23 }}%;"></div>
                                </div>
                                <span class="text-xs font-medium">{{ $stats['newsletter']['click_rate'] ?? 23 }}%</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">{{ __('app.pages.dashboard.newsletter.growth_rate') }}</span>
                            <span class="text-xs font-medium text-green-600">{{ $stats['newsletter']['growth_rate'] ?? '+5.2' }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé de performance -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['analytics']['visitors'] ?? 1247) }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.pages.dashboard.analytics.visitors_total') }}</div>
                    <div class="text-xs text-green-600 mt-1">+12.5% {{ __('app.pages.dashboard.analytics.monthly_growth') }}</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['orders'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.pages.dashboard.stats.orders') }}</div>
                    <div class="text-xs text-blue-600 mt-1">{{ __('app.pages.dashboard.performance.total_site') }}</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['newsletter']['subscribers'] ?? 0) }}</div>
                    <div class="text-sm text-gray-600">{{ __('app.pages.dashboard.performance.newsletter_subscribers') }}</div>
                    <div class="text-xs {{ ($stats['newsletter']['growth_rate'] ?? 0) > 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        {{ ($stats['newsletter']['growth_rate'] ?? 0) > 0 ? '+' : '' }}{{ $stats['newsletter']['growth_rate'] ?? '+5.2' }}% {{ __('app.pages.dashboard.analytics.monthly_growth') }}
                    </div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['rentals']['monthly_revenue'] ?? 0, 2) }}€</div>
                    <div class="text-sm text-gray-600">{{ __('app.pages.dashboard.rentals.monthly_revenue') }}</div>
                    <div class="text-xs text-emerald-600 mt-1">{{ __('app.pages.dashboard.actions.this_month') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Utilisateurs récents -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('app.pages.dashboard.recent.users') }}</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600 hover:text-green-500">
                        {{ __('app.pages.dashboard.recent.view_all') }}
                    </a>
                </div>
                
                @if($stats['recent_users']->count() > 0)
                    <div class="space-y-3">
                        @foreach($stats['recent_users'] as $user)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                                    @else
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">{{ __('app.pages.dashboard.recent.no_users') }}</p>
                @endif
            </div>
        </div>

        <!-- Articles de blog récents -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('app.pages.dashboard.recent.articles') }}</h3>
                    <a href="{{ route('admin.blog.index') }}" class="text-sm text-green-600 hover:text-green-500">
                        {{ __('app.pages.dashboard.recent.view_all') }}
                    </a>
                </div>
                
                @if(isset($stats['recent_blog_posts']) && $stats['recent_blog_posts']->count() > 0)
                    <div class="space-y-3">
                        @foreach($stats['recent_blog_posts'] as $post)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-12 h-8 object-cover rounded">
                                    @else
                                        <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $post->title }}</p>
                                    @if($post->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                            {{ $post->category->name }}
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @switch($post->status)
                                        @case('published')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('app.pages.dashboard.status.published') }}
                                            </span>
                                            @break
                                        @case('draft')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ __('app.pages.dashboard.status.draft') }}
                                            </span>
                                            @break
                                        @case('scheduled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ __('app.pages.dashboard.status.scheduled') }}
                                            </span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">{{ __('app.pages.dashboard.recent.no_articles') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('app.pages.dashboard.quick_actions') }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <a href="{{ route('admin.blog.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ __('app.pages.dashboard.actions.manage_articles') }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.pages.dashboard.actions.view_all_articles') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.blog-categories.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ __('app.pages.dashboard.actions.manage_categories') }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.pages.dashboard.actions.view_all_categories') }}</p>
                    </div>
                </a>

                <a href="/blog" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ __('app.pages.dashboard.actions.view_blog') }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.pages.dashboard.actions.blog_public_page') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ __('app.pages.dashboard.actions.manage_users') }}</p>
                        <p class="text-xs text-gray-500">{{ __('app.pages.dashboard.actions.view_all_users') }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.blog-comments.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ __('app.pages.dashboard.actions.manage_comments') }}</p>
                        <p class="text-xs text-gray-500">
                            @if($stats['pending_comments'] > 0)
                                {{ $stats['pending_comments'] }} {{ __('app.pages.dashboard.actions.pending_comments') }}
                            @else
                                {{ __('app.pages.dashboard.actions.all_comments') }}
                            @endif
                        </p>
                    </div>
                </a>

                <a href="{{ route('admin.blog-comment-reports.index') }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ __('app.pages.dashboard.actions.reports') }}</p>
                        <p class="text-xs text-gray-500">
                            @if($stats['pending_reports'] > 0)
                                {{ $stats['pending_reports'] }} à traiter
                            @else
                                Aucun signalement
                            @endif
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Système de Réapprovisionnement Automatique
async function generateRestockSuggestions() {
    const button = event.target;
    const originalText = button.textContent;
    
    // Affichage de l'état de chargement
    button.textContent = 'Génération...';
    button.disabled = true;
    
    try {
        const response = await fetch('/admin/stock/restock-suggestions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Créer une modal avec les suggestions
            showRestockModal(data.suggestions);
        } else {
            alert('Erreur lors de la génération des suggestions: ' + data.message);
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur de réseau lors de la génération des suggestions');
    } finally {
        button.textContent = originalText;
        button.disabled = false;
    }
}

function showRestockModal(suggestions) {
    // Créer la modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full m-4 max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">🔄 Suggestions de Réapprovisionnement</h3>
                    <button onclick="closeRestockModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Suggestions basées sur les ventes des 30 derniers jours et les seuils définis.</p>
                </div>
                
                <div class="space-y-4">
                    ${suggestions.map(suggestion => `
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${suggestion.product_name}</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2 text-sm">
                                        <div>
                                            <span class="text-gray-500">Stock actuel:</span>
                                            <span class="font-medium text-red-600">${suggestion.current_stock}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Ventes/mois:</span>
                                            <span class="font-medium">${suggestion.monthly_sales}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Stock recommandé:</span>
                                            <span class="font-medium text-green-600">${suggestion.recommended_stock}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">À commander:</span>
                                            <span class="font-bold text-blue-600">${suggestion.quantity_to_order}</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-gray-500 text-sm">Coût estimé:</span>
                                        <span class="font-bold text-gray-900">${suggestion.estimated_cost}€</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <button onclick="applyRestock(${suggestion.product_id}, ${suggestion.quantity_to_order})" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Appliquer
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                
                <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <strong>Coût total estimé: ${suggestions.reduce((sum, s) => sum + parseFloat(s.estimated_cost), 0).toFixed(2)}€</strong>
                    </div>
                    <div class="space-x-2">
                        <button onclick="closeRestockModal()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition-colors">
                            Fermer
                        </button>
                        <button onclick="applyAllRestock()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors">
                            Appliquer Tout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.id = 'restock-modal';
}

function closeRestockModal() {
    const modal = document.getElementById('restock-modal');
    if (modal) {
        modal.remove();
    }
}

async function applyRestock(productId, quantity) {
    try {
        const response = await fetch(`/admin/products/${productId}/restock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Stock mis à jour avec succès!');
            location.reload(); // Recharger la page pour mettre à jour les données
        } else {
            alert('Erreur lors de la mise à jour: ' + data.message);
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour du stock');
    }
}

async function applyAllRestock() {
    if (confirm('Êtes-vous sûr de vouloir appliquer toutes les suggestions de réapprovisionnement?')) {
        try {
            const response = await fetch('/admin/stock/apply-all-restock', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(`Réapprovisionnement appliqué avec succès! ${data.updated_products} produits mis à jour.`);
                location.reload();
            } else {
                alert('Erreur lors du réapprovisionnement: ' + data.message);
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors du réapprovisionnement automatique');
        }
    }
}

// Gestion de Stock Avancée avec Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('stockManagement', () => ({
        autoRestockEnabled: localStorage.getItem('autoRestockEnabled') === 'true',
        autoConfig: {
            predictiveAlerts: localStorage.getItem('predictiveAlerts') === 'true',
            weeklyReports: localStorage.getItem('weeklyReports') === 'true',
            supplierIntegration: localStorage.getItem('supplierIntegration') === 'true'
        },
        notifications: {
            sound: localStorage.getItem('stockNotificationSound') !== 'false',
            desktop: localStorage.getItem('stockNotificationDesktop') !== 'false'
        },
        
        init() {
            // Vérifier les notifications au chargement
            this.checkStockAlerts();
            
            // Vérifier périodiquement toutes les 5 minutes
            setInterval(() => {
                this.checkStockAlerts();
            }, 300000); // 5 minutes
        },
        
        // Vérifier les alertes de stock
        checkStockAlerts() {
            const outOfStock = {{ $stats['stock']['out_of_stock'] ?? 0 }};
            const criticalStock = {{ $stats['stock']['critical_stock'] ?? 0 }};
            const lowStock = {{ $stats['stock']['low_stock'] ?? 0 }};
            
            if (outOfStock > 0) {
                this.showStockAlert('Rupture de stock détectée!', `${outOfStock} produit(s) en rupture de stock`, 'error');
            } else if (criticalStock > 0) {
                this.showStockAlert('Stock critique!', `${criticalStock} produit(s) en stock critique`, 'warning');
            } else if (lowStock > 0) {
                this.showStockAlert('Stock bas', `${lowStock} produit(s) en stock bas`, 'info');
            }
        },
        
        // Afficher une alerte de stock
        showStockAlert(title, message, type = 'info') {
            // Notification sonore
            if (this.notifications.sound) {
                this.playNotificationSound(type);
            }
            
            // Notification desktop (si autorisée)
            if (this.notifications.desktop && 'Notification' in window) {
                if (Notification.permission === 'granted') {
                    new Notification(title, {
                        body: message,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico'
                    });
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            new Notification(title, {
                                body: message,
                                icon: '/favicon.ico'
                            });
                        }
                    });
                }
            }
        },
        
        // Jouer un son de notification
        playNotificationSound(type) {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            let frequency;
            
            switch(type) {
                case 'error':
                    frequency = [800, 600]; // Son d'alerte grave
                    break;
                case 'warning':
                    frequency = [600, 800]; // Son d'avertissement
                    break;
                default:
                    frequency = [400, 500]; // Son d'information
            }
            
            frequency.forEach((freq, index) => {
                setTimeout(() => {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.value = freq;
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.2);
                }, index * 200);
            });
        },
        
        // Basculer les notifications sonores
        toggleSoundNotifications() {
            this.notifications.sound = !this.notifications.sound;
            localStorage.setItem('stockNotificationSound', this.notifications.sound);
            this.showNotification(
                this.notifications.sound ? 'Notifications sonores activées' : 'Notifications sonores désactivées',
                'success'
            );
        },
        
        // Basculer les notifications desktop
        toggleDesktopNotifications() {
            this.notifications.desktop = !this.notifications.desktop;
            localStorage.setItem('stockNotificationDesktop', this.notifications.desktop);
            
            if (this.notifications.desktop && 'Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
            
            this.showNotification(
                this.notifications.desktop ? 'Notifications desktop activées' : 'Notifications desktop désactivées',
                'success'
            );
        },
        
        // Actualiser les données de stock
        async refreshStockData() {
            try {
                const response = await fetch(`{{ route('admin.dashboard') }}?refresh=stock`);
                if (response.ok) {
                    location.reload();
                }
            } catch (error) {
                console.error('Erreur lors de l\'actualisation:', error);
                this.showNotification('Erreur lors de l\'actualisation', 'error');
            }
        },

        // Générer une suggestion de réapprovisionnement pour un produit
        async generateRestockSuggestion(productId) {
            try {
                const response = await fetch(`/admin/products/${productId}/restock-suggestion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showRestockModal(data.suggestion);
                } else {
                    this.showNotification('Erreur lors de la génération de suggestion', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur de connexion', 'error');
            }
        },

        // Générer toutes les suggestions de réapprovisionnement
        async generateAllRestockSuggestions() {
            try {
                const response = await fetch('/admin/stock/restock-suggestions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showBulkRestockModal(data.suggestions);
                } else {
                    this.showNotification('Erreur lors de la génération des suggestions', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur de connexion', 'error');
            }
        },

        // Basculer le réapprovisionnement automatique
        toggleAutoRestock() {
            this.autoRestockEnabled = !this.autoRestockEnabled;
            localStorage.setItem('autoRestockEnabled', this.autoRestockEnabled);
            this.showNotification(
                `Réapprovisionnement automatique ${this.autoRestockEnabled ? 'activé' : 'désactivé'}`,
                this.autoRestockEnabled ? 'success' : 'info'
            );
        },

        // Générer un rapport hebdomadaire
        async generateWeeklyReport() {
            try {
                const response = await fetch('/admin/stock/weekly-report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `rapport-stock-${new Date().toISOString().split('T')[0]}.pdf`;
                    a.click();
                    window.URL.revokeObjectURL(url);
                    this.showNotification('Rapport généré avec succès', 'success');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de la génération du rapport', 'error');
            }
        },

        // Exporter les données de stock
        async exportStockData() {
            try {
                const response = await fetch('/admin/stock/export', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `donnees-stock-${new Date().toISOString().split('T')[0]}.xlsx`;
                    a.click();
                    window.URL.revokeObjectURL(url);
                    this.showNotification('Données exportées avec succès', 'success');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de l\'export', 'error');
            }
        },

        // Actualiser les tendances
        async refreshTrends() {
            this.showNotification('Actualisation des tendances...', 'info');
            await this.refreshStockData();
        },

        // Import en masse de stock
        bulkUpdateStock() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.csv,.xlsx,.xls';
            input.onchange = async (e) => {
                const file = e.target.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('file', file);
                    
                    try {
                        const response = await fetch('/admin/stock/bulk-update', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.showNotification(`${data.updated_count} produits mis à jour`, 'success');
                            await this.refreshStockData();
                        } else {
                            this.showNotification('Erreur lors de l\'import: ' + data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        this.showNotification('Erreur lors de l\'import de fichier', 'error');
                    }
                }
            };
            input.click();
        },

        // Générer un rapport global
        async generateGlobalReport() {
            this.showNotification('Génération du rapport global...', 'info');
            await this.generateWeeklyReport();
        },

        // Optimiser automatiquement les stocks
        async optimizeStock() {
            try {
                const response = await fetch('/admin/stock/optimize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(`Optimisation terminée: ${data.optimized_products} produits optimisés`, 'success');
                    await this.refreshStockData();
                } else {
                    this.showNotification('Erreur lors de l\'optimisation', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showNotification('Erreur lors de l\'optimisation', 'error');
            }
        },

        // Planifier une analyse
        scheduleAnalysis() {
            // Ouvrir une modal pour planifier l'analyse
            this.showNotification('Fonctionnalité de planification en développement', 'info');
        },

        // Afficher une notification
        showNotification(message, type = 'info') {
            // Créer une notification temporaire
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },

        // Afficher une modal de réapprovisionnement
        showRestockModal(suggestion) {
            const modal = `
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 class="text-lg font-medium mb-4">{{ __('app.pages.dashboard.restocking.suggestion_title') }}</h3>
                        <div class="space-y-3">
                            <p><span class="font-medium">Produit:</span> ${suggestion.product_name}</p>
                            <p><span class="font-medium">Stock actuel:</span> ${suggestion.current_stock}</p>
                            <p><span class="font-medium">Stock recommandé:</span> ${suggestion.recommended_stock}</p>
                            <p><span class="font-medium">Quantité à commander:</span> ${suggestion.quantity_to_order}</p>
                            <p><span class="font-medium">Coût estimé:</span> ${suggestion.estimated_cost}€</p>
                        </div>
                        <div class="mt-6 flex space-x-3">
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition-colors">
                                Annuler
                            </button>
                            <button onclick="applyRestock(${suggestion.product_id}, ${suggestion.quantity_to_order}); this.closest('.fixed').remove()" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors">
                                Appliquer
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
        },

        // Afficher une modal de réapprovisionnement en masse
        showBulkRestockModal(suggestions) {
            const suggestionsHtml = suggestions.map(s => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium">${s.product_name}</p>
                        <p class="text-sm text-gray-600">Recommandé: +${s.quantity_to_order} (${s.estimated_cost}€)</p>
                    </div>
                    <input type="checkbox" checked data-product-id="${s.product_id}" data-quantity="${s.quantity_to_order}">
                </div>
            `).join('');
            
            const modal = `
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                        <h3 class="text-lg font-medium mb-4">{{ __('app.pages.dashboard.restocking.bulk_suggestions_title') }}</h3>
                        <div class="space-y-2 mb-6">
                            ${suggestionsHtml}
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition-colors">
                                Annuler
                            </button>
                            <button onclick="applyBulkRestock(this.closest('.fixed')); this.closest('.fixed').remove()" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors">
                                Appliquer Sélection
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modal);
        }
    }));
});

// Fonctions globales pour les modals
async function applyRestock(productId, quantity) {
    try {
        const response = await fetch(`/admin/products/${productId}/restock`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity })
        });
        
        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

async function applyBulkRestock(modal) {
    const checkboxes = modal.querySelectorAll('input[type="checkbox"]:checked');
    const updates = Array.from(checkboxes).map(cb => ({
        product_id: cb.dataset.productId,
        quantity: cb.dataset.quantity
    }));
    
    try {
        const response = await fetch('/admin/stock/bulk-restock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ updates })
        });
        
        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}

// Auto-refresh des données de stock toutes les 5 minutes
setInterval(function() {
    // Actualiser uniquement la section stock sans recharger toute la page
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newStockSection = doc.querySelector('[x-data="stockManagement"]');
            const currentStockSection = document.querySelector('[x-data="stockManagement"]');
            
            if (newStockSection && currentStockSection) {
                // Garder l'état Alpine.js en réinitialisant seulement le contenu
                const stockStats = newStockSection.querySelectorAll('[class*="bg-"][class*="border"]');
                const currentStats = currentStockSection.querySelectorAll('[class*="bg-"][class*="border"]');
                
                stockStats.forEach((stat, index) => {
                    if (currentStats[index]) {
                        currentStats[index].innerHTML = stat.innerHTML;
                    }
                });
            }
        })
        .catch(error => console.error('Erreur lors de l\'actualisation:', error));
}, 300000); // 5 minutes

// Fonction pour actualiser les graphiques
function refreshCharts() {
    fetch('/admin/dashboard/refresh-charts', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDashboardNotification('Graphiques actualisés avec succès', 'success');
            // Recharger partiellement les données des graphiques
            location.reload();
        } else {
            showDashboardNotification('Erreur lors de l\'actualisation', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showDashboardNotification('Erreur de connexion', 'error');
    });
}

// Fonction notification générique pour le dashboard
function showDashboardNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
}
</script>

<style>
/* Styles personnalisés pour les notifications de stock */
@keyframes stockAlert {
    0% { 
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
        transform: scale(1.02);
    }
    100% { 
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        transform: scale(1);
    }
}

@keyframes stockWarning {
    0% { 
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 0 8px rgba(245, 158, 11, 0);
        transform: scale(1.01);
    }
    100% { 
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
        transform: scale(1);
    }
}

@keyframes notificationBounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-3px);
    }
    60% {
        transform: translateY(-1px);
    }
}

/* Application des animations selon les conditions de stock */
.stock-critical {
    animation: stockAlert 2s infinite;
}

.stock-warning {
    animation: stockWarning 3s infinite;
}

.notification-bubble {
    animation: notificationBounce 2s infinite;
}

/* Animation pour le pouls des badges */
.animate-pulse-fast {
    animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Amélioration des transitions */
.transition-all-smooth {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Style pour les indicateurs de notification */
.stock-notification-dot {
    position: relative;
}

.stock-notification-dot::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid white;
    animation: pulse 2s infinite;
}

/* Style spécial pour les cartes en alerte */
.card-alert-critical {
    border-left: 4px solid #ef4444 !important;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);
}

.card-alert-warning {
    border-left: 4px solid #f59e0b !important;
    box-shadow: 0 0 10px rgba(245, 158, 11, 0.2);
}

.card-alert-info {
    border-left: 4px solid #3b82f6 !important;
    box-shadow: 0 0 8px rgba(59, 130, 246, 0.2);
}
</style>
@endsection
