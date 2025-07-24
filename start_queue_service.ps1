# Script pour démarrer automatiquement le worker de queue
param(
    [string]$ProjectPath = "C:\Users\Master\Desktop\FarmShop"
)

Write-Host "Démarrage automatique du worker de queue pour FarmShop..." -ForegroundColor Green

# Aller dans le répertoire du projet
Set-Location $ProjectPath

# Vérifier si le worker tourne déjà
$existingProcess = Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object {$_.CommandLine -like "*queue:work*"}

if ($existingProcess) {
    Write-Host "Un worker de queue est déjà en cours d'exécution (PID: $($existingProcess.Id))" -ForegroundColor Yellow
} else {
    Write-Host "Démarrage du worker de queue..." -ForegroundColor Blue
    
    # Démarrer le worker en arrière-plan
    Start-Process -FilePath "php" -ArgumentList "artisan", "queue:work", "--timeout=300", "--tries=3", "--daemon" -WindowStyle Hidden
    
    Write-Host "Worker de queue démarré en arrière-plan !" -ForegroundColor Green
    Write-Host "La progression automatique des commandes est maintenant active." -ForegroundColor Green
}

Write-Host "`nPour arrêter le worker :" -ForegroundColor Yellow
Write-Host "Get-Process php | Where-Object {`$_.CommandLine -like '*queue:work*'} | Stop-Process" -ForegroundColor Gray
