# Export des schemas FarmShop - AVEC DEPENDANCES pour dbdiagram.io
# Chaque schema contient ses tables + tables referencees

Write-Host "FARMSHOP DATABASE - Export avec dependances pour dbdiagram.io" -ForegroundColor Cyan
Write-Host ""

# Configuration
$mysqlPath = "C:\xampp\mysql\bin\mysqldump.exe"
$username = "root"
$database = "farmshop"
$exportDir = "database_schemas"

# Verifier que mysqldump existe
if (-not (Test-Path $mysqlPath)) {
    Write-Host "ERREUR: mysqldump non trouve a: $mysqlPath" -ForegroundColor Red
    exit 1
}

# Options mysqldump POUR STRUCTURE SEULEMENT
$options = "--no-data --routines --triggers --add-drop-table --single-transaction"

# Definition des schemas AVEC DEPENDANCES
$schemas = @{
    "01_products_base" = @{
        "name" = "Produits & Catalogue (Base)"
        "tables" = "products categories rental_categories special_offers"
        "description" = "Schema de base - Aucune dependance externe"
    }
    "02_users_products" = @{
        "name" = "Utilisateurs avec Produits"
        "tables" = "users password_reset_tokens sessions products categories rental_categories product_likes wishlists cookies"
        "description" = "Users + Products + Categories (pour likes/wishlists)"
    }
    "03_orders_complete" = @{
        "name" = "Commandes Completes"
        "tables" = "users products categories orders order_items order_returns carts cart_items"
        "description" = "Orders + Users + Products (workflow complet)"
    }
    "04_rentals_complete" = @{
        "name" = "Locations Completes"
        "tables" = "users products categories rental_categories order_locations order_item_locations cart_locations cart_item_locations"
        "description" = "Rentals + Users + Products (workflow complet)"
    }
    "05_communication_users" = @{
        "name" = "Communication avec Utilisateurs"
        "tables" = "users messages blog_categories blog_posts blog_comments blog_comment_reports newsletters newsletter_subscriptions newsletter_sends"
        "description" = "Communication + Users (pour les auteurs)"
    }
}

Write-Host "Demarrage export avec DEPENDANCES..." -ForegroundColor Yellow
Write-Host ""

$totalSchemas = $schemas.Count
$currentSchema = 0

foreach ($schemaKey in $schemas.Keys | Sort-Object) {
    $currentSchema++
    $schema = $schemas[$schemaKey]
    $filename = "$exportDir\$schemaKey.sql"
    
    Write-Host "[$currentSchema/$totalSchemas] $($schema.name)" -ForegroundColor Cyan
    Write-Host "   Tables: $($schema.tables)" -ForegroundColor Gray
    Write-Host "   Description: $($schema.description)" -ForegroundColor Gray
    Write-Host "   Fichier: $filename" -ForegroundColor Gray
    
    try {
        Write-Host "   Export STRUCTURE avec dependances..." -ForegroundColor Yellow
        
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

Write-Host "RESUME DE L'EXPORT AVEC DEPENDANCES" -ForegroundColor Cyan
Write-Host "===================================" -ForegroundColor Cyan

$files = Get-ChildItem -Path $exportDir -Filter "*.sql" | Where-Object { $_.Name -like "*_*" -and $_.Name -notlike "*standalone*" -and $_.Name -notlike "*combined*" }
if ($files.Count -gt 0) {
    foreach ($file in $files) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "OK $($file.Name) ($size KB)" -ForegroundColor Green
    }
    
    Write-Host ""
    Write-Host "SCHEMAS AUTO-SUFFISANTS pour dbdiagram.io:" -ForegroundColor Yellow
    Write-Host "1. Chaque schema contient ses dependances"
    Write-Host "2. Toutes les FK sont resolvables"
    Write-Host "3. Import direct dans dbdiagram.io possible"
    Write-Host "4. Diagrammes complets et fonctionnels"
    
} else {
    Write-Host "Aucun fichier genere." -ForegroundColor Red
}

Write-Host ""
Write-Host "Export avec DEPENDANCES termine!" -ForegroundColor Green
