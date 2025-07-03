@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes commandes</h1>
            <p class="text-gray-600">Gérez et suivez vos commandes</p>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <form method="GET" action="{{ route('orders.user.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                        <option value="preparation" {{ request('status') == 'preparation' ? 'selected' : '' }}>En préparation</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Du</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Au</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        @if($orders->isEmpty())
        <!-- Aucune commande -->
        <div class="bg-white rounded-lg shadow-sm border p-12 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
            <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande ou aucune commande ne correspond aux filtres sélectionnés.</p>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Découvrir nos produits
            </a>
        </div>
        @else
        <!-- Liste des commandes -->
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition duration-200">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <!-- Informations principales -->
                        <div class="flex-1 mb-4 lg:mb-0">
                            <div class="flex items-center gap-4 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Commande #{{ $order->order_number }}
                                </h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'preparation') bg-purple-100 text-purple-800
                                    @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                    @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @switch($order->status)
                                        @case('pending') En attente @break
                                        @case('confirmed') Confirmée @break
                                        @case('preparation') En préparation @break
                                        @case('shipped') Expédiée @break
                                        @case('delivered') Livrée @break
                                        @case('cancelled') Annulée @break
                                        @default {{ ucfirst($order->status) }}
                                    @endswitch
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $order->created_at->format('d/m/Y à H:i') }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    {{ number_format($order->total_amount, 2) }} €
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    {{ $order->items->count() }} article(s)
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('orders.user.show', $order) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Détails
                            </a>

                            @if($order->isConfirmed() || $order->isProcessing() || $order->isShipped() || $order->isDelivered())
                            <a href="{{ route('orders.user.invoice', $order) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Facture
                            </a>
                            @endif

                            @if($order->canBeCancelled())
                            <button onclick="cancelOrder({{ $order->id }})" 
                                    class="inline-flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Produits de la commande (aperçu) -->
                    @if($order->items->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-4 overflow-x-auto">
                            @foreach($order->items->take(3) as $item)
                            <div class="flex-shrink-0 flex items-center space-x-2">
                                @if($item->product && $item->product->main_image)
                                <img src="{{ $item->product->main_image_url }}" 
                                     alt="{{ $item->product_name }}"
                                     class="w-10 h-10 object-cover rounded-md">
                                @else
                                <div class="w-10 h-10 bg-gray-200 rounded-md flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                                    <p class="text-gray-500">Qté: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            @endforeach
                            @if($order->items->count() > 3)
                            <div class="flex-shrink-0 text-sm text-gray-500">
                                +{{ $order->items->count() - 3 }} autre(s)
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-8">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<!-- Modal d'annulation -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="cancelForm" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Annuler la commande
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir annuler cette commande ? Cette action ne peut pas être annulée.
                                </p>
                                <div class="mt-4">
                                    <label for="cancelReason" class="block text-sm font-medium text-gray-700">Raison de l'annulation (optionnel)</label>
                                    <textarea name="reason" id="cancelReason" rows="3" 
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                              placeholder="Expliquez pourquoi vous souhaitez annuler cette commande..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler la commande
                    </button>
                    <button type="button" onclick="closeCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function cancelOrder(orderId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    form.action = `/mes-commandes/${orderId}/annuler`;
    modal.classList.remove('hidden');
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
    document.getElementById('cancelReason').value = '';
}

// Fermer le modal en cliquant en dehors
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCancelModal();
    }
});

// Système d'automatisation des statuts
function runAutomation() {
    fetch('/admin/orders/automation/run', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Automatisation exécutée:', data.message);
            // Recharger la page pour voir les changements seulement si il y a eu des mises à jour
            if (data.details && data.details.includes('✅')) {
                console.log('🔄 Rechargement de la page pour afficher les mises à jour...');
                location.reload();
            }
        } else {
            console.error('❌ Erreur automatisation:', data.message);
        }
    })
    .catch(error => {
        console.error('❌ Erreur automatisation:', error);
    });
}

// Afficher le statut d'automatisation
function showAutomationStatus() {
    const statusDiv = document.createElement('div');
    statusDiv.id = 'automation-status';
    statusDiv.className = 'fixed bottom-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    statusDiv.innerHTML = '🤖 Automatisation: Active (45s)';
    document.body.appendChild(statusDiv);
    
    // Compteur visuel
    let countdown = 45;
    const updateCountdown = () => {
        countdown--;
        if (countdown <= 0) {
            countdown = 45;
            statusDiv.innerHTML = '🤖 Automatisation: En cours...';
            setTimeout(() => {
                statusDiv.innerHTML = '🤖 Automatisation: Active (45s)';
            }, 2000);
        } else {
            statusDiv.innerHTML = `🤖 Automatisation: Active (${countdown}s)`;
        }
    };
    
    setInterval(updateCountdown, 1000);
}

// Démarrer l'automatisation toutes les 45 secondes
console.log('🚀 Démarrage de l\'automatisation des statuts (intervalle: 45 secondes)');
setInterval(runAutomation, 45000);

// Exécuter une première fois au chargement de la page
setTimeout(runAutomation, 5000); // Après 5 secondes pour laisser la page se charger

// Afficher le statut d'automatisation
showAutomationStatus();
</script>
@endpush
@endsection
