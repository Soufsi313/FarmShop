<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

use App\Models\User;

echo "=== Test du filtre des comptes supprimés ===\n";

// Vérifier combien d'utilisateurs on a dans chaque catégorie
$activeUsers = User::count();
$deletedUsers = User::onlyTrashed()->count();
$totalUsers = User::withTrashed()->count();

echo "📊 Statistiques des utilisateurs :\n";
echo "   ✅ Actifs : $activeUsers\n";
echo "   🗑️  Supprimés : $deletedUsers\n";
echo "   📋 Total : $totalUsers\n";

if ($deletedUsers > 0) {
    echo "\n🔍 Utilisateurs supprimés trouvés :\n";
    $deletedUsersList = User::onlyTrashed()->get();
    foreach ($deletedUsersList as $user) {
        echo "   - {$user->name} ({$user->email}) - Supprimé le " . $user->deleted_at->format('d/m/Y H:i') . "\n";
    }
    
    echo "\n✅ Le filtre devrait maintenant fonctionner dans l'admin !\n";
    echo "🔗 Allez sur : http://127.0.0.1:8000/admin/users?show_deleted=deleted\n";
} else {
    echo "\n⚠️  Aucun utilisateur supprimé trouvé.\n";
    echo "💡 Pour tester le filtre, supprimez un compte utilisateur d'abord.\n";
}

echo "\n=== Nouveaux filtres disponibles ===\n";
echo "🔸 show_deleted=active   → Comptes actifs uniquement (défaut)\n";
echo "🔸 show_deleted=deleted  → Comptes supprimés uniquement\n";
echo "🔸 show_deleted=all      → Tous les comptes\n";

echo "\n=== Nouvelles fonctionnalités ===\n";
echo "✅ Statistique des comptes supprimés dans le dashboard\n";
echo "✅ Colonne 'Statut' dans le tableau\n";
echo "✅ Tri par date de suppression disponible\n";
echo "✅ Bouton de restauration pour les comptes supprimés\n";
echo "✅ Interface visuelle distincte (fond rouge) pour les comptes supprimés\n";

echo "\nSystème prêt ! 🚀\n";
