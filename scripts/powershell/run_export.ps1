# Export des schemas FarmShop - Script PowerShell
# Genere le: 2025-07-14 10:03:30

Write-Host "FARMSHOP DATABASE - Export des schemas" -ForegroundColor Cyan
Write-Host ""

# Configuration
$mysqlPath = "C:\xampp\mysql\bin\mysqldump.exe"
$username = "root"
$database = "farmshop"
$exportDir = "database_schemas"

# Verifier que mysqldump existe
if (-not (Test-Path $mysqlPath)) {
    Write-Host "ERREUR: mysqldump non trouve a: $mysqlPath" -ForegroundColor Red
    Write-Host "   Verifiez votre installation MySQL/XAMPP" -ForegroundColor Yellow
    exit 1
}

# Creer le dossier s'il n'existe pas
if (-not (Test-Path $exportDir)) {
    New-Item -ItemType Directory -Path $exportDir | Out-Null
    Write-Host "Dossier cree: $exportDir" -ForegroundColor Green
}

# Options mysqldump
$options = "--routines --triggers --add-drop-table --single-transaction --lock-tables=false"

# Definition des schemas
$schemas = @{
    "01_products_schema" = @{
        "name" = "Produits et Catalogue"
        "tables" = "products categories rental_categories special_offers"
    }
    "02_users_schema" = @{
        "name" = "Utilisateurs et Authentification"
        "tables" = "users password_reset_tokens sessions product_likes wishlists cookies"
    }
    "03_orders_schema" = @{
        "name" = "Commandes et Achats"
        "tables" = "orders order_items order_returns carts cart_items"
    }
    "04_rentals_schema" = @{
        "name" = "Locations"
        "tables" = "order_locations order_item_locations cart_locations cart_item_locations"
    }
    "05_communication_schema" = @{
        "name" = "Communication et Marketing"
        "tables" = "messages blog_categories blog_posts blog_comments blog_comment_reports newsletters newsletter_subscriptions newsletter_sends"
    }
    "06_system_schema" = @{
        "name" = "Systeme et Infrastructure"
        "tables" = "migrations cache cache_locks jobs job_batches failed_jobs"
    }
}

Write-Host "Demarrage de l'export..." -ForegroundColor Yellow
Write-Host ""

$totalSchemas = $schemas.Count
$currentSchema = 0

foreach ($schemaKey in $schemas.Keys | Sort-Object) {
    $currentSchema++
    $schema = $schemas[$schemaKey]
    $filename = "$exportDir\$schemaKey.sql"
    
    Write-Host "[$currentSchema/$totalSchemas] $($schema.name)" -ForegroundColor Cyan
    Write-Host "   Tables: $($schema.tables)" -ForegroundColor Gray
    Write-Host "   Fichier: $filename" -ForegroundColor Gray
    
    # Construire la commande
    $command = "$mysqlPath -u $username -p$database $($schema.tables) $options"
    
    try {
        # Executer mysqldump et rediriger vers le fichier
        Write-Host "   Export en cours..." -ForegroundColor Yellow
        
        # Demander le mot de passe une seule fois
        if ($currentSchema -eq 1) {
            $password = Read-Host "   Mot de passe MySQL (root)" -AsSecureString
            $passwordText = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))
        }
        
        # Executer la commande
        $fullCommand = "$mysqlPath -u $username --password=$passwordText $database $($schema.tables) $options"
        Invoke-Expression $fullCommand | Out-File -FilePath $filename -Encoding UTF8
        
        if (Test-Path $filename) {
            $fileSize = (Get-Item $filename).Length
            Write-Host "   Export reussi ($fileSize octets)" -ForegroundColor Green
        } else {
            Write-Host "   Echec de l'export" -ForegroundColor Red
        }
        
    } catch {
        Write-Host "   Erreur: $($_.Exception.Message)" -ForegroundColor Red
    }
    
    Write-Host ""
}

Write-Host "RESUME DE L'EXPORT" -ForegroundColor Cyan
Write-Host "==================" -ForegroundColor Cyan

$files = Get-ChildItem -Path $exportDir -Filter "*.sql"
if ($files.Count -gt 0) {
    foreach ($file in $files) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "OK $($file.Name) ($size KB)" -ForegroundColor Green
    }
    
    Write-Host ""
    Write-Host "PROCHAINES ETAPES:" -ForegroundColor Yellow
    Write-Host "1. Importer les fichiers dans votre outil de modelisation"
    Write-Host "2. Respecter l'ordre numerique (01 puis 02 puis 03...)"
    Write-Host "3. Creer des diagrammes separes pour chaque schema"
    Write-Host ""
    Write-Host "Plus d'infos: DATABASE_SCHEMA_GUIDE.md" -ForegroundColor Cyan
    
} else {
    Write-Host "Aucun fichier genere. Verifiez les erreurs ci-dessus." -ForegroundColor Red
}

Write-Host ""
Write-Host "Export termine!" -ForegroundColor Green
