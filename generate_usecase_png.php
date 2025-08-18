<?php

echo "=== GÉNÉRATION DU DIAGRAMME USE CASE PNG ===\n\n";

// Définir les acteurs et leurs fonctionnalités
$useCases = [
    'Visiteur' => [
        'color' => [52, 152, 219], // #3498db
        'description' => 'Utilisateur non connecté',
        'cases' => [
            'Consulter le catalogue de produits',
            'Voir les détails d\'un produit',
            'Parcourir les catégories',
            'Consulter les articles de blog',
            'S\'inscrire sur le site',
            'Se connecter',
            'Consulter les pages d\'information',
            'Accepter/Refuser les cookies'
        ]
    ],
    'User' => [
        'color' => [46, 204, 113], // #2ecc71
        'description' => 'Utilisateur connecté',
        'cases' => [
            // Gestion du profil
            'Gérer son profil utilisateur',
            'Modifier ses informations personnelles',
            'Supprimer son compte',
            'Gérer ses préférences de cookies',
            
            // Catalogue et produits
            'Ajouter des produits aux favoris',
            'Gérer sa wishlist',
            'Noter et commenter les produits',
            
            // Panier et commandes
            'Ajouter des produits au panier',
            'Gérer son panier d\'achat',
            'Passer une commande',
            'Suivre ses commandes',
            'Demander un retour de produit',
            'Télécharger ses factures',
            
            // Locations
            'Ajouter des produits au panier de location',
            'Réserver des produits en location',
            'Gérer ses locations en cours',
            'Prolonger une location',
            'Retourner des produits loués',
            'Consulter l\'historique de ses locations',
            
            // Communication
            'Recevoir des messages système',
            'Gérer ses notifications',
            'S\'abonner/Se désabonner à la newsletter',
            
            // Blog
            'Commenter les articles de blog',
            'Signaler des commentaires inappropriés'
        ]
    ],
    'Admin' => [
        'color' => [231, 76, 60], // #e74c3c
        'description' => 'Administrateur du système',
        'cases' => [
            // Dashboard et statistiques
            'Accéder au tableau de bord',
            'Consulter les statistiques de vente',
            'Générer des rapports',
            
            // Gestion des utilisateurs
            'Gérer les comptes utilisateurs',
            'Modifier les rôles utilisateurs',
            'Supprimer des comptes utilisateurs',
            'Consulter l\'activité des utilisateurs',
            
            // Gestion du catalogue
            'Gérer les produits',
            'Ajouter/Modifier/Supprimer des produits',
            'Gérer les catégories de produits',
            'Gérer les catégories de location',
            'Gérer les offres spéciales',
            
            // Gestion des stocks
            'Surveiller les niveaux de stock',
            'Recevoir des alertes de stock faible',
            'Effectuer des réapprovisionnements',
            'Générer des rapports de stock',
            
            // Gestion des commandes
            'Traiter les commandes d\'achat',
            'Confirmer/Annuler des commandes',
            'Gérer les retours de produits',
            'Approuver/Rejeter les demandes de retour',
            
            // Gestion des locations
            'Traiter les demandes de location',
            'Confirmer les locations',
            'Gérer les retours de location',
            'Effectuer les inspections de retour',
            'Calculer les pénalités de retard',
            'Gérer les cautions',
            
            // Communication et contenu
            'Gérer les articles de blog',
            'Modérer les commentaires',
            'Gérer les catégories de blog',
            'Envoyer des newsletters',
            'Gérer les abonnements newsletter',
            'Envoyer des messages aux utilisateurs',
            
            // Administration système
            'Gérer les paramètres du site',
            'Consulter les logs de cookies',
            'Gérer les consentements RGPD',
            'Configurer les notifications email'
        ]
    ],
    'Stripe' => [
        'color' => [155, 89, 182], // #9b59b6
        'description' => 'Service de paiement externe',
        'cases' => [
            'Traiter les paiements',
            'Gérer les autorisations de caution',
            'Capturer les cautions',
            'Effectuer les remboursements',
            'Notifier les échecs de paiement',
            'Gérer les webhooks de paiement'
        ]
    ]
];

// Fonction pour dessiner un acteur stick figure
function drawActor($image, $x, $y, $color) {
    // Tête (cercle)
    imageellipse($image, $x + 30, $y + 15, 30, 30, $color);
    
    // Corps (ligne verticale)
    imageline($image, $x + 30, $y + 30, $x + 30, $y + 70, $color);
    
    // Bras (ligne horizontale)
    imageline($image, $x + 10, $y + 45, $x + 50, $y + 45, $color);
    
    // Jambes
    imageline($image, $x + 30, $y + 70, $x + 15, $y + 95, $color);
    imageline($image, $x + 30, $y + 70, $x + 45, $y + 95, $color);
}

// Fonction pour dessiner une ellipse de cas d'usage
function drawUseCase($image, $x, $y, $width, $height, $text, $textColor, $borderColor) {
    // Dessiner l'ellipse
    imageellipse($image, $x + $width/2, $y + $height/2, $width, $height, $borderColor);
    
    // Remplir l'ellipse avec du blanc
    $white = imagecolorallocate($image, 248, 249, 250);
    imagefilledellipse($image, $x + $width/2, $y + $height/2, $width-2, $height-2, $white);
    imageellipse($image, $x + $width/2, $y + $height/2, $width, $height, $borderColor);
    
    // Ajouter le texte (tronqué si nécessaire)
    $maxLength = 28;
    if (strlen($text) > $maxLength) {
        $text = substr($text, 0, $maxLength - 3) . '...';
    }
    
    // Calculer la position du texte pour le centrer
    $textWidth = strlen($text) * 6; // Approximation de la largeur du texte
    $textX = $x + ($width - $textWidth) / 2;
    $textY = $y + $height / 2 + 3;
    
    imagestring($image, 2, $textX, $textY, $text, $textColor);
}

// Fonction pour générer le diagramme PNG
function generateUseCasePNG($useCases) {
    $diagramWidth = 1600;
    $diagramHeight = 1200;
    
    // Créer l'image
    $image = imagecreatetruecolor($diagramWidth, $diagramHeight);
    
    // Couleurs de base
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $gray = imagecolorallocate($image, 102, 102, 102);
    $lightGray = imagecolorallocate($image, 220, 220, 220);
    $titleColor = imagecolorallocate($image, 44, 90, 160);
    $borderColor = imagecolorallocate($image, 102, 102, 102);
    
    // Fond blanc
    imagefill($image, 0, 0, $white);
    
    // Titre
    $titleText = "FarmShop - Diagramme des Cas d'Usage";
    $titleX = ($diagramWidth - strlen($titleText) * 12) / 2;
    imagestring($image, 5, $titleX, 20, $titleText, $titleColor);
    
    // Sous-titre
    $subtitleText = "Systeme de e-commerce avec location d'equipements agricoles";
    $subtitleX = ($diagramWidth - strlen($subtitleText) * 8) / 2;
    imagestring($image, 3, $subtitleX, 50, $subtitleText, $gray);
    
    // Zone du système (rectangle pointillé simulé par des lignes)
    $systemX = 300;
    $systemY = 100;
    $systemWidth = $diagramWidth - 600;
    $systemHeight = $diagramHeight - 300;
    
    // Bordure du système (lignes pointillées simulées)
    for ($i = 0; $i < $systemWidth; $i += 10) {
        if ($i % 20 < 10) {
            imageline($image, $systemX + $i, $systemY, $systemX + $i + 5, $systemY, $borderColor);
            imageline($image, $systemX + $i, $systemY + $systemHeight, $systemX + $i + 5, $systemY + $systemHeight, $borderColor);
        }
    }
    for ($i = 0; $i < $systemHeight; $i += 10) {
        if ($i % 20 < 10) {
            imageline($image, $systemX, $systemY + $i, $systemX, $systemY + $i + 5, $borderColor);
            imageline($image, $systemX + $systemWidth, $systemY + $i, $systemX + $systemWidth, $systemY + $i + 5, $borderColor);
        }
    }
    
    // Label du système
    imagestring($image, 4, $systemX + 10, $systemY + 10, "Systeme FarmShop", $gray);
    
    // Positions des acteurs
    $actorPositions = [
        'Visiteur' => ['x' => 50, 'y' => 150],
        'User' => ['x' => 50, 'y' => 350],
        'Admin' => ['x' => $diagramWidth - 120, 'y' => 250],
        'Stripe' => ['x' => $diagramWidth / 2 - 30, 'y' => $diagramHeight - 150]
    ];
    
    // Dessiner les acteurs
    foreach ($useCases as $actorName => $actorData) {
        $pos = $actorPositions[$actorName];
        $actorColor = imagecolorallocate($image, $actorData['color'][0], $actorData['color'][1], $actorData['color'][2]);
        
        // Dessiner l'acteur
        drawActor($image, $pos['x'], $pos['y'], $black);
        
        // Nom de l'acteur
        $nameX = $pos['x'] + 30 - (strlen($actorName) * 6) / 2;
        imagestring($image, 3, $nameX, $pos['y'] + 105, $actorName, $black);
        
        // Description
        $descX = $pos['x'] + 30 - (strlen($actorData['description']) * 4) / 2;
        imagestring($image, 2, $descX, $pos['y'] + 125, $actorData['description'], $gray);
    }
    
    // Organiser et dessiner les cas d'usage
    $allCases = [];
    foreach ($useCases as $actorName => $actorData) {
        foreach ($actorData['cases'] as $case) {
            $allCases[] = [
                'name' => $case,
                'actor' => $actorName,
                'color' => $actorData['color']
            ];
        }
    }
    
    // Disposer les cas d'usage en grille
    $casesPerRow = 4;
    $caseWidth = 180;
    $caseHeight = 35;
    $caseSpacingX = ($systemWidth - 40) / $casesPerRow;
    $caseSpacingY = 50;
    
    $maxVisibleCases = min(count($allCases), 48); // Limiter pour la lisibilité
    
    for ($i = 0; $i < $maxVisibleCases; $i++) {
        $case = $allCases[$i];
        $row = floor($i / $casesPerRow);
        $col = $i % $casesPerRow;
        
        $x = $systemX + 20 + ($col * $caseSpacingX);
        $y = $systemY + 50 + ($row * $caseSpacingY);
        
        // S'assurer que le cas d'usage reste dans la zone
        if ($y + $caseHeight > $systemY + $systemHeight - 20) {
            break;
        }
        
        // Dessiner le cas d'usage
        drawUseCase($image, $x, $y, $caseWidth, $caseHeight, $case['name'], $black, $black);
        
        // Ligne de connexion vers l'acteur
        $actorPos = $actorPositions[$case['actor']];
        $actorCenterX = $actorPos['x'] + 30;
        $actorCenterY = $actorPos['y'] + 50;
        $caseCenterX = $x + $caseWidth / 2;
        $caseCenterY = $y + $caseHeight / 2;
        
        imageline($image, $actorCenterX, $actorCenterY, $caseCenterX, $caseCenterY, $gray);
    }
    
    // Légende
    $legendY = $diagramHeight - 120;
    imagestring($image, 4, 50, $legendY, "Acteurs :", $black);
    
    $legendY += 25;
    foreach ($useCases as $actorName => $actorData) {
        $actorColor = imagecolorallocate($image, $actorData['color'][0], $actorData['color'][1], $actorData['color'][2]);
        
        // Petit cercle coloré
        imagefilledellipse($image, 60, $legendY + 5, 10, 10, $actorColor);
        
        // Texte de la légende
        $legendText = "{$actorName} - {$actorData['description']} (" . count($actorData['cases']) . " cas)";
        imagestring($image, 2, 75, $legendY, $legendText, $black);
        
        $legendY += 20;
    }
    
    // Statistiques en haut à droite
    $statsX = $diagramWidth - 350;
    $statsY = 100;
    $totalCases = array_sum(array_map(function($actor) { return count($actor['cases']); }, $useCases));
    
    imagestring($image, 3, $statsX, $statsY, "Statistiques :", $titleColor);
    imagestring($image, 2, $statsX, $statsY + 20, "Total acteurs: " . count($useCases), $black);
    imagestring($image, 2, $statsX, $statsY + 35, "Total cas d'usage: {$totalCases}", $black);
    imagestring($image, 2, $statsX, $statsY + 50, "Cas affiches: {$maxVisibleCases}", $black);
    
    return $image;
}

// Générer le diagramme PNG
echo "🎨 Génération du diagramme use case PNG...\n";
$image = generateUseCasePNG($useCases);

// Créer le répertoire si nécessaire
if (!is_dir('docs/diagrams')) {
    mkdir('docs/diagrams', 0755, true);
}

// Sauvegarder le fichier PNG
$pngFilename = "docs/diagrams/farmshop_usecase_diagram.png";

if (imagepng($image, $pngFilename)) {
    echo "✅ Diagramme use case PNG généré avec succès !\n";
    echo "   📄 PNG: {$pngFilename}\n\n";
    
    echo "📊 Statistiques du diagramme :\n";
    echo "   • Acteurs : " . count($useCases) . "\n";
    foreach ($useCases as $actorName => $actorData) {
        echo "   • {$actorName} : " . count($actorData['cases']) . " cas d'usage\n";
    }
    echo "   • Total cas d'usage : " . array_sum(array_map(function($actor) { return count($actor['cases']); }, $useCases)) . "\n";
    
    echo "\n🎉 Le diagramme use case PNG est prêt ! Vous pouvez l'ouvrir directement sur votre PC.\n";
} else {
    echo "❌ Erreur lors de la génération du PNG\n";
}

// Libérer la mémoire
imagedestroy($image);

?>
