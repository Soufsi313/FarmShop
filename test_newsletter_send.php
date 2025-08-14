<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test d'envoi de la newsletter de bienvenue ===\n";

try {
    // Récupérer la newsletter
    $newsletter = \App\Models\Newsletter::where('title', 'Bienvenue sur FarmShop !')->latest()->first();
    
    if (!$newsletter) {
        echo "❌ Newsletter non trouvée\n";
        exit(1);
    }
    
    echo "📧 Newsletter trouvée : {$newsletter->title} (ID: {$newsletter->id})\n";
    
    // Récupérer l'admin
    $admin = \App\Models\User::where('role', 'Admin')->first();
    
    if (!$admin) {
        echo "❌ Admin non trouvé\n";
        exit(1);
    }
    
    echo "👤 Admin trouvé : {$admin->email}\n";
    
    // Créer un enregistrement de suivi
    $send = \App\Models\NewsletterSend::create([
        'newsletter_id' => $newsletter->id,
        'user_id' => $admin->id,
        'email' => $admin->email,
        'status' => 'pending',
        'tracking_token' => \Illuminate\Support\Str::uuid(),
        'unsubscribe_token' => \Illuminate\Support\Str::uuid(),
    ]);
    
    // Générer les URLs de suivi
    $send->tracking_url = route('newsletter.track', ['token' => $send->tracking_token]);
    $send->unsubscribe_url = route('newsletter.unsubscribe.token', ['token' => $send->unsubscribe_token]);
    $send->save();
    
    echo "📋 Enregistrement de suivi créé\n";
    
    // Envoyer l'email
    \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\NewsletterMail($newsletter, $admin, $send));
    
    // Marquer comme envoyé
    $send->update([
        'status' => 'sent',
        'sent_at' => now()
    ]);
    
    echo "✅ Newsletter envoyée avec succès à : {$admin->email}\n";
    echo "📊 Statut : {$send->status}\n";
    echo "⏰ Envoyé à : {$send->sent_at}\n\n";
    
    echo "🎯 Vérifiez votre boîte mail pour voir la newsletter de bienvenue améliorée !\n";
    echo "🎨 Design avec contrastes optimisés et éléments cliquables bien visibles\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
