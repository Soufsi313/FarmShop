@echo off
title Automatisation FarmShop - 45 secondes
echo === Automatisation FarmShop ===
echo Intervalle: 45 secondes
echo Appuyez sur Ctrl+C pour arreter
echo.

cd /d "%~dp0"

:loop
echo [%date% %time%] Execution automatisation...
php artisan orders:update-status
echo.
echo Attente 45 secondes...
timeout /t 45 /nobreak >nul
goto loop
