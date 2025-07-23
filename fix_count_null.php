<?php

echo "=== CORRECTION AUTOMATIQUE count() on null ===\n\n";

// Fichiers à corriger
$files = [
    'resources/views/showservice_new.blade.php',
    'resources/views/partials/navbar.blade.php',
    'resources/views/partials/menu.blade.php',
    'resources/views/actualites.blade.php',
    'resources/views/projets.blade.php',
    'resources/views/publications.blade.php',
    'resources/views/index.blade.php',
    'resources/views/showactualite.blade.php',
    'resources/views/search_results.blade.php',
    'resources/views/showpublication.blade.php',
    'resources/views/galerie.blade.php',
    'resources/views/galerie_new.blade.php',
    'resources/views/showprojet.blade.php'
];

// Patterns à corriger
$replacements = [
    // Relations de service
    '$service->projets->count()' => 'optional($service->projets)->count() ?? 0',
    '$service->actualites->count()' => 'optional($service->actualites)->count() ?? 0',
    
    // Relations d'autres modèles
    '$publication->auteurs->count()' => 'optional($publication->auteurs)->count() ?? 0',
    '$projet->medias->count()' => 'optional($projet->medias)->count() ?? 0',
    '$newsletter->preferences->count()' => 'optional($newsletter->preferences)->count() ?? 0',
    
    // Collections générales
    '@if($services->count()' => '@if(optional($services)->count()',
    '@if($actualites->count()' => '@if(optional($actualites)->count()',
    '@if($projets->count()' => '@if(optional($projets)->count()',
    '@if($publications->count()' => '@if(optional($publications)->count()',
    '@if($results->count()' => '@if(optional($results)->count()',
    '@if($medias->count()' => '@if(optional($medias)->count()',
    '@if($partenaires->count()' => '@if(optional($partenaires)->count()',
    '@if($menuServices->count()' => '@if(optional($menuServices)->count()',
    
    // Conditions avec isset déjà protégées
    '@if(isset($menuServices) && $menuServices->count()' => '@if(isset($menuServices) && optional($menuServices)->count()',
    '@if(isset($medias) && $medias->count()' => '@if(isset($medias) && optional($medias)->count()',
    '@if($recentActualites && $recentActualites->count()' => '@if($recentActualites && optional($recentActualites)->count()',
    '@if($autresPublications->count()' => '@if(optional($autresPublications)->count()',
    '@if($autresEvenements->count()' => '@if(optional($autresEvenements)->count()',
    '@if($prochainsEvenements->count()' => '@if(optional($prochainsEvenements)->count()',
];

foreach ($files as $file) {
    $fullPath = $file;
    
    if (!file_exists($fullPath)) {
        echo "⚠️  Fichier non trouvé: $file\n";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    $originalContent = $content;
    $changed = false;
    
    foreach ($replacements as $search => $replace) {
        if (strpos($content, $search) !== false) {
            $content = str_replace($search, $replace, $content);
            $changed = true;
            echo "✅ Corrigé dans $file: $search → $replace\n";
        }
    }
    
    // Corrections spécifiques pour les conditions @if avec ->count()
    $patterns = [
        '/\@if\(\$([a-zA-Z_]+)->count\(\)/' => '@if(optional($${1})->count()',
        '/\{\{ \$([a-zA-Z_]+)->count\(\) \}\}/' => '{{ optional($${1})->count() ?? 0 }}',
        '/\$([a-zA-Z_]+)->([a-zA-Z_]+)->count\(\) >/' => 'optional($${1}->${2})->count() >',
        '/\$([a-zA-Z_]+)->([a-zA-Z_]+)->count\(\)/' => 'optional($${1}->${2})->count() ?? 0',
    ];
    
    foreach ($patterns as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $content = $newContent;
            $changed = true;
            echo "✅ Pattern corrigé dans $file: $pattern\n";
        }
    }
    
    if ($changed) {
        file_put_contents($fullPath, $content);
        echo "📝 Fichier sauvegardé: $file\n\n";
    } else {
        echo "ℹ️  Aucune correction nécessaire: $file\n";
    }
}

echo "\n=== CORRECTIONS TERMINÉES ===\n";
echo "Toutes les occurrences de ->count() ont été protégées avec optional().\n";
echo "L'erreur 'Call to a member function count() on null' devrait être résolue.\n";

?>
