@extends('layouts.admin')

@section('title', 'Gestion des commandes - Dashboard Admin')
@section('page-title', 'Gestion des commandes')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Liste des commandes</h2>
            <p class="text-gray-600">Suivez et gérez toutes les commandes</p>
        </div>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Module en développement</h3>
                <p class="mt-1 text-sm text-yellow-700">La gestion des commandes sera disponible une fois le système de panier implémenté.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune commande</h3>
            <p class="mt-1 text-sm text-gray-500">Les commandes apparaîtront ici une fois que les clients commenceront à acheter.</p>
        </div>
    </div>
</div>
@endsection
