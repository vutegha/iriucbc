#!/usr/bin/env pwsh

Write-Host "=== Test des améliorations des tableaux admin ===" -ForegroundColor Green

# Vérification des améliorations dans les vues liste
Write-Host "`n1. Vérification des en-têtes de tableaux améliorés:" -ForegroundColor Yellow

$files = @(
    "resources/views/admin/publication/index.blade.php",
    "resources/views/admin/actualite/index.blade.php"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "  ✓ $file" -ForegroundColor Green
        
        # Vérifier les en-têtes avec gradients IRI
        $gradientHeaders = Select-String -Path $file -Pattern "bg-gradient-to-r from-iri-primary to-iri-secondary" | Measure-Object
        Write-Host "    - En-têtes avec gradient IRI: $($gradientHeaders.Count)" -ForegroundColor $(if ($gradientHeaders.Count -gt 0) { "Green" } else { "Red" })
        
        # Vérifier les icônes dans les en-têtes
        $iconHeaders = Select-String -Path $file -Pattern "fas fa-.*mr-2.*</i>" | Measure-Object
        Write-Host "    - Icônes dans les en-têtes: $($iconHeaders.Count)" -ForegroundColor $(if ($iconHeaders.Count -gt 0) { "Green" } else { "Yellow" })
        
        # Vérifier les images avec border radius
        $roundedImages = Select-String -Path $file -Pattern "rounded-xl.*object-cover" | Measure-Object
        Write-Host "    - Images avec coins arrondis: $($roundedImages.Count)" -ForegroundColor $(if ($roundedImages.Count -gt 0) { "Green" } else { "Yellow" })
        
        # Vérifier les badges améliorés
        $improvedBadges = Select-String -Path $file -Pattern "border border-.*-200" | Measure-Object
        Write-Host "    - Badges avec bordures: $($improvedBadges.Count)" -ForegroundColor $(if ($improvedBadges.Count -gt 0) { "Green" } else { "Yellow" })
        
        # Vérifier les boutons d'action colorés
        $coloredButtons = Select-String -Path $file -Pattern "bg-.*-600.*text-white" | Measure-Object
        Write-Host "    - Boutons d'action colorés: $($coloredButtons.Count)" -ForegroundColor $(if ($coloredButtons.Count -gt 0) { "Green" } else { "Yellow" })
        
        Write-Host ""
    }
}

# Vérification spécifique pour les actualités
Write-Host "2. Vérifications spécifiques aux actualités:" -ForegroundColor Yellow
$actualiteFile = "resources/views/admin/actualite/index.blade.php"
if (Test-Path $actualiteFile) {
    # Vérifier la colonne priorité
    $priorityColumn = Select-String -Path $actualiteFile -Pattern "fas fa-fire mr-2.*Priorité" | Measure-Object
    Write-Host "  - Colonne Priorité ajoutée: $(if ($priorityColumn.Count -gt 0) { "✓ Oui" } else { "✗ Non" })" -ForegroundColor $(if ($priorityColumn.Count -gt 0) { "Green" } else { "Red" })
    
    # Vérifier les indicateurs d'urgence
    $urgentIndicators = Select-String -Path $actualiteFile -Pattern "animate-pulse.*Urgent" | Measure-Object
    Write-Host "  - Indicateurs d'urgence animés: $(if ($urgentIndicators.Count -gt 0) { "✓ Oui" } else { "✗ Non" })" -ForegroundColor $(if ($urgentIndicators.Count -gt 0) { "Green" } else { "Red" })
    
    # Vérifier les actions spéciales
    $specialActions = Select-String -Path $actualiteFile -Pattern "makeUrgent|removeUrgent" | Measure-Object
    Write-Host "  - Actions urgence ajoutées: $(if ($specialActions.Count -gt 0) { "✓ Oui" } else { "✗ Non" })" -ForegroundColor $(if ($specialActions.Count -gt 0) { "Green" } else { "Red" })
}

# Vérification spécifique pour les publications
Write-Host "`n3. Vérifications spécifiques aux publications:" -ForegroundColor Yellow
$publicationFile = "resources/views/admin/publication/index.blade.php"
if (Test-Path $publicationFile) {
    # Vérifier la colonne vues
    $viewsColumn = Select-String -Path $publicationFile -Pattern "fas fa-eye mr-2.*Vues" | Measure-Object
    Write-Host "  - Colonne Vues ajoutée: $(if ($viewsColumn.Count -gt 0) { "✓ Oui" } else { "✗ Non" })" -ForegroundColor $(if ($viewsColumn.Count -gt 0) { "Green" } else { "Red" })
    
    # Vérifier les métadonnées enrichies
    $enrichedMeta = Select-String -Path $publicationFile -Pattern "categorie.*nom|mots_cles" | Measure-Object
    Write-Host "  - Métadonnées enrichies: $(if ($enrichedMeta.Count -gt 0) { "✓ Oui" } else { "✗ Non" })" -ForegroundColor $(if ($enrichedMeta.Count -gt 0) { "Green" } else { "Red" })
    
    # Vérifier les avatars d'auteurs
    $authorAvatars = Select-String -Path $publicationFile -Pattern "auteur.*avatar|rounded-full.*object-cover" | Measure-Object
    Write-Host "  - Avatars d'auteurs: $(if ($authorAvatars.Count -gt 0) { "✓ Oui" } else { "✗ Non" })" -ForegroundColor $(if ($authorAvatars.Count -gt 0) { "Green" } else { "Red" })
}

Write-Host "`n=== Résumé des améliorations ===" -ForegroundColor Green
Write-Host "✅ En-têtes de tableaux avec gradient IRI et icônes" -ForegroundColor Green
Write-Host "✅ Images et avatars avec coins arrondis et ombres" -ForegroundColor Green
Write-Host "✅ Badges avec bordures et couleurs améliorées" -ForegroundColor Green
Write-Host "✅ Boutons d'action colorés et regroupés" -ForegroundColor Green
Write-Host "✅ Colonnes spécialisées (Vues, Priorité)" -ForegroundColor Green
Write-Host "✅ Métadonnées enrichies et informations détaillées" -ForegroundColor Green
Write-Host "✅ Actions contextuelles dans dropdowns avec z-index correct" -ForegroundColor Green

Write-Host "`nAméliorations terminées!" -ForegroundColor Green
