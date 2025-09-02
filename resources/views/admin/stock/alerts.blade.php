@extends('layouts.admin')

@section('title', __('stock.alerts_page_title'))
@section('page-title', __('stock.alerts_title'))

@section('content')
<div class="space-y-6" x-data="stockAlerts">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('stock.alerts_title') }}</h1>
            <p class="text-gray-600">{{ __('stock.alerts_subtitle') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.stock.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('stock.back_button') }}
            </a>
            <button @click="openBulkThresholdModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('stock.configure_thresholds') }}
            </button>
        </div>
    </div>

    <!-- Résumé des alertes actives -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">{{ __('stock.out_of_stock_plural') }}</p>
                    <p class="text-3xl font-bold text-red-900">{{ $outOfStockProducts->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">{{ __('stock.critical_stock_summary') }}</p>
                    <p class="text-3xl font-bold text-orange-900">{{ $criticalProducts->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">{{ __('stock.low_stock_summary') }}</p>
                    <p class="text-3xl font-bold text-yellow-900">{{ $lowStockProducts->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs pour les différents types d'alertes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button @click="activeTab = 'outofstock'" 
                        :class="activeTab === 'outofstock' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('stock.tab_out_of_stock') }} ({{ $outOfStockProducts->count() }})
                </button>
                <button @click="activeTab = 'critical'" 
                        :class="activeTab === 'critical' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('stock.tab_critical') }} ({{ $criticalProducts->count() }})
                </button>
                <button @click="activeTab = 'low'" 
                        :class="activeTab === 'low' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('stock.tab_low') }} ({{ $lowStockProducts->count() }})
                </button>
                <button @click="activeTab = 'alerts'" 
                        :class="activeTab === 'alerts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                    {{ __('stock.tab_history') }}
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Ruptures de Stock -->
            <div x-show="activeTab === 'outofstock'">
                @if($outOfStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($outOfStockProducts as $product)
                            <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product->category->name ?? __('stock.no_category') }}</p>
                                        <p class="text-xs text-red-600 font-medium">{{ __('stock.out_of_stock_since') }} {{ $product->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">{{ __('stock.price_label') }}: {{ number_format($product->price, 2) }}€</p>
                                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">{{ __('stock.out_of_stock_status') }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button @click="openQuickRestockModal({{ $product->id }}, '{{ $product->name }}')" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.restock_button') }}
                                        </button>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.edit_button') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-green-600">{{ __('stock.no_out_of_stock') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('stock.no_out_of_stock_desc') }}</p>
                    </div>
                @endif
            </div>

            <!-- Stock Critique -->
            <div x-show="activeTab === 'critical'">
                @if($criticalProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($criticalProducts as $product)
                            <div class="flex items-center justify-between p-4 bg-orange-50 border border-orange-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product->category->name ?? __('stock.no_category') }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">
                                                {{ __('stock.stock_label') }}: {{ $product->quantity }}
                                            </span>
                                            <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                {{ __('stock.threshold_label') }}: {{ $product->critical_threshold }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">{{ number_format($product->price * $product->quantity, 2) }}€</p>
                                        <p class="text-xs text-gray-500">{{ __('stock.unit_price') }}: {{ number_format($product->price, 2) }}€</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button @click="openQuickRestockModal({{ $product->id }}, '{{ $product->name }}')" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.restock_button') }}
                                        </button>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.edit_button') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-green-600">{{ __('stock.no_critical_stock') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('stock.no_critical_stock_desc') }}</p>
                    </div>
                @endif
            </div>

            <!-- Stock Bas -->
            <div x-show="activeTab === 'low'">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product->category->name ?? __('stock.no_category') }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                                {{ __('stock.stock_label') }}: {{ $product->quantity }}
                                            </span>
                                            <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                {{ __('stock.low_threshold_label') }}: {{ $product->low_stock_threshold }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">{{ number_format($product->price * $product->quantity, 2) }}€</p>
                                        <p class="text-xs text-gray-500">{{ __('stock.unit_price') }}: {{ number_format($product->price, 2) }}€</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button @click="openQuickRestockModal({{ $product->id }}, '{{ $product->name }}')" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.restock_button') }}
                                        </button>
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.edit_button') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-green-600">{{ __('stock.no_low_stock') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('stock.no_low_stock_desc') }}</p>
                    </div>
                @endif
            </div>

            <!-- Historique des Alertes -->
            <div x-show="activeTab === 'alerts'">
                @if($recentAlerts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAlerts as $alert)
                            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg border">
                                <div class="flex-shrink-0 mt-1">
                                    @if(str_contains($alert->subject, 'rupture'))
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    @elseif(str_contains($alert->subject, 'critique'))
                                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                    @else
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $alert->subject }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($alert->content, 120) }}</p>
                                            <p class="text-xs text-gray-500 mt-2">{{ $alert->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="ml-4">
                                            @switch($alert->priority)
                                                @case('urgent')
                                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ __('stock.priority_urgent') }}</span>
                                                    @break
                                                @case('high')
                                                    <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">{{ __('stock.priority_high') }}</span>
                                                    @break
                                                @default
                                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">{{ __('stock.priority_normal') }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-500">{{ __('stock.no_recent_alerts') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('stock.no_recent_alerts_desc') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de réapprovisionnement rapide -->
    <div x-show="showQuickRestockModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         style="display: none;">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-lg font-medium mb-4">{{ __('stock.quick_restock_title') }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('stock.product_label') }}</label>
                    <p class="text-gray-900" x-text="selectedProductName"></p>
                </div>
                <div>
                    <label for="quickRestockQuantity" class="block text-sm font-medium text-gray-700 mb-2">{{ __('stock.quantity_to_add') }}</label>
                    <input type="number" 
                           id="quickRestockQuantity" 
                           x-model="quickRestockQuantity"
                           min="1"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-6 flex space-x-3">
                <button @click="closeQuickRestockModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition-colors">
                    {{ __('stock.cancel_button') }}
                </button>
                <button @click="applyQuickRestock()" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors">
                    {{ __('stock.apply_button') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stockAlerts', () => ({
        activeTab: 'outofstock',
        showQuickRestockModal: false,
        selectedProductId: null,
        selectedProductName: '',
        quickRestockQuantity: 10,
        
        openQuickRestockModal(productId, productName) {
            this.selectedProductId = productId;
            this.selectedProductName = productName;
            this.quickRestockQuantity = 10;
            this.showQuickRestockModal = true;
        },
        
        closeQuickRestockModal() {
            this.showQuickRestockModal = false;
            this.selectedProductId = null;
            this.selectedProductName = '';
        },
        
        async applyQuickRestock() {
            if (!this.selectedProductId || !this.quickRestockQuantity) return;
            
            try {
                // Créer FormData au lieu de JSON
                const formData = new FormData();
                formData.append('quantity', this.quickRestockQuantity);
                
                const response = await fetch(`/admin/products/${this.selectedProductId}/restock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('Réapprovisionnement réussi:', result);
                    this.closeQuickRestockModal();
                    location.reload();
                } else {
                    const error = await response.text();
                    console.error('Erreur HTTP:', response.status, error);
                    alert(`Erreur lors du réapprovisionnement: ${response.status}`);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        },
        
        openBulkThresholdModal() {
            alert('Fonctionnalité de configuration des seuils en développement');
        }
    }));
});
</script>
@endsection
