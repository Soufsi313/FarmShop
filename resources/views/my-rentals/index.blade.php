@extends('layouts.app')

@section('title', 'Mes Locations')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mes Locations</h1>
            <p class="text-gray-600 mt-2">Gérez vos locations en cours et consultez l'historique</p>
        </div>

        @if($rentals->count() > 0)
            <!-- Liste des locations -->
            <div class="space-y-6">
                @foreach($rentals as $rental)
                    @php
                        $now = \Carbon\Carbon::now();
                        $status = null;
                        $canClose = false;
                        
                        switch ($rental->status) {
                            case 'confirmed':
                                if ($now->isBefore($rental->start_date)) {
                                    $status = ['label' => 'À venir', 'class' => 'bg-blue-100 text-blue-800'];
                                } elseif ($now->between($rental->start_date, $rental->end_date)) {
                                    $status = ['label' => __("app.status.in_progress"), 'class' => 'bg-green-100 text-green-800'];
                                    $canClose = true;
                                } else {
                                    $status = ['label' => 'En retard', 'class' => 'bg-red-100 text-red-800'];
                                    $canClose = true;
                                }
                                break;
                            case 'started':
                                if ($now->isAfter($rental->end_date)) {
                                    $status = ['label' => 'En retard', 'class' => 'bg-red-100 text-red-800'];
                                } else {
                                    $status = ['label' => __("app.status.in_progress"), 'class' => 'bg-green-100 text-green-800'];
                                }
                                $canClose = true;
                                break;
                            case 'completed':
                                $status = ['label' => 'Terminée', 'class' => 'bg-gray-100 text-gray-800'];
                                break;
                            case 'closed':
                                $status = ['label' => 'Clôturée', 'class' => 'bg-gray-100 text-gray-800'];
                                break;
                        }
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Commande #{{ $rental->order_number }}
                                    </h3>
                                    <p class="text-gray-600 text-sm">
                                        Créée le {{ $rental->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    @if($status)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $status['class'] }}">
                                            {{ $status['label'] }}
                                        </span>
                                    @endif
                                    
                                    @if($canClose)
                                        <div class="mt-2">
                                            <button onclick="closeRental({{ $rental->id }})" 
                                                    class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Clôturer
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Période de location</p>
                                    <p class="font-medium">
                                        {{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $rental->rental_days }} jours</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Montant total</p>
                                    <p class="font-medium text-lg">{{ number_format($rental->total_amount, 2) }} €</p>
                                    @if($rental->deposit_amount > 0)
                                        <p class="text-sm text-gray-500">dont {{ number_format($rental->deposit_amount, 2) }}€ de caution</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Produits loués</p>
                                    <p class="font-medium">{{ $rental->items->count() }} produit(s)</p>
                                    @if($rental->late_fees > 0)
                                        <p class="text-sm text-red-600">Pénalités: {{ number_format($rental->late_fees, 2) }}€</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Aperçu des produits -->
                            <div class="border-t pt-4">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($rental->items->take(3) as $item)
                                        <div class="flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="w-8 h-8 object-cover rounded">
                                            @endif
                                            <span class="text-sm font-medium">{{ $item->product_name }}</span>
                                            @if($item->quantity > 1)
                                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">
                                                    x{{ $item->quantity }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if($rental->items->count() > 3)
                                        <div class="flex items-center px-3 py-2 text-sm text-gray-500">
                                            +{{ $rental->items->count() - 3 }} autre(s)
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex justify-end space-x-2">
                                    @if($rental->canGenerateInvoice())
                                        <a href="{{ route('my-rentals.invoice', $rental) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Facture
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('my-rentals.show', $rental) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ __("app.content.view_detail") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $rentals->links() }}
            </div>
        @else
            <!-- État vide -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune location</h3>
                <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore effectué de location.</p>
                <div class="mt-6">
                    <a href="{{ route('rentals.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition-colors">
                        Découvrir nos produits de location
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Script pour clôturer une location -->
<script>
async function closeRental(rentalId) {
    if (!confirm('Êtes-vous sûr de vouloir clôturer cette location ? Cette action signale que vous avez rendu le matériel.')) {
        return;
    }

    try {
        const response = await fetch(`/my-rentals/${rentalId}/close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Une erreur est survenue lors de la clôture de la location.');
    }
}
</script>
@endsection
