<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$msg = DB::table('messages')->where('id', 131)->first();
echo "📏 ANALYSE MESSAGE TEST\n";
echo "======================\n";
echo "Longueur exacte: " . strlen($msg->content) . " caractères\n\n";
echo "Contenu complet:\n";
echo "================\n";
echo $msg->content . "\n\n";
echo "Limite actuelle vue liste: 400 caractères\n";
echo "Limite actuelle vue détail: 500 caractères\n";
echo "\nAffichage avec limite 400:\n";
echo "==========================\n";
echo Str::limit($msg->content, 400) . "\n";
