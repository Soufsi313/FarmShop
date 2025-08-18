<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST NOUVEAU SYSTÈME DE RAPPELS ===\n\n";

// Rechercher votre commande récente (1 jour)
$order = App\Models\OrderLocation::where('order_number', 'LOC-202508170236')->first();

if (!$order) {
    echo "❌ Commande non trouvée\n";
    exit;
}

echo "📋 Commande: {$order->order_number}\n";
echo "Status: {$order->status}\n";
echo "Début: {$order->start_date}\n";
echo "Fin: {$order->end_date}\n";

// Calculer la durée
$startDate = $order->start_date;
$endDate = $order->end_date;
$rentalDurationInDays = $startDate->diffInDays($endDate) + 1;

echo "Durée: {$rentalDurationInDays} jour(s)\n\n";

// Simuler le nouveau système de rappels
$now = now();

echo "=== SIMULATION NOUVEAU SYSTÈME ===\n";

if ($rentalDurationInDays <= 1) {
    $reminderTime = $endDate->copy()->subHours(2);
    echo "📅 Location 1 jour → Rappel programmé 2h avant la fin\n";
    echo "🕒 Rappel prévu pour: {$reminderTime->format('d/m/Y à H:i')}\n";
    
    if ($reminderTime->isPast()) {
        echo "⚠️  Le rappel était prévu dans le passé\n";
    } elseif ($reminderTime->isAfter($now)) {
        echo "✅ Rappel sera envoyé à l'heure prévue\n";
    }
    
} elseif ($rentalDurationInDays <= 3) {
    $reminderTime = $endDate->copy()->subHours(12);
    echo "📅 Location {$rentalDurationInDays} jours → Rappel programmé 12h avant la fin\n";
    echo "🕒 Rappel prévu pour: {$reminderTime->format('d/m/Y à H:i')}\n";
    
} else {
    $reminderTime = $endDate->copy()->subDay();
    echo "📅 Location {$rentalDurationInDays} jours → Rappel programmé 24h avant la fin\n";
    echo "🕒 Rappel prévu pour: {$reminderTime->format('d/m/Y à H:i')}\n";
}

echo "\n=== TEST EMAIL DE RAPPEL ===\n";

// Test manuel du nouveau email de rappel adaptatif
try {
    $endDate = $order->end_date;
    $startDate = $order->start_date;
    $hoursRemaining = max(0, now()->diffInHours($endDate, false)); // false pour avoir les heures négatives si passé
    
    echo "Heures restantes: {$hoursRemaining}h\n";
    
    // Créer l'email avec les nouvelles variables
    $mail = new \App\Mail\RentalEndReminderMail($order);
    
    // Forcer l'envoi de test
    \Illuminate\Support\Facades\Mail::to($order->user->email)->send($mail);
    
    echo "✅ Email de rappel adaptatif envoyé avec succès !\n";
    echo "📧 Vérifiez votre boîte email\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
}

echo "\n=== RÉSUMÉ NOUVEAU SYSTÈME ===\n";
echo "🏆 Locations 1 jour: Rappel 2h avant la fin\n";
echo "🏆 Locations 2-3 jours: Rappel 12h avant la fin\n";
echo "🏆 Locations 4+ jours: Rappel 24h avant la fin\n";
echo "📧 Template email adaptatif selon le temps restant\n";
