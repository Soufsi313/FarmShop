@extends('layouts.admin')

@section('title', 'Gestion des Offres Spéciales')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Offres Spéciales
                    </h1>
                    <p class="mt-2 text-red-100">
                        Gérez toutes les promotions et offres exceptionnelles de votre boutique
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? $specialOffers->total() }}</div>
                    <div class="text-red-100">Offres totales</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total'] ?? $specialOffers->total() }}</div>
                    <div class="text-sm text-green-700">Total offres</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['active'] ?? $specialOffers->where('is_active', true)->count() }}</div>
                    <div class="text-sm text-blue-700">Actives</div>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['upcoming'] ?? 0 }}</div>
                    <div class="text-sm text-orange-700">À venir</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['expired'] ?? 0 }}</div>
                    <div class="text-sm text-purple-700">Expirées</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton d'ajout et actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Gestion des promotions
                </h2>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour au dashboard
                    </a>
                    <a href="{{ route('admin.special-offers.create') }}" 
                       class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouvelle Offre
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Grille moderne des offres -->
        <div class="p-6">
            @if($specialOffers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($specialOffers as $offer)
                        <div class="bg-gradient-to-br from-white to-red-50 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
                            <!-- Entête de l'offre -->
                            <div class="bg-gradient-to-r from-red-500 to-pink-500 p-4 text-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-bold truncate">{{ $offer->name }}</h3>
                                        <p class="text-red-100 text-sm mt-1">{{ $offer->product->name ?? 'Produit non trouvé' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold">-{{ $offer->discount_percentage }}%</div>
                                        <div class="text-xs text-red-100">Réduction</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu de l'offre -->
                            <div class="p-4">
                                @if($offer->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($offer->description, 100) }}</p>
                                @endif
                                
                                <!-- Dates -->
                                <div class="text-sm mb-4 space-y-1">
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Du {{ $offer->start_date->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Au {{ $offer->end_date->format('d/m/Y') }}
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="flex items-center justify-between mb-4">
                                    @php
                                        $now = now();
                                        $isActive = $offer->is_active && $offer->start_date <= $now && $offer->end_date >= $now;
                                        $isUpcoming = $offer->start_date > $now;
                                        $isExpired = $offer->end_date < $now;
                                    @endphp
                                    
                                    @if($isActive)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            ✅ Active
                                        </span>
                                    @elseif($isUpcoming)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            ⏰ À venir
                                        </span>
                                    @elseif($isExpired)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            ⏳ Expirée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            ❌ Inactive
                                        </span>
                                    @endif
                                    
                                    <span class="text-xs text-gray-500">{{ $offer->created_at->format('d/m/Y') }}</span>
                                </div>

                                <!-- Actions modernes -->
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.special-offers.show', $offer) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50"
                                           title="Voir les détails">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.special-offers.edit', $offer) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 transition-colors p-1 rounded hover:bg-yellow-50"
                                           title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.special-offers.destroy', $offer) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors p-1 rounded hover:bg-red-50"
                                                    title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune offre spéciale</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer votre première offre promotionnelle.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.special-offers.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Créer une offre
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination moderne -->
    @if($specialOffers->hasPages())
    <div class="mt-8 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        {{ $specialOffers->links() }}
    </div>
    @endif
</div>
@endsection
