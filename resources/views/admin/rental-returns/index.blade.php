@extends('layouts.admin')

@section('title', __('rental_returns.management_title'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('rental_returns.management_title') }}
                    </h1>
                    <p class="mt-2 text-purple-100">
                        {{ __('rental_returns.management_subtitle') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $orderLocations->total() }}</div>
                    <div class="text-purple-100">{{ __('rental_returns.total_returns') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_inspection'] ?? 0 }}</div>
                    <div class="text-sm text-yellow-700">{{ __('rental_returns.pending_inspection') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['in_inspection'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700">{{ __('rental_returns.in_inspection') }}</div>
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
                    <div class="text-2xl font-bold text-green-600">{{ $stats['completed_inspection'] ?? 0 }}</div>
                    <div class="text-sm text-green-700">{{ __('rental_returns.completed_inspection') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['overdue_returns'] ?? 0 }}</div>
                    <div class="text-sm text-red-700">{{ __('rental_returns.overdue_returns') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('admin.rental-returns.index') }}" class="space-y-4">
            <!-- Première ligne : Recherche -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="{{ __('rental_returns.search_placeholder') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('rental_returns.filter') }}
                    </button>
                    <a href="{{ route('admin.rental-returns.index') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-200">
                        {{ __('rental_returns.reset') }}
                    </a>
                </div>
            </div>

            <!-- Seconde ligne : Filtres -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Tous les statuts</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé (Attente retour)</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Retourné (Attente inspection)</option>
                        <option value="inspecting" {{ request('status') == 'inspecting' ? 'selected' : '' }}>En cours d'inspection</option>
                        <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Inspection terminée</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('rental_returns.inspection_status_label') }}</label>
                    <select name="inspection_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">{{ __('rental_returns.all') }}</option>
                        <option value="pending" {{ request('inspection_status') == 'pending' ? 'selected' : '' }}>{{ __('rental_returns.pending') }}</option>
                        <option value="in_progress" {{ request('inspection_status') == 'in_progress' ? 'selected' : '' }}>{{ __('rental_returns.in_progress') }}</option>
                        <option value="completed" {{ request('inspection_status') == 'completed' ? 'selected' : '' }}>{{ __('rental_returns.completed') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('rental_returns.return_date_from') }}</label>
                    <input type="date" 
                           name="return_date_from" 
                           value="{{ request('return_date_from') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('rental_returns.return_date_to') }}</label>
                    <input type="date" 
                           name="return_date_to" 
                           value="{{ request('return_date_to') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </form>
    </div>

    <!-- Actions rapides -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-2">
            <button onclick="exportData()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('rental_returns.export_csv') }}
            </button>
        </div>
        
        <div class="text-sm text-gray-600">
            {{ __('rental_returns.showing_results', [
                'first' => $orderLocations->firstItem() ?? 0,
                'last' => $orderLocations->lastItem() ?? 0,
                'total' => $orderLocations->total()
            ]) }}
        </div>
    </div>

    <!-- Tableau des retours -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.order') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.client') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.dates') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.inspection') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.amounts') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('rental_returns.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orderLocations as $orderLocation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $orderLocation->order_number }}</div>
                            <div class="text-sm text-gray-500">{{ __('rental_returns.products_count', ['count' => $orderLocation->orderItemLocations->count()]) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $orderLocation->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $orderLocation->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><strong>{{ __('rental_returns.end_date_label') }}:</strong> {{ $orderLocation->end_date->format('d/m/Y') }}</div>
                            @if($orderLocation->actual_return_date)
                            <div class="text-green-600"><strong>{{ __('rental_returns.return_date_label') }}:</strong> {{ $orderLocation->actual_return_date->format('d/m/Y') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-yellow-100 text-yellow-800',
                                    'closed' => 'bg-blue-100 text-blue-800',
                                    'inspecting' => 'bg-purple-100 text-purple-800',
                                    'finished' => 'bg-green-100 text-green-800'
                                ];
                                $statusLabels = [
                                    'completed' => __('rental_returns.status_completed'),
                                    'closed' => __('rental_returns.status_closed'),
                                    'inspecting' => __('rental_returns.status_inspecting'),
                                    'finished' => __('rental_returns.status_finished')
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$orderLocation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$orderLocation->status] ?? $orderLocation->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($orderLocation->inspection_status)
                                @php
                                    $inspectionColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800'
                                    ];
                                    $inspectionLabels = [
                                        'pending' => __('rental_returns.pending'),
                                        'in_progress' => __('rental_returns.in_progress'),
                                        'completed' => __('rental_returns.completed')
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $inspectionColors[$orderLocation->inspection_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $inspectionLabels[$orderLocation->inspection_status] ?? $orderLocation->inspection_status }}
                                </span>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><strong>{{ __('rental_returns.deposit_label') }}:</strong> {{ number_format($orderLocation->deposit_amount, 2) }}€</div>
                            @if($orderLocation->penalty_amount > 0)
                            <div class="text-red-600"><strong>{{ __('rental_returns.penalties_label') }}:</strong> {{ number_format($orderLocation->penalty_amount, 2) }}€</div>
                            @endif
                            @if($orderLocation->deposit_refund !== null)
                            <div class="text-green-600"><strong>{{ __('rental_returns.refund_label') }}:</strong> {{ number_format($orderLocation->deposit_refund, 2) }}€</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.rental-returns.show', $orderLocation) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors"
                                   title="{{ __('rental_returns.view_details') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                @if($orderLocation->status === 'completed')
                                <form action="{{ route('admin.rental-returns.mark-returned', $orderLocation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-800 transition-colors"
                                            title="{{ __('rental_returns.confirm_return') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                
                                @if($orderLocation->status === 'closed')
                                <form action="{{ route('admin.rental-returns.start-inspection', $orderLocation) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-purple-600 hover:text-purple-800 transition-colors"
                                            title="{{ __('rental_returns.start_inspection') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-lg font-medium">{{ __('rental_returns.no_returns_found') }}</p>
                            <p class="mt-2">{{ __('rental_returns.no_returns_match_criteria') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($orderLocations->hasPages())
    <div class="mt-8">
        {{ $orderLocations->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<script>
function exportData() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = "{{ route('admin.rental-returns.export') }}?" + params.toString();
}
</script>
@endsection
