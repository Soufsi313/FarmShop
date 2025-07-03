# Script PowerShell pour automatisation FarmShop
# À utiliser avec le Planificateur de tâches Windows

$ScriptPath = "C:\Users\Master\Desktop\FarmShop"
$LogFile = "$ScriptPath\logs\automation.log"

# Créer le dossier logs s'il n'existe pas
if (!(Test-Path "$ScriptPath\logs")) {
    New-Item -ItemType Directory -Path "$ScriptPath\logs" -Force
}

# Fonction de logging
function Write-Log {
    param($Message)
    $Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $LogEntry = "[$Timestamp] $Message"
    Write-Output $LogEntry
    Add-Content -Path $LogFile -Value $LogEntry
}

Write-Log "=== AUTOMATISATION FARMSHOP - DÉMARRAGE ==="

try {
    # Changer vers le répertoire du projet
    Set-Location -Path $ScriptPath
    
    # Exécuter la commande artisan
    $Output = & php artisan orders:update-status 2>&1
    $ExitCode = $LASTEXITCODE
    
    if ($ExitCode -eq 0) {
        Write-Log "✅ Automatisation exécutée avec succès"
        foreach ($Line in $Output) {
            if ($Line -ne "") {
                Write-Log "   $Line"
            }
        }
    } else {
        Write-Log "❌ Erreur lors de l'exécution (code: $ExitCode)"
        foreach ($Line in $Output) {
            Write-Log "   ERROR: $Line"
        }
    }
    
} catch {
    Write-Log "❌ Exception: $($_.Exception.Message)"
    Write-Log "   Stack: $($_.Exception.StackTrace)"
}

Write-Log "=== AUTOMATISATION FARMSHOP - FIN ===`n"
