@extends('layouts.admin')

@section('title', 'Gestion des Offres Spéciales')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des Offres Spéciales</h1>
                    <p class="text-gray-600 mt-2">Gérez toutes les offres spéciales de votre boutique (Admin uniquement)</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Retour au dashboard
                    </a>
                    <a href="{{ route('admin.special-offers.create') }}" 
                       class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Nouvelle Offre
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-tags text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Offres</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $specialOffers->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Offres Actives</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $specialOffers->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En Cours</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $specialOffers->filter(function($offer) { 
                                return $offer->start_date <= now() && $offer->end_date >= now() && $offer->is_active; 
                            })->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-pause-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Inactives</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $specialOffers->where('is_active', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offers Grid -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($specialOffers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($specialOffers as $offer)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <!-- Offer Image -->
                            <div class="relative h-48 bg-gray-100">
                                @if($offer->image)
                                    <img src="{{ asset('storage/' . $offer->image) }}" 
                                         alt="{{ $offer->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-100">
                                        <i class="fas fa-percentage text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($offer->is_active)
                                        @if($offer->start_date <= now() && $offer->end_date >= now())
                                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                <i class="fas fa-play mr-1"></i>En cours
                                            </span>
                                        @elseif($offer->start_date > now())
                                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                <i class="fas fa-clock mr-1"></i>Programmée
                                            </span>
                                        @else
                                            <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                                <i class="fas fa-stop mr-1"></i>Expirée
                                            </span>
                                        @endif
                                    @else
                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            <i class="fas fa-pause mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>

                                <!-- Discount Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                        -{{ $offer->discount_percentage }}%
                                    </span>
                                </div>
                            </div>

                            <div class="p-4">
                                <!-- Title -->
                                <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $offer->title }}</h3>
                                
                                <!-- Product -->
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-box mr-1"></i>{{ $offer->product->name }}
                                </p>

                                <!-- Description -->
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($offer->description, 80) }}</p>

                                <!-- Dates -->
                                <div class="text-xs text-gray-500 mb-4">
                                    <div class="flex justify-between">
                                        <span><i class="fas fa-calendar-start mr-1"></i>{{ $offer->start_date->format('d/m/Y') }}</span>
                                        <span><i class="fas fa-calendar-times mr-1"></i>{{ $offer->end_date->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.special-offers.edit', $offer) }}" 
                                       class="flex-1 bg-blue-100 text-blue-700 text-center py-2 px-3 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                        <i class="fas fa-edit mr-1"></i>Modifier
                                    </a>
                                    
                                    <form action="{{ route('admin.special-offers.toggle', $offer) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full {{ $offer->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} py-2 px-3 rounded-lg transition-colors text-sm">
                                            @if($offer->is_active)
                                                <i class="fas fa-pause mr-1"></i>Désactiver
                                            @else
                                                <i class="fas fa-play mr-1"></i>Activer
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.special-offers.destroy', $offer) }}" method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-100 text-red-700 py-2 px-3 rounded-lg hover:bg-red-200 transition-colors text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $specialOffers->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune offre spéciale</h3>
                    <p class="text-gray-600 mb-6">Commencez par créer votre première offre spéciale</p>
                    <a href="{{ route('admin.special-offers.create') }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Créer une offre
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
