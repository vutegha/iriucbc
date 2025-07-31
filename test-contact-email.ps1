# Script PowerShell pour tester le système d'emails de contact IRI-UCBC
# Usage: .\test-contact-email.ps1

param(
    [string]$Email = "s.vutegha@gmail.com",
    [string]$Name = "Test Admin", 
    [string]$Subject = "Test système contact IRI-UCBC"
)

Write-Host "🚀 Test du système d'emails de contact IRI-UCBC" -ForegroundColor Cyan
Write-Host "=" * 60 -ForegroundColor Gray

# Vérifier que nous sommes dans le bon répertoire
if (!(Test-Path "artisan")) {
    Write-Host "❌ Erreur: Fichier artisan non trouvé" -ForegroundColor Red
    Write-Host "Assurez-vous d'être dans le répertoire du projet Laravel" -ForegroundColor Yellow
    exit 1
}

Write-Host "📍 Répertoire: $(Get-Location)" -ForegroundColor Green
Write-Host "📧 Email de test: $Email" -ForegroundColor Blue
Write-Host "👤 Nom: $Name" -ForegroundColor Blue  
Write-Host "📋 Sujet: $Subject" -ForegroundColor Blue
Write-Host ""

# Nettoyer les caches
Write-Host "🧹 Nettoyage des caches..." -ForegroundColor Yellow
try {
    & php artisan config:clear 2>$null
    & php artisan cache:clear 2>$null
    Write-Host "✅ Caches nettoyés" -ForegroundColor Green
} catch {
    Write-Host "⚠️ Avertissement: Erreur lors du nettoyage des caches" -ForegroundColor Yellow
}

Write-Host ""

# Vérifier la configuration email
Write-Host "⚙️ Vérification de la configuration SMTP..." -ForegroundColor Yellow
$envFile = Get-Content .env -ErrorAction SilentlyContinue
if ($envFile) {
    $mailHost = ($envFile | Where-Object { $_ -match "^MAIL_HOST=" }) -replace "MAIL_HOST=", ""
    $mailPort = ($envFile | Where-Object { $_ -match "^MAIL_PORT=" }) -replace "MAIL_PORT=", ""
    $mailEncryption = ($envFile | Where-Object { $_ -match "^MAIL_ENCRYPTION=" }) -replace "MAIL_ENCRYPTION=", ""
    
    Write-Host "📬 Serveur SMTP: $mailHost" -ForegroundColor Blue
    Write-Host "🔌 Port: $mailPort" -ForegroundColor Blue
    Write-Host "🔒 Encryption: $mailEncryption" -ForegroundColor Blue
    Write-Host ""
}

# Exécuter le test
Write-Host "🧪 Lancement du test d'email..." -ForegroundColor Cyan
Write-Host "⏳ Cela peut prendre quelques minutes..." -ForegroundColor Yellow
Write-Host ""

try {
    $result = & php artisan contact:test-email --email="$Email" --name="$Name" --subject="$Subject"
    Write-Host $result -ForegroundColor White
} catch {
    Write-Host "❌ Erreur lors de l'exécution du test:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

Write-Host ""
Write-Host "📋 Actions de suivi recommandées:" -ForegroundColor Cyan
Write-Host "1. Vérifiez la réception des emails dans les boîtes configurées" -ForegroundColor White
Write-Host "2. Vérifiez le dossier spam/courrier indésirable" -ForegroundColor White  
Write-Host "3. Testez le formulaire de contact depuis le site web" -ForegroundColor White
Write-Host "4. Consultez les logs Laravel: storage/logs/laravel.log" -ForegroundColor White

Write-Host ""
Write-Host "🔗 Liens utiles:" -ForegroundColor Cyan
Write-Host "• Configuration emails admin: /admin/email-settings" -ForegroundColor White
Write-Host "• Test emails admin: /admin/email-test" -ForegroundColor White
Write-Host "• Contacts reçus: /admin/contacts" -ForegroundColor White

Write-Host ""
Write-Host "✨ Test terminé!" -ForegroundColor Green
