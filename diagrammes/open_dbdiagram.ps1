# Script PowerShell pour ouvrir les schemas dans dbdiagram.io
# Usage: .\open_dbdiagram.ps1 [schema_name]

param(
    [Parameter(Position=0)]
    [ValidateSet("users_auth", "products_categories", "orders_purchases", "rentals_management", "communication_blog", "complete_overview")]
    [string]$Schema = "users_auth"
)

$schemasPath = "."

# Definition des schemas disponibles
$schemas = @{
    "users_auth" = @{
        "file" = "dbml_users_auth.dbml"
        "name" = "Utilisateurs & Authentification"
    }
    "products_categories" = @{
        "file" = "dbml_products_categories.dbml" 
        "name" = "Produits & Categories"
    }
    "orders_purchases" = @{
        "file" = "dbml_orders_purchases.dbml"
        "name" = "Commandes & Achats"
    }
    "rentals_management" = @{
        "file" = "dbml_rentals_management.dbml"
        "name" = "Locations & Gestion"
    }
    "communication_blog" = @{
        "file" = "dbml_communication_blog.dbml"
        "name" = "Communication & Blog"
    }
    "complete_overview" = @{
        "file" = "dbml_complete_overview.dbml"
        "name" = "Vue d'ensemble complete"
    }
}

function Show-Menu {
    Write-Host "`nSchemas de base de donnees FarmShop" -ForegroundColor Green
    Write-Host "==========================================" -ForegroundColor Green
    Write-Host "1. Utilisateurs & Authentification" -ForegroundColor Cyan
    Write-Host "2. Produits & Categories" -ForegroundColor Cyan  
    Write-Host "3. Commandes & Achats" -ForegroundColor Cyan
    Write-Host "4. Locations & Gestion" -ForegroundColor Cyan
    Write-Host "5. Communication & Blog" -ForegroundColor Cyan
    Write-Host "6. Vue d'ensemble complete" -ForegroundColor Yellow
    Write-Host "0. Quitter" -ForegroundColor Red
    Write-Host "`n==========================================`n" -ForegroundColor Green
}

function Copy-SchemaToClipboard {
    param([string]$FilePath, [string]$SchemaName)
    
    if (Test-Path $FilePath) {
        $content = Get-Content $FilePath -Raw
        $content | Set-Clipboard
        Write-Host "Schema '$SchemaName' copie dans le presse-papiers !" -ForegroundColor Green
        Write-Host "Collez-le dans dbdiagram.io" -ForegroundColor Yellow
        return $true
    } else {
        Write-Host "Fichier non trouve: $FilePath" -ForegroundColor Red
        return $false
    }
}

function Open-DbDiagram {
    Write-Host "Ouverture de dbdiagram.io..." -ForegroundColor Blue
    Start-Process "https://dbdiagram.io/"
}

# Si un schema specifique est demande
if ($Schema -and $schemas.ContainsKey($Schema)) {
    $schemaInfo = $schemas[$Schema]
    $filePath = Join-Path $schemasPath $schemaInfo.file
    
    Write-Host "Traitement du schema: $($schemaInfo.name)" -ForegroundColor Green
    
    if (Copy-SchemaToClipboard -FilePath $filePath -SchemaName $schemaInfo.name) {
        Open-DbDiagram
        Write-Host "`nInstructions:" -ForegroundColor Yellow
        Write-Host "1. Attendez que dbdiagram.io se charge" -ForegroundColor White
        Write-Host "2. Cliquez sur 'Create new diagram'" -ForegroundColor White
        Write-Host "3. Collez le contenu (Ctrl+V) dans l'editeur de gauche" -ForegroundColor White
        Write-Host "4. Le diagramme s'affiche automatiquement a droite" -ForegroundColor White
        Write-Host "5. Exportez en PNG/PDF avec le bouton 'Export'" -ForegroundColor White
    }
    exit
}

Write-Host "Au revoir !" -ForegroundColor Green
