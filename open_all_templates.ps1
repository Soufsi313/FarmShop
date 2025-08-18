$templates = @(
    "template_test_1_confirmation_de_commande.html",
    "template_test_2_dÉmarrage_de_location.html", 
    "template_test_3_rappel_fin_de_location.html"
)

Write-Host "=== OUVERTURE DE TOUS LES TEMPLATES D'EMAIL ===" -ForegroundColor Green
Write-Host ""

foreach ($template in $templates) {
    if (Test-Path $template) {
        Write-Host "🌐 Ouverture de: $template" -ForegroundColor Yellow
        Start-Process $template
        Start-Sleep -Seconds 2  # Pause de 2 secondes entre chaque ouverture
    } else {
        Write-Host "❌ Fichier manquant: $template" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "✅ Tous les templates ont été ouverts dans votre navigateur !" -ForegroundColor Green
Write-Host "📝 Vous pouvez maintenant voir tous les designs d'email de location" -ForegroundColor Cyan
