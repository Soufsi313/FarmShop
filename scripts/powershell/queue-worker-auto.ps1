# FarmShop - Worker de Queue Automatique avec Surveillance
# Ce script surveille et relance automatiquement le worker

$ProjectPath = Split-Path -Parent $MyInvocation.MyCommand.Definition
$LogFile = Join-Path $ProjectPath "queue-worker.log"

function Write-Log {
    param($Message)
    $Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $LogEntry = "[$Timestamp] $Message"
    Write-Host $LogEntry -ForegroundColor Green
    Add-Content -Path $LogFile -Value $LogEntry
}

function Start-QueueWorker {
    Write-Log "🚀 Démarrage du worker de queue..."
    
    while ($true) {
        try {
            # Démarrer le worker avec timeout de 5h pour présentation
            $process = Start-Process -FilePath "php" -ArgumentList "artisan", "queue:work", "--daemon", "--tries=3", "--timeout=18000", "--sleep=1" -WorkingDirectory $ProjectPath -PassThru -NoNewWindow
            
            Write-Log "✅ Worker démarré (PID: $($process.Id))"
            
            # Surveiller le processus
            $process.WaitForExit()
            
            Write-Log "⚠️ Worker arrêté - Redémarrage dans 5 secondes..."
            Start-Sleep 5
        }
        catch {
            Write-Log "❌ Erreur: $($_.Exception.Message)"
            Write-Log "🔄 Nouvelle tentative dans 10 secondes..."
            Start-Sleep 10
        }
    }
}

function Monitor-System {
    Write-Log "🔍 Surveillance du système démarrée"
    
    while ($true) {
        Start-Sleep 30
        
        # Vérifier les jobs en attente
        try {
            $jobs = & php artisan queue:size 2>$null
            if ($jobs -gt 0) {
                Write-Log "📋 $jobs job(s) en attente"
            }
        }
        catch {
            Write-Log "⚠️ Impossible de vérifier la queue"
        }
    }
}

# Interface principale
Clear-Host
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  FARMSHOP - WORKER AUTOMATIQUE" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "✅ Worker de queue avec surveillance automatique" -ForegroundColor Green
Write-Host "📝 Logs: queue-worker.log" -ForegroundColor Yellow
Write-Host "🛑 Pour arrêter: Ctrl+C" -ForegroundColor Red
Write-Host ""

# Démarrer le worker et la surveillance en parallèle
$workerJob = Start-Job -ScriptBlock { 
    param($Path) 
    Set-Location $Path
    & $Path\queue-worker-auto.ps1
} -ArgumentList $ProjectPath

Start-QueueWorker
