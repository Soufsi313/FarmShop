@extends('layouts.app')

@section('title', 'Commande #' . $order->order_number . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" 
     x-data="{
         order: {
             id: {{ $order->id }},
             status: '{{ $order->status }}',
             payment_status: '{{ $order->payment_status }}',
             delivered_at: {{ $order->delivered_at ? "'" . $order->delivered_at->toISOString() . "'" : 'null' }},
             has_returnable_items: {{ $order->has_returnable_items ? 'true' : 'false' }},
             can_be_cancelled: {{ $order->can_be_cancelled ? 'true' : 'false' }},
             invoice_number: '{{ $order->invoice_number ?? '' }}'
         },
         get canBeReturnedNow() {
             if (this.order.status !== 'delivered' || !this.order.has_returnable_items || !this.order.delivered_at) {
                 return false;
             }
             const deliveredDate = new Date(this.order.delivered_at);
             const now = new Date();
             const daysDiff = Math.floor((now - deliveredDate) / (1000 * 60 * 60 * 24));
             return daysDiff <= 14;
         }
     }"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Commande #{{ $order->order_number }}
                    </h1>
                    <p class="text-gray-600">
                        Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $order->status == 'return_requested' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $order->status == 'returned' ? 'bg-gray-100 text-gray-800' : '' }}">
                        @switch($order->status)
                            @case('pending')
                                🟡 En attente de confirmation
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
                            @case('return_requested')
                                🔶 Retour demandé
                                @break
                            @case('returned')
                                🟢 Retournée
                                @if($order->refund_processed)
                                    <span class="text-xs block text-green-600">✅ Remboursement effectué</span>
                                @else
                                    <span class="text-xs block text-orange-600">⏳ Remboursement en cours</span>
                                @endif
                                @break
                            @default
                                ⚪ {{ ucfirst($order->status) }}
                        @endswitch
                    </span>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" 
                   class="text-green-600 hover:text-green-800 text-sm font-medium">
                    ← Retour à mes commandes
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            
            <!-- Colonnes de gauche: Détails de la commande -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Articles commandés -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Articles commandés</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-start space-x-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <!-- Image produit -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" 
                                             alt="{{ $item->product_name }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-2xl">📦</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Détails produit -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-gray-900">{{ $item->product_name }}</h3>
                                @if($item->product_sku)
                                <p class="text-sm text-gray-600">SKU: {{ $item->product_sku }}</p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">{{ $item->product_description }}</p>
                                
                                <!-- Statut de l'article -->
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $item->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $item->status == 'preparing' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $item->status == 'shipped' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $item->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $item->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Prix et quantité -->
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm text-gray-600">{{ $item->quantity }} × {{ number_format($item->unit_price, 2) }} €</p>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($item->total_price, 2) }} €</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Informations de livraison -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresse de livraison</h2>
                    <div class="text-sm">
                        <p class="font-medium">{{ $order->shipping_address['name'] }}</p>
                        <p>{{ $order->shipping_address['address'] }}</p>
                        <p>{{ $order->shipping_address['postal_code'] }} {{ $order->shipping_address['city'] }}</p>
                        <p>{{ $order->shipping_address['country'] }}</p>
                        @if(isset($order->shipping_address['phone']))
                        <p class="mt-2">📞 {{ $order->shipping_address['phone'] }}</p>
                        @endif
                    </div>
                    
                    @if($order->tracking_number)
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-blue-900">Numéro de suivi</p>
                        <p class="text-sm text-blue-800">{{ $order->tracking_number }}</p>
                    </div>
                    @endif
                </div>

                <!-- Informations de facturation -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresse de facturation</h2>
                    <div class="text-sm">
                        <p class="font-medium">{{ $order->billing_address['name'] }}</p>
                        <p>{{ $order->billing_address['address'] }}</p>
                        <p>{{ $order->billing_address['postal_code'] }} {{ $order->billing_address['city'] }}</p>
                        <p>{{ $order->billing_address['country'] }}</p>
                    </div>
                </div>

            </div>

            <!-- Colonne de droite: Résumé et actions -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Résumé financier -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Résumé de la commande</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total</span>
                            <span>{{ number_format($order->subtotal, 2) }} €</span>
                        </div>
                        
                        @if($order->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">TVA</span>
                            <span>{{ number_format($order->tax_amount, 2) }} €</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Frais de livraison</span>
                            <span class="{{ $order->shipping_cost == 0 ? 'text-green-600' : '' }}">
                                {{ $order->shipping_cost == 0 ? 'GRATUITE' : number_format($order->shipping_cost, 2) . ' €' }}
                            </span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Remise</span>
                            <span class="text-green-600">-{{ number_format($order->discount_amount, 2) }} €</span>
                        </div>
                        @endif
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>{{ number_format($order->total_amount, 2) }} €</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statut du paiement -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Paiement</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Méthode</span>
                            <span class="capitalize">
                                @switch($order->payment_method)
                                    @case('card')
                                        💳 Carte bancaire
                                        @break
                                    @case('paypal')
                                        💰 PayPal
                                        @break
                                    @case('bank_transfer')
                                        🏦 Virement bancaire
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Statut</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->payment_status == 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                @switch($order->payment_status)
                                    @case('paid')
                                        ✅ Payé
                                        @break
                                    @case('pending')
                                        ⏳ En attente
                                        @break
                                    @case('failed')
                                        ❌ Échec
                                        @break
                                    @default
                                        {{ ucfirst($order->payment_status) }}
                                @endswitch
                            </span>
                        </div>
                        
                        @if($order->paid_at)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payé le</span>
                            <span>{{ $order->paid_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        @if($order->can_be_cancelled && in_array($order->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 transition-colors">
                                Annuler la commande
                            </button>
                        </form>
                        @endif
                        
                        <!-- Bouton de téléchargement de facture (si payée) -->
                        @if($order->payment_status == 'paid')
                        <a href="#" onclick="alert('Fonctionnalité bientôt disponible')"
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors text-center block">
                            📄 Télécharger la facture
                        </a>
                        @endif
                        
                        <!-- Retourner la commande (si livrée et dans les 14 jours) -->
                        <a x-show="canBeReturnedNow" 
                           href="{{ route('orders.return.confirm', $order) }}" 
                           class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-orange-700 transition-colors text-center block">
                            🔄 Retourner la commande
                        </a>
                        
                        <!-- Renouveler la commande -->
                        <a href="#" onclick="alert('Fonctionnalité bientôt disponible')"
                           class="w-full bg-green-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors text-center block">
                            🔄 Renouveler cette commande
                        </a>
                    </div>
                </div>

            </div>
        </div>
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

// Fonction pour mettre à jour l'affichage du statut sans recharger la page
function updateStatusDisplay(newStatus) {
    // Mettre à jour le badge de statut
    const statusBadge = document.querySelector('[x-text*="status"]');
    if (statusBadge) {
        statusBadge.textContent = getStatusText(newStatus);
        statusBadge.className = getStatusClasses(newStatus);
    }
    
    // Mettre à jour les boutons d'action
    updateActionButtons(newStatus);
}

function getStatusText(status) {
    const statusTexts = {
        'pending': '⏳ En attente',
        'confirmed': '✅ Confirmée',
        'preparing': '📦 En préparation',
        'shipped': '🚚 Expédiée',
        'delivered': '📍 Livrée',
        'cancelled': '❌ Annulée',
        'return_requested': '🔄 Retour demandé',
        'returned': '↩️ Retournée'
    };
    return statusTexts[status] || status;
}

function getStatusClasses(status) {
    const baseClasses = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ';
    const statusClasses = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-blue-100 text-blue-800',
        'preparing': 'bg-indigo-100 text-indigo-800',
        'shipped': 'bg-purple-100 text-purple-800',
        'delivered': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
        'return_requested': 'bg-orange-100 text-orange-800',
        'returned': 'bg-gray-100 text-gray-800'
    };
    return baseClasses + (statusClasses[status] || '');
}

function updateActionButtons(status) {
    // Masquer/afficher les boutons selon le statut
    const cancelButton = document.querySelector('button[onclick*="cancel"]');
    const returnButton = document.querySelector('button[onclick*="return"]');
    
    if (cancelButton) {
        cancelButton.style.display = ['pending', 'confirmed'].includes(status) ? 'inline-flex' : 'none';
    }
    
    if (returnButton) {
        returnButton.style.display = status === 'delivered' ? 'inline-flex' : 'none';
    }
}

// Polling pour mettre à jour le statut en temps réel
function pollOrderUpdate() {
    const orderData = Alpine.$data(document.querySelector('[x-data]'));
    if (orderData && orderData.order) {
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
                    
                    // Mettre à jour l'affichage du statut dynamiquement (sans recharger la page)
                    updateStatusDisplay(data.order.status);
                    
                    console.log('Statut mis à jour:', data.order.status);
                }
            })
            .catch(error => {
                console.log('Erreur lors de la mise à jour du statut:', error);
            });
    }
}

// Démarrer le polling toutes les 10 secondes
setInterval(pollOrderUpdate, 10000);

// Premier appel après 5 secondes
setTimeout(pollOrderUpdate, 5000);
</script>

@endsection
