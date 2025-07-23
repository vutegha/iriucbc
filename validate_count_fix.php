<?php

echo "=== VALIDATION FINALE count() on null ===\n\n";

// Recherche de patterns potentiellement problématiques
$patterns = [
    // Patterns dangereux non protégés
    '/\$[a-zA-Z_]+\->[a-zA-Z_]+\->count\(\)(?!\s*\?\?\s*\d+)/' => 'Relation->count() non protégée',
    '/\@if\(\$[a-zA-Z_]+\->[a-zA-Z_]+\->count\(\)/' => 'Condition @if avec relation->count() non protégée',
    '/\{\{\s*\$[a-zA-Z_]+\->[a-zA-Z_]+\->count\(\)\s*\}\}/' => 'Affichage {{ relation->count() }} non protégé',
];

$files = glob('resources/views/**/*.blade.php', GLOB_BRACE);

$issues = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    foreach ($patterns as $pattern => $description) {
        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $lineNumber = substr_count(substr($content, 0, $match[1]), "\n") + 1;
                $issues[] = [
                    'file' => $file,
                    'line' => $lineNumber,
                    'pattern' => $match[0],
                    'description' => $description
                ];
            }
        }
    }
}

if (empty($issues)) {
    echo "✅ AUCUN PROBLÈME DÉTECTÉ !\n";
    echo "Tous les appels ->count() semblent être protégés.\n\n";
} else {
    echo "⚠️  PROBLÈMES POTENTIELS DÉTECTÉS :\n\n";
    
    foreach ($issues as $issue) {
        echo "📂 {$issue['file']}:{$issue['line']}\n";
        echo "   Pattern: {$issue['pattern']}\n";
        echo "   Type: {$issue['description']}\n\n";
    }
}

// Recherche spécifique de quelques patterns courants non protégés
echo "=== VÉRIFICATION PATTERNS SPÉCIFIQUES ===\n";

$specificChecks = [
    '->count() >' => 'Comparaison directe non protégée',
    '->count() <' => 'Comparaison directe non protégée', 
    '->count() ==' => 'Comparaison directe non protégée',
    '->count())' => 'Possible appel non protégé dans condition',
];

$totalIssues = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    foreach ($specificChecks as $search => $desc) {
        $count = substr_count($content, $search);
        if ($count > 0) {
            // Vérifier si c'est déjà protégé avec optional()
            $protectedCount = substr_count($content, "optional(");
            $lines = preg_split('/\r\n|\r|\n/', $content);
            
            foreach ($lines as $lineNum => $line) {
                if (strpos($line, $search) !== false && strpos($line, 'optional(') === false) {
                    echo "⚠️  {$file}:" . ($lineNum + 1) . " - $desc\n";
                    echo "   Ligne: " . trim($line) . "\n\n";
                    $totalIssues++;
                }
            }
        }
    }
}

if ($totalIssues === 0) {
    echo "✅ Aucun pattern spécifique problématique trouvé.\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "Fichiers vérifiés: " . count($files) . "\n";
echo "Problèmes trouvés: " . (count($issues) + $totalIssues) . "\n";

if (count($issues) + $totalIssues === 0) {
    echo "\n🎉 VALIDATION RÉUSSIE !\n";
    echo "Tous les appels ->count() sont maintenant sécurisés.\n";
    echo "L'erreur 'Call to a member function count() on null' devrait être éliminée.\n";
} else {
    echo "\n⚠️  Des corrections supplémentaires peuvent être nécessaires.\n";
}

?>
