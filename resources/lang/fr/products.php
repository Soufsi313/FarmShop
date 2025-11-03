<?php

return [
    // Page titles
    'page_title' => 'Nos Produits',
    'title' => 'Nos Produits',
    'subtitle' => 'Découvrez notre sélection de produits biologiques et d\'équipements de qualité',
    
    // Filters
    'filters' => 'Filtres',
    'search' => 'Rechercher',
    'search_placeholder' => 'Nom du produit...',
    'category' => 'Catégorie', 
    'all_categories' => 'Toutes les catégories',
    'type' => 'Type',
    'all_types' => 'Tous les types',
    
    // Price
    'price_range' => 'Gamme de prix',
    'price_from' => 'De',
    'price_to' => 'à',
    'min_price' => 'Prix minimum',
    'max_price' => 'Prix maximum',
    'price_per_day' => '/jour',
    'starting_at' => 'dès',
    
    // Actions
    'filter' => 'Filtrer',
    'reset_filters' => 'Réinitialiser',
    'sort_by' => 'Trier par',
    'view_details' => 'Voir les détails',
    'add_to_cart' => 'Ajouter au panier',
    'buy_now' => 'Acheter maintenant',
    'quick_view' => 'Aperçu rapide',
    
    // Stock and availability
    'in_stock' => 'En stock',
    'out_of_stock' => 'Rupture de stock',
    'critical_stock' => 'Stock critique',
    'limited_stock' => 'Stock limité',
    'low_stock' => 'Stock faible',
    'quantity' => 'Quantité',
    'available' => 'disponible(s)',
    
    // Product types
    'type_sale' => 'Achat',
    'type_rental' => 'Location',
    'type_both' => 'Achat et Location',
    
    // Status
    'featured' => 'Vedette',
    'new' => 'Nouveau',
    'sale' => 'Promotion',
    
    // Results
    'results_count' => 'résultat(s) trouvé(s)',
    'no_products' => 'Aucun produit trouvé',
    'no_products_message' => 'Aucun produit ne correspond à vos critères de recherche.',
    'per_page' => 'par page',
    
    // Sorting
    'sort_newest' => 'Plus récent',
    'sort_oldest' => 'Plus ancien',
    'sort_price_asc' => 'Prix croissant',
    'sort_price_desc' => 'Prix décroissant',
    'sort_name_asc' => 'Nom A-Z',
    'sort_name_desc' => 'Nom Z-A',
    
    // Admin section
    'admin' => [
        'title' => 'Gestion des Produits - FarmShop Admin',
        'page_title' => 'Gestion des Produits',
        'catalog_title' => 'Catalogue de Produits',
        'advanced_interface' => 'Interface avancée de gestion de l\'inventaire avec recherche et filtres',
        'total_products' => 'Produits totaux',
        
        // Statistiques
        'stats' => [
            'total_products' => 'Total produits',
            'active_products' => 'Produits actifs',
            'low_stock' => 'Stock faible',
            'out_of_stock' => 'Rupture stock',
        ],
        
        // Actions rapides
        'actions' => [
            'new_product' => 'Nouveau Produit',
            'export' => 'Exporter',
            'import' => 'Importer',
            'filter' => 'Filtrer',
            'clear_filters' => 'Effacer',
        ],
        
        // Affichage
        'display' => [
            'showing' => 'Affichage :',
            'per_page' => 'par page',
            '15_per_page' => '15 par page',
            '30_per_page' => '30 par page',
            '50_per_page' => '50 par page',
            '100_per_page' => '100 par page',
        ],
        
        // Recherche et filtres
        'search' => [
            'title' => 'Recherche et Filtres',
            'description' => 'Trouvez rapidement les produits de votre catalogue',
            'general_search' => 'Recherche générale',
            'placeholder' => 'Nom, SKU, description...',
            'category' => 'Catégorie',
            'all_categories' => 'Toutes les catégories',
            'product_type' => 'Type de produit',
            'all_types' => 'Tous les types',
            'status' => 'Statut',
            'all_statuses' => 'Tous les statuts',
        ],
        
        // Types de produits
        'types' => [
            'sale_only' => '🛒 Vente uniquement',
            'rental_only' => '📅 Location uniquement',
        ],
        
        // Statuts
        'statuses' => [
            'active_only' => 'Actifs uniquement',
            'inactive_only' => 'Inactifs uniquement',
            'featured' => 'En vedette',
            'low_stock' => 'Stock faible',
            'out_of_stock' => 'Rupture de stock',
        ],
        
        // Messages
        'messages' => [
            'no_products' => 'Aucun produit trouvé',
            'products_filtered' => 'produits filtrés',
            'confirm_delete' => 'Êtes-vous sûr de vouloir supprimer ce produit ?',
            'success_created' => 'Produit créé avec succès',
            'success_updated' => 'Produit mis à jour avec succès',
            'success_deleted' => 'Produit supprimé avec succès',
        ],
        
        // Table headers
        'table' => [
            'product' => 'Produit',
            'category' => 'Catégorie',
            'price' => 'Prix',
            'stock' => 'Stock',
            'type' => 'Type',
            'status' => 'Statut',
            'actions' => 'Actions',
            'sku' => 'SKU',
            'view' => 'Voir',
            'edit' => 'Modifier',
            'delete' => 'Supprimer',
            'in_stock' => 'En stock',
            'low_stock' => 'Faible',
            'critical_stock' => 'Critique',
            'out_of_stock' => 'Rupture',
            'sale_only' => '🛒 Vente uniquement',
            'rental_only' => '📅 Location uniquement',
            'rental_only_text' => 'Location uniquement',
            'both_types' => '🔄 Mixte',
            'undefined_type' => '❓ Non défini',
            'active' => '✅ Actif',
            'inactive' => '❌ Inactif',
            'featured' => '⭐ Vedette',
            'per_day' => '€/jour',
            'no_products_found' => 'Aucun produit trouvé',
            'try_different_criteria' => 'Essayez de modifier vos critères de recherche.',
        ],
    ],
];
