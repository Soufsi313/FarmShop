@extends('layouts.admin')

@section('title', 'Détails Utilisateur - Dashboard Admin')
@section('page-title', 'Détails de l\'utilisateur')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name ?: $user->username }}</h2>
            <p class="text-gray-600">{{ $user->email }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                        <dd class="text-sm text-gray-900">{{ $user->name ?: 'Non renseigné' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom d'utilisateur</dt>
                        <dd class="text-sm text-gray-900">{{ $user->username }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                        <dd class="text-sm">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $user->role === 'Admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->role }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Préférences -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Préférences</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Newsletter</dt>
                        <dd class="text-sm">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $user->newsletter_subscribed ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $user->newsletter_subscribed ? 'Abonné' : 'Non abonné' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                        <dd class="text-sm">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $user->email_verified_at ? 'Vérifié' : 'Non vérifié' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Activité -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activité récente</h3>
                
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="mt-2 text-sm">Aucune activité récente disponible</p>
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">
            <!-- Avatar et statut -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Profil</h3>
                
                <div class="text-center">
                    <div class="mx-auto h-20 w-20 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-2xl">
                        {{ substr($user->name ?: $user->username, 0, 1) }}
                    </div>
                    <h4 class="mt-3 text-lg font-medium text-gray-900">{{ $user->name ?: $user->username }}</h4>
                    <p class="text-sm text-gray-500">@{{ $user->username }}</p>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Commandes</dt>
                        <dd class="text-sm font-medium text-gray-900">0</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Panier</dt>
                        <dd class="text-sm font-medium text-gray-900">0 article(s)</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Wishlist</dt>
                        <dd class="text-sm font-medium text-gray-900">0 article(s)</dd>
                    </div>
                </dl>
            </div>

            <!-- Informations système -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations système</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Inscrit le</dt>
                        <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                        <dd class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @if($user->email_verified_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email vérifié le</dt>
                        <dd class="text-sm text-gray-900">{{ $user->email_verified_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                        <dd class="text-sm text-gray-900">{{ $user->id }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Actions rapides -->
            @if($user->id !== auth()->id())
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Supprimer l'utilisateur
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
