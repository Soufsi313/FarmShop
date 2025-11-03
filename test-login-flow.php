<?php
// Script de test direct pour simuler le workflow login
require_once 'vendor/autoload.php';

// Simuler une connexion en tant qu'invité puis login
session_start();

echo "🔍 Test du workflow de login complet\n\n";

// 1. Simuler l'état invité - créer un cookie local
echo "1. État INVITÉ - Création cookie localStorage simulé\n";
echo "   localStorage.setItem('cookie_consent_given', 'false')\n";
echo "   localStorage.setItem('cookie_consent_date', null)\n\n";

// 2. Simuler la connexion - ajouter l'indicateur de session
echo "2. CONNEXION - Ajout indicateur session\n";
$_SESSION['auth_status_changed'] = true;
echo "   session('auth_status_changed') = true\n";
echo "   Session ID: " . session_id() . "\n\n";

// 3. Tester l'API de synchronisation (simuler la requête)
echo "3. TEST API de synchronisation\n";
echo "   POST /api/cookies/sync-auth-status\n";
echo "   Cookie: XSRF-TOKEN, laravel_session\n";

// 4. Afficher les cookies simulés dans la base de données
echo "\n4. État de la base de données (simulé)\n";
echo "   Table: cookie_consents\n";
echo "   user_id: null (invité) -> user_id: 1 (connecté)\n";
echo "   consent_given: false -> consent_given: false (à synchroniser)\n";

echo "\n5. Résultat attendu de l'API:\n";
echo "   {\n";
echo "     \"success\": true,\n";
echo "     \"consent_required\": true,\n";
echo "     \"message\": \"Synchronization completed\"\n";
echo "   }\n";

echo "\n6. Action JavaScript attendue:\n";
echo "   FarmShop.cookieConsent.show() -> Afficher le bandeau\n";

echo "\n✅ Test terminé\n";
?>
