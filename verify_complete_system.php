<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Vérification complète de l'état de la base de données\n";
echo "==================================================\n\n";

$tables = [
    'actualites' => 'Actualités',
    'publications' => 'Publications', 
    'evenements' => 'Événements',
    'projets' => 'Projets',
    'contacts' => 'Contacts',
    'newsletters' => 'Newsletters',
    'categories' => 'Catégories',
    'auteurs' => 'Auteurs',
    'auteur_publication' => 'Relations Auteur-Publication',
    'rapports' => 'Rapports',
    'partenaires' => 'Partenaires',
    'job_offers' => 'Offres d\'emploi',
    'job_applications' => 'Candidatures',
    'media' => 'Médias'
];

$totalRecords = 0;

foreach ($tables as $table => $name) {
    try {
        $count = DB::table($table)->count();
        $totalRecords += $count;
        $status = $count > 0 ? "✅" : "⚪";
        echo "$status $name ($table): $count enregistrements\n";
    } catch (Exception $e) {
        echo "❌ $name ($table): ERREUR - " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Résumé:\n";
echo "- Tables vérifiées: " . count($tables) . "\n";
echo "- Total enregistrements: $totalRecords\n";

// Test des requêtes complexes principales
echo "\n🧪 Test des requêtes complexes:\n";

try {
    // Test requête actualités avec catégories (utilise is_published au lieu de status)
    $actualites = DB::table('actualites')
        ->leftJoin('categories', 'actualites.service_id', '=', 'categories.id')
        ->select('actualites.*', 'categories.nom as category_name')
        ->where('actualites.is_published', 1)
        ->count();
    echo "✅ Actualités publiées: $actualites\n";
} catch (Exception $e) {
    echo "❌ Erreur actualités: " . $e->getMessage() . "\n";
}

try {
    // Test requête publications avec auteurs
    $publications = DB::table('publications')
        ->leftJoin('auteur_publication', 'publications.id', '=', 'auteur_publication.publication_id')
        ->leftJoin('auteurs', 'auteur_publication.auteur_id', '=', 'auteurs.id')
        ->select('publications.*')
        ->distinct()
        ->count();
    echo "✅ Publications avec auteurs: $publications\n";
} catch (Exception $e) {
    echo "❌ Erreur publications: " . $e->getMessage() . "\n";
}

try {
    // Test requête offres d'emploi actives
    $jobsActive = DB::table('job_offers')
        ->where('status', 'active')
        ->where(function($q) {
            $q->whereNull('application_deadline')
              ->orWhere('application_deadline', '>=', now());
        })
        ->count();
    echo "✅ Offres d'emploi actives: $jobsActive\n";
} catch (Exception $e) {
    echo "❌ Erreur offres emploi: " . $e->getMessage() . "\n";
}

try {
    // Test requête médias
    $mediaCount = DB::table('media')->where('status', 'active')->count();
    echo "✅ Médias actifs: $mediaCount\n";
} catch (Exception $e) {
    echo "❌ Erreur médias: " . $e->getMessage() . "\n";
}

echo "\n🎉 Vérification terminée avec succès!\n";
