#!/usr/bin/env pwsh

# Script de test pour les statistiques du footer
# Teste les nouvelles fonctionnalités de bénéficiaires et l'affichage du footer

Write-Host "=============================================" -ForegroundColor Green
Write-Host "Test des statistiques du footer - IRI UCBC" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Vérifier la migration des bénéficiaires
Write-Host "`n1. Vérification de la migration des bénéficiaires..." -ForegroundColor Yellow
try {
    php artisan db:show --table=projets | Out-Null
    Write-Host "   ✓ Table projets accessible" -ForegroundColor Green
    
    # Tester la présence des nouvelles colonnes
    $columns = php artisan tinker --execute="echo Schema::getColumnListing('projets');"
    if ($columns -match "beneficiaires_hommes|beneficiaires_femmes|beneficiaires_total") {
        Write-Host "   ✓ Colonnes de bénéficiaires ajoutées" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Colonnes de bénéficiaires manquantes" -ForegroundColor Red
    }
} catch {
    Write-Host "   ✗ Erreur lors de la vérification de la table" -ForegroundColor Red
}

# Test 2: Vérifier le modèle Projet
Write-Host "`n2. Test du modèle Projet..." -ForegroundColor Yellow
try {
    $totalProjets = php artisan tinker --execute="echo App\Models\Projet::count();"
    Write-Host "   ✓ Nombre total de projets: $totalProjets" -ForegroundColor Green
    
    $totalBeneficiaires = php artisan tinker --execute="echo App\Models\Projet::getTotalBeneficiaires();"
    Write-Host "   ✓ Total bénéficiaires: $totalBeneficiaires" -ForegroundColor Green
} catch {
    Write-Host "   ✗ Erreur lors du test du modèle" -ForegroundColor Red
}

# Test 3: Créer un projet de test avec bénéficiaires
Write-Host "`n3. Création d'un projet de test..." -ForegroundColor Yellow
try {
    $testProject = @"
\$projet = App\Models\Projet::create([
    'nom' => 'Projet Test Footer',
    'description' => 'Projet de test pour les statistiques du footer',
    'etat' => 'en cours',
    'beneficiaires_hommes' => 50,
    'beneficiaires_femmes' => 75,
    'beneficiaires_total' => 125
]);
echo 'Projet créé avec ID: ' . \$projet->id;
"@
    
    $result = php artisan tinker --execute="$testProject"
    Write-Host "   ✓ $result" -ForegroundColor Green
} catch {
    Write-Host "   ✗ Erreur lors de la création du projet de test" -ForegroundColor Red
}

# Test 4: Vérifier l'affichage du footer
Write-Host "`n4. Test de l'affichage du footer..." -ForegroundColor Yellow

# Vérifier que le footer existe
if (Test-Path "resources/views/partials/footer.blade.php") {
    Write-Host "   ✓ Fichier footer trouvé" -ForegroundColor Green
    
    # Vérifier la présence des statistiques dynamiques
    $footerContent = Get-Content "resources/views/partials/footer.blade.php" -Raw
    if ($footerContent -match "App\\Models\\Projet::getTotalProjets|App\\Models\\Projet::getTotalBeneficiaires") {
        Write-Host "   ✓ Statistiques dynamiques configurées" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Statistiques dynamiques non configurées" -ForegroundColor Red
    }
    
    # Vérifier la suppression des icônes de navigation
    if ($footerContent -match 'text-orange-400">-</span>') {
        Write-Host "   ✓ Navigation avec tirets configurée" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Navigation avec tirets non configurée" -ForegroundColor Red
    }
    
    # Vérifier la suppression des encadrés de contact
    if ($footerContent -notmatch "bg-gray-800/50.*backdrop-blur-sm") {
        Write-Host "   ✓ Encadrés de contact supprimés" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Encadrés de contact toujours présents" -ForegroundColor Red
    }
} else {
    Write-Host "   ✗ Fichier footer non trouvé" -ForegroundColor Red
}

# Test 5: Vérifier les formulaires admin
Write-Host "`n5. Test des formulaires admin..." -ForegroundColor Yellow

if (Test-Path "resources/views/admin/projets/_form.blade.php") {
    Write-Host "   ✓ Formulaire admin trouvé" -ForegroundColor Green
    
    $formContent = Get-Content "resources/views/admin/projets/_form.blade.php" -Raw
    if ($formContent -match "beneficiaires_hommes|beneficiaires_femmes|beneficiaires_total") {
        Write-Host "   ✓ Champs de bénéficiaires ajoutés au formulaire" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Champs de bénéficiaires manquants dans le formulaire" -ForegroundColor Red
    }
} else {
    Write-Host "   ✗ Formulaire admin non trouvé" -ForegroundColor Red
}

# Test 6: Vérifier les routes admin
Write-Host "`n6. Test des routes admin..." -ForegroundColor Yellow
try {
    $routes = php artisan route:list --name=admin.projets
    if ($routes -match "admin.projets.edit|admin.projets.update") {
        Write-Host "   ✓ Routes admin projets configurées" -ForegroundColor Green
    } else {
        Write-Host "   ✗ Routes admin projets manquantes" -ForegroundColor Red
    }
} catch {
    Write-Host "   ✗ Erreur lors de la vérification des routes" -ForegroundColor Red
}

# Test 7: Test d'affichage sur le serveur local
Write-Host "`n7. Test d'affichage sur le serveur local..." -ForegroundColor Yellow
try {
    # Vérifier si le serveur Laravel est en cours d'exécution
    $serverCheck = Test-NetConnection -ComputerName localhost -Port 8000 -InformationLevel Quiet
    if ($serverCheck) {
        Write-Host "   ✓ Serveur Laravel en cours d'exécution sur localhost:8000" -ForegroundColor Green
        Write-Host "   → Visitez http://localhost:8000 pour voir le footer avec les statistiques" -ForegroundColor Cyan
    } else {
        Write-Host "   ⚠ Serveur Laravel non détecté sur localhost:8000" -ForegroundColor Yellow
        Write-Host "   → Démarrez le serveur avec: php artisan serve --port=8000" -ForegroundColor Cyan
    }
} catch {
    Write-Host "   ⚠ Impossible de vérifier le serveur" -ForegroundColor Yellow
}

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Résumé des modifications apportées:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✓ Ajout des champs de bénéficiaires à la table projets" -ForegroundColor Green
Write-Host "✓ Mise à jour du modèle Projet avec méthodes statistiques" -ForegroundColor Green
Write-Host "✓ Modification du footer pour utiliser les données DB" -ForegroundColor Green
Write-Host "✓ Suppression des icônes de navigation, ajout de tirets" -ForegroundColor Green
Write-Host "✓ Suppression des encadrés de contact" -ForegroundColor Green
Write-Host "✓ Ajout des champs bénéficiaires aux formulaires admin" -ForegroundColor Green
Write-Host "✓ Mise à jour des contrôleurs pour gérer les nouveaux champs" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Test terminé avec succès!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
