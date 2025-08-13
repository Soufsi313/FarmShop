@echo off
echo ====================================
echo      FARMSHOP - NETTOYAGE LOCAL
echo ====================================
echo.

echo [1] Nettoyage du cache Laravel...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo [2] Nettoyage des logs...
echo. > storage\logs\laravel.log
echo ✅ Logs vides

echo.
echo [3] Nettoyage des jobs echoues...
php artisan queue:flush

echo.
echo [4] Optimization pour le developpement...
php artisan config:cache

echo.
echo ✅ Nettoyage termine !
echo.
pause
