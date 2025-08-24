# Script PowerShell pour générer les diagrammes UML
# Génération automatique des images à partir des fichiers .puml

param(
    [string]$InputDir = "docs\diagrams",
    [string]$OutputDir = "docs\diagrams\images",
    [string]$Format = "png"
)

Write-Host "🎨 Génération des diagrammes UML pour FarmShop" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Green

# Créer le dossier de sortie s'il n'existe pas
if (!(Test-Path $OutputDir)) {
    New-Item -Path $OutputDir -ItemType Directory -Force
    Write-Host "📁 Dossier créé: $OutputDir" -ForegroundColor Yellow
}

# Vérifier si PlantUML est disponible
$plantumlJar = "plantuml.jar"
if (!(Test-Path $plantumlJar)) {
    Write-Host "⚠️  PlantUML non trouvé. Téléchargement..." -ForegroundColor Yellow
    try {
        Invoke-WebRequest -Uri "https://github.com/plantuml/plantuml/releases/download/v1.2023.10/plantuml-1.2023.10.jar" -OutFile $plantumlJar
        Write-Host "✅ PlantUML téléchargé avec succès" -ForegroundColor Green
    }
    catch {
        Write-Host "❌ Erreur lors du téléchargement de PlantUML: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "📌 Veuillez télécharger manuellement depuis: https://plantuml.com/download" -ForegroundColor Cyan
        exit 1
    }
}

# Liste des fichiers PlantUML
$pumlFiles = @(
    @{
        File = "navigation_diagram.puml"
        Name = "Diagramme de Navigation"
        Description = "Parcours utilisateur dans l'interface web"
    },
    @{
        File = "state_transition_diagram.puml"
        Name = "Diagramme d'État-Transition"
        Description = "Cycle de vie d'un équipement en location"
    },
    @{
        File = "activity_diagram.puml"
        Name = "Diagramme d'Activité"
        Description = "Processus complet de location"
    },
    @{
        File = "sequence_diagram.puml"
        Name = "Diagramme de Séquence"
        Description = "Interactions système pour location"
    },
    @{
        File = "communication_diagram.puml"
        Name = "Diagramme de Communication"
        Description = "Collaborations entre objets"
    }
)

Write-Host "🔄 Génération des diagrammes en cours..." -ForegroundColor Cyan

$successCount = 0
$totalCount = $pumlFiles.Count

foreach ($diagram in $pumlFiles) {
    $inputFile = Join-Path $InputDir $diagram.File
    $outputFile = Join-Path $OutputDir ($diagram.File -replace "\.puml$", ".$Format")
    
    if (Test-Path $inputFile) {
        Write-Host "📊 Génération: $($diagram.Name)" -ForegroundColor White
        
        try {
            # Commande Java pour PlantUML
            $cmd = "java -jar $plantumlJar -t$Format -o `"$(Resolve-Path $OutputDir)`" `"$inputFile`""
            Invoke-Expression $cmd
            
            if (Test-Path $outputFile) {
                Write-Host "   ✅ Généré: $($diagram.File -replace '\.puml$', ".$Format")" -ForegroundColor Green
                $successCount++
            } else {
                Write-Host "   ❌ Échec de génération pour $($diagram.File)" -ForegroundColor Red
            }
        }
        catch {
            Write-Host "   ❌ Erreur: $($_.Exception.Message)" -ForegroundColor Red
        }
    } else {
        Write-Host "   ⚠️  Fichier non trouvé: $inputFile" -ForegroundColor Yellow
    }
}

Write-Host "`n📈 Résumé de génération:" -ForegroundColor Green
Write-Host "   Diagrammes générés: $successCount/$totalCount" -ForegroundColor White
Write-Host "   Format: $Format" -ForegroundColor White
Write-Host "   Dossier de sortie: $OutputDir" -ForegroundColor White

if ($successCount -eq $totalCount) {
    Write-Host "`n🎉 Tous les diagrammes ont été générés avec succès!" -ForegroundColor Green
} else {
    Write-Host "`n⚠️  Certains diagrammes n'ont pas pu être générés." -ForegroundColor Yellow
}

# Créer un fichier index HTML pour visualiser les diagrammes
$indexPath = Join-Path $OutputDir "index.html"
$htmlContent = @"
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagrammes UML - FarmShop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #2c5530; text-align: center; }
        .diagram { margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .diagram h2 { color: #2c5530; margin-top: 0; }
        .diagram img { max-width: 100%; height: auto; border: 1px solid #ccc; }
        .description { color: #666; font-style: italic; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Diagrammes UML - FarmShop</h1>
        <p style="text-align: center; color: #666;">Analyse UML 2 - Processus de Location d'Équipement Agricole</p>
"@

foreach ($diagram in $pumlFiles) {
    $imageName = $diagram.File -replace "\.puml$", ".$Format"
    $htmlContent += @"
        
        <div class="diagram">
            <h2>$($diagram.Name)</h2>
            <p class="description">$($diagram.Description)</p>
            <img src="$imageName" alt="$($diagram.Name)" />
        </div>
"@
}

$htmlContent += @"
    </div>
</body>
</html>
"@

Set-Content -Path $indexPath -Value $htmlContent -Encoding UTF8
Write-Host "`n🌐 Page de visualisation créée: $indexPath" -ForegroundColor Cyan

Write-Host "`n📋 Instructions pour la suite:" -ForegroundColor Yellow
Write-Host "1. Ouvrez le fichier HTML: $indexPath" -ForegroundColor White
Write-Host "2. Ou consultez les images directement dans: $OutputDir" -ForegroundColor White
Write-Host "3. Intégrez les diagrammes dans votre rapport final" -ForegroundColor White

Write-Host "`n✨ Génération terminée!" -ForegroundColor Green
