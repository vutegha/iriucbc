#!/usr/bin/env powershell
# Script de diagnostic pour le test d'email

Write-Host "=== DIAGNOSTIC TEST EMAIL IRI UCBC ===" -ForegroundColor Cyan
Write-Host ""

# Configuration
$BaseUrl = "http://localhost/projets/iriucbc/public"

Write-Host "1. Test de l'accès à l'interface admin..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/admin/email-settings" -Method GET -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Interface admin accessible" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Interface admin non accessible: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "2. Test de la configuration SMTP..." -ForegroundColor Yellow

# Tester avec la commande Artisan
cd c:\xampp\htdocs\projets\iriucbc
try {
    $emailTest = php artisan contact:test-email --email="test@example.com"
    Write-Host "✅ Commande d'email de test exécutée" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur lors du test d'email: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "3. Vérification des logs récents..." -ForegroundColor Yellow

if (Test-Path "storage/logs/laravel.log") {
    $logs = Get-Content "storage/logs/laravel.log" -Tail 20
    Write-Host "📋 Dernières entrées des logs:" -ForegroundColor Blue
    $logs | ForEach-Object { Write-Host "   $_" -ForegroundColor Gray }
} else {
    Write-Host "⚠️ Fichier de logs non trouvé" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "4. Test de validation de route..." -ForegroundColor Yellow

try {
    $routeTest = php artisan route:list | Select-String "test-email"
    if ($routeTest) {
        Write-Host "✅ Route test-email trouvée: $routeTest" -ForegroundColor Green
    } else {
        Write-Host "❌ Route test-email non trouvée" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Erreur lors de la vérification des routes" -ForegroundColor Red
}

Write-Host ""
Write-Host "5. Instructions pour test manuel..." -ForegroundColor Yellow
Write-Host "📧 Accéder à: $BaseUrl/admin/email-settings" -ForegroundColor Cyan
Write-Host "🔧 Cliquer sur 'Tester la configuration' pour une configuration" -ForegroundColor Cyan
Write-Host "📝 Entrer votre adresse email de test" -ForegroundColor Cyan
Write-Host "🚀 Cliquer sur 'Envoyer le test'" -ForegroundColor Cyan

Write-Host ""
Write-Host "Si l'erreur persiste, vérifiez:" -ForegroundColor Blue
Write-Host "• Token CSRF présent dans la page" -ForegroundColor White
Write-Host "• JavaScript console pour erreurs" -ForegroundColor White
Write-Host "• Logs Laravel pour détails d'erreur" -ForegroundColor White
Write-Host "• Configuration SMTP dans .env" -ForegroundColor White

Write-Host ""
Write-Host "=== DIAGNOSTIC TERMINÉ ===" -ForegroundColor Cyan
