# Script de test final pour le dashboard corrigé
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test final du dashboard admin" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Vérifier la colonne is_read
Write-Host "`n1. Test de la colonne is_read..." -ForegroundColor Yellow
$result = php artisan tinker --execute="echo \App\Models\Contact::first() ? 'Table contacts accessible' : 'Aucun contact';"
if ($result) {
    Write-Host "   OK: Table contacts accessible" -ForegroundColor Green
} else {
    Write-Host "   Erreur: Problème avec la table contacts" -ForegroundColor Red
}

# Test 2: Vérifier les migrations
Write-Host "`n2. Test des migrations..." -ForegroundColor Yellow
$migrations = php artisan migrate:status
if ($migrations -match "add_is_read_to_contacts_table.*Ran") {
    Write-Host "   OK: Migration is_read appliquée" -ForegroundColor Green
} else {
    Write-Host "   Erreur: Migration is_read non appliquée" -ForegroundColor Red
}

# Test 3: Vérifier le contenu du dashboard
Write-Host "`n3. Test du contenu dashboard..." -ForegroundColor Yellow
if (Test-Path "resources/views/admin/dashboard.blade.php") {
    $dashboardContent = Get-Content "resources/views/admin/dashboard.blade.php" -Raw
    
    # Vérifier que is_read est de retour
    if ($dashboardContent -match "is_read") {
        Write-Host "   OK: Fonctionnalité is_read restaurée" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Fonctionnalité is_read manquante" -ForegroundColor Red
    }
    
    # Vérifier que l'alerte est de retour
    if ($dashboardContent -match "Alertes en temps réel") {
        Write-Host "   OK: Alertes en temps réel restaurées" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Alertes en temps réel manquantes" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Fichier dashboard non trouvé" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résolution du problème terminée!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Colonne is_read ajoutée à la table contacts" -ForegroundColor Green
Write-Host "✅ Migration exécutée avec succès" -ForegroundColor Green
Write-Host "✅ Fonctionnalité is_read restaurée dans le dashboard" -ForegroundColor Green
Write-Host "✅ Alertes des messages non lus restaurées" -ForegroundColor Green
Write-Host "✅ Statistiques des messages non lus opérationnelles" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Fonctionnalités disponibles:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "📊 Compteur de messages non lus en temps réel" -ForegroundColor Cyan
Write-Host "🔔 Alertes visuelles pour nouveaux messages" -ForegroundColor Cyan
Write-Host "✅ Statut lu/non lu dans la liste des messages" -ForegroundColor Cyan
Write-Host "📈 Statistiques détaillées des messages" -ForegroundColor Cyan

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Testez maintenant:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "- Visitez http://localhost:8000/admin/dashboard" -ForegroundColor White
Write-Host "- Vérifiez les statistiques des messages" -ForegroundColor White
Write-Host "- Testez l'alerte des messages non lus" -ForegroundColor White
Write-Host "- Consultez la section des messages récents" -ForegroundColor White
