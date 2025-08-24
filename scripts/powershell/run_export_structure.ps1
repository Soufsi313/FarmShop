# Export des schemas FarmShop - STRUCTURE SEULEMENT pour diagrammes
# Genere le: 2025-07-14

Write-Host "FARMSHOP DATABASE - Export structures pour diagrammes" -ForegroundColor Cyan
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

# Options mysqldump POUR STRUCTURE SEULEMENT (pas de donnees)
$options = "--no-data --routines --triggers --add-drop-table --single-transaction"

# Definition des 5 schemas (sans systeme)
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
}

Write-Host "Demarrage de l'export STRUCTURE SEULEMENT..." -ForegroundColor Yellow
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
    
    try {
        Write-Host "   Export STRUCTURE en cours..." -ForegroundColor Yellow
        
        # Demander le mot de passe une seule fois
        if ($currentSchema -eq 1) {
            $password = Read-Host "   Mot de passe MySQL (root)" -AsSecureString
            $passwordText = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))
        }
        
        # Executer la commande STRUCTURE SEULEMENT
        $fullCommand = "$mysqlPath -u $username --password=$passwordText $database $($schema.tables) $options"
        Invoke-Expression $fullCommand | Out-File -FilePath $filename -Encoding UTF8
        
        if (Test-Path $filename) {
            $fileSize = (Get-Item $filename).Length
            Write-Host "   Export reussi ($fileSize octets) - STRUCTURE SEULEMENT" -ForegroundColor Green
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
        Write-Host "OK $($file.Name) ($size KB) - STRUCTURE SEULEMENT" -ForegroundColor Green
    }
    
    Write-Host ""
    Write-Host "PERFECT POUR DB DIAGRAM:" -ForegroundColor Yellow
    Write-Host "1. Importer chaque fichier dans dbdiagram.io" 
    Write-Host "2. Seulement les CREATE TABLE (pas d'INSERT)"
    Write-Host "3. Relations automatiquement detectees"
    Write-Host "4. Diagrammes propres et lisibles"
    Write-Host ""
    Write-Host "Schemas generes: 5 (systeme supprime)" -ForegroundColor Cyan
    
} else {
    Write-Host "Aucun fichier genere. Verifiez les erreurs ci-dessus." -ForegroundColor Red
}

Write-Host ""
Write-Host "Export STRUCTURE termine!" -ForegroundColor Green
