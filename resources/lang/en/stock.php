<?php

return [
    // Page titles and meta
    'title' => 'Stock Management',
    'page_title' => 'Stock Management - FarmShop Admin',
    'subtitle' => 'Real-time overview and monitoring of your inventory',

    // Header and actions
    'header' => [
        'title' => '📦 Stock Management',
        'description' => 'Real-time overview and monitoring of your inventory',
        'refresh' => 'Refresh',
        'restock' => 'Restock',
    ],

    // Stock statistics
    'stats' => [
        'out_of_stock' => 'Out of Stock',
        'critical_stock' => 'Critical Stock',
        'low_stock' => 'Low Stock',
        'normal_stock' => 'Normal Stock',
        'total_value' => 'Total Stock Value',
        'critical_value' => 'Critical Stock Value',
        'attention_needed' => 'Products Needing Attention',
        'of_catalog' => 'of catalog',
    ],

    // Alerts and trends
    'alerts' => [
        'trends_title' => '📈 Alert Trends (Last 7 days)',
        'view_reports' => 'View detailed reports →',
        'alerts_count' => 'alerts',
        'no_alerts' => 'No alerts',
        'out_of_stock_label' => 'Out',
        'critical_label' => 'Critical',
        'low_label' => 'Low',
    ],

    // Stock by category
    'by_category' => [
        'title' => '📊 Stock by Category',
        'products' => 'products',
        'product' => 'product',
        'total_value' => 'Total value',
        'no_products' => 'No products',
    ],

    // Actions
    'actions' => [
        'view_urgent' => 'View Urgent',
        'manage_products' => 'Manage Products',
        'view_all_alerts' => 'View all alerts',
        'generate_report' => 'Generate report',
    ],

    // Messages
    'messages' => [
        'data_refreshed' => 'Data refreshed successfully',
        'no_critical_products' => 'No products with critical stock',
        'all_good' => 'All good!',
    ],

    // Time periods
    'time' => [
        'last_7_days' => 'Last 7 days',
        'this_week' => 'This week',
        'this_month' => 'This month',
        'today' => 'Today',
    ],

    // Additional labels
    'normal_label' => 'Normal',
    'detailed_view' => 'Detailed view',
    'simple_view' => 'Simple view',

    // Critical products section
    'immediate_attention' => 'Immediate Attention Required',
    'no_category' => 'No category',
    'stock_label' => 'Stock',
    'threshold_label' => 'Threshold',
    'edit_button' => 'Edit',

    // Quick actions
    'quick_actions' => 'Quick Actions',
    'manage_alerts' => 'Manage Alerts',
    'manage_reports' => 'Reports',

    // Alerts page
    'alerts_page_title' => 'Alerts & Thresholds - Stock Management',
    'alerts_title' => '🚨 Critical Alerts & Thresholds',
    'alerts_subtitle' => 'Stock alert monitoring and configuration',
    'back_button' => '← Back',
    'configure_thresholds' => 'Configure Thresholds',
    
    // Alert summary
    'out_of_stock_plural' => 'Out of Stock',
    'critical_stock_summary' => 'Critical Stock',
    'low_stock_summary' => 'Low Stock',
    
    // Alert tabs
    'tab_out_of_stock' => 'Out of Stock',
    'tab_critical' => 'Critical Stock',
    'tab_low' => 'Low Stock',
    'tab_history' => 'Alert History',
    
    // Product details in alerts
    'no_category' => 'No category',
    'out_of_stock_since' => 'Out of stock since',
    'price_label' => 'Price',
    'unit_price' => 'Unit price',
    'restock_button' => 'Restock',
    'edit_button' => 'Edit',
    'stock_label' => 'Stock',
    'threshold_label' => 'Threshold',
    'low_threshold_label' => 'Low threshold',
    'out_of_stock_status' => 'OUT OF STOCK',
    
    // Empty states
    'no_out_of_stock' => 'No stock-outs!',
    'no_out_of_stock_desc' => 'All your products are in stock.',
    'no_critical_stock' => 'No critical stock!',
    'no_critical_stock_desc' => 'All your products have sufficient stock.',
    'no_low_stock' => 'No low stock!',
    'no_low_stock_desc' => 'All your products have satisfactory stock levels.',
    'no_recent_alerts' => 'No recent alerts',
    'no_recent_alerts_desc' => 'Your stock system is working perfectly.',
    
    // Alert priorities
    'priority_urgent' => 'Urgent',
    'priority_high' => 'High',
    'priority_normal' => 'Normal',
    
    // Quick restock modal
    'quick_restock_title' => '🔄 Quick Restocking',
    'product_label' => 'Product',
    'quantity_to_add' => 'Quantity to add',
    'cancel_button' => 'Cancel',
    'apply_button' => 'Apply',

    // Restock page
    'restock_page_title' => 'Restocking - Stock Management',
    'restock_title' => '🔄 Automatic Restocking',
    'restock_subtitle' => 'Smart suggestions and restocking management',
    'refresh_suggestions' => 'Refresh Suggestions',
    
    // Restock summary
    'products_to_restock' => 'Products to Restock',
    'estimated_total_cost' => 'Estimated Total Cost',
    'urgent_priority' => 'Urgent Priority',
    'total_quantity' => 'Total Quantity',
    
    // Restock suggestions
    'restock_suggestions' => '💡 Restocking Suggestions',
    'select_all' => 'Select All',
    'apply_selection' => 'Apply Selection',
    'urgent_tag' => 'URGENT',
    'high_tag' => 'HIGH',
    'current_stock' => 'Current stock',
    'recommended_stock' => 'Recommended stock',
    'to_order' => 'To order',
    'monthly_sales' => 'Monthly sales',
    'customize_button' => 'Customize',
    'apply_button' => 'Apply',
    
    // Empty state
    'no_restock_needed' => 'No restocking needed!',
    'no_restock_needed_desc' => 'All your products have sufficient stock.',
    
    // Restock history
    'restock_history' => '📋 Restocking History',
    
    // Custom restock modal
    'custom_restock_title' => '🔧 Custom Restocking',
    'suggested_quantity' => 'suggested',
    
    // Restock system messages
    'restock_system_messages' => [
        'restock_completed_title' => 'Restocking Completed',
        'restock_completed_message' => 'Product ":product" has been restocked. Quantity added: :quantity. New stock: :new_stock.',
        'auto_restock_title' => 'Automatic Restocking',
        'auto_restock_message' => 'Product ":product" has been automatically restocked. Quantity added: :quantity. New stock: :new_stock.',
        'bulk_restock_title' => 'Bulk Restocking',
        'bulk_restock_message' => 'Product ":product" has been restocked. Quantity added: :quantity. New stock: :new_stock.',
    ],

    // Reports page
    'reports_title' => 'Reports & Analytics',
    'reports_subtitle' => 'Detailed analysis and forecasts of your inventory',
    'back_button' => 'Back',
    'export_report_button' => 'Export Report',
    
    // Charts
    'category_stock_chart_title' => 'Stock Distribution by Category',
    'stock_status_chart_title' => 'Distribution by Stock Status',
    'stock_normal' => 'Normal Stock',
    'stock_low' => 'Low Stock',
    'stock_critical' => 'Critical Stock',
    'stock_outage' => 'Out of Stock',
    
    // Top products
    'top_products_title' => 'Top 10 Most Popular Products',
    'sort_by_views' => 'Views',
    'sort_by_likes' => 'Likes',
    'sort_by_value' => 'Value',
    
    // Table headers
    'product_header' => 'Product',
    'category_header' => 'Category',
    'stock_header' => 'Stock',
    'price_header' => 'Price',
    'views_header' => 'Views',
    'likes_header' => 'Likes',
    'stock_value_header' => 'Stock Value',
    'status_header' => 'Status',
    
    // Status labels
    'status_outage' => 'Out of Stock',
    'status_critical' => 'Critical',
    'status_low' => 'Low',
    'status_normal' => 'Normal',
    
    // Forecasts
    'stock_forecasts_title' => 'Stock Outage Forecasts',
    'current_stock' => 'Current stock',
    'monthly_sales' => 'Monthly sales',
    'days_remaining' => 'Days remaining',
    'estimated_outage_date' => 'Estimated outage',
    'days_unit' => 'days',
    'exhausted' => 'Exhausted',
    'manage_product_button' => 'Manage this product',
    
    // Priority labels
    'urgent_priority' => 'URGENT',
    'soon_priority' => 'SOON',
    'attention_priority' => 'ATTENTION',
    
    // No forecasts
    'no_forecasts_title' => 'No outages predicted!',
    'no_forecasts_message' => 'Your stocks are well managed.',
    
    // Export section
    'exports_reports_title' => 'Exports and Reports',
    'export_excel_title' => 'Excel Export',
    'export_excel_desc' => 'Complete data',
    'pdf_report_title' => 'PDF Report',
    'pdf_report_desc' => 'Weekly summary',
    'schedule_title' => 'Schedule',
    'schedule_desc' => 'Automatic reports',
    
    // JS Messages
    'export_error' => 'Export error',
    'report_error' => 'Error generating report',
    'schedule_development' => 'Scheduling feature in development',
];
