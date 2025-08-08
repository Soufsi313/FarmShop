<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Structure de la table blog_comment_reports ===\n";
$columns = DB::select("DESCRIBE blog_comment_reports");
foreach ($columns as $column) {
    echo "- {$column->Field}: {$column->Type} (Null: {$column->Null}, Key: {$column->Key})\n";
}

echo "\n=== Contraintes foreign key ===\n";
$constraints = DB::select("
    SELECT 
        TABLE_NAME,
        COLUMN_NAME,
        CONSTRAINT_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
    FROM 
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE 
        REFERENCED_TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'blog_comment_reports'
");

foreach ($constraints as $constraint) {
    echo "- {$constraint->COLUMN_NAME} -> {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME} (Constraint: {$constraint->CONSTRAINT_NAME})\n";
}

echo "\n=== Test du problème ===\n";
try {
    // Simuler le problème
    $report = \App\Models\BlogCommentReport::find(22);
    if ($report) {
        echo "Signalement trouvé: ID {$report->id}, Comment ID: {$report->blog_comment_id}\n";
        echo "Commentaire lié: " . ($report->comment ? "Exists (ID: {$report->comment->id})" : "Not found") . "\n";
    } else {
        echo "Signalement 22 non trouvé\n";
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
