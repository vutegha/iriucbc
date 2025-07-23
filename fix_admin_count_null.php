<?php

echo "=== CORRECTION VUES ADMIN - count() on null ===\n\n";

// Fichiers admin spécifiques à corriger
$adminFiles = [
    'resources/views/admin/publication/show.blade.php',
    'resources/views/admin/service/index.blade.php', 
    'resources/views/admin/newsletter/index.blade.php',
    'resources/views/admin/newsletter/show.blade.php',
    'resources/views/admin/dashboard.blade.php',
    'resources/views/admin/publication/index-tailwind.blade.php',
    'resources/views/admin/actualite/index-tailwind.blade.php',
    'resources/views/admin/job-offers/index.blade.php',
    'resources/views/admin/job-applications/index.blade.php'
];

// Patterns spécifiques pour les vues admin
$adminReplacements = [
    '$publication->auteurs->count()' => 'optional($publication->auteurs)->count() ?? 0',
    '$newsletter->preferences->count()' => 'optional($newsletter->preferences)->count() ?? 0', 
    '$pub->auteurs->count()' => 'optional($pub->auteurs)->count() ?? 0',
    '@if($services->count() >' => '@if(optional($services)->count() >',
    '@if($newsletters->count() >' => '@if(optional($newsletters)->count() >',
    '@if($prochainsEvenements->count() >' => '@if(optional($prochainsEvenements)->count() >',
    '@if($actualites->count() >' => '@if(optional($actualites)->count() >',
    '@if($jobOffers->count() >' => '@if(optional($jobOffers)->count() >',
    '@if($applications->count() >' => '@if(optional($applications)->count() >',
];

foreach ($adminFiles as $file) {
    if (!file_exists($file)) {
        echo "⚠️  Fichier non trouvé: $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $changed = false;
    
    foreach ($adminReplacements as $search => $replace) {
        if (strpos($content, $search) !== false) {
            $content = str_replace($search, $replace, $content);
            $changed = true;
            echo "✅ Corrigé dans $file: $search → $replace\n";
        }
    }
    
    // Corrections supplémentaires avec regex pour les patterns complexes
    $regexPatterns = [
        // Relations dans les conditions
        '/\@if\(\$([a-zA-Z_]+)->([a-zA-Z_]+)->count\(\) > (\d+)\)/' => '@if(optional($${1}->${2})->count() > ${3})',
        // Affichage direct de count dans les templates
        '/\{\{ \$([a-zA-Z_]+)->([a-zA-Z_]+)->count\(\) \}\}/' => '{{ optional($${1}->${2})->count() ?? 0 }}',
        // Conditions comparatives
        '/\$([a-zA-Z_]+)->([a-zA-Z_]+)->count\(\) > (\d+)/' => 'optional($${1}->${2})->count() > ${3}',
    ];
    
    foreach ($regexPatterns as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $content = $newContent;
            $changed = true;
            echo "✅ Pattern regex corrigé dans $file\n";
        }
    }
    
    if ($changed) {
        file_put_contents($file, $content);
        echo "📝 Fichier admin sauvegardé: $file\n\n";
    } else {
        echo "ℹ️  Aucune correction nécessaire: $file\n";
    }
}

echo "\n=== VÉRIFICATION FINALE ===\n";

// Recherche de patterns restants potentiellement problématiques
$searchPatterns = [
    '->count() >',
    '->count() <', 
    '->count() ==',
    '->count() !='
];

foreach ($searchPatterns as $pattern) {
    $command = "grep -r \"$pattern\" resources/views/ --include=\"*.blade.php\" 2>/dev/null || true";
    $output = shell_exec($command);
    
    if (!empty(trim($output))) {
        echo "⚠️  Pattern '$pattern' trouvé encore:\n";
        echo $output . "\n";
    }
}

echo "\n=== CORRECTIONS ADMIN TERMINÉES ===\n";

?>
