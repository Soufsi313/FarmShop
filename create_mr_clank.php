<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CRÉATION DE L'UTILISATEUR SYSTÈME MR CLANK ===" . PHP_EOL;

// Vérifier si Mr Clank existe déjà
$mrClank = \App\Models\User::where('email', 'system@farmshop.local')->first();

if ($mrClank) {
    echo "✅ Mr Clank existe déjà (ID: {$mrClank->id})" . PHP_EOL;
} else {
    echo "🔄 Création de Mr Clank..." . PHP_EOL;
    
    try {
        $mrClank = \App\Models\User::create([
            'username' => 'mr_clank_system', // Ajout du username requis
            'name' => 'Mr Clank 🤖', // Ajout d'un emoji pour le distinguer
            'email' => 'system@farmshop.local',
            'password' => bcrypt('system_password_' . time()), // Mot de passe random sécurisé
            'role' => 'Admin', // Utilisons Admin car "system" n'existe pas
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Mr Clank créé avec succès (ID: {$mrClank->id})" . PHP_EOL;
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création: " . $e->getMessage() . PHP_EOL;
        exit;
    }
}

echo PHP_EOL . "📋 INFORMATIONS DE MR CLANK:" . PHP_EOL;
echo "ID: " . $mrClank->id . PHP_EOL;
echo "Nom: " . $mrClank->name . PHP_EOL;
echo "Email: " . $mrClank->email . PHP_EOL;
echo "Rôle: " . $mrClank->role . PHP_EOL;
echo "Créé le: " . $mrClank->created_at->format('d/m/Y H:i:s') . PHP_EOL;

// Mettre à jour les messages système existants pour qu'ils soient associés à Mr Clank
echo PHP_EOL . "🔄 MISE À JOUR DES MESSAGES SYSTÈME EXISTANTS..." . PHP_EOL;

$systemMessages = \App\Models\Message::where('type', 'stock_alert')
    ->whereNull('sender_id')
    ->orWhere('sender_id', 0)
    ->get();

if ($systemMessages->count() > 0) {
    foreach ($systemMessages as $message) {
        $message->update(['sender_id' => $mrClank->id]);
    }
    echo "✅ " . $systemMessages->count() . " message(s) système mis à jour" . PHP_EOL;
} else {
    echo "ℹ️ Aucun message système à mettre à jour" . PHP_EOL;
}

echo PHP_EOL . "🎯 CONFIGURATION TERMINÉE!" . PHP_EOL;
echo "Mr Clank est maintenant configuré comme utilisateur système pour les messages automatiques." . PHP_EOL;
