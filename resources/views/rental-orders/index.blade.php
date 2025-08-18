@extends('layouts.app')

@section('title', 'Mes locations - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes Locations</h1>
            <p class="text-gray-600">Gérez vos locations et suivez leur statut</p>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('rental-orders.index') }}" class="flex flex-wrap gap-4 items-center">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par statut</label>
                    <select name="status" id="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __("app.status.pending") }}<//option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Clôturée</option>
                    </select>
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                           class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                           class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des commandes -->
        @if($orderLocations->count() > 0)
            <div class="space-y-4">
                @foreach($orderLocations as $order)
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
                                    <!-- Badge de statut -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'active') bg-green-100 text-green-800
                                        @elseif($order->status === 'completed') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'closed') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($order->status === 'pending') 🟡 En attente
                                        @elseif($order->status === 'confirmed') 🔵 Confirmée
                                        @elseif($order->status === 'active') 🟢 Active
                                        @elseif($order->status === 'completed') 🟣 Terminée
                                        @elseif($order->status === 'cancelled') 🔴 Annulée
                                        @elseif($order->status === 'closed') 🔒 Clôturée
                                        @else 🔘 {{ $order->status_label }}
                                        @endif
                                    </span>
                                    
                                    <!-- Badge de paiement -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                        @elseif($order->payment_status === 'refunded') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($order->payment_status === 'pending') 💳 En attente
                                        @elseif($order->payment_status === 'paid') 💚 Payé
                                        @elseif($order->payment_status === 'failed') 💔 Échec
                                        @elseif($order->payment_status === 'refunded') 💙 Remboursé
                                        @else 💳 {{ $order->payment_status_label }}
                                        @endif
                                    </span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $order->formatted_total }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Informations de location -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 bg-gray-50 rounded-lg p-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Période de location</p>
                                <p class="text-sm text-gray-900">
                                    Du {{ \Carbon\Carbon::parse($order->start_date)->format('d/m/Y') }} 
                                    au {{ \Carbon\Carbon::parse($order->end_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500">⏱️ {{ $order->rental_days }} jour(s)</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Articles</p>
                                <p class="text-sm text-gray-900">📦 {{ $order->items->count() }} article(s)</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Dernière mise à jour</p>
                                <p class="text-xs text-gray-500">📅 {{ $order->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        <!-- Aperçu des articles -->
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->items->take(3) as $item)
                                    <div class="flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                                        @if($item->product && !empty($item->product->images) && is_array($item->product->images))
                                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-6 h-6 object-cover rounded">
                                        @elseif($item->product && !empty($item->product->gallery_images) && is_array($item->product->gallery_images))
                                            <img src="{{ asset('storage/' . $item->product->gallery_images[0]) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-6 h-6 object-cover rounded">
                                        @endif
                                        <span class="text-sm">{{ $item->product->name ?? 'Produit supprimé' }}</span>
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
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('rental-orders.show', $order) }}" 
                               class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                {{ __("app.content.view_details") }}
                            </a>
                            
                            @if($order->canGenerateInvoice())
                                <a href="{{ route('rental-orders.invoice', $order) }}" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Télécharger facture
                                </a>
                            @endif
                            
                            @if($order->can_be_closed)
                                <button type="button" 
                                        onclick="closeRental({{ $order->id }})"
                                        class="bg-orange-100 hover:bg-orange-200 text-orange-800 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Clôturer la location
                                </button>
                            @endif
                            
                            @if($order->can_be_cancelled)
                                <button type="button" 
                                        onclick="cancelOrder({{ $order->id }}, '{{ $order->status }}')"
                                        class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Annuler
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $orderLocations->withQueryString()->links() }}
            </div>
        @else
            <!-- État vide -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande de location</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande de location.</p>
                    <a href="{{ route('products.index') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-medium transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Découvrir nos produits
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modale d'annulation -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M8.05 6.85L15.95 14.75M15.95 6.85L8.05 14.75"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900">
                        Annuler la commande de location
                    </h3>
                </div>
                
                <p class="text-sm text-gray-500 mb-4">
                    Êtes-vous sûr de vouloir annuler cette commande de location ? Cette action ne peut pas être annulée.
                </p>
                
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison de l'annulation *
                    </label>
                    <textarea 
                        id="cancellation_reason" 
                        name="cancellation_reason" 
                        rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Veuillez expliquer la raison de l'annulation..."
                        required></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCancelModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button type="button" 
                            onclick="confirmCancellation()" 
                            class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Confirmer l'annulation
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale de clôture de location -->
<div id="closeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-xl font-semibold text-gray-900">
                        🔒 Clôture de location
                    </h3>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">
                        Êtes-vous sûr de vouloir clôturer cette location ?
                    </p>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <h4 class="flex items-center text-sm font-medium text-green-800 mb-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Cette action confirme que :
                        </h4>
                        <ul class="text-sm text-green-700 space-y-1">
                            <li>• Vous avez rendu tout le matériel</li>
                            <li>• Le matériel est en bon état</li>
                            <li>• Vous acceptez l'inspection admin</li>
                        </ul>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="flex items-center text-sm font-medium text-yellow-800 mb-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Important à savoir
                        </h4>
                        <p class="text-sm text-yellow-700">
                            Cette action ne peut pas être annulée et déclenchera l'inspection par l'administration.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCloseModal()" 
                            class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        Annuler
                    </button>
                    <button type="button" 
                            onclick="confirmClose()" 
                            class="px-6 py-2 bg-orange-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Confirmer la clôture
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale d'information (location en cours) -->
<div id="infoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M8.05 6.85L15.95 14.75"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900">
                        Annulation impossible
                    </h3>
                </div>
                
                <p class="text-sm text-gray-500 mb-4">
                    Il n'est pas possible d'annuler une location en cours. Veuillez contacter notre service client pour toute assistance.
                </p>
                
                <div class="flex justify-end">
                    <button type="button" 
                            onclick="closeInfoModal()" 
                            class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Compris
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour les modales et l'annulation -->
<script>
let currentOrderId = null;

function cancelOrder(orderId, orderStatus) {
    console.log('cancelOrder called with:', orderId, orderStatus);
    currentOrderId = orderId;
    
    // Debug: vérifier si les éléments existent
    const cancelModal = document.getElementById('cancelModal');
    const infoModal = document.getElementById('infoModal');
    
    console.log('cancelModal found:', !!cancelModal);
    console.log('infoModal found:', !!infoModal);
    
    // Vérifier si la location est en cours
    if (orderStatus === 'active') {
        console.log('Showing info modal for active order');
        if (infoModal) {
            infoModal.style.display = 'block';
            infoModal.classList.remove('hidden');
        }
        return;
    }
    
    // Ouvrir la modale de confirmation
    console.log('Showing cancel modal');
    if (cancelModal) {
        cancelModal.style.display = 'block';
        cancelModal.classList.remove('hidden');
        
        const reasonField = document.getElementById('cancellation_reason');
        if (reasonField) {
            reasonField.value = '';
            reasonField.focus();
        }
    }
}

function closeCancelModal() {
    console.log('Closing cancel modal');
    const cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        cancelModal.style.display = 'none';
        cancelModal.classList.add('hidden');
    }
    currentOrderId = null;
}

function closeInfoModal() {
    console.log('Closing info modal');
    const infoModal = document.getElementById('infoModal');
    if (infoModal) {
        infoModal.style.display = 'none';
        infoModal.classList.add('hidden');
    }
}

function confirmCancellation() {
    console.log('confirmCancellation called');
    const reasonField = document.getElementById('cancellation_reason');
    if (!reasonField) {
        console.error('Reason field not found');
        alert('Erreur: champ de raison introuvable');
        return;
    }
    
    const reason = reasonField.value.trim();
    console.log('Reason:', reason);
    
    if (!reason) {
        alert('Veuillez indiquer une raison pour l\'annulation.');
        reasonField.focus();
        return;
    }
    
    if (!currentOrderId) {
        console.error('No current order ID');
        alert('Erreur: ID de commande manquant');
        return;
    }
    
    console.log('Sending cancellation request for order:', currentOrderId);
    
    // Envoyer la requête d'annulation
    fetch(`/rental-orders/${currentOrderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cancellation_reason: reason
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Fermer la modale et recharger la page
            closeCancelModal();
            alert('Commande annulée avec succès');
            location.reload();
        } else {
            alert(data.error || 'Erreur lors de l\'annulation de la commande');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Erreur lors de l\'annulation de la commande');
    });
}

// Fonction pour clôturer une location
let currentOrderIdForClose = null;

function closeRental(orderId) {
    currentOrderIdForClose = orderId;
    document.getElementById('closeModal').style.display = 'block';
    document.getElementById('closeModal').classList.remove('hidden');
}

function closeCloseModal() {
    document.getElementById('closeModal').style.display = 'none';
    document.getElementById('closeModal').classList.add('hidden');
    currentOrderIdForClose = null;
}

function confirmClose() {
    if (!currentOrderIdForClose) return;
    
    // Appel AJAX pour clôturer la location
    fetch(`/my-rentals/${currentOrderIdForClose}/close`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        closeCloseModal(); // Fermer la modale d'abord
        if (data.success) {
            alert('Location clôturée avec succès');
            location.reload();
        } else {
            alert(data.message || 'Erreur lors de la clôture de la location');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la clôture de la location.');
    });
}

// S'assurer que le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Fermer les modales en cliquant en dehors
    document.addEventListener('click', function(event) {
        const cancelModal = document.getElementById('cancelModal');
        const infoModal = document.getElementById('infoModal');
        const closeModal = document.getElementById('closeModal');
        
        if (event.target === cancelModal) {
            closeCancelModal();
        }
        if (event.target === infoModal) {
            closeInfoModal();
        }
        if (event.target === closeModal) {
            closeCloseModal();
        }
    });
});
</script>
@endsection
