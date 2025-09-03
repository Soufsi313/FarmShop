@extends('layouts.app')

@section('title', __('app.rental_orders.inspection_details') . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.rental_orders.inspection_details') }}</h1>
                    <p class="text-gray-600">{{ __('app.rental_orders.order_number') }} #{{ $orderLocation->order_number }}</p>
                </div>
                <a href="{{ route('rental-orders.index') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md font-medium transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('app.content.back_to_list') }}
                </a>
            </div>
        </div>

        <!-- Informations de la location -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.rental_orders.rental_information') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.rental_orders.rental_period_label') }}</p>
                    <p class="text-sm text-gray-900">
                        {{ __('app.rental_orders.period_from_to', [
                            'start' => \Carbon\Carbon::parse($orderLocation->start_date)->format(__('app.date_format.date')),
                            'end' => \Carbon\Carbon::parse($orderLocation->end_date)->format(__('app.date_format.date'))
                        ]) }}
                    </p>
                    <p class="text-xs text-gray-500">⏱️ {{ $orderLocation->rental_days }} {{ __('app.rental_orders.rental_days') }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.rental_orders.status') }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($orderLocation->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($orderLocation->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($orderLocation->status === 'active') bg-green-100 text-green-800
                        @elseif($orderLocation->status === 'completed') bg-purple-100 text-purple-800
                        @elseif($orderLocation->status === 'closed') bg-orange-100 text-orange-800
                        @elseif($orderLocation->status === 'inspecting') bg-indigo-100 text-indigo-800
                        @elseif($orderLocation->status === 'finished') bg-emerald-100 text-emerald-800
                        @elseif($orderLocation->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ __('app.rental_status.' . $orderLocation->status) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.rental_orders.inspection_date') }}</p>
                    <p class="text-sm text-gray-900">
                        @if($orderLocation->inspection_completed_at)
                            📅 {{ \Carbon\Carbon::parse($orderLocation->inspection_completed_at)->format(__('app.date_format.datetime')) }}
                        @else
                            {{ __('app.rental_orders.not_inspected_yet') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Résultats de l'inspection -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('app.rental_orders.inspection_results') }}</h3>
            
            <!-- Statut général -->
            <div class="mb-6 p-4 rounded-lg @if($orderLocation->has_damages) bg-red-50 border border-red-200 @else bg-green-50 border border-green-200 @endif">
                <div class="flex items-center">
                    @if($orderLocation->has_damages)
                        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h4 class="text-lg font-semibold text-red-800">{{ __('app.rental_orders.damage_detected') }}</h4>
                            <p class="text-red-700">{{ __('app.rental_orders.damage_detected_description') }}</p>
                        </div>
                    @else
                        <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-lg font-semibold text-green-800">{{ __('app.rental_orders.no_damage_detected') }}</h4>
                            <p class="text-green-700">{{ __('app.rental_orders.equipment_returned_good_condition') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Détails des dégâts -->
            @if($orderLocation->has_damages)
                <div class="space-y-6">
                    <!-- Description des dégâts -->
                    @if($orderLocation->damage_notes)
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-2">{{ __('app.rental_orders.damage_description') }}</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $orderLocation->damage_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Photos des dégâts -->
                    @if($orderLocation->damage_photos && count($orderLocation->damage_photos) > 0)
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-4">{{ __('app.rental_orders.damage_photos') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($orderLocation->damage_photos as $photo)
                                    <div class="bg-gray-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             alt="{{ __('app.rental_orders.damage_photo') }}" 
                                             class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Coût des dégâts -->
                    <div>
                        <h4 class="text-md font-semibold text-gray-900 mb-2">{{ __('app.rental_orders.damage_cost_breakdown') }}</h4>
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">{{ __('app.rental_orders.damage_repair_cost') }}</span>
                                    <span class="font-semibold text-red-800">{{ number_format($orderLocation->damage_cost, 2) }}€</span>
                                </div>
                                @if($orderLocation->late_days > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">{{ __('app.rental_orders.late_fees') }} ({{ $orderLocation->late_days }} {{ __('app.rental_orders.days') }})</span>
                                        <span class="font-semibold text-red-800">{{ number_format($orderLocation->late_fees, 2) }}€</span>
                                    </div>
                                @endif
                                <hr class="border-red-300">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="font-semibold text-gray-900">{{ __('app.rental_orders.total_penalties') }}</span>
                                    <span class="font-bold text-red-800">{{ number_format($orderLocation->damage_cost + $orderLocation->late_fees, 2) }}€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Commentaires d'inspection -->
            @if($orderLocation->inspection_notes)
                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-2">{{ __('app.rental_orders.inspection_notes') }}</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $orderLocation->inspection_notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Statut de la caution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('app.rental_orders.deposit_status') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.rental_orders.deposit_amount') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($orderLocation->deposit_amount, 2) }}€</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.rental_orders.penalty_deductions') }}</p>
                    <p class="text-lg font-semibold @if($orderLocation->damage_cost + $orderLocation->late_fees > 0) text-red-600 @else text-gray-900 @endif">
                        {{ number_format($orderLocation->damage_cost + $orderLocation->late_fees, 2) }}€
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('app.rental_orders.deposit_refund_amount') }}</p>
                    <p class="text-lg font-semibold @if($orderLocation->deposit_refund > 0) text-green-600 @else text-red-600 @endif">
                        {{ number_format($orderLocation->deposit_refund, 2) }}€
                    </p>
                </div>
            </div>
            
            @if($orderLocation->deposit_refund < $orderLocation->deposit_amount)
                <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p class="text-orange-800">
                            {{ __('app.rental_orders.deposit_partially_refunded_explanation') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ route('rental-orders.show', $orderLocation) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-medium transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ __('app.rental_orders.view_order_details') }}
            </a>

            @if($orderLocation->canGenerateInvoice())
                <a href="{{ route('rental-orders.invoice', $orderLocation) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('app.rental_orders.download_invoice') }}
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Modale pour agrandir les photos -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closePhotoModal()" 
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalPhoto" src="" alt="{{ __('app.rental_orders.damage_photo') }}" 
             class="max-w-full max-h-full rounded-lg">
    </div>
</div>

@section('scripts')
<script>
function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Fermer la modale en cliquant à l'extérieur
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

// Fermer la modale avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
@endsection

@endsection
