<?php

return [
    // Page titles
    'page_title' => 'Gestion des newsletters - Dashboard Admin',
    'section_title' => 'Gestion des newsletters',
    
    // Header
    'title' => 'Gestion des newsletters',
    'description' => 'Interface de gestion des campagnes email marketing',
    'total_newsletters' => 'Newsletters totales',
    
    // Statistics
    'statistics' => [
        'draft' => 'Brouillons',
        'scheduled' => 'Programmées', 
        'sent' => 'Envoyées',
        'subscribers' => 'Abonnés',
    ],
    
    // Filters section
    'filter_title' => 'Filtrer et rechercher',
    'new_newsletter' => 'Nouvelle Newsletter',
    'status_label' => 'Statut',
    'all_statuses' => 'Tous les statuts',
    'search_label' => 'Recherche',
    'search_placeholder' => 'Rechercher par titre, sujet...',
    'filter_button' => 'Filtrer',
    'reset_button' => 'Reset',
    
    // Status labels
    'status' => [
        'draft' => 'Brouillon',
        'scheduled' => 'Programmée',
        'sent' => 'Envoyée',
    ],
    
    // Newsletter list
    'results_count' => 'résultats',
    'sent_at' => 'Envoyée',
    
    // Action buttons
    'actions' => [
        'view' => 'Voir',
        'edit' => 'Modifier',
        'locked' => 'Verrouillée',
        'duplicate' => 'Dupliquer',
        'send' => 'Envoyer',
        'cancel' => 'Annuler',
        'resend' => 'Renvoyer',
        'delete' => 'Supprimer',
        'filter' => 'Filtrer',
        'reset' => 'Réinitialiser',
        'subscribe' => 'Abonner',
        'unsubscribe' => 'Désabonner',
    ],
    
    // Action tooltips
    'tooltips' => [
        'view_details' => 'Voir les détails',
        'edit_newsletter' => 'Modifier la newsletter',
        'locked_sent' => 'Newsletter déjà envoyée - modification impossible',
        'duplicate_newsletter' => 'Dupliquer cette newsletter',
        'send_now' => 'Envoyer maintenant',
        'send_newsletter' => 'Envoyer la newsletter maintenant',
        'scheduled_info' => 'Newsletter programmée pour plus tard',
        'already_sent' => 'Newsletter déjà envoyée',
        'cancel_scheduling' => 'Annuler la programmation',
        'resend_newsletter' => 'Renvoyer cette newsletter à tous les abonnés',
        'delete_permanently' => 'Supprimer définitivement',
    ],
    
    // Confirmation messages
    'confirmations' => [
        'send_now' => 'Êtes-vous sûr de vouloir envoyer cette newsletter maintenant ?',
        'cancel_scheduling' => 'Annuler la programmation de cette newsletter ?',
        'resend_newsletter' => 'Êtes-vous sûr de vouloir renvoyer cette newsletter à tous les abonnés actuels ?',
        'delete_newsletter' => 'Êtes-vous sûr de vouloir supprimer définitivement cette newsletter ? Cette action est irréversible.',
    ],
    
    // Empty state
    'empty_state' => [
        'no_newsletters_found' => 'Aucune newsletter trouvée',
        'no_match_criteria' => 'Aucune newsletter ne correspond à vos critères.',
        'create_first_newsletter' => 'Créez votre première newsletter pour commencer.',
        'create_first_button' => 'Créer ma première newsletter',
    ],
    
    // Sections
    'sections' => [
        'subscriber_management' => 'Section Gestion des Abonnés',
        'subscriber_header' => 'En-tête de la section abonnés',
        'subscriber_filters' => 'Filtres pour les abonnés',
        'keep_newsletter_params' => 'Garder les paramètres de newsletter',
        'bulk_actions_subscribers' => 'Actions en lot pour les abonnés',
        'subscriber_list' => 'Liste des abonnés',
        'pagination' => 'Pagination',
        'subscriber_pagination' => 'Pagination des abonnés',
        'additional_actions_by_status' => 'Actions supplémentaires selon le statut',
        'send_now_button' => 'Bouton Envoyer maintenant',
        'cancel_scheduling_button' => 'Bouton Annuler programmation',
        'resend_button' => 'Bouton Renvoyer',
        'delete_button' => 'Bouton Supprimer',
        'subscriber_management_js' => 'Gestion des abonnés JavaScript',
    ],
    
    // Subscriber management
    'subscriber_management' => [
        'title' => 'Gestion des Abonnés',
        'description' => 'Gérez vos abonnés à la newsletter : abonner, désabonner, filtrer',
        'active_subscribers' => 'Abonnés actifs',
        'search_subscriber' => 'Rechercher un abonné',
        'search_placeholder' => 'Nom ou email...',
        'subscription_status' => 'Statut d\'abonnement',
        'all_users' => 'Tous les utilisateurs',
        'subscribed_only' => 'Abonnés uniquement',
        'unsubscribed_only' => 'Non abonnés uniquement',
        'users_selected' => 'utilisateur(s) sélectionné(s)',
        
        // Table headers
        'table' => [
            'user' => 'Utilisateur',
            'email' => 'Email',
            'status' => 'Statut',
            'registration_date' => 'Date d\'inscription',
            'actions' => 'Actions',
            'registered_on' => 'Inscrit le',
        ],
        
        // Status labels
        'status' => [
            'subscribed' => 'Abonné',
            'unsubscribed' => 'Non abonné',
        ],
        
        // Empty state
        'empty_state' => [
            'no_users_found' => 'Aucun utilisateur trouvé',
            'no_match_search' => 'Aucun utilisateur ne correspond à vos critères de recherche.',
            'no_users_registered' => 'Aucun utilisateur enregistré dans le système.',
        ],
    ],
    
    // JavaScript messages
    'javascript' => [
        'error' => 'Erreur',
        'generic_error' => 'Une erreur s\'est produite',
        'select_at_least_one' => 'Veuillez sélectionner au moins un utilisateur',
        'confirm_subscribe' => 'Abonner :count utilisateur(s) à la newsletter ?',
        'confirm_unsubscribe' => 'Désabonner :count utilisateur(s) de la newsletter ?',
        'confirm_delete' => 'Supprimer définitivement :count utilisateur(s) ? Cette action est irréversible.',
    ],
];
