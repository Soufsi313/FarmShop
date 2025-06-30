@extends('layouts.public')

@section('title', 'Politique de Cookies')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Politique de Cookies</h1>
        
        <div class="prose lg:prose-lg">
            <p class="text-gray-700 mb-4">
                Cette page décrit notre politique concernant l'utilisation des cookies sur FarmShop.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">Qu'est-ce qu'un cookie ?</h2>
            <p class="text-gray-700 mb-4">
                Un cookie est un petit fichier texte stocké sur votre appareil lors de votre visite sur notre site web.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">Comment nous utilisons les cookies</h2>
            <ul class="list-disc pl-6 text-gray-700 mb-4">
                <li>Cookies essentiels pour le fonctionnement du site</li>
                <li>Cookies de session pour maintenir votre connexion</li>
                <li>Cookies de préférences pour mémoriser vos choix</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">Gérer vos préférences</h2>
            <p class="text-gray-700 mb-4">
                Vous pouvez modifier vos préférences de cookies à tout moment dans les paramètres de votre navigateur.
            </p>
        </div>
    </div>
</div>
@endsection
