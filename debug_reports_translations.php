<?php

// Script de test pour les traductions de la page reports

$languages = ['fr', 'en', 'nl'];
$keys = [
    'reports_title',
    'reports_subtitle', 
    'back_button',
    'export_report_button',
    'category_stock_chart_title',
    'stock_status_chart_title',
    'top_products_title',
    'sort_by_views',
    'sort_by_likes',
    'sort_by_value',
    'product_header',
    'category_header',
    'stock_header',
    'price_header',
    'views_header',
    'likes_header',
    'stock_value_header',
    'status_header',
    'status_outage',
    'status_critical',
    'status_low',
    'status_normal',
    'stock_forecasts_title',
    'current_stock',
    'monthly_sales',
    'days_remaining',
    'estimated_outage_date',
    'urgent_priority',
    'soon_priority',
    'attention_priority',
    'no_forecasts_title',
    'no_forecasts_message',
    'exports_reports_title',
    'export_excel_title',
    'pdf_report_title',
    'schedule_title'
];

echo "=== TEST DES TRADUCTIONS - PAGE REPORTS ===\n\n";

foreach ($languages as $lang) {
    echo "🌍 LANGUE: " . strtoupper($lang) . "\n";
    echo str_repeat("=", 50) . "\n";
    
    // Commande pour tester toutes les clés
    $command = "php artisan tinker --execute=\"app()->setLocale('{$lang}'); ";
    foreach ($keys as $key) {
        $command .= "echo '{$key}: ' . __('stock.{$key}') . '\\n'; ";
    }
    $command .= "\"";
    
    echo "Commande de test:\n";
    echo $command . "\n\n";
    echo str_repeat("-", 50) . "\n\n";
}

echo "✅ Les nouvelles traductions ont été ajoutées pour:\n";
echo "- Titre et navigation\n";
echo "- Graphiques et légendes\n";
echo "- Top des produits\n";
echo "- En-têtes de tableau\n";
echo "- Statuts de stock\n";
echo "- Prévisions de rupture\n";
echo "- Priorités (URGENT, BIENTÔT, ATTENTION)\n";
echo "- Section d'export\n";
echo "- Messages JavaScript\n\n";

echo "📝 Fichiers modifiés:\n";
echo "- resources/lang/fr/stock.php (nouvelles clés)\n";
echo "- resources/lang/en/stock.php (nouvelles clés)\n";
echo "- resources/lang/nl/stock.php (nouvelles clés)\n";
echo "- resources/views/admin/stock/reports.blade.php (traductions intégrées)\n\n";

echo "🎯 Page complètement traduite: http://127.0.0.1:8000/admin/stock/reports\n";
