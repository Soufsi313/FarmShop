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
                        <option value="return_requested" {{ $currentStatus == 'return_requested' ? 'selected' : '' }}>Retour demandé</option>
                        <option value="returned" {{ $currentStatus == 'returned' ? 'selected' : '' }}>Retournées</option>
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
                <div class="bg-white rounded-lg shadow p-6" 
                     x-data="{
                         order: {
                             id: {{ $order->id }},
                             status: '{{ $order->status }}',
                             payment_status: '{{ $order->payment_status }}',
                             delivered_at: {{ $order->delivered_at ? "'" . $order->delivered_at->toISOString() . "'" : 'null' }},
                             has_returnable_items: {{ $order->has_returnable_items ? 'true' : 'false' }},
                             has_non_returnable_items: {{ $order->has_non_returnable_items ? 'true' : 'false' }},
                             can_be_cancelled: {{ $order->can_be_cancelled ? 'true' : 'false' }},
                             invoice_number: '{{ $order->invoice_number ?? '' }}',
                             return_requested_at: {{ $order->return_requested_at ? "'" . $order->return_requested_at->toISOString() . "'" : 'null' }}
                         },
                         get canShowReturnButton() {
                             // Afficher le bouton pour toutes les commandes livrées non encore retournées
                             return this.order.status === 'delivered' && !this.order.return_requested_at;
                         },
                         get isWithinReturnPeriod() {
                             if (!this.order.delivered_at) return false;
                             const deliveredDate = new Date(this.order.delivered_at);
                             const now = new Date();
                             const daysDiff = Math.floor((now - deliveredDate) / (1000 * 60 * 60 * 24));
                             return daysDiff <= 14;
                         },
                         get returnButtonAction() {
                             if (!this.isWithinReturnPeriod) return 'expired';
                             if (this.order.has_returnable_items && !this.order.has_non_returnable_items) return 'direct';
                             if (this.order.has_returnable_items && this.order.has_non_returnable_items) return 'mixed';
                             return 'non_returnable';
                         }
                     }"
                >
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                          'bg-blue-100 text-blue-800': order.status === 'confirmed',
                                          'bg-purple-100 text-purple-800': order.status === 'preparing',
                                          'bg-purple-100 text-purple-800': order.status === 'shipped',
                                          'bg-green-100 text-green-800': order.status === 'delivered',
                                          'bg-red-100 text-red-800': order.status === 'cancelled',
                                          'bg-orange-100 text-orange-800': order.status === 'return_requested',
                                          'bg-gray-100 text-gray-800': order.status === 'returned'
                                      }">
                                    <span x-show="order.status === 'pending'">🟡 En attente</span>
                                    <span x-show="order.status === 'confirmed'">🔵 Confirmée</span>
                                    <span x-show="order.status === 'preparing'">🟠 En préparation</span>
                                    <span x-show="order.status === 'shipped'">🟣 Expédiée</span>
                                    <span x-show="order.status === 'delivered'">🟢 Livrée</span>
                                    <span x-show="order.status === 'cancelled'">🔴 Annulée</span>
                                    <span x-show="order.status === 'return_requested'">🔶 Retour demandé</span>
                                    <span x-show="order.status === 'returned'">🟢 Retournée</span>
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
                        <div class="flex flex-wrap gap-2">
                            <!-- Voir les détails -->
                            <a href="{{ route('orders.show', $order) }}" 
                               class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                👁️ Voir les détails
                            </a>
                            
                            <!-- Télécharger la facture (si payée) -->
                            <a x-show="order.payment_status === 'paid' && order.invoice_number" 
                               href="{{ route('orders.invoice', $order) }}" 
                               class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                📄 Télécharger la facture
                            </a>

                            <!-- Annuler la commande (si pas encore expédiée) -->
                            <form x-show="order.can_be_cancelled && ['pending', 'confirmed', 'preparing'].includes(order.status)" 
                                  method="POST" action="{{ route('orders.cancel', $order) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')"
                                  class="inline">
                                @csrf
                                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    ❌ Annuler
                                </button>
                            </form>

                            <!-- Retourner la commande (toujours visible si livrée) -->
                            <div x-data="{ 
                                showNonReturnableModal: false, 
                                showExpiredModal: false,
                                get canShowReturnButton() {
                                    return order.status === 'delivered' && !order.return_requested_at;
                                },
                                get isWithinReturnPeriod() {
                                    if (!order.delivered_at) return false;
                                    const deliveredDate = new Date(order.delivered_at);
                                    const now = new Date();
                                    const daysDiff = Math.floor((now - deliveredDate) / (1000 * 60 * 60 * 24));
                                    return daysDiff <= 14;
                                },
                                get returnButtonAction() {
                                    if (!this.isWithinReturnPeriod) return 'expired';
                                    if (order.has_returnable_items && !order.has_non_returnable_items) return 'direct';
                                    if (order.has_returnable_items && order.has_non_returnable_items) return 'mixed';
                                    return 'non_returnable';
                                }
                            }">
                                <template x-if="canShowReturnButton">
                                    <div>
                                        <!-- Retour direct (produits entièrement retournables) -->
                                        <a x-show="returnButtonAction === 'direct'" 
                                           href="{{ route('orders.return.confirm', $order) }}" 
                                           class="bg-orange-100 hover:bg-orange-200 text-orange-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                            🔄 Retourner
                                        </a>
                                        
                                        <!-- Retour mixte (produits partiellement retournables) -->
                                        <a x-show="returnButtonAction === 'mixed'" 
                                           href="{{ route('orders.return.confirm', $order) }}" 
                                           class="bg-orange-100 hover:bg-orange-200 text-orange-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                            🔄 Retourner
                                        </a>
                                        
                                        <!-- Produits non retournables -->
                                        <button x-show="returnButtonAction === 'non_returnable'" 
                                                @click="showNonReturnableModal = true"
                                                class="bg-orange-100 hover:bg-orange-200 text-orange-800 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                            🔄 Retourner
                                        </button>
                                        
                                        <!-- Délai expiré -->
                                        <button x-show="returnButtonAction === 'expired'" 
                                                @click="showExpiredModal = true"
                                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-md text-sm font-medium transition-colors cursor-not-allowed opacity-60">
                                            🔄 Retourner (expiré)
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Modal pour produits non retournables (locale) -->
                                <div x-show="showNonReturnableModal" 
                                     x-cloak
                                     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                                     @click="showNonReturnableModal = false">
                                    <div @click.stop class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3 text-center">
                                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Produits non retournables</h3>
                                            <div class="mt-2 px-7 py-3">
                                                <p class="text-sm text-gray-500">
                                                    Cette commande contient uniquement des produits alimentaires qui ne peuvent pas être retournés pour des raisons d'hygiène et de sécurité alimentaire.
                                                </p>
                                            </div>
                                            <div class="items-center px-4 py-3">
                                                <button @click="showNonReturnableModal = false" 
                                                        class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700">
                                                    J'ai compris
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal pour délai expiré (locale) -->
                                <div x-show="showExpiredModal" 
                                     x-cloak
                                     class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                                     @click="showExpiredModal = false">
                                    <div @click.stop class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3 text-center">
                                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Délai de retour expiré</h3>
                                            <div class="mt-2 px-7 py-3">
                                                <p class="text-sm text-gray-500">
                                                    Le délai de 14 jours pour retourner cette commande est malheureusement dépassé.
                                                </p>
                                            </div>
                                            <div class="items-center px-4 py-3">
                                                <button @click="showExpiredModal = false" 
                                                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700">
                                                    Fermer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

<!-- Modal de retour -->
<div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center mb-4">
                Demander un retour
            </h3>
            <form id="returnForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison du retour *
                    </label>
                    <textarea name="reason" id="return_reason" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Expliquez pourquoi vous souhaitez retourner cette commande..."
                              required></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeReturnModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        Demander le retour
                    </button>
                </div>
            </form>
        </div>
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

// Fonctions pour la modal de retour
function openReturnModal(orderId) {
    const modal = document.getElementById('returnModal');
    const form = document.getElementById('returnForm');
    
    // Mettre à jour l'action du formulaire
    form.action = `/orders/${orderId}/return`;
    
    // Afficher la modal
    modal.classList.remove('hidden');
}

function closeReturnModal() {
    const modal = document.getElementById('returnModal');
    modal.classList.add('hidden');
    
    // Réinitialiser le formulaire
    document.getElementById('return_reason').value = '';
}

// Fermer la modal en cliquant à l'extérieur
document.getElementById('returnModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReturnModal();
    }
});

// Polling pour mettre à jour les statuts des commandes en temps réel
function pollOrderUpdates() {
    const orderCards = document.querySelectorAll('[x-data]');
    
    orderCards.forEach(card => {
        const orderData = Alpine.$data(card);
        if (orderData && orderData.order) {
            // Vérifier le statut de la commande via AJAX
            fetch(`/api/orders/${orderData.order.id}/status`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Mettre à jour les données Alpine.js
                        orderData.order.status = data.order.status;
                        orderData.order.payment_status = data.order.payment_status;
                        orderData.order.delivered_at = data.order.delivered_at;
                        orderData.order.has_returnable_items = data.order.has_returnable_items;
                        orderData.order.can_be_cancelled = data.order.can_be_cancelled;
                        orderData.order.invoice_number = data.order.invoice_number || '';
                    }
                })
                .catch(error => {
                    console.log('Erreur lors de la mise à jour du statut:', error);
                });
        }
    });
}

// Démarrer le polling toutes les 10 secondes
setInterval(pollOrderUpdates, 10000);

// Premier appel après 5 secondes
setTimeout(pollOrderUpdates, 5000);
</script>

@endsection
