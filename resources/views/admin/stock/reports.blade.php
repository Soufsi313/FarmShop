@extends('layouts.admin')

@section('title', 'Rapports & Analyses - Gestion de Stock')
@section('page-title', 'Rapports & Analyses')

@section('content')
<div class="space-y-6" x-data="stockReports">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📊 Rapports & Analyses de Stock</h1>
            <p class="text-gray-600">Analyses détaillées et prévisions de votre inventaire</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.stock.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                ← Retour
            </a>
            <button @click="exportReport()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                📁 Exporter Rapport
            </button>
        </div>
    </div>

    <!-- Graphiques de répartition par catégorie -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique en barres - Stock par catégorie -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Répartition du Stock par Catégorie</h3>
            <div class="h-64">
                <canvas id="categoryStockChart"></canvas>
            </div>
        </div>

        <!-- Graphique en secteurs - Statut du stock -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🥧 Répartition par Statut de Stock</h3>
            <div class="h-64">
                <canvas id="stockStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top des produits -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">🏆 Top 10 des Produits les Plus Populaires</h3>
            <div class="flex space-x-2">
                <button @click="sortBy = 'views'; updateTopProducts()" 
                        :class="sortBy === 'views' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-3 py-1 rounded text-sm transition-colors">
                    Vues
                </button>
                <button @click="sortBy = 'likes'; updateTopProducts()" 
                        :class="sortBy === 'likes' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-3 py-1 rounded text-sm transition-colors">
                    Likes
                </button>
                <button @click="sortBy = 'stock_value'; updateTopProducts()" 
                        :class="sortBy === 'stock_value' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-3 py-1 rounded text-sm transition-colors">
                    Valeur
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vues</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Likes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topProducts as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-10 h-10 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 30) }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $product->category->name ?? 'Sans catégorie' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($product->quantity == 0)
                                        bg-red-100 text-red-800
                                    @elseif($product->quantity <= $product->critical_threshold)
                                        bg-orange-100 text-orange-800
                                    @elseif($product->low_stock_threshold && $product->quantity <= $product->low_stock_threshold)
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($product->price, 2) }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($product->views_count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($product->likes_count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($product->quantity * $product->price, 2) }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->quantity == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Rupture
                                    </span>
                                @elseif($product->quantity <= $product->critical_threshold)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Critique
                                    </span>
                                @elseif($product->low_stock_threshold && $product->quantity <= $product->low_stock_threshold)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Bas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Prévisions de stock -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">🔮 Prévisions de Rupture de Stock</h3>
        
        @if(count($stockForecasts) > 0)
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($stockForecasts as $forecast)
                    <div class="border border-gray-200 rounded-lg p-4 
                                {{ $forecast['days_remaining'] <= 7 ? 'bg-red-50 border-red-200' : 
                                   ($forecast['days_remaining'] <= 14 ? 'bg-orange-50 border-orange-200' : 'bg-yellow-50 border-yellow-200') }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $forecast['product']->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $forecast['product']->category->name ?? 'Sans catégorie' }}</p>
                                
                                <div class="mt-3 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Stock actuel:</span>
                                        <span class="font-medium">{{ $forecast['product']->quantity }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Ventes mensuelles:</span>
                                        <span class="font-medium">{{ $forecast['monthly_sales'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Jours restants:</span>
                                        <span class="font-medium 
                                              {{ $forecast['days_remaining'] <= 7 ? 'text-red-600' : 
                                                 ($forecast['days_remaining'] <= 14 ? 'text-orange-600' : 'text-yellow-600') }}">
                                            {{ $forecast['days_remaining'] > 0 ? $forecast['days_remaining'] . ' jours' : 'Épuisé' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Rupture prévue:</span>
                                        <span class="font-medium">{{ $forecast['estimated_outage_date'] }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ml-4 text-center">
                                @if($forecast['days_remaining'] <= 7)
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-red-600">URGENT</span>
                                @elseif($forecast['days_remaining'] <= 14)
                                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-orange-600">BIENTÔT</span>
                                @else
                                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-yellow-600">ATTENTION</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ route('admin.products.edit', $forecast['product']) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm transition-colors text-center block">
                                Gérer ce produit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-green-600">Aucune rupture prévue !</h3>
                <p class="mt-1 text-sm text-gray-500">Vos stocks sont bien gérés.</p>
            </div>
        @endif
    </div>

    <!-- Actions d'export -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📁 Exports et Rapports</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button @click="exportStockData()" 
                    class="flex items-center justify-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Export Excel</span>
                    <p class="text-xs text-gray-500 mt-1">Données complètes</p>
                </div>
            </button>

            <button @click="generateWeeklyReport()" 
                    class="flex items-center justify-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Rapport PDF</span>
                    <p class="text-xs text-gray-500 mt-1">Synthèse hebdomadaire</p>
                </div>
            </button>

            <button @click="scheduleReport()" 
                    class="flex items-center justify-center p-4 bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                <div class="text-center">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Planifier</span>
                    <p class="text-xs text-gray-500 mt-1">Rapports automatiques</p>
                </div>
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stockReports', () => ({
        sortBy: 'views',
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            // Graphique par catégorie
            const categoryCtx = document.getElementById('categoryStockChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['categories']) !!},
                    datasets: [{
                        label: 'Stock Normal',
                        data: {!! json_encode($chartData['stock_data']->pluck('normal')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.8)'
                    }, {
                        label: 'Stock Bas',
                        data: {!! json_encode($chartData['stock_data']->pluck('low')) !!},
                        backgroundColor: 'rgba(245, 158, 11, 0.8)'
                    }, {
                        label: 'Stock Critique',
                        data: {!! json_encode($chartData['stock_data']->pluck('critical')) !!},
                        backgroundColor: 'rgba(249, 115, 22, 0.8)'
                    }, {
                        label: 'Rupture',
                        data: {!! json_encode($chartData['stock_data']->pluck('out')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
            
            // Graphique de statut
            const statusCtx = document.getElementById('stockStatusChart').getContext('2d');
            const totalNormal = {!! $chartData['stock_data']->sum('normal') !!};
            const totalLow = {!! $chartData['stock_data']->sum('low') !!};
            const totalCritical = {!! $chartData['stock_data']->sum('critical') !!};
            const totalOut = {!! $chartData['stock_data']->sum('out') !!};
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Stock Normal', 'Stock Bas', 'Stock Critique', 'Rupture'],
                    datasets: [{
                        data: [totalNormal, totalLow, totalCritical, totalOut],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(249, 115, 22, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        },
        
        updateTopProducts() {
            // Simulation - Dans un cas réel, vous feriez un appel AJAX
            console.log('Tri par:', this.sortBy);
        },
        
        async exportStockData() {
            try {
                const response = await fetch('/admin/stock/export', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `export-stock-${new Date().toISOString().split('T')[0]}.csv`;
                    a.click();
                    window.URL.revokeObjectURL(url);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'export');
            }
        },
        
        async generateWeeklyReport() {
            try {
                const response = await fetch('/admin/stock/weekly-report', {
                    method: 'POST',
                    headers: {
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
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de la génération du rapport');
            }
        },
        
        scheduleReport() {
            alert('Fonctionnalité de planification en développement');
        },
        
        exportReport() {
            this.exportStockData();
        }
    }));
});
</script>
@endsection
