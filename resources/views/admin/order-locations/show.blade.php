@extends('layouts.admin')

@section('title', __('order_locations.order_detail_title', ['number' => $orderLocation->order_number]) . ' - Dashboard Admin')
@section('page-title', __('order_locations.order_detail_title', ['number' => $orderLocation->order_number]))

@section('content')
<div class="space-y-6">
    <!-- Retour -->
    <div>
        <a href="{{ route('admin.order-locations.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← {{ __('order_locations.back_to_orders') }}
        </a>
    </div>

    <!-- Header avec infos principales -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('order_locations.rental_information') }}</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.order_number_label') }}</dt>
                        <dd class="text-sm text-gray-900">#{{ $orderLocation->order_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.order_date') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $orderLocation->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.rental_period') }}</dt>
                        <dd class="text-sm text-gray-900">
                            {{ __('order_locations.from') }} {{ \Carbon\Carbon::parse($orderLocation->start_date)->format('d/m/Y') }} 
                            {{ __('order_locations.to') }} {{ \Carbon\Carbon::parse($orderLocation->end_date)->format('d/m/Y') }}
                            <span class="text-gray-500">
                                ({{ \Carbon\Carbon::parse($orderLocation->start_date)->diffInDays($orderLocation->end_date) }} {{ __('order_locations.duration') }})
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.total_amount') }}</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ number_format($orderLocation->total_amount, 2) }}€</dd>
                    </div>
                    @if($orderLocation->caution_amount)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.deposit_amount') }}</dt>
                        <dd class="text-sm text-gray-900">{{ number_format($orderLocation->caution_amount, 2) }}€</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('order_locations.customer_information') }}</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.customer_name') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $orderLocation->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.customer_email') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $orderLocation->user->email }}</dd>
                    </div>
                    @if($orderLocation->user->phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.customer_phone') }}</dt>
                        <dd class="text-sm text-gray-900">{{ $orderLocation->user->phone }}</dd>
                    </div>
                    @endif
                    @if($orderLocation->delivery_address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('order_locations.delivery_address') }}</dt>
                        <dd class="text-sm text-gray-900 whitespace-pre-line">
                            @if(is_array($orderLocation->delivery_address))
                                {{ implode("\n", $orderLocation->delivery_address) }}
                            @else
                                {{ $orderLocation->delivery_address }}
                            @endif
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statut et gestion</h3>
                <div class="space-y-4">
                    <!-- Statut actuel -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Statut de location</dt>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'active' => 'bg-green-100 text-green-800',
                                'pending_return' => 'bg-orange-100 text-orange-800',
                                'returned' => 'bg-gray-100 text-gray-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending' => 'En attente',
                                'confirmed' => 'Confirmée',
                                'active' => 'Active',
                                'pending_return' => 'Retour attendu',
                                'returned' => 'Retournée',
                                'cancelled' => 'Annulée',
                            ];
                        @endphp
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$orderLocation->status] }}">
                            {{ $statusLabels[$orderLocation->status] }}
                        </span>
                    </div>

                    <!-- Temps restant ou échéance -->
                    @if($orderLocation->status === 'active')
                        @php
                            $endDate = \Carbon\Carbon::parse($orderLocation->end_date);
                            $now = \Carbon\Carbon::now();
                            $daysRemaining = $now->diffInDays($endDate, false);
                        @endphp
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Temps restant</dt>
                            <dd class="text-sm text-gray-900">
                                @if($daysRemaining > 0)
                                    <span class="text-green-600">{{ $daysRemaining }} jour(s)</span>
                                @elseif($daysRemaining === 0)
                                    <span class="text-orange-600">Se termine aujourd'hui</span>
                                @else
                                    <span class="text-red-600">En retard de {{ abs($daysRemaining) }} jour(s)</span>
                                @endif
                            </dd>
                        </div>
                    @endif

                    @if($orderLocation->notes)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-1">Notes</dt>
                        <dd class="text-sm text-gray-900">{{ $orderLocation->notes }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion du statut -->
    @if($orderLocation->status !== 'returned' && $orderLocation->status !== 'cancelled')
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le statut</h3>
        <form method="POST" action="{{ route('admin.order-locations.update-status', $orderLocation) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            @method('PATCH')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau statut</label>
                <select name="status" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @php
                        $validTransitions = [
                            'pending' => ['confirmed', 'cancelled'],
                            'confirmed' => ['active', 'cancelled'],
                            'active' => ['pending_return', 'cancelled'],
                            'pending_return' => ['returned'],
                        ];
                        $availableStatuses = $validTransitions[$orderLocation->status] ?? [];
                    @endphp
                    @foreach($availableStatuses as $status)
                        <option value="{{ $status }}">{{ $statusLabels[$status] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <input type="text" name="notes" value="{{ $orderLocation->notes }}" 
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

    <!-- Articles de la location -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Matériel loué</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix/jour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orderLocation->orderItemLocations as $item)
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
                                    <div class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Catégorie non définie' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($item->daily_rate, 2) }}€
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                                $startDate = \Carbon\Carbon::parse($item->rental_start_date ?? $orderLocation->start_date);
                                $endDate = \Carbon\Carbon::parse($item->rental_end_date ?? $orderLocation->end_date);
                                $days = $startDate->diffInDays($endDate);
                            @endphp
                            {{ $days }} jour(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($item->subtotal, 2) }}€
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                            @if($orderLocation->caution_amount && $orderLocation->caution_amount > 0)
                                <div>Sous-total: {{ number_format($orderLocation->total_amount - $orderLocation->caution_amount, 2) }}€</div>
                                <div>Caution: {{ number_format($orderLocation->caution_amount, 2) }}€</div>
                                <div class="border-t border-gray-200 pt-2 mt-2">
                            @endif
                            Total:
                            @if($orderLocation->caution_amount && $orderLocation->caution_amount > 0)
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">
                            {{ number_format($orderLocation->total_amount, 2) }}€
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Historique et notes -->
    @if($orderLocation->notes || $orderLocation->updated_at != $orderLocation->created_at)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Historique</h3>
        <div class="space-y-3">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="h-2 w-2 bg-blue-400 rounded-full mt-2"></div>
                </div>
                <div>
                    <p class="text-sm text-gray-900">Location créée</p>
                    <p class="text-xs text-gray-500">{{ $orderLocation->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            
            @if($orderLocation->updated_at != $orderLocation->created_at)
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="h-2 w-2 bg-green-400 rounded-full mt-2"></div>
                </div>
                <div>
                    <p class="text-sm text-gray-900">Dernière mise à jour</p>
                    <p class="text-xs text-gray-500">{{ $orderLocation->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
