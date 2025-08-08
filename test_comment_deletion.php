<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Simuler la connexion de l'admin
$admin = User::where('role', 'Admin')->first();
Auth::login($admin);

echo "✅ Admin connecté: {$admin->name}\n";

// Trouver un commentaire à tester
$comment = BlogComment::first();
if (!$comment) {
    echo "❌ Aucun commentaire trouvé\n";
    exit;
}

echo "📝 Commentaire trouvé: ID {$comment->id}\n";
echo "   - Auteur: " . ($comment->user ? $comment->user->name : 'Guest') . "\n";
echo "   - Can Delete: " . ($comment->can_delete ? 'OUI' : 'NON') . "\n";

if ($comment->can_delete) {
    echo "🗑️ Suppression du commentaire...\n";
    try {
        $comment->delete();
        echo "✅ Commentaire supprimé avec succès\n";
    } catch (Exception $e) {
        echo "❌ Erreur lors de la suppression: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Suppression impossible\n";
}
