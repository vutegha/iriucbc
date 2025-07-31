# Script de test de configuration email pour IRI-UCBC
# Utilisation: .\test-email-config.ps1 [email@example.com]

param(
    [string]$Email = "test@example.com"
)

Write-Host "===================================" -ForegroundColor Blue
Write-Host "🔧 TEST DE CONFIGURATION EMAIL" -ForegroundColor Blue
Write-Host "===================================" -ForegroundColor Blue
Write-Host ""

Write-Host "📧 Configuration actuelle:" -ForegroundColor Yellow
Write-Host "   Serveur: iri.ledinitiatives.com"
Write-Host "   Port: 465 (SSL)"
Write-Host "   Utilisateur: info@iri.ledinitiatives.com"
Write-Host "   Email de test: $Email"
Write-Host ""

Write-Host "🔍 Vérification de la connectivité..." -ForegroundColor Yellow
try {
    $connection = New-Object System.Net.Sockets.TcpClient("iri.ledinitiatives.com", 465)
    if ($connection.Connected) {
        Write-Host "✅ Port 465 accessible" -ForegroundColor Green
        $connection.Close()
    }
} catch {
    Write-Host "❌ Port 465 inaccessible: $($_.Exception.Message)" -ForegroundColor Red
}
Write-Host ""

Write-Host "🧹 Nettoyage du cache Laravel..." -ForegroundColor Yellow
& php artisan config:clear
& php artisan route:clear
Write-Host "✅ Cache nettoyé" -ForegroundColor Green
Write-Host ""

Write-Host "📤 Test d'envoi d'email..." -ForegroundColor Yellow
& php artisan email:test $Email

Write-Host ""
Write-Host "===================================" -ForegroundColor Blue
Write-Host "✅ Test terminé" -ForegroundColor Green
Write-Host ""
Write-Host "💡 Pour tester via l'interface web:" -ForegroundColor Cyan
Write-Host "   1. Connectez-vous à l'admin"
Write-Host "   2. Allez sur /admin/email-test"
Write-Host "   3. Utilisez l'interface de test"
Write-Host "===================================" -ForegroundColor Blue
