# Configuration Railway Upload System

Write-Host "=== CONFIGURATION RAILWAY UPLOAD SYSTEM ===" -ForegroundColor Green

Write-Host "`n1. Variables d'environnement Railway:" -ForegroundColor Yellow
Write-Host "USE_RAILWAY_STORAGE=true" -ForegroundColor White
Write-Host "FILESYSTEM_DISK=railway" -ForegroundColor White

Write-Host "`n2. Commandes Railway CLI:" -ForegroundColor Yellow  
Write-Host "railway variables set USE_RAILWAY_STORAGE=true" -ForegroundColor White
Write-Host "railway variables set FILESYSTEM_DISK=railway" -ForegroundColor White

Write-Host "`n3. Test apres deploiement:" -ForegroundColor Yellow
Write-Host "railway run php artisan test:image-upload" -ForegroundColor White

Write-Host "`n4. URLs generees sur Railway:" -ForegroundColor Yellow
Write-Host "https://votre-site.up.railway.app/images/products/image.jpg" -ForegroundColor White

Write-Host "`n=== CONFIGURATION TERMINEE ===" -ForegroundColor Green
