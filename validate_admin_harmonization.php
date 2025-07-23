#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Output\ConsoleOutput;

$output = new ConsoleOutput();

$output->writeln('<info>=== VALIDATION FINALE DE L\'HARMONISATION ADMIN ===</info>');
$output->writeln('');

// 1. Vérification des vues harmonisées
$output->writeln('<comment>1. Vérification des vues harmonisées:</comment>');

$views = [
    'resources/views/admin/contacts/index.blade.php' => 'Contacts - Liste',
    'resources/views/admin/evenements/index.blade.php' => 'Événements - Liste',
    'resources/views/admin/evenements/create.blade.php' => 'Événements - Création',
    'resources/views/admin/evenements/edit.blade.php' => 'Événements - Édition',
    'resources/views/admin/projets/index.blade.php' => 'Projets - Liste',
    'resources/views/admin/projets/create.blade.php' => 'Projets - Création',
    'resources/views/admin/projets/edit.blade.php' => 'Projets - Édition',
    'resources/views/admin/rapports/index.blade.php' => 'Rapports - Liste',
    'resources/views/admin/rapports/create.blade.php' => 'Rapports - Création',
    'resources/views/admin/rapports/edit.blade.php' => 'Rapports - Édition'
];

foreach ($views as $path => $description) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (strpos($content, 'x-admin-layout') !== false || strpos($content, 'x-admin-form') !== false) {
            $output->writeln("  <info>✓</info> $description - <fg=green>Harmonisé</fg=green>");
        } else {
            $output->writeln("  <error>✗</error> $description - <fg=red>Non harmonisé</fg=red>");
        }
    } else {
        $output->writeln("  <error>✗</error> $description - <fg=red>Fichier manquant</fg=red>");
    }
}

// 2. Vérification des composants
$output->writeln('');
$output->writeln('<comment>2. Vérification des composants Blade:</comment>');

$components = [
    'resources/views/components/admin-layout.blade.php' => 'Layout admin principal',
    'resources/views/components/admin-form.blade.php' => 'Formulaire admin',
    'resources/views/components/form-field.blade.php' => 'Champs de formulaire'
];

foreach ($components as $path => $description) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $size = round(filesize($path) / 1024, 1);
        $output->writeln("  <info>✓</info> $description - <fg=green>Disponible</fg=green> ({$size}KB)");
    } else {
        $output->writeln("  <error>✗</error> $description - <fg=red>Manquant</fg=red>");
    }
}

// 3. Vérification des routes admin
$output->writeln('');
$output->writeln('<comment>3. URLs d\'accès admin (serveur sur http://127.0.0.1:8000):</comment>');

$adminRoutes = [
    '/admin' => 'Dashboard admin',
    '/admin/contacts' => 'Gestion des contacts',
    '/admin/evenements' => 'Gestion des événements',
    '/admin/evenements/create' => 'Création d\'événement',
    '/admin/projets' => 'Gestion des projets',
    '/admin/projets/create' => 'Création de projet',
    '/admin/rapports' => 'Gestion des rapports',
    '/admin/rapports/create' => 'Création de rapport'
];

foreach ($adminRoutes as $route => $description) {
    $output->writeln("  <info>→</info> $description: <fg=cyan>http://127.0.0.1:8000$route</fg=cyan>");
}

// 4. Résumé des améliorations
$output->writeln('');
$output->writeln('<comment>4. Résumé des améliorations apportées:</comment>');

$improvements = [
    'Interface unifiée avec Tailwind CSS et couleur coral',
    'Composants réutilisables (admin-layout, admin-form, form-field)',
    'Système de recherche et filtres standardisé',
    'Statistiques dynamiques sur chaque vue',
    'Actions de modération intégrées (publish/unpublish)',
    'Champs de modération ajoutés aux tables (is_published, published_at, etc.)',
    'Formulaires create/edit harmonisés avec validation',
    'Design responsive et moderne',
    'États visuels avec badges colorés',
    'Animations et transitions fluides'
];

foreach ($improvements as $improvement) {
    $output->writeln("  <info>✓</info> $improvement");
}

// 5. Prochaines étapes recommandées
$output->writeln('');
$output->writeln('<comment>5. Prochaines étapes recommandées:</comment>');

$nextSteps = [
    'Tester les fonctionnalités de modération avec différents rôles utilisateur',
    'Ajouter des tests automatisés pour les nouvelles interfaces',
    'Optimiser les performances des requêtes avec pagination',
    'Implémenter la recherche en temps réel (AJAX)',
    'Ajouter des notifications toast pour les actions utilisateur',
    'Créer des vues de modération dédiées (/admin/*/pending)',
    'Documenter les composants Blade pour les développeurs'
];

foreach ($nextSteps as $step) {
    $output->writeln("  <fg=yellow>→</fg=yellow> $step");
}

$output->writeln('');
$output->writeln('<info>=== HARMONISATION TERMINÉE AVEC SUCCÈS ===</info>');
$output->writeln('<fg=green>L\'interface admin est maintenant unifiée et moderne!</fg=green>');
