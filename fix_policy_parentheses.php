<?php

$policyPath = 'c:\\xampp\\htdocs\\projets\\iriucbc\\app\\Policies\\';
$policyFiles = [
    'ActualitePolicy.php',
    'ProjetPolicy.php', 
    'PublicationPolicy.php',
    'EvenementPolicy.php',
    'ServicePolicy.php',
    'MediaPolicy.php'
];

echo "🔧 Correction des parenthèses en trop dans les policies...\n\n";

foreach ($policyFiles as $file) {
    $filePath = $policyPath . $file;
    
    if (!file_exists($filePath)) {
        echo "⚠️ Policy non trouvée: $file\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    $changes = 0;
    
    // Corriger les patterns avec parenthèse en trop
    $patterns = [
        // hasPermissionTo('permission', 'web')) || 
        "/hasPermissionTo\s*\(\s*['\"][^'\"]*['\"]\s*,\s*['\"]\s*web\s*['\"]\s*\)\s*\)\s*\|\|/",
        // hasPermissionTo('permission')) ||
        "/hasPermissionTo\s*\(\s*['\"][^'\"]*['\"]\s*\)\s*\)\s*\|\|/",
    ];
    
    foreach ($patterns as $pattern) {
        $newContent = preg_replace_callback($pattern, function($matches) {
            // Enlever la parenthèse en trop
            $corrected = str_replace(')) ||', ') ||', $matches[0]);
            return $corrected;
        }, $content);
        
        if ($newContent !== $content) {
            $changesCount = preg_match_all($pattern, $content);
            $changes += $changesCount;
            $content = $newContent;
        }
    }
    
    // Correction plus spécifique
    $content = preg_replace("/hasPermissionTo\s*\(\s*(['\"][^'\"]*['\"]\s*,\s*['\"]\s*web\s*['\"]\s*)\s*\)\s*\)/", "hasPermissionTo($1)", $content);
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✅ Corrigé: $file\n";
    } else {
        echo "ℹ️ Aucune correction nécessaire: $file\n";
    }
}

echo "\n🎉 Correction terminée!\n";
