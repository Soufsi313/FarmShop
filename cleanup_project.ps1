# Script de nettoyage du projet FarmShop
Write-Host "🧹 Nettoyage du projet FarmShop..." -ForegroundColor Cyan

# Compteur de fichiers supprimés
$deletedCount = 0

# Fonction pour supprimer des fichiers avec pattern
function Remove-FilesWithPattern {
    param($pattern, $description)
    $files = Get-ChildItem -Path . -Name $pattern -File
    if ($files) {
        Write-Host "Suppression des $description..." -ForegroundColor Yellow
        $files | ForEach-Object { 
            Remove-Item $_ -Force
            Write-Host "  - $_" -ForegroundColor Red
            $script:deletedCount++
        }
    } else {
        Write-Host "Aucun fichier $description trouvé." -ForegroundColor Green
    }
}

# Supprimer tous les fichiers de test
Remove-FilesWithPattern "test_*.php" "fichiers test_*.php"

# Supprimer tous les fichiers check_*.php
Remove-FilesWithPattern "check_*.php" "fichiers check_*.php"

# Supprimer tous les fichiers debug_*.php
Remove-FilesWithPattern "debug_*.php" "fichiers debug_*.php"

# Supprimer tous les fichiers *_test.php
Remove-FilesWithPattern "*_test.php" "fichiers *_test.php"

# Supprimer tous les fichiers migrate_*.php temporaires
Remove-FilesWithPattern "migrate_*.php" "fichiers migrate_*.php temporaires"

# Supprimer tous les fichiers analyze_*.php
Remove-FilesWithPattern "analyze_*.php" "fichiers analyze_*.php"

# Supprimer des fichiers spécifiques
$specificFiles = @(
    "list_tables_test.php",
    "list_tables.php", 
    "list_order_tables.php"
)

Write-Host "Suppression de fichiers spécifiques..." -ForegroundColor Yellow
foreach ($file in $specificFiles) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Host "  - $file" -ForegroundColor Red
        $deletedCount++
    }
}

# Supprimer d'autres patterns
$patterns = @(
    "create_*.php",
    "delete_*.php", 
    "clean_*.php",
    "cleanup_*.php",
    "fix_*.php",
    "populate_*.php",
    "process_*.php",
    "run_*.php",
    "setup_*.php",
    "find_*.php",
    "generate_*.php"
)

foreach ($pattern in $patterns) {
    Remove-FilesWithPattern $pattern "fichiers $pattern"
}

# Nettoyer le dossier docs s'il existe
if (Test-Path "docs") {
    Write-Host "Nettoyage du dossier docs..." -ForegroundColor Yellow
    $docsFiles = Get-ChildItem -Path "docs" -Name "analyze_*.php", "list_*.php" -File
    if ($docsFiles) {
        $docsFiles | ForEach-Object { 
            Remove-Item "docs\$_" -Force
            Write-Host "  - docs\$_" -ForegroundColor Red
            $deletedCount++
        }
    }
}

Write-Host ""
Write-Host "✅ Nettoyage terminé !" -ForegroundColor Green
Write-Host "📊 Total de $deletedCount fichiers supprimés." -ForegroundColor Cyan
Write-Host "🔍 Vérifiez avec 'git status' pour voir les changements." -ForegroundColor Yellow
