<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 Correction du statut de la commande de test\n\n";

// Trouver la commande créée
$orderLocation = \App\Models\OrderLocation::where('order_number', 'LIKE', 'LOC-TEST-INSPECTION-%')->orderBy('created_at', 'desc')->first();

if (!$orderLocation) {
    echo "❌ Aucune commande de test trouvée\n";
    exit(1);
}

echo "📦 Commande trouvée: {$orderLocation->order_number}\n";
echo "📊 Statut actuel: {$orderLocation->status}\n";

// Corriger le statut
$orderLocation->update([
    'status' => 'completed' // Status correct pour permettre la clôture
]);

echo "✅ Statut corrigé vers: completed\n";
echo "📱 Maintenant le bouton 'Clôturer' devrait apparaître!\n\n";

echo "🎯 INSTRUCTIONS MISES À JOUR:\n";
echo "============================\n";
echo "1. 🌐 Allez sur http://127.0.0.1:8000/rental-orders\n";
echo "2. 🔍 Cherchez la commande: {$orderLocation->order_number}\n";
echo "3. ✅ Le bouton '🔒 Clôturer la location' devrait maintenant être visible\n";
echo "4. 📝 Cliquez dessus et confirmez la clôture\n";
echo "5. 📧 Vérifiez la réception de l'email d'inspection\n\n";

echo "📋 LOGIQUE DES STATUTS CLARIFIÉE:\n";
echo "=================================\n";
echo "• 'completed' = 🟣 Terminée (matériel à rendre, bouton Clôturer visible)\n";
echo "• 'closed' = 🔒 Clôturée (matériel rendu, en attente d'inspection)\n";
echo "• 'finished' = ✅ Finalisée (inspection terminée, processus complet)\n\n";

echo "🚀 Votre commande est maintenant prête pour le test de clôture!\n";
