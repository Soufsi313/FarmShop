@echo off
REM Script de surveillance automatique des locations
REM À programmer dans le Planificateur de tâches Windows

echo === SURVEILLANCE AUTOMATIQUE FARMSHOP ===
echo Execution: %date% %time%

cd /d "C:\Users\Master\Desktop\FarmShop"

REM Surveiller les transitions de location
php -d memory_limit=128M monitor_rentals.php >> logs\rental_monitor.log 2>&1

echo === Surveillance terminee ===
