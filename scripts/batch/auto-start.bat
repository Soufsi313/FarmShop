@echo off
title FarmShop - Demarrage Automatique
echo ====================================
echo    FARMSHOP - AUTO START
echo ====================================
echo.

echo [1/3] Demarrage du serveur Laravel...
start "Laravel Server" cmd /k "title Laravel Server && cd /d %~dp0 && php artisan serve"
timeout /t 2 /nobreak >nul

echo [2/3] Demarrage du worker de queue automatique...
start "Queue Worker" cmd /k "title Queue Worker Auto && cd /d %~dp0 && queue-worker-auto.bat"
timeout /t 2 /nobreak >nul

echo [3/3] Ouverture du navigateur...
timeout /t 3 /nobreak >nul
start http://localhost:8000

echo.
echo ✅ FarmShop demarre automatiquement !
echo.
echo Fenetres actives:
echo - Serveur Laravel: http://localhost:8000
echo - Worker Queue: Surveillance automatique active
echo.
echo ⚠️ Important: Ne fermez pas la fenetre "Queue Worker"
echo    Elle relance automatiquement le worker en cas d'arret
echo.
echo Appuyez sur une touche pour fermer cette fenetre...
pause >nul
