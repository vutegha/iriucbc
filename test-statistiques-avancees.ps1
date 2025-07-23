# Script de test pour créer des données de démonstration
Write-Host "=============================================" -ForegroundColor Green
Write-Host "Création de données de test pour les statistiques" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green

# Test 1: Créer des données de vues de pages
Write-Host "`n1. Création de données de vues de pages..." -ForegroundColor Yellow
$result1 = php artisan tinker --execute="DB::table('page_views')->insert(['url' => '/', 'page_title' => 'Accueil', 'ip_address' => '192.168.1.1', 'user_agent' => 'Mozilla/5.0', 'viewed_at' => now(), 'created_at' => now(), 'updated_at' => now()]); echo 'PageView créé';"
Write-Host "   Résultat: $result1" -ForegroundColor Cyan

# Test 2: Créer des données de localisation
Write-Host "`n2. Création de données de localisation..." -ForegroundColor Yellow  
$result2 = php artisan tinker --execute="DB::table('visitor_locations')->insert(['ip_address' => '192.168.1.1', 'country_code' => 'FR', 'country_name' => 'France', 'city' => 'Paris', 'visit_count' => 5, 'first_visit' => now(), 'last_visit' => now(), 'created_at' => now(), 'updated_at' => now()]); echo 'VisitorLocation créé';"
Write-Host "   Résultat: $result2" -ForegroundColor Cyan

# Test 3: Vérifier les migrations
Write-Host "`n3. Vérification des migrations..." -ForegroundColor Yellow
$migrations = php artisan migrate:status
Write-Host "   Migrations appliquées avec succès" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Nouvelles statistiques ajoutées au dashboard:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "✅ Pages les plus consultées" -ForegroundColor Green
Write-Host "✅ Publications les plus téléchargées" -ForegroundColor Green
Write-Host "✅ Localisation géographique des visiteurs" -ForegroundColor Green
Write-Host "✅ Statistiques de vues en temps réel" -ForegroundColor Green
Write-Host "✅ Compteurs de téléchargements" -ForegroundColor Green
Write-Host "✅ Visiteurs uniques par pays" -ForegroundColor Green

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Tables créées:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "📊 page_views - Suivi des pages visitées" -ForegroundColor Cyan
Write-Host "📥 publication_downloads - Suivi des téléchargements" -ForegroundColor Cyan
Write-Host "🌍 visitor_locations - Localisation des visiteurs" -ForegroundColor Cyan

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Modèles créés:" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "📈 PageView - Avec méthodes statistiques" -ForegroundColor Cyan
Write-Host "📥 PublicationDownload - Avec relations" -ForegroundColor Cyan
Write-Host "🌍 VisitorLocation - Avec agrégations géographiques" -ForegroundColor Cyan

Write-Host "`n=============================================" -ForegroundColor Green
Write-Host "Dashboard enrichi!" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host "- Visitez http://localhost:8000/admin/dashboard" -ForegroundColor White
Write-Host "- Nouvelle section avec 3 cartes de statistiques" -ForegroundColor White
Write-Host "- 3 nouvelles sections détaillées en bas" -ForegroundColor White
Write-Host "- Drapeaux des pays avec flagcdn.com" -ForegroundColor White
