@echo off
title FarmShop - Automatisation Continue
echo === AUTOMATISATION FARMSHOP - PRODUCTION ===
echo Demarrage : %date% %time%
echo Intervalle : 45 secondes
echo.

:loop
echo [%time%] Execution de l'automatisation...
cd /d "c:\Users\Master\Desktop\FarmShop"
php artisan orders:update-status
if %errorlevel% equ 0 (
    echo ✓ Commande executee avec succes
) else (
    echo ✗ Erreur lors de l'execution (code: %errorlevel%^)
)
echo Pause de 45 secondes...
timeout /t 45 /nobreak >nul
echo.
goto loop
