Write-Host "=== Automatisation FarmShop - 45 secondes ===" -ForegroundColor Green
Write-Host "Appuyez sur Ctrl+C pour arreter" -ForegroundColor Yellow
Write-Host ""

Set-Location $PSScriptRoot

try {
    while ($true) {
        $timestamp = Get-Date -Format "HH:mm:ss"
        Write-Host "[$timestamp] Execution automatisation..." -ForegroundColor Cyan
        
        $result = & php artisan orders:update-status 2>&1
        
        if ($result -match "✅") {
            Write-Host $result -ForegroundColor Green
        } elseif ($result -match "Aucune commande") {
            Write-Host "Aucune mise a jour necessaire" -ForegroundColor Gray
        } else {
            Write-Host $result -ForegroundColor White
        }
        
        Write-Host "Attente 45 secondes..." -ForegroundColor Yellow
        Write-Host ""
        Start-Sleep -Seconds 45
    }
}
catch [System.Management.Automation.PipelineStoppedException] {
    Write-Host "`nAutomatisation arretee." -ForegroundColor Red
}
catch {
    Write-Host "Erreur: $($_.Exception.Message)" -ForegroundColor Red
}
