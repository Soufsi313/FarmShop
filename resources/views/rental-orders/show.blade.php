@extends('layouts.app')

@section('title', 'Commande de location #' . $orderLocation->order_number . ' - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
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
                                'in_progress' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            
                            $statusLabels = [
                                'pending' => 'En attente',
                                'confirmed' => 'Confirmée',
                                'in_progress' => 'En cours',
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
                                ({{ $orderLocation->getRentalDaysCount() }} jours)
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
                        @foreach($orderLocation->orderItemLocations as $item)
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
                                    <span>{{ number_format($item->daily_price, 2) }}€/jour</span>
                                    <span>{{ $item->total_days }} jours</span>
                                </div>
                                
                                <div class="mt-2">
                                    <span class="text-sm font-medium text-gray-900">
                                        Sous-total: {{ number_format($item->subtotal, 2) }}€
                                    </span>
                                    <span class="text-sm text-gray-600 ml-4">
                                        Caution: {{ number_format($item->deposit_amount, 2) }}€
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
                    
                    @if($orderLocation->status === 'pending')
                    <!-- Actions pour commandes en attente -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form method="POST" action="{{ route('rental-orders.cancel', $orderLocation) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                            @csrf
                            <input type="hidden" name="cancellation_reason" value="Annulation par le client">
                            <button type="submit" 
                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Annuler la commande
                            </button>
                        </form>
                    </div>
                    @endif
                    
                    @if(in_array($orderLocation->status, ['completed']))
                    <!-- Actions pour commandes terminées -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Télécharger la facture
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
