@echo off
echo ====================================
echo    FARMSHOP - VERIFICATION SYSTEME
echo ====================================
echo.

echo [1] Verification des dernieres commandes...
php check_last_order.php

echo.
echo [2] Verification des logs d'erreur...
powershell -Command "Get-Content storage\logs\laravel.log | Select-String -Pattern 'ERROR|CRITICAL' | Select-Object -Last 5"

echo.
echo [3] Status du serveur Laravel...
netstat -an | findstr :8000 && echo ✅ Serveur Laravel actif || echo ❌ Serveur Laravel arrete

echo.
echo [4] Test de connexion Stripe...
php artisan tinker --execute="echo 'Stripe Key: ' . (config('services.stripe.key') ? '✅ Configuree' : '❌ Manquante') . PHP_EOL;"

echo.
echo [5] Verification de la timezone...
php artisan tinker --execute="echo 'Timezone: ' . config('app.timezone') . ' (' . now()->format('d/m/Y H:i:s T') . ')' . PHP_EOL;"

echo.
pause
