# Automatisation FarmShop - PowerShell
Write-Host "=== Automatisation FarmShop - Démarrage ===" -ForegroundColor Green
Write-Host "Intervalle: 45 secondes" -ForegroundColor Yellow
Write-Host "Appuyez sur Ctrl+C pour arrêter" -ForegroundColor Yellow
Write-Host ""

$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
$phpScript = Join-Path $scriptPath "run_automation.php"

try {
    while ($true) {
        $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
        Write-Host "[$timestamp] Exécution de l'automatisation..." -ForegroundColor Cyan
        
        # Exécuter le script PHP
        $output = & php $phpScript 2>&1
        
        # Afficher la sortie si elle contient des mises à jour importantes
        if ($output -match "✅|📧|❌") {
            Write-Host $output -ForegroundColor White
        } elseif ($output -match "Erreur") {
            Write-Host $output -ForegroundColor Red
        } else {
            Write-Host "Aucune mise à jour nécessaire" -ForegroundColor Gray
        }
        
        Write-Host "Attente de 45 secondes..." -ForegroundColor Yellow
        Start-Sleep -Seconds 45
    }
}
catch {
    Write-Host "Erreur: $($_.Exception.Message)" -ForegroundColor Red
    Read-Host "Appuyez sur Entrée pour quitter"
}
