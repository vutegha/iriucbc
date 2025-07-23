# Test du Design IRI - Verification des composants
# Script PowerShell pour tester toutes les fonctionnalites UI

Write-Host "=== Test du Design IRI - Verification des composants ===" -ForegroundColor Green
Write-Host ""

# Configuration des URLs a tester
$baseUrl = "http://localhost/iriucbc"
$testUrls = @{
    "Page d'accueil" = "$baseUrl/"
    "Publications" = "$baseUrl/publications"
    "Test des composants" = "$baseUrl/test-components"
    "Actualites" = "$baseUrl/actualites"
    "Contact" = "$baseUrl/contact"
}

# Fonction pour verifier si un URL est accessible
function Test-UrlAccess {
    param($url, $name)
    
    try {
        $response = Invoke-WebRequest -Uri $url -Method Head -TimeoutSec 10
        if ($response.StatusCode -eq 200) {
            Write-Host "OK $name : Accessible" -ForegroundColor Green
            return $true
        } else {
            Write-Host "ERREUR $name : Erreur HTTP $($response.StatusCode)" -ForegroundColor Red
            return $false
        }
    } catch {
        Write-Host "ERREUR $name : Inaccessible - $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

# Test d'accessibilite des pages
Write-Host "1. Test d'accessibilite des pages:" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan

foreach ($test in $testUrls.GetEnumerator()) {
    Test-UrlAccess -url $test.Value -name $test.Key
}

Write-Host ""
Write-Host "2. Verification des composants CSS:" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan

# Verifier si les classes CSS sont definies dans le layout
$layoutPath = "resources/views/layouts/iri.blade.php"
if (Test-Path $layoutPath) {
    $layoutContent = Get-Content $layoutPath -Raw
    
    # Classes CSS a verifier
    $cssClasses = @(
        "btn-iri-primary",
        "btn-iri-secondary", 
        "btn-iri-outline",
        "btn-iri-accent",
        "card-iri",
        "table-iri",
        "form-input",
        "form-label",
        "badge-iri",
        "alert-iri-success",
        "alert-iri-error",
        "link-iri"
    )
    
    foreach ($class in $cssClasses) {
        if ($layoutContent -match $class) {
            Write-Host "OK Classe CSS '$class' definie" -ForegroundColor Green
        } else {
            Write-Host "MANQUE Classe CSS '$class' manquante" -ForegroundColor Red
        }
    }
} else {
    Write-Host "ERREUR Fichier layout non trouve: $layoutPath" -ForegroundColor Red
}

Write-Host ""
Write-Host "3. Verification des fichiers de vue:" -ForegroundColor Cyan
Write-Host "====================================" -ForegroundColor Cyan

# Fichiers de vue a verifier
$viewFiles = @(
    "resources/views/home.blade.php",
    "resources/views/publications.blade.php",
    "resources/views/partials/menu.blade.php",
    "resources/views/partials/footer.blade.php",
    "resources/views/test-components.blade.php"
)

foreach ($file in $viewFiles) {
    if (Test-Path $file) {
        Write-Host "OK Vue trouvee: $file" -ForegroundColor Green
    } else {
        Write-Host "MANQUE Vue manquante: $file" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "4. Test des fonctionnalites JavaScript:" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan

# Verifier la presence d'Alpine.js dans les fichiers
$alpineFiles = @(
    "resources/views/partials/menu.blade.php",
    "resources/views/partials/footer.blade.php"
)

foreach ($file in $alpineFiles) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        if ($content -match "x-data" -or $content -match "alpine") {
            Write-Host "OK Alpine.js detecte dans: $file" -ForegroundColor Green
        } else {
            Write-Host "ATTENTION Alpine.js non detecte dans: $file" -ForegroundColor Yellow
        }
    }
}

Write-Host ""
Write-Host "5. Verification des assets:" -ForegroundColor Cyan
Write-Host "===========================" -ForegroundColor Cyan

# Verifier les assets importants
$assets = @(
    "public/assets/img/logos/ucbc-2.png",
    "public/assets/img/iri.jpg",
    "public/assets/img/research.jpg"
)

foreach ($asset in $assets) {
    if (Test-Path $asset) {
        Write-Host "OK Asset trouve: $asset" -ForegroundColor Green
    } else {
        Write-Host "MANQUE Asset manquant: $asset" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "6. Test des routes:" -ForegroundColor Cyan
Write-Host "==================" -ForegroundColor Cyan

# Verifier les routes importantes
$routeFile = "routes/web.php"
if (Test-Path $routeFile) {
    $routeContent = Get-Content $routeFile -Raw
    
    # Verifications specifiques des routes
    if ($routeContent -match "Route::get.*'/'") {
        Write-Host "OK Route d'accueil definie" -ForegroundColor Green
    } else {
        Write-Host "MANQUE Route d'accueil manquante" -ForegroundColor Red
    }
    
    if ($routeContent -match "Route::get.*'/publications'") {
        Write-Host "OK Route publications definie" -ForegroundColor Green
    } else {
        Write-Host "MANQUE Route publications manquante" -ForegroundColor Red
    }
    
    if ($routeContent -match "Route::get.*'/test-components'") {
        Write-Host "OK Route test-components definie" -ForegroundColor Green
    } else {
        Write-Host "MANQUE Route test-components manquante" -ForegroundColor Red
    }
    
    if ($routeContent -match "Route::post.*'/newsletter-subscribe'") {
        Write-Host "OK Route newsletter definie" -ForegroundColor Green
    } else {
        Write-Host "MANQUE Route newsletter manquante" -ForegroundColor Red
    }
} else {
    Write-Host "ERREUR Fichier de routes non trouve: $routeFile" -ForegroundColor Red
}

Write-Host ""
Write-Host "7. Recommandations:" -ForegroundColor Cyan
Write-Host "==================" -ForegroundColor Cyan

Write-Host "- Tester l'inscription newsletter sur plusieurs pages" -ForegroundColor Yellow
Write-Host "- Verifier la responsivite sur mobile et tablette" -ForegroundColor Yellow
Write-Host "- Tester les animations et transitions" -ForegroundColor Yellow
Write-Host "- Valider l'accessibilite (contraste, focus, etc.)" -ForegroundColor Yellow
Write-Host "- Tester les formulaires de contact" -ForegroundColor Yellow
Write-Host "- Verifier les liens de navigation" -ForegroundColor Yellow
Write-Host "- Tester le dropdown des programmes" -ForegroundColor Yellow

Write-Host ""
Write-Host "=== Test termine ===" -ForegroundColor Green
Write-Host "Consulter la page http://localhost/iriucbc/test-components pour un test visuel complet" -ForegroundColor Cyan
