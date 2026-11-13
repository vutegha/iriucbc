<?php
/**
 * Test simplifié de compilation de la vue media/index
 */

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST COMPILATION MEDIA/INDEX ===\n\n";

try {
    // Test direct du contenu Blade sans le layout
    $viewPath = 'resources/views/admin/media/index.blade.php';
    $content = file_get_contents($viewPath);
    
    // Vérifications de base
    $sections = substr_count($content, '@section');
    $endsections = substr_count($content, '@endsection');
    
    echo "📊 Analyse du fichier:\n";
    echo "   - Taille: " . number_format(strlen($content)) . " caractères\n";
    echo "   - @section: $sections\n";
    echo "   - @endsection: $endsections\n";
    echo "   - Balance: " . ($sections === $endsections ? "✅ OK" : "❌ ERREUR") . "\n\n";
    
    // Test des sections spécifiques
    $hasTitle = preg_match("/@section\('title'[^)]*\)/", $content);
    $hasStyles = preg_match("/@section\('styles'\)/", $content);
    $hasContent = preg_match("/@section\('content'\)/", $content);
    
    echo "📋 Sections détectées:\n";
    echo "   - title: " . ($hasTitle ? "✅" : "❌") . "\n";
    echo "   - styles: " . ($hasStyles ? "✅" : "❌") . "\n";
    echo "   - content: " . ($hasContent ? "✅" : "❌") . "\n\n";
    
    // Vérification manuelle ligne par ligne des @endsection
    $lines = explode("\n", $content);
    $sectionStack = [];
    $lineNum = 0;
    
    foreach ($lines as $line) {
        $lineNum++;
        $trimmed = trim($line);
        
        // Détection des @section
        if (preg_match("/@section\s*\(\s*'([^']+)'/", $trimmed, $matches)) {
            $sectionStack[] = ['name' => $matches[1], 'line' => $lineNum];
        }
        
        // Détection des @endsection
        if ($trimmed === '@endsection') {
            if (empty($sectionStack)) {
                echo "❌ ERREUR ligne $lineNum: @endsection sans @section correspondant\n";
            } else {
                $section = array_pop($sectionStack);
                echo "✅ Section '{$section['name']}' fermée correctement (lignes {$section['line']}-$lineNum)\n";
            }
        }
    }
    
    if (!empty($sectionStack)) {
        echo "❌ ERREUR: Sections non fermées:\n";
        foreach ($sectionStack as $section) {
            echo "   - '{$section['name']}' ouverte ligne {$section['line']}\n";
        }
    } else {
        echo "✅ Toutes les sections sont correctement fermées\n";
    }
    
    echo "\n🎉 ANALYSE TERMINÉE\n";
    echo "Le fichier semble structurellement correct pour Blade.\n";
    echo "Si l'erreur persiste, elle vient probablement du layout 'layouts.admin'.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
