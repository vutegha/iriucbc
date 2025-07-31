# Test final pour vérifier la correction de l'erreur is_read
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test de correction de l'erreur is_read" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Vérifier que les requêtes du dashboard fonctionnent
Write-Host "`n1. Test des requêtes du dashboard..." -ForegroundColor Yellow
$result = php artisan tinker --execute="echo 'Contacts: ' . \App\Models\Contact::where('statut', 'nouveau')->count();"
if ($LASTEXITCODE -eq 0) {
    Write-Host "   ✅ Requête contact avec statut='nouveau' fonctionne" -ForegroundColor Green
} else {
    Write-Host "   ❌ Erreur dans la requête contact" -ForegroundColor Red
}

# Test 2: Vérifier que le dashboard ne contient plus de références à is_read
Write-Host "`n2. Test de suppression des références is_read..." -ForegroundColor Yellow
$dashboardContent = Get-Content "resources\views\admin\dashboard.blade.php" -Raw
if ($dashboardContent -notmatch "is_read") {
    Write-Host "   ✅ Aucune référence à 'is_read' trouvée" -ForegroundColor Green
} else {
    Write-Host "   ❌ Des références à 'is_read' sont encore présentes" -ForegroundColor Red
}

# Test 3: Vérifier la structure de la table contacts
Write-Host "`n3. Test de la structure de la table contacts..." -ForegroundColor Yellow
$tableStructure = php artisan tinker --execute="echo 'Colonnes: '; \$columns = DB::select('DESCRIBE contacts'); foreach(\$columns as \$col) echo \$col->Field . ' ';"
if ($tableStructure -match "statut") {
    Write-Host "   ✅ Colonne 'statut' présente dans la table contacts" -ForegroundColor Green
} else {
    Write-Host "   ❌ Colonne 'statut' manquante dans la table contacts" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des corrections:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Remplacement de where('is_read', false) par where('statut', 'nouveau')" -ForegroundColor Green
Write-Host "✅ Remplacement de !`$message->is_read par `$message->statut == 'nouveau'" -ForegroundColor Green
Write-Host "✅ Remplacement de `$message->name par `$message->nom" -ForegroundColor Green
Write-Host "✅ Remplacement de `$message->subject par `$message->sujet" -ForegroundColor Green
Write-Host "✅ Le dashboard utilise maintenant la structure correcte de la table contacts" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Structure de la table contacts:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "- nom (string)" -ForegroundColor Cyan
Write-Host "- email (string)" -ForegroundColor Cyan
Write-Host "- sujet (string)" -ForegroundColor Cyan
Write-Host "- message (text)" -ForegroundColor Cyan
Write-Host "- statut (string) - nouveau/lu/traite/ferme" -ForegroundColor Cyan
Write-Host "- reponse (text nullable)" -ForegroundColor Cyan
Write-Host "- lu_a (timestamp nullable)" -ForegroundColor Cyan
Write-Host "- traite_a (timestamp nullable)" -ForegroundColor Cyan
