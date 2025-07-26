@extends('layouts.admin')

@section('title', 'Commande #' . $order->order_number . ' - Dashboard Admin')
@section('page-title', 'Détail de la commande #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Retour -->
    <div>
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Retour à la liste des commandes
        </a>
    </div>

    <!-- Header avec infos principales -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de commande</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Numéro de commande</dt>
                        <dd class="text-sm text-gray-900">#{{ $order->order_number }}</dd>
                    </div>
                    @if($order->invoice_number)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Numéro de facture</dt>
                        <dd class="text-sm text-gray-900">{{ $order->invoice_number }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de commande</dt>
                        <dd class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Montant total</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ number_format($order->total_amount, 2) }}€</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations client</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                        <dd class="text-sm text-gray-900">{{ $order->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $order->user->email }}</dd>
                    </div>
                    @if($order->user->phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                        <dd class="text-sm text-gray-900">{{ $order->user->phone }}</dd>
                    </div>
                    @endif
                    @if($order->shipping_address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Adresse de livraison</dt>
                        <dd class="text-sm text-gray-900">
                            @if(is_array($order->shipping_address))
                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                @if(!empty($order->shipping_address['address_line_2']))
                                    {{ $order->shipping_address['address_line_2'] }}<br>
                                @endif
                                {{ $order->shipping_address['postal_code'] ?? '' }} {{ $order->shipping_address['city'] ?? '' }}<br>
                                {{ $order->shipping_address['country'] ?? '' }}
                            @else
                                {{ $order->shipping_address }}
                            @endif
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statuts</h3>
                <div class="space-y-4">
                    <!-- Statut de commande -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Statut de commande</dt>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'preparing' => 'bg-purple-100 text-purple-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'return_requested' => 'bg-orange-100 text-orange-800',
                                'returned' => 'bg-gray-100 text-gray-800',
                            ];
                            $statusLabels = [
                                'pending' => 'En attente',
                                'confirmed' => 'Confirmée',
                                'preparing' => 'Préparation',
                                'shipped' => 'Expédiée',
                                'delivered' => 'Livrée',
                                'cancelled' => 'Annulée',
                                'return_requested' => 'Retour demandé',
                                'returned' => 'Retournée',
                            ];
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$order->status] }}">
                            {{ $statusLabels[$order->status] }}
                        </span>
                    </div>

                    <!-- Statut de paiement -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Statut de paiement</dt>
                        @php
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-gray-100 text-gray-800',
                            ];
                            $paymentLabels = [
                                'pending' => 'En attente',
                                'paid' => 'Payé',
                                'failed' => 'Échec',
                                'refunded' => 'Remboursé',
                            ];
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paymentColors[$order->payment_status] }}">
                            {{ $paymentLabels[$order->payment_status] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion du statut -->
    @if($order->status !== 'delivered' && $order->status !== 'cancelled')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le statut</h3>
        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            @method('PATCH')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau statut</label>
                <select name="status" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @php
                        $validTransitions = [
                            'pending' => ['confirmed', 'cancelled'],
                            'confirmed' => ['preparing', 'cancelled'],
                            'preparing' => ['shipped', 'cancelled'],
                            'shipped' => ['delivered'],
                        ];
                        $availableStatuses = $validTransitions[$order->status] ?? [];
                    @endphp
                    @foreach($availableStatuses as $status)
                        <option value="{{ $status }}">{{ $statusLabels[$status] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de suivi</label>
                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                       placeholder="Optionnel"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <input type="text" name="notes" value="{{ $order->notes }}" 
                       placeholder="Notes optionnelles"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Articles de la commande -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Articles commandés</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($item->product->image_url)
                                    <img class="h-12 w-12 rounded-lg object-cover mr-4" src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->product->category->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($item->price_per_unit, 2) }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($item->total_price, 2) }}€
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                            @if($order->shipping_cost > 0)
                                <div>Sous-total: {{ number_format($order->total_amount - $order->shipping_cost, 2) }}€</div>
                                <div>Frais de port: {{ number_format($order->shipping_cost, 2) }}€</div>
                                <div class="border-t border-gray-200 pt-2 mt-2">
                            @endif
                            Total:
                            @if($order->shipping_cost > 0)
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">
                            {{ number_format($order->total_amount, 2) }}€
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
