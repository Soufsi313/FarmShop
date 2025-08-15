<?php

// Ajouter les traductions manquantes pour la page de test

$additionalTranslations = [
    'fr' => [
        'pages' => [
            'test_title' => 'Test du Système de Traduction',
            'test_subtitle' => 'Vérification du fonctionnement des traductions multilingues',
            'current_language' => 'Langue Actuelle',
            'navigation_test' => 'Test de Navigation',
            'buttons_test' => 'Test des Boutons',
            'ecommerce_test' => 'Test E-commerce',
            'status_test' => 'Test des Statuts',
            'messages_test' => 'Test des Messages',
            'switch_language' => 'Changer de Langue',
        ],
        'time' => [
            'years' => 'ans',
            'months' => 'mois',
            'days' => 'jours',
            'hours' => 'heures',
            'minutes' => 'minutes',
        ],
        'status' => [
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            'pending' => 'En attente',
            'confirmed' => 'Confirmé',
            'cancelled' => 'Annulé',
            'shipped' => 'Expédié',
            'delivered' => 'Livré',
            'returned' => 'Retourné',
            'refunded' => 'Remboursé',
            'preparing' => 'En préparation',
            'validated' => 'Validé',
            'rejected' => 'Rejeté',
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'published' => 'Publié',
            'draft' => 'Brouillon',
            'archived' => 'Archivé',
        ],
        'messages' => [
            'operation_successful' => 'Opération réussie',
            'operation_error' => 'Erreur lors de l\'opération',
            'data_saved' => 'Données sauvegardées',
            'item_deleted' => 'Élément supprimé',
            'item_added' => 'Élément ajouté',
            'changes_saved' => 'Modifications enregistrées',
            'please_wait' => 'Veuillez patienter',
            'loading_in_progress' => 'Chargement en cours',
        ],
    ],
    'en' => [
        'pages' => [
            'test_title' => 'Translation System Test',
            'test_subtitle' => 'Verification of multilingual translation functionality',
            'current_language' => 'Current Language',
            'navigation_test' => 'Navigation Test',
            'buttons_test' => 'Buttons Test',
            'ecommerce_test' => 'E-commerce Test',
            'status_test' => 'Status Test',
            'messages_test' => 'Messages Test',
            'switch_language' => 'Switch Language',
        ],
        'time' => [
            'years' => 'years',
            'months' => 'months',
            'days' => 'days',
            'hours' => 'hours',
            'minutes' => 'minutes',
        ],
        'status' => [
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'returned' => 'Returned',
            'refunded' => 'Refunded',
            'preparing' => 'Preparing',
            'validated' => 'Validated',
            'rejected' => 'Rejected',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'published' => 'Published',
            'draft' => 'Draft',
            'archived' => 'Archived',
        ],
        'messages' => [
            'operation_successful' => 'Operation Successful',
            'operation_error' => 'Operation Error',
            'data_saved' => 'Data Saved',
            'item_deleted' => 'Item Deleted',
            'item_added' => 'Item Added',
            'changes_saved' => 'Changes Saved',
            'please_wait' => 'Please Wait',
            'loading_in_progress' => 'Loading in Progress',
        ],
    ],
    'nl' => [
        'pages' => [
            'test_title' => 'Vertaalsysteem Test',
            'test_subtitle' => 'Verificatie van meertalige vertaalfunctionaliteit',
            'current_language' => 'Huidige Taal',
            'navigation_test' => 'Navigatie Test',
            'buttons_test' => 'Knoppen Test',
            'ecommerce_test' => 'E-commerce Test',
            'status_test' => 'Status Test',
            'messages_test' => 'Berichten Test',
            'switch_language' => 'Taal Wijzigen',
        ],
        'time' => [
            'years' => 'jaar',
            'months' => 'maanden',
            'days' => 'dagen',
            'hours' => 'uren',
            'minutes' => 'minuten',
        ],
        'status' => [
            'in_progress' => 'Bezig',
            'completed' => 'Voltooid',
            'pending' => 'In Afwachting',
            'confirmed' => 'Bevestigd',
            'cancelled' => 'Geannuleerd',
            'shipped' => 'Verzonden',
            'delivered' => 'Bezorgd',
            'returned' => 'Geretourneerd',
            'refunded' => 'Terugbetaald',
            'preparing' => 'Voorbereiden',
            'validated' => 'Gevalideerd',
            'rejected' => 'Afgewezen',
            'active' => 'Actief',
            'inactive' => 'Inactief',
            'published' => 'Gepubliceerd',
            'draft' => 'Concept',
            'archived' => 'Gearchiveerd',
        ],
        'messages' => [
            'operation_successful' => 'Bewerking Succesvol',
            'operation_error' => 'Bewerkingsfout',
            'data_saved' => 'Gegevens Opgeslagen',
            'item_deleted' => 'Item Verwijderd',
            'item_added' => 'Item Toegevoegd',
            'changes_saved' => 'Wijzigingen Opgeslagen',
            'please_wait' => 'Even Wachten',
            'loading_in_progress' => 'Laden Bezig',
        ],
    ],
];

function updateTranslationFile($locale, $translations) {
    $filePath = "lang/{$locale}/app.php";
    
    if (!file_exists($filePath)) {
        echo "❌ Fichier {$filePath} non trouvé\n";
        return false;
    }

    $content = file_get_contents($filePath);
    
    foreach ($translations as $section => $sectionTranslations) {
        $newSection = "\n    // " . ucfirst($section) . "\n";
        $newSection .= "    '{$section}' => [\n";
        
        foreach ($sectionTranslations as $key => $value) {
            $value = str_replace("'", "\'", $value);
            $newSection .= "        '{$key}' => '{$value}',\n";
        }
        
        $newSection .= "    ],\n";
        
        // Ajouter avant la fermeture du fichier
        $content = str_replace("];", $newSection . "];", $content);
    }
    
    file_put_contents($filePath, $content);
    echo "   ✅ {$locale}/app.php mis à jour\n";
    return true;
}

echo "🔧 Ajout des traductions pour la page de test...\n";

foreach (['fr', 'en', 'nl'] as $locale) {
    if (isset($additionalTranslations[$locale])) {
        updateTranslationFile($locale, $additionalTranslations[$locale]);
    }
}

echo "\n✅ Traductions ajoutées avec succès !\n";
echo "🌍 Vous pouvez maintenant tester sur: http://127.0.0.1:8000/translation-test\n";
