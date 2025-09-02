<?php

return [
    'title' => 'Gestion des utilisateurs - Dashboard Admin',
    'page_title' => 'Gestion des utilisateurs',
    'manage_all_users' => 'Gérez tous les utilisateurs',
    'advanced_interface' => 'Interface avancée de gestion des comptes utilisateurs avec recherche et filtres',
    'total_users' => 'Utilisateurs totaux',
    
    // Messages
    'success' => 'Succès !',
    'error' => 'Erreur !',
    
    // Statistiques
    'stats' => [
        'users' => 'Utilisateurs',
        'administrators' => 'Administrateurs',
        'deleted' => 'Supprimés',
        'new_30_days' => 'Nouveaux (30j)',
        'active_7_days' => 'Actifs (7j)',
    ],
    
    // Recherche et filtres
    'search' => [
        'title' => 'Recherche et filtres avancés',
        'general_search' => 'Recherche générale',
        'placeholder' => 'Nom, username, email...',
        'filter_by_role' => 'Filtre par rôle',
        'all_roles' => 'Tous les rôles',
        'account_status' => 'Statut des comptes',
        'active_only' => '✅ Comptes actifs uniquement',
        'deleted_only' => '🗑️ Comptes supprimés uniquement',
        'all_accounts' => '📋 Tous les comptes',
        'sort_by' => 'Trier par',
        'order' => 'Ordre',
        'reset' => 'Réinitialiser',
        'apply_filters' => 'Appliquer les filtres',
    ],
    
    // Options de tri
    'sort_options' => [
        'created_at' => '📅 Date d\'inscription',
        'name' => '🔤 Nom',
        'username' => '👤 Username',
        'email' => '📧 Email',
        'role' => '⚡ Rôle',
        'updated_at' => '🔄 Dernière modification',
        'deleted_at' => '🗑️ Date de suppression',
    ],
    
    // Ordre de tri
    'order_options' => [
        'desc' => '⬇️ Décroissant',
        'asc' => '⬆️ Croissant',
    ],
    
    // Tableau
    'table' => [
        'user' => 'Utilisateur',
        'email' => 'Email',
        'role' => 'Rôle',
        'status' => 'Statut',
        'newsletter' => 'Newsletter',
        'registration' => 'Inscription',
        'actions' => 'Actions',
    ],
    
    // Statuts
    'status' => [
        'deleted' => '🗑️ Supprimé',
        'active' => '✅ Actif',
        'subscribed' => '📧 Abonné',
        'not_subscribed' => '❌ Non abonné',
    ],
    
    // Actions
    'actions' => [
        'restore' => 'Restaurer',
        'restore_confirm' => 'Êtes-vous sûr de vouloir restaurer cet utilisateur ?',
        'view_details' => 'Voir les détails',
        'edit_user' => 'Modifier l\'utilisateur',
        'delete_user' => 'Supprimer l\'utilisateur',
        'delete_confirm' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action peut être annulée en restaurant le compte.',
    ],
    
    // Messages vides
    'empty' => [
        'title' => 'Aucun utilisateur trouvé',
        'filtered' => 'Aucun utilisateur ne correspond à vos critères de recherche.',
        'no_users' => 'Les utilisateurs apparaîtront ici une fois qu\'ils s\'inscriront.',
    ],
];
