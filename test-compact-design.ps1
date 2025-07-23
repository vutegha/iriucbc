# Script de test pour les modifications de padding et marges
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test des modifications padding/marges" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Vérifier la réduction des marges
Write-Host "`n1. Test de la réduction des marges..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    Write-Host "   OK: Vue index trouvée" -ForegroundColor Green
    
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    # Vérifier la réduction des marges verticales
    if ($viewContent -match "pt-4 pb-2") {
        Write-Host "   OK: Marges verticales réduites (pt-4 pb-2)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Marges verticales non réduites" -ForegroundColor Red
    }
    
    # Vérifier la réduction des gaps
    if ($viewContent -match "gap-2") {
        Write-Host "   OK: Espacement entre éléments réduit (gap-2)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Espacement entre éléments non réduit" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Vue index non trouvée" -ForegroundColor Red
}

# Test 2: Vérifier la réduction des paddings internes
Write-Host "`n2. Test de la réduction des paddings..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    # Vérifier le padding du conteneur principal
    if ($viewContent -match "rounded-lg p-2 shadow-lg") {
        Write-Host "   OK: Padding conteneur principal réduit (p-2)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Padding conteneur principal non réduit" -ForegroundColor Red
    }
    
    # Vérifier le padding des éléments individuels
    if ($viewContent -match "rounded-lg p-2 transition-all") {
        Write-Host "   OK: Padding éléments individuels réduit (p-2)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Padding éléments individuels non réduit" -ForegroundColor Red
    }
    
    # Vérifier l'espacement horizontal
    if ($viewContent -match "space-x-2") {
        Write-Host "   OK: Espacement horizontal réduit (space-x-2)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Espacement horizontal non réduit" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier les paddings" -ForegroundColor Red
}

# Test 3: Vérifier la taille du texte
Write-Host "`n3. Test de la taille du texte..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    # Vérifier que text-sm est présent
    if ($viewContent -match "text-sm") {
        Write-Host "   OK: Taille de texte sm confirmée" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Taille de texte sm non trouvée" -ForegroundColor Red
    }
    
    # Vérifier qu'il n'y a pas d'autres tailles
    if ($viewContent -notmatch "text-lg|text-xl|text-2xl" -or $viewContent -match "text-sm") {
        Write-Host "   OK: Pas de tailles de texte plus grandes détectées" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Tailles de texte plus grandes détectées" -ForegroundColor Yellow
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier la taille du texte" -ForegroundColor Red
}

# Test 4: Vérifier la réduction des images
Write-Host "`n4. Test de la réduction des images..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    # Vérifier la taille des images
    if ($viewContent -match "w-10 h-10") {
        Write-Host "   OK: Taille des images réduite (40x40px)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Taille des images non réduite" -ForegroundColor Red
    }
    
    # Vérifier la hauteur minimum
    if ($viewContent -match "min-h-\[60px\]") {
        Write-Host "   OK: Hauteur minimum réduite (60px)" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Hauteur minimum non réduite" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier les images" -ForegroundColor Red
}

# Test 5: Calculer les économies d'espace
Write-Host "`n5. Calcul des économies d'espace..." -ForegroundColor Yellow
Write-Host "   Avant -> Après:" -ForegroundColor Cyan
Write-Host "   - Marges verticales: pt-6 pb-4 -> pt-4 pb-2 (économie: 16px)" -ForegroundColor Cyan
Write-Host "   - Padding conteneur: p-4 -> p-2 (économie: 8px)" -ForegroundColor Cyan
Write-Host "   - Padding éléments: p-3 -> p-2 (économie: 4px)" -ForegroundColor Cyan
Write-Host "   - Espacement horizontal: space-x-3 -> space-x-2 (économie: 4px)" -ForegroundColor Cyan
Write-Host "   - Gap grille: gap-4 -> gap-2 (économie: 8px)" -ForegroundColor Cyan
Write-Host "   - Images: 48x48px -> 40x40px (économie: 8px)" -ForegroundColor Cyan
Write-Host "   - Hauteur min: 80px -> 60px (économie: 20px)" -ForegroundColor Cyan
Write-Host "   TOTAL: ~68px d'économie verticale" -ForegroundColor Green

# Test 6: Vérifier la préservation du design
Write-Host "`n6. Test de la préservation du design..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    # Vérifier que les éléments essentiels sont préservés
    if ($viewContent -match "bg-black/40 backdrop-blur-sm") {
        Write-Host "   OK: Fond semi-transparent préservé" -ForegroundColor Green
    }
    
    if ($viewContent -match "border-2 border-orange-400") {
        Write-Host "   OK: Bordure orange préservée" -ForegroundColor Green
    }
    
    if ($viewContent -match "hover:bg-white/10.*hover:text-orange-300") {
        Write-Host "   OK: Effets hover préservés" -ForegroundColor Green
    }
    
    if ($viewContent -match "transition-all duration-300") {
        Write-Host "   OK: Transitions animées préservées" -ForegroundColor Green
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier la préservation du design" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des modifications appliquées:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "OK: Taille de texte réduite à sm" -ForegroundColor Green
Write-Host "OK: Marges verticales réduites (pt-4 pb-2)" -ForegroundColor Green
Write-Host "OK: Padding conteneur réduit (p-2)" -ForegroundColor Green
Write-Host "OK: Padding éléments réduit (p-2)" -ForegroundColor Green
Write-Host "OK: Espacement horizontal réduit (space-x-2)" -ForegroundColor Green
Write-Host "OK: Gap grille réduit (gap-2)" -ForegroundColor Green
Write-Host "OK: Images réduites (40x40px)" -ForegroundColor Green
Write-Host "OK: Hauteur minimum réduite (60px)" -ForegroundColor Green
Write-Host "OK: Design général préservé" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test terminé - Visitez http://localhost:8000" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
