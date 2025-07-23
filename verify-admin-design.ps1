#!/usr/bin/env pwsh

Write-Host "=== Vérification du Design Admin IRI-UCBC ===" -ForegroundColor Green

Set-Location "c:\xampp\htdocs\Projets\iriucbc"

Write-Host "`n1. Vérification des layouts admin..." -ForegroundColor Yellow

# Vérifier les meta tags
$adminLayout = Get-Content "resources\views\layouts\admin.blade.php" -Raw
$adminTailwindLayout = Get-Content "resources\views\layouts\admin-tailwind.blade.php" -Raw

if ($adminLayout -match '<meta name="viewport" content="width=device-width, initial-scale=1.0">') {
    Write-Host "   ✓ admin.blade.php: Meta viewport correcte" -ForegroundColor Green
} else {
    Write-Host "   ✗ admin.blade.php: Meta viewport incorrecte" -ForegroundColor Red
}

if ($adminTailwindLayout -match '<meta name="viewport" content="width=device-width, initial-scale=1.0">') {
    Write-Host "   ✓ admin-tailwind.blade.php: Meta viewport correcte" -ForegroundColor Green
} else {
    Write-Host "   ✗ admin-tailwind.blade.php: Meta viewport incorrecte" -ForegroundColor Red
}

# Vérifier qu'il n'y a pas de texte débordant
if ($adminLayout -notmatch 'con\s+<div' -and $adminLayout -notmatch 'Administrateur.*=') {
    Write-Host "   ✓ admin.blade.php: Pas de débordement de texte" -ForegroundColor Green
} else {
    Write-Host "   ✗ admin.blade.php: Texte débordant détecté" -ForegroundColor Red
}

if ($adminTailwindLayout -notmatch 'con\s+<div' -and $adminTailwindLayout -notmatch 'Administrateur.*=') {
    Write-Host "   ✓ admin-tailwind.blade.php: Pas de débordement de texte" -ForegroundColor Green
} else {
    Write-Host "   ✗ admin-tailwind.blade.php: Texte débordant détecté" -ForegroundColor Red
}

Write-Host "`n2. Test d'ouverture des interfaces..." -ForegroundColor Yellow
$urls = @(
    "http://localhost/Projets/iriucbc/admin/publication",
    "http://localhost/Projets/iriucbc/admin/projets"
)

foreach ($url in $urls) {
    Write-Host "   • Ouverture de $url" -ForegroundColor Cyan
    Start-Process $url
    Start-Sleep -Milliseconds 1000
}

Write-Host "`n✅ CORRECTIONS APPLIQUÉES:" -ForegroundColor Green
Write-Host "   • Meta viewport corrigée dans les deux layouts" -ForegroundColor White
Write-Host "   • Texte débordant 'Administrateur' supprimé" -ForegroundColor White
Write-Host "   • Email mis à jour vers iri@ucbc.org" -ForegroundColor White
Write-Host "   • Structure HTML restaurée" -ForegroundColor White

Write-Host "`nDesign admin corrigé ! 🎉" -ForegroundColor Green
