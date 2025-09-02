<?php

return [
    // Page
    'page_title' => 'Blog',
    'title' => 'Blog FarmShop',
    'subtitle' => 'Découvrez nos conseils d\'experts, actualités agricoles et guides pratiques',
    
    // Search and filters
    'search_placeholder' => 'Rechercher un article...',
    'all_categories' => 'Toutes les catégories',
    'search_button' => 'Rechercher',
    
    // Categories
    'categories' => 'Catégories',
    'popular_articles' => 'Articles populaires',
    
    // Results
    'no_articles' => 'Aucun article trouvé',
    'no_articles_message' => 'Aucun article ne correspond à vos critères de recherche.',
    'articles_available_soon' => 'Les articles de blog seront bientôt disponibles.',
    
    // Article meta
    'published_on' => 'Publié le',
    'by_author' => 'par',
    'read_more' => 'Lire la suite',
    'comments' => 'commentaire(s)',
    'views' => 'vue(s)',
    'reading_time' => 'min de lecture',
    
    // Sorting
    'sort' => [
        'recent' => 'Plus récents',
        'popular' => 'Plus populaires',
        'views' => 'Plus vus',
        'comments' => 'Plus commentés',
    ],
    
    'filter_button' => 'Filtrer',
    
    // Tags
    'tags' => 'Étiquettes',
    'related_articles' => 'Articles connexes',
    
    // Admin interface
    'admin' => [
        'title' => 'Gestion des Articles de Blog',
        'page_title' => 'Gestion des Articles de Blog - FarmShop Admin',
        'subtitle' => 'Gérez et consultez tous les articles de votre blog FarmShop',
        'blog_articles' => 'Articles de Blog',
        
        // Actions
        'manage_categories' => 'Gérer Catégories',
        'view_public_blog' => 'Voir le Blog Public',
        'new_article' => 'Nouvel Article',
        
        // Statistics
        'total_articles' => 'Total Articles',
        'published' => 'Publiés',
        'drafts' => 'Brouillons',
        'categories' => 'Catégories',
        
        // Filters and search
        'search_filters_title' => 'Recherche et Filtres Avancés',
        'search_articles' => 'Recherche d\'articles',
        'search_placeholder_admin' => 'Titre, contenu, extrait, tags...',
        'category' => 'Catégorie',
        'all_categories_admin' => 'Toutes les catégories',
        'articles_count' => ':count articles',
        'publication_status' => 'Statut de publication',
        'all_statuses' => 'Tous les statuts',
        'published_status' => '✅ Publié',
        'draft_status' => '📝 Brouillon',
        'scheduled_status' => '⏰ Programmé',
        'author' => 'Auteur',
        'all_authors' => 'Tous les auteurs',
        'sort_by' => 'Trier par',
        'sort_creation_date' => 'Date de création',
        'sort_update_date' => 'Dernière modification',
        'sort_publication_date' => 'Date de publication',
        'sort_views_count' => 'Nombre de vues',
        'sort_title' => 'Titre alphabétique',
        'order' => 'Ordre',
        'descending' => '↓ Décroissant',
        'ascending' => '↑ Croissant',
        'hide_advanced_filters' => 'Masquer filtres avancés',
        'advanced_filters' => 'Filtres avancés',
        'reset' => 'Réinitialiser',
        'search_button' => 'Rechercher',
        'articles_list_title' => 'Articles (:count)',
        'sorted_by' => 'Tri par :sort_by (:direction)',
        
        // Table headers
        'table_article' => 'Article',
        'table_category' => 'Catégorie',
        'table_author' => 'Auteur',
        'table_status' => 'Statut',
        'table_date' => 'Date',
        'table_views' => 'Vues',
        'table_actions' => 'Actions',
        
        // Article status badges
        'featured_badge' => '★ Mis en avant',
        'no_category' => 'Sans catégorie',
        'status_published' => 'Publié',
        'status_draft' => 'Brouillon',
        'status_scheduled' => 'Programmé',
        
        // Action tooltips
        'view_article' => 'Voir l\'article',
        'edit_article' => 'Modifier l\'article',
        'delete_article' => 'Supprimer l\'article',
        
        // Empty state messages
        'no_articles' => 'Aucun article',
        'no_articles_match_criteria' => 'Aucun article ne correspond à vos critères de recherche.',
        'create_first_article' => 'Commencez par créer votre premier article de blog.',
        
        // Info section
        'info_title' => '💡 Information sur la gestion des articles',
        'info_description_1' => 'Cette interface permet la <strong>consultation et le filtrage</strong> des articles existants.',
        'info_description_2' => 'La gestion complète (création, édition, suppression) est disponible via <strong>l\'API REST</strong>. Consultez la documentation API pour plus de détails.',
        'api_documentation' => '📖 Documentation API',
        'view_public_blog_info' => '🌐 Voir le blog public',
        
        // Delete modal
        'confirm_deletion' => 'Confirmer la suppression',
        'delete_confirmation_question' => 'Êtes-vous sûr de vouloir supprimer cet article ?',
        'irreversible_action' => 'Cette action est irréversible.',
        'cancel' => 'Annuler',
        'delete' => 'Supprimer',
        'deleting' => 'Suppression...',
    ],
    
    // Comments management
    'comments' => [
        'page_title' => 'Gestion des Commentaires - FarmShop Admin',
        'section_title' => 'Gestion des Commentaires',
        'title' => 'Gestion des Commentaires',
        'description' => 'Gérez tous les commentaires du blog et leur modération',
        
        // Header actions
        'filters_button' => 'Filtres',
        'refresh_button' => 'Actualiser',
        
        // Statistics
        'total_comments' => 'Total Commentaires',
        'pending_comments' => 'En Attente',
        'approved_comments' => 'Approuvés',
        'rejected_comments' => 'Rejetés',
        'spam_comments' => 'Spam',
        'reported_comments' => 'Signalements',
        
        // Search and filters
        'search_label' => 'Recherche générale',
        'search_placeholder' => 'Rechercher par auteur, contenu...',
        'status' => 'Statut de modération',
        'all_statuses' => 'Tous les statuts',
        'status_pending' => 'En attente',
        'status_approved' => 'Approuvé',
        'status_rejected' => 'Rejeté',
        'status_spam' => 'Spam',
        'reports_label' => 'Signalements',
        'all_reports' => 'Tous',
        'with_reports' => 'Avec signalements',
        'without_reports' => 'Sans signalements',
        
        // Table headers
        'comment' => 'Commentaire',
        'author' => 'Auteur',
        'article' => 'Article',
        'date' => 'Date',
        'status' => 'Statut',
        'actions' => 'Actions',
        'content' => 'Contenu',
        
        // Advanced search section
        'advanced_search_title' => 'Recherche et Filtres Avancés',
        'advanced_search_description' => 'Trouvez rapidement les commentaires que vous recherchez avec nos outils de filtrage',
        
        // Bulk actions
        'select_all' => 'Tout sélectionner',
        'selected_count' => 'sélectionné(s)',
        'bulk_approve' => 'Approuver',
        'bulk_reject' => 'Rejeter', 
        'bulk_delete' => 'Supprimer',
        'quick_actions' => 'Actions rapides',
        
        // Comments list
        'comments_list' => 'Liste des Commentaires',
        
        // Status labels
        'status_pending' => 'En attente',
        'status_approved' => 'Approuvé',
        'status_rejected' => 'Rejeté',
        'status_spam' => 'Spam',
        
        // Actions
        'approve' => 'Approuver',
        'reject' => 'Rejeter',
        'mark_spam' => 'Marquer spam',
        'edit' => 'Modifier',
        'delete' => 'Supprimer',
        'view_article' => 'Voir l\'article',
        'reply' => 'Répondre',
        
        // Filters
        'filter_by_status' => 'Filtrer par statut',
        'filter_by_article' => 'Filtrer par article',
        'search_label' => 'Rechercher',
        'search_placeholder' => 'Rechercher dans les commentaires...',
        'all_statuses' => 'Tous les statuts',
        'all_articles' => 'Tous les articles',
        'filter_button' => 'Filtrer',
        'reset_filters' => 'Réinitialiser',
        'reports_label' => 'Signalements',
        'all_reports' => 'Tous',
        'with_reports' => 'Avec signalements',
        'without_reports' => 'Sans signalements',
        
        // Bulk actions
        'bulk_actions' => 'Actions groupées',
        'select_all' => 'Tout sélectionner',
        'selected_count' => 'sélectionné(s)',
        'bulk_approve' => 'Approuver sélectionnés',
        'bulk_reject' => 'Rejeter sélectionnés',
        'bulk_spam' => 'Marquer spam sélectionnés',
        'bulk_delete' => 'Supprimer sélectionnés',
        
        // Table
        'comments_list' => 'Liste des Commentaires',
                'loading' => 'Chargement des commentaires...',
        'no_comments' => 'Aucun commentaire',
        'no_comments_message' => 'Aucun commentaire ne correspond à vos critères de recherche.',
        
        // Messages JavaScript
        'js_error_loading' => 'Erreur lors du chargement des commentaires',
        'js_error_loading_detail' => 'Erreur lors du chargement du détail',
        'js_select_action' => 'Veuillez sélectionner une action',
        'js_action_success' => 'Action effectuée avec succès',
        'js_moderation_error' => 'Erreur lors de la modération',
        'js_delete_success' => 'Commentaire supprimé avec succès',
        'js_delete_error' => 'Erreur lors de la suppression',
        'js_select_comments' => 'Veuillez sélectionner au moins un commentaire',
        
        // Messages
        'no_comments' => 'Aucun commentaire',
        'no_comments_message' => 'Aucun commentaire ne correspond à vos critères de recherche.',
        
        // Table headers
        'table_author' => 'Auteur',
        'table_article' => 'Article',
        'table_status' => 'Statut',
        'table_date' => 'Date',
        'table_actions' => 'Actions',
        
        // Action buttons
        'action_approve' => 'Approuver',
        'action_reject' => 'Rejeter',
        'action_spam' => 'Marquer comme spam',
        'action_delete' => 'Supprimer',
        
        // Additional JavaScript messages
        'js_confirm_delete' => 'Êtes-vous sûr de vouloir supprimer ce commentaire ?',
        'js_bulk_action_success' => 'Action effectuée avec succès',
        'js_bulk_action_error' => 'Erreur lors de l\'action groupée',
        'js_data_refreshed' => 'Données actualisées',
        'js_feature_development' => 'Fonctionnalité en développement',
        
        // Status labels for JavaScript
        'status_approved_label' => 'Approuvé',
        'status_rejected_label' => 'Rejeté',
        
        // Additional table headers
        'table_content' => 'Contenu',
        'table_reports' => 'Signalements',
        
        // Guest info
        'anonymous' => 'Anonyme',
        'not_available' => 'N/A',
    ],
];
