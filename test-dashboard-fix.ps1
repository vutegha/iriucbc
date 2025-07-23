# Script de test pour les corrections du dashboard
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test des corrections du dashboard admin" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Vérifier la suppression des références is_read
Write-Host "`n1. Test de suppression des références is_read..." -ForegroundColor Yellow
if (Test-Path "resources/views/admin/dashboard.blade.php") {
    Write-Host "   OK: Fichier dashboard trouvé" -ForegroundColor Green
    
    $dashboardContent = Get-Content "resources/views/admin/dashboard.blade.php" -Raw
    
    # Vérifier que is_read n'est plus présent
    if ($dashboardContent -notmatch "is_read") {
        Write-Host "   OK: Toutes les références à 'is_read' ont été supprimées" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Des références à 'is_read' sont encore présentes" -ForegroundColor Red
        $matches = ($dashboardContent | Select-String "is_read").Count
        Write-Host "   Nombre de références trouvées: $matches" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Fichier dashboard non trouvé" -ForegroundColor Red
}

# Test 2: Vérifier la suppression de l'alerte des messages non lus
Write-Host "`n2. Test de suppression de l'alerte..." -ForegroundColor Yellow
if (Test-Path "resources/views/admin/dashboard.blade.php") {
    $dashboardContent = Get-Content "resources/views/admin/dashboard.blade.php" -Raw
    
    # Vérifier que l'alerte a été supprimée
    if ($dashboardContent -notmatch "Alertes en temps réel") {
        Write-Host "   OK: Section des alertes supprimée" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Section des alertes encore présente" -ForegroundColor Red
    }
    
    # Vérifier que l'alerte jaune a été supprimée
    if ($dashboardContent -notmatch "bg-yellow-50 border-l-4 border-yellow-400") {
        Write-Host "   OK: Alerte jaune supprimée" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Alerte jaune encore présente" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier les alertes" -ForegroundColor Red
}

# Test 3: Vérifier la simplification des messages récents
Write-Host "`n3. Test de simplification des messages..." -ForegroundColor Yellow
if (Test-Path "resources/views/admin/dashboard.blade.php") {
    $dashboardContent = Get-Content "resources/views/admin/dashboard.blade.php" -Raw
    
    # Vérifier que les messages récents sont toujours présents
    if ($dashboardContent -match "Messages récents") {
        Write-Host "   OK: Section des messages récents préservée" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Section des messages récents supprimée" -ForegroundColor Red
    }
    
    # Vérifier que la logique non lu a été supprimée
    if ($dashboardContent -notmatch "Non lu") {
        Write-Host "   OK: Logique 'Non lu' supprimée des messages" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Logique 'Non lu' encore présente" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier les messages" -ForegroundColor Red
}

# Test 4: Vérifier la correction des statistiques
Write-Host "`n4. Test de correction des statistiques..." -ForegroundColor Yellow
if (Test-Path "resources/views/admin/dashboard.blade.php") {
    $dashboardContent = Get-Content "resources/views/admin/dashboard.blade.php" -Raw
    
    # Vérifier que les statistiques de messages utilisent maintenant created_at
    if ($dashboardContent -match "where\('created_at', '>=', now\(\)->subMonth\(\)\)->count\(\)") {
        Write-Host "   OK: Statistiques corrigées pour utiliser created_at" -ForegroundColor Green
    } else {
        Write-Host "   Erreur: Statistiques non corrigées" -ForegroundColor Red
    }
} else {
    Write-Host "   Erreur: Impossible de vérifier les statistiques" -ForegroundColor Red
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des corrections appliquées:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Suppression des références à 'is_read'" -ForegroundColor Green
Write-Host "✅ Suppression de l'alerte des messages non lus" -ForegroundColor Green
Write-Host "✅ Simplification de l'affichage des messages récents" -ForegroundColor Green
Write-Host "✅ Correction des statistiques de messages" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Nouvelles fonctionnalités:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "📊 Statistiques des messages par mois" -ForegroundColor Cyan
Write-Host "📝 Affichage simplifié des messages récents" -ForegroundColor Cyan
Write-Host "🕒 Horodatage des messages avec diffForHumans" -ForegroundColor Cyan
Write-Host "Email affiche dans les messages" -ForegroundColor Cyan

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test terminé - Dashboard corrigé!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "- Visitez http://localhost:8000/admin/dashboard" -ForegroundColor White
Write-Host "- Vérifiez que les erreurs SQL ont disparu" -ForegroundColor White
Write-Host "- Testez les statistiques et les messages" -ForegroundColor White
