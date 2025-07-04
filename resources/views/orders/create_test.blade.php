@extends('layouts.app')

@section('title', 'Test Checkout')

@section('content')
<div class="container">
    <h1>Test Checkout - Page Simple</h1>
    <p>Si vous voyez ceci, le problème ne vient pas du contrôleur.</p>
    
    <div class="alert alert-info">
        <h4>Informations de debug :</h4>
        <ul>
            <li>Utilisateur connecté : {{ auth()->check() ? 'Oui' : 'Non' }}</li>
            <li>Nom utilisateur : {{ auth()->user()->name ?? 'N/A' }}</li>
            <li>Nombre d'articles dans le panier : {{ isset($cartItems) ? count($cartItems) : 'N/A' }}</li>
        </ul>
    </div>
</div>
@endsection
