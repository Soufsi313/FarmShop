<?php

return [
    // Page title and headers
    'page_title' => 'Messages reçus',
    'received_messages' => 'Messages reçus',
    
    // Statistics
    'statistics' => [
        'total' => 'Total',
        'unread' => 'Non lus',
        'read' => 'Lus',
        'important' => 'Importants',
    ],
    
    // Filters
    'filters' => [
        'author' => 'Auteur',
        'author_placeholder' => 'Nom ou email...',
        'reason' => 'Motif',
        'all_reasons' => 'Tous les motifs',
        'priority' => 'Priorité',
        'all_priorities' => 'Toutes priorités',
        'status' => 'Statut',
        'all_statuses' => 'Tous statuts',
        'date_from' => 'Date de début',
        'date_to' => 'Date de fin',
        'search' => 'Recherche',
        'search_placeholder' => 'Contenu du message...',
        'per_page' => 'Par page',
        'filter_button' => 'Filtrer',
        'reset_button' => 'Réinitialiser',
    ],
    
    // User types
    'user_types' => [
        'registered' => 'Inscrit',
        'visitor' => 'Visiteur',
        'migrated' => 'Migré',
        'unknown_user' => 'Utilisateur inconnu',
        'anonymous' => 'Anonyme',
    ],
    
    // Status labels
    'status_labels' => [
        'important' => 'Important',
    ],
    
    // Reasons
    'reasons' => [
        'question' => 'Question',
        'support' => 'Support',
        'commande' => 'Commande',
        'autre' => 'Autre',
    ],
    
    // Priorities
    'priorities' => [
        'urgent' => 'Urgent',
        'high' => 'Haute',
        'normal' => 'Normale',
        'low' => 'Basse',
    ],
    
    // Status
    'statuses' => [
        'new' => 'Nouveau',
        'read' => 'Lu',
        'responded' => 'Répondu',
        'archived' => 'Archivé',
    ],
    
    // Bulk actions
    'bulk_actions' => [
        'selected_count' => 'message(s) sélectionné(s)',
        'mark_as_read' => 'Marquer comme lu',
        'mark_as_unread' => 'Marquer comme non lu',
        'mark_as_important' => 'Marquer comme important',
        'archive' => 'Archiver',
        'delete' => 'Supprimer',
    ],
    
    // Table headers
    'table' => [
        'author' => 'Auteur',
        'subject' => 'Sujet',
        'reason' => 'Motif',
        'priority' => 'Priorité',
        'status' => 'Statut',
        'date' => 'Date',
        'actions' => 'Actions',
    ],
    
    // Actions
    'actions' => [
        'view' => 'Voir',
        'reply' => 'Répondre',
        'mark_read' => 'Marquer lu',
        'mark_unread' => 'Marquer non lu',
        'mark_important' => 'Important',
        'archive' => 'Archiver',
        'delete' => 'Supprimer',
    ],
    
    // Empty state
    'empty_state' => [
        'no_messages' => 'Aucun message trouvé',
        'no_match_filters' => 'Aucun message ne correspond aux filtres sélectionnés.',
        'no_messages_yet' => 'Aucun message reçu pour le moment.',
    ],
    
    // Confirmations
    'confirmations' => [
        'delete_message' => 'Êtes-vous sûr de vouloir supprimer ce message ?',
        'archive_message' => 'Êtes-vous sûr de vouloir archiver ce message ?',
        'bulk_delete' => 'Êtes-vous sûr de vouloir supprimer :count message(s) ?',
        'bulk_archive' => 'Êtes-vous sûr de vouloir archiver :count message(s) ?',
    ],
    
    // JavaScript messages
    'javascript' => [
        'error' => 'Erreur',
        'success' => 'Succès',
        'generic_error' => 'Une erreur s\'est produite',
        'select_at_least_one' => 'Veuillez sélectionner au moins un message',
        'action_completed' => 'Action effectuée avec succès',
        'response_sent_success' => 'Réponse envoyée avec succès !',
        'response_send_error' => 'Erreur lors de l\'envoi de la réponse',
    ],
    
    // Pagination
    'pagination' => [
        'info' => 'Informations sur la pagination',
        'showing' => 'Affichage de',
        'to' => 'à',
        'of' => 'sur',
        'results' => 'résultats',
    ],
    
    // Response modal
    'response_modal' => [
        'title' => 'Répondre au message',
        'your_response' => 'Votre réponse',
        'response_placeholder' => 'Rédigez votre réponse...',
        'cancel' => 'Annuler',
        'send_response' => 'Envoyer la réponse',
    ],
];
