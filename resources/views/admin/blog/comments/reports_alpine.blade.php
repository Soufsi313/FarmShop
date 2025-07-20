@extends('layouts.admin')

@section('title', 'Gestion des Signalements')
@section('page-title', 'Gestion des Signalements')

@section('content')
<div x-data="reportsManager()" x-init="init()">
    <!-- En-tête avec actions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des Signalements</h1>
                    <p class="mt-1 text-sm text-gray-600">Examinez et traitez les signalements de commentaires</p>
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
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total signalements</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.total_reports || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.pending_reports || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Résolus</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.resolved_reports || 0"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Rejetés</dt>
                            <dd class="text-2xl font-semibold text-gray-900" x-text="stats.dismissed_reports || 0"></dd>
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
            <p class="text-sm text-gray-600">Gérez efficacement les signalements avec nos outils de filtrage et de recherche</p>
        </div>
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Recherche générale
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="filters.search"
                            @input.debounce.500ms="loadReports(1)"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" 
                            placeholder="Rechercher dans les signalements...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Statut du signalement
                    </label>
                    <select 
                        x-model="filters.status"
                        @change="loadReports(1)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="pending">⏳ En attente de traitement</option>
                        <option value="resolved">✅ Signalements résolus</option>
                        <option value="dismissed">❌ Signalements rejetés</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Raison du signalement
                    </label>
                    <select 
                        x-model="filters.reason"
                        @change="loadReports(1)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white transition-colors">
                        <option value="">Toutes les raisons</option>
                        <option value="inappropriate">🚫 Contenu inapproprié</option>
                        <option value="spam">📧 Spam / Publicité</option>
                        <option value="harassment">😡 Harcèlement</option>
                        <option value="hate_speech">💬 Discours haineux</option>
                        <option value="violence">⚔️ Violence</option>
                        <option value="misinformation">❌ Désinformation</option>
                        <option value="other">❓ Autre raison</option>
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
                            @click="loadReports(pagination.current_page)" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-lg text-base font-medium text-white hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Actualiser
                        </button>
                        <button 
                            @click="resetFilters()" 
                            class="px-6 py-4 bg-gray-100 border border-gray-300 rounded-lg text-base font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <span class="ml-2 text-xs text-gray-500">(Priorité, Date, Actions en masse)</span>
                </button>
                
                <div x-show="showAdvancedFilters" x-collapse class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 rounded-lg">
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                </svg>
                                Niveau de priorité
                            </label>
                            <select 
                                x-model="filters.priority"
                                @change="loadReports(1)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white text-sm">
                                <option value="">Toutes les priorités</option>
                                <option value="high">🔴 Priorité haute</option>
                                <option value="medium">🟡 Priorité moyenne</option>
                                <option value="low">🟢 Priorité basse</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Période
                            </label>
                            <select 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white text-sm">
                                <option value="">Toutes les dates</option>
                                <option value="today">📅 Aujourd'hui</option>
                                <option value="week">📆 Cette semaine</option>
                                <option value="month">🗓️ Ce mois</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-medium text-gray-700">
                                <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Actions en masse
                            </label>
                            <div class="flex space-x-2">
                                <button class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg text-xs font-medium hover:bg-green-200 transition-colors">
                                    ✅ Résoudre sélectionnés
                                </button>
                                <button class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                                    ❌ Rejeter sélectionnés
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des signalements -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Signalements</h3>
        </div>
        <div>
            <!-- Loading state -->
            <div x-show="loading" class="flex items-center justify-center h-64">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Chargement des signalements...</p>
                </div>
            </div>

            <!-- Empty state -->
            <div x-show="!loading && reports.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun signalement</h3>
                <p class="mt-1 text-sm text-gray-500">Aucun signalement ne correspond à vos critères.</p>
            </div>

            <!-- Reports table -->
            <div x-show="!loading && reports.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signalé par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="report in reports" :key="report.id">
                            <tr class="hover:bg-gray-50" :class="report.priority === 'high' ? 'bg-red-50' : (report.priority === 'medium' ? 'bg-yellow-50' : '')">
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm text-gray-900 line-clamp-2" x-text="(report.comment?.content || 'Commentaire supprimé').substring(0, 100) + ((report.comment?.content?.length || 0) > 100 ? '...' : '')"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="'Par: ' + (report.comment?.user?.name || report.comment?.guest_name || 'Utilisateur supprimé')"></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <a :href="'/blog/' + (report.comment?.post?.slug || '#')" target="_blank" class="text-blue-600 hover:text-blue-800" x-text="report.comment?.post?.title || 'Article supprimé'"></a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900" x-text="report.reporter?.name || 'Utilisateur supprimé'"></div>
                                    <div class="text-xs text-gray-500" x-text="report.reporter?.email || 'N/A'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getReasonBadgeClass(report.reason)">
                                        <span x-text="getReasonLabel(report.reason)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getPriorityBadgeClass(report.priority)">
                                        <span x-text="getPriorityLabel(report.priority)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getStatusBadgeClass(report.status)">
                                        <span x-text="getStatusLabel(report.status)"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div x-text="formatDate(report.created_at)"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button @click="showReportDetail(report)" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Voir détail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button x-show="report.status === 'pending'" @click="openProcessModal(report)" class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors" title="Traiter">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                        <button @click="viewComment(report.comment?.id)" class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50 transition-colors" title="Voir commentaire">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
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
            <div x-show="!loading && reports.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button 
                            @click="loadReports(pagination.current_page - 1)" 
                            :disabled="pagination.current_page <= 1"
                            :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                            Précédent
                        </button>
                        <button 
                            @click="loadReports(pagination.current_page + 1)" 
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
                                    @click="loadReports(pagination.current_page - 1)" 
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
                                        @click="loadReports(page)" 
                                        :class="page === pagination.current_page ? 
                                            'z-10 bg-blue-50 border-blue-500 text-blue-600' : 
                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        x-text="page">
                                    </button>
                                </template>

                                <!-- Bouton Suivant -->
                                <button 
                                    @click="loadReports(pagination.current_page + 1)" 
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

    <!-- Modal de traitement du signalement -->
    <div x-show="showProcessModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showProcessModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Traiter le Signalement</h3>
                                <button @click="showProcessModal = false" class="bg-white rounded-md text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div x-show="selectedReport" x-data="{
                                processing: false,
                                processForm: {
                                    status: '',
                                    admin_notes: '',
                                    comment_action: 'none'
                                }
                            }">
                                <!-- Informations du signalement -->
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Signalement</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Raison:</span>
                                            <span class="ml-2 font-medium" x-text="getReasonLabel(selectedReport?.reason)"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Priorité:</span>
                                            <span class="ml-2 font-medium" x-text="getPriorityLabel(selectedReport?.priority)"></span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-gray-500">Signalé par:</span>
                                        <span class="ml-2 font-medium" x-text="selectedReport?.reporter?.name"></span>
                                    </div>
                                    <div class="mt-2" x-show="selectedReport?.description">
                                        <span class="text-gray-500">Description:</span>
                                        <p class="mt-1 text-gray-900" x-text="selectedReport?.description"></p>
                                    </div>
                                </div>

                                <!-- Commentaire signalé -->
                                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Commentaire signalé</h4>
                                    <p class="text-sm text-gray-900 whitespace-pre-wrap" x-text="selectedReport?.comment?.content"></p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <span x-text="'Par: ' + (selectedReport?.comment?.user?.name || selectedReport?.comment?.guest_name)"></span>
                                        <span> • </span>
                                        <span x-text="formatDate(selectedReport?.comment?.created_at)"></span>
                                    </p>
                                </div>

                                <!-- Formulaire de traitement -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Décision sur le signalement</label>
                                        <select 
                                            x-model="processForm.status"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="">Choisir une action...</option>
                                            <option value="resolved">Signalement fondé - Résoudre</option>
                                            <option value="dismissed">Signalement non fondé - Rejeter</option>
                                        </select>
                                    </div>

                                    <div x-show="processForm.status === 'resolved'">
                                        <label class="block text-sm font-medium text-gray-700">Action sur le commentaire</label>
                                        <select 
                                            x-model="processForm.comment_action"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="none">Aucune action</option>
                                            <option value="moderate">Modérer le commentaire</option>
                                            <option value="delete">Supprimer le commentaire</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Notes administratives</label>
                                        <textarea 
                                            x-model="processForm.admin_notes"
                                            rows="3" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Notes sur la décision prise..."></textarea>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        @click="showProcessModal = false"
                                        class="px-6 py-3 border border-gray-300 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">
                                        Annuler
                                    </button>
                                    <button 
                                        @click="processReport(processForm)"
                                        :disabled="!processForm.status || processing"
                                        :class="!processForm.status || processing ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                                        class="px-6 py-3 bg-blue-600 border border-transparent rounded-md text-base font-medium text-white">
                                        <span x-show="!processing">Traiter le signalement</span>
                                        <span x-show="processing">Traitement...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de détail du signalement -->
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDetailModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Détail du Signalement</h3>
                                <button @click="showDetailModal = false" class="bg-white rounded-md text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div x-show="selectedReport">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Informations du signalement</h4>
                                        <dl class="space-y-2">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Raison :</dt>
                                                <dd class="text-sm text-gray-900" x-text="getReasonLabel(selectedReport?.reason)"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Priorité :</dt>
                                                <dd class="text-sm text-gray-900" x-text="getPriorityLabel(selectedReport?.priority)"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Statut :</dt>
                                                <dd class="text-sm text-gray-900" x-text="getStatusLabel(selectedReport?.status)"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Date :</dt>
                                                <dd class="text-sm text-gray-900" x-text="formatDate(selectedReport?.created_at)"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Signalé par</h4>
                                        <dl class="space-y-2">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Nom :</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedReport?.reporter?.name || 'Utilisateur supprimé'"></dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Email :</dt>
                                                <dd class="text-sm text-gray-900" x-text="selectedReport?.reporter?.email || 'N/A'"></dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Commentaire signalé</h4>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap" x-text="selectedReport?.comment?.content || 'Commentaire supprimé'"></p>
                                        <p class="text-xs text-gray-500 mt-2">
                                            <span x-text="'Par: ' + (selectedReport?.comment?.user?.name || selectedReport?.comment?.guest_name || 'Utilisateur supprimé')"></span>
                                            <span> • Article: </span>
                                            <span x-text="selectedReport?.comment?.post?.title || 'Article supprimé'"></span>
                                        </p>
                                    </div>
                                </div>
                                <div x-show="selectedReport?.description">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Description du signalement</h4>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-sm text-gray-900 whitespace-pre-wrap" x-text="selectedReport?.description"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="showDetailModal = false" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function reportsManager() {
    return {
        // State
        reports: [],
        stats: {},
        loading: false,
        showDetailModal: false,
        showProcessModal: false,
        selectedReport: null,
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
            reason: '',
            priority: ''
        },
        
        // Methods
        async init() {
            await this.loadReports();
        },
        
        async loadReports(page = 1) {
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    search: this.filters.search,
                    status: this.filters.status,
                    reason: this.filters.reason,
                    priority: this.filters.priority,
                    page: page,
                    per_page: this.pagination.per_page
                });
                
                const response = await fetch(`/admin/blog-comment-reports?${params}`, {
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
                this.reports = data.data.data || [];
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
                this.showToast('Erreur lors du chargement des signalements', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        showReportDetail(report) {
            this.selectedReport = report;
            this.showDetailModal = true;
        },
        
        openProcessModal(report) {
            this.selectedReport = report;
            this.showProcessModal = true;
        },
        
        viewComment(commentId) {
            if (commentId) {
                window.location.href = `/admin/blog-comments?comment_id=${commentId}`;
            }
        },
        
        getPriorityLabel(priority) {
            const labels = {
                'high': 'Haute',
                'medium': 'Moyenne',
                'low': 'Basse'
            };
            return labels[priority] || priority;
        },
        
        getPriorityBadgeClass(priority) {
            const classes = {
                'high': 'bg-red-100 text-red-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'low': 'bg-gray-100 text-gray-800'
            };
            return classes[priority] || 'bg-gray-100 text-gray-800';
        },
        
        getStatusLabel(status) {
            const labels = {
                'pending': 'En attente',
                'resolved': 'Résolu',
                'dismissed': 'Rejeté'
            };
            return labels[status] || status;
        },
        
        getStatusBadgeClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'resolved': 'bg-green-100 text-green-800',
                'dismissed': 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },
        
        getReasonLabel(reason) {
            const labels = {
                'inappropriate': 'Inapproprié',
                'spam': 'Spam',
                'harassment': 'Harcèlement',
                'hate_speech': 'Discours haineux',
                'violence': 'Violence',
                'misinformation': 'Désinformation',
                'other': 'Autre'
            };
            return labels[reason] || reason;
        },
        
        getReasonBadgeClass(reason) {
            const classes = {
                'inappropriate': 'bg-red-100 text-red-800',
                'spam': 'bg-orange-100 text-orange-800',
                'harassment': 'bg-red-100 text-red-800',
                'hate_speech': 'bg-red-100 text-red-800',
                'violence': 'bg-red-100 text-red-800',
                'misinformation': 'bg-yellow-100 text-yellow-800',
                'other': 'bg-gray-100 text-gray-800'
            };
            return classes[reason] || 'bg-gray-100 text-gray-800';
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
        
        async processReport(processForm) {
            if (!this.selectedReport || !processForm.status) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/blog-comment-reports/${this.selectedReport.id}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: processForm.status,
                        admin_notes: processForm.admin_notes,
                        comment_action: processForm.comment_action
                    })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.showToast(data.message || 'Signalement traité avec succès', 'success');
                    this.showProcessModal = false;
                    await this.loadReports(this.pagination.current_page);
                } else {
                    throw new Error('Erreur lors du traitement');
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showToast('Erreur lors du traitement du signalement', 'error');
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
        
        resetFilters() {
            this.filters = {
                search: '',
                status: '',
                reason: '',
                priority: ''
            };
            this.loadReports(1);
        }
    }
}
</script>
@endsection
