@echo off
echo ==============================================
echo   FARMSHOP - Demarrage Service Automatique
echo ==============================================
echo.
echo Demarrage du worker de queue en arriere-plan...
echo.

cd /d "C:\Users\Master\Desktop\FarmShop"

REM Vérifier si le worker tourne déjà
tasklist /FI "IMAGENAME eq php.exe" /FI "WINDOWTITLE eq *queue:work*" 2>NUL | find /I "php.exe" >NUL
if "%ERRORLEVEL%"=="0" (
    echo [INFO] Un worker de queue est deja en cours d'execution.
    echo.
) else (
    echo [INFO] Demarrage du worker de queue...
    start /MIN "FarmShop Queue Worker" php artisan queue:work --timeout=300 --tries=3
    echo [SUCCESS] Worker demarre en arriere-plan !
    echo.
)

echo ==============================================
echo   PROGRESSION AUTOMATIQUE ACTIVEE !
echo ==============================================
echo.
echo Maintenant, quand un paiement Stripe reussit :
echo   1. Confirmation      (immediat)
echo   2. Preparation   --^  (apres 20 secondes)
echo   3. Expedition    --^  (apres 20 secondes)  
echo   4. Livraison     --^  (apres 20 secondes)
echo.
echo Total : 60 secondes pour progression complete
echo.
echo [ANNULATION] Possible jusqu'a l'expedition
echo.
pause
