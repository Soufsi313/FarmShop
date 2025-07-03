@echo off
REM Script d'automatisation FarmShop - Exécution unique
cd /d "%~dp0"
php run_automation.php >> automation.log 2>&1
