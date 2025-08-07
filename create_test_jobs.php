<?php

/**
 * Script pour créer des offres d'emploi de test
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\JobOffer;
use Illuminate\Support\Facades\DB;

echo "=== CRÉATION D'OFFRES D'EMPLOI DE TEST ===\n";

// Vérifier si la table existe et est accessible
try {
    $count = DB::table('job_offers')->count();
    echo "Nombre d'offres existantes: $count\n\n";
    
    // Données de test
    $testJobs = [
        [
            'title' => 'Spécialiste en Gouvernance Foncière',
            'description' => "Nous recherchons un spécialiste expérimenté en gouvernance foncière pour rejoindre notre équipe.\n\nResponsabilités:\n- Analyser les politiques foncières\n- Accompagner les communautés locales\n- Rédiger des rapports d'expertise\n- Participer aux formations\n\nNous offrons un environnement de travail stimulant et la possibilité de contribuer directement au développement des communautés.",
            'type' => 'temps_plein',
            'location' => 'Bukavu, RDC',
            'department' => 'Recherche et Développement',
            'source' => 'interne',
            'status' => 'active',
            'application_deadline' => date('Y-m-d', strtotime('+30 days')),
            'requirements' => json_encode([
                'Diplôme universitaire en droit, développement ou domaine connexe',
                'Minimum 3 ans d\'expérience en gouvernance foncière',
                'Excellente maîtrise du français et du swahili',
                'Capacité à travailler en équipe',
                'Disponibilité pour les missions sur le terrain'
            ]),
            'criteria' => json_encode([
                'Formation en droits de l\'homme (optionnel)',
                'Expérience avec les communautés rurales',
                'Compétences en recherche qualitative'
            ]),
            'benefits' => 'Assurance santé, formation continue, possibilités d\'évolution, environnement de travail collaboratif',
            'salary_min' => 800,
            'salary_max' => 1200,
            'positions_available' => 1,
            'contact_email' => 'recrutement@iri-ucbc.org',
            'is_featured' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'title' => 'Coordonnateur de Projet - Partenaire',
            'description' => "Notre partenaire recherche un coordonnateur de projet dynamique.\n\nMission:\n- Coordonner les activités du projet\n- Gérer les relations avec les bénéficiaires\n- Superviser l'équipe terrain\n- Produire les rapports périodiques",
            'type' => 'contrat',
            'location' => 'Goma, RDC',
            'department' => 'Gestion de Projet',
            'source' => 'partenaire',
            'partner_name' => 'ONG Développement Local',
            'status' => 'active',
            'application_deadline' => date('Y-m-d', strtotime('+15 days')),
            'requirements' => json_encode([
                'Bac+4 en gestion de projet ou équivalent',
                'Expérience en coordination d\'équipe',
                'Bonnes compétences en communication',
                'Maîtrise des outils bureautiques'
            ]),
            'salary_min' => 600,
            'salary_max' => 900,
            'positions_available' => 1,
            'contact_email' => 'jobs@partenairelocal.org',
            'is_featured' => false,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'title' => 'Stage en Recherche Socio-économique',
            'description' => "Opportunité de stage pour étudiant en fin de cursus.\n\nActivités:\n- Participation aux enquêtes terrain\n- Analyse de données\n- Rédaction de rapports\n- Support aux chercheurs seniors",
            'type' => 'stage',
            'location' => 'Bukavu, RDC',
            'department' => 'Recherche',
            'source' => 'interne',
            'status' => 'active',
            'application_deadline' => date('Y-m-d', strtotime('+45 days')),
            'requirements' => json_encode([
                'Étudiant en sciences sociales, économie ou domaine connexe',
                'Disponibilité pour 3-6 mois',
                'Intérêt pour la recherche de terrain',
                'Capacités rédactionnelles'
            ]),
            'benefits' => 'Indemnité de stage, formation pratique, certificat de stage',
            'positions_available' => 2,
            'contact_email' => 'stages@iri-ucbc.org',
            'is_featured' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    // Créer les offres
    foreach ($testJobs as $jobData) {
        $existing = JobOffer::where('title', $jobData['title'])->first();
        if (!$existing) {
            JobOffer::create($jobData);
            echo "✓ Créé: {$jobData['title']}\n";
        } else {
            echo "- Existe déjà: {$jobData['title']}\n";
        }
    }
    
    $newCount = DB::table('job_offers')->count();
    echo "\nNombre total d'offres après création: $newCount\n";
    echo "✓ Script terminé avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN ===\n";
