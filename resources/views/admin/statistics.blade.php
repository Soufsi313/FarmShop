@extends('layouts.admin')

@section('title', 'Statistiques Avancées - FarmShop Admin')
@section('page-title', 'Analytics & Statistiques')

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        padding: 16px;
        color: white;
        position: relative;
         const chartOptions = {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        },
        layout: {
            padding: 10
        }
    };idden;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 120px;
        width: 100%;
        max-width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-shrink: 0;
        box-sizing: border-box;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 4px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chart-container {
        background: white;
        border-radius: 8px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        max-width: 100%;
        overflow: hidden;
    }
    
    .section-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 20px;
        max-width: 100%;
        overflow: hidden;
    }
    
    .metric-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        flex-shrink: 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        max-width: 100%;
    }
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- En-tête avec filtres -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tableau de Bord Analytics</h1>
                <p class="text-gray-600 mt-1">Analyse détaillée de vos performances</p>
            </div>
            <div class="mt-4 lg:mt-0 flex space-x-3">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" id="periodFilter">
                    <option value="7">7 derniers jours</option>
                    <option value="30" selected>30 derniers jours</option>
                    <option value="90">3 derniers mois</option>
                    <option value="365">Année complète</option>
                </select>
                <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                    Actualiser
                </button>
            </div>
        </div>
    </div>

    <!-- Métriques principales (contraintes) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 max-w-full">
        <!-- Visiteurs Uniques -->
        <div class="stat-card cursor-pointer" onclick="showDetailModal('visitors')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="flex items-start justify-between">
                <div class="metric-icon bg-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="stat-number">{{ number_format($stats['visitors'] ?? 1247) }}</div>
                <div class="text-white/90 font-medium text-xs">Visiteurs Uniques</div>
                <div class="text-white/70 text-xs mt-1">+12.5% vs période précédente</div>
            </div>
        </div>

        <!-- Produits Consultés -->
        <div class="stat-card cursor-pointer" onclick="showDetailModal('products')" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="flex items-start justify-between">
                <div class="metric-icon bg-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="stat-number">{{ number_format($stats['product_views'] ?? 3842) }}</div>
                <div class="text-white/90 font-medium text-xs">Vues Produits</div>
                <div class="text-white/70 text-xs mt-1">+8.3% vs période précédente</div>
            </div>
        </div>

        <!-- Articles Blog Consultés -->
        <div class="stat-card cursor-pointer" onclick="showDetailModal('blog')" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="flex items-start justify-between">
                <div class="metric-icon bg-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="stat-number">{{ number_format($stats['blog_views'] ?? 892) }}</div>
                <div class="text-white/90 font-medium text-xs">Vues Blog</div>
                <div class="text-white/70 text-xs mt-1">+15.7% vs période précédente</div>
            </div>
        </div>

        <!-- Interactions (Likes Produits + Commentaires) -->
        <div class="stat-card cursor-pointer" onclick="showDetailModal('interactions')" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="flex items-start justify-between">
                <div class="metric-icon bg-white/20">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="stat-number">{{ number_format($stats['interactions'] ?? 156) }}</div>
                <div class="text-white/90 font-medium text-xs">Interactions</div>
                <div class="text-white/70 text-xs mt-1">Likes produits + commentaires</div>
            </div>
        </div>
    </div>

    <!-- Graphiques principaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-full">
        <!-- Graphique des Visiteurs -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Évolution des Visiteurs</h3>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="visitorsChart" style="max-width: 100%; max-height: 250px;"></canvas>
            </div>
        </div>

        <!-- Graphique des Ventes -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Revenus & Commandes</h3>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="salesChart" style="max-width: 100%; max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Produits et Top Articles -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-full">
        <!-- Produits les plus consultés -->
        <div class="section-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Produits les Plus Consultés</h3>
            <div class="space-y-3">
                @foreach($topProducts ?? [] as $index => $product)
                <div class="flex items-center space-x-4 p-3 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center font-semibold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                        <p class="text-sm text-gray-500">{{ $product['views'] }} vues</p>
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $product['conversion'] }}%
                    </div>
                </div>
                @endforeach
                
                <!-- Données factices si pas de données -->
                @if(empty($topProducts))
                @foreach([
                    ['name' => 'Tomates Bio Premium', 'views' => 342, 'conversion' => 12.5],
                    ['name' => 'Courgettes Fraîches', 'views' => 298, 'conversion' => 18.2],
                    ['name' => 'Salade Verte Bio', 'views' => 267, 'conversion' => 15.8],
                    ['name' => 'Carottes du Terroir', 'views' => 234, 'conversion' => 22.1],
                    ['name' => 'Pommes de Terre Nouvelles', 'views' => 187, 'conversion' => 8.9]
                ] as $index => $product)
                <div class="flex items-center space-x-4 p-3 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center font-semibold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                        <p class="text-sm text-gray-500">{{ $product['views'] }} vues</p>
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $product['conversion'] }}%
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <!-- Articles de blog les plus lus -->
        <div class="section-card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles les Plus Lus</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($topArticles ?? [] as $index => $article)
                <div class="flex items-center space-x-4 p-3 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center font-semibold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $article['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $article['views'] }} lectures</p>
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $article['comments'] }} 💬
                    </div>
                </div>
                @endforeach
                
                <!-- Données factices si pas de données -->
                @if(empty($topArticles))
                @foreach([
                    ['title' => 'Guide complet du potager bio', 'views' => 456, 'comments' => 8],
                    ['title' => 'Les légumes de saison en automne', 'views' => 387, 'comments' => 5],
                    ['title' => 'Comment conserver vos légumes plus longtemps', 'views' => 298, 'comments' => 12],
                    ['title' => 'Recettes avec des légumes oubliés', 'views' => 234, 'comments' => 3],
                    ['title' => 'Agriculture durable et environnement', 'views' => 187, 'comments' => 7]
                ] as $index => $article)
                <div class="flex items-center space-x-4 p-3 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center font-semibold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $article['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $article['views'] }} lectures</p>
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $article['comments'] }} 💬
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Graphique des interactions -->
    <div class="chart-container max-w-full">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Répartition des Interactions</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-full">
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="interactionsChart" style="max-width: 100%; max-height: 250px;"></canvas>
            </div>
            <div class="flex flex-col justify-center space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="font-medium text-sm">Likes sur produits</span>
                    </div>
                    <span class="font-bold text-sm">{{ $stats['product_likes'] ?? 156 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="font-medium text-sm">Commentaires blog</span>
                    </div>
                    <span class="font-bold text-sm">{{ $stats['comments'] ?? 23 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="font-medium text-sm">Partages</span>
                    </div>
                    <span class="font-bold text-sm">{{ $stats['shares'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeDetailModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900"></h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="modalContent" class="p-6">
                <!-- Le contenu sera injecté ici via JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des graphiques
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Graphique des visiteurs
    const visitorsCtx = document.getElementById('visitorsChart').getContext('2d');
    new Chart(visitorsCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Visiteurs uniques',
                data: [65, 89, 123, 156, 189, 267, 234],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: chartOptions
    });

    // Graphique des ventes
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Revenus (€)',
                data: [320, 450, 678, 567, 789, 923, 876],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
            }, {
                label: 'Commandes',
                data: [12, 18, 23, 19, 26, 31, 28],
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                yAxisID: 'y1'
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Graphique des interactions (doughnut)
    const interactionsCtx = document.getElementById('interactionsChart').getContext('2d');
    new Chart(interactionsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Likes produits', 'Commentaires blog', 'Partages'],
            datasets: [{
                data: [156, 23, 0],
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

// Fonctions pour les modales de détail
function showDetailModal(type) {
    const modal = document.getElementById('detailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    let title, content;
    
    switch(type) {
        case 'visitors':
            title = 'Détails des Visiteurs';
            content = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">1,247</div>
                            <div class="text-sm text-blue-800">Visiteurs uniques</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">2,834</div>
                            <div class="text-sm text-green-800">Pages vues</div>
                        </div>
                    </div>
                    <canvas id="detailVisitorsChart" height="200"></canvas>
                </div>
            `;
            break;
        case 'products':
            title = 'Performances des Produits';
            content = `
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">3,842</div>
                            <div class="text-sm text-purple-800">Vues totales</div>
                        </div>
                        <div class="bg-pink-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-pink-600">156</div>
                            <div class="text-sm text-pink-800">Likes</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-indigo-600">12.5%</div>
                            <div class="text-sm text-indigo-800">Taux conversion</div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'blog':
            title = 'Performances du Blog';
            content = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-cyan-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-cyan-600">892</div>
                            <div class="text-sm text-cyan-800">Lectures totales</div>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-emerald-600">23</div>
                            <div class="text-sm text-emerald-800">Commentaires</div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'interactions':
            title = 'Analyse des Interactions';
            content = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">156</div>
                            <div class="text-sm text-red-800">Likes sur produits</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">23</div>
                            <div class="text-sm text-blue-800">Commentaires blog</div>
                        </div>
                    </div>
                </div>
            `;
            break;
    }
    
    modalTitle.textContent = title;
    modalContent.innerHTML = content;
    modal.classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>
@endpush
