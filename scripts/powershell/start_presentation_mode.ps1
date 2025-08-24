# FarmShop - Mode Présentation
# Timeout de 5 heures pour une session de travail complète

param(
    [string]$ProjectPath = "C:\Users\Master\Desktop\FarmShop"
)

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "   FARMSHOP - MODE PRÉSENTATION" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "🎯 Configuration optimisée pour présentation :" -ForegroundColor Green
Write-Host "   • Worker timeout: 5 heures (18000 secondes)" -ForegroundColor Yellow
Write-Host "   • Parfait pour 2-4h de travail continu" -ForegroundColor Yellow
Write-Host "   • Progression automatique: 15s par étape" -ForegroundColor Yellow
Write-Host ""

# Aller dans le répertoire du projet
Set-Location $ProjectPath

# Arrêter les workers existants
Write-Host "🛑 Arrêt des workers existants..." -ForegroundColor Blue
Get-Process -Name "php" -ErrorAction SilentlyContinue | Where-Object {$_.CommandLine -like "*queue:work*"} | Stop-Process -Force -ErrorAction SilentlyContinue

# Démarrer le serveur Laravel
Write-Host "🚀 Démarrage du serveur Laravel..." -ForegroundColor Blue
Start-Process -FilePath "php" -ArgumentList "artisan", "serve" -WindowStyle Normal

Start-Sleep 2

# Démarrer le worker avec timeout de 5h
Write-Host "⚙️ Démarrage du worker (timeout 5h)..." -ForegroundColor Blue
Start-Process -FilePath "php" -ArgumentList "artisan", "queue:work", "--daemon", "--tries=3", "--timeout=18000", "--sleep=1" -WindowStyle Normal

Start-Sleep 3

Write-Host ""
Write-Host "✅ Mode présentation activé avec succès !" -ForegroundColor Green
Write-Host ""
Write-Host "📊 Statut :" -ForegroundColor White
Write-Host "   🌐 Serveur Laravel: http://localhost:8000" -ForegroundColor Gray
Write-Host "   ⚙️ Worker actif avec timeout 5h" -ForegroundColor Gray
Write-Host "   🔄 Progression automatique des commandes activée" -ForegroundColor Gray
Write-Host ""
Write-Host "💡 Pour votre présentation :" -ForegroundColor Yellow
Write-Host "   • Le worker restera actif pendant 5 heures" -ForegroundColor Gray
Write-Host "   • Les commandes progresseront automatiquement (15s/étape)" -ForegroundColor Gray
Write-Host "   • Aucun redémarrage pendant votre session" -ForegroundColor Gray
Write-Host ""

# Ouvrir le navigateur
Write-Host "🌐 Ouverture du navigateur..." -ForegroundColor Blue
Start-Sleep 2
Start-Process "http://localhost:8000"

Write-Host ""
Write-Host "🎉 Bonne présentation !" -ForegroundColor Green
