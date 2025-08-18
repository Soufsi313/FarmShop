<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST NOUVEAU TEMPLATE EMAIL DÉMARRAGE ===\n\n";

// Rechercher votre commande récente
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508170236')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📋 Commande: {$order->order_number}\n";
echo "Status: {$order->status}\n";
echo "Email: {$order->user->email}\n\n";

// Forcer l'envoi du nouvel email de démarrage
echo "📧 Envoi du nouveau template d'email de démarrage...\n";

try {
    // Vider le cache de vues Laravel
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "✅ Cache des vues vidé\n";
    
    // Envoyer l'email avec le nouveau template
    \Illuminate\Support\Facades\Mail::to($order->user->email)->send(
        new \App\Mail\RentalStartedMail($order)
    );
    
    echo "✅ Nouvel email de démarrage envoyé avec succès !\n";
    echo "📧 Vérifiez votre boîte email pour voir le nouveau design Tailwind\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "📍 Fichier: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n=== DIFFÉRENCES VISUELLES ATTENDUES ===\n";
echo "🎨 Design moderne avec Tailwind CSS\n";
echo "🌈 Header avec dégradé vert\n";
echo "📱 Design responsive\n";
echo "🎯 Sections colorées avec bordures\n";
echo "🚀 Icônes et émojis intégrés\n";
echo "💡 Typographie moderne (Inter font)\n";
