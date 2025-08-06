<?php

// Script pour envoyer la notification d'activation de location
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OrderLocation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "=== ENVOI NOTIFICATION D'ACTIVATION DE LOCATION ===\n\n";

try {
    // Trouver votre commande
    $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
    
    if (!$order) {
        echo "❌ Commande non trouvée\n";
        exit(1);
    }
    
    echo "✅ Commande trouvée: {$order->order_number}\n";
    echo "📊 Statut: {$order->status}\n";
    echo "👤 Client: {$order->user->email}\n";
    echo "📅 Date de début: {$order->start_date}\n\n";
    
    // Vérifier si la classe d'email existe
    if (class_exists('App\Mail\RentalStartedMail')) {
        echo "📧 Envoi de l'email d'activation...\n";
        
        Mail::to($order->user->email)->send(
            new \App\Mail\RentalStartedMail($order)
        );
        
        echo "✅ Email d'activation envoyé avec succès!\n";
        echo "📨 Destinataire: {$order->user->email}\n";
        
    } else {
        // Créer un email simple si la classe n'existe pas
        echo "📧 Classe RentalStartedMail non trouvée, envoi d'un email simple...\n";
        
        Mail::raw("
Bonjour,

Votre location {$order->order_number} est maintenant ACTIVE !

📅 Période de location : Du {$order->start_date->format('d/m/Y')} au {$order->end_date->format('d/m/Y')}
💰 Montant : {$order->total_amount}€
📦 Article(s) : {$order->orderItemLocations->count()} article(s)

Votre location se terminera automatiquement le {$order->end_date->format('d/m/Y à H:i')}.

Cordialement,
L'équipe FarmShop
        ", function ($message) use ($order) {
            $message->to($order->user->email)
                   ->subject("🟢 Votre location {$order->order_number} est maintenant active!");
        });
        
        echo "✅ Email simple envoyé avec succès!\n";
    }
    
    echo "\n=== NOTIFICATION ENVOYÉE ===\n";
    echo "📧 Un email de notification a été envoyé à votre adresse\n";
    echo "📱 Vérifiez votre boîte de réception (et spam si nécessaire)\n";
    
} catch (\Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n";
    
    // Essayer une approche alternative avec notification simple
    echo "\n🔄 Tentative d'envoi simplifié...\n";
    
    try {
        $order = OrderLocation::where('order_number', 'LOC-202508034682')->first();
        
        Mail::send([], [], function ($message) use ($order) {
            $message->to($order->user->email)
                   ->subject("🟢 Location {$order->order_number} activée - FarmShop")
                   ->setBody("
                   <h2>Votre location est maintenant active !</h2>
                   <p><strong>Numéro :</strong> {$order->order_number}</p>
                   <p><strong>Statut :</strong> Active ✅</p>
                   <p><strong>Période :</strong> Du " . $order->start_date->format('d/m/Y') . " au " . $order->end_date->format('d/m/Y') . "</p>
                   <p><strong>Montant :</strong> {$order->total_amount}€</p>
                   <p>Votre location se terminera automatiquement le " . $order->end_date->format('d/m/Y à H:i') . ".</p>
                   <p>Cordialement,<br>L'équipe FarmShop</p>
                   ", 'text/html');
        });
        
        echo "✅ Email HTML envoyé avec succès!\n";
        
    } catch (\Exception $e2) {
        echo "❌ Erreur alternative: " . $e2->getMessage() . "\n";
        echo "💡 Vérifiez la configuration email dans .env\n";
    }
}
