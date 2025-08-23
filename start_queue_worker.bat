@echo off
echo Démarrage automatique du worker de queue...
cd /d "C:\Users\Master\Desktop\FarmShop"
php artisan queue:work --daemon --tries=3 --timeout=18000
pause
