@echo off
title FarmShop - Worker de Queue Automatique
echo ====================================
echo   FARMSHOP - WORKER AUTOMATIQUE
echo ====================================
echo.
echo [INFO] Demarrage du worker de queue automatique...
echo [INFO] Ce processus va redemarrer automatiquement en cas d'erreur
echo [INFO] Pour arreter: Fermez cette fenetre
echo.

:start
echo [%date% %time%] Demarrage du worker...
php artisan queue:work --daemon --tries=3 --timeout=300 --sleep=1

echo.
echo [%date% %time%] Worker arrete - Redemarrage dans 5 secondes...
timeout /t 5 /nobreak >nul
goto start
