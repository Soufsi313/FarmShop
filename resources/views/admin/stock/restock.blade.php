@extends('layouts.admin')

@section('title', __('stock.restock_page_title'))
@section('page-title', __('stock.header.restock'))

@section('content')
<div class="space-y-6" x-data="stockRestock">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('stock.restock_title') }}</h1>
            <p class="text-gray-600">{{ __('stock.restock_subtitle') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.stock.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('stock.back_button') }}
            </a>
            <button @click="generateAllSuggestions()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                {{ __('stock.refresh_suggestions') }}
            </button>
        </div>
    </div>

    <!-- Résumé des suggestions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">{{ __('stock.products_to_restock') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ count($suggestions) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">{{ __('stock.estimated_total_cost') }}</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format(collect($suggestions)->sum('estimated_cost'), 2) }}€
                    </p>
                </div>
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">{{ __('stock.urgent_priority') }}</p>
                    <p class="text-2xl font-bold text-red-900">
                        {{ collect($suggestions)->where('priority', 'urgent')->count() }}
                    </p>
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
                    <p class="text-gray-600 text-sm font-medium">{{ __('stock.total_quantity') }}</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ collect($suggestions)->sum('quantity_to_order') }}
                    </p>
                </div>
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Suggestions de réapprovisionnement -->
    @if(count($suggestions) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('stock.restock_suggestions') }}</h3>
                    <div class="flex space-x-2">
                        <button @click="selectAllSuggestions()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                            {{ __('stock.select_all') }}
                        </button>
                        <button @click="applySelectedSuggestions()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                            {{ __('stock.apply_selection') }}
                        </button>
                    </div>
                </div>
            </div>
                        </button>
                        <button @click="applySelectedSuggestions()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                            Appliquer Sélection
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    @foreach($suggestions as $index => $suggestion)
                        <div class="border border-gray-200 rounded-lg p-4 
                                    {{ $suggestion['priority'] === 'urgent' ? 'bg-red-50 border-red-200' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <input type="checkbox" 
                                           x-model="selectedSuggestions" 
                                           value="{{ $index }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="font-medium text-gray-900">{{ $suggestion['product']->name }}</h4>
                                            @if($suggestion['priority'] === 'urgent')
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-medium">
                                                    {{ __('stock.urgent_tag') }}
                                                </span>
                                            @elseif($suggestion['priority'] === 'high')
                                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full font-medium">
                                                    {{ __('stock.high_tag') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $suggestion['product']->category->name ?? __('stock.no_category') }}</p>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-sm">
                                            <div>
                                                <span class="text-gray-500">{{ __('stock.current_stock') }}:</span>
                                                <span class="font-medium 
                                                      {{ $suggestion['current_stock'] == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ $suggestion['current_stock'] }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{ __('stock.recommended_stock') }}:</span>
                                                <span class="font-medium text-green-600">{{ $suggestion['recommended_stock'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{ __('stock.to_order') }}:</span>
                                                <span class="font-medium text-blue-600">{{ $suggestion['quantity_to_order'] }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">{{ __('stock.monthly_sales') }}:</span>
                                                <span class="font-medium text-gray-900">{{ $suggestion['monthly_sales'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($suggestion['estimated_cost'], 2) }}€
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($suggestion['product']->price, 2) }}€ × {{ $suggestion['quantity_to_order'] }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex flex-col space-y-2">
                                        <button @click="openCustomRestockModal({{ $suggestion['product']->id }}, '{{ $suggestion['product']->name }}', {{ $suggestion['quantity_to_order'] }})" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.customize_button') }}
                                        </button>
                                        <button @click="applyQuickRestock({{ $suggestion['product']->id }}, {{ $suggestion['quantity_to_order'] }})" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            {{ __('stock.apply_button') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-green-600">{{ __('stock.no_restock_needed') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('stock.no_restock_needed_desc') }}</p>
            </div>
        </div>
    @endif

    <!-- Historique des réapprovisionnements -->
    @if($recentRestocks->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('stock.restock_history') }}</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($recentRestocks as $restock)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $restock->subject }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($restock->content, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $restock->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de réapprovisionnement personnalisé -->
    <div x-show="showCustomRestockModal" 
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
            <h3 class="text-lg font-medium mb-4">{{ __('stock.custom_restock_title') }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('stock.product_label') }}</label>
                    <p class="text-gray-900" x-text="customRestockProductName"></p>
                </div>
                <div>
                    <label for="customRestockQuantity" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('stock.quantity_to_add') }}
                        <span class="text-gray-500">
                            ({{ __('stock.suggested_quantity') }}: <span x-text="suggestedQuantity"></span>)
                        </span>
                    </label>
                    <input type="number" 
                           id="customRestockQuantity" 
                           x-model="customRestockQuantity"
                           min="1"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mt-6 flex space-x-3">
                <button @click="closeCustomRestockModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded transition-colors">
                    {{ __('stock.cancel_button') }}
                </button>
                <button @click="applyCustomRestock()" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors">
                    {{ __('stock.apply_button') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stockRestock', () => ({
        selectedSuggestions: [],
        showCustomRestockModal: false,
        customRestockProductId: null,
        customRestockProductName: '',
        customRestockQuantity: 0,
        suggestedQuantity: 0,
        
        selectAllSuggestions() {
            this.selectedSuggestions = Array.from({length: {{ count($suggestions) }}}, (_, i) => i.toString());
        },
        
        async applySelectedSuggestions() {
            if (this.selectedSuggestions.length === 0) {
                alert('Veuillez sélectionner au moins une suggestion');
                return;
            }
            
            const updates = this.selectedSuggestions.map(index => ({
                product_id: {{ json_encode(collect($suggestions)->pluck('product.id')->toArray()) }}[index],
                quantity: {{ json_encode(collect($suggestions)->pluck('quantity_to_order')->toArray()) }}[index]
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
                } else {
                    alert('Erreur lors du réapprovisionnement en masse');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        },
        
        async applyQuickRestock(productId, quantity) {
            try {
                // Créer FormData au lieu de JSON
                const formData = new FormData();
                formData.append('quantity', quantity);
                
                const response = await fetch(`/admin/products/${productId}/restock`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('Réapprovisionnement réussi:', result);
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
        
        openCustomRestockModal(productId, productName, suggestedQuantity) {
            this.customRestockProductId = productId;
            this.customRestockProductName = productName;
            this.suggestedQuantity = suggestedQuantity;
            this.customRestockQuantity = suggestedQuantity;
            this.showCustomRestockModal = true;
        },
        
        closeCustomRestockModal() {
            this.showCustomRestockModal = false;
            this.customRestockProductId = null;
            this.customRestockProductName = '';
        },
        
        async applyCustomRestock() {
            if (!this.customRestockProductId || !this.customRestockQuantity) return;
            
            await this.applyQuickRestock(this.customRestockProductId, this.customRestockQuantity);
            this.closeCustomRestockModal();
        },
        
        async generateAllSuggestions() {
            try {
                const response = await fetch('/admin/stock/restock-suggestions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Erreur lors de la génération des suggestions');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        }
    }));
});
</script>
@endsection
