@extends('layouts.admin')

@section('title', 'Gestion des produits - Dashboard Admin')
@section('page-title', 'Gestion des produits')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec bouton d'action -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Liste des produits</h2>
            <p class="text-gray-600">Gérez le catalogue de produits agricoles</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Ajouter un produit
        </button>
    </div>

    <!-- Message informatif -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800">Module produits en développement</h3>
                <p class="text-sm text-blue-700 mt-1">La gestion des produits sera disponible prochainement. Le système de pagination et de tri (20 éléments par page) est déjà prêt à être implémenté.</p>
            </div>
        </div>
    </div>

    <!-- Tableau vide pour l'instant -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun produit</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier produit.</p>
            <div class="mt-6">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Ajouter un produit
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
