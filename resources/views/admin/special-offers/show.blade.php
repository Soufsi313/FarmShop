@extends('layouts.admin')

@section('title', 'Détails de l\'offre spéciale')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Détails de l'offre spéciale</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.special-offers.edit', $specialOffer) }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Modifier
            </a>
            <a href="{{ route('admin.special-offers.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $specialOffer->title }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                @if($specialOffer->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Actif
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Inactif
                    </span>
                @endif
            </p>
        </div>

        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Titre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $specialOffer->title }}</dd>
                </div>

                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $specialOffer->description }}</dd>
                </div>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Produit</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($specialOffer->product)
                            <a href="{{ route('admin.products.show', $specialOffer->product) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $specialOffer->product->name }}
                            </a>
                        @else
                            <span class="text-gray-500">Produit non trouvé</span>
                        @endif
                    </dd>
                </div>

                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Pourcentage de réduction</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="text-2xl font-bold text-green-600">{{ $specialOffer->discount_percentage }}%</span>
                    </dd>
                </div>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Quantité minimum</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $specialOffer->minimum_quantity }}</dd>
                </div>

                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Limite d'utilisation</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($specialOffer->usage_limit)
                            {{ $specialOffer->usage_limit }}
                            @if($specialOffer->used_count)
                                <span class="text-gray-500">({{ $specialOffer->used_count }} utilisées)</span>
                            @endif
                        @else
                            Illimitée
                        @endif
                    </dd>
                </div>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Période de validité</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($specialOffer->starts_at || $specialOffer->ends_at)
                            @if($specialOffer->starts_at)
                                Du {{ $specialOffer->starts_at->format('d/m/Y H:i') }}
                            @endif
                            @if($specialOffer->ends_at)
                                au {{ $specialOffer->ends_at->format('d/m/Y H:i') }}
                            @endif
                        @else
                            Aucune limite de temps
                        @endif
                    </dd>
                </div>

                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($specialOffer->is_active)
                            @if($specialOffer->isCurrentlyValid())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Actif et valide
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Actif mais hors période
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactif
                            </span>
                        @endif
                    </dd>
                </div>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Dates de création/modification</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div>Créé le : {{ $specialOffer->created_at->format('d/m/Y H:i') }}</div>
                        <div>Modifié le : {{ $specialOffer->updated_at->format('d/m/Y H:i') }}</div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @if($specialOffer->product)
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Aperçu du produit</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex items-center space-x-4">
                @if($specialOffer->product->image_url)
                    <img src="{{ $specialOffer->product->image_url }}" 
                         alt="{{ $specialOffer->product->name }}" 
                         class="h-20 w-20 object-cover rounded-lg">
                @endif
                <div>
                    <h4 class="text-lg font-medium">{{ $specialOffer->product->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $specialOffer->product->category->name ?? 'Aucune catégorie' }}</p>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($specialOffer->product->price, 2) }} €</p>
                    @if($specialOffer->minimum_quantity <= 10)
                        <p class="text-sm text-green-600">
                            Prix avec offre ({{ $specialOffer->minimum_quantity }}+ unités) : 
                            <span class="font-bold">{{ number_format($specialOffer->product->price * (1 - $specialOffer->discount_percentage / 100), 2) }} €</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
