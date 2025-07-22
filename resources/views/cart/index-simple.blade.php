@extends('layouts.app')

@section('title', 'Mon Panier - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🛒 Mon Panier</h1>
        <p class="text-gray-600">Vérifiez vos articles avant de passer commande</p>
        
        <div class="mt-8 p-4 bg-white rounded-lg shadow">
            <p>Version simplifiée pour tests - Alpine.js désactivé</p>
            <p>Token CSRF: {{ csrf_token() }}</p>
            <p>Utilisateur: {{ auth()->user()->email }}</p>
            
            <div class="mt-4">
                <button class="px-4 py-2 bg-blue-500 text-white rounded" onclick="alert('Test JS')">
                    Test JavaScript
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
