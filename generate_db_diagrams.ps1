# Script de generation automatique des diagrammes de base de donnees thematiques

Write-Host "Generation des diagrammes de base de donnees thematiques..." -ForegroundColor Green

# Liste des diagrammes a generer
$diagrams = @(
    @{ name = "db_users_authentication"; file = "docs\diagrams\db_users_authentication.puml" },
    @{ name = "db_products_catalog"; file = "docs\diagrams\db_products_catalog.puml" },
    @{ name = "db_shopping_carts"; file = "docs\diagrams\db_shopping_carts.puml" },
    @{ name = "db_orders_rentals"; file = "docs\diagrams\db_orders_rentals.puml" },
    @{ name = "db_blog_system"; file = "docs\diagrams\db_blog_system.puml" },
    @{ name = "db_newsletter_system"; file = "docs\diagrams\db_newsletter_system.puml" }
)

function ConvertTo-PlantUMLEncoding($text) {
    # Tableau de caracteres PlantUML
    $plantumlChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_"
    
    # Compresser avec Deflate
    $bytes = [System.Text.Encoding]::UTF8.GetBytes($text)
    $ms = New-Object System.IO.MemoryStream
    $deflate = New-Object System.IO.Compression.DeflateStream($ms, [System.IO.Compression.CompressionMode]::Compress)
    $deflate.Write($bytes, 0, $bytes.Length)
    $deflate.Close()
    $compressed = $ms.ToArray()
    
    # Encoder en Base64 PlantUML
    $result = ""
    for ($i = 0; $i -lt $compressed.Length; $i += 3) {
        $b1 = if ($i -lt $compressed.Length) { $compressed[$i] } else { 0 }
        $b2 = if (($i + 1) -lt $compressed.Length) { $compressed[$i + 1] } else { 0 }
        $b3 = if (($i + 2) -lt $compressed.Length) { $compressed[$i + 2] } else { 0 }
        
        $result += $plantumlChars[($b1 -shr 2) -band 63]
        $result += $plantumlChars[(($b1 -band 3) -shl 4) -bor (($b2 -shr 4) -band 15)]
        $result += $plantumlChars[(($b2 -band 15) -shl 2) -bor (($b3 -shr 6) -band 3)]
        $result += $plantumlChars[$b3 -band 63]
    }
    
    return $result
}

$successCount = 0

foreach ($diagram in $diagrams) {
    Write-Host "Generation: $($diagram.name)..." -ForegroundColor Yellow
    
    if (Test-Path $diagram.file) {
        try {
            # Lire le contenu du fichier PUML
            $content = Get-Content $diagram.file -Raw -Encoding UTF8
            
            # Encoder avec l'algorithme PlantUML
            $encoded = ConvertTo-PlantUMLEncoding $content
            
            # URL du service PlantUML
            $url = "http://www.plantuml.com/plantuml/png/$encoded"
            
            # Nom du fichier de sortie
            $outputFile = $diagram.file -replace ".puml", ".png"
            
            # Telecharger l'image
            Invoke-WebRequest -Uri $url -OutFile $outputFile
            
            if (Test-Path $outputFile) {
                $size = [math]::Round((Get-Item $outputFile).Length / 1KB, 2)
                Write-Host "  Succes: $outputFile ($size Ko)" -ForegroundColor Green
                $successCount++
            } else {
                Write-Host "  Echec de generation" -ForegroundColor Red
            }
        } catch {
            Write-Host "  Erreur: $($_.Exception.Message)" -ForegroundColor Red
        }
    } else {
        Write-Host "  Fichier introuvable: $($diagram.file)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Resultat: $successCount/$($diagrams.Count) diagrammes de DB generes" -ForegroundColor Cyan

# Lister les fichiers generes
if ($successCount -gt 0) {
    Write-Host ""
    Write-Host "Diagrammes de base de donnees generes:" -ForegroundColor Cyan
    Get-ChildItem "docs\diagrams\db_*.png" | ForEach-Object {
        $size = [math]::Round($_.Length / 1KB, 2)
        Write-Host "  $($_.Name) ($size Ko)" -ForegroundColor White
    }
}
