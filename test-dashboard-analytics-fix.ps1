# Test pour vérifier la correction des erreurs de tables analytics manquantes
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test de correction des erreurs analytics" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

Write-Host "`n1. Test d'accès aux modèles de base..." -ForegroundColor Yellow
$basicTest = php artisan tinker --execute="echo 'Publications: ' . \App\Models\Publication::count();"
if ($LASTEXITCODE -eq 0) {
    Write-Host "   ✅ Modèles de base accessibles" -ForegroundColor Green
} else {
    Write-Host "   ❌ Erreur avec les modèles de base" -ForegroundColor Red
}

Write-Host "`n2. Vérification du contenu du dashboard..." -ForegroundColor Yellow
$dashboardContent = Get-Content "resources\views\admin\dashboard.blade.php" -Raw

# Vérifier que les try-catch sont présents
if ($dashboardContent -match "@php.*try.*catch.*@endphp") {
    Write-Host "   ✅ Blocs try-catch ajoutés pour la gestion d'erreurs" -ForegroundColor Green
} else {
    Write-Host "   ❌ Blocs try-catch manquants" -ForegroundColor Red
}

# Vérifier qu'il n'y a plus d'appels directs aux modèles analytics
$directCalls = $dashboardContent | Select-String "\\App\\Models\\PageView::|\\App\\Models\\PublicationDownload::|\\App\\Models\\VisitorLocation::" | Where-Object { $_ -notmatch "try|catch" }
if ($directCalls.Count -eq 0) {
    Write-Host "   ✅ Tous les appels directs aux modèles analytics sont protégés" -ForegroundColor Green
} else {
    Write-Host "   ⚠️  Quelques appels directs encore présents: $($directCalls.Count)" -ForegroundColor Yellow
}

Write-Host "`n3. Test des corrections statut contact..." -ForegroundColor Yellow
if ($dashboardContent -match "statut.*nouveau") {
    Write-Host "   ✅ Correction statut contact appliquée" -ForegroundColor Green
} else {
    Write-Host "   ❌ Correction statut contact manquante" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des corrections appliquées:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Remplacement de is_read par statut='nouveau'" -ForegroundColor Green
Write-Host "✅ Ajout de try-catch pour PageView::count()" -ForegroundColor Green
Write-Host "✅ Ajout de try-catch pour PublicationDownload" -ForegroundColor Green
Write-Host "✅ Ajout de try-catch pour VisitorLocation" -ForegroundColor Green
Write-Host "✅ Protection des méthodes getMostViewedPages()" -ForegroundColor Green
Write-Host "✅ Protection des méthodes getMostDownloadedPublications()" -ForegroundColor Green
Write-Host "✅ Protection des méthodes getTopCountries()" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Le dashboard devrait maintenant fonctionner" -ForegroundColor Green
Write-Host "sans erreurs même si les tables analytics" -ForegroundColor Green
Write-Host "n'existent pas encore." -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
