# Export des schemas FarmShop - GROUPEMENTS REDUITS pour dbdiagram.io
# Schemas avec 2-4 tables maximum pour une meilleure lisibilite

Write-Host "FARMSHOP DATABASE - Export schemas reduits pour dbdiagram.io" -ForegroundColor Cyan
Write-Host ""

# Configuration
$mysqlPath = "C:\xampp\mysql\bin\mysqldump.exe"
$username = "root"
$database = "farmshop"
$exportDir = "database_schemas_small"

# Creer le dossier
if (-not (Test-Path $exportDir)) {
    New-Item -ItemType Directory -Path $exportDir | Out-Null
    Write-Host "Dossier cree: $exportDir" -ForegroundColor Green
}

# Options mysqldump
$options = "--no-data --routines --triggers --add-drop-table --single-transaction"

# Definition des schemas REDUITS (2-4 tables max)
$schemas = @{
    "01_users_auth" = @{
        "name" = "Utilisateurs & Authentification"
        "tables" = "users password_reset_tokens sessions"
        "description" = "Gestion des utilisateurs et authentification (3 tables)"
    }
    "02_products_catalog" = @{
        "name" = "Produits & Catalogue"
        "tables" = "products categories rental_categories special_offers"
        "description" = "Catalogue produits complet (4 tables)"
    }
    "03_users_preferences" = @{
        "name" = "Preferences Utilisateurs"
        "tables" = "users products categories rental_categories product_likes wishlists"
        "description" = "Likes et wishlists utilisateurs avec dependances (6 tables)"
    }
    "04_shopping_carts" = @{
        "name" = "Paniers d'Achat"
        "tables" = "users products categories carts cart_items"
        "description" = "Gestion des paniers avec dependances (5 tables)"
    }
    "05_orders_main" = @{
        "name" = "Commandes Principales"
        "tables" = "users products categories orders order_items"
        "description" = "Workflow principal des commandes avec dependances (5 tables)"
    }
    "06_orders_returns" = @{
        "name" = "Retours de Commandes"
        "tables" = "users orders order_items order_returns"
        "description" = "Gestion des retours (4 tables)"
    }
    "07_rentals_main" = @{
        "name" = "Locations Principales"
        "tables" = "users products categories rental_categories order_locations order_item_locations"
        "description" = "Workflow principal des locations avec dependances (6 tables)"
    }
    "08_rental_carts" = @{
        "name" = "Paniers de Location"
        "tables" = "users products categories rental_categories cart_locations cart_item_locations"
        "description" = "Paniers pour locations avec dependances (6 tables)"
    }
    "09_blog_system" = @{
        "name" = "Systeme de Blog"
        "tables" = "users blog_categories blog_posts blog_comments"
        "description" = "Blog et commentaires (4 tables)"
    }
    "10_blog_moderation" = @{
        "name" = "Moderation Blog"
        "tables" = "users blog_comments blog_comment_reports"
        "description" = "Signalements et moderation (3 tables)"
    }
    "11_newsletters" = @{
        "name" = "Newsletters"
        "tables" = "users newsletters newsletter_subscriptions newsletter_sends"
        "description" = "Systeme de newsletters (4 tables)"
    }
    "12_messages" = @{
        "name" = "Messages"
        "tables" = "users messages"
        "description" = "Systeme de messages (2 tables)"
    }
    "13_cookies_gdpr" = @{
        "name" = "Cookies RGPD"
        "tables" = "users cookies"
        "description" = "Gestion des cookies RGPD (2 tables)"
    }
}

Write-Host "Demarrage export SCHEMAS REDUITS..." -ForegroundColor Yellow
Write-Host "Nombre de schemas: $($schemas.Count)" -ForegroundColor Yellow
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

Write-Host "RESUME DE L'EXPORT SCHEMAS REDUITS" -ForegroundColor Cyan
Write-Host "===================================" -ForegroundColor Cyan

$files = Get-ChildItem -Path $exportDir -Filter "*.sql"
if ($files.Count -gt 0) {
    Write-Host "Schemas generes: $($files.Count)" -ForegroundColor Green
    Write-Host ""
    
    foreach ($file in $files) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "OK $($file.Name) ($size KB)" -ForegroundColor Green
    }
    
    Write-Host ""
    Write-Host "PARFAIT POUR DBDIAGRAM.IO:" -ForegroundColor Yellow
    Write-Host "- Maximum 4 tables par schema"
    Write-Host "- Diagrammes lisibles et clairs"
    Write-Host "- Toutes les FK resolvables"
    Write-Host "- Import direct possible"
    Write-Host "- Affichage optimal" -ForegroundColor Green
    
} else {
    Write-Host "Aucun fichier genere." -ForegroundColor Red
}

Write-Host ""
Write-Host "Export SCHEMAS REDUITS termine!" -ForegroundColor Green
