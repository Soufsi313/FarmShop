@echo off
echo === Automatisation FarmShop - Démarrage ===
echo Appuyez sur Ctrl+C pour arrêter
echo.

:loop
echo [%date% %time%] Exécution de l'automatisation...
php "%~dp0run_automation.php"
echo Attente de 45 secondes...
timeout /t 45 /nobreak >nul
goto loop
