<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\JobOffer;

echo "=== CRÉATION D'UNE OFFRE DE TEST ===\n";

try {
    $jobOffer = JobOffer::create([
        'title' => 'Développeur Web Full Stack',
        'description' => 'Nous recherchons un développeur web expérimenté pour rejoindre notre équipe de développement. Vous travaillerez sur des projets innovants de gouvernance foncière et aurez l\'opportunité de faire un impact significatif sur les communautés locales.',
        'type' => 'temps_plein',
        'location' => 'Yaoundé, Cameroun',
        'department' => 'Développement',
        'source' => 'interne',
        'status' => 'active',
        'application_deadline' => now()->addMonth(),
        'requirements' => "Minimum 3 ans d'expérience en développement web\nMaîtrise de PHP, Laravel, JavaScript\nConnaissance des bases de données MySQL\nExpérience avec Git et les méthodologies Agile\nBonnes compétences en communication",
        'benefits' => 'Télétravail partiel possible, Formation continue, Assurance santé, Projets à impact social',
        'salary_min' => 800000,
        'salary_max' => 1200000,
        'positions_available' => 2,
        'contact_email' => 'recrutement@iri-ucbc.org',
        'contact_phone' => '+237 123 456 789',
        'is_featured' => true,
    ]);

    echo "✅ Offre créée avec succès !\n";
    echo "ID: {$jobOffer->id}\n";
    echo "Titre: {$jobOffer->title}\n";
    echo "Type requirements: " . gettype($jobOffer->requirements) . "\n";
    echo "Requirements (tableau): " . json_encode($jobOffer->requirements) . "\n";
    echo "Requirements text: {$jobOffer->requirements_text}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN ===\n";
