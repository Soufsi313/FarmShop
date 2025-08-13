# FarmShop - Script de développement local PowerShell
# Utilisation: .\farmshop.ps1 [start|check|clean|restart-queue|orders]

param(
    [string]$Action = "menu"
)

$ProjectPath = Split-Path -Parent $MyInvocation.MyCommand.Definition

function Show-Menu {
    Clear-Host
    Write-Host "=====================================" -ForegroundColor Cyan
    Write-Host "    FARMSHOP - ENVIRONNEMENT LOCAL" -ForegroundColor Cyan
    Write-Host "=====================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "[1] Démarrer l'environnement complet" -ForegroundColor Green
    Write-Host "[2] Vérifier le système" -ForegroundColor Yellow
    Write-Host "[3] Nettoyer et optimiser" -ForegroundColor Magenta
    Write-Host "[4] Redémarrer le worker de queue" -ForegroundColor Blue
    Write-Host "[5] Voir les dernières commandes" -ForegroundColor White
    Write-Host "[6] Logs en temps réel" -ForegroundColor DarkYellow
    Write-Host "[7] Quitter" -ForegroundColor Red
    Write-Host ""
    
    $choice = Read-Host "Votre choix (1-7)"
    
    switch ($choice) {
        "1" { Start-Environment }
        "2" { Check-System }
        "3" { Clean-System }
        "4" { Restart-Queue }
        "5" { Show-Orders }
        "6" { Show-Logs }
        "7" { exit }
        default { Show-Menu }
    }
}

function Start-Environment {
    Write-Host "🚀 Démarrage de l'environnement local..." -ForegroundColor Green
    
    # Démarrer le serveur Laravel
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$ProjectPath'; php artisan serve" -WindowStyle Normal
    Start-Sleep 2
    
    # Démarrer le worker de queue
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$ProjectPath'; Write-Host 'Worker de queue actif - Ne fermez pas cette fenêtre' -ForegroundColor Green; php artisan queue:work --daemon" -WindowStyle Normal
    Start-Sleep 2
    
    # Ouvrir le navigateur
    Start-Process "http://localhost:8000"
    
    Write-Host "✅ Environnement démarré avec succès !" -ForegroundColor Green
    Write-Host "- Serveur: http://localhost:8000" -ForegroundColor Cyan
    Write-Host "- Worker de queue: Actif en arrière-plan" -ForegroundColor Cyan
    
    Read-Host "Appuyez sur Entrée pour retourner au menu"
    Show-Menu
}

function Check-System {
    Write-Host "🔍 Vérification du système..." -ForegroundColor Yellow
    
    # Vérifier les commandes
    & php "$ProjectPath\check_last_order.php"
    
    # Vérifier les erreurs
    Write-Host "`n📋 Dernières erreurs:" -ForegroundColor Red
    Get-Content "$ProjectPath\storage\logs\laravel.log" | Select-String -Pattern "ERROR|CRITICAL" | Select-Object -Last 3
    
    # Vérifier le serveur
    $server = netstat -an | Select-String ":8000"
    if ($server) {
        Write-Host "✅ Serveur Laravel actif" -ForegroundColor Green
    } else {
        Write-Host "❌ Serveur Laravel arrêté" -ForegroundColor Red
    }
    
    Read-Host "Appuyez sur Entrée pour retourner au menu"
    Show-Menu
}

function Clean-System {
    Write-Host "🧹 Nettoyage du système..." -ForegroundColor Magenta
    
    & php artisan cache:clear
    & php artisan config:clear
    & php artisan route:clear
    & php artisan view:clear
    & php artisan queue:flush
    
    # Vider les logs
    "" | Out-File "$ProjectPath\storage\logs\laravel.log"
    
    Write-Host "✅ Nettoyage terminé !" -ForegroundColor Green
    
    Read-Host "Appuyez sur Entrée pour retourner au menu"
    Show-Menu
}

function Restart-Queue {
    Write-Host "🔄 Redémarrage du worker de queue..." -ForegroundColor Blue
    
    # Arrêter les workers existants
    Get-Process | Where-Object {$_.ProcessName -eq "php" -and $_.MainWindowTitle -like "*queue*"} | Stop-Process -Force -ErrorAction SilentlyContinue
    
    # Démarrer un nouveau worker
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$ProjectPath'; Write-Host 'Worker de queue redémarré' -ForegroundColor Green; php artisan queue:work --daemon" -WindowStyle Normal
    
    Write-Host "✅ Worker de queue redémarré !" -ForegroundColor Green
    
    Read-Host "Appuyez sur Entrée pour retourner au menu"
    Show-Menu
}

function Show-Orders {
    Write-Host "📦 Dernières commandes..." -ForegroundColor White
    & php "$ProjectPath\check_last_order.php"
    
    Read-Host "Appuyez sur Entrée pour retourner au menu"
    Show-Menu
}

function Show-Logs {
    Write-Host "📝 Logs en temps réel (Ctrl+C pour arrêter)..." -ForegroundColor DarkYellow
    Get-Content "$ProjectPath\storage\logs\laravel.log" -Wait -Tail 10
    Show-Menu
}

# Point d'entrée principal
switch ($Action.ToLower()) {
    "start" { Start-Environment }
    "check" { Check-System }
    "clean" { Clean-System }
    "restart-queue" { Restart-Queue }
    "orders" { Show-Orders }
    default { Show-Menu }
}
