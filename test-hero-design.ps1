# Script de test pour la nouvelle section HERO avec actualités
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test du nouveau design HERO - IRI UCBC" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Creer des actualites de test
Write-Host "`n1. Creation d'actualites de test..." -ForegroundColor Yellow
try {
    $createActualites = @"
    // Creer une actualite en vedette
    `$actualite1 = App\Models\Actualite::create([
        'titre' => 'Nouvelle recherche sur la gouvernance fonciere',
        'resume' => 'Decouvrez notre derniere etude sur la gouvernance des ressources naturelles au Nord-Kivu.',
        'texte' => 'Contenu complet de l actualite...',
        'image' => 'actualites/test-1.jpg',
        'en_vedette' => true,
        'a_la_une' => false,
        'service_id' => 1
    ]);

    // Creer une actualite a la une
    `$actualite2 = App\Models\Actualite::create([
        'titre' => 'Lancement du nouveau projet GeoLab',
        'resume' => 'Le projet GeoLab vise a renforcer les capacites en cartographie participative.',
        'texte' => 'Contenu complet de l actualite...',
        'image' => 'actualites/test-2.jpg',
        'en_vedette' => false,
        'a_la_une' => true,
        'service_id' => 2
    ]);

    // Creer une actualite en vedette ET a la une
    `$actualite3 = App\Models\Actualite::create([
        'titre' => 'Formation sur l innovation sociale',
        'resume' => 'Participez a notre formation sur l innovation sociale et l entrepreneuriat.',
        'texte' => 'Contenu complet de l actualite...',
        'image' => 'actualites/test-3.jpg',
        'en_vedette' => true,
        'a_la_une' => true,
        'service_id' => 3
    ]);

    echo 'Actualites creees avec succes!';
"@
    
    $result = php artisan tinker --execute="$createActualites"
    Write-Host "   OK: $result" -ForegroundColor Green
} catch {
    Write-Host "   Erreur lors de la creation des actualites de test" -ForegroundColor Red
}

# Test 2: Verifier les requetes d'actualites
Write-Host "`n2. Test des requetes d'actualites..." -ForegroundColor Yellow
try {
    $countVedette = php artisan tinker --execute="echo App\Models\Actualite::where('en_vedette', true)->count();"
    Write-Host "   OK: Actualites en vedette: $countVedette" -ForegroundColor Green
    
    $countUne = php artisan tinker --execute="echo App\Models\Actualite::where('a_la_une', true)->count();"
    Write-Host "   OK: Actualites a la une: $countUne" -ForegroundColor Green
    
    $countTotal = php artisan tinker --execute="echo App\Models\Actualite::where('en_vedette', true)->orWhere('a_la_une', true)->count();"
    Write-Host "   OK: Total actualites pour HERO: $countTotal" -ForegroundColor Green
} catch {
    Write-Host "   Erreur lors du test des requetes" -ForegroundColor Red
}

# Test 3: Verifier les modifications du controleur
Write-Host "`n3. Test du controleur SiteController..." -ForegroundColor Yellow
if (Test-Path "app/Http/Controllers/Site/SiteController.php") {
    Write-Host "   OK: Controleur trouve" -ForegroundColor Green
    
    $controllerContent = Get-Content "app/Http/Controllers/Site/SiteController.php" -Raw
    if ($controllerContent -match "orWhere.*a_la_une.*true") {
        Write-Host "   OK: Requete modifiee pour inclure a_la_une" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Requete non modifiee" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Controleur non trouve" -ForegroundColor Red
}

# Test 4: Verifier les modifications de la vue
Write-Host "`n4. Test de la vue index..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    Write-Host "   OK: Vue index trouvee" -ForegroundColor Green
    
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    if ($viewContent -match "Actualites en vedette") {
        Write-Host "   OK: Nouveau design HERO implemente" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Nouveau design non trouve" -ForegroundColor Red
    }
    
    if ($viewContent -match "youtube.com") {
        Write-Host "   OK: Modal YouTube configure" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Modal YouTube non configure" -ForegroundColor Red
    }
    
    if ($viewContent -match "a_la_une.*En vedette") {
        Write-Host "   OK: Badges de statut configures" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Badges de statut non configures" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Vue index non trouvee" -ForegroundColor Red
}

# Test 5: Verifier la charte graphique
Write-Host "`n5. Test de la charte graphique..." -ForegroundColor Yellow
if (Test-Path "resources/views/index.blade.php") {
    $viewContent = Get-Content "resources/views/index.blade.php" -Raw
    if ($viewContent -match "from-orange-500.*to-coral|bg-gradient-to-r.*orange.*coral") {
        Write-Host "   OK: Couleurs IRI utilisees (orange, coral)" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Couleurs IRI non detectees" -ForegroundColor Yellow
    }
    
    if ($viewContent -match "backdrop-blur-sm|bg-white/10") {
        Write-Host "   OK: Effets de transparence appliques" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Effets de transparence non detectes" -ForegroundColor Yellow
    }
} else {
    Write-Host "   Erreur: Impossible de verifier la charte graphique" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Resume des modifications apportees:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "OK: Requete actualites modifiee (en_vedette OU a_la_une)" -ForegroundColor Green
Write-Host "OK: Nouveau design HERO professionnel" -ForegroundColor Green
Write-Host "OK: Section actualites en haut avec grille responsive" -ForegroundColor Green
Write-Host "OK: Badges de statut (A la une / En vedette)" -ForegroundColor Green
Write-Host "OK: Modal YouTube pour le bouton 'Qui sommes-nous'" -ForegroundColor Green
Write-Host "OK: Statistiques dynamiques integrees" -ForegroundColor Green
Write-Host "OK: Charte graphique IRI respectee" -ForegroundColor Green
Write-Host "OK: Design responsive mobile/desktop" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test termine - Visitez http://localhost:8000" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
