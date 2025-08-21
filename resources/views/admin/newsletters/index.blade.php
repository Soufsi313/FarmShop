@extends('layouts.admin')

@section('title', 'Gestion des newsletters - Dashboard Admin')
@section('page-title', 'Gestion des newsletters')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a1 1 0 001.42 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Gestion des newsletters
                    </h1>
                    <p class="mt-2 text-purple-100">
                        Interface de gestion des campagnes email marketing
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-purple-100">Newsletters totales</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['draft'] ?? 0 }}</div>
                    <div class="text-sm text-yellow-700">Brouillons</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['scheduled'] ?? 0 }}</div>
                    <div class="text-sm text-blue-700">Programmées</div>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['sent'] ?? 0 }}</div>
                    <div class="text-sm text-green-700">Envoyées</div>
                </div>
            </div>
        </div>

        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-indigo-600">{{ $stats['subscribers'] ?? 0 }}</div>
                    <div class="text-sm text-indigo-700">Abonnés</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre d'actions et filtres -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 lg:mb-0">
                Filtrer et rechercher
            </h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.newsletters.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle Newsletter
                </a>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillons</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Programmées</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Envoyées</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher par titre, sujet..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Filtrer
                </button>
                <a href="{{ route('admin.newsletters.index') }}" 
                   class="px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des newsletters -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        @if($newsletters->count() > 0)
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">
                    Newsletters ({{ $newsletters->total() }} résultats)
                </h3>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($newsletters as $newsletter)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('admin.newsletters.show', $newsletter) }}" 
                                           class="hover:text-purple-600 transition-colors">
                                            {{ $newsletter->title }}
                                        </a>
                                    </h4>
                                    
                                    @if($newsletter->status == 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Brouillon
                                        </span>
                                    @elseif($newsletter->status == 'scheduled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Programmée
                                        </span>
                                    @elseif($newsletter->status == 'sent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Envoyée
                                        </span>
                                    @endif
                                </div>
                                
                                @if($newsletter->subject)
                                    <p class="text-gray-600 mb-2">{{ $newsletter->subject }}</p>
                                @endif
                                
                                @if($newsletter->excerpt)
                                    <p class="text-gray-500 text-sm mb-3">{{ Str::limit($newsletter->excerpt, 120) }}</p>
                                @endif
                                
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $newsletter->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    @if($newsletter->sent_at)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            Envoyée : {{ $newsletter->sent_at->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-2 ml-6">
                                <!-- Bouton Voir -->
                                <a href="{{ route('admin.newsletters.show', $newsletter) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors group"
                                   title="Voir les détails">
                                    <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Voir
                                </a>
                                
                                <!-- Bouton Modifier -->
                                @if($newsletter->status != 'sent')
                                <a href="{{ route('admin.newsletters.edit', $newsletter) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg transition-colors group"
                                   title="Modifier la newsletter">
                                    <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modifier
                                </a>
                                @else
                                <span class="inline-flex items-center px-3 py-2 text-sm bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed"
                                      title="Newsletter déjà envoyée - modification impossible">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Verrouillée
                                </span>
                                @endif
                                
                                <!-- Bouton Dupliquer -->
                                <a href="{{ route('admin.newsletters.create') }}?duplicate={{ $newsletter->id }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors group"
                                   title="Dupliquer cette newsletter">
                                    <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Dupliquer
                                </a>
                                
                                <!-- Actions supplémentaires selon le statut -->
                                @if($newsletter->status == 'draft')
                                    <!-- Bouton Envoyer maintenant -->
                                    <form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="send_now">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 text-sm bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors group"
                                                title="Envoyer maintenant"
                                                onclick="return confirm('Êtes-vous sûr de vouloir envoyer cette newsletter maintenant ?')">
                                            <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            Envoyer
                                        </button>
                                    </form>
                                @elseif($newsletter->status == 'scheduled')
                                    <!-- Bouton Annuler programmation -->
                                    <form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="draft">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 text-sm bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg transition-colors group"
                                                title="Annuler la programmation"
                                                onclick="return confirm('Annuler la programmation de cette newsletter ?')">
                                            <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Annuler
                                        </button>
                                    </form>
                                @elseif($newsletter->status == 'sent')
                                    <!-- Bouton Renvoyer -->
                                    <form method="POST" action="{{ route('admin.newsletters.resend', $newsletter) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors group"
                                                title="Renvoyer cette newsletter à tous les abonnés"
                                                onclick="return confirm('Êtes-vous sûr de vouloir renvoyer cette newsletter à tous les abonnés actuels ?')">
                                            <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Renvoyer
                                        </button>
                                    </form>
                                @endif
                                
                                <!-- Bouton Supprimer -->
                                <form method="POST" action="{{ route('admin.newsletters.destroy', $newsletter) }}" 
                                      class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette newsletter ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors group"
                                            title="Supprimer définitivement">
                                        <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $newsletters->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a1 1 0 001.42 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune newsletter trouvée</h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->filled('search') || request()->filled('status'))
                        Aucune newsletter ne correspond à vos critères.
                    @else
                        Créez votre première newsletter pour commencer.
                    @endif
                </p>
                <a href="{{ route('admin.newsletters.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer ma première newsletter
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Section Gestion des Abonnés -->
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <!-- En-tête de la section abonnés -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Gestion des Abonnés
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Gérez vos abonnés à la newsletter : abonner, désabonner, filtrer
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['subscribers'] }}</div>
                        <div class="text-sm text-gray-500">Abonnés actifs</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres pour les abonnés -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <!-- Garder les paramètres de newsletter -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                
                <div class="flex-1 min-w-64">
                    <label for="subscriber_search" class="block text-sm font-medium text-gray-700 mb-1">
                        Rechercher un abonné
                    </label>
                    <input type="text" name="subscriber_search" id="subscriber_search" 
                           value="{{ request('subscriber_search') }}"
                           placeholder="Nom ou email..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                
                <div class="min-w-48">
                    <label for="subscription_status" class="block text-sm font-medium text-gray-700 mb-1">
                        Statut d'abonnement
                    </label>
                    <select name="subscription_status" id="subscription_status" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Tous les utilisateurs</option>
                        <option value="subscribed" {{ request('subscription_status') == 'subscribed' ? 'selected' : '' }}>
                            Abonnés uniquement
                        </option>
                        <option value="unsubscribed" {{ request('subscription_status') == 'unsubscribed' ? 'selected' : '' }}>
                            Non abonnés uniquement
                        </option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filtrer
                    </button>
                    
                    @if(request()->filled('subscriber_search') || request()->filled('subscription_status'))
                        <a href="{{ route('admin.newsletters.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Actions en lot pour les abonnés -->
        <div class="px-6 py-3 border-b border-gray-200 bg-blue-50" id="bulk-actions" style="display: none;">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">
                        <span id="selected-count">0</span> utilisateur(s) sélectionné(s)
                    </span>
                    <div class="flex gap-2">
                        <button onclick="bulkAction('subscribe')" 
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 text-sm rounded-lg transition-colors">
                            Abonner
                        </button>
                        <button onclick="bulkAction('unsubscribe')" 
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1.5 text-sm rounded-lg transition-colors">
                            Désabonner
                        </button>
                        <button onclick="bulkAction('delete')" 
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 text-sm rounded-lg transition-colors">
                            Supprimer
                        </button>
                    </div>
                </div>
                <button onclick="clearSelection()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Liste des abonnés -->
        @if($subscribers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all-subscribers" 
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       onchange="toggleAllSubscribers()">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date d'inscription
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subscribers as $subscriber)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_subscribers[]" value="{{ $subscriber->id }}" 
                                       class="subscriber-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       onchange="updateBulkActions()">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr($subscriber->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $subscriber->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Inscrit le {{ $subscriber->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $subscriber->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($subscriber->newsletter_subscribed)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Abonné
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Non abonné
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($subscriber->newsletter_subscribed_at)
                                    {{ $subscriber->newsletter_subscribed_at->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    @if($subscriber->newsletter_subscribed)
                                        <button onclick="toggleSubscription({{ $subscriber->id }}, false)" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                            </svg>
                                            Désabonner
                                        </button>
                                    @else
                                        <button onclick="toggleSubscription({{ $subscriber->id }}, true)" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Abonner
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination des abonnés -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $subscribers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
                <p class="text-gray-500">
                    @if(request()->filled('subscriber_search') || request()->filled('subscription_status'))
                        Aucun utilisateur ne correspond à vos critères de recherche.
                    @else
                        Aucun utilisateur enregistré dans le système.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<script>
// Gestion des abonnés
function toggleAllSubscribers() {
    const selectAll = document.getElementById('select-all-subscribers');
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    selectedCount.textContent = checkboxes.length;
    
    if (checkboxes.length > 0) {
        bulkActions.style.display = 'block';
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.subscriber-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('select-all-subscribers').checked = false;
    updateBulkActions();
}

function toggleSubscription(userId, subscribe) {
    const url = subscribe ? 
        '{{ route("admin.newsletter.subscribers.subscribe") }}' : 
        '{{ route("admin.newsletter.subscribers.unsubscribe") }}';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur s\'est produite');
    });
}

function bulkAction(action) {
    const checkboxes = document.querySelectorAll('.subscriber-checkbox:checked');
    const userIds = Array.from(checkboxes).map(cb => cb.value);
    
    if (userIds.length === 0) {
        alert('Veuillez sélectionner au moins un utilisateur');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'subscribe':
            confirmMessage = `Abonner ${userIds.length} utilisateur(s) à la newsletter ?`;
            break;
        case 'unsubscribe':
            confirmMessage = `Désabonner ${userIds.length} utilisateur(s) de la newsletter ?`;
            break;
        case 'delete':
            confirmMessage = `Supprimer définitivement ${userIds.length} utilisateur(s) ? Cette action est irréversible.`;
            break;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    fetch('{{ route("admin.newsletter.subscribers.bulk") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            action: action,
            user_ids: userIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur s\'est produite');
    });
}
</script>
@endsection