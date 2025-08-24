@echo off
echo ====================================
echo     FARMSHOP - DEMARRAGE RAPIDE
echo ====================================
echo.

echo Que souhaitez-vous faire ?
echo.
echo [1] Demarrer l'environnement complet
echo [2] Verifier le systeme
echo [3] Nettoyer et optimiser
echo [4] Redemarrer seulement le worker de queue
echo [5] Voir les dernieres commandes
echo [6] Quitter
echo.

set /p choice="Votre choix (1-6): "

if "%choice%"=="1" goto start_all
if "%choice%"=="2" goto check_system
if "%choice%"=="3" goto clean_system
if "%choice%"=="4" goto restart_queue
if "%choice%"=="5" goto show_orders
if "%choice%"=="6" goto end

:start_all
call start-local.bat
goto end

:check_system
call check-system.bat
goto end

:clean_system
call clean-local.bat
goto end

:restart_queue
echo Arret des workers existants...
taskkill /f /im php.exe /fi "windowtitle eq Queue Worker*" 2>nul
echo Demarrage du nouveau worker...
start "Queue Worker" cmd /k "php artisan queue:work --daemon"
echo ✅ Worker de queue redemarré !
pause
goto end

:show_orders
php check_last_order.php
pause
goto end

:end
echo Au revoir !
