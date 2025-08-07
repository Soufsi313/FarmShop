<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\Cookie;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);

echo "=== DIAGNOSTIC AUTHENTIFICATION COOKIES ===\n\n";

// Vérifier les utilisateurs existants
$users = User::all();
echo "👥 UTILISATEURS DANS LA BASE :\n";
foreach ($users as $user) {
    echo "- ID: {$user->id}, Email: {$user->email}, Nom: {$user->name}\n";
}

echo "\n🍪 COOKIES DANS LA BASE :\n";
$cookies = Cookie::with('user')->orderBy('created_at', 'desc')->take(10)->get();

foreach ($cookies as $cookie) {
    echo "- ID: {$cookie->id}\n";
    echo "  User ID: " . ($cookie->user_id ?? 'NULL') . "\n";
    echo "  User: " . ($cookie->user ? $cookie->user->email : 'VISITEUR') . "\n";
    echo "  Session ID: " . ($cookie->session_id ?? 'NULL') . "\n";
    echo "  IP: {$cookie->ip_address}\n";
    echo "  Status: {$cookie->status}\n";
    echo "  Créé le: {$cookie->created_at}\n";
    echo "  ----------------------------------------\n";
}

// Vérifier les cookies pour chaque utilisateur
echo "\n📊 COOKIES PAR UTILISATEUR :\n";
foreach ($users as $user) {
    $userCookies = Cookie::where('user_id', $user->id)->count();
    echo "- {$user->email}: {$userCookies} cookie(s)\n";
}

// Vérifier les cookies visiteurs
$guestCookies = Cookie::whereNull('user_id')->count();
echo "- Visiteurs: {$guestCookies} cookie(s)\n";

echo "\n✅ Diagnostic terminé\n";
