@extends('layouts.admin')

@section('title', 'Gestion de Stock - FarmShop Admin')
@section('page-title', 'Gestion de Stock')

@section('content')
<div class="space-y-6" x-data="stockOverview">
    <!-- En-tête avec actions rapides -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📦 Gestion de Stock</h1>
            <p class="text-gray-600">Vue d'ensemble et surveillance en temps réel de vos stocks</p>
        </div>
        <div class="flex space-x-3">
            <button @click="refreshData()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Actualiser
            </button>
            <a href="{{ route('admin.stock.restock') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                Réapprovisionner
            </a>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Rupture de Stock -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Rupture de Stock</p>
                    <p class="text-3xl font-bold text-red-900">{{ $stockStats['out_of_stock'] }}</p>
                    <p class="text-red-700 text-xs mt-1">
                        {{ number_format(($stockStats['out_of_stock'] / max(1, $stockStats['total_products'])) * 100, 1) }}% du catalogue
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
            @if($stockStats['out_of_stock'] > 0)
                <div class="mt-4">
                    <a href="{{ route('admin.stock.alerts') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors">
                        Voir les produits
                    </a>
                </div>
            @endif
        </div>

        <!-- Stock Critique -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">Stock Critique</p>
                    <p class="text-3xl font-bold text-orange-900">{{ $stockStats['critical_stock'] }}</p>
                    <p class="text-orange-700 text-xs mt-1">
                        {{ number_format(($stockStats['critical_stock'] / max(1, $stockStats['total_products'])) * 100, 1) }}% du catalogue
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
            @if($stockStats['critical_stock'] > 0)
                <div class="mt-4">
                    <a href="{{ route('admin.stock.alerts') }}" 
                       class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-xs transition-colors">
                        Voir les produits
                    </a>
                </div>
            @endif
        </div>

        <!-- Stock Bas -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Stock Bas</p>
                    <p class="text-3xl font-bold text-yellow-900">{{ $stockStats['low_stock'] }}</p>
                    <p class="text-yellow-700 text-xs mt-1">
                        {{ number_format(($stockStats['low_stock'] / max(1, $stockStats['total_products'])) * 100, 1) }}% du catalogue
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stock Normal -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Stock Normal</p>
                    <p class="text-3xl font-bold text-green-900">{{ $stockStats['normal_stock'] }}</p>
                    <p class="text-green-700 text-xs mt-1">
                        {{ number_format(($stockStats['normal_stock'] / max(1, $stockStats['total_products'])) * 100, 1) }}% du catalogue
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicateurs financiers -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Valeur Totale du Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stockStats['total_stock_value'], 2) }}€</p>
                </div>
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Valeur Stock Critique</p>
                    <p class="text-2xl font-bold text-red-900">{{ number_format($stockStats['critical_stock_value'], 2) }}€</p>
                </div>
                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Produits Nécessitant Attention</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $stockStats['needs_attention'] }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tendances des 7 derniers jours -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">📈 Tendances des Alertes (7 derniers jours)</h3>
            <a href="{{ route('admin.stock.reports') }}" 
               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Voir les rapports détaillés →
            </a>
        </div>
        
        <div class="grid grid-cols-7 gap-4">
            @foreach($trends as $trend)
                <div class="text-center">
                    <div class="text-xs text-gray-500 mb-2">{{ $trend['label'] }}</div>
                    <div class="space-y-1">
                        <div class="w-full bg-red-100 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(100, $trend['out_of_stock'] * 20) }}%"></div>
                        </div>
                        <div class="w-full bg-orange-100 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ min(100, $trend['critical_stock'] * 10) }}%"></div>
                        </div>
                        <div class="w-full bg-yellow-100 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(100, $trend['low_stock'] * 6) }}%"></div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">{{ $trend['total_alerts'] }} alertes</div>
                </div>
            @endforeach
        </div>
        
        <div class="flex justify-center space-x-6 mt-4 text-xs">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Rupture</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Critique</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Bas</span>
            </div>
        </div>
    </div>

    <!-- Stock par catégorie -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">📊 Stock par Catégorie</h3>
            <button @click="toggleCategoryView()" 
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                    x-text="showDetailedView ? 'Vue simplifiée' : 'Vue détaillée'">
            </button>
        </div>
        
        <div class="grid gap-4" :class="showDetailedView ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'">
            @foreach($categoriesWithStock as $categoryData)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-900">{{ $categoryData['category']->name }}</h4>
                        <span class="text-sm text-gray-500">{{ $categoryData['total_products'] }} produits</span>
                    </div>
                    
                    <div class="space-y-2">
                        <!-- Barre de progression du stock -->
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $total = $categoryData['total_products'];
                                $outWidth = $total > 0 ? ($categoryData['out_of_stock'] / $total) * 100 : 0;
                                $criticalWidth = $total > 0 ? ($categoryData['critical_stock'] / $total) * 100 : 0;
                                $lowWidth = $total > 0 ? ($categoryData['low_stock'] / $total) * 100 : 0;
                                $normalWidth = $total > 0 ? ($categoryData['normal_stock'] / $total) * 100 : 0;
                            @endphp
                            <div class="h-3 rounded-full flex">
                                @if($outWidth > 0)
                                    <div class="bg-red-500 rounded-l-full" style="width: {{ $outWidth }}%"></div>
                                @endif
                                @if($criticalWidth > 0)
                                    <div class="bg-orange-500" style="width: {{ $criticalWidth }}%"></div>
                                @endif
                                @if($lowWidth > 0)
                                    <div class="bg-yellow-500" style="width: {{ $lowWidth }}%"></div>
                                @endif
                                @if($normalWidth > 0)
                                    <div class="bg-green-500 rounded-r-full" style="width: {{ $normalWidth }}%"></div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Détails numériques -->
                        <div x-show="showDetailedView" class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-red-600">Rupture:</span>
                                <span class="font-medium">{{ $categoryData['out_of_stock'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-orange-600">Critique:</span>
                                <span class="font-medium">{{ $categoryData['critical_stock'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-yellow-600">Bas:</span>
                                <span class="font-medium">{{ $categoryData['low_stock'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-green-600">Normal:</span>
                                <span class="font-medium">{{ $categoryData['normal_stock'] }}</span>
                            </div>
                        </div>
                        
                        <!-- Valeur totale -->
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-sm text-gray-600">Valeur totale:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($categoryData['total_value'], 2) }}€</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Produits nécessitant attention immédiate -->
    @if($stockStats['critical_products']->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">🚨 Attention Immédiate Requise</h3>
                <a href="{{ route('admin.stock.alerts') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    Voir toutes les alertes
                </a>
            </div>
            
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($stockStats['critical_products']->take(6) as $product)
                    <div class="border border-red-200 bg-red-50 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->category->name ?? 'Sans catégorie' }}</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                        Stock: {{ $product->quantity }}
                                    </span>
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">
                                        Seuil: {{ $product->critical_threshold }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-red-900">
                                    {{ number_format($product->price * $product->quantity, 2) }}€
                                </p>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="mt-1 inline-block text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded transition-colors">
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Actions rapides -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.stock.alerts') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Gérer Alertes</span>
            </a>

            <a href="{{ route('admin.stock.restock') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Réapprovisionner</span>
            </a>

            <a href="{{ route('admin.stock.reports') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Rapports</span>
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="flex flex-col items-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Gérer Produits</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stockOverview', () => ({
        showDetailedView: false,
        
        refreshData() {
            location.reload();
        },
        
        toggleCategoryView() {
            this.showDetailedView = !this.showDetailedView;
        }
    }));
});
</script>
@endsection
