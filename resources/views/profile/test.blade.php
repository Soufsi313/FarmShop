@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold">Test Profil</h1>
    <p>Utilisateur: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
</div>
@endsection
