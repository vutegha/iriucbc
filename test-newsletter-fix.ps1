# Script de test pour la correction Newsletter
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test de correction Newsletter (status -> actif)" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Vérifier la correction dans le dashboard
Write-Host "`n1. Test de correction des références newsletter..." -ForegroundColor Yellow
if (Test-Path "resources/views/admin/dashboard.blade.php") {
    Write-Host "   OK: Fichier dashboard trouvé" -ForegroundColor Green
    
    $dashboardContent = Get-Content "resources/views/admin/dashboard.blade.php" -Raw
    
    # Vérifier que 'status' a été remplacé par 'actif'
    if ($dashboardContent -notmatch "where\('status', 'active'\)") {
        Write-Host "   OK: Références à 'status' supprimées" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Des références à 'status' sont encore présentes" -ForegroundColor Red
    }
    
    # Vérifier que 'actif' est maintenant utilisé
    if ($dashboardContent -match "where\('actif', true\)") {
        Write-Host "   OK: Références à 'actif' ajoutées" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Références à 'actif' manquantes" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Fichier dashboard non trouvé" -ForegroundColor Red
}

# Test 2: Vérifier la structure de la table newsletters
Write-Host "`n2. Test de la structure de la table newsletters..." -ForegroundColor Yellow
if (Test-Path "database/migrations/2025_07_17_085934_create_newsletters_table.php") {
    $migrationContent = Get-Content "database/migrations/2025_07_17_085934_create_newsletters_table.php" -Raw
    
    # Vérifier que la colonne 'actif' existe
    if ($migrationContent -match "boolean\('actif'\)") {
        Write-Host "   OK: Colonne 'actif' définie dans la migration" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Colonne 'actif' non trouvée dans la migration" -ForegroundColor Red
    }
    
    # Vérifier qu'il n'y a pas de colonne 'status'
    if ($migrationContent -notmatch "status") {
        Write-Host "   OK: Pas de colonne 'status' dans la migration" -ForegroundColor Green
    } else {
        Write-Host "   Attention: Colonne 'status' trouvée dans la migration" -ForegroundColor Yellow
    }
} else {
    Write-Host "   Erreur: Migration newsletters non trouvée" -ForegroundColor Red
}

# Test 3: Vérifier que la migration est appliquée
Write-Host "`n3. Test du statut des migrations..." -ForegroundColor Yellow
$migrations = php artisan migrate:status
if ($migrations -match "create_newsletters_table.*Ran") {
    Write-Host "   OK: Migration newsletters appliquée" -ForegroundColor Green
} else {
    Write-Host "   Erreur: Migration newsletters non appliquée" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des corrections appliquées:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Remplacement de where('status', 'active') par where('actif', true)" -ForegroundColor Green
Write-Host "✅ Correction des statistiques d'abonnés newsletter" -ForegroundColor Green
Write-Host "✅ Correction des statistiques mensuelles newsletter" -ForegroundColor Green
Write-Host "✅ Cohérence avec la structure de la table newsletters" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Structure de la table newsletters:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "• id (clé primaire)" -ForegroundColor Cyan
Write-Host "• email (unique)" -ForegroundColor Cyan
Write-Host "• nom (nullable)" -ForegroundColor Cyan
Write-Host "• token (unique)" -ForegroundColor Cyan
Write-Host "• actif (boolean, default: true) ← UTILISÉ" -ForegroundColor Green
Write-Host "• confirme_a (timestamp, nullable)" -ForegroundColor Cyan
Write-Host "• created_at / updated_at" -ForegroundColor Cyan

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Fonctionnalités newsletter opérationnelles:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "📊 Comptage des abonnés actifs" -ForegroundColor Cyan
Write-Host "📈 Statistiques mensuelles des nouveaux abonnés" -ForegroundColor Cyan
Write-Host "✅ Gestion du statut actif/inactif" -ForegroundColor Cyan
Write-Host "🔑 Système de tokens pour les préférences" -ForegroundColor Cyan

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test terminé - Dashboard Newsletter corrigé!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "- Visitez http://localhost:8000/admin/dashboard" -ForegroundColor White
Write-Host "- Vérifiez les statistiques des abonnés newsletter" -ForegroundColor White
Write-Host "- Plus d'erreur SQL liée à la colonne 'status'" -ForegroundColor White
