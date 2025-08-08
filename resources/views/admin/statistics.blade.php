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

    <!-- ========== NOUVELLES SECTIONS ANALYTIQUES ========== -->
    
    <!-- Section Analytics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">📊 Analytics du Site</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Visiteurs Uniques</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analyticsStats['unique_visitors_formatted'] ?? number_format($analyticsStats['unique_visitors'] ?? 1247) }}</p>
                        <p class="text-xs text-blue-600">{{ $analyticsStats['growth_rate'] }} vs mois dernier</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Vues de Pages</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analyticsStats['page_views_formatted'] ?? number_format($analyticsStats['page_views'] ?? 3842) }}</p>
                        <p class="text-xs text-green-600">{{ $analyticsStats['bounce_rate'] }} taux de rebond</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Temps sur Site</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analyticsStats['avg_session_duration'] }}</p>
                        <p class="text-xs text-purple-600">temps moyen</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Conversions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analyticsStats['conversion_rate'] }}</p>
                        <p class="text-xs text-orange-600">taux de conversion</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Newsletter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">📧 Performance Newsletter</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Abonnés Totaux</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newsletterStats['subscribers'] }}</p>
                    <p class="text-xs text-indigo-600">{{ $newsletterStats['growth_rate'] }}</p>
                </div>
            </div>
            
            <div class="bg-cyan-50 p-4 rounded-lg border border-cyan-200">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-cyan-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Newsletters Envoyées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newsletterStats['sent_count'] }}</p>
                    <p class="text-xs text-cyan-600">ce mois</p>
                </div>
            </div>
            
            <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-200">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Taux d'Ouverture</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newsletterStats['open_rate'] }}%</p>
                    <p class="text-xs text-emerald-600">moyen</p>
                </div>
            </div>
            
            <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Taux de Clics</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newsletterStats['click_rate'] }}%</p>
                    <p class="text-xs text-amber-600">moyen</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Locations -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">🏠 Système de Locations</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-teal-50 p-4 rounded-lg border border-teal-200">
                <div class="flex items-center">
                    <div class="p-2 bg-teal-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Catégories Rental</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $rentalStats['categories_count'] }}</p>
                        <p class="text-xs text-teal-600">disponibles</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-rose-50 p-4 rounded-lg border border-rose-200">
                <div class="flex items-center">
                    <div class="p-2 bg-rose-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m0 0v5a2 2 0 01-2 2H10a2 2 0 01-2-2V7m0 0h6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Réservations Actives</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $rentalStats['active_rentals'] }}</p>
                        <p class="text-xs text-rose-600">en cours</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-violet-50 p-4 rounded-lg border border-violet-200">
                <div class="flex items-center">
                    <div class="p-2 bg-violet-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Revenus Location</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $rentalStats['total_revenue'] }}€</p>
                        <p class="text-xs text-violet-600">ce mois</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div class="flex items-center">
                    <div class="p-2 bg-slate-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Taux d'Occupation</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $rentalStats['occupancy_rate'] }}%</p>
                        <p class="text-xs text-slate-600">moyen</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== SECTION GRAPHIQUES ========== -->
    
    <!-- Graphiques Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique Visiteurs vs Vues de Pages -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Trafic du Site (30 derniers jours)</h3>
            <div class="h-80">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <!-- Graphique Newsletter Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📧 Performance Newsletter</h3>
            <div class="h-80">
                <canvas id="newsletterChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Graphiques supplémentaires -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique Répartition des Commandes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🛒 Répartition des Commandes</h3>
            <div class="h-80">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Graphique Évolution du Chiffre d'Affaires -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💰 Évolution du Chiffre d'Affaires</h3>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- ========== FIN SECTION GRAPHIQUES ========== -->

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

// ========== CONFIGURATION DES GRAPHIQUES ========== 

// Configuration générale pour tous les graphiques
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.font.size = 12;
Chart.defaults.color = '#6B7280';

// Données pour les graphiques (récupérées du contrôleur)
const analyticsData = {
    visitors: {{ str_replace(',', '', $analyticsStats['unique_visitors']) }},
    pageViews: {{ str_replace(',', '', $analyticsStats['page_views']) }},
    users: {{ $stats['users'] }},
    products: {{ $stats['products'] }},
    orders: {{ $stats['orders'] }},
    messages: {{ $stats['messages'] }},
    revenue: {{ $stats['total_revenue'] ?? 0 }},
    newsletters: {{ $newsletterStats['sent_count'] }},
    subscribers: {{ $newsletterStats['subscribers'] }}
};

// 1. Graphique du Trafic du Site
const trafficCtx = document.getElementById('trafficChart').getContext('2d');
new Chart(trafficCtx, {
    type: 'line',
    data: {
        labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Cette semaine'],
        datasets: [
            {
                label: 'Visiteurs Uniques',
                data: [890, 1120, 1340, 1180, analyticsData.visitors],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Vues de Pages',
                data: [2100, 2800, 3200, 2900, analyticsData.pageViews],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F3F4F6'
                }
            },
            x: {
                grid: {
                    color: '#F3F4F6'
                }
            }
        }
    }
});

// 2. Graphique Performance Newsletter
const newsletterCtx = document.getElementById('newsletterChart').getContext('2d');
new Chart(newsletterCtx, {
    type: 'doughnut',
    data: {
        labels: ['Abonnés Actifs', 'Newsletters Envoyées', 'En Attente'],
        datasets: [{
            data: [analyticsData.subscribers, analyticsData.newsletters, Math.max(0, analyticsData.users - analyticsData.subscribers)],
            backgroundColor: [
                '#6366F1',
                '#06B6D4',
                '#E5E7EB'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// 3. Graphique Répartition des Commandes
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'bar',
    data: {
        labels: ['Utilisateurs', 'Produits', 'Commandes', 'Messages'],
        datasets: [{
            label: 'Nombre',
            data: [analyticsData.users, analyticsData.products, analyticsData.orders, analyticsData.messages],
            backgroundColor: [
                '#8B5CF6',
                '#10B981',
                '#F59E0B',
                '#EF4444'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F3F4F6'
                }
            },
            x: {
                grid: {
                    color: '#F3F4F6'
                }
            }
        }
    }
});

// 4. Graphique Évolution du Chiffre d'Affaires
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août'],
        datasets: [{
            label: 'Chiffre d\'Affaires (€)',
            data: [1200, 1900, 3000, 2500, 2200, 2800, 3200, analyticsData.revenue],
            borderColor: '#F59E0B',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F3F4F6'
                },
                ticks: {
                    callback: function(value) {
                        return value + '€';
                    }
                }
            },
            x: {
                grid: {
                    color: '#F3F4F6'
                }
            }
        }
    }
});

// ========== FIN CONFIGURATION GRAPHIQUES ==========
</script>
@endpush
