@echo off
title FarmShop - Nettoyage Scripts de Test
echo === NETTOYAGE DES SCRIPTS DE TEST ===
echo.

echo 🧹 Scripts de test qui peuvent être supprimes :
echo.

REM Lister les scripts de test
set TEST_SCRIPTS=^
create_test_order.php ^
check_orders_status.php ^
test_automation_system.php ^
check_preparation_status.php ^
check_recent_orders.php ^
debug_specific_order.php ^
create_test_orders_cancellation.php ^
create_perfect_return_order.php ^
create_returnable_order.php ^
quick_test_orders.php ^
list_eligible_orders.php ^
test_order_location_system.php ^
automation_daemon.php ^
run_automation.php ^
auto_45s.bat ^
auto_45s.ps1

echo Scripts de test identifies :
for %%f in (%TEST_SCRIPTS%) do (
    if exist "%%f" (
        echo   ✓ %%f
    ) else (
        echo   ✗ %%f ^(non trouve^)
    )
)

echo.
echo 📁 Scripts de PRODUCTION a conserver :
echo   ✓ automation_continuous.php
echo   ✓ automation_production.bat  
echo   ✓ automation_scheduler.ps1
echo   ✓ setup_automation.bat
echo   ✓ configure_emails.php
echo   ✓ manage_emails.php
echo.

echo ⚠️  ATTENTION: Cette operation supprimera definitivement les scripts de test
echo Voulez-vous continuer ? ^(O/N^)
set /p choice=

if /i "%choice%"=="O" goto :cleanup
if /i "%choice%"=="Y" goto :cleanup
echo ❌ Nettoyage annule
goto :end

:cleanup
echo.
echo 🧹 Suppression des scripts de test...

for %%f in (%TEST_SCRIPTS%) do (
    if exist "%%f" (
        del "%%f" >nul 2>&1
        if not exist "%%f" (
            echo   ✅ %%f supprime
        ) else (
            echo   ❌ Erreur lors de la suppression de %%f
        )
    )
)

REM Nettoyer aussi les logs de test si ils existent
if exist "logs\test_*.log" (
    del "logs\test_*.log" >nul 2>&1
    echo   ✅ Logs de test nettoyes
)

echo.
echo ✅ Nettoyage termine !
echo.
echo 📋 Scripts CONSERVES pour la production :
if exist "automation_continuous.php" echo   ✓ automation_continuous.php
if exist "automation_production.bat" echo   ✓ automation_production.bat
if exist "automation_scheduler.ps1" echo   ✓ automation_scheduler.ps1
if exist "setup_automation.bat" echo   ✓ setup_automation.bat
if exist "configure_emails.php" echo   ✓ configure_emails.php
if exist "manage_emails.php" echo   ✓ manage_emails.php

:end
echo.
echo Appuyez sur une touche pour continuer...
pause >nul
