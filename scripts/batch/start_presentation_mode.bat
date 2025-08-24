@echo off
title FarmShop - Mode Présentation
echo ====================================
echo   FARMSHOP - MODE PRESENTATION
echo ====================================
echo.
echo [INFO] Demarrage en mode presentation...
echo [INFO] Worker timeout: 5 heures (18000 secondes)
echo [INFO] Parfait pour une session de travail de 2-4h
echo.

cd /d "C:\Users\Master\Desktop\FarmShop"

REM Arrêter les workers existants
echo [1/3] Arret des workers existants...
taskkill /f /im php.exe /fi "windowtitle eq *queue:work*" 2>nul >nul

REM Démarrer le serveur Laravel
echo [2/3] Demarrage du serveur Laravel...
start "Laravel Server - Presentation" cmd /k "title Laravel Server - Presentation && php artisan serve"

REM Démarrer le worker avec timeout de 5h
echo [3/3] Demarrage du worker (5h timeout)...
start "Queue Worker - Presentation" cmd /k "title Queue Worker - Presentation && php artisan queue:work --daemon --tries=3 --timeout=18000 --sleep=1"

echo.
echo ✅ Mode présentation activé !
echo.
echo 🕐 Timeout worker: 5 heures
echo 🔄 Progression automatique: 15s par étape
echo 🌐 Serveur: http://localhost:8000
echo.
echo ⚠️ Gardez les fenêtres ouvertes pendant la présentation
echo.
echo Appuyez sur une touche pour ouvrir le navigateur...
pause >nul
start http://localhost:8000
