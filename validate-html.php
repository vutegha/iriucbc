<?php
/**
 * Script de validation des balises HTML dans les fichiers Blade
 */

function validateHtmlTags($content) {
    $errors = [];
    $stack = [];
    $selfClosingTags = ['img', 'input', 'br', 'hr', 'meta', 'link', 'area', 'base', 'col', 'embed', 'source', 'track', 'wbr'];
    
    // Supprimer les commentaires HTML et les blocs PHP/Blade
    $content = preg_replace('/<!--.*?-->/s', '', $content);
    $content = preg_replace('/{{.*?}}/s', '', $content);
    $content = preg_replace('/{!!.*?!!}/s', '', $content);
    $content = preg_replace('/@\w+.*$/m', '', $content);
    
    // Extraire toutes les balises
    preg_match_all('/<\/?([a-zA-Z][a-zA-Z0-9]*)\b[^>]*>/i', $content, $matches, PREG_OFFSET_CAPTURE);
    
    foreach ($matches[0] as $index => $match) {
        $fullTag = $match[0];
        $tagName = strtolower($matches[1][$index][0]);
        $position = $match[1];
        
        // Ignorer les balises auto-fermantes
        if (in_array($tagName, $selfClosingTags) || substr($fullTag, -2) === '/>') {
            continue;
        }
        
        // Balise fermante
        if (substr($fullTag, 1, 1) === '/') {
            if (empty($stack)) {
                $errors[] = "Balise fermante '$fullTag' sans balise ouvrante correspondante à la position $position";
            } else {
                $lastOpen = array_pop($stack);
                if ($lastOpen['name'] !== $tagName) {
                    $errors[] = "Balise fermante '$fullTag' ne correspond pas à la balise ouvrante '{$lastOpen['tag']}' à la position $position";
                }
            }
        } 
        // Balise ouvrante
        else {
            $stack[] = [
                'name' => $tagName,
                'tag' => $fullTag,
                'position' => $position
            ];
        }
    }
    
    // Vérifier les balises non fermées
    while (!empty($stack)) {
        $unclosed = array_pop($stack);
        $errors[] = "Balise ouvrante '{$unclosed['tag']}' non fermée à la position {$unclosed['position']}";
    }
    
    return $errors;
}

// Lire le fichier
$filePath = 'resources/views/index.blade.php';
$content = file_get_contents($filePath);

echo "=== VALIDATION DES BALISES HTML ===\n";
echo "Fichier: $filePath\n\n";

$errors = validateHtmlTags($content);

if (empty($errors)) {
    echo "✅ SUCCÈS: Toutes les balises HTML sont correctement équilibrées!\n";
} else {
    echo "❌ ERREURS DÉTECTÉES:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}

echo "\n=== STATISTIQUES ===\n";
echo "Taille du fichier: " . number_format(strlen($content)) . " caractères\n";
echo "Nombre de lignes: " . count(explode("\n", $content)) . "\n";

// Compter les balises principales
$divCount = substr_count($content, '<div') - substr_count($content, '</div>');
$sectionCount = substr_count($content, '<section') - substr_count($content, '</section>');

echo "Balance des <div>: " . ($divCount === 0 ? "✅ Équilibré" : "❌ Déséquilibré ($divCount)") . "\n";
echo "Balance des <section>: " . ($sectionCount === 0 ? "✅ Équilibré" : "❌ Déséquilibré ($sectionCount)") . "\n";
?>
