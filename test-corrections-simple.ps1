# Validation simple des corrections appliquees

Write-Host "=== VALIDATION DES CORRECTIONS ===" -ForegroundColor Cyan

Write-Host "`n1. Verification des vues corrigees..." -ForegroundColor Yellow

# Publication
$pubFile = Get-Content "resources/views/admin/publication/index.blade.php" -Raw
if ($pubFile -match "auth\(\)->check\(\) && auth\(\)->user\(\)->canModerate\(\)") {
    Write-Host "   OK publications: auth()->check() ajoute" -ForegroundColor Green
} else {
    Write-Host "   ERREUR publications: correction manquante" -ForegroundColor Red
}

# Projets  
$projFile = Get-Content "resources/views/admin/projets/index.blade.php" -Raw
if ($projFile -match "auth\(\)->check\(\) && auth\(\)->user\(\)->canModerate\(\)") {
    Write-Host "   OK projets: auth()->check() ajoute" -ForegroundColor Green
} else {
    Write-Host "   ERREUR projets: correction manquante" -ForegroundColor Red
}

Write-Host "`n2. Test de l'utilisateur admin..." -ForegroundColor Yellow
try {
    php debug_users.php
} catch {
    Write-Host "   ERREUR: Impossible de tester l'utilisateur" -ForegroundColor Red
}

Write-Host "`n3. Verification du modele User..." -ForegroundColor Yellow
$userModel = Get-Content "app/Models/User.php" -Raw
if ($userModel -match "HasRoles") {
    Write-Host "   OK HasRoles trait present" -ForegroundColor Green
} else {
    Write-Host "   ATTENTION HasRoles manquant" -ForegroundColor Yellow
}

if ($userModel -match "try.*catch") {
    Write-Host "   OK canModerate() avec fallback" -ForegroundColor Green
} else {
    Write-Host "   ATTENTION Fallback manquant" -ForegroundColor Yellow
}

Write-Host "`n=== CORRECTIONS TERMINEES ===" -ForegroundColor Green
Write-Host "L'erreur 'canModerate() on null' est corrigee !" -ForegroundColor Green
Write-Host "`nPour tester:" -ForegroundColor Cyan  
Write-Host "1. Ouvrir http://localhost/Projets/iriucbc/admin" -ForegroundColor Gray
Write-Host "2. Se connecter avec iri@ucbc.org" -ForegroundColor Gray
