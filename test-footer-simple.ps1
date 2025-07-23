# Script de test pour les statistiques du footer - IRI UCBC
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test des statistiques du footer - IRI UCBC" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Verification de la migration
Write-Host "`n1. Verification de la migration des beneficiaires..." -ForegroundColor Yellow
try {
    # Creer un projet de test
    $testProjectCommand = @"
    `$projet = App\Models\Projet::create([
        'nom' => 'Projet Test Footer',
        'description' => 'Projet de test pour les statistiques du footer',
        'etat' => 'en cours',
        'beneficiaires_hommes' => 50,
        'beneficiaires_femmes' => 75,
        'beneficiaires_total' => 125
    ]);
    echo 'Projet cree avec ID: ' . `$projet->id;
"@
    
    $result = php artisan tinker --execute="$testProjectCommand"
    Write-Host "   OK: $result" -ForegroundColor Green
} catch {
    Write-Host "   Erreur lors de la creation du projet de test" -ForegroundColor Red
}

# Test 2: Verifier les statistiques
Write-Host "`n2. Test des statistiques..." -ForegroundColor Yellow
try {
    $totalProjets = php artisan tinker --execute="echo App\Models\Projet::count();"
    Write-Host "   OK: Nombre total de projets: $totalProjets" -ForegroundColor Green
    
    $totalBeneficiaires = php artisan tinker --execute="echo App\Models\Projet::getTotalBeneficiaires();"
    Write-Host "   OK: Total beneficiaires: $totalBeneficiaires" -ForegroundColor Green
} catch {
    Write-Host "   Erreur lors du test du modele" -ForegroundColor Red
}

# Test 3: Verifier le footer
Write-Host "`n3. Test du footer..." -ForegroundColor Yellow
if (Test-Path "resources/views/partials/footer.blade.php") {
    Write-Host "   OK: Fichier footer trouve" -ForegroundColor Green
    
    $footerContent = Get-Content "resources/views/partials/footer.blade.php" -Raw
    if ($footerContent -match "getTotalProjets|getTotalBeneficiaires") {
        Write-Host "   OK: Statistiques dynamiques configurees" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Statistiques dynamiques non configurees" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Fichier footer non trouve" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test termine!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
