# Script PowerShell pour corriger les collations MySQL incompatibles
# Ce script remplace les collations modernes par des collations compatibles avec les anciennes versions

Write-Host "🔧 Correction des collations MySQL/MariaDB..." -ForegroundColor Green

# Définir les remplacements de collations
$collationReplacements = @{
    'utf8mb4_uca1400_ai_ci' = 'utf8mb4_unicode_ci'
    'utf8mb4_0900_ai_ci' = 'utf8mb4_unicode_ci'
    'utf8_uca1400_ai_ci' = 'utf8_unicode_ci'
    'utf8_0900_ai_ci' = 'utf8_unicode_ci'
}

# Obtenir tous les fichiers SQL dans le projet
$sqlFiles = Get-ChildItem -Path "." -Recurse -Include "*.sql" -File

Write-Host "📁 Fichiers SQL trouvés: $($sqlFiles.Count)" -ForegroundColor Yellow

$totalReplacements = 0
$modifiedFiles = 0

foreach ($file in $sqlFiles) {
    Write-Host "   Traitement: $($file.Name)" -ForegroundColor Cyan
    
    $content = Get-Content $file.FullName -Raw -Encoding UTF8
    $originalContent = $content
    $fileReplacements = 0
    
    # Appliquer chaque remplacement
    foreach ($oldCollation in $collationReplacements.Keys) {
        $newCollation = $collationReplacements[$oldCollation]
        
        # Compter les occurrences avant remplacement
        $matches = [regex]::Matches($content, [regex]::Escape($oldCollation))
        if ($matches.Count -gt 0) {
            Write-Host "     ↳ Remplacement: $oldCollation → $newCollation ($($matches.Count) occurrence(s))" -ForegroundColor White
            $content = $content -replace [regex]::Escape($oldCollation), $newCollation
            $fileReplacements += $matches.Count
        }
    }
    
    # Sauvegarder le fichier si des modifications ont été apportées
    if ($content -ne $originalContent) {
        Set-Content $file.FullName -Value $content -Encoding UTF8
        $modifiedFiles++
        $totalReplacements += $fileReplacements
        Write-Host "     ✅ Fichier modifié ($fileReplacements remplacement(s))" -ForegroundColor Green
    } else {
        Write-Host "     ⏭️ Aucune modification nécessaire" -ForegroundColor Gray
    }
}

Write-Host ""
Write-Host "🎉 Correction terminée!" -ForegroundColor Green
Write-Host "   📊 Fichiers modifiés: $modifiedFiles" -ForegroundColor Yellow
Write-Host "   🔄 Total remplacements: $totalReplacements" -ForegroundColor Yellow
Write-Host ""

if ($modifiedFiles -gt 0) {
    Write-Host "💡 Les fichiers SQL sont maintenant compatibles avec les anciennes versions de MySQL/MariaDB" -ForegroundColor Cyan
    Write-Host "   Vous pouvez maintenant importer vos dumps SQL sans erreur de collation." -ForegroundColor Cyan
} else {
    Write-Host "ℹ️ Aucune collation problématique trouvée dans vos fichiers SQL." -ForegroundColor Blue
}

Write-Host ""
Write-Host "Appuyez sur une touche pour continuer..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
