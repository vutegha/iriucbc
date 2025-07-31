# Script de validation complète du système d'emails IRI-UCBC
# Usage: .\validate-email-system.ps1

Write-Host "🔍 Validation complète du système d'emails IRI-UCBC" -ForegroundColor Cyan
Write-Host "=" * 70 -ForegroundColor Gray

# Fonction pour vérifier les fichiers
function Test-FileExists {
    param([string]$Path, [string]$Description)
    
    if (Test-Path $Path) {
        Write-Host "✅ $Description : OK" -ForegroundColor Green
        return $true
    } else {
        Write-Host "❌ $Description : MANQUANT - $Path" -ForegroundColor Red
        return $false
    }
}

# Fonction pour tester une route
function Test-Route {
    param([string]$RouteName, [string]$Description)
    
    try {
        $result = & php artisan route:list --name="$RouteName" 2>$null
        if ($result -and $result.Length -gt 1) {
            Write-Host "✅ $Description : OK" -ForegroundColor Green
            return $true
        } else {
            Write-Host "❌ $Description : ROUTE MANQUANTE" -ForegroundColor Red
            return $false
        }
    } catch {
        Write-Host "❌ $Description : ERREUR TEST" -ForegroundColor Red
        return $false
    }
}

$allChecks = @()

Write-Host "`n📂 1. Vérification des fichiers..." -ForegroundColor Yellow

# Modèles
$allChecks += Test-FileExists "app\Models\EmailSetting.php" "Modèle EmailSetting"
$allChecks += Test-FileExists "app\Mail\ContactMessageWithCopy.php" "Mailable ContactMessageWithCopy"

# Contrôleurs  
$allChecks += Test-FileExists "app\Http\Controllers\Admin\EmailSettingController.php" "Contrôleur EmailSetting"

# Migrations
$allChecks += Test-FileExists "database\migrations\2025_01_29_000000_create_email_settings_table.php" "Migration email_settings"
$allChecks += Test-FileExists "database\migrations\2025_01_29_000001_seed_default_email_settings.php" "Seed configurations par défaut"

# Vues
$allChecks += Test-FileExists "resources\views\admin\email-settings\index.blade.php" "Vue admin configuration emails"
$allChecks += Test-FileExists "resources\views\emails\contact-message-admin.blade.php" "Template email admin"
$allChecks += Test-FileExists "resources\views\emails\contact-message-copy.blade.php" "Template email copie"

# Commandes
$allChecks += Test-FileExists "app\Console\Commands\TestContactEmailSystem.php" "Commande test email"

# Documentation
$allChecks += Test-FileExists "GUIDE_EMAILS_CONTACT.md" "Guide d'utilisation"
$allChecks += Test-FileExists "test-contact-email.ps1" "Script de test PowerShell"

Write-Host "`n🛤️ 2. Vérification des routes..." -ForegroundColor Yellow

$allChecks += Test-Route "admin.email-settings.index" "Route index email-settings"
$allChecks += Test-Route "admin.email-settings.update" "Route update email-settings"  
$allChecks += Test-Route "admin.email-settings.add-email" "Route add-email"
$allChecks += Test-Route "admin.email-settings.remove-email" "Route remove-email"
$allChecks += Test-Route "admin.email-settings.test-email" "Route test-email"

Write-Host "`n⚙️ 3. Vérification de la configuration..." -ForegroundColor Yellow

# Configuration SMTP
$envContent = Get-Content .env -ErrorAction SilentlyContinue
if ($envContent) {
    $mailHost = $envContent | Where-Object { $_ -match "^MAIL_HOST=" }
    $mailPort = $envContent | Where-Object { $_ -match "^MAIL_PORT=" }
    $mailUsername = $envContent | Where-Object { $_ -match "^MAIL_USERNAME=" }
    
    if ($mailHost -and $mailPort -and $mailUsername) {
        Write-Host "✅ Configuration SMTP : OK" -ForegroundColor Green
        $allChecks += $true
        
        Write-Host "   📬 Serveur: $($mailHost -replace 'MAIL_HOST=', '')" -ForegroundColor Blue
        Write-Host "   🔌 Port: $($mailPort -replace 'MAIL_PORT=', '')" -ForegroundColor Blue
        Write-Host "   👤 Utilisateur: $($mailUsername -replace 'MAIL_USERNAME=', '')" -ForegroundColor Blue
    } else {
        Write-Host "❌ Configuration SMTP : INCOMPLÈTE" -ForegroundColor Red
        $allChecks += $false
    }
} else {
    Write-Host "❌ Fichier .env : NON TROUVÉ" -ForegroundColor Red
    $allChecks += $false
}

Write-Host "`n💾 4. Vérification de la base de données..." -ForegroundColor Yellow

try {
    $migrationStatus = & php artisan migrate:status 2>$null
    if ($migrationStatus -match "create_email_settings_table.*Ran") {
        Write-Host "✅ Migration email_settings : OK" -ForegroundColor Green
        $allChecks += $true
    } else {
        Write-Host "❌ Migration email_settings : NON APPLIQUÉE" -ForegroundColor Red
        $allChecks += $false
    }
    
    if ($migrationStatus -match "seed_default_email_settings.*Ran") {
        Write-Host "✅ Seed configurations par défaut : OK" -ForegroundColor Green
        $allChecks += $true
    } else {
        Write-Host "❌ Seed configurations par défaut : NON APPLIQUÉ" -ForegroundColor Red
        $allChecks += $false
    }
} catch {
    Write-Host "❌ Test base de données : ERREUR" -ForegroundColor Red
    $allChecks += $false
}

Write-Host "`n🧪 5. Test des commandes..." -ForegroundColor Yellow

try {
    $commandList = & php artisan list 2>$null
    if ($commandList -match "contact:test-email") {
        Write-Host "✅ Commande contact:test-email : OK" -ForegroundColor Green
        $allChecks += $true
    } else {
        Write-Host "❌ Commande contact:test-email : NON DISPONIBLE" -ForegroundColor Red
        $allChecks += $false
    }
} catch {
    Write-Host "❌ Test commandes : ERREUR" -ForegroundColor Red
    $allChecks += $false
}

# Résultats finaux
Write-Host "`n📊 RÉSULTATS DE LA VALIDATION" -ForegroundColor Cyan
Write-Host "=" * 40 -ForegroundColor Gray

$successCount = ($allChecks | Where-Object { $_ -eq $true }).Count
$totalCount = $allChecks.Count
$successRate = [math]::Round(($successCount / $totalCount) * 100, 1)

Write-Host "✅ Tests réussis : $successCount / $totalCount ($successRate%)" -ForegroundColor Green

if ($successRate -eq 100) {
    Write-Host "🎉 SYSTÈME ENTIÈREMENT FONCTIONNEL !" -ForegroundColor Green
    Write-Host ""
    Write-Host "📋 Prochaines étapes recommandées :" -ForegroundColor Cyan
    Write-Host "1. Tester l'envoi d'email : .\test-contact-email.ps1" -ForegroundColor White
    Write-Host "2. Accéder à l'admin : /admin/email-settings" -ForegroundColor White
    Write-Host "3. Tester le formulaire de contact du site" -ForegroundColor White
    Write-Host "4. Consulter le guide : GUIDE_EMAILS_CONTACT.md" -ForegroundColor White
} elseif ($successRate -ge 80) {
    Write-Host "⚠️ SYSTÈME PARTIELLEMENT FONCTIONNEL" -ForegroundColor Yellow
    Write-Host "Quelques vérifications sont nécessaires avant utilisation." -ForegroundColor Yellow
} else {
    Write-Host "❌ SYSTÈME NON FONCTIONNEL" -ForegroundColor Red
    Write-Host "Veuillez corriger les erreurs avant utilisation." -ForegroundColor Red
}

Write-Host "`n🔗 Liens utiles :" -ForegroundColor Cyan
Write-Host "• Configuration : /admin/email-settings" -ForegroundColor White
Write-Host "• Test simple : /admin/email-test" -ForegroundColor White
Write-Host "• Messages : /admin/contacts" -ForegroundColor White
Write-Host "• Documentation : GUIDE_EMAILS_CONTACT.md" -ForegroundColor White

Write-Host "`n✨ Validation terminée !" -ForegroundColor Green
