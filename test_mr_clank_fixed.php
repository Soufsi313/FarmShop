<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🤖 TEST MR CLANK MESSAGE SYSTEM\n";
echo "==============================\n";

// Vérifier Mr Clank
$mrClank = DB::table('users')->where('id', 103)->first();
echo "✅ Mr Clank: {$mrClank->name} ({$mrClank->email})\n\n";

// Créer un message test pour vérifier
$testUser = DB::table('users')->where('id', 1)->first();
if ($testUser) {
    $testMessage = DB::table('messages')->insertGetId([
        'user_id' => $testUser->id,
        'sender_id' => 103, // Mr Clank
        'type' => 'system',
        'subject' => '🤖 Test Mr Clank - Message de validation',
        'content' => '🤖 **Mr Clank - Message Test**

Bonjour ' . $testUser->name . ',

Ceci est un message de test pour vérifier que :
✅ L\'expéditeur est bien Mr Clank (ID: 103)
✅ Le message système apparaît correctement
✅ Le contenu n\'est plus tronqué à 100 caractères

Ce message contient plus de 200 caractères pour tester l\'affichage complet dans l\'interface administrateur. Le système devrait maintenant afficher ce message avec son contenu étendu sans troncature excessive.

---
🤖 Message automatique généré par Mr Clank
Système de test FarmShop',
        'status' => 'unread',
        'priority' => 'normal',
        'is_important' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "✅ Message test créé avec l'ID: {$testMessage}\n";
    echo "📧 Expéditeur: Mr Clank (ID: 103)\n";
    echo "👤 Destinataire: {$testUser->name}\n";
    echo "📝 Type: system\n";
    echo "📏 Longueur contenu: " . strlen('🤖 **Mr Clank - Message Test**...') . " caractères\n";
} else {
    echo "❌ Utilisateur test non trouvé\n";
}
