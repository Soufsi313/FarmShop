# Script pour generer les diagrammes via service en ligne PlantUML avec le bon encodage

Add-Type -AssemblyName System.IO.Compression

Write-Host "Generation des diagrammes UML via service en ligne (encodage corrige)..." -ForegroundColor Green

$diagrams = @(
    @{ name = "user_authentication"; file = "docs\diagrams\user_authentication.puml" },
    @{ name = "user_product_catalog"; file = "docs\diagrams\user_product_catalog.puml" },
    @{ name = "user_cart_systems"; file = "docs\diagrams\user_cart_systems.puml" },
    @{ name = "user_order_management"; file = "docs\diagrams\user_order_management.puml" },
    @{ name = "user_blog"; file = "docs\diagrams\user_blog.puml" },
    @{ name = "user_newsletter"; file = "docs\diagrams\user_newsletter.puml" }
)

# Fonction pour encoder selon le format PlantUML
function ConvertTo-PlantUMLEncoding($text) {
    # Tableau de caractères PlantUML
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
            
            Write-Host "  URL: $url" -ForegroundColor Gray
            
            # Télécharger l'image
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
Write-Host "Resultat: $successCount/$($diagrams.Count) diagrammes generes" -ForegroundColor Cyan
