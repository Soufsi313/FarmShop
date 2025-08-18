<?php

echo "=== GÉNÉRATION DU DIAGRAMME USE CASE ===\n\n";

// Définir les acteurs et leurs fonctionnalités
$useCases = [
    'Visiteur' => [
        'color' => '#3498db',
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
        'color' => '#2ecc71',
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
        'color' => '#e74c3c',
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
        'color' => '#9b59b6',
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

// Fonction pour générer le diagramme SVG
function generateUseCaseDiagram($useCases) {
    $diagramWidth = 1400;
    $diagramHeight = 1000;
    $actorWidth = 120;
    $actorHeight = 150;
    $caseWidth = 200;
    $caseHeight = 30;
    $margin = 50;
    
    // Calculer les positions des acteurs
    $actorPositions = [];
    $actorNames = array_keys($useCases);
    $actorCount = count($actorNames);
    
    // Positionnement des acteurs : Visiteur et User à gauche, Admin à droite, Stripe en bas
    $actorPositions['Visiteur'] = ['x' => 50, 'y' => 100];
    $actorPositions['User'] = ['x' => 50, 'y' => 300];
    $actorPositions['Admin'] = ['x' => $diagramWidth - 150, 'y' => 200];
    $actorPositions['Stripe'] = ['x' => $diagramWidth / 2 - 60, 'y' => $diagramHeight - 200];
    
    // Zone centrale pour les cas d'usage
    $caseAreaX = 250;
    $caseAreaY = 80;
    $caseAreaWidth = $diagramWidth - 500;
    $caseAreaHeight = $diagramHeight - 300;
    
    // Générer le SVG
    $svg = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $svg .= "<svg width=\"{$diagramWidth}\" height=\"{$diagramHeight}\" xmlns=\"http://www.w3.org/2000/svg\">\n";
    
    // Styles CSS
    $svg .= "<style>\n";
    $svg .= "  .actor-circle { fill: white; stroke: #333; stroke-width: 2; }\n";
    $svg .= "  .actor-text { fill: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; text-anchor: middle; }\n";
    $svg .= "  .usecase-oval { fill: #f8f9fa; stroke: #333; stroke-width: 1.5; }\n";
    $svg .= "  .usecase-text { fill: #333; font-family: Arial, sans-serif; font-size: 10px; text-anchor: middle; }\n";
    $svg .= "  .system-boundary { fill: none; stroke: #666; stroke-width: 2; stroke-dasharray: 5,5; }\n";
    $svg .= "  .connection-line { stroke: #666; stroke-width: 1; }\n";
    $svg .= "  .title-text { fill: #2c5aa0; font-family: Arial, sans-serif; font-size: 20px; font-weight: bold; text-anchor: middle; }\n";
    $svg .= "  .subtitle-text { fill: #666; font-family: Arial, sans-serif; font-size: 14px; text-anchor: middle; }\n";
    $svg .= "</style>\n\n";
    
    // Titre
    $svg .= "<text x=\"" . ($diagramWidth / 2) . "\" y=\"30\" class=\"title-text\">FarmShop - Diagramme des Cas d'Usage</text>\n";
    $svg .= "<text x=\"" . ($diagramWidth / 2) . "\" y=\"50\" class=\"subtitle-text\">Système de e-commerce avec location d'équipements agricoles</text>\n\n";
    
    // Frontière du système
    $svg .= "<rect x=\"{$caseAreaX}\" y=\"{$caseAreaY}\" width=\"{$caseAreaWidth}\" height=\"{$caseAreaHeight}\" class=\"system-boundary\"/>\n";
    $svg .= "<text x=\"" . ($caseAreaX + 10) . "\" y=\"" . ($caseAreaY + 20) . "\" style=\"fill: #666; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold;\">Système FarmShop</text>\n\n";
    
    // Dessiner les acteurs
    foreach ($useCases as $actorName => $actorData) {
        $pos = $actorPositions[$actorName];
        
        // Cercle de l'acteur (tête)
        $svg .= "<circle cx=\"" . ($pos['x'] + 60) . "\" cy=\"" . ($pos['y'] + 20) . "\" r=\"15\" class=\"actor-circle\"/>\n";
        
        // Corps de l'acteur (ligne verticale)
        $svg .= "<line x1=\"" . ($pos['x'] + 60) . "\" y1=\"" . ($pos['y'] + 35) . "\" x2=\"" . ($pos['x'] + 60) . "\" y2=\"" . ($pos['y'] + 80) . "\" stroke=\"#333\" stroke-width=\"2\"/>\n";
        
        // Bras (ligne horizontale)
        $svg .= "<line x1=\"" . ($pos['x'] + 40) . "\" y1=\"" . ($pos['y'] + 50) . "\" x2=\"" . ($pos['x'] + 80) . "\" y2=\"" . ($pos['y'] + 50) . "\" stroke=\"#333\" stroke-width=\"2\"/>\n";
        
        // Jambes
        $svg .= "<line x1=\"" . ($pos['x'] + 60) . "\" y1=\"" . ($pos['y'] + 80) . "\" x2=\"" . ($pos['x'] + 45) . "\" y2=\"" . ($pos['y'] + 110) . "\" stroke=\"#333\" stroke-width=\"2\"/>\n";
        $svg .= "<line x1=\"" . ($pos['x'] + 60) . "\" y1=\"" . ($pos['y'] + 80) . "\" x2=\"" . ($pos['x'] + 75) . "\" y2=\"" . ($pos['y'] + 110) . "\" stroke=\"#333\" stroke-width=\"2\"/>\n";
        
        // Nom de l'acteur
        $svg .= "<text x=\"" . ($pos['x'] + 60) . "\" y=\"" . ($pos['y'] + 130) . "\" class=\"actor-text\">{$actorName}</text>\n";
        
        // Description de l'acteur
        $svg .= "<text x=\"" . ($pos['x'] + 60) . "\" y=\"" . ($pos['y'] + 145) . "\" style=\"fill: #666; font-family: Arial, sans-serif; font-size: 9px; text-anchor: middle;\">{$actorData['description']}</text>\n";
    }
    
    // Dessiner les cas d'usage regroupés par zone
    $currentY = $caseAreaY + 50;
    $currentX = $caseAreaX + 30;
    $maxCasesPerRow = 3;
    $caseCount = 0;
    
    // Compter le total des cas d'usage
    $totalCases = 0;
    foreach ($useCases as $actorData) {
        $totalCases += count($actorData['cases']);
    }
    
    // Organiser les cas d'usage par importance/fréquence
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
    $caseSpacingX = ($caseAreaWidth - 60) / $casesPerRow;
    $caseSpacingY = 45;
    
    foreach ($allCases as $index => $case) {
        $row = floor($index / $casesPerRow);
        $col = $index % $casesPerRow;
        
        $x = $caseAreaX + 30 + ($col * $caseSpacingX);
        $y = $caseAreaY + 60 + ($row * $caseSpacingY);
        
        // Limiter à la zone visible
        if ($y + 30 > $caseAreaY + $caseAreaHeight - 20) {
            break;
        }
        
        // Dessiner l'ellipse du cas d'usage
        $ellipseWidth = min(180, $caseSpacingX - 10);
        $ellipseHeight = 25;
        
        $svg .= "<ellipse cx=\"" . ($x + $ellipseWidth/2) . "\" cy=\"" . ($y + $ellipseHeight/2) . "\" rx=\"" . ($ellipseWidth/2) . "\" ry=\"" . ($ellipseHeight/2) . "\" class=\"usecase-oval\"/>\n";
        
        // Texte du cas d'usage (tronqué si nécessaire)
        $caseText = strlen($case['name']) > 25 ? substr($case['name'], 0, 22) . '...' : $case['name'];
        $svg .= "<text x=\"" . ($x + $ellipseWidth/2) . "\" y=\"" . ($y + $ellipseHeight/2 + 3) . "\" class=\"usecase-text\">{$caseText}</text>\n";
        
        // Ligne de connexion vers l'acteur
        $actorPos = $actorPositions[$case['actor']];
        $actorCenterX = $actorPos['x'] + 60;
        $actorCenterY = $actorPos['y'] + 60;
        $caseCenterX = $x + $ellipseWidth/2;
        $caseCenterY = $y + $ellipseHeight/2;
        
        $svg .= "<line x1=\"{$actorCenterX}\" y1=\"{$actorCenterY}\" x2=\"{$caseCenterX}\" y2=\"{$caseCenterY}\" class=\"connection-line\"/>\n";
    }
    
    // Légende des acteurs
    $legendY = $diagramHeight - 120;
    $svg .= "<text x=\"50\" y=\"{$legendY}\" style=\"fill: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold;\">Acteurs :</text>\n";
    
    $legendX = 50;
    foreach ($useCases as $actorName => $actorData) {
        $legendY += 20;
        $svg .= "<circle cx=\"" . ($legendX + 10) . "\" cy=\"" . ($legendY - 5) . "\" r=\"5\" fill=\"{$actorData['color']}\"/>\n";
        $svg .= "<text x=\"" . ($legendX + 25) . "\" y=\"{$legendY}\" style=\"fill: #333; font-family: Arial, sans-serif; font-size: 12px;\">{$actorName} - {$actorData['description']}</text>\n";
    }
    
    $svg .= "</svg>\n";
    
    return $svg;
}

// Générer le diagramme
echo "🎨 Génération du diagramme use case...\n";
$svg = generateUseCaseDiagram($useCases);

// Créer le répertoire si nécessaire
if (!is_dir('docs/diagrams')) {
    mkdir('docs/diagrams', 0755, true);
}

// Sauvegarder le fichier SVG
$svgFilename = "docs/diagrams/farmshop_usecase_diagram.svg";
file_put_contents($svgFilename, $svg);

// Créer une version HTML pour la visualisation
$htmlContent = "<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>FarmShop - Diagramme des Cas d'Usage</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #f5f5f5; 
        }
        .container { 
            max-width: 100%; 
            overflow: auto; 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .description { 
            color: #666; 
            font-style: italic; 
            margin-bottom: 30px; 
            text-align: center;
        }
        svg { 
            max-width: 100%; 
            height: auto; 
            border: 1px solid #ddd; 
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h1 style=\"color: #2c5aa0;\">FarmShop - Diagramme des Cas d'Usage</h1>
            <p class=\"description\">Système de e-commerce avec location d'équipements agricoles</p>
        </div>
        
        <div class=\"stats\">
            <div class=\"stat-item\">
                <div class=\"stat-number\">4</div>
                <div class=\"stat-label\">Acteurs</div>
            </div>
            <div class=\"stat-item\">
                <div class=\"stat-number\">" . array_sum(array_map(function($actor) { return count($actor['cases']); }, $useCases)) . "</div>
                <div class=\"stat-label\">Cas d'Usage</div>
            </div>
            <div class=\"stat-item\">
                <div class=\"stat-number\">" . count($useCases['User']['cases']) . "</div>
                <div class=\"stat-label\">Fonctions Utilisateur</div>
            </div>
            <div class=\"stat-item\">
                <div class=\"stat-number\">" . count($useCases['Admin']['cases']) . "</div>
                <div class=\"stat-label\">Fonctions Admin</div>
            </div>
        </div>
        
        {$svg}
        
        <div style=\"margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;\">
            <h3>Résumé des Fonctionnalités par Acteur :</h3>
            <ul>
                <li><strong>Visiteur :</strong> Navigation, consultation, inscription</li>
                <li><strong>User :</strong> Gestion profil, commandes, locations, wishlist, blog</li>
                <li><strong>Admin :</strong> Dashboard, gestion complète du système</li>
                <li><strong>Stripe :</strong> Traitement sécurisé des paiements</li>
            </ul>
        </div>
    </div>
</body>
</html>";

$htmlFilename = "docs/diagrams/farmshop_usecase_diagram.html";
file_put_contents($htmlFilename, $htmlContent);

echo "✅ Diagramme use case généré avec succès !\n";
echo "   📄 SVG: {$svgFilename}\n";
echo "   🌐 HTML: {$htmlFilename}\n\n";

echo "📊 Statistiques du diagramme :\n";
echo "   • Acteurs : " . count($useCases) . "\n";
foreach ($useCases as $actorName => $actorData) {
    echo "   • {$actorName} : " . count($actorData['cases']) . " cas d'usage\n";
}
echo "   • Total cas d'usage : " . array_sum(array_map(function($actor) { return count($actor['cases']); }, $useCases)) . "\n";

echo "\n🎉 Le diagramme use case est prêt ! Vous pouvez l'ouvrir dans votre navigateur.\n";

?>
