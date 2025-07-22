@extends('layouts.app')

@section('title', 'Mes Commandes - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes Commandes</h1>
            <p class="text-gray-600">Suivez l'état de vos commandes et gérez vos achats</p>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par statut</label>
                    <select id="status-filter" onchange="filterByStatus(this.value)" 
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ $currentStatus == 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                        <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }}>Expédiées</option>
                        <option value="delivered" {{ $currentStatus == 'delivered' ? 'selected' : '' }}>Livrées</option>
                        <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Annulées</option>
                    </select>
                </div>
                
                <div>
                    <label for="sort-filter" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                    <select id="sort-filter" onchange="sortBy(this.value)"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="recent" {{ $currentSort == 'recent' ? 'selected' : '' }}>Plus récentes</option>
                        <option value="oldest" {{ $currentSort == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                        <option value="total_desc" {{ $currentSort == 'total_desc' ? 'selected' : '' }}>Montant décroissant</option>
                        <option value="total_asc" {{ $currentSort == 'total_asc' ? 'selected' : '' }}>Montant croissant</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Liste des commandes -->
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Commande #{{ $order->order_number }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    @switch($order->status)
                                        @case('pending')
                                            🟡 En attente
                                            @break
                                        @case('confirmed')
                                            🔵 Confirmée
                                            @break
                                        @case('shipped')
                                            🟣 Expédiée
                                            @break
                                        @case('delivered')
                                            🟢 Livrée
                                            @break
                                        @case('cancelled')
                                            🔴 Annulée
                                            @break
                                        @default
                                            ⚪ {{ ucfirst($order->status) }}
                                    @endswitch
                                </span>
                            </div>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ number_format($order->total_amount, 2) }} €
                            </p>
                        </div>
                    </div>

                    <!-- Aperçu des articles -->
                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($order->items->take(3) as $item)
                            <div class="flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                                <span class="text-sm">{{ $item->product_name }}</span>
                                <span class="text-xs text-gray-600">×{{ $item->quantity }}</span>
                            </div>
                            @endforeach
                            
                            @if($order->items->count() > 3)
                            <div class="flex items-center px-3 py-2 text-sm text-gray-600">
                                +{{ $order->items->count() - 3 }} autres articles
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            <a href="{{ route('orders.show', $order) }}" 
                               class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Voir les détails
                            </a>
                            
                            @if($order->can_be_cancelled && in_array($order->status, ['pending', 'confirmed']))
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')"
                                  class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Annuler
                                </button>
                            </form>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            @if($order->payment_status == 'paid')
                                ✅ Payée
                            @elseif($order->payment_status == 'pending')
                                ⏳ Paiement en attente
                            @else
                                ❌ {{ ucfirst($order->payment_status) }}
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Aucune commande -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                <p class="text-gray-600 mb-6">
                    @if($currentStatus)
                        Aucune commande avec le statut "{{ $currentStatus }}" trouvée.
                    @else
                        Vous n'avez pas encore passé de commande.
                    @endif
                </p>
                <div class="space-x-4">
                    @if($currentStatus)
                    <a href="{{ route('orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Voir toutes les commandes
                    </a>
                    @endif
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        Découvrir nos produits
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function filterByStatus(status) {
    const url = new URL(window.location);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location = url.toString();
}

function sortBy(sort) {
    const url = new URL(window.location);
    url.searchParams.set('sort_by', sort);
    window.location = url.toString();
}
</script>

@endsection
