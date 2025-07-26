@extends('layouts.admin')

@section('title', 'Gestion des Cookies - FarmShop Admin')
@section('page-title', 'Gestion des Cookies')

@section('content')
<div class="space-y-6" x-data="cookieManagement">
    <!-- Statistiques des cookies -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total des consentements -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total consentements</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.total_consents">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consentements acceptés -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Acceptés</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.accepted_consents">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consentements rejetés -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Rejetés</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.rejected_consents">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En attente -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-lg font-medium text-gray-900" x-text="stats.pending_consents">-</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques des préférences -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique par type de cookie -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Préférences par type de cookie</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">🔧 Cookies essentiels</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-600 h-2 rounded-full" x-bind:style="`width: 100%`"></div>
                        </div>
                        <span class="text-sm font-medium" x-text="stats.necessary_count">-</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">📊 Cookies analytiques</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-600 h-2 rounded-full" x-bind:style="`width: ${getPercentage(stats.analytics_count)}%`"></div>
                        </div>
                        <span class="text-sm font-medium" x-text="stats.analytics_count">-</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">🎯 Cookies marketing</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-purple-600 h-2 rounded-full" x-bind:style="`width: ${getPercentage(stats.marketing_count)}%`"></div>
                        </div>
                        <span class="text-sm font-medium" x-text="stats.marketing_count">-</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">⚙️ Cookies préférences</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-orange-600 h-2 rounded-full" x-bind:style="`width: ${getPercentage(stats.preferences_count)}%`"></div>
                        </div>
                        <span class="text-sm font-medium" x-text="stats.preferences_count">-</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">📱 Cookies sociaux</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-pink-600 h-2 rounded-full" x-bind:style="`width: ${getPercentage(stats.social_media_count)}%`"></div>
                        </div>
                        <span class="text-sm font-medium" x-text="stats.social_media_count">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution dans le temps -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Consentements par jour (7 derniers jours)</h3>
            <div class="space-y-3">
                <template x-for="day in stats.daily_consents" :key="day.date">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600" x-text="formatDate(day.date)"></span>
                        <div class="flex items-center">
                            <div class="flex items-center mr-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm" x-text="day.accepted">0</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm" x-text="day.rejected">0</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Liste des consentements récents -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Consentements récents</h3>
                <div class="flex space-x-3">
                    <button @click="refreshData()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualiser
                    </button>
                    <button @click="exportData()" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exporter
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Préférences</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="cookie in cookies" :key="cookie.id">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full flex items-center justify-center"
                                             :class="cookie.user ? 'bg-blue-100 text-blue-800' : 'bg-gray-300 text-gray-700'">
                                            <span class="text-sm font-medium" x-text="cookie.user ? cookie.user.email.substring(0, 2).toUpperCase() : 'V'"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <span x-text="cookie.user ? cookie.user.email : 'Visiteur anonyme'"></span>
                                            <span x-show="cookie.user" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                Connecté
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <span x-show="cookie.user && !cookie.session_id" class="italic">Utilisateur connecté</span>
                                            <span x-show="cookie.session_id" x-text="cookie.session_id.substring(0, 10) + '...'"></span>
                                            <span x-show="!cookie.user && !cookie.session_id">Session inconnue</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="cookie.status === 'accepted' ? 'bg-green-100 text-green-800' : cookie.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'" 
                                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    <span x-text="cookie.status === 'accepted' ? 'Accepté' : cookie.status === 'rejected' ? 'Rejeté' : 'En attente'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex space-x-1">
                                    <span x-show="cookie.necessary" class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">🔧</span>
                                    <span x-show="cookie.analytics" class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">📊</span>
                                    <span x-show="cookie.marketing" class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">🎯</span>
                                    <span x-show="cookie.preferences" class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">⚙️</span>
                                    <span x-show="cookie.social_media" class="bg-pink-100 text-pink-800 px-2 py-1 rounded text-xs">📱</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="new Date(cookie.updated_at).toLocaleString('fr-FR')"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="cookie.ip_address"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- Bouton Voir -->
                                    <button @click="viewDetails(cookie)" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Voir les détails">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Bouton Supprimer -->
                                    <button @click="deleteConsent(cookie.id)" 
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            title="Supprimer le consentement">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="previousPage()" :disabled="currentPage <= 1" 
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Précédent
                </button>
                <button @click="nextPage()" :disabled="currentPage >= totalPages" 
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Suivant
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Affichage <span x-text="(currentPage - 1) * perPage + 1"></span> à 
                        <span x-text="Math.min(currentPage * perPage, totalItems)"></span> sur 
                        <span x-text="totalItems"></span> résultats
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                        <button @click="previousPage()" :disabled="currentPage <= 1" 
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Précédent
                        </button>
                        <button @click="nextPage()" :disabled="currentPage >= totalPages" 
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Suivant
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale des détails -->
    <div x-show="showModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>

            <!-- Contenu de la modale -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Détails du consentement
                            </h3>
                            
                            <template x-if="selectedCookie">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Informations utilisateur -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-700 border-b pb-2">👤 Informations utilisateur</h4>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Utilisateur:</span>
                                            <p class="text-sm text-gray-900" x-text="selectedCookie.user ? selectedCookie.user.email : 'Visiteur anonyme'"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Session ID:</span>
                                            <p class="text-sm text-gray-900 font-mono" x-text="selectedCookie.session_id || 'Utilisateur connecté'"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Adresse IP:</span>
                                            <p class="text-sm text-gray-900" x-text="selectedCookie.ip_address"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">User Agent:</span>
                                            <p class="text-sm text-gray-900 break-all" x-text="selectedCookie.user_agent"></p>
                                        </div>
                                    </div>

                                    <!-- Statut et préférences -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-700 border-b pb-2">⚙️ Consentement</h4>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Statut:</span>
                                            <span :class="selectedCookie.status === 'accepted' ? 'bg-green-100 text-green-800' : selectedCookie.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'" 
                                                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2">
                                                <span x-text="selectedCookie.status === 'accepted' ? 'Accepté' : selectedCookie.status === 'rejected' ? 'Rejeté' : selectedCookie.status === 'partial' ? 'Partiel' : 'En attente'"></span>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Types acceptés:</span>
                                            <p class="text-sm text-gray-900" x-text="formatCookieTypes(selectedCookie)"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Version:</span>
                                            <p class="text-sm text-gray-900" x-text="selectedCookie.consent_version"></p>
                                        </div>
                                        <div x-show="selectedCookie.page_url">
                                            <span class="text-sm font-medium text-gray-500">Page de consentement:</span>
                                            <p class="text-sm text-gray-900 break-all" x-text="selectedCookie.page_url"></p>
                                        </div>
                                    </div>

                                    <!-- Dates -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-700 border-b pb-2">📅 Historique</h4>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Créé le:</span>
                                            <p class="text-sm text-gray-900" x-text="new Date(selectedCookie.created_at).toLocaleString('fr-FR')"></p>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Dernière mise à jour:</span>
                                            <p class="text-sm text-gray-900" x-text="new Date(selectedCookie.updated_at).toLocaleString('fr-FR')"></p>
                                        </div>
                                        <div x-show="selectedCookie.accepted_at">
                                            <span class="text-sm font-medium text-gray-500">Accepté le:</span>
                                            <p class="text-sm text-gray-900" x-text="selectedCookie.accepted_at ? new Date(selectedCookie.accepted_at).toLocaleString('fr-FR') : 'N/A'"></p>
                                        </div>
                                        <div x-show="selectedCookie.rejected_at">
                                            <span class="text-sm font-medium text-gray-500">Rejeté le:</span>
                                            <p class="text-sm text-gray-900" x-text="selectedCookie.rejected_at ? new Date(selectedCookie.rejected_at).toLocaleString('fr-FR') : 'N/A'"></p>
                                        </div>
                                    </div>

                                    <!-- Détails techniques -->
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-700 border-b pb-2">🔧 Détails techniques</h4>
                                        <div x-show="selectedCookie.browser_info">
                                            <span class="text-sm font-medium text-gray-500">Navigateur:</span>
                                            <div class="text-sm text-gray-900 mt-1">
                                                <div x-show="selectedCookie.browser_info?.platform">
                                                    <span class="font-medium">Plateforme:</span> <span x-text="selectedCookie.browser_info?.platform"></span>
                                                </div>
                                                <div x-show="selectedCookie.browser_info?.screen_resolution">
                                                    <span class="font-medium">Résolution:</span> <span x-text="selectedCookie.browser_info?.screen_resolution"></span>
                                                </div>
                                                <div x-show="selectedCookie.browser_info?.mobile !== undefined">
                                                    <span class="font-medium">Mobile:</span> <span x-text="selectedCookie.browser_info?.mobile ? 'Oui' : 'Non'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div x-show="selectedCookie.preferences_details">
                                            <span class="text-sm font-medium text-gray-500">Préférences détaillées:</span>
                                            <div class="text-sm text-gray-900 mt-1">
                                                <div x-show="selectedCookie.preferences_details?.language">
                                                    <span class="font-medium">Langue:</span> <span x-text="selectedCookie.preferences_details?.language"></span>
                                                </div>
                                                <div x-show="selectedCookie.preferences_details?.theme">
                                                    <span class="font-medium">Thème:</span> <span x-text="selectedCookie.preferences_details?.theme"></span>
                                                </div>
                                                <div x-show="selectedCookie.preferences_details?.notifications !== undefined">
                                                    <span class="font-medium">Notifications:</span> <span x-text="selectedCookie.preferences_details?.notifications ? 'Activées' : 'Désactivées'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="closeModal()" 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cookieManagement', () => ({
        stats: {
            total_consents: 0,
            accepted_consents: 0,
            rejected_consents: 0,
            pending_consents: 0,
            necessary_count: 0,
            analytics_count: 0,
            marketing_count: 0,
            preferences_count: 0,
            social_media_count: 0,
            daily_consents: []
        },
        cookies: [],
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        totalItems: 0,
        loading: false,
        showModal: false,
        selectedCookie: null,

        async init() {
            await this.loadData();
        },

        async loadData() {
            this.loading = true;
            
            try {
                // Charger les statistiques
                const statsResponse = await fetch('/admin/api/cookies/stats', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (statsResponse.ok) {
                    const statsData = await statsResponse.json();
                    this.stats = { ...this.stats, ...statsData.data };
                }

                // Charger la liste des cookies
                const cookiesResponse = await fetch(`/admin/api/cookies/list?page=${this.currentPage}&per_page=${this.perPage}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (cookiesResponse.ok) {
                    const cookiesData = await cookiesResponse.json();
                    this.cookies = cookiesData.data.data || [];
                    this.totalPages = cookiesData.data.last_page || 1;
                    this.totalItems = cookiesData.data.total || 0;
                }
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
            }
            this.loading = false;
        },

        getPercentage(count) {
            return this.stats.total_consents > 0 ? Math.round((count / this.stats.total_consents) * 100) : 0;
        },

        getStatusClass(status) {
            switch (status) {
                case 'accepted': return 'bg-green-100 text-green-800';
                case 'rejected': return 'bg-red-100 text-red-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        },

        getStatusText(status) {
            switch (status) {
                case 'accepted': return 'Accepté';
                case 'rejected': return 'Rejeté';
                case 'pending': return 'En attente';
                default: return 'Inconnu';
            }
        },

        getUserInitials(user) {
            if (!user || !user.email) return 'V';
            return user.email.substring(0, 2).toUpperCase();
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString('fr-FR');
        },

        formatDateTime(date) {
            return new Date(date).toLocaleString('fr-FR');
        },

        async refreshData() {
            await this.loadData();
        },

        async nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                await this.loadData();
            }
        },

        async previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                await this.loadData();
            }
        },

        async deleteConsent(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce consentement ?')) {
                try {
                    const response = await fetch(`/admin/api/cookies/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    });
                    
                    if (response.ok) {
                        await this.loadData();
                        if (window.FarmShop?.notification) {
                            window.FarmShop.notification.show('Consentement supprimé avec succès', 'success');
                        }
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression:', error);
                }
            }
        },

        async exportData() {
            try {
                const response = await fetch('/admin/api/cookies/export', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `cookies-export-${new Date().toISOString().split('T')[0]}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }
            } catch (error) {
                console.error('Erreur lors de l\'export:', error);
            }
        },

        viewDetails(cookie) {
            this.selectedCookie = cookie;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.selectedCookie = null;
        },

        formatCookieTypes(cookie) {
            const types = [];
            if (cookie.necessary) types.push('🔧 Nécessaires');
            if (cookie.analytics) types.push('📊 Analytiques');
            if (cookie.marketing) types.push('🎯 Marketing');
            if (cookie.preferences) types.push('⚙️ Préférences');
            if (cookie.social_media) types.push('📱 Réseaux sociaux');
            return types.length > 0 ? types.join(', ') : 'Aucun';
        }
    }))
});
</script>
@endsection
