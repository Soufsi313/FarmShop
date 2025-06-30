@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes commandes</h1>
            <p class="text-gray-600">Consultez l'historique de vos commandes et suivez leur statut</p>
        </div>

        @if($orders->count() > 0)
            <!-- Liste des commandes -->
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- En-tête de la commande -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="mb-4 sm:mb-0">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    Commande #{{ $order->order_number }}
                                </h3>
                                <div class="flex flex-wrap items-center text-sm text-gray-600 gap-4">
                                    <span>
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-1 12a2 2 0 002 2h6a2 2 0 002-2L15 7m-6 0h6"></path>
                                        </svg>
                                        {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </span>
                                    <span>
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        {{ $order->orderItems->count() }} article(s)
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <!-- Statut -->
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                                    @switch($order->status)
                                        @case('pending')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @case('confirmed')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('shipped')
                                            bg-purple-100 text-purple-800
                                            @break
                                        @case('delivered')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('cancelled')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($order->status)
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('confirmed')
                                            Confirmée
                                            @break
                                        @case('shipped')
                                            Expédiée
                                            @break
                                        @case('delivered')
                                            Livrée
                                            @break
                                        @case('cancelled')
                                            Annulée
                                            @break
                                        @default
                                            {{ ucfirst($order->status) }}
                                    @endswitch
                                </span>
                                
                                <!-- Total -->
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">
                                        {{ number_format($order->total_amount, 2) }} €
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu des articles -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">
                            @foreach($order->orderItems->take(3) as $item)
                            <div class="flex items-center space-x-3">
                                <!-- Image du produit -->
                                <div class="flex-shrink-0">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}" 
                                             alt="{{ $item->product_name }}"
                                             class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Informations de l'article -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item->product_name }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Qté: {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} €
                                    </p>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($order->orderItems->count() > 3)
                            <div class="flex items-center justify-center text-sm text-gray-500 bg-gray-50 rounded-lg p-3">
                                +{{ $order->orderItems->count() - 3 }} autre(s)
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex space-x-3">
                                <a href="{{ route('orders.user.show', $order) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Voir les détails
                                </a>
                                
                                @if(in_array($order->status, ['pending', 'confirmed']) && $order->created_at->diffInHours(now()) < 24)
                                <button type="button"
                                        onclick="cancelOrder({{ $order->id }})"
                                        class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Annuler
                                </button>
                                @endif
                            </div>
                            
                            <!-- Statut de paiement -->
                            <div class="mt-3 sm:mt-0">
                                <span class="text-xs text-gray-500">
                                    Paiement : 
                                    <span class="font-medium
                                        @switch($order->payment_status)
                                            @case('paid')
                                                text-green-600
                                                @break
                                            @case('pending')
                                                text-yellow-600
                                                @break
                                            @case('failed')
                                                text-red-600
                                                @break
                                            @default
                                                text-gray-600
                                        @endswitch
                                    ">
                                        @switch($order->payment_status)
                                            @case('paid')
                                                Payé
                                                @break
                                            @case('pending')
                                                En attente
                                                @break
                                            @case('failed')
                                                Échec
                                                @break
                                            @default
                                                {{ ucfirst($order->payment_status) }}
                                        @endswitch
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
            @endif

        @else
            <!-- État vide -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande</h3>
                <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande.</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Commencer mes achats
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal d'annulation -->
<div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Annuler la commande</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelButton"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Annuler
                </button>
                <button onclick="closeCancelModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Retour
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let orderToCancel = null;

function cancelOrder(orderId) {
    orderToCancel = orderId;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    orderToCancel = null;
    document.getElementById('cancelModal').classList.add('hidden');
}

document.getElementById('cancelButton').addEventListener('click', function() {
    if (!orderToCancel) return;
    
    fetch(`/api/orders/${orderToCancel}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de l\'annulation de la commande');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'annulation de la commande');
    });
});
</script>
@endsection
