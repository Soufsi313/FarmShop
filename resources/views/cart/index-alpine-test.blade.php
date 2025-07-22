@extends('layouts.app')

@section('title', 'Mon Panier - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">🛒 Mon Panier - Test Alpine</h1>
        <p class="text-gray-600">Test basic Alpine.js</p>
        
        <div x-data="{ message: 'Alpine.js fonctionne!', count: 0 }" class="mt-8 p-4 bg-white rounded-lg shadow">
            <p x-text="message"></p>
            <p>Compteur: <span x-text="count"></span></p>
            <button @click="count++" class="px-4 py-2 bg-blue-500 text-white rounded mt-2">
                Incrémenter
            </button>
            
            <div class="mt-4">
                <p>Token CSRF: {{ csrf_token() }}</p>
                <p>Utilisateur: {{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
