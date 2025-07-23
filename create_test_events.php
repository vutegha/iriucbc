<?php

// Script temporaire pour créer des événements de test
use App\Models\Evenement;

// Événement à venir
Evenement::create([
    'titre' => 'Conférence internationale sur les droits de l\'homme',
    'resume' => 'Une conférence importante réunissant experts et activistes pour discuter des enjeux actuels des droits humains en Afrique centrale.',
    'description' => 'Cette conférence internationale rassemblera des experts, des militants et des décideurs pour examiner les défis et opportunités en matière de droits de l\'homme dans la région. Les discussions porteront sur la justice transitionnelle, la protection des défenseurs des droits humains, et l\'impact des conflits sur les populations civiles.',
    'image' => null,
    'date_debut' => '2025-08-15',
    'date_fin' => '2025-08-17',
    'lieu' => 'Centre de Conférences de Bujumbura',
    'rapport_url' => null
]);

// Événement en cours
Evenement::create([
    'titre' => 'Formation des journalistes sur la couverture des droits humains',
    'resume' => 'Atelier de formation destiné aux journalistes locaux pour améliorer leur couverture des questions de droits de l\'homme.',
    'description' => 'Un programme de formation intensive pour renforcer les capacités des journalistes dans le traitement éthique et professionnel des sujets liés aux droits humains.',
    'image' => null,
    'date_debut' => '2025-07-17',
    'date_fin' => '2025-07-20',
    'lieu' => 'Salle de formation IRI',
    'rapport_url' => null
]);

// Événement passé avec rapport
Evenement::create([
    'titre' => 'Forum national sur la réconciliation',
    'resume' => 'Grand forum réunissant la société civile, le gouvernement et les communautés pour discuter des mécanismes de réconciliation.',
    'description' => 'Ce forum a permis de créer un dialogue constructif entre les différentes parties prenantes pour identifier les voies vers une réconciliation durable au Burundi.',
    'image' => null,
    'date_debut' => '2025-06-10',
    'date_fin' => '2025-06-12',
    'lieu' => 'Palais des Congrès, Bujumbura',
    'rapport_url' => 'https://example.com/rapport-forum-reconciliation.pdf'
]);

// Événement passé récent
Evenement::create([
    'titre' => 'Atelier sur la protection des femmes défenseures',
    'resume' => 'Atelier de renforcement des capacités pour la protection et la sécurité des femmes défenseures des droits humains.',
    'description' => 'Session de formation spécialisée axée sur les stratégies de protection et de sécurité pour les femmes qui défendent les droits humains.',
    'image' => null,
    'date_debut' => '2025-07-05',
    'date_fin' => '2025-07-05',
    'lieu' => 'Centre communautaire Kamenge',
    'rapport_url' => 'https://example.com/rapport-atelier-femmes.pdf'
]);

// Événement futur
Evenement::create([
    'titre' => 'Campagne de sensibilisation dans les écoles',
    'resume' => 'Programme de sensibilisation sur les droits de l\'enfant dans les établissements scolaires de la capitale.',
    'description' => 'Une série de visites dans les écoles pour sensibiliser les élèves et enseignants aux droits de l\'enfant et à la prévention de la violence en milieu scolaire.',
    'image' => null,
    'date_debut' => '2025-09-01',
    'date_fin' => '2025-09-30',
    'lieu' => 'Écoles de Bujumbura Mairie',
    'rapport_url' => null
]);

echo "5 événements de test créés avec succès!\n";
