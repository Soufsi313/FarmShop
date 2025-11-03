<?php

return [
    // Page titles and meta
    'title' => 'Gestion de Stock',
    'page_title' => 'Gestion de Stock - FarmShop Admin',
    'subtitle' => 'Vue d\'ensemble et surveillance en temps réel de vos stocks',

    // Header and actions
    'header' => [
        'title' => '📦 Gestion de Stock',
        'description' => 'Vue d\'ensemble et surveillance en temps réel de vos stocks',
        'refresh' => 'Actualiser',
        'restock' => 'Réapprovisionner',
    ],

    // Stock statistics
    'stats' => [
        'out_of_stock' => 'Rupture de Stock',
        'critical_stock' => 'Stock Critique',
        'low_stock' => 'Stock Bas',
        'normal_stock' => 'Stock Normal',
        'total_value' => 'Valeur Totale du Stock',
        'critical_value' => 'Valeur Stock Critique',
        'attention_needed' => 'Produits Nécessitant Attention',
        'of_catalog' => 'du catalogue',
    ],

    // Alerts and trends
    'alerts' => [
        'trends_title' => '📈 Tendances des Alertes (7 derniers jours)',
        'view_reports' => 'Voir les rapports détaillés →',
        'alerts_count' => 'alertes',
        'no_alerts' => 'Aucune alerte',
        'out_of_stock_label' => 'Rupture',
        'critical_label' => 'Critique',
        'low_label' => 'Bas',
    ],

    // Stock by category
    'by_category' => [
        'title' => '📊 Stock par Catégorie',
        'products' => 'produits',
        'product' => 'produit',
        'total_value' => 'Valeur totale',
        'no_products' => 'Aucun produit',
    ],

    // Actions
    'actions' => [
        'view_urgent' => 'Voir Urgent',
        'manage_products' => 'Gérer Produits',
        'view_all_alerts' => 'Voir toutes les alertes',
        'generate_report' => 'Générer un rapport',
    ],

    // Messages
    'messages' => [
        'data_refreshed' => 'Données actualisées avec succès',
        'no_critical_products' => 'Aucun produit avec stock critique',
        'all_good' => 'Tout va bien !',
    ],

    // Time periods
    'time' => [
        'last_7_days' => '7 derniers jours',
        'this_week' => 'Cette semaine',
        'this_month' => 'Ce mois',
        'today' => 'Aujourd\'hui',
        'last_update' => 'Dernière mise à jour',
    ],

    // Additional labels
    'normal_label' => 'Normal',
    'detailed_view' => 'Vue détaillée',
    'simple_view' => 'Vue simplifiée',

    // Critical products section
    'immediate_attention' => 'Attention Immédiate Requise',
    'no_category' => 'Sans catégorie',
    'stock_label' => 'Stock',
    'threshold_label' => 'Seuil',
    'edit_button' => 'Modifier',

    // Quick actions
    'quick_actions' => 'Actions Rapides',
    'manage_alerts' => 'Gérer Alertes',
    'manage_reports' => 'Rapports',

    // Alerts page
    'alerts_page_title' => 'Alertes & Seuils - Gestion de Stock',
    'alerts_title' => '🚨 Alertes & Seuils Critiques',
    'alerts_subtitle' => 'Surveillance et configuration des alertes de stock',
    'back_button' => '← Retour',
    'configure_thresholds' => 'Configurer Seuils',
    
    // Alert summary
    'out_of_stock_plural' => 'Ruptures de Stock',
    'critical_stock_summary' => 'Stock Critique',
    'low_stock_summary' => 'Stock Bas',
    
    // Alert tabs
    'tab_out_of_stock' => 'Ruptures de Stock',
    'tab_critical' => 'Stock Critique',
    'tab_low' => 'Stock Bas',
    'tab_history' => 'Historique des Alertes',
    
    // Product details in alerts
    'no_category' => 'Sans catégorie',
    'out_of_stock_since' => 'En rupture depuis',
    'price_label' => 'Prix',
    'unit_price' => 'Prix unitaire',
    'restock_button' => 'Réapprovisionner',
    'edit_button' => 'Modifier',
    'stock_label' => 'Stock',
    'threshold_label' => 'Seuil',
    'low_threshold_label' => 'Seuil bas',
    'out_of_stock_status' => 'RUPTURE',
    
    // Empty states
    'no_out_of_stock' => 'Aucune rupture de stock !',
    'no_out_of_stock_desc' => 'Tous vos produits sont en stock.',
    'no_critical_stock' => 'Aucun stock critique !',
    'no_critical_stock_desc' => 'Tous vos produits ont un stock suffisant.',
    'no_low_stock' => 'Aucun stock bas !',
    'no_low_stock_desc' => 'Tous vos produits ont un niveau de stock satisfaisant.',
    'no_recent_alerts' => 'Aucune alerte récente',
    'no_recent_alerts_desc' => 'Votre système de stock fonctionne parfaitement.',
    
    // Alert priorities
    'priority_urgent' => 'Urgent',
    'priority_high' => 'Élevé',
    'priority_normal' => 'Normal',
    
    // Quick restock modal
    'quick_restock_title' => '🔄 Réapprovisionnement Rapide',
    'product_label' => 'Produit',
    'quantity_to_add' => 'Quantité à ajouter',
    'cancel_button' => 'Annuler',
    'apply_button' => 'Appliquer',

    // Restock page
    'restock_page_title' => 'Réapprovisionnement - Gestion de Stock',
    'restock_title' => '🔄 Réapprovisionnement Automatique',
    'restock_subtitle' => 'Suggestions intelligentes et gestion du réapprovisionnement',
    'refresh_suggestions' => 'Actualiser Suggestions',
    
    // Restock summary
    'products_to_restock' => 'Produits à Réapprovisionner',
    'estimated_total_cost' => 'Coût Total Estimé',
    'urgent_priority' => 'Priorité Urgente',
    'total_quantity' => 'Quantité Totale',
    'average_stock' => 'Stock Moyen',
    
    // Restock suggestions
    'restock_suggestions' => '💡 Suggestions de Réapprovisionnement',
    'select_all' => 'Sélectionner Tout',
    'apply_selection' => 'Appliquer Sélection',
    'urgent_tag' => 'URGENT',
    'high_tag' => 'ÉLEVÉ',
    'current_stock' => 'Stock actuel',
    'recommended_stock' => 'Stock recommandé',
    'to_order' => 'À commander',
    'monthly_sales' => 'Ventes mensuelles',
    'customize_button' => 'Personnaliser',
    'apply_button' => 'Appliquer',
    
    // Empty state
    'no_restock_needed' => 'Aucun réapprovisionnement nécessaire !',
    'no_restock_needed_desc' => 'Tous vos produits ont un stock suffisant.',
    
    // Restock history
    'restock_history' => '📋 Historique des Réapprovisionnements',
    
    // Custom restock modal
    'custom_restock_title' => '🔧 Réapprovisionnement Personnalisé',
    'suggested_quantity' => 'suggéré',
    
    // Messages système de réapprovisionnement
    'restock_system_messages' => [
        'restock_completed_title' => 'Réapprovisionnement effectué',
        'restock_completed_message' => 'Le produit ":product" a été réapprovisionné. Quantité ajoutée: :quantity. Nouveau stock: :new_stock.',
        'auto_restock_title' => 'Réapprovisionnement automatique',
        'auto_restock_message' => 'Le produit ":product" a été réapprovisionné automatiquement. Quantité ajoutée: :quantity. Nouveau stock: :new_stock.',
        'bulk_restock_title' => 'Réapprovisionnement en masse',
        'bulk_restock_message' => 'Le produit ":product" a été réapprovisionné. Quantité ajoutée: :quantity. Nouveau stock: :new_stock.',
    ],

    // Reports page
    'reports_title' => 'Rapports & Analyses',
    'reports_subtitle' => 'Analyses détaillées et prévisions de votre inventaire',
    'back_button' => 'Retour',
    'export_report_button' => 'Exporter Rapport',
    
    // Charts
    'category_stock_chart_title' => 'Répartition du Stock par Catégorie',
    'stock_status_chart_title' => 'Répartition par Statut de Stock',
    'stock_normal' => 'Stock Normal',
    'stock_low' => 'Stock Bas',
    'stock_critical' => 'Stock Critique',
    'stock_outage' => 'Rupture',
    
    // Top products
    'top_products_title' => 'Top 10 des Produits les Plus Populaires',
    'sort_by_views' => 'Vues',
    'sort_by_likes' => 'Likes',
    'sort_by_value' => 'Valeur',
    
    // Table headers
    'product_header' => 'Produit',
    'category_header' => 'Catégorie',
    'stock_header' => 'Stock',
    'price_header' => 'Prix',
    'views_header' => 'Vues',
    'likes_header' => 'Likes',
    'stock_value_header' => 'Valeur Stock',
    'status_header' => 'Statut',
    
    // Status labels
    'status_outage' => 'Rupture',
    'status_critical' => 'Critique',
    'status_low' => 'Bas',
    'status_normal' => 'Normal',
    
    // Forecasts
    'stock_forecasts_title' => 'Prévisions de Rupture de Stock',
    'current_stock' => 'Stock actuel',
    'monthly_sales' => 'Ventes mensuelles',
    'days_remaining' => 'Jours restants',
    'estimated_outage_date' => 'Rupture prévue',
    'days_unit' => 'jours',
    'exhausted' => 'Épuisé',
    'manage_product_button' => 'Gérer ce produit',
    
    // Priority labels
    'urgent_priority' => 'URGENT',
    'soon_priority' => 'BIENTÔT',
    'attention_priority' => 'ATTENTION',
    
    // No forecasts
    'no_forecasts_title' => 'Aucune rupture prévue !',
    'no_forecasts_message' => 'Vos stocks sont bien gérés.',
    
    // Export section
    'exports_reports_title' => 'Exports et Rapports',
    'export_excel_title' => 'Export Excel',
    'export_excel_desc' => 'Données complètes',
    'pdf_report_title' => 'Rapport PDF',
    'pdf_report_desc' => 'Synthèse hebdomadaire',
    'schedule_title' => 'Planifier',
    'schedule_desc' => 'Rapports automatiques',
    
    // JS Messages
    'export_error' => 'Erreur lors de l\'export',
    'report_error' => 'Erreur lors de la génération du rapport',
    'schedule_development' => 'Fonctionnalité de planification en développement',
    
    // Time references for restock page
    'today' => 'Aujourd\'hui',
    'last_update' => 'Dernière mise à jour',
    'total_value' => 'Valeur Totale',
    
    // Restock page section
    'restock' => [
        'title' => 'Réapprovisionnement',
        'page_title' => 'Réapprovisionnement - Gestion de Stock',
    ],
];
