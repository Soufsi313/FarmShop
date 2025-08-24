# 🗄️ Script PowerShell d'export des schémas FarmShop
# Usage: .\export_schemas.ps1 -Username "root" -Password "" -Database "farmshop"

param(
    [string]$Username = "root",
    [string]$Password = "",
    [string]$Database = "farmshop",
    [string]$ExportDir = "database_schemas"
)

# Créer le dossier d'export
if (!(Test-Path $ExportDir)) {
    New-Item -ItemType Directory -Path $ExportDir | Out-Null
}

Write-Host "🚀 Export des schémas FarmShop Database" -ForegroundColor Green
Write-Host "Base de données: $Database" -ForegroundColor Yellow
Write-Host "Dossier de sortie: $ExportDir" -ForegroundColor Yellow
Write-Host ""

# Options communes mysqldump
$DumpOptions = "--routines --triggers --add-drop-table --single-transaction --lock-tables=false"

# Fonction pour exporter un schéma
function Export-Schema {
    param($Name, $Description, $Tables, $FileNumber)
    
    Write-Host "$Description..." -ForegroundColor Cyan
    
    $fileName = "$ExportDir\${FileNumber}_schema_${Name}.sql"
    $tablesStr = $Tables -join " "
    
    # Construire la commande mysqldump
    if ($Password -eq "") {
        $cmd = "mysqldump -u $Username $Database $tablesStr $DumpOptions"
    } else {
        $cmd = "mysqldump -u $Username -p$Password $Database $tablesStr $DumpOptions"
    }
    
    # Exécuter et rediriger vers le fichier
    Invoke-Expression $cmd | Out-File -FilePath $fileName -Encoding UTF8
    
    # Ajouter un en-tête au fichier
    $header = @"
-- ================================================================
-- 🗄️ FARMSHOP DATABASE SCHEMA: $($Name.ToUpper())
-- ================================================================
-- Généré le: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")
-- Version: Alpha v1.0.0
-- 
-- ⚠️ IMPORTANT: Ce schéma fait partie d'un ensemble de 6 schémas.
-- Consultez DATABASE_SCHEMA_GUIDE.md pour les dépendances.
-- ================================================================

"@
    
    # Lire le contenu existant et ajouter l'en-tête
    $content = Get-Content $fileName -Raw
    $newContent = $header + $content
    Set-Content -Path $fileName -Value $newContent -Encoding UTF8
}

# 1. SCHÉMA PRODUITS (À FAIRE EN PREMIER - Base)
Export-Schema -Name "products" -Description "📦 Export Schéma Produits" -FileNumber "01" -Tables @(
    "products", "categories", "rental_categories", "special_offers"
)

# 2. SCHÉMA UTILISATEURS  
Export-Schema -Name "users" -Description "👥 Export Schéma Utilisateurs" -FileNumber "02" -Tables @(
    "users", "password_reset_tokens", "sessions", "product_likes", "wishlists", "cookies"
)

# 3. SCHÉMA COMMANDES & ACHATS
Export-Schema -Name "orders" -Description "🛒 Export Schéma Commandes" -FileNumber "03" -Tables @(
    "orders", "order_items", "order_returns", "carts", "cart_items"
)

# 4. SCHÉMA LOCATIONS
Export-Schema -Name "rentals" -Description "🏠 Export Schéma Locations" -FileNumber "04" -Tables @(
    "order_locations", "order_item_locations", "cart_locations", "cart_item_locations"
)

# 5. SCHÉMA COMMUNICATION & MARKETING
Export-Schema -Name "communication" -Description "📢 Export Schéma Communication" -FileNumber "05" -Tables @(
    "messages", "blog_categories", "blog_posts", "blog_comments", "blog_comment_reports",
    "newsletters", "newsletter_subscriptions", "newsletter_sends"
)

# 6. SCHÉMA SYSTÈME
Export-Schema -Name "system" -Description "⚙️ Export Schéma Système" -FileNumber "06" -Tables @(
    "migrations", "cache", "cache_locks", "jobs", "job_batches", "failed_jobs"
)

Write-Host ""
Write-Host "✅ Export terminé avec succès!" -ForegroundColor Green
Write-Host "📁 Fichiers générés dans: $ExportDir\" -ForegroundColor Yellow
Write-Host ""

Write-Host "📋 Fichiers créés:" -ForegroundColor Cyan
Get-ChildItem $ExportDir -Filter "*.sql" | ForEach-Object {
    $size = [math]::Round($_.Length / 1KB, 2)
    Write-Host "   $($_.Name) ($size KB)" -ForegroundColor White
}

Write-Host ""
Write-Host "📖 Consultez DATABASE_SCHEMA_GUIDE.md pour utiliser ces schémas." -ForegroundColor Yellow
Write-Host "🎯 Ordre d'import recommandé: 01 -> 02 -> 03 -> 04 -> 05 -> 06" -ForegroundColor Yellow
