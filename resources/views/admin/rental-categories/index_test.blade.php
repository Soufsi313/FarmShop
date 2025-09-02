@extends('layouts.admin')

@section('title', 'Test Catégories de Location')
@section('page-title', 'Test Catégories de Location')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Test de la page des catégories de location</h1>
    <p>Si vous voyez ce message, le problème est résolu.</p>
    
    <div class="mt-4">
        <a href="{{ route('admin.rental-categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Ajouter une catégorie
        </a>
    </div>

    @if(isset($rentalCategories))
        <div class="mt-6">
            <h2 class="text-xl font-semibold mb-4">Catégories trouvées: {{ $rentalCategories->count() }}</h2>
            @foreach($rentalCategories as $category)
                <div class="bg-white p-4 rounded shadow mb-4">
                    <h3 class="font-semibold">{{ $category->name }}</h3>
                    <p class="text-gray-600">{{ $category->description ?? 'Pas de description' }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $category->rental_products_count ?? 0 }} produits de location
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
