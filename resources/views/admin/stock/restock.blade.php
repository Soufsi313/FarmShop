@extends('layouts.admin')

@section('title', __('stock.restock.title'))
@section('page-title', __('stock.restock.page_title'))

@section('content')
<div class="container mx-auto px-4 py-8" x-data="restockManager">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        {{ __('stock.restock_title') }}
                    </h1>
                    <p class="mt-2 text-green-100">
                        {{ __('stock.restock_subtitle') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ count($suggestions) }}</div>
                    <div class="text-green-100">{{ __('stock.products_to_restock') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ count($suggestions) }}</div>
                    <div class="text-sm text-blue-700">{{ __('stock.products_to_restock') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m-3-6h6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ number_format($totalValue, 2) }}€</div>
                    <div class="text-sm text-green-700">{{ __('stock.total_value') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($averageStock, 1) }}</div>
                    <div class="text-sm text-yellow-700">{{ __('stock.average_stock') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-purple-600">{{ __('stock.today') }}</div>
                    <div class="text-sm text-purple-700">{{ __('stock.last_update') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex space-x-3 mb-4 sm:mb-0">
            <a href="{{ route('admin.stock.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('stock.back_button') }}
            </a>
            
            <button @click="generateAllSuggestions()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                {{ __('stock.refresh_suggestions') }}
            </button>
            
            <button @click="selectAllSuggestions()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('stock.select_all') }}
            </button>
        </div>

        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <span>Mode d'affichage :</span>
            <select x-model="displayMode" 
                    class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <option value="cards">Cartes</option>
                <option value="list">Liste</option>
            </select>
        </div>
    </div>

    <!-- Suggestions de réapprovisionnement -->
    @if(count($suggestions) > 0)
        <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ __('stock.restock_suggestions') }}</h3>
                <p class="text-sm text-gray-600">Gérez et appliquez les suggestions de réapprovisionnement</p>
            </div>
            
            <div class="space-y-4">
                @foreach($suggestions as $index => $suggestion)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors 
                                {{ $suggestion['priority'] === 'urgent' ? 'bg-red-50 border-red-200' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <input type="checkbox" 
                                       x-model="selectedProducts" 
                                       :value="{{ $index }}"
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="font-medium text-gray-900">{{ $suggestion['product']->name }}</h4>
                                        
                                        @if($suggestion['priority'] === 'urgent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Urgent
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Priorité élevée
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-1 text-sm text-gray-600">
                                        <span class="mr-4">Catégorie: {{ $suggestion['product']->category->name ?? 'Non définie' }}</span>
                                        <span class="mr-4">Stock actuel: {{ $suggestion['current_stock'] }}</span>
                                        <span>Ventes mensuelles: {{ $suggestion['monthly_sales'] }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="font-medium text-gray-900">{{ $suggestion['quantity_to_order'] }} unités</div>
                                    <div class="text-sm text-gray-600">{{ number_format($suggestion['estimated_cost'], 2) }}€</div>
                                </div>
                                
                                <button @click="applyRestock({{ $index }})" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm transition-colors">
                                    Réapprovisionner
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span x-text="selectedProducts.length"></span> produit(s) sélectionné(s)
                </div>
                <button @click="applySelectedSuggestions()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors disabled:opacity-50" 
                        :disabled="selectedProducts.length === 0">
                    Appliquer la sélection
                </button>
            </div>
        </div>
    @else
        <div class="bg-white shadow-lg rounded-xl p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Aucune suggestion</h3>
            <p class="mt-1 text-gray-500">Tous les produits ont un stock suffisant pour le moment.</p>
        </div>
    @endif

    <!-- Historique des réapprovisionnements récents -->
    @if(count($recentRestocks) > 0)
        <div class="bg-white shadow-lg rounded-xl p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Historique récent</h3>
                <p class="text-sm text-gray-600">Les dernières opérations de réapprovisionnement</p>
            </div>
            
            <div class="space-y-3">
                @foreach($recentRestocks as $restock)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                        <div>
                            <div class="font-medium text-gray-900">{{ $restock->subject }}</div>
                            <div class="text-sm text-gray-600">{{ $restock->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Terminé
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('restockManager', () => ({
        selectedProducts: [],
        displayMode: 'cards',
        
        init() {
            console.log('Restock Manager initialized');
        },
        
        selectAllSuggestions() {
            const totalSuggestions = {{ count($suggestions) }};
            this.selectedProducts = Array.from({length: totalSuggestions}, (_, i) => i);
        },
        
        generateAllSuggestions() {
            window.location.reload();
        },
        
        applyRestock(index) {
            if (confirm('Êtes-vous sûr de vouloir réapprovisionner ce produit ?')) {
                // Logique de réapprovisionnement
                console.log('Applying restock for index:', index);
            }
        },
        
        applySelectedSuggestions() {
            if (this.selectedProducts.length === 0) return;
            
            if (confirm(`Êtes-vous sûr de vouloir réapprovisionner ${this.selectedProducts.length} produit(s) ?`)) {
                console.log('Applying restock for selected products:', this.selectedProducts);
            }
        }
    }));
});
</script>
@endsection
