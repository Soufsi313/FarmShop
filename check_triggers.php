<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Recherche de triggers ===\n";
try {
    $triggers = DB::select("SHOW TRIGGERS");
    if (empty($triggers)) {
        echo "Aucun trigger trouvé\n";
    } else {
        foreach ($triggers as $trigger) {
            echo "Trigger: {$trigger->Trigger} sur {$trigger->Table} ({$trigger->Event})\n";
            echo "Statement: {$trigger->Statement}\n\n";
        }
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Recherche de procédures stockées ===\n";
try {
    $procedures = DB::select("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
    if (empty($procedures)) {
        echo "Aucune procédure stockée trouvée\n";
    } else {
        foreach ($procedures as $procedure) {
            echo "Procédure: {$procedure->Name}\n";
        }
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== Recherche dans le code d'une requête qui utilise comment_id ===\n";
// Regardons s'il y a du SQL brut quelque part
echo "Nous devons chercher dans les fichiers PHP une requête qui utilise 'comment_id'\n";
