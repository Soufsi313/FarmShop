@extends('layouts.admin')

@section('title', __('blog.comments.page_title'))
@section('page-title', __('blog.comments.section_title'))

@section('content')
<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-7 h-7 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        {{ __('blog.comments.title') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">{{ __('blog.comments.description') }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <button type="button" onclick="openFilterModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                        </svg>
                        {{ __('blog.comments.filters_button') }}
                    </button>
                    <button type="button" onclick="refreshData()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('blog.comments.refresh_button') }}
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
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">{{ __('blog.comments.total_comments') }}</div>
                        <div class="text-2xl font-bold text-gray-900" id="totalComments">{{ $stats['total_comments'] ?? 0 }}</div>
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
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">{{ __('blog.comments.pending_comments') }}</div>
                        <div class="text-2xl font-bold text-gray-900" id="pendingComments">{{ $stats['pending_comments'] ?? 0 }}</div>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">{{ __('blog.comments.approved_comments') }}</div>
                        <div class="text-2xl font-bold text-gray-900" id="approvedComments">{{ $stats['approved_comments'] ?? 0 }}</div>
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
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">{{ __('blog.comments.reported_comments') }}</div>
                        <div class="text-2xl font-bold text-gray-900" id="reportedComments">{{ $stats['total_reports'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Section de filtrage et recherche -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('blog.comments.advanced_search_title') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('blog.comments.advanced_search_description') }}</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="lg:col-span-2">
                    <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.comments.search_label') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ __('blog.comments.search_placeholder') }}">
                    </div>
                </div>

                <!-- Filtre par statut -->
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.comments.status') }}</label>
                    <select id="statusFilter" name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('blog.comments.all_statuses') }}</option>
                        <option value="pending">{{ __('blog.comments.status_pending') }}</option>
                        <option value="approved">{{ __('blog.comments.status_approved') }}</option>
                        <option value="rejected">{{ __('blog.comments.status_rejected') }}</option>
                        <option value="spam">{{ __('blog.comments.status_spam') }}</option>
                    </select>
                </div>

                <!-- Filtre signalements -->
                <div>
                    <label for="reportedFilter" class="block text-sm font-medium text-gray-700 mb-2">{{ __('blog.comments.reports_label') }}</label>
                    <select id="reportedFilter" name="reported" class="block w-full px-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('blog.comments.all_reports') }}</option>
                        <option value="reported">{{ __('blog.comments.with_reports') }}</option>
                        <option value="not_reported">{{ __('blog.comments.without_reports') }}</option>
                    </select>
                </div>
            </div>

            <!-- Actions en lot -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-4">{{ __('blog.comments.quick_actions') }}</h4>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="selectAll" class="ml-2 text-sm text-gray-900">{{ __('blog.comments.select_all') }}</label>
                        </div>
                        <span id="selectedCount" class="text-sm text-gray-500">0 {{ __('blog.comments.selected_count') }}</span>
                    </div>
                    <div class="mt-4 sm:mt-0 flex space-x-2">
                        <button type="button" onclick="bulkAction('approve')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" id="bulkApprove" disabled>
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('blog.comments.bulk_approve') }}
                        </button>
                        <button type="button" onclick="bulkAction('reject')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" id="bulkReject" disabled>
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('blog.comments.bulk_reject') }}
                        </button>
                        <button type="button" onclick="bulkAction('delete')" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50" id="bulkDelete" disabled>
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('blog.comments.bulk_delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des commentaires -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ __('blog.comments.comments_list') }}</h3>
        </div>
        <div class="overflow-hidden">
            <div id="commentsContainer" class="min-h-[400px]">
                <!-- Le contenu sera chargé via AJAX -->
                <div class="flex items-center justify-center h-64">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">{{ __('blog.comments.loading') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                        <option value="true">Signalés seulement</option>
                                        <option value="false">Non signalés</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="sortFilter">
                                        <option value="recent">Plus récents</option>
                                        <option value="popular">Plus populaires</option>
                                        <option value="reports">Plus signalés</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des commentaires -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="commentsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('blog.comments.table_author') }}</th>
                                    <th>{{ __('blog.comments.table_article') }}</th>
                                    <th>{{ __('blog.comments.table_content') }}</th>
                                    <th>{{ __('blog.comments.table_status') }}</th>
                                    <th>{{ __('blog.comments.table_reports') }}</th>
                                    <th>{{ __('blog.comments.table_date') }}</th>
                                    <th>{{ __('blog.comments.table_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="commentsTableBody">
                                <!-- Contenu chargé via AJAX -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center" id="paginationContainer">
                        <!-- Pagination chargée via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de détail du commentaire -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détail du Commentaire</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="commentModalBody">
                <!-- Contenu chargé via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <div id="commentActions">
                    <!-- Actions chargées via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération -->
<div class="modal fade" id="moderateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modération du Commentaire</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="moderateForm">
                <div class="modal-body">
                    <input type="hidden" id="moderateCommentId">
                    <div class="form-group">
                        <label>Action à effectuer :</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" id="approveAction" value="approve">
                            <label class="form-check-label" for="approveAction">
                                <i class="fas fa-check text-success"></i> {{ __('blog.comments.action_approve') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" id="rejectAction" value="reject">
                            <label class="form-check-label" for="rejectAction">
                                <i class="fas fa-times text-danger"></i> {{ __('blog.comments.action_reject') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="action" id="spamAction" value="spam">
                            <label class="form-check-label" for="spamAction">
                                <i class="fas fa-ban text-warning"></i> {{ __('blog.comments.action_spam') }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="reasonGroup" style="display: none;">
                        <label for="reason">Raison (optionnelle) :</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Expliquez la raison de cette action..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    loadComments();
    
    // Recherche
    $('#searchButton, #statusFilter, #reportedFilter, #sortFilter').on('click change', function() {
        loadComments();
    });
    
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            loadComments();
        }
    });
    
    // Afficher/masquer le champ raison
    $('input[name="action"]').on('change', function() {
        if ($(this).val() === 'reject') {
            $('#reasonGroup').show();
<!-- Modal de détail du commentaire -->
<div id="commentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCommentModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-start">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Détail du Commentaire
                            </h3>
                            <button type="button" onclick="closeCommentModal()" class="bg-white rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div id="commentModalBody" class="mt-4">
                            <!-- Contenu chargé via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeCommentModal()" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Fermer
                </button>
                <div id="commentActions" class="flex space-x-2">
                    <!-- Actions chargées via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modération -->
<div id="moderateModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="moderate-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModerateModal()"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="moderateForm" onsubmit="moderateComment(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="moderate-modal-title">
                                Modération du Commentaire
                            </h3>
                            <div class="mt-6">
                                <input type="hidden" id="moderateCommentId">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-base font-medium text-gray-900">Action à effectuer :</label>
                                        <div class="mt-4 space-y-3">
                                            <div class="flex items-center">
                                                <input id="approveAction" name="action" type="radio" value="approve" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                                <label for="approveAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Approuver
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="rejectAction" name="action" type="radio" value="reject" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                                <label for="rejectAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Rejeter
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="spamAction" name="action" type="radio" value="spam" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                                <label for="spamAction" class="ml-3 block text-sm font-medium text-gray-700">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                                        </svg>
                                                        {{ __('blog.comments.action_spam') }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="reasonGroup" class="hidden">
                                        <label for="reason" class="block text-sm font-medium text-gray-700">Raison (optionnelle) :</label>
                                        <div class="mt-1">
                                            <textarea id="reason" name="reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Expliquez la raison de cette action..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Appliquer
                    </button>
                    <button type="button" onclick="closeModerateModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    loadComments();
    initializeEventListeners();
});

function initializeEventListeners() {
    // Filtres et recherche
    document.getElementById('searchInput').addEventListener('input', debounce(function() {
        loadComments();
    }, 500));
    
    document.getElementById('statusFilter').addEventListener('change', loadComments);
    document.getElementById('reportedFilter').addEventListener('change', loadComments);
    
    // Actions modales
    document.querySelectorAll('input[name="action"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const reasonGroup = document.getElementById('reasonGroup');
            if (this.value === 'reject' || this.value === 'spam') {
                reasonGroup.classList.remove('hidden');
            } else {
                reasonGroup.classList.add('hidden');
            }
        });
    });
    
    // Sélection multiple
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.comment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
        updateBulkButtons();
    });
}

function loadComments(page = 1) {
    const params = new URLSearchParams({
        page: page,
        search: document.getElementById('searchInput').value,
        status: document.getElementById('statusFilter').value,
        reported: document.getElementById('reportedFilter').value
    });
    
    // Afficher le loader
    document.getElementById('commentsContainer').innerHTML = `
        <div class="flex items-center justify-center h-64">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Chargement des commentaires...</p>
            </div>
        </div>
    `;
    
    fetch(`/admin/blog-comments?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        updateCommentsDisplay(data.data);
        updateStats(data);
    })
    .catch(error => {
        console.error('{{ __('blog.comments.js_error_loading') }}:', error);
        showToast('{{ __('blog.comments.js_error_loading') }}', 'error');
    });
}

function updateCommentsDisplay(comments) {
    const container = document.getElementById('commentsContainer');
    
    if (comments.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('blog.comments.no_comments') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('blog.comments.no_comments_message') }}</p>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('blog.comments.table_reports') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    comments.forEach(comment => {
        html += createCommentRow(comment);
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = html;
    
    // Réattacher les event listeners
    initializeTableEvents();
}

function createCommentRow(comment) {
    const statusBadge = getStatusBadge(comment.status);
    const reportsBadge = getReportsBadge(comment.reports_count || 0);
    const authorInfo = comment.user ? 
        `${comment.user.name}<br><span class="text-xs text-gray-500">${comment.user.email}</span>` :
        `${comment.guest_name || '{{ __('blog.comments.anonymous') }}'}<br><span class="text-xs text-gray-500">${comment.guest_email || '{{ __('blog.comments.not_available') }}'}</span>`;
    
    return `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" class="comment-checkbox focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" data-comment-id="${comment.id}">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${authorInfo}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs truncate">
                    <a href="/blog/${comment.post?.slug || '#'}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        ${comment.post?.title || 'Article supprimé'}
                    </a>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs">
                    <p class="line-clamp-2">${comment.content.substring(0, 100)}${comment.content.length > 100 ? '...' : ''}</p>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">${reportsBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(comment.created_at).toLocaleDateString('fr-FR')}<br>
                ${new Date(comment.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="showCommentDetail(${comment.id})" class="text-blue-600 hover:text-blue-900" title="Voir détail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button onclick="showModerateModal(${comment.id})" class="text-green-600 hover:text-green-900" title="Modérer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                    ${(comment.reports_count || 0) > 0 ? `
                        <button onclick="showReports(${comment.id})" class="text-yellow-600 hover:text-yellow-900" title="Voir signalements">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </button>
                    ` : ''}
                    <button onclick="deleteComment(${comment.id})" class="text-red-600 hover:text-red-900" title="Supprimer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>',
        'approved': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('blog.comments.status_approved_label') }}</span>',
        'rejected': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('blog.comments.status_rejected_label') }}</span>',
        'spam': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Spam</span>'
    };
    return badges[status] || '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inconnu</span>';
}

function getReportsBadge(count) {
    if (count > 0) {
        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">${count}</span>`;
    }
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">0</span>';
}

function updateStats(data) {
    // Cette fonction sera appelée avec les bonnes données du serveur
    // document.getElementById('totalComments').textContent = data.total || 0;
    // etc.
}

function initializeTableEvents() {
    // Event listeners pour les checkboxes
    document.querySelectorAll('.comment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateBulkButtons();
        });
    });
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.comment-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = `${selected} sélectionné(s)`;
}

function updateBulkButtons() {
    const hasSelected = document.querySelectorAll('.comment-checkbox:checked').length > 0;
    document.getElementById('bulkApprove').disabled = !hasSelected;
    document.getElementById('bulkReject').disabled = !hasSelected;
    document.getElementById('bulkDelete').disabled = !hasSelected;
}

function showCommentDetail(commentId) {
    fetch(`/api/admin/blog/comments/${commentId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const comment = data.data;
        document.getElementById('commentModalBody').innerHTML = createCommentDetailHTML(comment);
        document.getElementById('commentModal').classList.remove('hidden');
    })
    .catch(error => {
        showToast('{{ __('blog.comments.js_error_loading_detail') }}', 'error');
    });
}

function createCommentDetailHTML(comment) {
    return `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Informations de l'auteur</h4>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nom :</dt>
                        <dd class="text-sm text-gray-900">${comment.user ? comment.user.name : comment.guest_name || 'Anonyme'}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email :</dt>
                        <dd class="text-sm text-gray-900">${comment.user ? comment.user.email : comment.guest_email || 'N/A'}</dd>
                    </div>
                    ${comment.guest_website ? `
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Site web :</dt>
                            <dd class="text-sm text-gray-900">${comment.guest_website}</dd>
                        </div>
                    ` : ''}
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Adresse IP :</dt>
                        <dd class="text-sm text-gray-900">${comment.ip_address || 'N/A'}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Détails du commentaire</h4>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Article :</dt>
                        <dd class="text-sm text-gray-900">${comment.post?.title || 'Article supprimé'}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date :</dt>
                        <dd class="text-sm text-gray-900">${new Date(comment.created_at).toLocaleString('fr-FR')}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Statut :</dt>
                        <dd class="text-sm text-gray-900">${getStatusBadge(comment.status)}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Signalements :</dt>
                        <dd class="text-sm text-gray-900">${comment.reports_count || 0}</dd>
                    </div>
                </dl>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Contenu du commentaire</h4>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-900 whitespace-pre-wrap">${comment.content}</p>
            </div>
        </div>
    `;
}

function closeCommentModal() {
    document.getElementById('commentModal').classList.add('hidden');
}

function showModerateModal(commentId) {
    document.getElementById('moderateCommentId').value = commentId;
    document.getElementById('moderateModal').classList.remove('hidden');
}

function closeModerateModal() {
    document.getElementById('moderateModal').classList.add('hidden');
    document.getElementById('moderateForm').reset();
    document.getElementById('reasonGroup').classList.add('hidden');
}

function moderateComment(event) {
    event.preventDefault();
    
    const commentId = document.getElementById('moderateCommentId').value;
    const action = document.querySelector('input[name="action"]:checked')?.value;
    const reason = document.getElementById('reason').value;
    
    if (!action) {
        showToast('{{ __('blog.comments.js_select_action') }}', 'error');
        return;
    }
    
    fetch(`/api/admin/blog/comments/${commentId}/moderate`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModerateModal();
            showToast(data.message || '{{ __('blog.comments.js_action_success') }}', 'success');
            loadComments();
        } else {
            showToast(data.message || '{{ __('blog.comments.js_moderation_error') }}', 'error');
        }
    })
    .catch(error => {
        showToast('{{ __('blog.comments.js_moderation_error') }}', 'error');
    });
}

function deleteComment(commentId) {
    if (confirm('{{ __('blog.comments.js_confirm_delete') }}')) {
        fetch(`/api/admin/blog/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('{{ __('blog.comments.js_delete_success') }}', 'success');
                loadComments();
            } else {
                showToast(data.message || '{{ __('blog.comments.js_delete_error') }}', 'error');
            }
        })
        .catch(error => {
            showToast('{{ __('blog.comments.js_delete_error') }}', 'error');
        });
    }
}

function bulkAction(action) {
    const selectedCheckboxes = document.querySelectorAll('.comment-checkbox:checked');
    const commentIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.commentId);
    
    if (commentIds.length === 0) {
        showToast('{{ __('blog.comments.js_select_comments') }}', 'error');
        return;
    }
    
    const actionText = {
        'approve': 'approuver',
        'reject': 'rejeter',
        'delete': 'supprimer'
    };
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText[action]} ${commentIds.length} commentaire(s) ?`)) {
        fetch('/api/blog/comments/bulk-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                action: action,
                comment_ids: commentIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || '{{ __('blog.comments.js_bulk_action_success') }}', 'success');
                loadComments();
                document.getElementById('selectAll').checked = false;
                updateSelectedCount();
                updateBulkButtons();
            } else {
                showToast(data.message || '{{ __('blog.comments.js_bulk_action_error') }}', 'error');
            }
        })
        .catch(error => {
            showToast('{{ __('blog.comments.js_bulk_action_error') }}', 'error');
        });
    }
}

function showReports(commentId) {
    window.location.href = `/admin/blog-comment-reports?comment_id=${commentId}`;
}

function refreshData() {
    loadComments();
    showToast('{{ __('blog.comments.js_data_refreshed') }}', 'success');
}

function openFilterModal() {
    // Implémenter une modal de filtres avancés si nécessaire
    showToast('{{ __('blog.comments.js_feature_development') }}', 'info');
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
