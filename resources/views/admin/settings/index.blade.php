@extends('layouts.admin')

@section('title', 'Paramètres - Dashboard Admin')
@section('page-title', 'Paramètres')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">Paramètres du site</h2>
        <p class="text-gray-600">Configurez les paramètres généraux de votre plateforme</p>
    </div>

    <!-- Paramètres généraux -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informations générales</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom du site</label>
                <input type="text" value="FarmShop" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">Marketplace agricole de confiance pour acheter et louer du matériel agricole de qualité</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email de contact</label>
                <input type="email" value="contact@farmshop.be" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Paramètres de paiement -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Configuration Stripe</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Clé publique Stripe</label>
                <input type="text" placeholder="pk_..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Clé secrète Stripe</label>
                <input type="password" placeholder="sk_..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="flex justify-end space-x-3">
        <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Annuler
        </button>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Sauvegarder
        </button>
    </div>
</div>
@endsection
