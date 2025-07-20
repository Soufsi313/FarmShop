@extends('layouts.admin')

@section('title', 'Gestion des Signalements')
@section('page-title', 'Gestion des Signalements')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des Signalements</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Examinez et traitez les signalements de commentaires
                    </p>
                </div>
                <div class="mt-4 flex space-x-3 sm:mt-0">
                    <button onclick="refreshData()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualiser
                    </button>
                    <button onclick="openFilterModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"/>
                        </svg>
                        Filtres
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                            <dd class="text-2xl font-semibold text-gray-900" id="totalReports">0</dd>
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
                            <dd class="text-2xl font-semibold text-gray-900" id="pendingReports">0</dd>
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
                            <dd class="text-2xl font-semibold text-gray-900" id="resolvedReports">0</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Priorité haute</dt>
                            <dd class="text-2xl font-semibold text-gray-900" id="highPriorityReports">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="searchInput" class="block text-sm font-medium text-gray-700">Rechercher</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" id="searchInput" class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md" placeholder="Rechercher dans les signalements...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select id="statusFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="resolved">Résolu</option>
                        <option value="dismissed">Rejeté</option>
                    </select>
                </div>
                <div>
                    <label for="reasonFilter" class="block text-sm font-medium text-gray-700">Raison</label>
                    <select id="reasonFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Toutes les raisons</option>
                        <option value="inappropriate">Inapproprié</option>
                        <option value="spam">Spam</option>
                        <option value="harassment">Harcèlement</option>
                        <option value="hate_speech">Discours haineux</option>
                        <option value="violence">Violence</option>
                        <option value="misinformation">Désinformation</option>
                        <option value="other">Autre</option>
                    </select>
                </div>
                <div>
                    <label for="priorityFilter" class="block text-sm font-medium text-gray-700">Priorité</label>
                    <select id="priorityFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Toutes les priorités</option>
                        <option value="high">Haute</option>
                        <option value="medium">Moyenne</option>
                        <option value="low">Basse</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des signalements -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Signalements</h3>
        </div>
        <div id="reportsContainer">
            <!-- Contenu chargé via JavaScript -->
        </div>
    </div>
</div>

<!-- Modal de détail du signalement -->
<div id="reportModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeReportModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-start">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Détail du Signalement
                            </h3>
                            <button type="button" onclick="closeReportModal()" class="bg-white rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div id="reportModalBody" class="mt-4">
                            <!-- Contenu chargé via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeReportModal()" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de traitement du signalement -->
<div id="processModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="process-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeProcessModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="processForm" onsubmit="processReport(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="process-modal-title">
                                Traiter le Signalement
                            </h3>
                            <div class="mt-6">
                                <input type="hidden" id="processReportId">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-base font-medium text-gray-900">Action à effectuer :</label>
                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-center">
                                                <input id="resolveAction" name="action" type="radio" value="resolve" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                                                <label for="resolveAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Résoudre (valider le signalement)
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="dismissAction" name="action" type="radio" value="dismiss" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                                                <label for="dismissAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Rejeter (signalement non fondé)
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="commentActionGroup" class="hidden">
                                        <label class="text-base font-medium text-gray-900">Action sur le commentaire :</label>
                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-center">
                                                <input id="deleteCommentAction" name="comment_action" type="radio" value="delete" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                                                <label for="deleteCommentAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    Supprimer le commentaire
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="rejectCommentAction" name="comment_action" type="radio" value="reject" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                                                <label for="rejectCommentAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    Rejeter le commentaire
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="keepCommentAction" name="comment_action" type="radio" value="keep" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                                                <label for="keepCommentAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    Garder le commentaire
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="adminNotes" class="block text-sm font-medium text-gray-700">Notes administratives :</label>
                                        <div class="mt-1">
                                            <textarea id="adminNotes" name="admin_notes" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ajoutez vos notes sur ce traitement..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Traiter
                    </button>
                    <button type="button" onclick="closeProcessModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-4 max-w-sm">
        <div class="flex items-center">
            <div id="toastIcon" class="flex-shrink-0">
                <!-- Icon will be set dynamically -->
            </div>
            <div class="ml-3">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="hideToast()" class="inline-flex text-gray-400 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    loadReports();
    initializeEventListeners();
});

function initializeEventListeners() {
    // Filtres et recherche
    document.getElementById('searchInput').addEventListener('input', debounce(function() {
        loadReports();
    }, 500));
    
    document.getElementById('statusFilter').addEventListener('change', loadReports);
    document.getElementById('reasonFilter').addEventListener('change', loadReports);
    document.getElementById('priorityFilter').addEventListener('change', loadReports);
    
    // Actions modales
    document.querySelectorAll('input[name="action"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const commentActionGroup = document.getElementById('commentActionGroup');
            if (this.value === 'resolve') {
                commentActionGroup.classList.remove('hidden');
            } else {
                commentActionGroup.classList.add('hidden');
            }
        });
    });
}

function loadReports(page = 1) {
    const params = new URLSearchParams({
        page: page,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        reason: document.getElementById('reasonFilter').value,
        priority: document.getElementById('priorityFilter').value
    });
    
    // Afficher le loader
    document.getElementById('reportsContainer').innerHTML = `
        <div class="flex items-center justify-center h-64">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Chargement des signalements...</p>
            </div>
        </div>
    `;
    
    fetch(`/admin/blog-comment-reports?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        updateReportsDisplay(data.data);
        updateStats(data);
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors du chargement des signalements', 'error');
    });
}

function updateReportsDisplay(reports) {
    const container = document.getElementById('reportsContainer');
    
    if (reports.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun signalement</h3>
                <p class="mt-1 text-sm text-gray-500">Aucun signalement ne correspond à vos critères de recherche.</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Signalé par</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    reports.forEach(report => {
        html += createReportRow(report);
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = html;
}

function createReportRow(report) {
    const priorityBadge = getPriorityBadge(report.priority);
    const statusBadge = getStatusBadge(report.status);
    const reasonBadge = getReasonBadge(report.reason);
    
    const priorityClass = report.priority === 'high' ? 'bg-red-50' : report.priority === 'medium' ? 'bg-yellow-50' : '';
    
    return `
        <tr class="hover:bg-gray-50 ${priorityClass}">
            <td class="px-6 py-4">
                <div class="max-w-xs">
                    <p class="text-sm text-gray-900 line-clamp-2">${report.comment?.content?.substring(0, 100) || 'Commentaire supprimé'}${(report.comment?.content?.length || 0) > 100 ? '...' : ''}</p>
                    <p class="text-xs text-gray-500 mt-1">Par: ${report.comment?.user?.name || report.comment?.guest_name || 'Utilisateur supprimé'}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs truncate">
                    <a href="/blog/${report.comment?.post?.slug || '#'}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        ${report.comment?.post?.title || 'Article supprimé'}
                    </a>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${report.reporter?.name || 'Utilisateur supprimé'}</div>
                <div class="text-xs text-gray-500">${report.reporter?.email || 'N/A'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${reasonBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">${priorityBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(report.created_at).toLocaleDateString('fr-FR')}<br>
                ${new Date(report.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="showReportDetail(${report.id})" class="text-blue-600 hover:text-blue-900" title="Voir détail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    ${report.status === 'pending' ? `
                        <button onclick="showProcessModal(${report.id})" class="text-green-600 hover:text-green-900" title="Traiter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>
                    ` : ''}
                    <button onclick="viewComment(${report.comment?.id || 0})" class="text-purple-600 hover:text-purple-900" title="Voir commentaire">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

function getPriorityBadge(priority) {
    const badges = {
        'high': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Haute</span>',
        'medium': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Moyenne</span>',
        'low': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Basse</span>'
    };
    return badges[priority] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inconnue</span>';
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>',
        'resolved': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Résolu</span>',
        'dismissed': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Rejeté</span>'
    };
    return badges[status] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inconnu</span>';
}

function getReasonBadge(reason) {
    const badges = {
        'inappropriate': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inapproprié</span>',
        'spam': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Spam</span>',
        'harassment': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Harcèlement</span>',
        'hate_speech': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Discours haineux</span>',
        'violence': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Violence</span>',
        'misinformation': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Désinformation</span>',
        'other': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Autre</span>'
    };
    return badges[reason] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inconnue</span>';
}

function updateStats(data) {
    // Cette fonction sera appelée avec les bonnes données du serveur
}

function showReportDetail(reportId) {
    fetch(`/api/admin/blog/comment-reports/${reportId}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const report = data.data;
        document.getElementById('reportModalBody').innerHTML = createReportDetailHTML(report);
        document.getElementById('reportModal').classList.remove('hidden');
    })
    .catch(error => {
        showToast('Erreur lors du chargement du détail', 'error');
    });
}

function createReportDetailHTML(report) {
    return `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Informations du signalement</h4>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Raison :</dt>
                        <dd class="text-sm text-gray-900">${getReasonBadge(report.reason)}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Priorité :</dt>
                        <dd class="text-sm text-gray-900">${getPriorityBadge(report.priority)}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Statut :</dt>
                        <dd class="text-sm text-gray-900">${getStatusBadge(report.status)}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date :</dt>
                        <dd class="text-sm text-gray-900">${new Date(report.created_at).toLocaleString('fr-FR')}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Signalé par</h4>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom :</dt>
                        <dd class="text-sm text-gray-900">${report.reporter?.name || 'Utilisateur supprimé'}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email :</dt>
                        <dd class="text-sm text-gray-900">${report.reporter?.email || 'N/A'}</dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Commentaire signalé</h4>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-900 whitespace-pre-wrap">${report.comment?.content || 'Commentaire supprimé'}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Par: ${report.comment?.user?.name || report.comment?.guest_name || 'Utilisateur supprimé'} • 
                    Article: ${report.comment?.post?.title || 'Article supprimé'}
                </p>
            </div>
        </div>
        ${report.description ? `
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Description du signalement</h4>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">${report.description}</p>
                </div>
            </div>
        ` : ''}
        ${report.admin_notes ? `
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Notes administratives</h4>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-900 whitespace-pre-wrap">${report.admin_notes}</p>
                </div>
            </div>
        ` : ''}
    `;
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
}

function showProcessModal(reportId) {
    document.getElementById('processReportId').value = reportId;
    document.getElementById('processModal').classList.remove('hidden');
}

function closeProcessModal() {
    document.getElementById('processModal').classList.add('hidden');
    document.getElementById('processForm').reset();
    document.getElementById('commentActionGroup').classList.add('hidden');
}

function processReport(event) {
    event.preventDefault();
    
    const reportId = document.getElementById('processReportId').value;
    const action = document.querySelector('input[name="action"]:checked')?.value;
    const commentAction = document.querySelector('input[name="comment_action"]:checked')?.value;
    const adminNotes = document.getElementById('adminNotes').value;
    
    if (!action) {
        showToast('Veuillez sélectionner une action', 'error');
        return;
    }
    
    const data = {
        action: action,
        admin_notes: adminNotes
    };
    
    if (action === 'resolve' && commentAction) {
        data.comment_action = commentAction;
    }
    
    fetch(`/api/admin/blog/comment-reports/${reportId}/process`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeProcessModal();
            showToast(data.message || 'Signalement traité avec succès', 'success');
            loadReports();
        } else {
            showToast(data.message || 'Erreur lors du traitement', 'error');
        }
    })
    .catch(error => {
        showToast('Erreur lors du traitement', 'error');
    });
}

function viewComment(commentId) {
    if (commentId) {
        window.location.href = `/admin/blog-comments?comment_id=${commentId}`;
    } else {
        showToast('Commentaire non disponible', 'error');
    }
}

function refreshData() {
    loadReports();
    showToast('Données actualisées', 'success');
}

function openFilterModal() {
    // Implémenter une modal de filtres avancés si nécessaire
    showToast('Fonctionnalité en développement', 'info');
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    // Icônes selon le type
    const icons = {
        'success': '<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
        'error': '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
        'info': '<svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
    };
    
    toastIcon.innerHTML = icons[type] || icons['info'];
    
    toast.classList.remove('hidden');
    
    // Auto-hide après 5 secondes
    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    document.getElementById('toast').classList.add('hidden');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endsection
