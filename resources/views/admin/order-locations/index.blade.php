@extends('layouts.admin')

@section('title', __('order_locations.title'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('order_locations.title') }}
                    </h1>
                    <p class="mt-2 text-orange-100">
                        {{ __('order_locations.subtitle') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $orders->total() }}</div>
                    <div class="text-orange-100">{{ __('order_locations.total_rentals') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['pending_orders'] ?? 0 }}</div>
                    <div class="text-sm text-orange-700">{{ __('order_locations.pending_orders') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_rentals'] ?? 0 }}</div>
                    <div class="text-sm text-green-700">{{ __('order_locations.active_rentals') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['pending_returns'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700">{{ __('order_locations.pending_returns') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="text-sm text-purple-700">{{ __('order_locations.total_orders') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques financières -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['revenue_month'] ?? 0, 0) }}€</div>
                    <div class="text-sm text-yellow-700">{{ __('order_locations.revenue_month') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-emerald-600">{{ number_format($stats['revenue_year'] ?? 0, 0) }}€</div>
                    <div class="text-sm text-emerald-700">{{ __('order_locations.revenue_year') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-amber-600">{{ number_format($stats['deposits_held'] ?? 0, 0) }}€</div>
                    <div class="text-sm text-amber-700">{{ __('order_locations.deposits_held') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recherche avancée -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8" x-data="{ showFilters: false }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                {{ __('order_locations.search_filters') }}
            </h3>
            <button @click="showFilters = !showFilters" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <span x-text="showFilters ? '{{ __('order_locations.hide_filters') }}' : '{{ __('order_locations.show_filters') }}'"></span>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.order-locations.index') }}" class="space-y-4">
            <!-- Recherche rapide -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.quick_search') }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('order_locations.search_placeholder') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.status') }}</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">{{ __('order_locations.all_statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('order_locations.status_pending') }}</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('order_locations.status_confirmed') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('order_locations.status_active') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('order_locations.status_completed') }}</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('order_locations.status_closed') }}</option>
                        <option value="inspecting" {{ request('status') === 'inspecting' ? 'selected' : '' }}>{{ __('order_locations.status_inspecting') }}</option>
                        <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>{{ __('order_locations.status_finished') }}</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('order_locations.status_cancelled') }}</option>
                    </select>
                </div>

                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.sort_by') }}</label>
                    <select id="sort_by" name="sort_by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="recent" {{ request('sort_by') === 'recent' ? 'selected' : '' }}>{{ __('order_locations.sort_recent') }}</option>
                        <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>{{ __('order_locations.sort_oldest') }}</option>
                        <option value="start_date" {{ request('sort_by') === 'start_date' ? 'selected' : '' }}>{{ __('order_locations.sort_start_date') }}</option>
                        <option value="end_date" {{ request('sort_by') === 'end_date' ? 'selected' : '' }}>{{ __('order_locations.sort_end_date') }}</option>
                        <option value="total_desc" {{ request('sort_by') === 'total_desc' ? 'selected' : '' }}>{{ __('order_locations.sort_total_desc') }}</option>
                        <option value="total_asc" {{ request('sort_by') === 'total_asc' ? 'selected' : '' }}>{{ __('order_locations.sort_total_asc') }}</option>
                    </select>
                </div>
            </div>

            <!-- Filtres avancés -->
            <div x-show="showFilters" x-transition class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.date_from') }}</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.date_to') }}</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.start_date_from') }}</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('order_locations.start_date_to') }}</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                    🔍 {{ __('order_locations.search_button') }}
                </button>
                <a href="{{ route('admin.order-locations.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    🔄 {{ __('order_locations.reset_filters') }}
                </a>
                <a href="{{ route('admin.order-locations.export', request()->all()) }}" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    📊 Exporter CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des commandes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('order_locations.order_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('order_locations.customer') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('order_locations.rental_period') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('order_locations.amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('order_locations.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('order_locations.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500">{{ $order->orderItemLocations->count() }} {{ __('order_locations.product') }}(s)</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><strong>{{ __('order_locations.from') }}:</strong> {{ $order->start_date->format('d/m/Y') }}</div>
                            <div><strong>{{ __('order_locations.to') }}:</strong> {{ $order->end_date->format('d/m/Y') }}</div>
                            <div class="text-gray-500">{{ $order->rental_days }} {{ __('order_locations.duration') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><strong>Total:</strong> {{ number_format($order->total_amount, 2) }}€</div>
                            <div class="text-gray-500">{{ __('order_locations.deposit') }}: {{ number_format($order->calculated_deposit_amount, 2) }}€</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-purple-100 text-purple-800',
                                    'closed' => 'bg-indigo-100 text-indigo-800',
                                    'inspecting' => 'bg-orange-100 text-orange-800',
                                    'finished' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => __('order_locations.status_pending'),
                                    'confirmed' => __('order_locations.status_confirmed'),
                                    'active' => __('order_locations.status_active'),
                                    'completed' => __('order_locations.status_completed'),
                                    'closed' => __('order_locations.status_closed'),
                                    'inspecting' => __('order_locations.status_inspecting'),
                                    'finished' => __('order_locations.status_finished'),
                                    'cancelled' => __('order_locations.status_cancelled')
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <!-- Bouton Voir avec icône œil -->
                                <a href="{{ route('admin.order-locations.show', $order) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition duration-200"
                                   title="{{ __('order_locations.view_details') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                @if($order->status === 'pending')
                                <!-- Bouton Confirmer avec icône check -->
                                <button onclick="updateStatus({{ $order->id }}, 'confirmed')" 
                                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition duration-200"
                                        title="{{ __('order_locations.confirm_order') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                @endif

                                @if($order->status === 'completed' && $order->inspection_status !== 'completed')
                                <!-- Bouton Clôturer pour les locations terminées (non inspectées) -->
                                <button onclick="updateStatus({{ $order->id }}, 'closed')" 
                                        class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition duration-200"
                                        title="{{ __('order_locations.close_rental') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                                @elseif($order->status === 'completed' && $order->inspection_status === 'completed')
                                <!-- Message pour les locations déjà inspectées -->
                                <span class="inline-flex items-center px-3 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-md"
                                      title="{{ __('order_locations.inspection_completed') }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('order_locations.inspection_completed') }}
                                </span>
                                @elseif($order->status === 'finished')
                                <!-- Message pour les locations complètement terminées -->
                                <span class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-md"
                                      title="{{ __('order_locations.rental_finished') }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('order_locations.rental_finished') }}
                                </span>
                                @endif
                                
                                @if(in_array($order->status, ['completed', 'closed', 'inspecting']))
                                <!-- Bouton Retour avec icône -->
                                <a href="{{ route('admin.rental-returns.show', $order) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition duration-200"
                                   title="{{ __('order_locations.manage_return') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </a>
                                @endif
                                
                                @if(!in_array($order->status, ['finished', 'cancelled']))
                                <!-- Bouton Annuler - Admin peut annuler même les locations actives -->
                                <button onclick="if(confirm('{{ __('order_locations.confirm_cancel') }}')) updateStatus({{ $order->id }}, 'cancelled')" 
                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition duration-200"
                                        title="{{ __('order_locations.cancel_order') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-lg font-medium">{{ __('order_locations.no_orders_found') }}</p>
                            <p class="mt-2">{{ __('order_locations.no_orders_match_criteria') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-8">
        {{ $orders->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<script>
function updateStatus(orderId, status) {
    fetch(`/admin/order-locations/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('{{ __("order_locations.status_update_error") }}: ' + (data.message || '{{ __("order_locations.unknown_error") }}'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("order_locations.status_update_error") }}: ' + error.message);
    });
}
</script>
@endsection
