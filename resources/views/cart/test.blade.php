@extends('layouts.app')

@section('title', 'Test Panier - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🛒 Test Panier</h1>
        <p class="text-gray-600">Page de test pour débugger</p>
        
        <div class="mt-8 p-4 bg-white rounded-lg shadow">
            <p>Si vous voyez cette page, le problème ne vient pas des routes.</p>
            <p>Token CSRF: {{ csrf_token() }}</p>
            <p>Utilisateur connecté: {{ auth()->check() ? auth()->user()->email : 'Non connecté' }}</p>
        </div>
    </div>
</div>
@endsection
