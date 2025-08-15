@extends('layouts.app')

@section('title', 'Mes Commandes - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes Commandes</h1>
            <p class="text-gray-600">Suivez l'état de vos commandes et consultez votre historique</p>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center space-x-2">
                    <label for="status" class="text-sm font-medium text-gray-700">Statut:</label>
                    <select id="status" name="status" 
                            onchange="window.location.href = updateQueryParam('status', this.value)"
                            class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">{{ __("app.common.all") }}</option>
                        <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>{{ __("app.status.pending") }}<//option>
                        <option value="confirmed" {{ $currentStatus == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                        <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ $currentStatus == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label for="sort" class="text-sm font-medium text-gray-700">Trier par:</label>
                    <select id="sort" name="sort" 
                            onchange="window.location.href = updateQueryParam('sort_by', this.value)"
                            class="px-3 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="recent" {{ $currentSort == 'recent' ? 'selected' : '' }}>Plus récentes</option>
                        <option value="oldest" {{ $currentSort == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                        <option value="total_desc" {{ $currentSort == 'total_desc' ? 'selected' : '' }}>Montant décroissant</option>
                        <option value="total_asc" {{ $currentSort == 'total_asc' ? 'selected' : '' }}>Montant croissant</option>
                    </select>
                </div>
            </div>
        </div>

        @if($orders->count() > 0)
            <!-- Liste des commandes -->
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <!-- Informations commande -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Commande #{{ $order->order_number }}
                                    </h3>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        @switch($order->status)
                                            @case('pending') En attente @break
                                            @case('confirmed') Confirmée @break
                                            @case('shipped') Expédiée @break
                                            @case('delivered') Livrée @break
                                            @case('cancelled') Annulée @break
                                            @default {{ ucfirst($order->status) }}
                                        @endswitch
                                    </span>
                                </div>
                                
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p>Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                    <p>{{ $order->items->count() }} article{{ $order->items->count() > 1 ? 's' : '' }}</p>
                                    @if($order->tracking_number)
                                        <p>Suivi: <span class="font-medium">{{ $order->tracking_number }}</span></p>
                                    @endif
                                </div>
                                
                                <!-- Aperçu des produits -->
                                <div class="mt-3 flex items-center space-x-2">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <span class="text-sm">📦</span>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-xs text-gray-600">+{{ $order->items->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Prix et actions -->
                            <div class="mt-4 lg:mt-0 lg:ml-6 text-right">
                                <div class="text-2xl font-bold text-gray-900 mb-2">
                                    {{ number_format($order->total_amount, 2) }} €
                                </div>
                                
                                <div class="space-y-2">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        Voir les détails
                                    </a>
                                    
                                    @if($order->can_be_cancelled_now)
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')"
                                              class="inline-block ml-2">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
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
                        Vous n'avez aucune commande avec le statut "{{ $currentStatus }}".
                    @else
                        Vous n'avez pas encore passé de commande.
                    @endif
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Découvrir nos produits
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function updateQueryParam(param, value) {
    const url = new URL(window.location);
    if (value) {
        url.searchParams.set(param, value);
    } else {
        url.searchParams.delete(param);
    }
    return url.toString();
}
</script>
@endsection
