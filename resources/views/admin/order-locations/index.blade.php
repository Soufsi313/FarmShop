@extends('layouts.admin')

@section('title', 'Gestion des Commandes de Location')

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
                        Gestion des Commandes de Location
                    </h1>
                    <p class="mt-2 text-orange-100">
                        Interface complète de gestion des locations avec suivi et inspection
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $orders->total() }}</div>
                    <div class="text-orange-100">Locations totales</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_rentals'] ?? 0 }}</div>
                    <div class="text-sm text-green-700">Locations actives</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['pending_returns'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700">Retours attendus</div>
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
                    <div class="text-sm text-purple-700">Total locations</div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['revenue_month'] ?? 0, 0) }}€</div>
                    <div class="text-sm text-yellow-700">Revenus ce mois</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des commandes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commande</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500">{{ $order->orderItemLocations->count() }} produit(s)</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><strong>Début:</strong> {{ $order->start_date->format('d/m/Y') }}</div>
                            <div><strong>Fin:</strong> {{ $order->end_date->format('d/m/Y') }}</div>
                            <div class="text-gray-500">{{ $order->rental_days }} jour(s)</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><strong>Total:</strong> {{ number_format($order->total_amount, 2) }}€</div>
                            <div class="text-gray-500">Dépôt: {{ number_format($order->deposit_amount, 2) }}€</div>
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
                                    'pending' => 'En attente',
                                    'confirmed' => 'Confirmée',
                                    'active' => 'Active',
                                    'completed' => 'Terminée',
                                    'closed' => 'Fermée',
                                    'inspecting' => 'Inspection',
                                    'finished' => 'Finalisée',
                                    'cancelled' => 'Annulée'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.order-locations.show', $order) }}" 
                               class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition duration-200 mr-2">
                                Voir
                            </a>
                            
                            @if($order->status === 'pending')
                            <button onclick="updateStatus({{ $order->id }}, 'confirmed')" 
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition duration-200 mr-2">
                                Confirmer
                            </button>
                            @endif
                            
                            @if(in_array($order->status, ['completed', 'closed', 'inspecting']))
                            <a href="{{ route('admin.rental-returns.show', $order) }}" 
                               class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 transition duration-200 mr-2">
                                Retour
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-lg font-medium">Aucune commande trouvée</p>
                            <p class="mt-2">Aucune commande ne correspond aux critères de recherche.</p>
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour du statut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour du statut');
    });
}
</script>
@endsection
