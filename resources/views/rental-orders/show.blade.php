@extends('layouts.app')

@section('title', 'Commande de location #' . $orderLocation->order_number . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 101.414 1.414L9 4.414V17a1 1 0 102 0V4.414l7.293 7.293a1 1 0 001.414-1.414l-9-9z"></path>
                            </svg>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('rental-orders.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                Mes locations
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Commande #{{ $orderLocation->order_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="mt-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Commande de location #{{ $orderLocation->order_number }}</h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Passée le {{ $orderLocation->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'active' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            
                            $statusLabels = [
                                'pending' => 'En attente',
                                'confirmed' => 'Confirmée',
                                'active' => 'En cours',
                                'completed' => 'Terminée',
                                'cancelled' => 'Annulée'
                            ];
                        @endphp
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$orderLocation->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$orderLocation->status] ?? $orderLocation->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Colonne principale : Détails de la commande -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informations de la location -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de location</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Période de location</h3>
                            <p class="text-sm text-gray-900">
                                Du {{ $orderLocation->start_date->format('d/m/Y') }} 
                                au {{ $orderLocation->end_date->format('d/m/Y') }}
                            </p>
                            <p class="text-sm text-purple-600 font-medium">
                                ({{ $orderLocation->rental_days }} jours)
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Statut</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$orderLocation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$orderLocation->status] ?? $orderLocation->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Adresses -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresses</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Adresse de récupération</h3>
                            <div class="p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $orderLocation->pickup_address }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Adresse de retour</h3>
                            <div class="p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $orderLocation->return_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($orderLocation->notes)
                <!-- Notes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes additionnelles</h2>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $orderLocation->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Produits loués -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Produits loués</h2>
                    
                    <div class="space-y-4">
                        @foreach($orderLocation->items as $item)
                        <div class="flex space-x-4 p-4 border border-gray-200 rounded-lg">
                            @if($item->product && $item->product->image_url)
                                <img src="{{ $item->product->image_url }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                @if($item->product_description)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($item->product_description, 100) }}</p>
                                @endif
                                
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                    <span>Quantité: {{ $item->quantity }}</span>
                                    <span>{{ number_format($item->daily_rate, 2) }}€/jour</span>
                                    <span>{{ $item->rental_days }} jours</span>
                                </div>
                                
                                <div class="mt-2">
                                    <span class="text-sm font-medium text-gray-900">
                                        Sous-total: {{ number_format($item->subtotal, 2) }}€
                                    </span>
                                    <span class="text-sm text-gray-600 ml-4">
                                        Caution: {{ number_format($item->deposit_per_item, 2) }}€
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Colonne de droite : Récapitulatif -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Récapitulatif</h2>
                    
                    <!-- Total -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total location</span>
                            <span>{{ number_format($orderLocation->subtotal, 2) }}€</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Caution totale</span>
                            <span>{{ number_format($orderLocation->deposit_amount, 2) }}€</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">TVA ({{ $orderLocation->tax_rate }}%)</span>
                            <span>{{ number_format($orderLocation->tax_amount, 2) }}€</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                            <span>Total payé</span>
                            <span>{{ number_format($orderLocation->total_amount, 2) }}€</span>
                        </div>
                    </div>
                    
                    @if($orderLocation->can_be_cancelled)
                    <!-- Actions pour commandes en attente/confirmées -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button type="button" 
                                onclick="cancelOrder({{ $orderLocation->id }}, '{{ $orderLocation->status }}')"
                                class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            ❌ Annuler la commande
                        </button>
                    </div>
                    @endif
                    
                    @if($orderLocation->canGenerateInvoice())
                    <!-- Téléchargement de facture -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('rental-orders.invoice', $orderLocation) }}" 
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 block text-center">
                            📄 Télécharger la facture
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
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
                            class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        ❌ Confirmer l'annulation
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

// S'assurer que le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Fermer les modales en cliquant en dehors
    document.addEventListener('click', function(event) {
        const cancelModal = document.getElementById('cancelModal');
        const infoModal = document.getElementById('infoModal');
        
        if (event.target === cancelModal) {
            closeCancelModal();
        }
        if (event.target === infoModal) {
            closeInfoModal();
        }
    });
});
</script>
@endsection
