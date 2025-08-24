# Script de conversion HTML vers Word
# Convertit rapport_final_farmshop.html en rapport_final_farmshop.docx

param(
    [string]$InputFile = "rapport_final_farmshop.html",
    [string]$OutputFile = "rapport_final_farmshop.docx"
)

Write-Host "🔄 Conversion HTML vers Word en cours..." -ForegroundColor Yellow

try {
    # Vérifier si le fichier HTML existe
    if (-not (Test-Path $InputFile)) {
        throw "Le fichier $InputFile n'existe pas"
    }

    # Créer une instance de Word
    Write-Host "📝 Démarrage de Microsoft Word..." -ForegroundColor Cyan
    $Word = New-Object -ComObject Word.Application
    $Word.Visible = $false
    
    # Ouvrir le fichier HTML
    Write-Host "📂 Ouverture du fichier HTML..." -ForegroundColor Cyan
    $Document = $Word.Documents.Open((Resolve-Path $InputFile).Path)
    
    # Configurer les propriétés du document
    Write-Host "⚙️ Configuration du document..." -ForegroundColor Cyan
    $Document.BuiltInDocumentProperties("Title") = "Rapport Final FarmShop"
    $Document.BuiltInDocumentProperties("Author") = "Équipe FarmShop"
    $Document.BuiltInDocumentProperties("Subject") = "Projet e-commerce agricole avec système de location"
    $Document.BuiltInDocumentProperties("Keywords") = "FarmShop, Laravel, e-commerce, agriculture, location"
    $Document.BuiltInDocumentProperties("Comments") = "Rapport final complet du projet FarmShop - Plateforme e-commerce agricole"
    
    # Ajuster les marges (en points : 1 inch = 72 points)
    $Document.PageSetup.TopMargin = 72    # 1 inch
    $Document.PageSetup.BottomMargin = 72 # 1 inch
    $Document.PageSetup.LeftMargin = 90   # 1.25 inches
    $Document.PageSetup.RightMargin = 90  # 1.25 inches
    
    # Configurer l'orientation et le format
    $Document.PageSetup.Orientation = 0  # Portrait
    $Document.PageSetup.PaperSize = 9    # A4
    
    # Supprimer le fichier de sortie s'il existe
    if (Test-Path $OutputFile) {
        Remove-Item $OutputFile -Force
        Write-Host "🗑️ Ancien fichier supprimé" -ForegroundColor Yellow
    }
    
    # Sauvegarder en format Word
    Write-Host "💾 Sauvegarde au format Word..." -ForegroundColor Cyan
    $Document.SaveAs2((Join-Path (Get-Location) $OutputFile), 16) # 16 = wdFormatDocumentDefault
    
    # Fermer le document et Word
    Write-Host "🔒 Fermeture du document..." -ForegroundColor Cyan
    $Document.Close()
    $Word.Quit()
    
    # Libérer les objets COM
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($Document) | Out-Null
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($Word) | Out-Null
    [System.GC]::Collect()
    [System.GC]::WaitForPendingFinalizers()
    
    # Vérifier que le fichier a été créé
    if (Test-Path $OutputFile) {
        $FileInfo = Get-Item $OutputFile
        Write-Host "✅ Conversion réussie !" -ForegroundColor Green
        Write-Host "📄 Fichier créé : $($FileInfo.Name)" -ForegroundColor Green
        Write-Host "📏 Taille : $([math]::Round($FileInfo.Length / 1MB, 2)) MB" -ForegroundColor Green
        Write-Host "📅 Date : $($FileInfo.LastWriteTime)" -ForegroundColor Green
        
        # Ouvrir le fichier
        $OpenChoice = Read-Host "Voulez-vous ouvrir le fichier Word ? (o/n)"
        if ($OpenChoice -eq "o" -or $OpenChoice -eq "O") {
            Start-Process $OutputFile
        }
    } else {
        throw "Échec de la création du fichier Word"
    }
    
} catch {
    Write-Host "❌ Erreur lors de la conversion : $($_.Exception.Message)" -ForegroundColor Red
    
    # Nettoyer en cas d'erreur
    try {
        if ($Document) { $Document.Close() }
        if ($Word) { $Word.Quit() }
    } catch {
        # Ignorer les erreurs de nettoyage
    }
    
    exit 1
} finally {
    Write-Host "🏁 Script terminé" -ForegroundColor White
}
