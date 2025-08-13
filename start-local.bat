@echo off
echo ====================================
echo    FARMSHOP - ENVIRONNEMENT LOCAL
echo ====================================
echo.

echo [1] Demarrage du serveur Laravel...
start "Laravel Server" cmd /k "cd /d %~dp0 && php artisan serve"
timeout /t 3 /nobreak >nul

echo [2] Ouverture du site web...
start http://localhost:8000

echo.
echo ✅ Environnement local demarre !
echo.
echo ℹ️  Le worker de queue se demarre automatiquement
echo    lors du premier paiement confirme.
echo.
echo Fenetres ouvertes:
echo - Serveur Laravel: http://localhost:8000
echo - Site web: Ouvert dans le navigateur
echo.
echo ⚡ Transitions automatiques avec delais de 15s
echo.
pause
