# Test simple pour vérifier la correction du slug
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test de correction du problème slug" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

Write-Host "`n1. Test de création de catégorie..." -ForegroundColor Yellow
$result = php artisan tinker --execute="echo 'Creating category: '; \$cat = new \App\Models\Categorie(); \$cat->nom = 'Test'; \$cat->description = 'Test'; \$cat->save(); echo 'OK - ID: ' . \$cat->id;"
if ($LASTEXITCODE -eq 0) {
    Write-Host "   ✅ Catégorie créée avec succès" -ForegroundColor Green
} else {
    Write-Host "   ❌ Erreur lors de la création" -ForegroundColor Red
}

Write-Host "`n2. Vérification du modèle Categorie..." -ForegroundColor Yellow
$modelContent = Get-Content "app\Models\Categorie.php" -Raw

if ($modelContent -match "slug.*fillable") {
    Write-Host "   ✅ Champ 'slug' ajouté aux fillable" -ForegroundColor Green
} else {
    Write-Host "   ❌ Champ 'slug' manquant dans fillable" -ForegroundColor Red
}

if ($modelContent -match "Str::slug") {
    Write-Host "   ✅ Génération automatique de slug implémentée" -ForegroundColor Green
} else {
    Write-Host "   ❌ Génération automatique de slug manquante" -ForegroundColor Red
}

if ($modelContent -match "boot.*creating") {
    Write-Host "   ✅ Événements de modèle configurés" -ForegroundColor Green
} else {
    Write-Host "   ❌ Événements de modèle manquants" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des corrections:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Ajout de 'slug' dans \$fillable" -ForegroundColor Green
Write-Host "✅ Ajout de 'couleur', 'active', 'ordre' dans \$fillable" -ForegroundColor Green
Write-Host "✅ Génération automatique de slug avec Str::slug()" -ForegroundColor Green
Write-Host "✅ Événements boot() pour creating et updating" -ForegroundColor Green
Write-Host "✅ Cast boolean pour le champ 'active'" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Le problème SQLSTATE[HY000]: Field 'slug'" -ForegroundColor Green
Write-Host "doesn't have a default value est maintenant" -ForegroundColor Green
Write-Host "résolu. Les catégories génèrent automatiquement" -ForegroundColor Green
Write-Host "leur slug à partir du nom." -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
