# Script pour organiser les fichiers et les masquer visuellement sur GitHub
# Exécuter avec: .\organize_files.ps1

Write-Host "🗂️  Organisation des fichiers pour masquer visuellement sur GitHub..." -ForegroundColor Green

# Documentation et guides
$docFiles = @(
    "ACCOUNT_DELETION_SYSTEM.md",
    "ADMIN_BLOG_IMPROVEMENTS.md", 
    "COMMIT_INSPECTION_SYSTEM.md",
    "CONCLUSION_PROJET_FARMSHOP_UNIQUE.md",
    "COOKIE_SYSTEM.md",
    "COOKIE_SYSTEM_GUIDE.md",
    "CORRECTIONS_EMAIL.md",
    "DATABASE_SCHEMA_GUIDE.md",
    "DBDIAGRAM_DEPENDENCIES_GUIDE.md",
    "DBDIAGRAM_GUIDE.md",
    "DIAGRAMMES_UML_ACHAT.md",
    "EMAIL_SYSTEM_DOCUMENTATION.md",
    "EMAIL_VERIFICATION_SUCCESS.md",
    "GUIDE_TEST_FINAL.md",
    "LOCATION_SURVEILLANCE_GUIDE.md",
    "LOGO_DOCUMENTATION.md",
    "MANUEL_UTILISATION.md",
    "NGROK_WEBHOOK_SETUP.md",
    "README_LOCAL.md",
    "RELEASE_ALPHA.md",
    "RELEASE_BETA.md",
    "RELEASE_BETA_LOCATION.md",
    "RENTAL_CONSTRAINTS.md",
    "RESTORE_BUTTONS_FIX.md",
    "RESTORE_REDIRECT_FIX.md",
    "SIGNALEMENTS_CORRECTIONS_RESUME.md",
    "STOCK_ALERTS_GUIDE.md",
    "STOCK_CONSTRAINTS_DOCUMENTATION.md",
    "STRIPE_INTEGRATION_DOCUMENTATION.md",
    "SYSTEME_TRANSITIONS_AUTOMATIQUES.md",
    "TRANSLATION_SYSTEM_COMPLETE.md",
    "UPLOAD_SYSTEM_GUIDE.md",
    "USER_FILTER_SYSTEM.md",
    "VERSION.md",
    "production_reliability_guide.md",
    "description_logo_rapport.md"
)

# Schémas et diagrammes
$schemaFiles = @(
    "SCHEMAS_CORRIGES_FINAL.md",
    "SCHEMAS_FONCTIONNELS.md", 
    "SCHEMAS_ULTRA_REDUITS.md",
    "SCHEMAS_USAGE_GUIDE.md",
    "database_schema_data.json",
    "farmshop_database_schema.html",
    "farmshop_database_schema.svg",
    "navigation_achat.dot",
    "navigation_location.dot",
    "rapport_final_vitamiel.html"
)

# Scripts PowerShell
$psFiles = @(
    "configure_railway_upload.ps1",
    "convert_to_word.ps1",
    "export_schemas.ps1",
    "farmshop.ps1", 
    "generate_db_diagrams.ps1",
    "generate_diagrams_fixed.ps1",
    "generate_uml_diagrams.ps1",
    "open_all_templates.ps1",
    "queue-worker-auto.ps1",
    "railway_config.ps1",
    "run_export.ps1",
    "run_export_small_schemas.ps1",
    "run_export_structure.ps1",
    "run_export_with_dependencies.ps1",
    "start_presentation_mode.ps1",
    "start_queue_service.ps1"
)

# Scripts Batch  
$batFiles = @(
    "auto-start.bat",
    "check-system.bat",
    "clean-local.bat",
    "farmshop.bat",
    "monitor_rentals.bat",
    "queue-worker-auto.bat",
    "start-local.bat",
    "start_automatic_service.bat", 
    "start_presentation_mode.bat",
    "start_queue_worker.bat"
)

# Fichiers HTML et assets
$htmlFiles = @(
    "favicon_generator.html",
    "icones_navigation_farmshop.html",
    "logo_farmshop.html",
    "logo_farmshop_concept4_agrandi.html", 
    "logo_test_multilingue.html",
    "logos_export_png.html",
    "template_test_2_dÉmarrage_de_location.html",
    "test_error_messages_improvements.html",
    "test_location_terminee_2025-08-17_19-28-11.html",
    "test_rappel_email_2025-08-17_19-17-14.html",
    "test_rental_started_email.html"
)

# Fichiers de données et CSV
$dataFiles = @(
    "Organigramme_FarmShop_Complet.csv",
    "Organigramme_FarmShop_Optimise.csv", 
    "Organigramme_FarmShop_Optimise.xlsx",
    "Organigramme_FarmShop_Optimise_grouped.csv",
    "icons_farmshop_social.svg"
)

# Fichiers de backup et misc
$backupFiles = @(
    "OrderReturnController_backup.php",
    "cleanup_project.sh",
    "export_schemas.sh",
    "run_export.sh",
    "convert_to_word.py",
    "generate_navigation_diagrams.py",
    "nixpacks.toml",
    "plantuml.jar",
    "VERSION"
)

# Fonction pour déplacer les fichiers
function Move-FilesToFolder {
    param(
        [array]$Files,
        [string]$DestinationFolder,
        [string]$Description
    )
    
    Write-Host "`n📁 Déplacement $Description..." -ForegroundColor Yellow
    
    foreach ($file in $Files) {
        if (Test-Path $file) {
            Move-Item $file $DestinationFolder -Force
            Write-Host "   ✅ $file → $DestinationFolder" -ForegroundColor Gray
        } else {
            Write-Host "   ⚠️  $file non trouvé" -ForegroundColor DarkYellow
        }
    }
}
}

# Déplacer les fichiers
Move-FilesToFolder $docFiles "docs\guides" "de la documentation"
Move-FilesToFolder $schemaFiles "docs\schemas" "des schémas et diagrammes"  
Move-FilesToFolder $psFiles "scripts\powershell" "des scripts PowerShell"
Move-FilesToFolder $batFiles "scripts\batch" "des scripts Batch"
Move-FilesToFolder $htmlFiles "assets\html" "des fichiers HTML"
Move-FilesToFolder $dataFiles "assets\data" "des fichiers de données"
Move-FilesToFolder $backupFiles "backups" "des backups et fichiers divers"

Write-Host "`n✅ Organisation terminée !" -ForegroundColor Green
Write-Host "📂 Structure créée :" -ForegroundColor Cyan
Write-Host "   📁 docs/guides/ - Documentation et guides"
Write-Host "   📁 docs/schemas/ - Schémas et diagrammes"  
Write-Host "   📁 scripts/powershell/ - Scripts PowerShell"
Write-Host "   📁 scripts/batch/ - Scripts Batch"
Write-Host "   📁 assets/html/ - Fichiers HTML"
Write-Host "   📁 assets/data/ - Fichiers de données"
Write-Host "   📁 backups/ - Backups et divers"

Write-Host "`n🔄 Prochaines étapes :" -ForegroundColor Magenta
Write-Host "   1. git add ."
Write-Host "   2. git commit -m 'refactor: Organisation des fichiers en dossiers pour améliorer la lisibilité'"
Write-Host "   3. git push"
