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
                                <a href="{{ route('admin.newsletters.show', $newsletter) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors">
                                    Voir
                                </a>
                                
                                <a href="{{ route('admin.newsletters.edit', $newsletter) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg transition-colors">
                                    Modifier
                                </a>
                                
                                @if($newsletter->status == 'draft')
                                    <form method="POST" action="{{ route('admin.newsletters.destroy', $newsletter) }}" 
                                          class="inline" onsubmit="return confirm('Supprimer cette newsletter ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
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
@endsection