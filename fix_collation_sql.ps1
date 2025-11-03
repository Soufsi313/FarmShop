# Script PowerShell pour corriger la collation MySQL dans les fichiers SQL
# Remplace utf8mb4_uca1400_ai_ci par utf8mb4_unicode_ci

Write-Host "=== Script de correction des collations MySQL ===" -ForegroundColor Green
Write-Host ""

# Définir les dossiers à traiter
$folders = @(
    "database_schemas",
    "database_schemas_small"
)

# Compteurs
$totalFiles = 0
$modifiedFiles = 0
$totalReplacements = 0

foreach ($folder in $folders) {
    $folderPath = Join-Path $PSScriptRoot $folder
    
    if (Test-Path $folderPath) {
        Write-Host "Traitement du dossier: $folder" -ForegroundColor Yellow
        
        # Obtenir tous les fichiers .sql
        $sqlFiles = Get-ChildItem -Path $folderPath -Filter "*.sql" -Recurse
        
        foreach ($file in $sqlFiles) {
            $totalFiles++
            Write-Host "  Vérification: $($file.Name)" -ForegroundColor Cyan
            
            # Lire le contenu du fichier
            $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
            $originalContent = $content
            
            # Compter les occurrences avant remplacement
            $occurrences = ([regex]::Matches($content, "utf8mb4_uca1400_ai_ci")).Count
            
            if ($occurrences -gt 0) {
                # Effectuer le remplacement
                $content = $content -replace "utf8mb4_uca1400_ai_ci", "utf8mb4_unicode_ci"
                
                # Sauvegarder le fichier modifié
                Set-Content -Path $file.FullName -Value $content -Encoding UTF8
                
                $modifiedFiles++
                $totalReplacements += $occurrences
                
                Write-Host "    ✓ Modifié: $occurrences remplacement(s)" -ForegroundColor Green
            } else {
                Write-Host "    - Aucun changement nécessaire" -ForegroundColor Gray
            }
        }
    } else {
        Write-Host "Dossier non trouvé: $folderPath" -ForegroundColor Red
    }
    
    Write-Host ""
}

# Résumé
Write-Host "=== RÉSUMÉ ===" -ForegroundColor Green
Write-Host "Fichiers traités: $totalFiles" -ForegroundColor White
Write-Host "Fichiers modifiés: $modifiedFiles" -ForegroundColor Yellow
Write-Host "Total des remplacements: $totalReplacements" -ForegroundColor Green
Write-Host ""

if ($totalReplacements -gt 0) {
    Write-Host "✓ Correction terminée ! Vos fichiers SQL sont maintenant compatibles." -ForegroundColor Green
    Write-Host "Vous pouvez maintenant importer vos fichiers SQL dans phpMyAdmin." -ForegroundColor White
} else {
    Write-Host "Aucune correction nécessaire trouvée." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Appuyez sur une touche pour continuer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
