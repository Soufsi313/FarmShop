@extends('layouts.admin')

@section('title', 'Gestion des Commentaires')
@section('page-title', 'Gestion des Commentaires')

@section('content')
<div x-data="commentsManager()" x-init="init()">
    <!-- En-tête avec actions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-7 h-7 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Gestion des Commentaires
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">Gérez tous les commentaires du blog et leur modération</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total commentaires</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.total_comments || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.pending_comments || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Approuvés</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.approved_comments || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Signalés</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.reported_comments || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche avancée -->
    <div class="bg-white shadow-lg rounded-xl p-8 mb-6">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Recherche et Filtres Avancés</h3>
            <p class="text-sm text-gray-600">Trouvez rapidement les commentaires que vous recherchez avec nos outils de filtrage</p>
        </div>
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label for="searchInput" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Recherche générale
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput" 
                            x-model="filters.search"
                            @input.debounce.500ms="loadComments(1)"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                            placeholder="Contenu, auteur, article...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="statusFilter" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Statut de modération
                    </label>
                    <select 
                        id="statusFilter" 
                        x-model="filters.status"
                        @change="loadComments(1)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="pending">⏳ En attente de modération</option>
                        <option value="approved">✅ Commentaires approuvés</option>
                        <option value="rejected">❌ Commentaires rejetés</option>
                        <option value="spam">🚫 Marqués comme spam</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="reportedFilter" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Signalements
                    </label>
                    <select 
                        id="reportedFilter" 
                        x-model="filters.reported"
                        @change="loadComments(1)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors">
                        <option value="">Tous les commentaires</option>
                        <option value="true">⚠️ Commentaires signalés</option>
                        <option value="false">✅ Commentaires non signalés</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                        Actions rapides
                    </label>
                    <div class="flex space-x-2">
                        <button 
                            @click="loadComments(pagination.current_page)" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg text-base font-medium text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Actualiser
                        </button>
                        <button 
                            @click="resetFilters()" 
                            class="px-6 py-4 bg-gray-100 border border-gray-300 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filtres avancés (section repliable) -->
            <div class="border-t border-gray-200 pt-6">
                <button 
                    @click="showAdvancedFilters = !showAdvancedFilters"
                    class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4 mr-2 transform transition-transform" :class="showAdvancedFilters ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Filtres avancés
                    <span class="ml-2 text-xs text-gray-500">(Article, Type d'utilisateur, Date)</span>
                </button>
                
                <div x-show="showAdvancedFilters" x-collapse class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 rounded-lg">
                        <div class="space-y-2">
                            <label for="articleFilter" class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                Article spécifique
                            </label>
                            <select 
                                id="articleFilter" 
                                x-model="filters.post_id"
                                @change="loadComments(1)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm">
                                <option value="">Tous les articles</option>
                                <!-- Options seront chargées dynamiquement -->
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="userTypeFilter" class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Type d'utilisateur
                            </label>
                            <select 
                                id="userTypeFilter" 
                                x-model="filters.type"
                                @change="loadComments(1)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm">
                                <option value="">Tous les types</option>
                                <option value="registered">👤 Utilisateurs inscrits</option>
                                <option value="guest">👥 Utilisateurs invités</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="sortFilter" class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                                </svg>
                                Trier par
                            </label>
                            <select 
                                x-model="filters.sort_by"
                                @change="loadComments(1)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm">
                                <option value="created_at">📅 Date de création</option>
                                <option value="updated_at">🔄 Dernière modification</option>
                                <option value="content">📝 Contenu</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des commentaires -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Commentaires</h3>
        </div>
        <div class="min-h-[400px]">
            <!-- Loading state -->
            <div x-show="loading" class="flex items-center justify-center h-64">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Chargement des commentaires...</p>
                </div>
            </div>

            <!-- Empty state -->
            <div x-show="!loading && comments.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun commentaire</h3>
                <p class="mt-1 text-sm text-gray-500">Aucun commentaire ne correspond à vos critères de recherche.</p>
            </div>

            <!-- Comments table -->
            <div x-show="!loading && comments.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contenu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="comment in comments" :key="comment.id">
                            <tr class="hover:bg-gray-50" :class="comment.is_reported ? 'bg-red-50' : ''">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm text-gray-900 line-clamp-3" x-text="comment.content.substring(0, 100) + (comment.content.length > 100 ? '...' : '')"></p>
                                        <div class="mt-1 flex items-center space-x-2">
                                            <span x-show="comment.is_reported" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Signalé
                                            </span>
                                            <span x-show="comment.is_pinned" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Épinglé
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="comment.user ? comment.user.name : comment.guest_name"></div>
                                    <div class="text-xs text-gray-500" x-text="comment.user ? comment.user.email : comment.guest_email"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <a :href="`/blog/${comment.post?.slug || '#'}`" target="_blank" class="text-blue-600 hover:text-blue-800" x-text="comment.post?.title || 'Article supprimé'"></a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="{
                                              'bg-yellow-100 text-yellow-800': comment.status === 'pending',
                                              'bg-green-100 text-green-800': comment.status === 'approved',
                                              'bg-red-100 text-red-800': comment.status === 'rejected',
                                              'bg-gray-100 text-gray-800': comment.status === 'spam'
                                          }">
                                        <span x-text="getStatusLabel(comment.status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div x-text="formatDate(comment.created_at)"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button @click="showCommentDetail(comment)" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Voir détail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button @click="showModerateModal(comment)" class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors" title="Modérer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                        <button @click="deleteComment(comment)" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div x-show="!loading && comments.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button 
                            @click="loadComments(pagination.current_page - 1)" 
                            :disabled="pagination.current_page <= 1"
                            :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Précédent
                        </button>
                        <button 
                            @click="loadComments(pagination.current_page + 1)" 
                            :disabled="pagination.current_page >= pagination.last_page"
                            :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Suivant
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de 
                                <span class="font-medium" x-text="pagination.from"></span>
                                à 
                                <span class="font-medium" x-text="pagination.to"></span>
                                sur 
                                <span class="font-medium" x-text="pagination.total"></span>
                                résultats
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <!-- Bouton Précédent -->
                                <button 
                                    @click="loadComments(pagination.current_page - 1)" 
                                    :disabled="pagination.current_page <= 1"
                                    :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <!-- Numéros de page -->
                                <template x-for="page in getPageNumbers()" :key="page">
                                    <button 
                                        @click="loadComments(page)" 
                                        :class="page === pagination.current_page ? 
                                            'z-10 bg-blue-50 border-blue-500 text-blue-600' : 
                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        x-text="page">
                                    </button>
                                </template>

                                <!-- Bouton Suivant -->
                                <button 
                                    @click="loadComments(pagination.current_page + 1)" 
                                    :disabled="pagination.current_page >= pagination.last_page"
                                    :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de détail -->
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDetailModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Détail du Commentaire</h3>
                                <button @click="showDetailModal = false" class="bg-white rounded-md text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div x-show="selectedComment">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap" x-text="selectedComment?.content"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Informations de l'auteur</h4>
                                        <dl class="space-y-1">
                                            <div>
                                                <dt class="text-xs text-gray-500">Nom:</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedComment?.user ? selectedComment.user.name : selectedComment?.guest_name"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs text-gray-500">Email:</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedComment?.user ? selectedComment.user.email : selectedComment?.guest_email"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Informations du commentaire</h4>
                                        <dl class="space-y-1">
                                            <div>
                                                <dt class="text-xs text-gray-500">Statut:</dt>
                                                <dd class="text-sm text-gray-900" x-text="getStatusLabel(selectedComment?.status)"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs text-gray-500">Date:</dt>
                                                <dd class="text-sm text-gray-900" x-text="formatDate(selectedComment?.created_at)"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="showDetailModal = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function commentsManager() {
    return {
        // State
        comments: [],
        stats: {},
        loading: false,
        showDetailModal: false,
        selectedComment: null,
        showAdvancedFilters: false,
        
        // Pagination
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
            from: 0,
            to: 0
        },
        
        // Filters
        filters: {
            search: '',
            status: '',
            reported: '',
            post_id: '',
            type: '',
            sort_by: 'created_at',
            sort_order: 'desc'
        },
        
        // Methods
        async init() {
            await this.loadComments();
        },
        
        async loadComments(page = 1) {
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    search: this.filters.search,
                    status: this.filters.status,
                    reported: this.filters.reported,
                    post_id: this.filters.post_id,
                    type: this.filters.type,
                    sort_by: this.filters.sort_by,
                    sort_order: this.filters.sort_order,
                    page: page,
                    per_page: this.pagination.per_page
                });
                
                const response = await fetch(`/admin/blog-comments?${params}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                
                const data = await response.json();
                this.comments = data.data.data || [];
                this.stats = data.meta || {};
                
                // Mettre à jour les informations de pagination
                if (data.data) {
                    this.pagination = {
                        current_page: data.data.current_page,
                        last_page: data.data.last_page,
                        per_page: data.data.per_page,
                        total: data.data.total,
                        from: data.data.from,
                        to: data.data.to
                    };
                }
                
            } catch (error) {
                console.error('Erreur:', error);
                this.showToast('Erreur lors du chargement des commentaires', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        showCommentDetail(comment) {
            this.selectedComment = comment;
            this.showDetailModal = true;
        },
        
        showModerateModal(comment) {
            // TODO: Implémenter la modal de modération
            console.log('Modérer:', comment);
        },
        
        async deleteComment(comment) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/admin/blog/comments/${comment.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    this.showToast('Commentaire supprimé avec succès', 'success');
                    await this.loadComments();
                } else {
                    throw new Error('Erreur lors de la suppression');
                }
            } catch (error) {
                this.showToast('Erreur lors de la suppression', 'error');
            }
        },
        
        getStatusLabel(status) {
            const labels = {
                'pending': 'En attente',
                'approved': 'Approuvé',
                'rejected': 'Rejeté',
                'spam': 'Spam'
            };
            return labels[status] || status;
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        showToast(message, type = 'info') {
            if (window.showToast) {
                window.showToast(message, type);
            } else {
                console.log(`${type}: ${message}`);
            }
        },
        
        getPageNumbers() {
            const pages = [];
            const current = this.pagination.current_page;
            const last = this.pagination.last_page;
            
            // Afficher jusqu'à 7 pages autour de la page courante
            let start = Math.max(1, current - 3);
            let end = Math.min(last, current + 3);
            
            // Ajuster pour avoir toujours 7 pages si possible
            if (end - start < 6) {
                if (start === 1) {
                    end = Math.min(last, start + 6);
                } else if (end === last) {
                    start = Math.max(1, end - 6);
                }
            }
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        },
        
        // Méthodes de pagination
        goToPage(page) {
            if (page >= 1 && page <= this.pagination.last_page && page !== this.pagination.current_page) {
                this.loadComments(page);
            }
        },
        
        nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.loadComments(this.pagination.current_page + 1);
            }
        },
        
        previousPage() {
            if (this.pagination.current_page > 1) {
                this.loadComments(this.pagination.current_page - 1);
            }
        },
        
        resetFilters() {
            this.filters = {
                search: '',
                status: '',
                reported: '',
                post_id: '',
                type: '',
                sort_by: 'created_at',
                sort_order: 'desc'
            };
            this.loadComments(1);
        }
    }
}
</script>
@endsection
