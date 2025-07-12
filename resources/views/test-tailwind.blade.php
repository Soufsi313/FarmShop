@extends('layouts.app')

@section('title', 'FarmShop - Test Tailwind')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-100 to-amber-100">
    <!-- Test Section Simple -->
    <div class="container mx-auto px-6 py-20">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-green-800 mb-6">
                🌾 Test Tailwind CSS v4
            </h1>
            <p class="text-2xl text-amber-700 mb-8">
                Si vous voyez ce texte stylé, Tailwind fonctionne !
            </p>
            <div class="flex justify-center gap-4">
                <button class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors">
                    Bouton Vert
                </button>
                <button class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors">
                    Bouton Ambre
                </button>
            </div>
        </div>
        
        <!-- Test Grid -->
        <div class="mt-16 grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-green-700 mb-4">🚜 Test Card 1</h3>
                <p class="text-gray-600">Si cette carte a des ombres et est bien stylée, c'est bon !</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-green-700 mb-4">⚡ Test Card 2</h3>
                <p class="text-gray-600">Les couleurs et espacements doivent être corrects.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-green-700 mb-4">🛡️ Test Card 3</h3>
                <p class="text-gray-600">Responsive design et layout grid fonctionnels.</p>
            </div>
        </div>
    </div>
</div>
@endsection
