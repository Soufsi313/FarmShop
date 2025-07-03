@echo off
title FarmShop - Configuration Task Scheduler
echo === CONFIGURATION AUTOMATISATION FARMSHOP ===
echo.

echo Configuration du Planificateur de taches Windows...
echo Tache : FarmShop_Automation
echo Frequence : Toutes les 45 secondes
echo Script : automation_scheduler.ps1
echo.

REM Supprimer la tâche existante si elle existe
schtasks /delete /tn "FarmShop_Automation" /f >nul 2>&1

REM Créer la nouvelle tâche
schtasks /create /tn "FarmShop_Automation" /tr "powershell.exe -ExecutionPolicy Bypass -File \"C:\Users\Master\Desktop\FarmShop\automation_scheduler.ps1\"" /sc minute /mo 1 /ru "SYSTEM" /f

if %errorlevel% equ 0 (
    echo ✓ Tache creee avec succes !
    echo.
    echo IMPORTANT: La tache s'execute toutes les MINUTES
    echo Pour avoir 45 secondes, nous devons la modifier manuellement :
    echo.
    echo 1. Ouvrir le Planificateur de taches ^(taskschd.msc^)
    echo 2. Aller dans "Bibliotheque du Planificateur de taches"
    echo 3. Trouver "FarmShop_Automation"
    echo 4. Clic droit ^> Proprietes
    echo 5. Onglet "Declencheurs" ^> Modifier
    echo 6. Cocher "Repetition de la tache"
    echo 7. Definir "Toutes les : 45 secondes"
    echo 8. Definir "Pendant : Indefiniment"
    echo 9. OK ^> OK
    echo.
    echo Voulez-vous ouvrir le Planificateur maintenant ? ^(O/N^)
    set /p choice=
    if /i "%choice%"=="O" start taskschd.msc
) else (
    echo ✗ Erreur lors de la creation de la tache
    echo Verifiez les permissions administrateur
)

echo.
echo Appuyez sur une touche pour continuer...
pause >nul
