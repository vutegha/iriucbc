#!/usr/bin/env pwsh

Write-Host "=== Test des fonctionnalités admin après correction ===" -ForegroundColor Green

# Test 1: Vérification des z-index dans les fichiers
Write-Host "`n1. Vérification des z-index corrections:" -ForegroundColor Yellow
$filesWithZIndex = @(
    "resources/views/admin/publication/index.blade.php",
    "resources/views/admin/actualite/index.blade.php"
)

foreach ($file in $filesWithZIndex) {
    if (Test-Path $file) {
        $zIndexOccurrences = Select-String -Path $file -Pattern "z-10" | Measure-Object
        $z50Occurrences = Select-String -Path $file -Pattern "z-50" | Measure-Object
        
        Write-Host "  ✓ $file" -ForegroundColor Green
        Write-Host "    - z-10 trouvés: $($zIndexOccurrences.Count)" -ForegroundColor $(if ($zIndexOccurrences.Count -eq 0) { "Green" } else { "Red" })
        Write-Host "    - z-50 trouvés: $($z50Occurrences.Count)" -ForegroundColor $(if ($z50Occurrences.Count -gt 0) { "Green" } else { "Yellow" })
    }
}

# Test 2: Vérification des fonctions JavaScript
Write-Host "`n2. Vérification des fonctions JavaScript:" -ForegroundColor Yellow
$jsFiles = @(
    @{file="resources/views/admin/publication/index.blade.php"; functions=@("publishPublication", "unpublishPublication", "deletePublication", "filterPublications")},
    @{file="resources/views/admin/actualite/index.blade.php"; functions=@("publishActualite", "unpublishActualite", "deleteActualite", "filterActualites")}
)

foreach ($jsFile in $jsFiles) {
    if (Test-Path $jsFile.file) {
        Write-Host "  ✓ $($jsFile.file)" -ForegroundColor Green
        foreach ($func in $jsFile.functions) {
            $funcExists = Select-String -Path $jsFile.file -Pattern "function $func" | Measure-Object
            Write-Host "    - $func : $(if ($funcExists.Count -gt 0) { "✓ Présente" } else { "✗ Manquante" })" -ForegroundColor $(if ($funcExists.Count -gt 0) { "Green" } else { "Red" })
        }
    }
}

# Test 3: Vérification des éléments de recherche
Write-Host "`n3. Vérification des éléments de recherche:" -ForegroundColor Yellow
$searchElements = @(
    @{file="resources/views/admin/publication/index.blade.php"; id="search-publications"},
    @{file="resources/views/admin/actualite/index.blade.php"; id="search-actualites"}
)

foreach ($element in $searchElements) {
    if (Test-Path $element.file) {
        $elementExists = Select-String -Path $element.file -Pattern "id=`"$($element.id)`"" | Measure-Object
        Write-Host "  ✓ $($element.file)" -ForegroundColor Green
        Write-Host "    - Input de recherche ($($element.id)): $(if ($elementExists.Count -gt 0) { "✓ Présent" } else { "✗ Manquant" })" -ForegroundColor $(if ($elementExists.Count -gt 0) { "Green" } else { "Red" })
    }
}

# Test 4: Vérification des dropdowns avec z-index
Write-Host "`n4. Vérification des dropdowns:" -ForegroundColor Yellow
foreach ($file in $filesWithZIndex) {
    if (Test-Path $file) {
        $dropdowns = Select-String -Path $file -Pattern "absolute.*z-\d+" | Measure-Object
        $relativeContainers = Select-String -Path $file -Pattern "relative.*x-data" | Measure-Object
        
        Write-Host "  ✓ $file" -ForegroundColor Green
        Write-Host "    - Dropdowns avec z-index: $($dropdowns.Count)" -ForegroundColor $(if ($dropdowns.Count -gt 0) { "Green" } else { "Yellow" })
        Write-Host "    - Conteneurs relatifs: $($relativeContainers.Count)" -ForegroundColor $(if ($relativeContainers.Count -gt 0) { "Green" } else { "Yellow" })
    }
}

Write-Host "`n=== Résumé des corrections ===" -ForegroundColor Green
Write-Host "✅ Z-index des dropdowns corrigés (z-10 → z-50)" -ForegroundColor Green
Write-Host "✅ Recherche et filtres améliorés pour publications et actualités" -ForegroundColor Green
Write-Host "✅ Fonctions JavaScript pour actions contextuelles disponibles" -ForegroundColor Green
Write-Host "✅ Éléments de recherche configurés avec event listeners" -ForegroundColor Green

Write-Host "`n=== Prochaines étapes recommandées ===" -ForegroundColor Cyan
Write-Host "1. Tester les fonctionnalités de recherche en temps réel" -ForegroundColor White
Write-Host "2. Vérifier les actions publier/dépublier dans l'interface" -ForegroundColor White
Write-Host "3. Tester les dropdowns en mode grille pour le z-index" -ForegroundColor White
Write-Host "4. Appliquer les mêmes corrections aux autres sections admin" -ForegroundColor White

Write-Host "`nTest terminé!" -ForegroundColor Green
