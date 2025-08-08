<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Simuler la connexion de l'admin
$admin = User::where('role', 'Admin')->first();
if (!$admin) {
    echo "❌ Aucun admin trouvé avec le rôle 'Admin'\n";
    exit;
}

echo "✅ Admin trouvé: {$admin->name} (ID: {$admin->id}, Rôle: {$admin->role})\n";

// Simuler l'authentification
Auth::login($admin);

// Tester un commentaire spécifique
$comment = BlogComment::find(161);
if (!$comment) {
    echo "❌ Commentaire 161 non trouvé\n";
    exit;
}

echo "✅ Commentaire trouvé: ID {$comment->id}\n";
echo "   - Auteur: " . ($comment->user ? $comment->user->name : 'Guest') . "\n";
echo "   - Contenu: " . substr($comment->content, 0, 50) . "...\n";
echo "   - Statut: {$comment->status}\n";
echo "   - Can Delete: " . ($comment->can_delete ? 'OUI' : 'NON') . "\n";
echo "   - Can Edit: " . ($comment->can_edit ? 'OUI' : 'NON') . "\n";

// Tester la suppression
if ($comment->can_delete) {
    echo "✅ L'admin peut supprimer ce commentaire\n";
} else {
    echo "❌ L'admin ne peut pas supprimer ce commentaire\n";
}
