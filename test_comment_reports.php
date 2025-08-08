<?php
require_once 'vendor/autoload.php';

use App\Models\BlogComment;
use App\Models\BlogCommentReport;
use App\Models\User;
use App\Models\BlogPost;

// Chargement de Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Test du système de signalements\n\n";

// Vérifier s'il y a des signalements en attente
$pendingReports = BlogCommentReport::where('status', 'pending')->count();
echo "📊 Signalements en attente : {$pendingReports}\n";

$resolvedReports = BlogCommentReport::where('status', 'resolved')->count();
echo "✅ Signalements résolus : {$resolvedReports}\n";

$dismissedReports = BlogCommentReport::where('status', 'dismissed')->count();
echo "❌ Signalements rejetés : {$dismissedReports}\n\n";

// Afficher quelques signalements récents
echo "📋 Derniers signalements :\n";
$recentReports = BlogCommentReport::with(['comment.user', 'reporter'])
    ->latest()
    ->take(5)
    ->get();

foreach ($recentReports as $report) {
    $commentUser = $report->comment->user->name ?? 'Utilisateur supprimé';
    $reporterName = $report->reporter->name ?? 'Reporter supprimé';
    
    echo "- ID {$report->id} | Statut: {$report->status} | Raison: {$report->reason}\n";
    echo "  Commentaire de: {$commentUser} | Signalé par: {$reporterName}\n";
    echo "  Action prise: " . ($report->action_taken ?? 'Aucune') . "\n\n";
}

// Si pas de signalements, proposer d'en créer un de test
if ($pendingReports === 0) {
    echo "💡 Aucun signalement en attente. Voulez-vous créer un signalement de test ?\n";
    echo "Pour tester le système, connectez-vous en admin et allez sur la page des signalements.\n";
}

echo "\n🔗 URL Admin : http://localhost:8000/admin/blog/comments/reports\n";
