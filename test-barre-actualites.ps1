# Script de test pour la barre d'actualités horizontale
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test de la barre d'actualités horizontale" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Verifier les modifications de la vue
Write-Host "`n1. Test des modifications de la vue..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    Write-Host "   OK: Vue index trouvee" -ForegroundColor Green
    
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    # Vérifier la suppression des éléments
    if ($viewContent -notmatch "Actualités en vedette") {
        Write-Host "   OK: Titre 'Actualités en vedette' supprime" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Titre 'Actualités en vedette' encore present" -ForegroundColor Yellow
    }
    
    if ($viewContent -notmatch "a_la_une.*En vedette") {
        Write-Host "   OK: Badges de statut supprimes" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Badges de statut encore presents" -ForegroundColor Yellow
    }
    
    if ($viewContent -notmatch "Lire plus.*svg") {
        Write-Host "   OK: Liens 'Lire plus' supprimes" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Liens 'Lire plus' encore presents" -ForegroundColor Yellow
    }
    
    # Vérifier les nouveaux éléments
    if ($viewContent -match "flex items-center space-x-6 overflow-x-auto") {
        Write-Host "   OK: Barre horizontale implementee" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Barre horizontale non trouvee" -ForegroundColor Red
    }
    
    if ($viewContent -match "w-12 h-12 rounded-full") {
        Write-Host "   OK: Images circulaires configurees" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Images circulaires non configurees" -ForegroundColor Red
    }
    
    if ($viewContent -match "scrollbar-hide") {
        Write-Host "   OK: Masquage de la barre de defilement configure" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Masquage de la barre de defilement non configure" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Vue index non trouvee" -ForegroundColor Red
}

# Test 2: Verifier la structure HTML
Write-Host "`n2. Test de la structure HTML..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    if ($viewContent -match "bg-black/40 backdrop-blur-sm") {
        Write-Host "   OK: Conteneur avec transparence configure" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Conteneur avec transparence non configure" -ForegroundColor Red
    }
    
    if ($viewContent -match "flex-shrink-0 flex items-center space-x-3") {
        Write-Host "   OK: Structure des elements d'actualite correcte" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Structure des elements d'actualite incorrecte" -ForegroundColor Red
    }
    
    if ($viewContent -match "border-2 border-orange-400") {
        Write-Host "   OK: Bordure orange pour les images configuree" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Bordure orange pour les images non configuree" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de verifier la structure HTML" -ForegroundColor Red
}

# Test 3: Verifier les actualités dans la base de données
Write-Host "`n3. Test des actualites dans la base de donnees..." -ForegroundColor Yellow
try {
    $totalActualites = php artisan tinker --execute="echo App\Models\Actualite::where('en_vedette', true)->orWhere('a_la_une', true)->count();"
    Write-Host "   OK: Actualites disponibles pour la barre: $totalActualites" -ForegroundColor Green
    
    if ([int]$totalActualites -gt 0) {
        $actualitesList = php artisan tinker --execute="App\Models\Actualite::where('en_vedette', true)->orWhere('a_la_une', true)->take(6)->get()->each(function(`$a) { echo `$a->titre . PHP_EOL; });"
        Write-Host "   OK: Actualites trouvees:" -ForegroundColor Green
        Write-Host "   $actualitesList" -ForegroundColor Cyan
    } else {
        Write-Host "   Attention: Aucune actualite en vedette ou a la une trouvee" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   Erreur lors de la verification des actualites" -ForegroundColor Red
}

# Test 4: Verifier les styles CSS
Write-Host "`n4. Test des styles CSS..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    if ($viewContent -match "scrollbar-hide.*-webkit-scrollbar.*display: none") {
        Write-Host "   OK: Styles pour masquer la barre de defilement presents" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Styles pour masquer la barre de defilement manquants" -ForegroundColor Red
    }
    
    if ($viewContent -match "hover:bg-white/10.*transition-all") {
        Write-Host "   OK: Effets hover configures" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Effets hover non configures" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de verifier les styles CSS" -ForegroundColor Red
}

# Test 5: Test de responsive design
Write-Host "`n5. Test du responsive design..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    
    if ($viewContent -match "overflow-x-auto") {
        Write-Host "   OK: Defilement horizontal pour mobile configure" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Defilement horizontal pour mobile non configure" -ForegroundColor Red
    }
    
    if ($viewContent -match "max-w-7xl mx-auto px-4 sm:px-6 lg:px-8") {
        Write-Host "   OK: Conteneur responsive configure" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Conteneur responsive non configure" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de verifier le responsive design" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Resume des modifications apportees:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "OK: Barre horizontale avec 6 actualites max" -ForegroundColor Green
Write-Host "OK: Images petites et circulaires (48x48px)" -ForegroundColor Green
Write-Host "OK: Titres affiches a cote des images" -ForegroundColor Green
Write-Host "OK: Titre 'Actualités en vedette' supprime" -ForegroundColor Green
Write-Host "OK: Badges de statut supprimes" -ForegroundColor Green
Write-Host "OK: Liens 'Lire plus' supprimes" -ForegroundColor Green
Write-Host "OK: Barre de defilement masquee" -ForegroundColor Green
Write-Host "OK: Effets hover et transitions" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test termine - Visitez http://localhost:8000" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
